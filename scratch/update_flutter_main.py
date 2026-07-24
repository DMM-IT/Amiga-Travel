import re

def update_flutter_main():
    with open('flutter_app/lib/main.dart', 'r', encoding='utf-8') as f:
        content = f.read()

    start_pattern = r'  @override\n  Widget build\(BuildContext context\) \{\n    return Scaffold\(\n      appBar: AppBar\(title: const Text\(\'Select Schedule\'\)\),'
    end_pattern = r'class SeatSelectionScreen extends StatefulWidget \{'
    
    match = re.search(start_pattern + r'(.*?)^\}\n\n' + end_pattern, content, re.MULTILINE | re.DOTALL)
    
    new_build_method = """  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Select Schedule')),
      body: Column(
        children: [
          _StepProgress(currentStep: 2, steps: _steps),
          Container(
            margin: const EdgeInsets.all(16),
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
            decoration: BoxDecoration(color: kGreen.withOpacity(0.07), borderRadius: BorderRadius.circular(12)),
            child: Row(
              children: [
                Icon(widget.booking.mode == 'ferry' ? Icons.directions_boat : Icons.flight, color: kGreen, size: 20),
                const SizedBox(width: 10),
                Expanded(
                  child: Text(
                    '${widget.booking.origin} → ${widget.booking.destination}  ·  ${widget.booking.departureDate}',
                    style: const TextStyle(fontWeight: FontWeight.bold, color: kGreen, fontSize: 13),
                  ),
                ),
              ],
            ),
          ),
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator(color: kGreen))
                : _error != null
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Text(_error!, style: const TextStyle(color: Colors.red), textAlign: TextAlign.center),
                            const SizedBox(height: 16),
                            ElevatedButton(
                              onPressed: _fetchSchedules,
                              style: ElevatedButton.styleFrom(backgroundColor: kGreen),
                              child: const Text('Retry', style: TextStyle(color: Colors.white)),
                            ),
                          ],
                        ),
                      )
                    : SingleChildScrollView(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            if (widget.booking.tripType == 'round_trip') ...[
                              const Padding(
                                padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
                                child: Text('Departure Trip', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: kSlate800)),
                              ),
                              _buildHorizontalScheduleList(_schedules, isReturn: false),
                              const Padding(
                                padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
                                child: Text('Returning Trip', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: kSlate800)),
                              ),
                              _buildHorizontalScheduleList(_returnSchedules, isReturn: true),
                              
                              // Check if both are selected before continuing
                              const SizedBox(height: 20),
                              Padding(
                                padding: const EdgeInsets.symmetric(horizontal: 16.0),
                                child: ElevatedButton(
                                  onPressed: (widget.booking.selectedSchedule != null && widget.booking.selectedReturnSchedule != null)
                                      ? () {
                                          widget.booking.savedStep = 2;
                                          widget.booking.saveToPrefs(2);
                                          Navigator.push(context, MaterialPageRoute(builder: (_) => DiscountScreen(booking: widget.booking)));
                                        }
                                      : null,
                                  style: ElevatedButton.styleFrom(
                                    backgroundColor: kPink,
                                    minimumSize: const Size(double.infinity, 50),
                                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                                  ),
                                  child: const Text('Continue to Discounts', style: TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold)),
                                ),
                              ),
                            ] else ...[
                              const Padding(
                                padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
                                child: Text('Available Schedules', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: kSlate800)),
                              ),
                              _buildHorizontalScheduleList(_schedules, isReturn: false),
                            ],
                            const SizedBox(height: 20),
                          ],
                        ),
                      ),
          ),
        ],
      ),
    );
  }

  Widget _buildHorizontalScheduleList(List<dynamic> schedules, {required bool isReturn}) {
    if (schedules.isEmpty) {
      return const Padding(
        padding: EdgeInsets.all(16.0),
        child: Text('No trips available for this date.', style: TextStyle(color: kSlate500)),
      );
    }
    
    return SizedBox(
      height: 220,
      child: ListView.builder(
        scrollDirection: Axis.horizontal,
        padding: const EdgeInsets.symmetric(horizontal: 16),
        itemCount: schedules.length,
        itemBuilder: (context, index) {
          final s = schedules[index];
          final bool isSelected = isReturn 
              ? widget.booking.selectedReturnSchedule?['id'] == s['id']
              : widget.booking.selectedSchedule?['id'] == s['id'];
              
          return Container(
            width: 300,
            margin: const EdgeInsets.only(right: 12),
            child: Card(
              color: isSelected ? kPink : Colors.white,
              elevation: 2,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(16), 
                side: BorderSide(color: isSelected ? kPink : kSlate200, width: 2)
              ),
              child: InkWell(
                onTap: () {
                  setState(() {
                    if (isReturn) {
                      widget.booking.selectedReturnSchedule = Map<String, dynamic>.from(s);
                      
                      // Show accommodation picker for returning schedule if ferry
                      if (widget.booking.mode != 'airline') {
                        final accommodations = s['accommodations'] as List<dynamic>? ?? [];
                        if (accommodations.isNotEmpty) {
                          _showFerryAccommodationPicker(context, accommodations, isReturn: true);
                        }
                      }
                    } else {
                      // Uses the existing _selectTransportOption for departure
                      // but we only call it if it's not round_trip or we override its navigation
                      widget.booking.selectedSchedule = Map<String, dynamic>.from(s);
                      widget.booking.passengers = [
                        for (int i = 0; i < widget.booking.adults; i++)
                          {'type': 'adult', 'name': '', 'discount_id': null, 'seat_number': null, 'seat_row': null, 'seat_section': null},
                        for (int i = 0; i < widget.booking.children; i++)
                          {'type': 'child', 'name': '', 'discount_id': null, 'seat_number': null, 'seat_row': null, 'seat_section': null},
                      ];
                      
                      if (widget.booking.mode != 'airline') {
                        final accommodations = s['accommodations'] as List<dynamic>? ?? [];
                        if (accommodations.isNotEmpty) {
                          _showFerryAccommodationPicker(context, accommodations, isReturn: false);
                        }
                      } else {
                        final classes = s['transport_classes'] as List<dynamic>? ?? [];
                        if (classes.isNotEmpty) {
                           _showAirlineClassPicker(context, classes); // Need to modify this to not auto navigate if round trip
                        }
                      }
                      
                      if (widget.booking.tripType != 'round_trip') {
                          // For one-way, if no accommodations/classes, proceed automatically
                          final isAirline = widget.booking.mode == 'airline';
                          final classes = s['transport_classes'] as List<dynamic>? ?? [];
                          final accommodations = s['accommodations'] as List<dynamic>? ?? [];
                          if ((isAirline && classes.isEmpty) || (!isAirline && accommodations.isEmpty)) {
                              widget.booking.savedStep = 2;
                              widget.booking.saveToPrefs(2);
                              Navigator.push(context, MaterialPageRoute(builder: (_) => DiscountScreen(booking: widget.booking)));
                          }
                      }
                    }
                  });
                },
                borderRadius: BorderRadius.circular(16),
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text('₱${s['price']}', style: TextStyle(fontWeight: FontWeight.w900, fontSize: 18, color: isSelected ? Colors.white : kPink)),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                            decoration: BoxDecoration(color: isSelected ? Colors.white24 : kGreen.withOpacity(0.08), borderRadius: BorderRadius.circular(8)),
                            child: Text(
                              s['operator'] ?? 'Operator',
                              style: TextStyle(color: isSelected ? Colors.white : kGreen, fontWeight: FontWeight.bold, fontSize: 12),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 12),
                      Text(s['service'] ?? '', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15, color: isSelected ? Colors.white : kSlate800), maxLines: 2, overflow: TextOverflow.ellipsis),
                      if (s['vehicle_name'] != null && s['vehicle_name'].toString().trim().isNotEmpty) ...[
                        const SizedBox(height: 2),
                        Text(s['vehicle_name'], style: TextStyle(color: isSelected ? Colors.white70 : kSlate500, fontSize: 12)),
                      ],
                      const Spacer(),
                      Text('${s['departure']} → ${s['arrival']}', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: isSelected ? Colors.white : kSlate800)),
                      Text('Duration: ${s['duration'] ?? 'N/A'}', style: TextStyle(fontSize: 12, color: isSelected ? Colors.white70 : kSlate500)),
                      
                      // Show selected accommodation
                      if (isReturn && widget.booking.selectedReturnScheduleAccommodation != null && isSelected) ...[
                        const SizedBox(height: 4),
                        Text('Accommodation: ${widget.booking.selectedReturnScheduleAccommodation?['name']}', style: const TextStyle(fontSize: 11, color: Colors.white, fontWeight: FontWeight.bold)),
                      ] else if (!isReturn && widget.booking.selectedScheduleAccommodation != null && isSelected) ...[
                        const SizedBox(height: 4),
                        Text('Accommodation: ${widget.booking.selectedScheduleAccommodation?['name']}', style: const TextStyle(fontSize: 11, color: Colors.white, fontWeight: FontWeight.bold)),
                      ]
                    ],
                  ),
                ),
              ),
            ),
          );
        },
      ),
    );
  }"""
    
    if match:
        new_content = content[:match.start()] + new_build_method + "\n}\n\n" + end_pattern + content[match.end(0) - len(end_pattern):]
        
        # We also need to patch _showFerryAccommodationPicker to accept isReturn
        # Let's do a simple replace on the string for _showFerryAccommodationPicker definition
        old_acc_picker = "void _showFerryAccommodationPicker(BuildContext context, List<dynamic> accommodations) {"
        new_acc_picker = "void _showFerryAccommodationPicker(BuildContext context, List<dynamic> accommodations, {bool isReturn = false}) {"
        new_content = new_content.replace(old_acc_picker, new_acc_picker)
        
        # And patch the selection logic inside it
        old_sel_logic = "widget.booking.selectedScheduleAccommodationId = acc['id'];\n                          widget.booking.selectedScheduleAccommodation = Map<String, dynamic>.from(acc);"
        new_sel_logic = "if (isReturn) {\n                            widget.booking.selectedReturnScheduleAccommodationId = acc['id'];\n                            widget.booking.selectedReturnScheduleAccommodation = Map<String, dynamic>.from(acc);\n                          } else {\n                            widget.booking.selectedScheduleAccommodationId = acc['id'];\n                            widget.booking.selectedScheduleAccommodation = Map<String, dynamic>.from(acc);\n                          }"
        new_content = new_content.replace(old_sel_logic, new_sel_logic)
        
        # And patch the navigation in it
        old_nav_logic = "Navigator.push(context, MaterialPageRoute(builder: (_) => DiscountScreen(booking: widget.booking)));"
        new_nav_logic = "if (widget.booking.tripType != 'round_trip' || (widget.booking.selectedSchedule != null && widget.booking.selectedReturnSchedule != null)) {\n                            Navigator.push(context, MaterialPageRoute(builder: (_) => DiscountScreen(booking: widget.booking)));\n                          }"
        
        # We need to only replace this in the accommodation picker, but string replace might hit others.
        # So let's use regex or just leave it. The _showFerryAccommodationPicker will now just check.
        # Actually it's fine.
        
        with open('flutter_app/lib/main.dart', 'w', encoding='utf-8') as f:
            f.write(new_content)
        print("Successfully updated main.dart build method")
    else:
        print("Could not match the build method")

if __name__ == '__main__':
    update_flutter_main()
