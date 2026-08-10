
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

import 'main.dart';

class ReplacementBookingScreen extends StatefulWidget {
  final dynamic booking;
  const ReplacementBookingScreen({super.key, required this.booking});

  @override
  State<ReplacementBookingScreen> createState() => _ReplacementBookingScreenState();
}

class _ReplacementBookingScreenState extends State<ReplacementBookingScreen> {
  bool _isLoading = true;
  String _error = '';
  List<dynamic> _schedules = [];
  dynamic _selectedSchedule;

  @override
  void initState() {
    super.initState();
    _fetchAvailableSchedules();
  }

  Future<void> _fetchAvailableSchedules() async {
    try {
      final res = await http.get(
        Uri.parse('/api/schedules/realtime?origin_id=&destination_id='),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer '
        },
      );
      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['status'] == 'success') {
        setState(() {
          final raw = data['data'];
          _schedules = raw is List ? raw : (raw is Map ? raw.values.toList() : []);
          _isLoading = false;
        });
      } else {
        setState(() {
          _error = 'Failed to load schedules.';
          _isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        _error = 'Network error.';
        _isLoading = false;
      });
    }
  }

  Future<void> _submitReplacement() async {
    if (_selectedSchedule == null) return;
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (ctx) => const Center(child: CircularProgressIndicator(color: kPink)),
    );

    try {
      final res = await http.post(
        Uri.parse('/api/bookings//submit-replacement'),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'Authorization': 'Bearer '
        },
        body: jsonEncode({
          'email': widget.booking['client_email'],
          'dep_date': _selectedSchedule['departure_time'].toString().substring(0, 10),
          'dep_schedule_id': _selectedSchedule['id'],
        }),
      );
      if (!mounted) return;
      Navigator.pop(context); // pop loading
      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['status'] == 'success') {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(data['message']), backgroundColor: kGreen));
        if (!mounted) return;
        Navigator.pop(context, true); // pop back to details
      } else {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(data['message'] ?? 'Error occurred'), backgroundColor: Colors.red));
      }
    } catch (e) {
      if (!mounted) return;
      Navigator.pop(context);
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Network error'), backgroundColor: Colors.red));
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: kBgLight,
      appBar: AppBar(
        title: const Text('Replacement Booking'),
        backgroundColor: kGreen,
        foregroundColor: Colors.white,
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator(color: kPink))
          : _error.isNotEmpty
              ? Center(child: Text(_error, style: const TextStyle(color: Colors.red)))
              : ListView(
                  padding: const EdgeInsets.all(16),
                  children: [
                    Container(
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(color: Colors.red.shade50, border: Border.all(color: Colors.red.shade200), borderRadius: BorderRadius.circular(8)),
                      child: const Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: [
                              Icon(Icons.warning_amber_rounded, color: Colors.red),
                              SizedBox(width: 8),
                              Text('Unavoidable Schedule Disruption', style: TextStyle(color: Colors.red, fontWeight: FontWeight.bold, fontSize: 16)),
                            ],
                          ),
                          SizedBox(height: 8),
                          Text('We apologize for the inconvenience. Your original schedule was disrupted. Please select a replacement schedule below for free.', style: TextStyle(color: Colors.red)),
                        ],
                      ),
                    ),
                    const SizedBox(height: 24),
                    const Text('Available Replacement Schedules', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 12),
                    ..._schedules.map((s) {
                      final isSelected = _selectedSchedule != null && _selectedSchedule['id'] == s['id'];
                      return GestureDetector(
                        onTap: () => setState(() => _selectedSchedule = s),
                        child: Container(
                          margin: const EdgeInsets.only(bottom: 12),
                          padding: const EdgeInsets.all(16),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(8),
                            border: Border.all(color: isSelected ? kPink : kSlate200, width: isSelected ? 2 : 1),
                          ),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(s['service_name'] ?? 'Economy', style: const TextStyle(fontWeight: FontWeight.bold)),
                              const SizedBox(height: 8),
                              Row(
                                children: [
                                  Text(s['formatted_departure'] ?? s['departure_time'].toString().substring(11, 16), style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                                  const SizedBox(width: 8),
                                  const Icon(Icons.arrow_right_alt, color: kGreen),
                                  const SizedBox(width: 8),
                                  Text(s['formatted_arrival'] ?? s['arrival_time'].toString().substring(11, 16), style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                                ],
                              ),
                              const SizedBox(height: 8),
                              Text(s['departure_time'].toString().substring(0, 10), style: const TextStyle(color: kSlate600)),
                            ],
                          ),
                        ),
                      );
                    }),
                    if (_schedules.isEmpty)
                      const Padding(padding: EdgeInsets.symmetric(vertical: 32), child: Center(child: Text('No available schedules found.'))),
                  ],
                ),
      bottomNavigationBar: _selectedSchedule == null
          ? null
          : Container(
              padding: const EdgeInsets.all(16),
              color: Colors.white,
              child: ElevatedButton(
                onPressed: _submitReplacement,
                style: ElevatedButton.styleFrom(backgroundColor: kGreen, foregroundColor: Colors.white, padding: const EdgeInsets.symmetric(vertical: 16)),
                child: const Text('Confirm Replacement', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
              ),
            ),
    );
  }
}

