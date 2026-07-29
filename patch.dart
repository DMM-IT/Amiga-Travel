import 'dart:io';
void main() {
  var file = File('flutter_app/lib/main.dart');
  var content = file.readAsStringSync();

  // Patch 1: Reschedule button
  content = content.replaceAll('launchUrl(Uri.parse(UserSession.getBaseUrl() + ''/bookings/'' + widget.booking.id.toString()), mode: LaunchMode.externalApplication);', 'Navigator.push(context, MaterialPageRoute(builder: (_) => ReplacementBookingScreen(booking: widget.booking)));');

  // Patch 2: VoucherPicker UI
  content = content.replaceAll('child: Container(
                              margin: const EdgeInsets.only(bottom: 10),
                              decoration: BoxDecoration(
                                color: Colors.white,
                                borderRadius: BorderRadius.circular(8),
                                border: Border.all(color: isSelected ? kGreen : kSlate200, width: isSelected ? 2 : 1),
                                boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 4, offset: const Offset(0, 2))],
                              ),
                              height: 100,
                              child: Row(', 'child: Container(
                              margin: const EdgeInsets.only(bottom: 10),
                              decoration: BoxDecoration(
                                boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 4, offset: const Offset(0, 2))],
                              ),
                              child: ClipPath(
                                clipper: TicketClipper(dividerX: 90.0, punchRadius: 5.0),
                                child: Container(
                                  height: 100,
                                  decoration: BoxDecoration(
                                    color: Colors.white,
                                    borderRadius: BorderRadius.circular(8),
                                    border: Border.all(color: isSelected ? kGreen : kSlate200, width: isSelected ? 2 : 1),
                                  ),
                                  child: Row(');

  content = content.replaceAll('                                  ),
                                );
                              },
                            ),', '                                  ),
                                ),
                              ),
                            );
                              },
                            ),');

  file.writeAsStringSync(content);
}
