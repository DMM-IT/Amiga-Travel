
import os

with open('flutter_app/lib/main.dart', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update Reschedule button
old_btn = '''launchUrl(Uri.parse(UserSession.getBaseUrl() + '/bookings/' + widget.booking.id.toString()), mode: LaunchMode.externalApplication);'''
new_btn = '''Navigator.push(context, MaterialPageRoute(builder: (_) => ReplacementBookingScreen(booking: widget.booking)));'''
content = content.replace(old_btn, new_btn)

# 2. Add imports
if 'import \'replacement_booking_screen.dart\';' not in content:
    content = content.replace('import \'notification_service.dart\';', 'import \'notification_service.dart\';\nimport \'replacement_booking_screen.dart\';')

# 3. Add TicketClipper
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


with open('flutter_app/lib/main.dart', 'w', encoding='utf-8') as f:
    f.write(content)
print('Patched successfully.')

