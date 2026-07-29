
import os

with open('flutter_app/lib/main.dart', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update Reschedule button
old_btn = '''launchUrl(Uri.parse(UserSession.getBaseUrl() + '/bookings/' + widget.booking.id.toString()), mode: LaunchMode.externalApplication);'''
new_btn = '''Navigator.push(context, MaterialPageRoute(builder: (_) => ReplacementBookingScreen(booking: widget.booking)));'''
content = content.replace(old_btn, new_btn)

# 2. VouchersScreen styling
old_vs = '''                                child: Container(
                                  margin: const EdgeInsets.only(bottom: 12),
                                  height: 110,
                                  decoration: BoxDecoration(
                                    color: Colors.white,
                                    borderRadius: BorderRadius.circular(8),
                                    boxShadow: [
                                      BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 6, offset: const Offset(0, 3)),
                                    ],
                                  ),
                                  child: Row('''

new_vs = '''                                child: Container(
                                  margin: const EdgeInsets.only(bottom: 12),
                                  decoration: BoxDecoration(
                                    boxShadow: [
                                      BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 6, offset: const Offset(0, 3)),
                                    ],
                                  ),
                                  child: ClipPath(
                                    clipper: TicketClipper(dividerX: 100.0, punchRadius: 6.0),
                                    child: Container(
                                      height: 110,
                                      decoration: BoxDecoration(
                                        color: Colors.white,
                                        borderRadius: BorderRadius.circular(8),
                                      ),
                                      child: Row('''
content = content.replace(old_vs, new_vs)

old_vs_end = '''                                      ),
                                    ],
                                  ),
                                );
                              },
                            ),'''
new_vs_end = '''                                      ),
                                    ],
                                  ),
                                ),
                              ),
                            );
                              },
                            ),'''
content = content.replace(old_vs_end, new_vs_end)

# 3. VoucherPickerScreen styling
old_vps = '''                            child: Container(
                              margin: const EdgeInsets.only(bottom: 10),
                              decoration: BoxDecoration(
                                color: Colors.white,
                                borderRadius: BorderRadius.circular(8),
                                border: Border.all(color: isSelected ? kGreen : kSlate200, width: isSelected ? 2 : 1),
                                boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 4, offset: const Offset(0, 2))],
                              ),
                              height: 100,
                              child: Row('''

new_vps = '''                            child: Container(
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
                                  child: Row('''
content = content.replace(old_vps, new_vps)

old_vps_end = '''                                  ),
                                );
                              },
                            ),'''
new_vps_end = '''                                  ),
                                ),
                              ),
                            );
                              },
                            ),'''
content = content.replace(old_vps_end, new_vps_end)


with open('flutter_app/lib/main.dart', 'w', encoding='utf-8') as f:
    f.write(content)

