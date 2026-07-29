
import os

with open('flutter_app/lib/main.dart', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update Reschedule button
old_btn = '''launchUrl(Uri.parse(UserSession.getBaseUrl() + '/bookings/' + widget.booking.id.toString()), mode: LaunchMode.externalApplication);'''
new_btn = '''Navigator.push(context, MaterialPageRoute(builder: (_) => ReplacementBookingScreen(booking: widget.booking)));'''
content = content.replace(old_btn, new_btn)

# 2. Append TicketClipper at the end of the file
clipper = '''

class TicketClipper extends CustomClipper<Path> {
  final double punchRadius;
  final double dividerX;
  TicketClipper({this.punchRadius = 8.0, this.dividerX = 100.0});
  @override
  Path getClip(Size size) {
    final path = Path();
    path.lineTo(dividerX - punchRadius, 0);
    path.arcToPoint(Offset(dividerX + punchRadius, 0), radius: Radius.circular(punchRadius), clockwise: false);
    path.lineTo(size.width, 0);
    path.lineTo(size.width, size.height);
    path.lineTo(dividerX + punchRadius, size.height);
    path.arcToPoint(Offset(dividerX - punchRadius, size.height), radius: Radius.circular(punchRadius), clockwise: false);
    path.lineTo(0, size.height);
    path.close();
    return path;
  }
  @override
  bool shouldReclip(CustomClipper<Path> oldClipper) => false;
}
'''
if 'TicketClipper' not in content:
    content += clipper

# 3. Add imports at the top
if 'import \'replacement_booking_screen.dart\';' not in content:
    content = content.replace('import \'notification_service.dart\';', 'import \'notification_service.dart\';\nimport \'replacement_booking_screen.dart\';')

# 4. Patch VouchersScreen (we look for the exact Container inside VouchersScreen map)
# It's inside _VouchersScreenState

vouchers_screen_target = '''                                child: Container(
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

vouchers_screen_replace = '''                                child: Container(
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

# The end of VouchersScreen card
vouchers_end_target = '''                                      ),
                                    ],
                                  ),
                                );
                              },
                            ),
                          ),
                        ],
                      ),
                    );
  }
}'''

vouchers_end_replace = '''                                      ),
                                    ],
                                  ),
                                ),
                              ),
                            );
                              },
                            ),
                          ),
                        ],
                      ),
                    );
  }
}'''

content = content.replace(vouchers_screen_target, vouchers_screen_replace)
content = content.replace(vouchers_end_target, vouchers_end_replace)

# 5. Patch VoucherPickerScreen (we look for exact Container inside VoucherPickerScreen listview)
picker_target = '''                            child: Container(
                              margin: const EdgeInsets.only(bottom: 10),
                              decoration: BoxDecoration(
                                color: Colors.white,
                                borderRadius: BorderRadius.circular(8),
                                border: Border.all(color: isSelected ? kGreen : kSlate200, width: isSelected ? 2 : 1),
                                boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 4, offset: const Offset(0, 2))],
                              ),
                              height: 100,
                              child: Row('''

picker_replace = '''                            child: Container(
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

picker_end_target = '''                                  ),
                                );
                              },
                            ),
                          ),
                        ],
                      ),
                    ),
    );
  }
}'''

picker_end_replace = '''                                  ),
                                ),
                              ),
                            );
                              },
                            ),
                          ),
                        ],
                      ),
                    ),
    );
  }
}'''

content = content.replace(picker_target, picker_replace)
content = content.replace(picker_end_target, picker_end_replace)


with open('flutter_app/lib/main.dart', 'w', encoding='utf-8') as f:
    f.write(content)
print('Patched successfully.')

