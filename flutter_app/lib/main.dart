import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'dart:io';
import 'package:flutter/foundation.dart' show kIsWeb;
import 'package:url_launcher/url_launcher.dart';
import 'package:image_picker/image_picker.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:open_filex/open_filex.dart';
import 'package:path_provider/path_provider.dart';
import 'dart:async';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  final prefs = await SharedPreferences.getInstance();
  final isFirstLaunch = prefs.getBool('first_launch') ?? true;
  await UserSession.init();
  runApp(MyApp(isFirstLaunch: isFirstLaunch));
}

// ==========================================
// BRAND COLORS
// ==========================================
const kGreen = Color(0xFF216417);
const kPink = Color(0xFFEE018D);
const kBgLight = Color(0xFFF8FAFC);
const kSlate800 = Color(0xFF1E293B);
const kSlate700 = Color(0xFF334155);
const kSlate600 = Color(0xFF475569);
const kSlate500 = Color(0xFF64748B);
const kSlate400 = Color(0xFF94A3B8);
const kSlate300 = Color(0xFFCBD5E1);
const kSlate200 = Color(0xFFE2E8F0);
const kSlate100 = Color(0xFFF1F5F9);
const kSlate50 = Color(0xFFF8FAFC);

// ==========================================
// GLOBAL SESSION
// ==========================================
class UserSession {
  static bool isLoggedIn = false;
  static bool isEmailVerified = false;
  static String username = 'Traveler';
  static String email = 'user@amigagracia.com';
  static String token = '';
  static String lookupToken = '';

  // Match this with pubspec.yaml version
  static const String appVersion = '1.0.4+6';

  static Future<void> init() async {
    final prefs = await SharedPreferences.getInstance();
    isLoggedIn = prefs.getBool('isLoggedIn') ?? false;
    isEmailVerified = prefs.getBool('isEmailVerified') ?? false;
    username = prefs.getString('username') ?? 'Traveler';
    email = prefs.getString('email') ?? 'user@amigagracia.com';
    token = prefs.getString('token') ?? '';
    lookupToken = prefs.getString('lookupToken') ?? '';
  }

  static Future<void> save() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool('isLoggedIn', isLoggedIn);
    await prefs.setBool('isEmailVerified', isEmailVerified);
    await prefs.setString('username', username);
    await prefs.setString('email', email);
    await prefs.setString('token', token);
    await prefs.setString('lookupToken', lookupToken);
  }

  static Future<void> clear() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('isLoggedIn');
    await prefs.remove('isEmailVerified');
    await prefs.remove('username');
    await prefs.remove('email');
    await prefs.remove('token');
    await prefs.remove('lookupToken');
    isLoggedIn = false;
    isEmailVerified = false;
    username = 'Traveler';
    email = 'user@amigagracia.com';
    token = '';
    lookupToken = '';
  }

  static String getBaseUrl() {
    const configuredUrl = String.fromEnvironment(
      'API_BASE_URL',
      defaultValue: 'https://amiga-travel.up.railway.app',
    );

    if (kIsWeb && configuredUrl.isEmpty) return '';
    return configuredUrl.replaceFirst(RegExp(r'/$'), '');
  }
}

// ==========================================
// BOOKING STATE (passed through screens)
// ==========================================
class BookingData {
  String mode = 'ferry'; // ferry | airline
  String tripType = 'one_way';
  String origin = '';
  String destination = '';
  String departureDate = '';
  String? returnDate;
  int adults = 1;
  int children = 0;

  // Step 2 — Schedule
  Map<String, dynamic>? selectedSchedule;
  int? selectedTransportClassId;
  Map<String, dynamic>? selectedTransportClass;
  int? selectedScheduleAccommodationId;
  Map<String, dynamic>? selectedScheduleAccommodation;

  // Vehicle (Ferry only)
  bool hasVehicle = false;
  int? selectedVehicleRateId;
  String vehicleType = '';
  String vehiclePlateNumber = '';
  double vehiclePrice = 0.0;

  // Step 3 — Passengers with discounts and seat selections
  // Each passenger: {'type': 'adult'|'child', 'name': '', 'discount_id': int?, 'seat_number': String?, 'seat_row': int?, 'seat_section': String?}
  List<Map<String, dynamic>> passengers = [];

  // Step 4 — Stay (accommodations)
  List<int> selectedAccommodationIds = [];
  List<Map<String, dynamic>> availableAccommodations = [];

  // Step 5 — Contact
  String clientName = '';
  String clientEmail = '';

  // Pricing
  double totalPrice = 0;
}

// ==========================================
// APP ENTRY
// ==========================================
class MyApp extends StatelessWidget {
  final bool isFirstLaunch;
  const MyApp({super.key, required this.isFirstLaunch});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Amiga Gracia',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        useMaterial3: true,
        colorScheme: ColorScheme.fromSeed(
          seedColor: kGreen,
          primary: kGreen,
          secondary: kPink,
        ),
        scaffoldBackgroundColor: kBgLight,
        appBarTheme: const AppBarTheme(
          backgroundColor: kGreen,
          foregroundColor: Colors.white,
          elevation: 2,
        ),
      ),
      home: SplashLoaderScreen(isFirstLaunch: isFirstLaunch),
    );
  }
}

// ==========================================
// SPLASH & ONBOARDING
// ==========================================
class SplashLoaderScreen extends StatefulWidget {
  final bool isFirstLaunch;
  const SplashLoaderScreen({super.key, required this.isFirstLaunch});

  @override
  State<SplashLoaderScreen> createState() => _SplashLoaderScreenState();
}

class _SplashLoaderScreenState extends State<SplashLoaderScreen> {
  @override
  void initState() {
    super.initState();
    _checkVersionAndProceed();
  }

  Future<void> _checkVersionAndProceed() async {
    // 1. Check for app updates
    try {
      final response = await http.get(Uri.parse('${UserSession.getBaseUrl()}/api/app-version'))
          .timeout(const Duration(seconds: 5));
          
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final latestVersion = data['version'] as String;
        
        // If versions don't match, show update prompt
        if (latestVersion != UserSession.appVersion) {
          if (mounted) {
            showDialog(
              context: context,
              barrierDismissible: false,
              builder: (ctx) {
                bool isDownloading = false;
                double progress = 0.0;
                String dlError = '';
                return StatefulBuilder(
                  builder: (context, setState) {
                    return AlertDialog(
                      title: const Text('Update Required'),
                      content: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Text('A new version ($latestVersion) of Amiga Gracia is available. Please update to continue using the app.'),
                          if (isDownloading) ...[
                            const SizedBox(height: 20),
                            LinearProgressIndicator(value: progress, color: kGreen),
                            const SizedBox(height: 8),
                            Text('${(progress * 100).toStringAsFixed(0)}% downloaded'),
                          ],
                          if (dlError.isNotEmpty) ...[
                            const SizedBox(height: 12),
                            Text(dlError, style: const TextStyle(color: Colors.red, fontSize: 12)),
                          ]
                        ],
                      ),
                      actions: [
                        if (!isDownloading)
                          FilledButton(
                            onPressed: () async {
                              setState(() {
                                isDownloading = true;
                                dlError = '';
                              });
                              try {
                                final apkUrl = '${UserSession.getBaseUrl()}/downloads/amiga-travel.apk';
                                final request = http.Request('GET', Uri.parse(apkUrl));
                                final response = await http.Client().send(request);
                                
                                if (response.statusCode != 200) {
                                  throw Exception('Server returned ${response.statusCode}');
                                }
                                
                                final contentLength = response.contentLength ?? 1;
                                
                                final dir = await getExternalStorageDirectory();
                                final file = File('${dir!.path}/update_$latestVersion.apk');
                                final sink = file.openWrite();
                                
                                int bytes = 0;
                                await response.stream.listen((List<int> chunk) {
                                  bytes += chunk.length;
                                  setState(() => progress = bytes / contentLength);
                                  sink.add(chunk);
                                }).asFuture();
                                await sink.close();
                                
                                final result = await OpenFilex.open(file.path);
                                if (result.type != ResultType.done) {
                                  throw Exception(result.message);
                                }
                                
                                setState(() => isDownloading = false);
                              } catch (e) {
                                debugPrint('Download error: $e');
                                setState(() {
                                  isDownloading = false;
                                  progress = 0;
                                  dlError = 'Download failed. Please try again or visit the website.';
                                });
                              }
                            },
                            style: FilledButton.styleFrom(backgroundColor: kGreen),
                            child: const Text('Update Now'),
                          ),
                      ],
                    );
                  }
                );
              },
            );
          }
          return; // Stop initialization, wait for update
        }
      }
    } catch (e) {
      debugPrint('Version check failed: $e');
      // Proceed if server is unreachable
    }

    // 2. Proceed to app
    Future.delayed(const Duration(seconds: 3), () {
      if (mounted) {
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(
            builder: (_) => widget.isFirstLaunch ? const OnboardingScreen() : const MainScreen(),
          ),
        );
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Image.asset('assets/icon/app_icon.png', width: 180, height: 180, fit: BoxFit.contain),
            const SizedBox(height: 24),
            const CircularProgressIndicator(color: kGreen),
            const SizedBox(height: 16),
            const Text('Connecting to Amiga Travel...', style: TextStyle(color: kGreen, fontSize: 16, fontWeight: FontWeight.bold)),
          ],
        ),
      ),
    );
  }
}

class OnboardingScreen extends StatefulWidget {
  const OnboardingScreen({super.key});

  @override
  State<OnboardingScreen> createState() => _OnboardingScreenState();
}

class _OnboardingScreenState extends State<OnboardingScreen> {
  final PageController _pageController = PageController();
  int _currentPage = 0;

  final List<Map<String, String>> _slides = [
    {
      'title': 'Welcome to Amiga Gracia',
      'desc': 'The fastest way to book your ferry, flight, and tour packages online.',
      'icon': 'explore',
    },
    {
      'title': 'Hassle-Free Travel',
      'desc': 'Skip the lines at the terminal. Pay securely via GCash or Bank Transfer directly in the app.',
      'icon': 'payments',
    },
    {
      'title': 'Exclusive Promos & Discounts',
      'desc': 'Get access to special rates for students, seniors, PWDs, and early bookings.',
      'icon': 'local_offer',
    },
  ];

  void _finishOnboarding() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool('first_launch', false);
    if (mounted) {
      Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => const MainScreen()));
    }
  }

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        child: Column(
          children: [
            Expanded(
              child: PageView.builder(
                controller: _pageController,
                onPageChanged: (i) => setState(() => _currentPage = i),
                itemCount: _slides.length,
                itemBuilder: (context, i) {
                  return Padding(
                    padding: const EdgeInsets.all(40),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(
                          i == 0 ? Icons.explore : i == 1 ? Icons.payments : Icons.local_offer,
                          size: 100,
                          color: kGreen,
                        ),
                        const SizedBox(height: 40),
                        Text(
                          _slides[i]['title']!,
                          textAlign: TextAlign.center,
                          style: const TextStyle(fontSize: 24, fontWeight: FontWeight.w900, color: kSlate800),
                        ),
                        const SizedBox(height: 16),
                        Text(
                          _slides[i]['desc']!,
                          textAlign: TextAlign.center,
                          style: const TextStyle(fontSize: 16, color: kSlate600, height: 1.5),
                        ),
                      ],
                    ),
                  );
                },
              ),
            ),
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: List.generate(_slides.length, (i) {
                return AnimatedContainer(
                  duration: const Duration(milliseconds: 300),
                  margin: const EdgeInsets.symmetric(horizontal: 4),
                  height: 8,
                  width: _currentPage == i ? 24 : 8,
                  decoration: BoxDecoration(color: _currentPage == i ? kGreen : kSlate200, borderRadius: BorderRadius.circular(4)),
                );
              }),
            ),
            const SizedBox(height: 40),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24),
              child: SizedBox(
                width: double.infinity,
                height: 54,
                child: ElevatedButton(
                  onPressed: () {
                    if (_currentPage < _slides.length - 1) {
                      _pageController.nextPage(duration: const Duration(milliseconds: 300), curve: Curves.easeInOut);
                    } else {
                      _finishOnboarding();
                    }
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: kPink,
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                  ),
                  child: Text(
                    _currentPage == _slides.length - 1 ? 'Get Started' : 'Next',
                    style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                  ),
                ),
              ),
            ),
            const SizedBox(height: 32),
          ],
        ),
      ),
    );
  }
}

// ==========================================
// MAIN SCREEN WITH BOTTOM NAV
// ==========================================
class MainScreen extends StatefulWidget {
  const MainScreen({super.key});

  @override
  State<MainScreen> createState() => _MainScreenState();
}

class _MainScreenState extends State<MainScreen> {
  int _selectedIndex = 0;
  final GlobalKey<ScaffoldState> _scaffoldKey = GlobalKey<ScaffoldState>();

  void _handleLogout() async {
    await UserSession.clear();
    setState(() {
      UserSession.isLoggedIn = false;
      UserSession.isEmailVerified = false;
      UserSession.username = 'Traveler';
      UserSession.email = 'user@amigagracia.com';
      UserSession.token = '';
      UserSession.lookupToken = '';
      _selectedIndex = 0; // Immediately navigate away from Transaction tab
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      key: _scaffoldKey,
      drawer: AppDrawer(onLogout: _handleLogout),
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(Icons.menu, color: Colors.white),
          onPressed: () => _scaffoldKey.currentState?.openDrawer(),
        ),
        title: Row(
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(6),
              child: Image.asset(
                'assets/icon/app_icon.png',
                height: 32,
                width: 32,
                fit: BoxFit.contain,
              ),
            ),
            const SizedBox(width: 10),
            const Text(
              'AMIGA GRACIA',
              style: TextStyle(fontWeight: FontWeight.w900, fontSize: 16, letterSpacing: 1.2),
            ),
          ],
        ),
      ),
      body: IndexedStack(
        index: _selectedIndex,
        children: [
          HomeScreen(
            onBookFerry: () => setState(() => _selectedIndex = 2),
            onBookAirline: () => setState(() => _selectedIndex = 2),
          ),
          const SchedulesScreen(),
          const TravelScreen(),
          const GraciaPointsScreen(),
          ActivityScreen(onLoginSuccess: () => setState(() {})),
        ],
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _selectedIndex,
        onTap: (i) => setState(() => _selectedIndex = i),
        selectedItemColor: kPink,
        unselectedItemColor: kSlate400,
        showUnselectedLabels: true,
        type: BottomNavigationBarType.fixed,
        backgroundColor: Colors.white,
        elevation: 12,
        items: const [
          BottomNavigationBarItem(
            icon: Icon(Icons.home_outlined),
            activeIcon: Icon(Icons.home),
            label: 'Home',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.calendar_month_outlined),
            activeIcon: Icon(Icons.calendar_month),
            label: 'Schedules',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.explore_outlined),
            activeIcon: Icon(Icons.explore),
            label: 'Travel',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.stars_outlined),
            activeIcon: Icon(Icons.stars),
            label: 'Gracia',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.receipt_long_outlined),
            activeIcon: Icon(Icons.receipt_long),
            label: 'Transaction',
          ),
        ],
      ),
    );
  }
}

// ==========================================
// 1. HOME SCREEN
// ==========================================
class HomeScreen extends StatefulWidget {
  final VoidCallback onBookFerry;
  final VoidCallback onBookAirline;

  const HomeScreen({
    super.key,
    required this.onBookFerry,
    required this.onBookAirline,
  });

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> with SingleTickerProviderStateMixin {
  List<dynamic> _promotions = [];
  late TabController _tourTabController;
  final PageController _promoPageController = PageController();
  int _currentPromoPage = 0;
  bool _promoLoading = true;

  final List<Map<String, dynamic>> _domesticPackages = [
    {
      'name': 'Puerto Galera Rainy Promo',
      'desc': '3D/2N · Ferry + Hotel + Island Tour',
      'price': '₱4,994',
      'tag': 'Rainy Promo',
      'tagColor': Color(0xFF216417),
      'gradient': [Color(0xFF1565C0), Color(0xFF42A5F5)],
    },
    {
      'name': 'Tagaytay City Tour',
      'desc': '2D/1N · Hotel + City Tour',
      'price': '₱5,900',
      'tag': 'City Escape',
      'tagColor': Color(0xFF1565C0),
      'gradient': [Color(0xFF00BCD4), Color(0xFF006064)],
    },
  ];

  final List<Map<String, dynamic>> _internationalPackages = [
    {
      'name': 'Love to Love Singapore & KL',
      'desc': '6D/5N · Airfare + Hotel + Tours',
      'price': '₱39,888',
      'tag': 'Love to Love',
      'tagColor': Color(0xFFEE018D),
      'gradient': [Color(0xFFE91E63), Color(0xFF880E4F)],
    },
    {
      'name': 'Great Singapore',
      'desc': '5D/4N · Airfare + Hotel + Transfers',
      'price': '₱32,888',
      'tag': 'Singapore',
      'tagColor': Color(0xFF7B1FA2),
      'gradient': [Color(0xFF7B1FA2), Color(0xFF4A148C)],
    },
    {
      'name': 'Golden Thailand Choose Premium',
      'desc': '5D/3N · Pattaya + Bangkok + Tours',
      'price': '₱32,888',
      'tag': 'Thailand',
      'tagColor': Color(0xFFC62828),
      'gradient': [Color(0xFFC62828), Color(0xFF7F0000)],
    },
    {
      'name': 'Memorable Japan - Hokkaido',
      'desc': '8D/5N · Airfare + Hotel + Private Coach',
      'price': '₱2,288',
      'tag': 'Japan',
      'tagColor': Color(0xFF216417),
      'gradient': [Color(0xFF00897B), Color(0xFF004D40)],
    },
    {
      'name': 'Heartfelt Korea',
      'desc': '6D/4N · Airfare + Hotel + Guided Tours',
      'price': '₱37,888',
      'tag': 'Korea',
      'tagColor': Color(0xFF1565C0),
      'gradient': [Color(0xFF1565C0), Color(0xFF42A5F5)],
    },
  ];

  final List<Map<String, dynamic>> _services = [
    {
      'title': '2GO Travel Booking',
      'desc': 'Premier overnight ship accommodation and fast cargo transits with 2GO Travel.',
      'icon': Icons.directions_boat,
      'color': Color(0xFFEE018D),
    },
    {
      'title': 'Starlite & Supercat',
      'desc': 'Affordable regional transits between Batangas, Calapan, and Roxas.',
      'icon': Icons.sailing,
      'color': Color(0xFF216417),
    },
    {
      'title': 'Airline Ticketing',
      'desc': 'Domestic & international flights: AirAsia, Cebu Pacific, and PAL.',
      'icon': Icons.flight,
      'color': Color(0xFF1565C0),
    },
    {
      'title': 'Tour Packages',
      'desc': 'Curated local and international itineraries with accommodations & guides.',
      'icon': Icons.landscape,
      'color': Color(0xFF7B1FA2),
    },
    {
      'title': 'Apprenticeships & Training',
      'desc': 'Hospitality training programs and educational field trips with 2GO.',
      'icon': Icons.school,
      'color': Color(0xFFF57C00),
    },
    {
      'title': 'Custom Group Packages',
      'desc': 'Corporate retreats, family reunions, and large group travel packages.',
      'icon': Icons.groups,
      'color': Color(0xFF00897B),
    },
  ];

  @override
  void initState() {
    super.initState();
    _tourTabController = TabController(length: 2, vsync: this);
    _fetchPromotions();
  }

  @override
  void dispose() {
    _tourTabController.dispose();
    _promoPageController.dispose();
    super.dispose();
  }

  void _fetchPromotions() async {
    try {
      final baseUrl = UserSession.getBaseUrl();
      final res = await http.get(Uri.parse('$baseUrl/api/promotions'));
      if (res.statusCode == 200) {
        final data = jsonDecode(res.body);
        if (data['status'] == 'success') {
          setState(() => _promotions = data['promotions']);
        }
      }
    } catch (_) {}
    finally {
      setState(() => _promoLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Carousel Banner (Hero + Promotions)
          const SizedBox(height: 16),
          SizedBox(
            height: 190,
            child: PageView.builder(
              controller: _promoPageController,
              onPageChanged: (i) => setState(() => _currentPromoPage = i),
              itemCount: 1 + _promotions.length,
              itemBuilder: (context, i) {
                if (i == 0) {
                  // Default Green Hero Banner
                  return Container(
                    margin: const EdgeInsets.symmetric(horizontal: 16),
                    decoration: BoxDecoration(
                      gradient: const LinearGradient(colors: [kGreen, Color(0xFF0e2709)], begin: Alignment.topLeft, end: Alignment.bottomRight),
                      borderRadius: BorderRadius.circular(20),
                      boxShadow: [BoxShadow(color: kGreen.withOpacity(0.4), blurRadius: 16, offset: const Offset(0, 6))],
                    ),
                    child: Stack(
                      children: [
                        Positioned(
                          right: -10,
                          bottom: -10,
                          child: Opacity(opacity: 0.08, child: const Icon(Icons.travel_explore, size: 180, color: Colors.white)),
                        ),
                        Padding(
                          padding: const EdgeInsets.all(22.0),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                                decoration: BoxDecoration(color: kPink, borderRadius: BorderRadius.circular(20)),
                                child: const Text('Kay Amiga, Hassle Free Ka!', style: TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold)),
                              ),
                              const SizedBox(height: 10),
                              const Text('Book Ferry Tickets\n& Flights Online', style: TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.w900, height: 1.2)),
                              const SizedBox(height: 6),
                              const Text('Calapan • Batangas • Puerto Galera', style: TextStyle(color: Colors.white70, fontSize: 12)),
                            ],
                          ),
                        ),
                      ],
                    ),
                  );
                } else {
                  // Promotional Image from backend
                  final promo = _promotions[i - 1];
                  final imgUrl = promo['image_url'] as String?;
                  return Container(
                    margin: const EdgeInsets.symmetric(horizontal: 16),
                    decoration: BoxDecoration(borderRadius: BorderRadius.circular(20), color: kSlate100),
                    clipBehavior: Clip.antiAlias,
                    child: imgUrl != null
                        ? Image.network(imgUrl, fit: BoxFit.cover, errorBuilder: (_, __, ___) => const Center(child: Icon(Icons.image, color: kSlate400, size: 40)))
                        : const Center(child: Icon(Icons.image, color: kSlate400, size: 40)),
                  );
                }
              },
            ),
          ),
          const SizedBox(height: 12),
          // Carousel Indicators
          if (_promotions.isNotEmpty)
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: List.generate(1 + _promotions.length, (i) {
                return AnimatedContainer(
                  duration: const Duration(milliseconds: 300),
                  margin: const EdgeInsets.symmetric(horizontal: 3),
                  height: 6,
                  width: _currentPromoPage == i ? 18 : 6,
                  decoration: BoxDecoration(color: _currentPromoPage == i ? kGreen : kSlate200, borderRadius: BorderRadius.circular(3)),
                );
              }),
            ),
          const SizedBox(height: 8),

          // Track Booking
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Card(
              color: Colors.white,
              elevation: 2,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'Track Booking',
                      style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: kSlate800),
                    ),
                    const SizedBox(height: 10),
                    TextField(
                      decoration: InputDecoration(
                        hintText: 'Enter your booking or tracking number',
                        hintStyle: const TextStyle(color: kSlate400, fontSize: 13),
                        filled: true,
                        fillColor: kSlate50,
                        suffixIcon: const Icon(Icons.search, color: kGreen),
                        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(12),
                          borderSide: BorderSide.none,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),

          const SizedBox(height: 20),

          // Quick Services
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Quick Services', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15, color: kSlate800)),
                const SizedBox(height: 12),
                Row(
                  children: [
                    Expanded(
                      child: _ServiceCard(
                        label: 'Book Ferry',
                        subtitle: 'Starlite, 2GO',
                        icon: Icons.directions_boat,
                        iconBg: kGreen.withOpacity(0.1),
                        iconColor: kGreen,
                        onTap: widget.onBookFerry,
                      ),
                    ),
                    const SizedBox(width: 14),
                    Expanded(
                      child: _ServiceCard(
                        label: 'Book Airline',
                        subtitle: 'PAL, CebuPac, AirAsia',
                        icon: Icons.flight,
                        iconBg: kPink.withOpacity(0.1),
                        iconColor: kPink,
                        onTap: widget.onBookAirline,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),

          const SizedBox(height: 20),

          // Request Travel Booking
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: GestureDetector(
              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const RequestBookingScreen())),
              child: Container(
                width: double.infinity,
                padding: const EdgeInsets.all(18),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(colors: [kGreen, Color(0xFF14400e)], begin: Alignment.topLeft, end: Alignment.bottomRight),
                  borderRadius: BorderRadius.circular(16),
                  boxShadow: [BoxShadow(color: kGreen.withOpacity(0.35), blurRadius: 12, offset: const Offset(0, 4))],
                ),
                child: Row(
                  children: [
                    const Icon(Icons.send_and_archive, color: Colors.white, size: 32),
                    const SizedBox(width: 14),
                    const Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('Request Travel Booking', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 15)),
                          Text('Fill out our booking request form', style: TextStyle(color: Colors.white70, fontSize: 12)),
                        ],
                      ),
                    ),
                    const Icon(Icons.chevron_right, color: Colors.white70),
                  ],
                ),
              ),
            ),
          ),

          const SizedBox(height: 24),

          // Our Services
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text('Our Services', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15, color: kSlate800)),
                    TextButton(
                      onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ServicesScreen())),
                      child: const Text('See all →', style: TextStyle(color: kPink, fontSize: 12, fontWeight: FontWeight.bold)),
                    ),
                  ],
                ),
                const SizedBox(height: 8),
                GridView.builder(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  itemCount: _services.length,
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 2,
                    crossAxisSpacing: 12,
                    mainAxisSpacing: 12,
                    childAspectRatio: 1.3,
                  ),
                  itemBuilder: (context, i) {
                    final s = _services[i];
                    return Container(
                      padding: const EdgeInsets.all(14),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(color: kSlate200),
                        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 6, offset: const Offset(0, 2))],
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Container(
                            padding: const EdgeInsets.all(8),
                            decoration: BoxDecoration(
                              color: (s['color'] as Color).withOpacity(0.1),
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: Icon(s['icon'] as IconData, color: s['color'] as Color, size: 20),
                          ),
                          const SizedBox(height: 8),
                          Text(s['title'] as String, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 11, color: kSlate800), maxLines: 1, overflow: TextOverflow.ellipsis),
                          Text(s['desc'] as String, style: const TextStyle(color: kSlate500, fontSize: 10), maxLines: 2, overflow: TextOverflow.ellipsis),
                        ],
                      ),
                    );
                  },
                ),
              ],
            ),
          ),

          const SizedBox(height: 24),

          // Tour Packages
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text('Tour Packages', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15, color: kSlate800)),
                    TextButton(
                      onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const TourPackagesScreen())),
                      child: const Text('See all →', style: TextStyle(color: kPink, fontSize: 12, fontWeight: FontWeight.bold)),
                    ),
                  ],
                ),
                const SizedBox(height: 8),
                Container(
                  decoration: BoxDecoration(
                    color: kSlate100,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: TabBar(
                    controller: _tourTabController,
                    indicatorColor: kGreen,
                    labelColor: kGreen,
                    unselectedLabelColor: kSlate500,
                    indicatorSize: TabBarIndicatorSize.tab,
                    labelStyle: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12),
                    tabs: const [Tab(text: 'Domestic'), Tab(text: 'International')],
                  ),
                ),
                const SizedBox(height: 12),
                SizedBox(
                  height: 200,
                  child: TabBarView(
                    controller: _tourTabController,
                    children: [
                      _PackageHorizontalList(packages: _domesticPackages),
                      _PackageHorizontalList(packages: _internationalPackages),
                    ],
                  ),
                ),
              ],
            ),
          ),

          const SizedBox(height: 32),
        ],
      ),
    );
  }
}

class _PackageHorizontalList extends StatelessWidget {
  final List<Map<String, dynamic>> packages;
  const _PackageHorizontalList({required this.packages});

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      scrollDirection: Axis.horizontal,
      itemCount: packages.length,
      itemBuilder: (context, i) {
        final p = packages[i];
        final gradient = p['gradient'] as List<Color>;
        return GestureDetector(
          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const TourPackagesScreen())),
          child: Container(
            width: 170,
            margin: const EdgeInsets.only(right: 12),
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(18),
              gradient: LinearGradient(colors: gradient, begin: Alignment.topLeft, end: Alignment.bottomRight),
              boxShadow: [BoxShadow(color: gradient.first.withOpacity(0.4), blurRadius: 10, offset: const Offset(0, 4))],
            ),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                    decoration: BoxDecoration(color: Colors.white.withOpacity(0.2), borderRadius: BorderRadius.circular(10)),
                    child: Text(p['tag'] as String, style: const TextStyle(color: Colors.white, fontSize: 9, fontWeight: FontWeight.bold)),
                  ),
                  const Spacer(),
                  Text(p['name'] as String, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 13), maxLines: 2),
                  const SizedBox(height: 4),
                  Text(p['desc'] as String, style: const TextStyle(color: Colors.white70, fontSize: 10), maxLines: 2),
                  const SizedBox(height: 8),
                  Text(p['price'] as String, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 15)),
                ],
              ),
            ),
          ),
        );
      },
    );
  }
}

class _ServiceCard extends StatelessWidget {
  final String label;
  final String subtitle;
  final IconData icon;
  final Color iconBg;
  final Color iconColor;
  final VoidCallback onTap;

  const _ServiceCard({
    required this.label,
    required this.subtitle,
    required this.icon,
    required this.iconBg,
    required this.iconColor,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 20, horizontal: 16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: kSlate200),
          boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 8, offset: const Offset(0, 2))],
        ),
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.all(14),
              decoration: BoxDecoration(color: iconBg, shape: BoxShape.circle),
              child: Icon(icon, color: iconColor, size: 26),
            ),
            const SizedBox(height: 10),
            Text(label, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: kSlate800)),
            const SizedBox(height: 3),
            Text(subtitle, style: const TextStyle(color: kSlate500, fontSize: 10), textAlign: TextAlign.center),
          ],
        ),
      ),
    );
  }
}

// ==========================================
// 2. TRAVEL SCREEN (Step 1: Route & Passengers)
// ==========================================
class TravelScreen extends StatefulWidget {
  const TravelScreen({super.key});

  @override
  State<TravelScreen> createState() => _TravelScreenState();
}

class _TravelScreenState extends State<TravelScreen> with SingleTickerProviderStateMixin {
  late TabController _tripTabController;
  String _mode = 'ferry';
  String? _origin;
  String? _destination;
  DateTime _departureDate = DateTime.now().add(const Duration(days: 1));
  DateTime _returnDate = DateTime.now().add(const Duration(days: 3));
  int _adults = 1;
  int _children = 0;
  bool _showPassengerDropdown = false;

  List<String> _origins = [];
  List<String> _destinations = [];
  bool _loadingOrigins = false;
  bool _loadingDestinations = false;

  List<Map<String, dynamic>> _vehicleRates = [];
  final _plateCtrl = TextEditingController();

  @override
  void initState() {
    super.initState();
    _tripTabController = TabController(length: 2, vsync: this);
    _tripTabController.addListener(() => setState(() {}));
    _fetchOrigins();
    _fetchVehicleRates();
  }

  @override
  void dispose() {
    _tripTabController.dispose();
    _plateCtrl.dispose();
    super.dispose();
  }

  void _fetchVehicleRates() async {
    try {
      final baseUrl = UserSession.getBaseUrl();
      final res = await http.get(Uri.parse('$baseUrl/api/vehicle-rates'));
      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['status'] == 'success') {
        if (mounted) setState(() => _vehicleRates = List<Map<String, dynamic>>.from(data['vehicle_rates']));
      }
    } catch (_) {}
  }

  void _fetchOrigins() async {
    setState(() => _loadingOrigins = true);
    try {
      final baseUrl = UserSession.getBaseUrl();
      final res = await http.get(Uri.parse('$baseUrl/api/origins?mode=$_mode'));
      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['status'] == 'success') {
        setState(() {
          _origins = List<String>.from(data['origins']);
          _origin = _origins.isNotEmpty ? _origins.first : null;
          _destination = null;
          _destinations = [];
          if (_origin != null) _fetchDestinations(_origin!);
        });
      }
    } catch (e) {
      debugPrint('Error fetching origins: $e');
    } finally {
      setState(() => _loadingOrigins = false);
    }
  }

  void _fetchDestinations(String origin) async {
    setState(() => _loadingDestinations = true);
    try {
      final baseUrl = UserSession.getBaseUrl();
      final res = await http.get(Uri.parse('$baseUrl/api/destinations?origin=${Uri.encodeComponent(origin)}&mode=$_mode'));
      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['status'] == 'success') {
        setState(() {
          _destinations = List<String>.from(data['destinations']);
          _destination = _destinations.isNotEmpty ? _destinations.first : null;
        });
      }
    } catch (e) {
      debugPrint('Error fetching destinations: $e');
    } finally {
      setState(() => _loadingDestinations = false);
    }
  }

  Future<void> _selectDate(BuildContext context, bool isDeparture) async {
    final picked = await showDatePicker(
      context: context,
      initialDate: isDeparture ? _departureDate : _returnDate,
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(const Duration(days: 365)),
      builder: (context, child) => Theme(
        data: Theme.of(context).copyWith(colorScheme: const ColorScheme.light(primary: kGreen, secondary: kPink)),
        child: child!,
      ),
    );
    if (picked != null) {
      setState(() {
        if (isDeparture) _departureDate = picked;
        else _returnDate = picked;
      });
    }
  }

  String _fmt(DateTime d) => '${d.year}-${d.month.toString().padLeft(2, '0')}-${d.day.toString().padLeft(2, '0')}';
  String _fmtDisplay(DateTime d) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return '${months[d.month - 1]} ${d.day}, ${d.year}';
  }

  int get _totalPassengers => _adults + _children;

  void _goToSchedule() {
    if (_origin == null || _destination == null) return;
    final booking = BookingData()
      ..mode = _mode
      ..tripType = _tripTabController.index == 0 ? 'one_way' : 'round_trip'
      ..origin = _origin!
      ..destination = _destination!
      ..departureDate = _fmt(_departureDate)
      ..returnDate = _tripTabController.index == 1 ? _fmt(_returnDate) : null
      ..adults = _adults
      ..children = _children;

    if (_mode == 'ferry' && (_plateCtrl.text.isNotEmpty || _vehicleRates.any((r) => r['selected'] == true))) {
       booking.hasVehicle = true;
       booking.vehiclePlateNumber = _plateCtrl.text;
       final selected = _vehicleRates.where((r) => r['selected'] == true).toList();
       if (selected.isNotEmpty) {
           booking.selectedVehicleRateId = selected.first['id'];
           booking.vehicleType = selected.first['name'];
           booking.vehiclePrice = double.tryParse(selected.first['price'].toString()) ?? 0;
       }
    }

    Navigator.push(context, MaterialPageRoute(builder: (_) => ScheduleSelectScreen(booking: booking)));
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        // Mode selector (Ferry / Airline)
        Container(
          color: Colors.white,
          padding: const EdgeInsets.fromLTRB(16, 12, 16, 0),
          child: Row(
            children: [
              _ModeTab(label: 'Ferry', icon: Icons.directions_boat, selected: _mode == 'ferry', onTap: () {
                if (_mode != 'ferry') {
                  setState(() { _mode = 'ferry'; _origin = null; _destination = null; _origins = []; _destinations = []; });
                  _fetchOrigins();
                }
              }),
              const SizedBox(width: 10),
              _ModeTab(label: 'Airline', icon: Icons.flight, selected: _mode == 'airline', onTap: () {
                if (_mode != 'airline') {
                  setState(() { _mode = 'airline'; _origin = null; _destination = null; _origins = []; _destinations = []; });
                  _fetchOrigins();
                }
              }),
            ],
          ),
        ),

        // One-Way / Round Trip tabs
        Container(
          color: Colors.white,
          child: TabBar(
            controller: _tripTabController,
            indicatorColor: kPink,
            labelColor: kPink,
            unselectedLabelColor: kSlate600,
            indicatorWeight: 3,
            tabs: const [Tab(text: 'One-Way'), Tab(text: 'Round Trip')],
          ),
        ),

        Expanded(
          child: _loadingOrigins
              ? const Center(child: CircularProgressIndicator(color: kGreen))
              : SingleChildScrollView(
                  padding: const EdgeInsets.all(16),
                  child: Card(
                    color: Colors.white,
                    elevation: 2,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                    child: Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          // Origin
                          _label('Origin'),
                          const SizedBox(height: 6),
                          DropdownButtonFormField<String>(
                            value: _origins.contains(_origin) ? _origin : null,
                            hint: const Text('Select Origin'),
                            items: _origins.toSet().map((c) => DropdownMenuItem(value: c, child: Text(c))).toList(),
                            onChanged: (v) {
                              if (v != null) {
                                setState(() { _origin = v; _destination = null; _destinations = []; });
                                _fetchDestinations(v);
                              }
                            },
                            decoration: _dropDecor(Icons.location_on),
                          ),
                          const SizedBox(height: 16),

                          // Destination
                          _label('Destination'),
                          const SizedBox(height: 6),
                          _loadingDestinations
                              ? const SizedBox(height: 52, child: Center(child: CircularProgressIndicator(color: kGreen)))
                              : DropdownButtonFormField<String>(
                                  value: _destinations.contains(_destination) ? _destination : null,
                                  hint: const Text('Select Destination'),
                                  items: _destinations.toSet().map((c) => DropdownMenuItem(value: c, child: Text(c))).toList(),
                                  onChanged: (v) => setState(() => _destination = v),
                                  decoration: _dropDecor(Icons.navigation),
                                ),
                          const SizedBox(height: 16),

                          // Travel Dates
                          _label('Travel Dates'),
                          const SizedBox(height: 6),
                          _datePicker(_fmtDisplay(_departureDate), () => _selectDate(context, true)),
                          if (_tripTabController.index == 1) ...[
                            const SizedBox(height: 10),
                            _datePicker(_fmtDisplay(_returnDate), () => _selectDate(context, false), label: 'Return'),
                          ],
                          const SizedBox(height: 16),

                          // Passenger Selector
                          _label('Passenger'),
                          const SizedBox(height: 6),
                          GestureDetector(
                            onTap: () => setState(() => _showPassengerDropdown = !_showPassengerDropdown),
                            child: Container(
                              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 14),
                              decoration: BoxDecoration(
                                border: Border.all(color: kSlate200),
                                borderRadius: BorderRadius.circular(12),
                              ),
                              child: Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  Row(children: [
                                    const Icon(Icons.people, color: kGreen, size: 20),
                                    const SizedBox(width: 8),
                                    Text(
                                      '$_adults Adult${_adults > 1 ? 's' : ''}${_children > 0 ? '  $_children Child${_children > 1 ? 'ren' : ''}' : ''}',
                                      style: const TextStyle(fontSize: 14, color: kSlate800),
                                    ),
                                  ]),
                                  Icon(_showPassengerDropdown ? Icons.expand_less : Icons.expand_more, color: kSlate400),
                                ],
                              ),
                            ),
                          ),
                          if (_showPassengerDropdown) ...[
                            Container(
                              margin: const EdgeInsets.only(top: 4),
                              padding: const EdgeInsets.all(16),
                              decoration: BoxDecoration(
                                color: Colors.white,
                                borderRadius: BorderRadius.circular(12),
                                border: Border.all(color: kSlate200),
                                boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.06), blurRadius: 10, offset: const Offset(0, 4))],
                              ),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  const Text('Maximum 8 passengers per booking', style: TextStyle(color: kSlate500, fontSize: 12)),
                                  const SizedBox(height: 12),
                                  _PassengerCounter(
                                    label: 'Adult',
                                    subtitle: '12 years and above',
                                    count: _adults,
                                    onIncrement: _totalPassengers < 8 ? () => setState(() => _adults++) : null,
                                    onDecrement: _adults > 1 ? () => setState(() => _adults--) : null,
                                  ),
                                  const Divider(height: 20),
                                  _PassengerCounter(
                                    label: 'Child',
                                    subtitle: '2 - 11 years',
                                    count: _children,
                                    onIncrement: _totalPassengers < 8 ? () => setState(() => _children++) : null,
                                    onDecrement: _children > 0 ? () => setState(() => _children--) : null,
                                  ),
                                  const Divider(height: 20),
                                  const Row(
                                    children: [
                                      Expanded(
                                        child: Column(
                                          crossAxisAlignment: CrossAxisAlignment.start,
                                          children: [
                                            Text('Infant', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: kSlate800)),
                                            Text('Below 2 years old', style: TextStyle(color: kSlate500, fontSize: 12)),
                                            Text('₱500 fare / infant to be paid at the terminal', style: TextStyle(color: kSlate500, fontSize: 11)),
                                          ],
                                        ),
                                      ),
                                    ],
                                  ),
                                  const SizedBox(height: 10),
                                  GestureDetector(
                                    onTap: () {
                                      showDialog(
                                        context: context,
                                        builder: (ctx) => AlertDialog(
                                          title: const Row(
                                            children: [
                                              Icon(Icons.info_outline, color: kPink),
                                              SizedBox(width: 8),
                                              Text('Infant Passenger Info', style: TextStyle(fontSize: 16)),
                                            ],
                                          ),
                                          content: const Text(
                                            'Infants (below 2 years old) do not need to be added to the passenger list in the app.\n\n'
                                            'Instead, a flat fare of ₱500 per infant must be paid directly at the terminal counter before boarding.',
                                            style: TextStyle(fontSize: 14, color: kSlate600, height: 1.5),
                                          ),
                                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                                          actions: [
                                            TextButton(
                                              onPressed: () => Navigator.pop(ctx),
                                              child: const Text('Got it', style: TextStyle(color: kGreen, fontWeight: FontWeight.bold)),
                                            ),
                                          ],
                                        ),
                                      );
                                    },
                                    child: const Row(
                                      children: [
                                        Text('Info', style: TextStyle(color: kPink, fontWeight: FontWeight.bold, fontSize: 13)),
                                        SizedBox(width: 4),
                                        Icon(Icons.info_outline, color: kPink, size: 14),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ],
                          const SizedBox(height: 24),

                          // Vehicle / Car Booking (Ferry only)
                          if (_mode == 'ferry') ...[
                            Container(
                              padding: const EdgeInsets.all(16),
                              decoration: BoxDecoration(
                                color: Colors.white,
                                border: Border.all(color: kSlate200),
                                borderRadius: BorderRadius.circular(16),
                              ),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Row(
                                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                    children: [
                                      const Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Text('Vehicle / Car Booking', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15, color: kSlate800)),
                                          Text('Bring your vehicle on the ferry', style: TextStyle(color: kSlate500, fontSize: 12)),
                                        ],
                                      ),
                                      Switch(
                                        value: _plateCtrl.text.isNotEmpty || _vehicleRates.any((r) => r['selected'] == true),
                                        activeColor: kGreen,
                                        onChanged: (val) {
                                          setState(() {
                                            if (!val) {
                                              _plateCtrl.clear();
                                              for (var r in _vehicleRates) { r['selected'] = false; }
                                            } else {
                                              if (_vehicleRates.isNotEmpty) _vehicleRates.first['selected'] = true;
                                            }
                                          });
                                        },
                                      ),
                                    ],
                                  ),
                                  if (_plateCtrl.text.isNotEmpty || _vehicleRates.any((r) => r['selected'] == true)) ...[
                                    const SizedBox(height: 16),
                                    Container(
                                      padding: const EdgeInsets.all(12),
                                      decoration: BoxDecoration(
                                        color: Colors.amber.shade50,
                                        border: Border.all(color: Colors.amber.shade200),
                                        borderRadius: BorderRadius.circular(10),
                                      ),
                                      child: const Row(
                                        children: [
                                          Icon(Icons.info_outline, color: Colors.amber, size: 18),
                                          SizedBox(width: 8),
                                          Expanded(child: Text('Vehicle bookings are subject to availability.', style: TextStyle(color: Colors.amber, fontSize: 12))),
                                        ],
                                      ),
                                    ),
                                    const SizedBox(height: 14),
                                    const Text('Vehicle Type', style: TextStyle(fontWeight: FontWeight.w600, color: kSlate700, fontSize: 13)),
                                    const SizedBox(height: 8),
                                    if (_vehicleRates.isNotEmpty)
                                      Column(
                                        children: _vehicleRates.map((rate) {
                                          final selected = rate['selected'] == true;
                                          return GestureDetector(
                                            onTap: () {
                                              setState(() {
                                                for (var r in _vehicleRates) { r['selected'] = false; }
                                                rate['selected'] = true;
                                              });
                                            },
                                            child: Container(
                                              margin: const EdgeInsets.only(bottom: 8),
                                              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                                              decoration: BoxDecoration(
                                                color: selected ? kGreen.withOpacity(0.05) : kSlate50,
                                                border: Border.all(color: selected ? kGreen : kSlate200, width: selected ? 2 : 1),
                                                borderRadius: BorderRadius.circular(12),
                                              ),
                                              child: Row(
                                                children: [
                                                  Icon(Icons.directions_car, color: selected ? kGreen : kSlate400, size: 20),
                                                  const SizedBox(width: 10),
                                                  Expanded(child: Text(rate['name'], style: TextStyle(fontWeight: FontWeight.w600, color: selected ? kGreen : kSlate800))),
                                                  Text('₱${rate['price']}', style: TextStyle(color: selected ? kGreen : kPink, fontWeight: FontWeight.bold)),
                                                ],
                                              ),
                                            ),
                                          );
                                        }).toList(),
                                      ),
                                    const SizedBox(height: 14),
                                    const Text('Plate Number', style: TextStyle(fontWeight: FontWeight.w600, color: kSlate700, fontSize: 13)),
                                    const SizedBox(height: 8),
                                    TextField(
                                      controller: _plateCtrl,
                                      decoration: InputDecoration(hintText: 'e.g., ABC 1234', border: OutlineInputBorder(borderRadius: BorderRadius.circular(12))),
                                    ),
                                  ],
                                ],
                              ),
                            ),
                            const SizedBox(height: 24),
                          ],

                          // Next Button
                          SizedBox(
                            width: double.infinity,
                            height: 52,
                            child: ElevatedButton(
                              onPressed: (_origin == null || _destination == null) ? null : _goToSchedule,
                              style: ElevatedButton.styleFrom(
                                backgroundColor: kPink,
                                foregroundColor: Colors.white,
                                disabledBackgroundColor: kSlate200,
                                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                                elevation: 4,
                              ),
                              child: const Text('Next', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
        ),
      ],
    );
  }

  Widget _label(String text) => Text(text, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: kSlate600));

  InputDecoration _dropDecor(IconData icon) => InputDecoration(
        prefixIcon: Icon(icon, color: kGreen),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: kSlate200)),
        contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
      );

  Widget _datePicker(String value, VoidCallback onTap, {String? label}) => InkWell(
        onTap: onTap,
        child: Container(
          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 14),
          decoration: BoxDecoration(border: Border.all(color: kSlate400), borderRadius: BorderRadius.circular(12)),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Row(children: [
                if (label != null) ...[Text('$label: ', style: const TextStyle(color: kSlate500, fontSize: 13)), ],
                Text(value, style: const TextStyle(fontSize: 14, color: kSlate800)),
              ]),
              const Icon(Icons.calendar_today, size: 20, color: kPink),
            ],
          ),
        ),
      );
}

class _ModeTab extends StatelessWidget {
  final String label;
  final IconData icon;
  final bool selected;
  final VoidCallback onTap;

  const _ModeTab({required this.label, required this.icon, required this.selected, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        padding: const EdgeInsets.symmetric(horizontal: 18, vertical: 10),
        decoration: BoxDecoration(
          color: selected ? kGreen : kSlate100,
          borderRadius: BorderRadius.circular(30),
          boxShadow: selected ? [BoxShadow(color: kGreen.withOpacity(0.3), blurRadius: 8, offset: const Offset(0, 3))] : [],
        ),
        child: Row(
          children: [
            Icon(icon, size: 16, color: selected ? Colors.white : kSlate600),
            const SizedBox(width: 6),
            Text(label, style: TextStyle(color: selected ? Colors.white : kSlate600, fontWeight: FontWeight.bold, fontSize: 13)),
          ],
        ),
      ),
    );
  }
}

class _PassengerCounter extends StatelessWidget {
  final String label;
  final String subtitle;
  final int count;
  final VoidCallback? onIncrement;
  final VoidCallback? onDecrement;

  const _PassengerCounter({
    required this.label,
    required this.subtitle,
    required this.count,
    this.onIncrement,
    this.onDecrement,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(label, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: kSlate800)),
              Text(subtitle, style: const TextStyle(color: kSlate500, fontSize: 12)),
            ],
          ),
        ),
        Row(
          children: [
            _CounterButton(icon: Icons.remove, onPressed: onDecrement),
            SizedBox(width: 44, child: Text('$count', textAlign: TextAlign.center, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: kSlate800))),
            _CounterButton(icon: Icons.add, onPressed: onIncrement),
          ],
        ),
      ],
    );
  }
}

class _CounterButton extends StatelessWidget {
  final IconData icon;
  final VoidCallback? onPressed;
  const _CounterButton({required this.icon, this.onPressed});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onPressed,
      child: Container(
        width: 34,
        height: 34,
        decoration: BoxDecoration(
          shape: BoxShape.rectangle,
          borderRadius: BorderRadius.circular(8),
          border: Border.all(color: onPressed != null ? kSlate400 : kSlate200),
          color: onPressed != null ? Colors.white : kSlate50,
        ),
        child: Icon(icon, size: 16, color: onPressed != null ? kSlate800 : kSlate400),
      ),
    );
  }
}

// ==========================================
// 3. ACTIVITY SCREEN
// ==========================================
class ActivityScreen extends StatefulWidget {
  final VoidCallback onLoginSuccess;
  const ActivityScreen({super.key, required this.onLoginSuccess});

  @override
  State<ActivityScreen> createState() => _ActivityScreenState();
}

class _ActivityScreenState extends State<ActivityScreen> {
  // Login/Register form fields
  final _emailCtrl = TextEditingController();
  final _passCtrl = TextEditingController();
  final _nameCtrl = TextEditingController();
  bool _isLoading = false;
  bool _obscure = true;
  bool _isSignUp = false;

  // OTP registration state
  String? _pendingRegisterEmail;  // non-null when OTP step is active
  final _otpCtrl = TextEditingController();
  bool _otpLoading = false;

  // Guest booking lookup (separate from login/register fields)
  final _guestEmailCtrl = TextEditingController();
  bool _verificationRequested = false;
  bool _verificationLoading = false;
  final _verificationCodeCtrl = TextEditingController();

  List<dynamic> _bookings = [];
  bool _loadingBookings = false;

  @override
  void initState() {
    super.initState();
    if (UserSession.isLoggedIn) {
      _fetchBookings();
    } else {
      _loadVerifiedEmail();
    }
  }

  @override
  void dispose() {
    _emailCtrl.dispose();
    _passCtrl.dispose();
    _nameCtrl.dispose();
    _otpCtrl.dispose();
    _guestEmailCtrl.dispose();
    _verificationCodeCtrl.dispose();
    super.dispose();
  }

  Future<void> _loadVerifiedEmail() async {
    final prefs = await SharedPreferences.getInstance();
    final email = prefs.getString('verified_email');
    final lookupToken = prefs.getString('booking_lookup_token');
    if (!mounted || email == null || lookupToken == null) return;
    setState(() {
      UserSession.email = email;
      UserSession.lookupToken = lookupToken;
      UserSession.isEmailVerified = true;
    });
    _fetchBookings();
  }

  Future<void> _requestEmailVerification() async {
    final email = _guestEmailCtrl.text.trim();
    if (email.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Enter the email used for your booking.'), backgroundColor: Colors.red));
      return;
    }
    setState(() => _verificationLoading = true);
    try {
      final response = await http.post(
        Uri.parse('${UserSession.getBaseUrl()}/api/email-verification/request'),
        headers: {'Accept': 'application/json'},
        body: {'email': email},
      );
      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['status'] == 'success') {
        setState(() => _verificationRequested = true);
        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Verification code sent to your email.'), backgroundColor: kGreen));
      } else {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(data['message'] ?? 'Unable to send verification code.'), backgroundColor: Colors.red));
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Connection error: $e'), backgroundColor: Colors.red));
    } finally {
      if (mounted) setState(() => _verificationLoading = false);
    }
  }

  Future<void> _verifyEmail() async {
    final email = _guestEmailCtrl.text.trim();
    final code = _verificationCodeCtrl.text.trim();
    if (code.length != 6) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Enter the six-digit verification code.'), backgroundColor: Colors.red));
      return;
    }
    setState(() => _verificationLoading = true);
    try {
      final response = await http.post(
        Uri.parse('${UserSession.getBaseUrl()}/api/email-verification/verify'),
        headers: {'Accept': 'application/json'},
        body: {'email': email, 'code': code},
      );
      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['status'] == 'success') {
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('verified_email', data['email']);
        await prefs.setString('booking_lookup_token', data['lookup_token']);
        setState(() {
          UserSession.email = data['email'];
          UserSession.lookupToken = data['lookup_token'];
          UserSession.isEmailVerified = true;
        });
        _fetchBookings();
      } else {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(data['message'] ?? 'Verification failed.'), backgroundColor: Colors.red));
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Connection error: $e'), backgroundColor: Colors.red));
    } finally {
      if (mounted) setState(() => _verificationLoading = false);
    }
  }

  Future<void> _fetchBookings() async {
    setState(() => _loadingBookings = true);
    try {
      final baseUrl = UserSession.getBaseUrl();
      final response = await http.get(
        Uri.parse('$baseUrl/api/bookings?email=${Uri.encodeComponent(UserSession.email)}&lookup_token=${Uri.encodeComponent(UserSession.lookupToken)}'),
        headers: {'Accept': 'application/json'},
      );
      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['status'] == 'success') {
        setState(() {
          _bookings = data['bookings'];
        });
      }
    } catch (e) {
      debugPrint('Error fetching bookings: $e');
    } finally {
      setState(() => _loadingBookings = false);
    }
  }

  Future<String?> _askRefundDestination() async {
    final controller = TextEditingController();
    final destination = await showDialog<String>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Refund destination'),
        content: TextField(
          controller: controller,
          autofocus: true,
          decoration: const InputDecoration(labelText: 'GCash, wallet, or bank account'),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Back')),
          FilledButton(onPressed: () => Navigator.pop(ctx, controller.text.trim()), child: const Text('Continue')),
        ],
      ),
    );
    controller.dispose();
    return destination;
  }

  Future<void> _cancelBooking(int id) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Cancel Booking'),
        content: const Text('Are you sure you want to cancel this booking?'),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('No', style: TextStyle(color: kSlate600))),
          TextButton(onPressed: () => Navigator.pop(ctx, true), child: const Text('Yes, Cancel', style: TextStyle(color: Colors.red, fontWeight: FontWeight.bold))),
        ],
      ),
    );
    if (confirm != true) return;
    final refundDestination = await _askRefundDestination();
    if (refundDestination == null || refundDestination.isEmpty) return;

    try {
      final baseUrl = UserSession.getBaseUrl();
      final res = await http.post(
        Uri.parse('$baseUrl/api/bookings/$id/cancel'),
        headers: {'Accept': 'application/json'},
        body: {'email': UserSession.email, 'refund_destination': refundDestination},
      );
      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['status'] == 'success') {
        await _fetchBookings();
        if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Cancelled. Refund: ₱${data['refund_amount']}'), backgroundColor: Colors.green));
      } else {
        if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(data['message'] ?? 'Failed to cancel booking.'), backgroundColor: Colors.red));
      }
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Error: $e'), backgroundColor: Colors.red));
    }
  }

  Future<void> _rebookBooking(Map<String, dynamic> booking) async {
    final now = DateTime.now();
    final firstDate = now.add(const Duration(days: 1));
    final departure = await showDatePicker(
      context: context,
      firstDate: firstDate,
      lastDate: DateTime(now.year + 2),
      initialDate: firstDate,
      helpText: 'New departure date',
    );
    if (departure == null || !mounted) return;

    DateTime? returnDate;
    if (booking['return_date'] != null) {
      returnDate = await showDatePicker(
        context: context,
        firstDate: departure,
        lastDate: DateTime(now.year + 2),
        initialDate: departure.add(const Duration(days: 1)),
        helpText: 'New return date',
      );
      if (returnDate == null || !mounted) return;
    }

    XFile? proof;
    final picker = ImagePicker();
    final fee = ((booking['total_price'] as num?)?.toDouble() ?? 0) * 0.3;
    final shouldSubmit = await showDialog<bool>(
      context: context,
      builder: (ctx) => StatefulBuilder(
        builder: (ctx, setDialogState) => AlertDialog(
          title: const Text('Request rebooking'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('Rebooking fee: ₱${fee.toStringAsFixed(2)}'),
              const SizedBox(height: 8),
              const Text('Upload your payment proof so staff can verify the request.'),
              const SizedBox(height: 12),
              OutlinedButton.icon(
                onPressed: () async {
                  proof = await picker.pickImage(source: ImageSource.gallery, imageQuality: 80);
                  setDialogState(() {});
                },
                icon: const Icon(Icons.upload_file),
                label: Text(proof == null ? 'Choose proof image' : 'Proof selected'),
              ),
            ],
          ),
          actions: [
            TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Back')),
            FilledButton(onPressed: () => Navigator.pop(ctx, true), child: const Text('Submit request')),
          ],
        ),
      ),
    );
    if (shouldSubmit != true) return;

    try {
      final baseUrl = UserSession.getBaseUrl();
      final request = http.MultipartRequest('POST', Uri.parse('$baseUrl/api/bookings/${booking['id']}/rebook'));
      request.headers['Accept'] = 'application/json';
      request.fields['email'] = UserSession.email;
      request.fields['departure_date'] = departure.toIso8601String().split('T')[0];
      if (returnDate != null) request.fields['return_date'] = returnDate.toIso8601String().split('T')[0];
      if (proof != null) request.files.add(await http.MultipartFile.fromPath('proof', proof!.path));
      final streamed = await request.send();
      final response = await http.Response.fromStream(streamed);
      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['status'] == 'success') {
        await _fetchBookings();
        if (mounted) ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Rebooking request submitted for verification.'), backgroundColor: Colors.green));
      } else if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(data['message'] ?? 'Rebooking failed.'), backgroundColor: Colors.red));
      }
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Error: $e'), backgroundColor: Colors.red));
    }
  }

  // Step 1 of registration: request OTP
  Future<void> _requestRegisterOtp() async {
    final email    = _emailCtrl.text.trim();
    final password = _passCtrl.text;
    final name     = _nameCtrl.text.trim();

    if (name.isEmpty || email.isEmpty || password.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please fill in your username, email, and password.'), backgroundColor: Colors.red),
      );
      return;
    }
    if (password.length < 8) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Password must be at least 8 characters.'), backgroundColor: Colors.red),
      );
      return;
    }

    setState(() => _isLoading = true);
    try {
      final response = await http.post(
        Uri.parse('${UserSession.getBaseUrl()}/api/register/request-otp'),
        headers: {'Accept': 'application/json'},
        body: {'name': name, 'email': email, 'password': password},
      );
      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['status'] == 'success') {
        setState(() {
          _pendingRegisterEmail = email;
          _otpCtrl.clear();
        });
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(data['message'] ?? 'OTP sent! Check your email.'), backgroundColor: kGreen),
        );
      } else {
        final msg = data['message'] ?? data['errors']?.values?.first?.first ?? 'Could not send OTP.';
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(msg), backgroundColor: Colors.red),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Connection error: $e'), backgroundColor: Colors.red),
      );
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  // Step 2 of registration: verify OTP and complete account creation
  Future<void> _verifyRegisterOtp() async {
    final otp = _otpCtrl.text.trim();
    if (otp.length != 6) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Enter the 6-digit code sent to your email.'), backgroundColor: Colors.red),
      );
      return;
    }
    setState(() => _otpLoading = true);
    try {
      final response = await http.post(
        Uri.parse('${UserSession.getBaseUrl()}/api/register/verify-otp'),
        headers: {'Accept': 'application/json'},
        body: {'email': _pendingRegisterEmail!, 'otp': otp},
      );
      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['status'] == 'success') {
        setState(() {
          UserSession.isLoggedIn = true;
          UserSession.username = data['user']['name'];
          UserSession.email = data['user']['email'];
          UserSession.token = data['token'];
          UserSession.lookupToken = data['lookup_token'] ?? '';
          UserSession.isEmailVerified = UserSession.lookupToken.isNotEmpty;
          _pendingRegisterEmail = null;
        });
        widget.onLoginSuccess();
        _fetchBookings();
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(data['message'] ?? 'Welcome, ${UserSession.username}!'), backgroundColor: kGreen),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(data['message'] ?? 'Verification failed.'), backgroundColor: Colors.red),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Connection error: $e'), backgroundColor: Colors.red),
      );
    } finally {
      if (mounted) setState(() => _otpLoading = false);
    }
  }

  void _submitAuth() async {
    final email    = _emailCtrl.text.trim();
    final password = _passCtrl.text;

    if (email.isEmpty || password.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please fill out all required fields.'), backgroundColor: Colors.red),
      );
      return;
    }

    setState(() => _isLoading = true);

    try {
      final response = await http.post(
        Uri.parse('${UserSession.getBaseUrl()}/api/login'),
        headers: {'Accept': 'application/json'},
        body: {'email': email, 'password': password},
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200 && data['status'] == 'success') {
        setState(() {
          UserSession.isLoggedIn = true;
          UserSession.username = data['user']['name'];
          UserSession.email = data['user']['email'];
          UserSession.token = data['token'];
          UserSession.lookupToken = data['lookup_token'] ?? '';
          UserSession.isEmailVerified = UserSession.lookupToken.isNotEmpty;
        });
        widget.onLoginSuccess();
        _fetchBookings();
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Welcome back, ${data['user']['name']}!'), backgroundColor: kGreen),
        );
      } else {
        final errorMsg = data['message'] ?? 'Authentication failed. Please check your credentials.';
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(errorMsg), backgroundColor: Colors.red),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error connecting to server: $e'), backgroundColor: Colors.red),
      );
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    if (!UserSession.isLoggedIn && !UserSession.isEmailVerified) {
      // ── OTP verification screen (after sign-up form submitted) ──────────
      if (_pendingRegisterEmail != null) {
        return SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 28, vertical: 40),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              Image.asset('assets/icon/app_icon.png', height: 90, width: 90, fit: BoxFit.contain),
              const SizedBox(height: 24),
              const Text(
                'Verify Your Email',
                style: TextStyle(fontSize: 24, fontWeight: FontWeight.w900, color: kGreen),
              ),
              const SizedBox(height: 8),
              Text(
                'We sent a 6-digit code to\n$_pendingRegisterEmail',
                textAlign: TextAlign.center,
                style: const TextStyle(fontSize: 13, color: kSlate500),
              ),
              const SizedBox(height: 36),
              TextField(
                controller: _otpCtrl,
                keyboardType: TextInputType.number,
                maxLength: 6,
                textAlign: TextAlign.center,
                style: const TextStyle(fontSize: 28, fontWeight: FontWeight.bold, letterSpacing: 12),
                decoration: InputDecoration(
                  hintText: '000000',
                  hintStyle: const TextStyle(color: kSlate300, fontSize: 28, letterSpacing: 12),
                  prefixIcon: const Icon(Icons.lock_clock_outlined, color: kGreen),
                  border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                ),
              ),
              const SizedBox(height: 8),
              const Text(
                'The code expires in 10 minutes.',
                style: TextStyle(fontSize: 12, color: kSlate400),
              ),
              const SizedBox(height: 24),
              SizedBox(
                width: double.infinity, height: 52,
                child: ElevatedButton(
                  onPressed: _otpLoading ? null : _verifyRegisterOtp,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: kPink,
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    elevation: 4,
                  ),
                  child: _otpLoading
                      ? const SizedBox(width: 22, height: 22, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5))
                      : const Text('Verify & Create Account', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                ),
              ),
              const SizedBox(height: 12),
              TextButton(
                onPressed: () => setState(() => _pendingRegisterEmail = null),
                child: const Text('← Back to sign up', style: TextStyle(color: kSlate500)),
              ),
            ],
          ),
        );
      }

      // ── Login / Register form ────────────────────────────────────────────
      return SingleChildScrollView(
        padding: const EdgeInsets.symmetric(horizontal: 28, vertical: 32),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            const SizedBox(height: 16),
            // Amiga Gracia logo (transparent bg) instead of ship icon
            Image.asset('assets/icon/app_icon.png', height: 88, width: 88, fit: BoxFit.contain),
            const SizedBox(height: 20),
            Text(
              _isSignUp ? 'Create Account' : 'Welcome Back!',
              style: const TextStyle(fontSize: 24, fontWeight: FontWeight.w900, color: kGreen),
            ),
            const SizedBox(height: 6),
            Text(
              _isSignUp ? 'Sign up to start booking ferry and flights' : 'Sign in to view your bookings & transactions',
              textAlign: TextAlign.center,
              style: const TextStyle(fontSize: 13, color: kSlate500),
            ),
            const SizedBox(height: 36),



            // ── Sign-up extra field: Username ──────────────────────────────
            if (_isSignUp) ...[
              TextField(
                controller: _nameCtrl,
                keyboardType: TextInputType.name,
                decoration: InputDecoration(
                  labelText: 'Username',
                  prefixIcon: const Icon(Icons.person_outline, color: kGreen),
                  border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                ),
              ),
              const SizedBox(height: 16),
            ],

            TextField(
              controller: _emailCtrl,
              keyboardType: TextInputType.emailAddress,
              decoration: InputDecoration(
                labelText: 'Email address',
                prefixIcon: const Icon(Icons.email_outlined, color: kGreen),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
              ),
            ),
            const SizedBox(height: 16),

            TextField(
              controller: _passCtrl,
              obscureText: _obscure,
              decoration: InputDecoration(
                labelText: 'Password',
                prefixIcon: const Icon(Icons.lock_outline, color: kGreen),
                suffixIcon: IconButton(
                  icon: Icon(_obscure ? Icons.visibility_off : Icons.visibility, color: kSlate400),
                  onPressed: () => setState(() => _obscure = !_obscure),
                ),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
              ),
            ),
            const SizedBox(height: 24),

            SizedBox(
              width: double.infinity, height: 52,
              child: ElevatedButton(
                onPressed: _isLoading ? null : (_isSignUp ? _requestRegisterOtp : _submitAuth),
                style: ElevatedButton.styleFrom(
                  backgroundColor: kPink,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  elevation: 4,
                ),
                child: _isLoading
                    ? const SizedBox(width: 22, height: 22, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5))
                    : Text(_isSignUp ? 'Sign Up' : 'Login', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
              ),
            ),
            const SizedBox(height: 12),
            TextButton(
              onPressed: () => setState(() {
                _isSignUp = !_isSignUp;
                _emailCtrl.clear();
                _passCtrl.clear();
                _nameCtrl.clear();
              }),
              child: Text(
                _isSignUp ? 'Already have an account? Login' : "Don't have an account? Register",
                style: const TextStyle(color: kPink, fontWeight: FontWeight.bold),
              ),
            ),
          ],
        ),
      );
    }

    if (_loadingBookings) {
      return const Center(child: CircularProgressIndicator(color: kGreen));
    }

    return RefreshIndicator(
      onRefresh: () async => _fetchBookings(),
      color: kGreen,
      child: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          const Text('My Bookings', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18, color: kSlate800)),
          const SizedBox(height: 12),
          if (_bookings.isEmpty)
            const Center(
              child: Padding(
                padding: EdgeInsets.symmetric(vertical: 48),
                child: Column(
                  children: [
                    Icon(Icons.receipt_long, size: 64, color: kSlate200),
                    SizedBox(height: 16),
                    Text('No bookings yet', style: TextStyle(color: kSlate400, fontSize: 16)),
                    Text('Your bookings will appear here after you book.', style: TextStyle(color: kSlate400, fontSize: 12), textAlign: TextAlign.center),
                  ],
                ),
              ),
            )
          else
            ..._bookings.map((b) {
              final status = b['status']?.toString() ?? 'pending';
              Color statusColor = Colors.orange;
              if (status == 'confirmed' || status == 'paid') statusColor = kGreen;
              if (status == 'cancelled') statusColor = Colors.red;

              return Card(
                color: Colors.white,
                margin: const EdgeInsets.only(bottom: 12),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            b['transaction_number'] ?? '',
                            style: const TextStyle(fontWeight: FontWeight.bold, color: kGreen),
                          ),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                            decoration: BoxDecoration(
                              color: statusColor.withOpacity(0.1),
                              borderRadius: BorderRadius.circular(8),
                            ),
                            child: Text(
                              status.toUpperCase(),
                              style: TextStyle(color: statusColor, fontSize: 10, fontWeight: FontWeight.bold),
                            ),
                          ),
                        ],
                      ),
                      const Divider(height: 20),
                      Row(
                        children: [
                          const Icon(Icons.location_on, color: kPink, size: 16),
                          const SizedBox(width: 6),
                          Text('${b['origin']} → ${b['destination']}', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                        ],
                      ),
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          const Icon(Icons.calendar_today, color: kSlate400, size: 14),
                          const SizedBox(width: 6),
                          Text(b['departure_date'] != null ? b['departure_date'].toString().split('T')[0] : '', style: const TextStyle(fontSize: 12, color: kSlate600)),
                          if (b['return_date'] != null) ...[
                            const Text('  |  Return: ', style: TextStyle(fontSize: 12, color: kSlate400)),
                            Text(b['return_date'].toString().split('T')[0], style: const TextStyle(fontSize: 12, color: kSlate600)),
                          ],
                        ],
                      ),
                      const SizedBox(height: 4),
                      Row(
                        children: [
                          const Icon(Icons.directions_boat, color: kSlate400, size: 14),
                          const SizedBox(width: 6),
                          Text(b['schedule_summary'] ?? b['schedule_service'] ?? '', style: const TextStyle(fontSize: 12, color: kSlate600)),
                        ],
                      ),
                      const Divider(height: 20),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            '₱${b['total_price']}',
                            style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 15, color: kPink),
                          ),
                          Text(
                            b['created_at'] != null ? 'Booked: ${b['created_at'].toString().split('T')[0]}' : '',
                            style: const TextStyle(fontSize: 11, color: kSlate400),
                          ),
                        ],
                      ),
                      if (b['rebooking_status'] == 'pending') ...[
                        const SizedBox(height: 12),
                        const Text('Rebooking request pending verification', style: TextStyle(color: Colors.orange, fontWeight: FontWeight.bold, fontSize: 12)),
                      ],
                      const SizedBox(height: 12),
                      SizedBox(
                        width: double.infinity,
                        child: OutlinedButton.icon(
                          onPressed: () async {
                            await Navigator.push(context, MaterialPageRoute(builder: (_) => BookingDetailsScreen(booking: Map<String, dynamic>.from(b))));
                            _fetchBookings();
                          },
                          icon: const Icon(Icons.open_in_new, size: 18),
                          label: const Text('Open booking', style: TextStyle(fontWeight: FontWeight.bold)),
                          style: OutlinedButton.styleFrom(
                            foregroundColor: kGreen,
                            side: const BorderSide(color: kGreen),
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              );
            }).toList(),
        ],
      ),
    );
  }
}

class BookingDetailsScreen extends StatefulWidget {
  final Map<String, dynamic> booking;

  const BookingDetailsScreen({super.key, required this.booking});

  @override
  State<BookingDetailsScreen> createState() => _BookingDetailsScreenState();
}

class _BookingDetailsScreenState extends State<BookingDetailsScreen> {
  late Map<String, dynamic> _booking;
  final _refundInstitutionCtrl = TextEditingController();
  final _refundAccountCtrl = TextEditingController();
  final _refundNameCtrl = TextEditingController();
  DateTime? _cancellationExpiresAt;
  Timer? _cancellationTimer;
  String _refundMethod = 'GCash';
  bool _cancellationStarted = false;
  bool _busy = false;
  String? _qrCodeUrl;

  String get _baseUrl => UserSession.getBaseUrl();
  String get _paymentStatus => (_booking['transaction']?['payment_status'] ?? 'unpaid').toString();
  bool get _canManage => _booking['status'] == 'pending' && _paymentStatus != 'paid' && _paymentStatus != 'cancelled';
  bool get _isRoundTrip => _booking['return_date'] != null;

  @override
  void initState() {
    super.initState();
    _booking = Map<String, dynamic>.from(widget.booking);
    _fetchPaymentSettings();
  }

  @override
  void dispose() {
    _cancellationTimer?.cancel();
    _refundInstitutionCtrl.dispose();
    _refundAccountCtrl.dispose();
    _refundNameCtrl.dispose();
    super.dispose();
  }

  Future<void> _fetchPaymentSettings() async {
    try {
      final response = await http.get(Uri.parse('$_baseUrl/api/payment-settings'), headers: {'Accept': 'application/json'});
      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && mounted) setState(() => _qrCodeUrl = data['qr_code_url']);
    } catch (_) {}
  }

  void _showMessage(String message, {bool error = false}) {
    if (!mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(message), backgroundColor: error ? Colors.red : kGreen));
  }

  Future<void> _startCancellation() async {
    setState(() => _busy = true);
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl/api/bookings/${_booking['id']}/cancel'),
        headers: {'Accept': 'application/json'},
        body: {'email': UserSession.email, 'action': 'start'},
      );
      final data = jsonDecode(response.body);
      if (response.statusCode != 200 || data['status'] != 'success') {
        _showMessage(data['message'] ?? 'Unable to start cancellation.', error: true);
        return;
      }
      _cancellationExpiresAt = DateTime.parse(data['expires_at']).toLocal();
      _cancellationStarted = true;
      _cancellationTimer = Timer.periodic(const Duration(seconds: 1), (_) {
        if (mounted) setState(() {});
      });
      setState(() {});
    } catch (e) {
      _showMessage('Connection error: $e', error: true);
    } finally {
      if (mounted) setState(() => _busy = false);
    }
  }

  String _refundDestination() {
    final parts = ['Method: $_refundMethod'];
    if (_refundMethod != 'GCash') parts.add('Institution: ${_refundInstitutionCtrl.text.trim()}');
    parts.add('Account No: ${_refundAccountCtrl.text.trim()}');
    parts.add('Name: ${_refundNameCtrl.text.trim()}');
    return parts.join(' | ');
  }

  Future<void> _confirmCancellation() async {
    if (_refundAccountCtrl.text.trim().isEmpty || _refundNameCtrl.text.trim().isEmpty || (_refundMethod != 'GCash' && _refundInstitutionCtrl.text.trim().isEmpty)) {
      _showMessage('Complete the refund details first.', error: true);
      return;
    }
    if (_cancellationExpiresAt == null || DateTime.now().isAfter(_cancellationExpiresAt!)) {
      _showMessage('The cancellation window has expired.', error: true);
      return;
    }
    final confirm = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Confirm cancellation'),
        content: Text('A 50% fee will be deducted. Your estimated refund is ₱${((( _booking['total_price'] as num?)?.toDouble() ?? 0) * 0.5).toStringAsFixed(2)}.'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Back')),
          FilledButton(onPressed: () => Navigator.pop(ctx, true), child: const Text('Confirm')),
        ],
      ),
    );
    if (confirm != true) return;
    setState(() => _busy = true);
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl/api/bookings/${_booking['id']}/cancel'),
        headers: {'Accept': 'application/json'},
        body: {'email': UserSession.email, 'action': 'confirm', 'refund_destination': _refundDestination()},
      );
      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['status'] == 'success') {
        _booking['status'] = 'cancelled';
        _booking['cancellation_fee'] = data['cancellation_fee'];
        _booking['refund_amount'] = data['refund_amount'];
        _cancellationTimer?.cancel();
        setState(() => _cancellationStarted = false);
        _showMessage('Booking cancelled. Refund: ₱${data['refund_amount']}');
      } else {
        _showMessage(data['message'] ?? 'Cancellation failed.', error: true);
      }
    } catch (e) {
      _showMessage('Connection error: $e', error: true);
    } finally {
      if (mounted) setState(() => _busy = false);
    }
  }

  Future<void> _uploadPaymentProof() async {
    final proof = await ImagePicker().pickImage(source: ImageSource.gallery, imageQuality: 80);
    if (proof == null) return;
    setState(() => _busy = true);
    try {
      final request = http.MultipartRequest('POST', Uri.parse('$_baseUrl/api/bookings/${_booking['id']}/proof'));
      request.headers['Accept'] = 'application/json';
      request.fields['email'] = UserSession.email;
      request.files.add(await http.MultipartFile.fromPath('proof', proof.path));
      final response = await http.Response.fromStream(await request.send());
      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['status'] == 'success') {
        final transaction = Map<String, dynamic>.from(_booking['transaction'] ?? {});
        transaction['payment_status'] = 'pending';
        _booking['transaction'] = transaction;
        setState(() {});
        _showMessage('Payment proof uploaded for verification.');
      } else {
        _showMessage(data['message'] ?? 'Upload failed.', error: true);
      }
    } catch (e) {
      _showMessage('Upload error: $e', error: true);
    } finally {
      if (mounted) setState(() => _busy = false);
    }
  }

  Future<void> _rebook() async {
    final now = DateTime.now();
    final departure = await showDatePicker(context: context, firstDate: now.add(const Duration(days: 1)), lastDate: DateTime(now.year + 2), initialDate: now.add(const Duration(days: 1)), helpText: 'New departure date');
    if (departure == null || !mounted) return;
    DateTime? returnDate;
    if (_isRoundTrip) {
      returnDate = await showDatePicker(context: context, firstDate: departure, lastDate: DateTime(now.year + 2), initialDate: departure.add(const Duration(days: 1)), helpText: 'New return date');
      if (returnDate == null || !mounted) return;
    }
    final proof = await ImagePicker().pickImage(source: ImageSource.gallery, imageQuality: 80);
    if (proof == null) return;
    final fee = ((_booking['total_price'] as num?)?.toDouble() ?? 0) * 0.3;
    final confirm = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Submit rebooking request'),
        content: Column(mainAxisSize: MainAxisSize.min, children: [
          Text('Rebooking fee: ₱${fee.toStringAsFixed(2)}'),
          if (_qrCodeUrl != null) ...[const SizedBox(height: 12), Image.network(_qrCodeUrl!, height: 120, width: 120, errorBuilder: (_, __, ___) => const SizedBox.shrink())],
          const SizedBox(height: 12),
          const Text('Payment proof selected. Submit it for staff verification?'),
        ]),
        actions: [TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Back')), FilledButton(onPressed: () => Navigator.pop(ctx, true), child: const Text('Submit'))],
      ),
    );
    if (confirm != true) return;
    setState(() => _busy = true);
    try {
      final request = http.MultipartRequest('POST', Uri.parse('$_baseUrl/api/bookings/${_booking['id']}/rebook'));
      request.headers['Accept'] = 'application/json';
      request.fields['email'] = UserSession.email;
      request.fields['departure_date'] = departure.toIso8601String().split('T')[0];
      if (returnDate != null) request.fields['return_date'] = returnDate.toIso8601String().split('T')[0];
      request.files.add(await http.MultipartFile.fromPath('proof', proof.path));
      final response = await http.Response.fromStream(await request.send());
      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['status'] == 'success') {
        _booking['rebooking_status'] = 'pending';
        setState(() {});
        _showMessage('Rebooking request submitted for verification.');
      } else {
        _showMessage(data['message'] ?? 'Rebooking failed.', error: true);
      }
    } catch (e) {
      _showMessage('Upload error: $e', error: true);
    } finally {
      if (mounted) setState(() => _busy = false);
    }
  }

  Future<void> _openSupport() async {
    final subjectCtrl = TextEditingController(text: 'Booking ${_booking['transaction_number']} support');
    final messageCtrl = TextEditingController();
    final submit = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Contact support'),
        content: Column(mainAxisSize: MainAxisSize.min, children: [
          TextField(controller: subjectCtrl, decoration: const InputDecoration(labelText: 'Subject')),
          TextField(controller: messageCtrl, maxLines: 4, decoration: const InputDecoration(labelText: 'Message')),
        ]),
        actions: [TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Back')), FilledButton(onPressed: () => Navigator.pop(ctx, true), child: const Text('Send'))],
      ),
    );
    if (submit != true || messageCtrl.text.trim().isEmpty) return;
    try {
      final response = await http.post(Uri.parse('$_baseUrl/api/support'), headers: {'Accept': 'application/json'}, body: {
        'name': UserSession.username,
        'email': UserSession.email,
        'subject': subjectCtrl.text.trim(),
        'message': 'Booking ${_booking['transaction_number']}: ${messageCtrl.text.trim()}',
      });
      final data = jsonDecode(response.body);
      _showMessage(response.statusCode == 200 ? 'Support request sent.' : (data['message'] ?? 'Unable to contact support.'), error: response.statusCode != 200);
    } catch (e) {
      _showMessage('Connection error: $e', error: true);
    } finally {
      subjectCtrl.dispose();
      messageCtrl.dispose();
    }
  }

  @override
  Widget build(BuildContext context) {
    final status = (_booking['status'] ?? 'pending').toString();
    final expiry = _cancellationExpiresAt;
    final secondsLeft = expiry == null ? 0 : expiry.difference(DateTime.now()).inSeconds.clamp(0, 300);
    final transaction = Map<String, dynamic>.from(_booking['transaction'] ?? {});
    return Scaffold(
      appBar: AppBar(title: const Text('Booking details')),
      body: ListView(padding: const EdgeInsets.all(16), children: [
        _detailHeader(status),
        const SizedBox(height: 12),
        _detailSection('Trip', [
          '${_booking['origin']} → ${_booking['destination']}',
          'Departure: ${_booking['departure_date']?.toString().split('T')[0] ?? '—'}',
          if (_booking['return_date'] != null) 'Return: ${_booking['return_date'].toString().split('T')[0]}',
          _booking['schedule_summary'] ?? _booking['schedule_service'] ?? 'Schedule not recorded',
        ]),
        _detailSection('Payment', ['Status: ${_paymentStatus.toUpperCase()}', 'Total: ₱${_booking['total_price'] ?? '0.00'}']),
        if (_paymentStatus == 'unpaid' || _paymentStatus == 'pending') ...[
          OutlinedButton.icon(onPressed: _busy ? null : _uploadPaymentProof, icon: const Icon(Icons.upload_file), label: const Text('Upload payment proof')),
        ],
        if (_booking['rebooking_status'] == 'pending')
          _detailSection('Rebooking', ['Request pending verification', 'New dates will appear after approval.']),
        if (transaction['confirmation_url'] != null)
          OutlinedButton.icon(onPressed: () => launchUrl(Uri.parse(transaction['confirmation_url'])), icon: const Icon(Icons.confirmation_num), label: const Text('Open confirmation')),
        if (_booking['confirmation_url'] != null)
          OutlinedButton.icon(onPressed: () => launchUrl(Uri.parse(_booking['confirmation_url'])), icon: const Icon(Icons.link), label: const Text('Open e-ticket link')),
        if (_booking['confirmation_pdf_url'] != null)
          OutlinedButton.icon(onPressed: () => launchUrl(Uri.parse(_booking['confirmation_pdf_url'])), icon: const Icon(Icons.picture_as_pdf), label: const Text('Open e-ticket PDF')),
        if (_booking['ticket_url'] != null)
          OutlinedButton.icon(onPressed: () => launchUrl(Uri.parse(_booking['ticket_url'])), icon: const Icon(Icons.download), label: const Text('Download ticket')),
        const SizedBox(height: 12),
        if (_canManage && !_cancellationStarted) ...[
          OutlinedButton.icon(onPressed: _busy ? null : _rebook, icon: const Icon(Icons.calendar_month), label: const Text('Request rebooking')),
          OutlinedButton.icon(onPressed: _busy ? null : _startCancellation, icon: const Icon(Icons.cancel_outlined), label: const Text('Start cancellation'), style: OutlinedButton.styleFrom(foregroundColor: Colors.red)),
        ],
        if (_cancellationStarted) ...[
          _detailSection('Cancellation', ['Complete the refund details and confirm within ${secondsLeft ~/ 60}:${(secondsLeft % 60).toString().padLeft(2, '0')}.', 'Cancellation fee: 50% of total price']),
          DropdownButtonFormField<String>(value: _refundMethod, decoration: const InputDecoration(labelText: 'Refund method'), items: const [DropdownMenuItem(value: 'GCash', child: Text('GCash')), DropdownMenuItem(value: 'Online Wallet', child: Text('Online Wallet')), DropdownMenuItem(value: 'Bank Account', child: Text('Bank Account'))], onChanged: (value) => setState(() => _refundMethod = value ?? 'GCash')),
          if (_refundMethod != 'GCash') TextField(controller: _refundInstitutionCtrl, decoration: const InputDecoration(labelText: 'Bank or wallet provider')),
          TextField(controller: _refundAccountCtrl, decoration: InputDecoration(labelText: _refundMethod == 'GCash' ? 'GCash number' : 'Account number')),
          TextField(controller: _refundNameCtrl, decoration: const InputDecoration(labelText: 'Account name')),
          const SizedBox(height: 12),
          FilledButton.icon(onPressed: _busy || secondsLeft == 0 ? null : _confirmCancellation, icon: const Icon(Icons.check), label: Text(secondsLeft == 0 ? 'Window expired' : 'Confirm cancellation')),
        ],
        const SizedBox(height: 12),
        OutlinedButton.icon(onPressed: _openSupport, icon: const Icon(Icons.support_agent), label: const Text('Contact support')),
      ]),
    );
  }

  Widget _detailHeader(String status) => Card(color: kGreen, child: Padding(padding: const EdgeInsets.all(18), child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [Text(_booking['transaction_number'] ?? '', style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 18)), const SizedBox(height: 8), Text(status.toUpperCase(), style: const TextStyle(color: Colors.white70, fontWeight: FontWeight.bold))])));

  Widget _detailSection(String title, List<String> values) => Card(margin: const EdgeInsets.only(bottom: 12), child: Padding(padding: const EdgeInsets.all(16), child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [Text(title, style: const TextStyle(fontWeight: FontWeight.bold, color: kSlate800)), const SizedBox(height: 8), ...values.map((value) => Padding(padding: const EdgeInsets.only(bottom: 4), child: Text(value, style: const TextStyle(color: kSlate600))))])));
}

// ==========================================
// DRAWER
// ==========================================
class AppDrawer extends StatelessWidget {
  final VoidCallback onLogout;
  const AppDrawer({super.key, required this.onLogout});

  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: Column(
        children: [
          UserAccountsDrawerHeader(
            decoration: const BoxDecoration(color: kGreen),
            currentAccountPicture: CircleAvatar(
              backgroundColor: Colors.white,
              child: Text(
                UserSession.isLoggedIn ? UserSession.username[0] : '?',
                style: const TextStyle(fontSize: 28, fontWeight: FontWeight.bold, color: kGreen),
              ),
            ),
            accountName: Text(
              UserSession.isLoggedIn ? 'Hi, ${UserSession.username}!' : 'Guest User',
              style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
            ),
            accountEmail: Text(
              UserSession.isLoggedIn ? UserSession.email : 'Sign in to access your activities',
              style: const TextStyle(fontSize: 12, color: Colors.white70),
            ),
          ),
          ListTile(
            leading: const Icon(Icons.person_outline, color: kGreen),
            title: const Text('My Profile'),
            onTap: () => Navigator.pop(context),
          ),
          ListTile(
            leading: const Icon(Icons.info_outline, color: kGreen),
            title: const Text('About'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(context, MaterialPageRoute(builder: (_) => const AboutScreen()));
            },
          ),
          ListTile(
            leading: const Icon(Icons.phone_outlined, color: kGreen),
            title: const Text('Contacts'),
            onTap: () {
              Navigator.pop(context);
              Navigator.push(context, MaterialPageRoute(builder: (_) => const ContactScreen()));
            },
          ),
          ListTile(
            leading: const Icon(Icons.language, color: kGreen),
            title: const Text('Visit Website'),
            onTap: () async {
              Navigator.pop(context);
              final url = Uri.parse('https://amiga-travel.up.railway.app');
              if (await canLaunchUrl(url)) await launchUrl(url, mode: LaunchMode.externalApplication);
            },
          ),
          const Spacer(),
          if (UserSession.isLoggedIn)
            ListTile(
              leading: const Icon(Icons.logout, color: Colors.redAccent),
              title: const Text('Log out', style: TextStyle(color: Colors.redAccent)),
              onTap: () {
                Navigator.pop(context);
                onLogout(); // Clears full session & navigates to Home tab
              },
            ),
          const Padding(
            padding: EdgeInsets.all(16),
            child: Text(
              '© 2025 Amiga Gracia Travel Services',
              style: TextStyle(color: kSlate400, fontSize: 11),
              textAlign: TextAlign.center,
            ),
          ),
        ],
      ),
    );
  }
}

// ==========================================
// STEP PROGRESS INDICATOR
// ==========================================
class _StepProgress extends StatelessWidget {
  final int currentStep;
  final List<String> steps;

  const _StepProgress({required this.currentStep, required this.steps});

  IconData _getStepIcon(String stepName) {
    switch (stepName.toLowerCase()) {
      case 'route': return Icons.directions_boat;
      case 'schedule': return Icons.calendar_month;
      case 'discount': return Icons.local_offer;
      case 'add-ons': return Icons.extension;
      case 'submit': return Icons.fact_check;
      default: return Icons.circle;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      color: Colors.white,
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      child: Row(
        children: List.generate(steps.length * 2 - 1, (i) {
          if (i.isOdd) {
            return Expanded(
              child: Container(
                height: 2,
                color: i ~/ 2 < currentStep - 1 ? kGreen : kSlate200,
              ),
            );
          }
          final step = i ~/ 2 + 1;
          final active = step == currentStep;
          final done = step < currentStep;
          return Column(
            children: [
              Container(
                width: 28,
                height: 28,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: done ? kGreen : active ? kPink : kSlate200,
                ),
                child: Center(
                  child: done
                      ? const Icon(Icons.check, color: Colors.white, size: 14)
                      : Icon(_getStepIcon(steps[i ~/ 2]), color: active ? Colors.white : kSlate500, size: 14),
                ),
              ),
              const SizedBox(height: 4),
              Text(steps[i ~/ 2], style: TextStyle(fontSize: 9, color: active ? kPink : done ? kGreen : kSlate400, fontWeight: active ? FontWeight.bold : FontWeight.normal)),
            ],
          );
        }),
      ),
    );
  }
}

// ==========================================
// STEP 2: SCHEDULE SELECT
// ==========================================
class ScheduleSelectScreen extends StatefulWidget {
  final BookingData booking;
  const ScheduleSelectScreen({super.key, required this.booking});

  @override
  State<ScheduleSelectScreen> createState() => _ScheduleSelectScreenState();
}

class _ScheduleSelectScreenState extends State<ScheduleSelectScreen> {
  List<dynamic> _schedules = [];
  bool _isLoading = true;
  String? _error;

  static const _steps = ['Route', 'Schedule', 'Discount', 'Add-ons', 'Submit'];

  @override
  void initState() {
    super.initState();
    _fetchSchedules();
  }

  void _fetchSchedules() async {
    setState(() { _isLoading = true; _error = null; });
    try {
      final baseUrl = UserSession.getBaseUrl();
      final res = await http.post(
        Uri.parse('$baseUrl/api/schedules'),
        headers: {'Accept': 'application/json', 'Content-Type': 'application/json'},
        body: jsonEncode({
          'origin': widget.booking.origin,
          'destination': widget.booking.destination,
          'date': widget.booking.departureDate,
          'mode': widget.booking.mode,
        }),
      );
      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['status'] == 'success') {
        setState(() => _schedules = data['schedules']);
      } else {
        setState(() => _error = data['message'] ?? 'Failed to load schedules.');
      }
    } catch (e) {
      setState(() => _error = 'Error connecting to server: $e');
    } finally {
      setState(() => _isLoading = false);
    }
  }

  void _selectTransportOption(BuildContext context, Map<String, dynamic> s) {
    widget.booking.selectedSchedule = Map<String, dynamic>.from(s);
    widget.booking.passengers = [
      for (int i = 0; i < widget.booking.adults; i++)
        {'type': 'adult', 'name': '', 'discount_id': null, 'seat_number': null, 'seat_row': null, 'seat_section': null},
      for (int i = 0; i < widget.booking.children; i++)
        {'type': 'child', 'name': '', 'discount_id': null, 'seat_number': null, 'seat_row': null, 'seat_section': null},
    ];

    final isAirline = widget.booking.mode == 'airline';
    final classes = s['transport_classes'] as List<dynamic>? ?? [];
    final accommodations = s['accommodations'] as List<dynamic>? ?? [];

    if (isAirline && classes.isNotEmpty) {
      _showAirlineClassPicker(context, classes);
    } else if (!isAirline && accommodations.isNotEmpty) {
      _showFerryAccommodationPicker(context, accommodations);
    } else {
      widget.booking.selectedTransportClassId = null;
      widget.booking.selectedTransportClass = null;
      widget.booking.selectedScheduleAccommodationId = null;
      widget.booking.selectedScheduleAccommodation = null;
      Navigator.push(context, MaterialPageRoute(builder: (_) => DiscountScreen(booking: widget.booking)));
    }
  }

  void _showAirlineClassPicker(BuildContext context, List<dynamic> classes) {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (context) {
        return Container(
          padding: const EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text('Select Cabin Class', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18, color: kSlate800)),
              const SizedBox(height: 12),
              Flexible(
                child: ListView.builder(
                  shrinkWrap: true,
                  itemCount: classes.length,
                  itemBuilder: (context, idx) {
                    final c = classes[idx];
                    final isOnSale = c['is_on_sale'] == true;
                    final price = isOnSale ? c['sale_price'] : c['price'];
                    return Card(
                      color: Colors.white,
                      margin: const EdgeInsets.only(bottom: 10),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12), side: const BorderSide(color: kSlate200)),
                      child: InkWell(
                        onTap: () {
                          widget.booking.selectedTransportClassId = c['id'];
                          widget.booking.selectedTransportClass = Map<String, dynamic>.from(c);
                          widget.booking.selectedScheduleAccommodationId = null;
                          widget.booking.selectedScheduleAccommodation = null;
                          Navigator.pop(context);
                          Navigator.push(context, MaterialPageRoute(builder: (_) => SeatSelectionScreen(booking: widget.booking)));
                        },
                        borderRadius: BorderRadius.circular(12),
                        child: Padding(
                          padding: const EdgeInsets.all(14),
                          child: Row(
                            children: [
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Row(
                                      children: [
                                        Text(c['name'] ?? '', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15, color: kSlate800)),
                                        if (isOnSale) ...[
                                          const SizedBox(width: 8),
                                          Container(
                                            padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                                            decoration: BoxDecoration(color: Colors.red, borderRadius: BorderRadius.circular(4)),
                                            child: const Text('SALE', style: TextStyle(color: Colors.white, fontSize: 9, fontWeight: FontWeight.bold)),
                                          ),
                                        ],
                                      ],
                                    ),
                                    if (c['description'] != null) ...[
                                      const SizedBox(height: 4),
                                      Text(c['description'], style: const TextStyle(color: kSlate500, fontSize: 12)),
                                    ],
                                  ],
                                ),
                              ),
                              const SizedBox(width: 10),
                              Column(
                                crossAxisAlignment: CrossAxisAlignment.end,
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: [
                                  if (isOnSale)
                                    Text('₱${c['price']}', style: const TextStyle(color: kSlate400, fontSize: 12, decoration: TextDecoration.lineThrough)),
                                  Text('₱$price', style: const TextStyle(color: kPink, fontWeight: FontWeight.bold, fontSize: 16)),
                                ],
                              ),
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
      },
    );
  }

  void _showFerryAccommodationPicker(BuildContext context, List<dynamic> accommodations) {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (context) {
        return Container(
          padding: const EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text('Select Accommodation', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18, color: kSlate800)),
              const SizedBox(height: 12),
              Flexible(
                child: ListView.builder(
                  shrinkWrap: true,
                  itemCount: accommodations.length,
                  itemBuilder: (context, idx) {
                    final acc = accommodations[idx];
                    return Card(
                      color: Colors.white,
                      margin: const EdgeInsets.only(bottom: 10),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12), side: const BorderSide(color: kSlate200)),
                      child: InkWell(
                        onTap: () {
                          widget.booking.selectedScheduleAccommodationId = acc['id'];
                          widget.booking.selectedScheduleAccommodation = Map<String, dynamic>.from(acc);
                          widget.booking.selectedTransportClassId = null;
                          widget.booking.selectedTransportClass = null;
                          Navigator.pop(context);
                          Navigator.push(context, MaterialPageRoute(builder: (_) => DiscountScreen(booking: widget.booking)));
                        },
                        borderRadius: BorderRadius.circular(12),
                        child: Padding(
                          padding: const EdgeInsets.all(14),
                          child: Row(
                            children: [
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(acc['name'] ?? '', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15, color: kSlate800)),
                                    if (acc['description'] != null) ...[
                                      const SizedBox(height: 4),
                                      Text(acc['description'], style: const TextStyle(color: kSlate500, fontSize: 12)),
                                    ],
                                  ],
                                ),
                              ),
                              const SizedBox(width: 10),
                              Text('+₱${acc['price']}', style: const TextStyle(color: kPink, fontWeight: FontWeight.bold, fontSize: 15)),
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
      },
    );
  }

  @override
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
                    : _schedules.isEmpty
                        ? const Center(child: Text('No trips available for this date.', style: TextStyle(color: kSlate500)))
                        : ListView.builder(
                            padding: const EdgeInsets.symmetric(horizontal: 16),
                            itemCount: _schedules.length,
                            itemBuilder: (context, index) {
                              final s = _schedules[index];
                              return Card(
                                color: Colors.white,
                                margin: const EdgeInsets.only(bottom: 12),
                                elevation: 2,
                                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                                child: InkWell(
                                  onTap: () => _selectTransportOption(context, s),
                                  borderRadius: BorderRadius.circular(16),
                                  child: Padding(
                                    padding: const EdgeInsets.all(16),
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Row(
                                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                          children: [
                                            // Operator Badge
                                            Container(
                                              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                                              decoration: BoxDecoration(color: kGreen.withOpacity(0.08), borderRadius: BorderRadius.circular(8)),
                                              child: Row(
                                                mainAxisSize: MainAxisSize.min,
                                                children: [
                                                  Icon(
                                                    widget.booking.mode == 'ferry' ? Icons.directions_boat : Icons.flight_takeoff,
                                                    color: kGreen,
                                                    size: 13,
                                                  ),
                                                  const SizedBox(width: 6),
                                                  Text(
                                                    s['operator'] ?? 'Operator',
                                                    style: const TextStyle(color: kGreen, fontWeight: FontWeight.bold, fontSize: 12),
                                                  ),
                                                ],
                                              ),
                                            ),
                                            Text('₱${s['price']}', style: const TextStyle(color: kPink, fontWeight: FontWeight.w900, fontSize: 18)),
                                          ],
                                        ),
                                        const SizedBox(height: 12),
                                        // Vehicle name and service name
                                        Row(
                                          children: [
                                            Column(
                                              crossAxisAlignment: CrossAxisAlignment.start,
                                              children: [
                                                Text(s['service'] ?? '', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: kSlate800)),
                                                if (s['vehicle_name'] != null && s['vehicle_name'].toString().trim().isNotEmpty) ...[
                                                  const SizedBox(height: 2),
                                                  Text(s['vehicle_name'], style: const TextStyle(color: kSlate500, fontSize: 11)),
                                                ],
                                              ],
                                            ),
                                          ],
                                        ),
                                        const SizedBox(height: 12),
                                        Row(
                                          children: [
                                            Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                                              Text(s['departure'] ?? '--:--', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: kSlate800)),
                                              const Text('Departure', style: TextStyle(color: kSlate500, fontSize: 11)),
                                            ]),
                                            const Spacer(),
                                            Column(children: [
                                              Icon(widget.booking.mode == 'ferry' ? Icons.directions_boat : Icons.flight, color: kSlate400, size: 20),
                                              Text(s['duration'] ?? '', style: const TextStyle(color: kSlate500, fontSize: 11)),
                                            ]),
                                            const Spacer(),
                                            Column(crossAxisAlignment: CrossAxisAlignment.end, children: [
                                              Text(s['arrival'] ?? '--:--', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: kSlate800)),
                                              const Text('Arrival', style: TextStyle(color: kSlate500, fontSize: 11)),
                                            ]),
                                          ],
                                        ),
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
}

// ==========================================
// SEAT SELECTION (Airlines Only)
// ==========================================
class SeatSelectionScreen extends StatefulWidget {
  final BookingData booking;
  const SeatSelectionScreen({super.key, required this.booking});

  @override
  State<SeatSelectionScreen> createState() => _SeatSelectionScreenState();
}

class _SeatSelectionScreenState extends State<SeatSelectionScreen> {
  int _activePassengerIndex = 0;
  late List<dynamic> _seatRows;
  late List<dynamic> _occupiedSeats;

  @override
  void initState() {
    super.initState();
    final tc = widget.booking.selectedTransportClass;
    _seatRows = tc?['seat_rows'] as List<dynamic>? ?? [];
    _occupiedSeats = widget.booking.selectedSchedule?['occupied_seats'] as List<dynamic>? ?? [];
  }

  bool _isSeatSelectedByOther(String seatId) {
    for (int i = 0; i < widget.booking.passengers.length; i++) {
      if (i != _activePassengerIndex && widget.booking.passengers[i]['seat_number'] == seatId) {
        return true;
      }
    }
    return false;
  }

  @override
  Widget build(BuildContext context) {
    final passengers = widget.booking.passengers;
    final activePassenger = passengers[_activePassengerIndex];
    final allAssigned = passengers.every((p) => p['seat_number'] != null);

    return Scaffold(
      appBar: AppBar(title: const Text('Select Seats')),
      body: Column(
        children: [
          // Passenger selector row
          Container(
            height: 80,
            padding: const EdgeInsets.symmetric(vertical: 10),
            color: Colors.white,
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 16),
              itemCount: passengers.length,
              itemBuilder: (context, idx) {
                final p = passengers[idx];
                final isSelected = _activePassengerIndex == idx;
                final seatStr = p['seat_number'] ?? 'None';
                return Padding(
                  padding: const EdgeInsets.only(right: 10),
                  child: ChoiceChip(
                    label: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Text('Pax ${idx + 1} (${p['type']})', style: TextStyle(fontWeight: FontWeight.bold, color: isSelected ? Colors.white : kSlate700)),
                        Text('Seat: $seatStr', style: TextStyle(fontSize: 11, color: isSelected ? Colors.white.withOpacity(0.8) : kSlate500)),
                      ],
                    ),
                    selected: isSelected,
                    selectedColor: kGreen,
                    backgroundColor: kBgLight,
                    onSelected: (val) {
                      if (val) setState(() => _activePassengerIndex = idx);
                    },
                  ),
                );
              },
            ),
          ),
          
          // Guide / Front of Aircraft
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(vertical: 8),
            color: kGreen.withOpacity(0.05),
            child: const Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Icon(Icons.arrow_upward, size: 16, color: kGreen),
                SizedBox(width: 6),
                Text('FRONT OF AIRCRAFT', style: TextStyle(color: kGreen, fontWeight: FontWeight.bold, fontSize: 12)),
              ],
            ),
          ),

          // Seat Grid
          Expanded(
            child: _seatRows.isEmpty
                ? const Center(child: Text('No seating layout available for this cabin.'))
                : ListView.builder(
                    padding: const EdgeInsets.all(16),
                    itemCount: _seatRows.length,
                    itemBuilder: (context, rIdx) {
                      final row = _seatRows[rIdx];
                      final rowLabel = row['label'].toString();
                      final leftSeats = row['left'] as List<dynamic>? ?? [];
                      final rightSeats = row['right'] as List<dynamic>? ?? [];

                      return Container(
                        margin: const EdgeInsets.only(bottom: 10),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            // Left Column seats
                            Row(
                              children: leftSeats.map((s) {
                                final seatId = s['id'].toString();
                                final label = s['label'].toString();
                                final isOccupied = _occupiedSeats.contains(seatId) || _isSeatSelectedByOther(seatId);
                                final isSelected = activePassenger['seat_number'] == seatId;

                                return _buildSeatButton(seatId, label, isOccupied, isSelected);
                              }).toList(),
                            ),
                            
                            // Aisle spacer
                            Container(
                              width: 32,
                              alignment: Alignment.center,
                              child: Text(rowLabel, style: const TextStyle(fontWeight: FontWeight.bold, color: kSlate400, fontSize: 13)),
                            ),

                            // Right Column seats
                            Row(
                              children: rightSeats.map((s) {
                                final seatId = s['id'].toString();
                                final label = s['label'].toString();
                                final isOccupied = _occupiedSeats.contains(seatId) || _isSeatSelectedByOther(seatId);
                                final isSelected = activePassenger['seat_number'] == seatId;

                                return _buildSeatButton(seatId, label, isOccupied, isSelected);
                              }).toList(),
                            ),
                          ],
                        ),
                      );
                    },
                  ),
          ),

          // Legend
          Container(
            color: Colors.white,
            padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 16),
            child: const Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: [
                _LegendItem(color: Colors.white, borderColor: kSlate300, label: 'Available'),
                _LegendItem(color: kGreen, borderColor: kGreen, label: 'Selected'),
                _LegendItem(color: kSlate200, borderColor: kSlate200, label: 'Occupied'),
              ],
            ),
          ),

          // Bottom Bar
          Container(
            padding: const EdgeInsets.all(16),
            color: Colors.white,
            child: SizedBox(
              width: double.infinity,
              height: 50,
              child: ElevatedButton(
                onPressed: allAssigned
                    ? () {
                        Navigator.push(context, MaterialPageRoute(builder: (_) => DiscountScreen(booking: widget.booking)));
                      }
                    : null,
                style: ElevatedButton.styleFrom(
                  backgroundColor: kPink,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
                child: const Text('Next: Passenger Details', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSeatButton(String seatId, String label, bool isOccupied, bool isSelected) {
    Color bg = Colors.white;
    Color border = kSlate300;
    Color text = kSlate800;

    if (isOccupied) {
      bg = kSlate200;
      border = kSlate200;
      text = kSlate400;
    } else if (isSelected) {
      bg = kGreen;
      border = kGreen;
      text = Colors.white;
    }

    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 4),
      child: SizedBox(
        width: 42,
        height: 42,
        child: OutlinedButton(
          onPressed: isOccupied
              ? null
              : () {
                  setState(() {
                    widget.booking.passengers[_activePassengerIndex]['seat_number'] = seatId;
                    widget.booking.passengers[_activePassengerIndex]['seat_row'] = int.tryParse(seatId.replaceAll(RegExp(r'[^0-9]'), ''));
                    widget.booking.passengers[_activePassengerIndex]['seat_section'] = widget.booking.selectedTransportClass?['name'] ?? 'Economy';
                    
                    // Auto advance to next passenger without seat
                    for (int i = 0; i < widget.booking.passengers.length; i++) {
                      if (widget.booking.passengers[i]['seat_number'] == null) {
                        _activePassengerIndex = i;
                        break;
                      }
                    }
                  });
                },
          style: OutlinedButton.styleFrom(
            padding: EdgeInsets.zero,
            backgroundColor: bg,
            side: BorderSide(color: border, width: 1.5),
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
          ),
          child: Text(label, style: TextStyle(fontWeight: FontWeight.bold, color: text)),
        ),
      ),
    );
  }
}

class _LegendItem extends StatelessWidget {
  final Color color;
  final Color borderColor;
  final String label;

  const _LegendItem({required this.color, required this.borderColor, required this.label});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Container(
          width: 18,
          height: 18,
          decoration: BoxDecoration(
            color: color,
            border: Border.all(color: borderColor, width: 1.5),
            borderRadius: BorderRadius.circular(4),
          ),
        ),
        const SizedBox(width: 6),
        Text(label, style: const TextStyle(fontSize: 12, color: kSlate600)),
      ],
    );
  }
}

// ==========================================
// STEP 3: DISCOUNT (Passenger Details + Discount)
// ==========================================
class DiscountScreen extends StatefulWidget {
  final BookingData booking;
  const DiscountScreen({super.key, required this.booking});

  @override
  State<DiscountScreen> createState() => _DiscountScreenState();
}

class _DiscountScreenState extends State<DiscountScreen> {
  final _formKey = GlobalKey<FormState>();
  List<Map<String, dynamic>> _discounts = [];
  List<TextEditingController> _nameControllers = [];
  List<TextEditingController> _schoolControllers = [];
  List<TextEditingController> _idControllers = [];

  static const _steps = ['Route', 'Schedule', 'Discount', 'Add-ons', 'Submit'];

  @override
  void initState() {
    super.initState();
    _nameControllers = List.generate(widget.booking.passengers.length, (i) {
      return TextEditingController(text: widget.booking.passengers[i]['name'] ?? '');
    });
    _schoolControllers = List.generate(widget.booking.passengers.length, (i) {
      return TextEditingController(text: widget.booking.passengers[i]['school_name'] ?? '');
    });
    _idControllers = List.generate(widget.booking.passengers.length, (i) {
      return TextEditingController(text: widget.booking.passengers[i]['id_number'] ?? '');
    });
    _fetchDiscounts();
  }

  @override
  void dispose() {
    for (var c in _nameControllers) c.dispose();
    for (var c in _schoolControllers) c.dispose();
    for (var c in _idControllers) c.dispose();
    super.dispose();
  }

  void _fetchDiscounts() async {
    try {
      final baseUrl = UserSession.getBaseUrl();
      final res = await http.get(Uri.parse('$baseUrl/api/discounts'));
      if (res.statusCode == 200) {
        final data = jsonDecode(res.body);
        if (data['status'] == 'success') {
          setState(() => _discounts = List<Map<String, dynamic>>.from(data['discounts']));
        }
      }
    } catch (_) {}
  }

  void _goNext() {
    if (!_formKey.currentState!.validate()) return;
    for (int i = 0; i < widget.booking.passengers.length; i++) {
      widget.booking.passengers[i]['name'] = _nameControllers[i].text.trim();
      
      final discId = widget.booking.passengers[i]['discount_id'];
      final disc = _discounts.firstWhere((d) => d['id'] == discId, orElse: () => {});
      final discName = disc['name']?.toString().toLowerCase() ?? '';
      
      if (discName == 'student') {
        widget.booking.passengers[i]['school_name'] = _schoolControllers[i].text.trim();
        widget.booking.passengers[i]['id_number'] = _idControllers[i].text.trim();
      } else if (discName == 'senior citizen' || discName == 'pwd') {
        widget.booking.passengers[i]['school_name'] = null;
        widget.booking.passengers[i]['id_number'] = _idControllers[i].text.trim();
      } else {
        widget.booking.passengers[i]['school_name'] = null;
        widget.booking.passengers[i]['id_number'] = null;
      }
    }
    Navigator.push(context, MaterialPageRoute(builder: (_) => StayScreen(booking: widget.booking)));
  }

  @override
  Widget build(BuildContext context) {
    final s = widget.booking.selectedSchedule!;
    final pax = widget.booking.passengers;

    return Scaffold(
      appBar: AppBar(title: const Text('Passenger & Discount')),
      body: Column(
        children: [
          _StepProgress(currentStep: 3, steps: _steps),
          Expanded(
            child: Form(
              key: _formKey,
              child: ListView(
                padding: const EdgeInsets.all(16),
                children: [
                  // Schedule summary
                  Container(
                    padding: const EdgeInsets.all(14),
                    decoration: BoxDecoration(color: kGreen.withOpacity(0.06), borderRadius: BorderRadius.circular(12)),
                    child: Text(
                      '${widget.booking.origin} → ${widget.booking.destination}  ·  ${s['service']}  ·  ₱${s['price']} / person',
                      style: const TextStyle(fontWeight: FontWeight.bold, color: kGreen, fontSize: 13),
                    ),
                  ),
                  const SizedBox(height: 16),

                  ...List.generate(pax.length, (i) {
                    final type = pax[i]['type'] as String;
                    return Card(
                      color: Colors.white,
                      margin: const EdgeInsets.only(bottom: 14),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                      child: Padding(
                        padding: const EdgeInsets.all(16),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              children: [
                                Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                                  decoration: BoxDecoration(color: (type == 'adult' ? kGreen : kPink).withOpacity(0.1), borderRadius: BorderRadius.circular(20)),
                                  child: Text(
                                    '${type == 'adult' ? 'Adult' : 'Child'} ${i + 1}',
                                    style: TextStyle(color: type == 'adult' ? kGreen : kPink, fontSize: 11, fontWeight: FontWeight.bold),
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 12),
                            TextFormField(
                              controller: _nameControllers[i],
                              decoration: InputDecoration(
                                labelText: 'Full Name',
                                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                              ),
                              validator: (v) => (v == null || v.trim().isEmpty) ? 'Full name is required' : null,
                            ),
                            if (_discounts.isNotEmpty) ...[
                              const SizedBox(height: 10),
                              DropdownButtonFormField<int?>(
                                value: pax[i]['discount_id'],
                                hint: const Text('No Discount'),
                                items: [
                                  const DropdownMenuItem<int?>(value: null, child: Text('No Discount')),
                                  ..._discounts
                                      .where((d) => d['name'].toString().toLowerCase() != 'infant')
                                      .map((d) => DropdownMenuItem<int?>(
                                    value: d['id'] as int,
                                    child: Text('${d['name']} (${d['percentage']}% off)'),
                                  )),
                                ],
                                onChanged: (v) {
                                  setState(() {
                                    pax[i]['discount_id'] = v;
                                  });
                                },
                                decoration: InputDecoration(
                                  labelText: 'Discount',
                                  prefixIcon: const Icon(Icons.local_offer, color: kGreen, size: 18),
                                  border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                                ),
                              ),
                              Builder(
                                builder: (context) {
                                  final discId = pax[i]['discount_id'];
                                  final disc = _discounts.firstWhere((d) => d['id'] == discId, orElse: () => {});
                                  final discName = disc['name']?.toString().toLowerCase() ?? '';

                                  if (discName == 'student') {
                                    return Column(
                                      children: [
                                        const SizedBox(height: 10),
                                        TextFormField(
                                          controller: _schoolControllers[i],
                                          decoration: InputDecoration(
                                            labelText: 'School Name *',
                                            border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                                          ),
                                          validator: (v) => (v == null || v.trim().isEmpty) ? 'School name is required' : null,
                                        ),
                                        const SizedBox(height: 10),
                                        TextFormField(
                                          controller: _idControllers[i],
                                          decoration: InputDecoration(
                                            labelText: 'Student ID Number *',
                                            border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                                          ),
                                          validator: (v) => (v == null || v.trim().isEmpty) ? 'Student ID is required' : null,
                                        ),
                                      ],
                                    );
                                  } else if (discName == 'senior citizen') {
                                    return Column(
                                      children: [
                                        const SizedBox(height: 10),
                                        TextFormField(
                                          controller: _idControllers[i],
                                          decoration: InputDecoration(
                                            labelText: 'Senior Citizen ID Number *',
                                            border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                                          ),
                                          validator: (v) => (v == null || v.trim().isEmpty) ? 'Senior ID is required' : null,
                                        ),
                                      ],
                                    );
                                  } else if (discName == 'pwd') {
                                    return Column(
                                      children: [
                                        const SizedBox(height: 10),
                                        TextFormField(
                                          controller: _idControllers[i],
                                          decoration: InputDecoration(
                                            labelText: 'PWD ID Number *',
                                            border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                                          ),
                                          validator: (v) => (v == null || v.trim().isEmpty) ? 'PWD ID is required' : null,
                                        ),
                                      ],
                                    );
                                  }
                                  return const SizedBox.shrink();
                                },
                              ),
                            ],
                          ],
                        ),
                      ),
                    );
                  }),

                  const SizedBox(height: 8),
                  SizedBox(
                    width: double.infinity,
                    height: 52,
                    child: ElevatedButton(
                      onPressed: _goNext,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: kPink,
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                        elevation: 4,
                      ),
                      child: const Text('Next: Select Stay', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

// ==========================================
// STEP 4: STAY
// ==========================================
class StayScreen extends StatefulWidget {
  final BookingData booking;
  const StayScreen({super.key, required this.booking});

  @override
  State<StayScreen> createState() => _StayScreenState();
}

class _StayScreenState extends State<StayScreen> {
  List<Map<String, dynamic>> _accommodations = [];
  bool _isLoading = true;

  static const _steps = ['Route', 'Schedule', 'Discount', 'Add-ons', 'Submit'];

  @override
  void initState() {
    super.initState();
    _fetchData();
  }

  void _fetchData() async {
    try {
      final baseUrl = UserSession.getBaseUrl();
      final res = await http.get(Uri.parse('$baseUrl/api/accommodations'));
      final accData = jsonDecode(res.body);
      if (res.statusCode == 200 && accData['status'] == 'success') {
        _accommodations = List<Map<String, dynamic>>.from(accData['accommodations']);
      }
    } catch (_) {}
    finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Add-ons')),
      body: Column(
        children: [
          _StepProgress(currentStep: 4, steps: _steps),
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator(color: kGreen))
                : ListView(
                    padding: const EdgeInsets.all(16),
                    children: [
                      // ── Hotel / Accommodation Add-ons ──
                      const Text(
                        'Hotel & Accommodation Add-ons',
                        style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15, color: kSlate800),
                      ),
                      const SizedBox(height: 4),
                      const Text('Select one or more stays to add to your booking.', style: TextStyle(color: kSlate500, fontSize: 12)),
                      const SizedBox(height: 16),

                      if (_accommodations.isEmpty)
                        const Center(
                          child: Padding(
                            padding: EdgeInsets.symmetric(vertical: 24),
                            child: Text('No accommodations available at this time.', style: TextStyle(color: kSlate400)),
                          ),
                        )
                      else
                        ..._accommodations.map((a) {
                          final id = a['id'] as int;
                          final selected = widget.booking.selectedAccommodationIds.contains(id);
                          return Card(
                            color: selected ? kGreen.withOpacity(0.05) : Colors.white,
                            margin: const EdgeInsets.only(bottom: 12),
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(14),
                              side: BorderSide(color: selected ? kGreen : kSlate200, width: selected ? 2 : 1),
                            ),
                            child: InkWell(
                              onTap: () {
                                setState(() {
                                  if (selected) widget.booking.selectedAccommodationIds.remove(id);
                                  else widget.booking.selectedAccommodationIds.add(id);
                                });
                              },
                              borderRadius: BorderRadius.circular(14),
                              child: Padding(
                                padding: const EdgeInsets.all(14),
                                child: Row(
                                  children: [
                                    if (a['cover_image'] != null)
                                      ClipRRect(
                                        borderRadius: BorderRadius.circular(10),
                                        child: Image.network(a['cover_image'] as String, width: 60, height: 60, fit: BoxFit.cover, errorBuilder: (_, __, ___) => Container(width: 60, height: 60, color: kSlate100, child: const Icon(Icons.hotel, color: kSlate400))),
                                      )
                                    else
                                      Container(width: 60, height: 60, decoration: BoxDecoration(color: kSlate100, borderRadius: BorderRadius.circular(10)), child: const Icon(Icons.hotel, color: kSlate400)),
                                    const SizedBox(width: 14),
                                    Expanded(
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Text(a['name'] as String, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: kSlate800)),
                                          if (a['description'] != null)
                                            Text(a['description'] as String, style: const TextStyle(color: kSlate500, fontSize: 12), maxLines: 2, overflow: TextOverflow.ellipsis),
                                          const SizedBox(height: 4),
                                          Text('₱${a['price']}', style: const TextStyle(color: kPink, fontWeight: FontWeight.bold, fontSize: 14)),
                                        ],
                                      ),
                                    ),
                                    Checkbox(
                                      value: selected,
                                      onChanged: (_) {
                                        setState(() {
                                          if (selected) widget.booking.selectedAccommodationIds.remove(id);
                                          else widget.booking.selectedAccommodationIds.add(id);
                                        });
                                      },
                                      activeColor: kGreen,
                                    ),
                                  ],
                                ),
                              ),
                            ),
                          );
                        }),

                      const SizedBox(height: 16),
                      SizedBox(
                        width: double.infinity,
                        height: 52,
                        child: ElevatedButton(
                          onPressed: () {
                            Navigator.push(context, MaterialPageRoute(builder: (_) => BookingSubmitScreen(booking: widget.booking)));
                          },
                          style: ElevatedButton.styleFrom(
                            backgroundColor: kPink,
                            foregroundColor: Colors.white,
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                            elevation: 4,
                          ),
                          child: const Text('Next: Review & Submit', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                        ),
                      ),
                    ],
                  ),
          ),
        ],
      ),
    );
  }
}

// ==========================================
// STEP 5: SUBMIT (Review + Contact Info)
// ==========================================
class BookingSubmitScreen extends StatefulWidget {
  final BookingData booking;
  const BookingSubmitScreen({super.key, required this.booking});

  @override
  State<BookingSubmitScreen> createState() => _BookingSubmitScreenState();
}

class _BookingSubmitScreenState extends State<BookingSubmitScreen> {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _clientNameCtrl;
  late TextEditingController _clientEmailCtrl;
  bool _isSubmitting = false;

  // Payment / QR
  String? _qrCodeUrl;
  bool _loadingPaymentSettings = true;

  // Proof upload state (shown after booking is created)
  int? _bookingId;
  String? _transactionNumber;
  double? _totalPrice;
  XFile? _proofImage;
  bool _isUploadingProof = false;
  bool _proofUploaded = false;

  static const _steps = ['Route', 'Schedule', 'Discount', 'Add-ons', 'Submit'];

  @override
  void initState() {
    super.initState();
    _clientNameCtrl = TextEditingController(text: UserSession.isLoggedIn ? UserSession.username : widget.booking.clientName);
    _clientEmailCtrl = TextEditingController(text: UserSession.isLoggedIn ? UserSession.email : widget.booking.clientEmail);
    _fetchPaymentSettings();
  }

  @override
  void dispose() {
    _clientNameCtrl.dispose();
    _clientEmailCtrl.dispose();
    super.dispose();
  }

  void _fetchPaymentSettings() async {
    try {
      final baseUrl = UserSession.getBaseUrl();
      final res = await http.get(Uri.parse('$baseUrl/api/payment-settings'), headers: {'Accept': 'application/json'});
      if (res.statusCode == 200) {
        final data = jsonDecode(res.body);
        if (data['status'] == 'success') {
          setState(() => _qrCodeUrl = data['qr_code_url']);
        }
      }
    } catch (_) {}
    finally {
      setState(() => _loadingPaymentSettings = false);
    }
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _isSubmitting = true);

    try {
      final baseUrl = UserSession.getBaseUrl();
      final res = await http.post(
        Uri.parse('$baseUrl/api/bookings'),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          if (UserSession.isLoggedIn && UserSession.token.isNotEmpty) 'Authorization': 'Bearer ${UserSession.token}',
        },
        body: jsonEncode({
          'schedule_id': widget.booking.selectedSchedule!['id'],
          'origin': widget.booking.origin,
          'destination': widget.booking.destination,
          'departure_date': widget.booking.departureDate,
          'trip_type': widget.booking.tripType,
          'return_date': widget.booking.returnDate,
          'client_name': _clientNameCtrl.text.trim(),
          'client_email': _clientEmailCtrl.text.trim(),
          'passengers': widget.booking.passengers,
          'accommodation_ids': widget.booking.selectedAccommodationIds,
          // Vehicle
          'has_vehicle': widget.booking.hasVehicle,
          if (widget.booking.hasVehicle) ...{
            'vehicle_rate_id': widget.booking.selectedVehicleRateId,
            'vehicle_type': widget.booking.vehicleType,
            'vehicle_plate_number': widget.booking.vehiclePlateNumber,
            'vehicle_price': widget.booking.vehiclePrice,
          },
        }),
      );
      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['status'] == 'success') {
        // Store booking details — show payment/QR screen instead of navigating away
        setState(() {
          _bookingId = data['booking_id'] as int?;
          _transactionNumber = data['transaction_number'];
          _totalPrice = (data['total_price'] as num).toDouble();
        });
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(data['message'] ?? 'Booking failed.'), backgroundColor: Colors.red),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: $e'), backgroundColor: Colors.red),
      );
    } finally {
      setState(() => _isSubmitting = false);
    }
  }

  Future<void> _pickProofImage() async {
    final picker = ImagePicker();
    final picked = await picker.pickImage(source: ImageSource.gallery, imageQuality: 80);
    if (picked != null) setState(() => _proofImage = picked);
  }

  Future<void> _uploadProof() async {
    if (_proofImage == null || _bookingId == null) return;
    setState(() => _isUploadingProof = true);
    try {
      final baseUrl = UserSession.getBaseUrl();
      final request = http.MultipartRequest('POST', Uri.parse('$baseUrl/api/bookings/$_bookingId/proof'));
      request.headers['Accept'] = 'application/json';
      request.fields['email'] = UserSession.email;
      request.files.add(await http.MultipartFile.fromPath('proof', _proofImage!.path));
      final streamed = await request.send();
      final res = await http.Response.fromStream(streamed);
      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['status'] == 'success') {
        setState(() => _proofUploaded = true);
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Proof of payment uploaded! We will verify it shortly.'), backgroundColor: Colors.green),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(data['message'] ?? 'Upload failed.'), backgroundColor: Colors.red),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Upload error: $e'), backgroundColor: Colors.red),
      );
    } finally {
      setState(() => _isUploadingProof = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    // ── STEP B: Booking created — show payment + proof upload ──
    if (_bookingId != null) {
      return Scaffold(
        appBar: AppBar(title: const Text('Payment'), automaticallyImplyLeading: false),
        body: ListView(
          padding: const EdgeInsets.all(20),
          children: [
            // Success banner
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(color: kGreen.withOpacity(0.08), borderRadius: BorderRadius.circular(14), border: Border.all(color: kGreen.withOpacity(0.3))),
              child: Column(
                children: [
                  const Icon(Icons.check_circle, color: kGreen, size: 48),
                  const SizedBox(height: 8),
                  const Text('Booking Confirmed!', style: TextStyle(fontWeight: FontWeight.w900, fontSize: 20, color: kGreen)),
                  const SizedBox(height: 4),
                  Text('Transaction #: $_transactionNumber', style: const TextStyle(color: kSlate600, fontSize: 13)),
                  const SizedBox(height: 4),
                  Text('Total: ₱${_totalPrice?.toStringAsFixed(2)}', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: kPink)),
                ],
              ),
            ),
            const SizedBox(height: 24),

            // QR Code section
            Card(
              color: Colors.white,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
              child: Padding(
                padding: const EdgeInsets.all(18),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Payment QR Code', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: kSlate800)),
                    const SizedBox(height: 4),
                    const Text('Scan the QR code below to pay via GCash, Maya, or bank transfer.', style: TextStyle(fontSize: 12, color: kSlate500)),
                    const SizedBox(height: 16),
                    Center(
                      child: _loadingPaymentSettings
                          ? const CircularProgressIndicator(color: kGreen)
                          : _qrCodeUrl != null
                              ? ClipRRect(
                                  borderRadius: BorderRadius.circular(12),
                                  child: Image.network(
                                    _qrCodeUrl!,
                                    width: 220,
                                    height: 220,
                                    fit: BoxFit.contain,
                                    errorBuilder: (_, __, ___) => Container(
                                      width: 220, height: 220,
                                      decoration: BoxDecoration(color: kSlate100, borderRadius: BorderRadius.circular(12)),
                                      child: const Column(
                                        mainAxisAlignment: MainAxisAlignment.center,
                                        children: [
                                          Icon(Icons.qr_code, size: 64, color: kSlate400),
                                          SizedBox(height: 8),
                                          Text('QR Code unavailable', style: TextStyle(color: kSlate400, fontSize: 12)),
                                        ],
                                      ),
                                    ),
                                  ),
                                )
                              : Container(
                                  width: 220, height: 220,
                                  decoration: BoxDecoration(color: kSlate100, borderRadius: BorderRadius.circular(12)),
                                  child: const Column(
                                    mainAxisAlignment: MainAxisAlignment.center,
                                    children: [
                                      Icon(Icons.qr_code, size: 64, color: kSlate400),
                                      SizedBox(height: 8),
                                      Text('No QR code set', style: TextStyle(color: kSlate400, fontSize: 12)),
                                      SizedBox(height: 4),
                                      Text('Please contact the admin.', style: TextStyle(color: kSlate400, fontSize: 11)),
                                    ],
                                  ),
                                ),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 20),

            // Proof of payment upload section
            Card(
              color: Colors.white,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
              child: Padding(
                padding: const EdgeInsets.all(18),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Attach Proof of Payment', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: kSlate800)),
                    const SizedBox(height: 4),
                    const Text('Upload a screenshot or photo of your payment receipt.', style: TextStyle(fontSize: 12, color: kSlate500)),
                    const SizedBox(height: 16),

                    if (_proofUploaded)
                      Container(
                        padding: const EdgeInsets.all(14),
                        decoration: BoxDecoration(color: Colors.green.withOpacity(0.08), borderRadius: BorderRadius.circular(10)),
                        child: const Row(
                          children: [
                            Icon(Icons.check_circle, color: Colors.green),
                            SizedBox(width: 10),
                            Expanded(child: Text('Proof uploaded! Our team will verify your payment within 24 hours.', style: TextStyle(color: Colors.green, fontSize: 13))),
                          ],
                        ),
                      )
                    else ...[
                      // Image preview
                      if (_proofImage != null) ...[
                        ClipRRect(
                          borderRadius: BorderRadius.circular(10),
                          child: Image.file(File(_proofImage!.path), height: 180, width: double.infinity, fit: BoxFit.cover),
                        ),
                        const SizedBox(height: 12),
                      ] else
                        GestureDetector(
                          onTap: _pickProofImage,
                          child: Container(
                            height: 120,
                            width: double.infinity,
                            decoration: BoxDecoration(
                              color: kSlate50,
                              border: Border.all(color: kSlate200, width: 2),
                              borderRadius: BorderRadius.circular(12),
                            ),
                            child: const Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Icon(Icons.add_photo_alternate_outlined, size: 40, color: kSlate400),
                                SizedBox(height: 8),
                                Text('Tap to select image', style: TextStyle(color: kSlate400, fontSize: 13)),
                              ],
                            ),
                          ),
                        ),

                      const SizedBox(height: 12),
                      Row(
                        children: [
                          if (_proofImage != null) ...[
                            Expanded(
                              child: OutlinedButton.icon(
                                onPressed: _pickProofImage,
                                icon: const Icon(Icons.image, size: 16),
                                label: const Text('Change Image'),
                                style: OutlinedButton.styleFrom(foregroundColor: kSlate600, side: const BorderSide(color: kSlate200)),
                              ),
                            ),
                            const SizedBox(width: 10),
                          ],
                          Expanded(
                            flex: 2,
                            child: ElevatedButton.icon(
                              onPressed: (_proofImage == null || _isUploadingProof) ? null : _uploadProof,
                              icon: _isUploadingProof
                                  ? const SizedBox(width: 16, height: 16, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                                  : const Icon(Icons.upload, size: 16),
                              label: Text(_isUploadingProof ? 'Uploading...' : 'Upload Proof'),
                              style: ElevatedButton.styleFrom(
                                backgroundColor: kGreen,
                                foregroundColor: Colors.white,
                                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ],
                ),
              ),
            ),
            const SizedBox(height: 24),

            // Done button
            SizedBox(
              width: double.infinity,
              height: 52,
              child: ElevatedButton(
                onPressed: () => Navigator.pushAndRemoveUntil(
                  context,
                  MaterialPageRoute(
                    builder: (_) => BookingSuccessScreen(
                      transactionNumber: _transactionNumber!,
                      totalPrice: _totalPrice!,
                    ),
                  ),
                  (route) => route.isFirst,
                ),
                style: ElevatedButton.styleFrom(
                  backgroundColor: kPink,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  elevation: 4,
                ),
                child: const Text('Done', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
              ),
            ),
          ],
        ),
      );
    }

    // ── STEP A: Review form before submitting ──
    final s = widget.booking.selectedSchedule!;
    final pax = widget.booking.passengers;

    return Scaffold(
      appBar: AppBar(title: const Text('Review & Submit')),
      body: Column(
        children: [
          _StepProgress(currentStep: 5, steps: _steps),
          Expanded(
            child: Form(
              key: _formKey,
              child: ListView(
                padding: const EdgeInsets.all(16),
                children: [
                  // Trip Summary
                  _SummarySection(title: 'Trip Details', children: [
                    _SummaryRow('Route', '${widget.booking.origin} → ${widget.booking.destination}'),
                    _SummaryRow('Mode', widget.booking.mode == 'ferry' ? 'Ferry' : 'Airline'),
                    _SummaryRow('Date', widget.booking.departureDate),
                    _SummaryRow('Trip Type', widget.booking.tripType == 'one_way' ? 'One-Way' : 'Round Trip'),
                    _SummaryRow('Schedule', '${s['service']}  ${s['departure']} – ${s['arrival']}'),
                    _SummaryRow('Fare / person', '₱${s['price']}'),
                  ]),
                  const SizedBox(height: 16),

                  // Passengers
                  _SummarySection(title: 'Passengers', children: [
                    ...List.generate(pax.length, (i) => _SummaryRow(
                      '${pax[i]['type'] == 'adult' ? 'Adult' : 'Child'} ${i + 1}',
                      pax[i]['name'] as String? ?? '',
                    )),
                  ]),
                  const SizedBox(height: 16),

                  // Add-ons
                  if (widget.booking.selectedAccommodationIds.isNotEmpty) ...[
                    _SummarySection(title: 'Add-on Stays', children: [
                      ...widget.booking.selectedAccommodationIds.map((id) {
                        final acc = widget.booking.availableAccommodations.firstWhere(
                          (a) => a['id'] == id, orElse: () => {'name': 'Accommodation #$id', 'price': '—'},
                        );
                        return _SummaryRow(acc['name'] as String, '₱${acc['price']}');
                      }),
                    ]),
                    const SizedBox(height: 16),
                  ],

                  // Vehicle (ferry only)
                  if (widget.booking.hasVehicle) ...[
                    _SummarySection(title: 'Vehicle / Car Booking', children: [
                      _SummaryRow('Vehicle Type', widget.booking.vehicleType.isEmpty ? '—' : widget.booking.vehicleType),
                      _SummaryRow('Plate Number', widget.booking.vehiclePlateNumber.isEmpty ? '—' : widget.booking.vehiclePlateNumber),
                      _SummaryRow('Vehicle Fee', '₱${widget.booking.vehiclePrice.toStringAsFixed(2)}'),
                    ]),
                    const SizedBox(height: 16),
                  ],

                  // Contact
                  Card(
                    color: Colors.white,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                    child: Padding(
                      padding: const EdgeInsets.all(16),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('Contact Details', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: kSlate800)),
                          const SizedBox(height: 12),
                          TextFormField(
                            controller: _clientNameCtrl,
                            decoration: InputDecoration(labelText: 'Contact Name', border: OutlineInputBorder(borderRadius: BorderRadius.circular(10))),
                            validator: (v) => (v == null || v.trim().isEmpty) ? 'Required' : null,
                          ),
                          const SizedBox(height: 12),
                          TextFormField(
                            controller: _clientEmailCtrl,
                            keyboardType: TextInputType.emailAddress,
                            decoration: InputDecoration(labelText: 'Email', border: OutlineInputBorder(borderRadius: BorderRadius.circular(10))),
                            validator: (v) => (v == null || v.trim().isEmpty) ? 'Required' : null,
                          ),
                        ],
                      ),
                    ),
                  ),
                  const SizedBox(height: 24),

                  SizedBox(
                    width: double.infinity,
                    height: 52,
                    child: ElevatedButton(
                      onPressed: _isSubmitting ? null : _submit,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: kGreen,
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                        elevation: 4,
                      ),
                      child: _isSubmitting
                          ? const SizedBox(width: 22, height: 22, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5))
                          : const Text('Submit Booking', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _SummarySection extends StatelessWidget {
  final String title;
  final List<Widget> children;
  const _SummarySection({required this.title, required this.children});

  @override
  Widget build(BuildContext context) {
    return Card(
      color: Colors.white,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: kSlate800)),
            const Divider(height: 16),
            ...children,
          ],
        ),
      ),
    );
  }
}

class _SummaryRow extends StatelessWidget {
  final String label;
  final String value;
  const _SummaryRow(this.label, this.value);

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: const TextStyle(color: kSlate500, fontSize: 13)),
          Flexible(child: Text(value, style: const TextStyle(color: kSlate800, fontSize: 13, fontWeight: FontWeight.bold), textAlign: TextAlign.end)),
        ],
      ),
    );
  }
}

// ==========================================
// BOOKING SUCCESS SCREEN
// ==========================================
class BookingSuccessScreen extends StatelessWidget {
  final String transactionNumber;
  final double totalPrice;

  const BookingSuccessScreen({super.key, required this.transactionNumber, required this.totalPrice});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Booking Success'), automaticallyImplyLeading: false),
      body: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.check_circle, color: kGreen, size: 96),
            const SizedBox(height: 24),
            const Text('Booking Submitted!', style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: kGreen)),
            const SizedBox(height: 8),
            const Text(
              'Your booking has been submitted. Please complete payment to issue your tickets.',
              textAlign: TextAlign.center,
              style: TextStyle(color: kSlate600, fontSize: 14),
            ),
            const SizedBox(height: 32),
            Card(
              color: kSlate100,
              elevation: 0,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
              child: Padding(
                padding: const EdgeInsets.all(20),
                child: Column(
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Text('Transaction #', style: TextStyle(color: kSlate600)),
                        Text(transactionNumber, style: const TextStyle(fontWeight: FontWeight.bold, color: kSlate800)),
                      ],
                    ),
                    const Divider(height: 24),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Text('Total Amount', style: TextStyle(color: kSlate600)),
                        Text('₱${totalPrice.toStringAsFixed(2)}', style: const TextStyle(fontWeight: FontWeight.w900, color: kPink, fontSize: 18)),
                      ],
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 40),
            SizedBox(
              width: double.infinity,
              height: 52,
              child: ElevatedButton(
                onPressed: () => Navigator.popUntil(context, (route) => route.isFirst),
                style: ElevatedButton.styleFrom(backgroundColor: kGreen, foregroundColor: Colors.white, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12))),
                child: const Text('Back to Home', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// ==========================================
// ABOUT SCREEN
// ==========================================
class AboutScreen extends StatelessWidget {
  const AboutScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('About')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Hero
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(24),
              decoration: BoxDecoration(
                gradient: const LinearGradient(colors: [kGreen, Color(0xFF14400e)], begin: Alignment.topLeft, end: Alignment.bottomRight),
                borderRadius: BorderRadius.circular(20),
              ),
              child: const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('About Us', style: TextStyle(color: Colors.white70, fontSize: 12, fontWeight: FontWeight.bold, letterSpacing: 1.2)),
                  SizedBox(height: 6),
                  Text('Our Journey &\nMission', style: TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.w900, height: 1.2)),
                  SizedBox(height: 8),
                  Text('Discover the story behind Amiga Gracia Travel Services and our dedication to making every journey hassle-free.', style: TextStyle(color: Colors.white70, fontSize: 13)),
                ],
              ),
            ),
            const SizedBox(height: 20),
            Card(
              color: Colors.white,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
              child: Padding(
                padding: const EdgeInsets.all(20),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Backed by Experience, Driven by Excellence', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: kSlate800)),
                    const SizedBox(height: 12),
                    const Text(
                      'Amiga Gracia was established in July 2017. Its humble beginning was born from the dedication of its founder, Mrs. MGA-Ting, whose extensive experience with 2GO laid the foundation for the company\'s first-class standard of service.',
                      style: TextStyle(color: kSlate600, fontSize: 13, height: 1.6),
                    ),
                    const SizedBox(height: 12),
                    const Text(
                      'What started in the municipality of Roxas, Oriental Mindoro has expanded. Following the challenges of the pandemic, our main office relocated to the thriving City of Calapan, positioned to serve travelers better than ever.',
                      style: TextStyle(color: kSlate600, fontSize: 13, height: 1.6),
                    ),
                    const SizedBox(height: 20),
                    const Text('Quick Facts', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: kSlate800)),
                    const SizedBox(height: 12),
                    _AboutFact(number: '01', title: 'Established', desc: 'July 2017 in Oriental Mindoro'),
                    const SizedBox(height: 10),
                    _AboutFact(number: '02', title: 'Key Partnerships', desc: '2GO, Starlite Ferries, Supercat'),
                    const SizedBox(height: 10),
                    _AboutFact(number: '03', title: 'Specialty', desc: 'Ferry bookings, Educational tours, Apprenticeship programs'),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 16),
            // Partners
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                gradient: const LinearGradient(colors: [kGreen, Color(0xFF14400e)], begin: Alignment.topLeft, end: Alignment.bottomRight),
                borderRadius: BorderRadius.circular(16),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Our Trusted Travel Operators', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 15)),
                  const SizedBox(height: 14),
                  Row(
                    children: ['2GO TRAVEL', 'STARLITE', 'SUPERCAT'].map((name) => Expanded(
                      child: Container(
                        margin: const EdgeInsets.only(right: 8),
                        padding: const EdgeInsets.symmetric(vertical: 10),
                        decoration: BoxDecoration(color: Colors.white.withOpacity(0.15), borderRadius: BorderRadius.circular(12)),
                        child: Text(name, textAlign: TextAlign.center, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 11, letterSpacing: 0.5)),
                      ),
                    )).toList(),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 16),
            const Text('Kay Amiga, Hassle Free Ka!', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: kSlate800), textAlign: TextAlign.center),
          ],
        ),
      ),
    );
  }
}

class _AboutFact extends StatelessWidget {
  final String number;
  final String title;
  final String desc;
  const _AboutFact({required this.number, required this.title, required this.desc});

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        Container(
          width: 38, height: 38,
          decoration: BoxDecoration(color: kGreen.withOpacity(0.1), borderRadius: BorderRadius.circular(10)),
          child: Center(child: Text(number, style: const TextStyle(color: kGreen, fontWeight: FontWeight.bold, fontSize: 12))),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: kSlate800)),
              Text(desc, style: const TextStyle(color: kSlate500, fontSize: 12)),
            ],
          ),
        ),
      ],
    );
  }
}

// ==========================================
// CONTACT SCREEN
// ==========================================
class ContactScreen extends StatefulWidget {
  const ContactScreen({super.key});

  @override
  State<ContactScreen> createState() => _ContactScreenState();
}

class _ContactScreenState extends State<ContactScreen> {
  final _nameCtrl = TextEditingController();
  final _emailCtrl = TextEditingController();
  final _subjectCtrl = TextEditingController();
  final _msgCtrl = TextEditingController();
  bool _submitted = false;

  @override
  void dispose() {
    _nameCtrl.dispose(); _emailCtrl.dispose(); _subjectCtrl.dispose(); _msgCtrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Contact Us')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            // Info cards
            _ContactInfoCard(icon: Icons.phone, color: kPink, title: 'Phone Numbers', lines: ['Mobile: 0930-928-4278', 'Landline: (043) 738-2989']),
            const SizedBox(height: 12),
            _ContactInfoCard(icon: Icons.email, color: kGreen, title: 'Email Addresses', lines: ['agt.salesmarketing1103@gmail.com', 'amigagracia.travelservices@gmail.com']),
            const SizedBox(height: 12),
            _ContactInfoCard(icon: Icons.location_on, color: Color(0xFF1565C0), title: 'Office Location', lines: ['Roxas Drive, Libis, Calapan City,', 'Oriental Mindoro, 5200']),
            const SizedBox(height: 12),
            _ContactInfoCard(icon: Icons.facebook, color: Color(0xFF7B1FA2), title: 'Social Media', lines: ['Facebook: Amiga Gracia']),
            const SizedBox(height: 20),

            // Form
            if (_submitted)
              Container(
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(color: kGreen.withOpacity(0.08), borderRadius: BorderRadius.circular(16), border: Border.all(color: kGreen.withOpacity(0.2))),
                child: Column(
                  children: [
                    const Icon(Icons.check_circle, color: kGreen, size: 48),
                    const SizedBox(height: 12),
                    const Text('Inquiry Sent!', style: TextStyle(color: kGreen, fontWeight: FontWeight.bold, fontSize: 16)),
                    const Text('Our team will get back to you shortly.', style: TextStyle(color: kSlate500, fontSize: 13), textAlign: TextAlign.center),
                    const SizedBox(height: 12),
                    TextButton(onPressed: () => setState(() => _submitted = false), child: const Text('Send another', style: TextStyle(color: kPink))),
                  ],
                ),
              )
            else
              Card(
                color: Colors.white,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                child: Padding(
                  padding: const EdgeInsets.all(18),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Send an Inquiry', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15, color: kSlate800)),
                      const SizedBox(height: 14),
                      TextField(controller: _nameCtrl, decoration: InputDecoration(labelText: 'Your Name *', border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)))),
                      const SizedBox(height: 10),
                      TextField(controller: _emailCtrl, keyboardType: TextInputType.emailAddress, decoration: InputDecoration(labelText: 'Email Address *', border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)))),
                      const SizedBox(height: 10),
                      TextField(controller: _subjectCtrl, decoration: InputDecoration(labelText: 'Subject', border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)))),
                      const SizedBox(height: 10),
                      TextField(controller: _msgCtrl, maxLines: 4, decoration: InputDecoration(labelText: 'Message *', border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)))),
                      const SizedBox(height: 14),
                      SizedBox(
                        width: double.infinity,
                        height: 48,
                        child: ElevatedButton(
                          onPressed: () {
                            if (_nameCtrl.text.isEmpty || _emailCtrl.text.isEmpty || _msgCtrl.text.isEmpty) {
                              ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Please fill required fields'), backgroundColor: Colors.red));
                              return;
                            }
                            setState(() => _submitted = true);
                          },
                          style: ElevatedButton.styleFrom(backgroundColor: kGreen, foregroundColor: Colors.white, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10))),
                          child: const Text('Send Message', style: TextStyle(fontWeight: FontWeight.bold)),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
          ],
        ),
      ),
    );
  }
}

class _ContactInfoCard extends StatelessWidget {
  final IconData icon;
  final Color color;
  final String title;
  final List<String> lines;
  const _ContactInfoCard({required this.icon, required this.color, required this.title, required this.lines});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(14), border: Border.all(color: kSlate200)),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(color: color.withOpacity(0.1), borderRadius: BorderRadius.circular(12)),
            child: Icon(icon, color: color, size: 22),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: kSlate800)),
                const SizedBox(height: 4),
                ...lines.map((l) => Text(l, style: const TextStyle(color: kSlate600, fontSize: 12))),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

// ==========================================
// SERVICES SCREEN
// ==========================================
class ServicesScreen extends StatelessWidget {
  const ServicesScreen({super.key});

  static const List<Map<String, dynamic>> _services = [
    {
      'title': '2GO Travel Booking',
      'desc': 'Book premier overnight ship accommodation and fast cargo transits with 2GO Travel. Ideal for family retreats, business logistics, and leisure trips.',
      'icon': Icons.directions_boat,
      'color': Color(0xFFEE018D),
      'tag': 'Available Online',
    },
    {
      'title': 'Starlite & Supercat',
      'desc': 'Affordable regional transits between Batangas, Calapan, and Roxas. We manage standard ferry bookings, roll-on/roll-off (RoRo) cargo slots, and fastcraft ticketing.',
      'icon': Icons.sailing,
      'color': Color(0xFF216417),
      'tag': 'Available Online',
    },
    {
      'title': 'Airline Ticketing',
      'desc': 'Domestic & international flights powered by leading carriers including AirAsia, Cebu Pacific, and Philippine Airlines (PAL). Hassle-free check-ins and seat bookings.',
      'icon': Icons.flight,
      'color': Color(0xFF1565C0),
      'tag': 'PAL, CebuPac, AirAsia',
    },
    {
      'title': 'Tour Packages',
      'desc': 'Curated itineraries for both local (Puerto Galera, El Nido, Boracay) and international (Thailand, Japan, Korea) travel hotspots. Complete with accommodations and guides.',
      'icon': Icons.landscape,
      'color': Color(0xFF7B1FA2),
      'tag': 'Local & International',
    },
    {
      'title': 'Apprenticeships & Training',
      'desc': 'Custom-tailored hospitality training programs, onboard apprenticeship training options, and educational field trips in cooperation with 2GO.',
      'icon': Icons.school,
      'color': Color(0xFFF57C00),
      'tag': 'For Academe & Students',
    },
    {
      'title': 'Custom Group Packages',
      'desc': 'Tailored travel packages for corporate retreats, family reunions, and large groups. We handle flight connections, hotel accommodation blocks, and group transport.',
      'icon': Icons.groups,
      'color': Color(0xFF00897B),
      'tag': 'Tailored For Groups',
    },
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Our Services')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          // CTA Banner
          Container(
            margin: const EdgeInsets.only(bottom: 20),
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              gradient: const LinearGradient(colors: [kGreen, Color(0xFF14400e)], begin: Alignment.topLeft, end: Alignment.bottomRight),
              borderRadius: BorderRadius.circular(18),
            ),
            child: const Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('Book Ferry Tickets Directly Online', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 17)),
                SizedBox(height: 6),
                Text('Quickly check available schedules, fares, and cabins. Complete your passenger credentials instantly.', style: TextStyle(color: Colors.white70, fontSize: 12)),
              ],
            ),
          ),
          ..._services.map((s) => Card(
            color: Colors.white,
            margin: const EdgeInsets.only(bottom: 14),
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
            child: Padding(
              padding: const EdgeInsets.all(18),
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(color: (s['color'] as Color).withOpacity(0.1), borderRadius: BorderRadius.circular(14)),
                    child: Icon(s['icon'] as IconData, color: s['color'] as Color, size: 26),
                  ),
                  const SizedBox(width: 14),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(s['title'] as String, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: kSlate800)),
                        const SizedBox(height: 6),
                        Text(s['desc'] as String, style: const TextStyle(color: kSlate500, fontSize: 12, height: 1.5)),
                        const SizedBox(height: 8),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                          decoration: BoxDecoration(color: kSlate100, borderRadius: BorderRadius.circular(10)),
                          child: Text(s['tag'] as String, style: const TextStyle(color: kSlate500, fontSize: 10, fontWeight: FontWeight.bold)),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          )),
        ],
      ),
    );
  }
}

// ==========================================
// TOUR PACKAGES SCREEN
// ==========================================
class TourPackagesScreen extends StatefulWidget {
  const TourPackagesScreen({super.key});

  @override
  State<TourPackagesScreen> createState() => _TourPackagesScreenState();
}

class _TourPackagesScreenState extends State<TourPackagesScreen> with SingleTickerProviderStateMixin {
  late TabController _tabController;

  List<Map<String, dynamic>> _domestic = [];
  List<Map<String, dynamic>> _international = [];
  bool _loadingTours = true;

  Future<void> _fetchTours() async {
    try {
      final res = await http.get(Uri.parse('${UserSession.getBaseUrl()}/api/tours'));
      if (res.statusCode == 200) {
        final List<dynamic> tours = json.decode(res.body) as List<dynamic>;
        List<Map<String, dynamic>> normalized = tours.map((e) {
          final m = Map<String, dynamic>.from(e as Map);
          return _normalizeTour(m);
        }).toList();

        final dom = normalized.where((t) => (t['country'] ?? '').toString().toLowerCase().contains('philipp')).toList();
        final intl = normalized.where((t) => !( (t['country'] ?? '').toString().toLowerCase().contains('philipp'))).toList();
        setState(() {
          _domestic = dom.cast<Map<String, dynamic>>();
          _international = intl.cast<Map<String, dynamic>>();
          _loadingTours = false;
        });
      } else {
        setState(() => _loadingTours = false);
      }
    } catch (e) {
      setState(() => _loadingTours = false);
    }
  }

  Map<String, dynamic> _normalizeTour(Map raw) {
    return {
      'name': raw['tour_name'] ?? raw['tour'] ?? raw['name'] ?? '',
      'detail': raw['duration'] ?? raw['detail'] ?? '',
      'desc': raw['highlights'] ?? raw['desc'] ?? raw['inclusions'] ?? '',
      'price': raw['price_per_pax'] ?? raw['price'] ?? '',
      'tag': raw['promo'] ?? raw['tag'] ?? '',
      'country': raw['country'] ?? '',
      'destinations': raw['destinations'] ?? '',
      'available_dates': raw['available_dates'] ?? raw['departure'] ?? '',
      'hotel': raw['hotel'] ?? '',
      'inclusions': raw['inclusions'] ?? '',
      'exclusions': raw['exclusions'] ?? '',
      'remarks': raw['remarks'] ?? '',
      'raw': raw,
      'gradient': [Color(0xFF1565C0), Color(0xFF42A5F5)],
    };
  }

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
    _fetchTours();
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Tour Packages')),
      body: Column(
        children: [
          Container(
            color: Colors.white,
            child: TabBar(
              controller: _tabController,
              indicatorColor: kPink,
              labelColor: kPink,
              unselectedLabelColor: kSlate600,
              indicatorWeight: 3,
              tabs: const [Tab(text: 'Domestic'), Tab(text: 'International')],
            ),
          ),
          Expanded(
            child: TabBarView(
              controller: _tabController,
              children: [
                _loadingTours ? Center(child: CircularProgressIndicator()) : _PackageList(packages: _domestic),
                _loadingTours ? Center(child: CircularProgressIndicator()) : _PackageList(packages: _international),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _PackageList extends StatelessWidget {
  final List<Map<String, dynamic>> packages;
  const _PackageList({required this.packages});

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: packages.length,
      itemBuilder: (context, i) {
        final p = packages[i];
        final gradient = p['gradient'] as List<Color>;
        return Card(
          margin: const EdgeInsets.only(bottom: 16),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(18)),
          elevation: 3,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                height: 120,
                decoration: BoxDecoration(
                  gradient: LinearGradient(colors: gradient, begin: Alignment.topLeft, end: Alignment.bottomRight),
                  borderRadius: const BorderRadius.vertical(top: Radius.circular(18)),
                ),
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                        decoration: BoxDecoration(color: Colors.white.withOpacity(0.2), borderRadius: BorderRadius.circular(20)),
                        child: Text(p['tag'] as String, style: const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold)),
                      ),
                      const Spacer(),
                      Text(p['name'] as String, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 17)),
                      Text(p['detail'] as String, style: const TextStyle(color: Colors.white70, fontSize: 11)),
                    ],
                  ),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(p['desc'] as String, style: const TextStyle(color: kSlate600, fontSize: 13, height: 1.5)),
                    const SizedBox(height: 12),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                          const Text('Starting from', style: TextStyle(color: kSlate400, fontSize: 11)),
                          Text(p['price'] as String, style: const TextStyle(color: kGreen, fontWeight: FontWeight.w900, fontSize: 16)),
                        ]),
                        ElevatedButton(
                          onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (_) => RequestBookingScreen(package: p))),
                          style: ElevatedButton.styleFrom(backgroundColor: kPink, foregroundColor: Colors.white, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)), padding: const EdgeInsets.symmetric(horizontal: 20)),
                          child: const Text('Book Now', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13)),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}

// ==========================================
// REQUEST BOOKING SCREEN (Slide Form)
// ==========================================
class RequestBookingScreen extends StatefulWidget {
  final Map<String, dynamic>? package;
  const RequestBookingScreen({super.key, this.package});

  @override
  State<RequestBookingScreen> createState() => _RequestBookingScreenState();
}

class _RequestBookingScreenState extends State<RequestBookingScreen> {
  final PageController _pageCtrl = PageController();
  int _page = 0;

  // Form fields
  final _nameCtrl = TextEditingController();
  final _emailCtrl = TextEditingController();
  final _phoneCtrl = TextEditingController();
  String _serviceType = 'Ferry Ticket';
  final _fromCtrl = TextEditingController();
  final _toCtrl = TextEditingController();
  final _dateCtrl = TextEditingController();
  final _passengersCtrl = TextEditingController(text: '1');
  final _notesCtrl = TextEditingController();
  bool _submitted = false;

  static const _services = ['Ferry Ticket', 'Airline Ticket', 'Tour Package', 'Custom Group Package', 'Apprenticeship / Educational Tour'];

  @override
  void initState() {
    super.initState();
    final pkg = widget.package;
    if (pkg != null) {
      _serviceType = 'Tour Package';
      _fromCtrl.text = pkg['destinations'] ?? '';
      _toCtrl.text = pkg['name'] ?? '';
      _dateCtrl.text = pkg['available_dates'] ?? '';
      _passengersCtrl.text = '1';
      _notesCtrl.text = pkg['inclusions'] ?? '';
      _nameCtrl.text = UserSession.username;
      _emailCtrl.text = UserSession.email;
    }
  }

  void _next() {
    if (_page < 2) {
      _pageCtrl.nextPage(duration: const Duration(milliseconds: 300), curve: Curves.easeInOut);
      setState(() => _page++);
    } else {
      setState(() => _submitted = true);
    }
  }

  void _prev() {
    if (_page > 0) {
      _pageCtrl.previousPage(duration: const Duration(milliseconds: 300), curve: Curves.easeInOut);
      setState(() => _page--);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Request Travel Booking')),
      body: _submitted
          ? Center(
              child: Padding(
                padding: const EdgeInsets.all(32),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Icon(Icons.check_circle, color: kGreen, size: 80),
                    const SizedBox(height: 20),
                    const Text('Request Submitted!', style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: kGreen)),
                    const SizedBox(height: 12),
                    const Text('Our travel consultants will contact you within 24-48 hours to confirm your booking.', textAlign: TextAlign.center, style: TextStyle(color: kSlate500, fontSize: 14, height: 1.6)),
                    const SizedBox(height: 32),
                    ElevatedButton(
                      onPressed: () => Navigator.pop(context),
                      style: ElevatedButton.styleFrom(backgroundColor: kGreen, foregroundColor: Colors.white, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12))),
                      child: const Text('Back to Home', style: TextStyle(fontWeight: FontWeight.bold)),
                    ),
                  ],
                ),
              ),
            )
          : Column(
              children: [
                // Step indicator
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Row(
                    children: List.generate(3, (i) => Expanded(
                      child: Row(
                        children: [
                          Expanded(
                            child: Container(
                              height: 4,
                              decoration: BoxDecoration(
                                color: i <= _page ? kGreen : kSlate200,
                                borderRadius: BorderRadius.circular(2),
                              ),
                            ),
                          ),
                          if (i < 2) const SizedBox(width: 4),
                        ],
                      ),
                    )),
                  ),
                ),
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  child: Text(
                    ['Contact Info', 'Trip Details', 'Notes & Submit'][_page],
                    style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: kSlate800),
                  ),
                ),
                const SizedBox(height: 12),
                Expanded(
                  child: PageView(
                    controller: _pageCtrl,
                    physics: const NeverScrollableScrollPhysics(),
                    children: [
                      // Page 1: Contact
                      _FormPage(children: [
                        _Field(ctrl: _nameCtrl, label: 'Full Name *', icon: Icons.person),
                        _Field(ctrl: _emailCtrl, label: 'Email Address *', icon: Icons.email, keyboard: TextInputType.emailAddress),
                        _Field(ctrl: _phoneCtrl, label: 'Phone Number', icon: Icons.phone, keyboard: TextInputType.phone),
                      ]),
                      // Page 2: Trip
                      _FormPage(children: [
                        Padding(
                          padding: const EdgeInsets.only(bottom: 14),
                          child: DropdownButtonFormField<String>(
                            value: _serviceType,
                            items: _services.map((s) => DropdownMenuItem(value: s, child: Text(s))).toList(),
                            onChanged: (v) => setState(() => _serviceType = v!),
                            decoration: InputDecoration(labelText: 'Service Type', prefixIcon: const Icon(Icons.category, color: kGreen), border: OutlineInputBorder(borderRadius: BorderRadius.circular(10))),
                          ),
                        ),
                        _Field(ctrl: _fromCtrl, label: 'From', icon: Icons.location_on),
                        _Field(ctrl: _toCtrl, label: 'To', icon: Icons.navigation),
                        _Field(ctrl: _dateCtrl, label: 'Travel Date', icon: Icons.calendar_today),
                        _Field(ctrl: _passengersCtrl, label: 'Number of Passengers', icon: Icons.people, keyboard: TextInputType.number),
                      ]),
                      // Page 3: Notes
                      _FormPage(children: [
                        Padding(
                          padding: const EdgeInsets.only(bottom: 14),
                          child: TextField(
                            controller: _notesCtrl,
                            maxLines: 5,
                            decoration: InputDecoration(
                              labelText: 'Additional Notes or Requirements',
                              prefixIcon: const Icon(Icons.note, color: kGreen),
                              border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                            ),
                          ),
                        ),
                        Container(
                          padding: const EdgeInsets.all(14),
                          decoration: BoxDecoration(color: kGreen.withOpacity(0.05), borderRadius: BorderRadius.circular(12)),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Text('Summary', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 13, color: kSlate800)),
                              const Divider(),
                              _SummaryRow('Name', _nameCtrl.text),
                              _SummaryRow('Email', _emailCtrl.text),
                              _SummaryRow('Service', _serviceType),
                              _SummaryRow('From → To', '${_fromCtrl.text} → ${_toCtrl.text}'),
                              _SummaryRow('Date', _dateCtrl.text),
                              _SummaryRow('Passengers', _passengersCtrl.text),
                            ],
                          ),
                        ),
                      ]),
                    ],
                  ),
                ),
                // Nav buttons
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Row(
                    children: [
                      if (_page > 0)
                        Expanded(
                          child: OutlinedButton(
                            onPressed: _prev,
                            style: OutlinedButton.styleFrom(foregroundColor: kGreen, side: const BorderSide(color: kGreen), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)), padding: const EdgeInsets.symmetric(vertical: 14)),
                            child: const Text('Back', style: TextStyle(fontWeight: FontWeight.bold)),
                          ),
                        ),
                      if (_page > 0) const SizedBox(width: 12),
                      Expanded(
                        flex: 2,
                        child: ElevatedButton(
                          onPressed: _next,
                          style: ElevatedButton.styleFrom(backgroundColor: kPink, foregroundColor: Colors.white, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)), padding: const EdgeInsets.symmetric(vertical: 14)),
                          child: Text(_page < 2 ? 'Next' : 'Submit Request', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15)),
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
    );
  }
}

class _FormPage extends StatelessWidget {
  final List<Widget> children;
  const _FormPage({required this.children});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Column(children: children),
    );
  }
}

class _Field extends StatelessWidget {
  final TextEditingController ctrl;
  final String label;
  final IconData icon;
  final TextInputType keyboard;

  const _Field({required this.ctrl, required this.label, required this.icon, this.keyboard = TextInputType.text});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 14),
      child: TextField(
        controller: ctrl,
        keyboardType: keyboard,
        decoration: InputDecoration(
          labelText: label,
          prefixIcon: Icon(icon, color: kGreen),
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
        ),
      ),
    );
  }
}

// ==========================================
// GRACIA POINTS SCREEN
// ==========================================
class GraciaPointsScreen extends StatefulWidget {
  const GraciaPointsScreen({super.key});

  @override
  State<GraciaPointsScreen> createState() => _GraciaPointsScreenState();
}

class _GraciaPointsScreenState extends State<GraciaPointsScreen> {
  bool _isLoading = true;
  String _error = '';
  int _currentPoints = 0;
  int _unconvertedSpend = 0;
  Map<String, dynamic>? _activeRule;
  List<dynamic> _history = [];

  @override
  void initState() {
    super.initState();
    _fetchPoints();
  }

  Future<void> _fetchPoints() async {
    if (!UserSession.isLoggedIn || UserSession.token.isEmpty) {
      setState(() {
        _error = 'Please log in to view your Gracia Points.';
        _isLoading = false;
      });
      return;
    }

    try {
      final res = await http.get(
        Uri.parse('${UserSession.getBaseUrl()}/api/gracia-points'),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer ${UserSession.token}',
        },
      );
      
      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['status'] == 'success') {
        setState(() {
          _currentPoints = data['current_points'] ?? 0;
          _unconvertedSpend = data['unconverted_spend_centavos'] ?? 0;
          _activeRule = data['active_rule'];
          _history = data['history'] ?? [];
          _isLoading = false;
        });
      } else {
        setState(() {
          _error = 'Failed to load points data.';
          _isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        _error = 'Network error occurred.';
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    if (!UserSession.isLoggedIn) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.stars, size: 80, color: kSlate300),
            const SizedBox(height: 16),
            const Text('Gracia Points', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            const Text('Sign in to view and use your Gracia Points.', style: TextStyle(color: kSlate500)),
          ],
        ),
      );
    }

    if (_error == 'Please log in to view your Gracia Points.' && UserSession.isLoggedIn) {
      _error = '';
      _isLoading = true;
      Future.microtask(() => _fetchPoints());
    }

    return _isLoading
        ? const Center(child: CircularProgressIndicator(color: kPink))
        : _error.isNotEmpty
              ? Center(child: Text(_error, style: const TextStyle(color: Colors.red)))
              : RefreshIndicator(
                  onRefresh: _fetchPoints,
                  color: kPink,
                  child: ListView(
                    padding: const EdgeInsets.all(16),
                    children: [
                      // Balance Card
                      Card(
                        color: kPink,
                        elevation: 4,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                        child: Padding(
                          padding: const EdgeInsets.all(24),
                          child: Column(
                            children: [
                              const Text('CURRENT BALANCE', style: TextStyle(color: Colors.white70, fontWeight: FontWeight.bold, letterSpacing: 1.2)),
                              const SizedBox(height: 8),
                              Text('$_currentPoints pts', style: const TextStyle(color: Colors.white, fontSize: 40, fontWeight: FontWeight.w900)),
                              const SizedBox(height: 16),
                              if (_activeRule != null) ...[
                                Text('Unconverted Spend: ₱${(_unconvertedSpend / 100).toStringAsFixed(2)}', style: const TextStyle(color: Colors.white)),
                                const SizedBox(height: 4),
                                Text('Earn ${_activeRule!['points_awarded']} pts for every ₱${(_activeRule!['spend_threshold_centavos'] / 100).toStringAsFixed(0)}', style: const TextStyle(color: Colors.white70, fontSize: 12)),
                                const SizedBox(height: 12),
                                LinearProgressIndicator(
                                  value: _unconvertedSpend / _activeRule!['spend_threshold_centavos'],
                                  backgroundColor: Colors.white24,
                                  valueColor: const AlwaysStoppedAnimation<Color>(Colors.white),
                                ),
                              ] else ...[
                                const Text('No active earning rule.', style: TextStyle(color: Colors.white70)),
                              ]
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(height: 24),
                      const Text('RECENT ACTIVITY', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: kSlate800)),
                      const SizedBox(height: 12),
                      if (_history.isEmpty)
                        const Padding(padding: EdgeInsets.all(32), child: Center(child: Text('No points activity yet.', style: TextStyle(color: kSlate400))))
                      else
                        ..._history.map((entry) {
                          final isEarned = entry['entry_type'] == 'earned';
                          final isReversed = entry['entry_type'] == 'reversed';
                          final points = entry['points'];
                          return Card(
                            margin: const EdgeInsets.only(bottom: 8),
                            child: ListTile(
                              leading: Icon(
                                isEarned ? Icons.add_circle : isReversed ? Icons.remove_circle : Icons.admin_panel_settings,
                                color: isEarned ? Colors.green : isReversed ? Colors.red : Colors.orange,
                              ),
                              title: Text(entry['reason'] ?? 'Point adjustment', style: const TextStyle(fontSize: 14)),
                              subtitle: Text(entry['created_at'] != null ? entry['created_at'].toString().substring(0, 10) : ''),
                              trailing: Text('${points > 0 ? '+' : ''}$points', style: TextStyle(fontWeight: FontWeight.bold, color: points > 0 ? Colors.green : Colors.red, fontSize: 16)),
                            ),
                          );
                        }),
                    ],
                  ),
                );
  }
}

// ==========================================
// SCHEDULES SCREEN
// ==========================================
class SchedulesScreen extends StatefulWidget {
  const SchedulesScreen({super.key});
  @override
  State<SchedulesScreen> createState() => _SchedulesScreenState();
}

class _SchedulesScreenState extends State<SchedulesScreen> {
  bool _loading = true;
  List<dynamic> _routes = [];
  String _filterMode = 'all'; // all, ferry, airline

  @override
  void initState() {
    super.initState();
    _fetchSchedules();
  }

  Future<void> _fetchSchedules() async {
    try {
      final baseUrl = UserSession.getBaseUrl();
      final res = await http.get(Uri.parse('$baseUrl/api/all-schedules'));
      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['status'] == 'success') {
        if (mounted) setState(() { _routes = data['routes']; _loading = false; });
      } else {
        if (mounted) setState(() => _loading = false);
      }
    } catch (e) {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_loading) return const Center(child: CircularProgressIndicator(color: kGreen));

    final filteredRoutes = _routes.where((r) {
      if (_filterMode == 'all') return true;
      final mode = r['mode'] ?? 'ferry';
      return mode == _filterMode;
    }).toList();

    return RefreshIndicator(
      onRefresh: _fetchSchedules,
      color: kGreen,
      child: CustomScrollView(
        slivers: [
          SliverToBoxAdapter(
            child: Container(
              color: kGreen,
              padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 30),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.2),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: const Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(Icons.bolt, color: Colors.greenAccent, size: 16),
                        SizedBox(width: 4),
                        Text('Real-time schedules', style: TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold)),
                      ],
                    ),
                  ),
                  const SizedBox(height: 16),
                  const Text('Schedule and Routes', style: TextStyle(color: Colors.white, fontSize: 28, fontWeight: FontWeight.w900)),
                  const SizedBox(height: 8),
                  const Text('Browse available ferry and airline routes with live pricing, departure times, and accommodation options.', style: TextStyle(color: Colors.white70, fontSize: 14)),
                ],
              ),
            ),
          ),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Row(
                children: [
                  _buildFilterBtn('All Routes', 'all', Icons.map),
                  const SizedBox(width: 8),
                  _buildFilterBtn('Ferry', 'ferry', Icons.directions_boat),
                  const SizedBox(width: 8),
                  _buildFilterBtn('Airline', 'airline', Icons.flight),
                ],
              ),
            ),
          ),
          if (filteredRoutes.isEmpty)
            const SliverToBoxAdapter(
              child: Padding(
                padding: EdgeInsets.all(32),
                child: Center(
                  child: Text('No active schedules found.', style: TextStyle(color: kSlate500)),
                ),
              ),
            )
          else
            SliverPadding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              sliver: SliverList(
                delegate: SliverChildBuilderDelegate(
                  (context, index) {
                    final route = filteredRoutes[index];
                    final schedules = route['schedules'] as List<dynamic>;
                    final isFerry = (route['mode'] ?? 'ferry') == 'ferry';
                    
                    return Card(
                      elevation: 3,
                      margin: const EdgeInsets.only(bottom: 24),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16), side: BorderSide(color: kSlate200)),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Container(
                            padding: const EdgeInsets.all(16),
                            decoration: BoxDecoration(
                              color: kSlate50,
                              borderRadius: const BorderRadius.vertical(top: Radius.circular(16)),
                              border: const Border(bottom: BorderSide(color: kSlate200)),
                            ),
                            child: Row(
                              children: [
                                CircleAvatar(
                                  backgroundColor: isFerry ? Colors.blue.withOpacity(0.1) : Colors.amber.withOpacity(0.1),
                                  child: Icon(isFerry ? Icons.directions_boat : Icons.flight, color: isFerry ? Colors.blue : Colors.amber),
                                ),
                                const SizedBox(width: 12),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Row(
                                        children: [
                                          Text(route['origin'], style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                                          const Padding(
                                            padding: EdgeInsets.symmetric(horizontal: 8),
                                            child: Icon(Icons.arrow_forward, size: 16, color: kSlate400),
                                          ),
                                          Text(route['destination'], style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                                        ],
                                      ),
                                      const SizedBox(height: 4),
                                      Text(route['vehicle']?['full_name'] ?? route['operator'] ?? 'Amiga Gracia', style: const TextStyle(fontSize: 12, color: kSlate500)),
                                    ],
                                  ),
                                ),
                              ],
                            ),
                          ),
                          SizedBox(
                            height: 200,
                            child: ListView.separated(
                              scrollDirection: Axis.horizontal,
                              padding: const EdgeInsets.all(16),
                              itemCount: schedules.length,
                              separatorBuilder: (_, __) => const SizedBox(width: 16),
                              itemBuilder: (context, sIndex) {
                                final s = schedules[sIndex];
                                final price = s['price'] ?? 0;
                                return Container(
                                  width: 260,
                                  padding: const EdgeInsets.all(16),
                                  decoration: BoxDecoration(
                                    color: Colors.white,
                                    border: Border.all(color: kSlate200),
                                    borderRadius: BorderRadius.circular(12),
                                  ),
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Row(
                                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                        children: [
                                          Expanded(child: Text(s['service_name'] ?? 'Economy', style: const TextStyle(fontWeight: FontWeight.bold), maxLines: 1, overflow: TextOverflow.ellipsis)),
                                          Container(
                                            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                            decoration: BoxDecoration(color: Colors.green.withOpacity(0.1), borderRadius: BorderRadius.circular(8)),
                                            child: Text('₱$price', style: const TextStyle(color: Colors.green, fontWeight: FontWeight.bold, fontSize: 12)),
                                          ),
                                        ],
                                      ),
                                      const Spacer(),
                                      Row(
                                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                        children: [
                                          Column(
                                            crossAxisAlignment: CrossAxisAlignment.start,
                                            children: [
                                              Text(s['formatted_departure'] ?? s['departure_time'].toString().substring(11, 16), style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                                              const Text('DEPART', style: TextStyle(fontSize: 10, color: kSlate400, fontWeight: FontWeight.bold)),
                                            ],
                                          ),
                                          const Icon(Icons.arrow_right_alt, color: kGreen),
                                          Column(
                                            crossAxisAlignment: CrossAxisAlignment.end,
                                            children: [
                                              Text(s['formatted_arrival'] ?? s['arrival_time'].toString().substring(11, 16), style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                                              const Text('ARRIVE', style: TextStyle(fontSize: 10, color: kSlate400, fontWeight: FontWeight.bold)),
                                            ],
                                          ),
                                        ],
                                      ),
                                      const Spacer(),
                                      Row(
                                        children: [
                                          const Icon(Icons.event, size: 14, color: kSlate400),
                                          const SizedBox(width: 4),
                                          Text(s['departure_time'].toString().substring(0, 10), style: const TextStyle(fontSize: 12, color: kSlate600)),
                                        ],
                                      ),
                                    ],
                                  ),
                                );
                              },
                            ),
                          ),
                        ],
                      ),
                    );
                  },
                  childCount: filteredRoutes.length,
                ),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildFilterBtn(String label, String value, IconData icon) {
    final isActive = _filterMode == value;
    return Expanded(
      child: GestureDetector(
        onTap: () => setState(() => _filterMode = value),
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 12),
          decoration: BoxDecoration(
            color: isActive ? kGreen : Colors.white,
            borderRadius: BorderRadius.circular(12),
            border: Border.all(color: isActive ? kGreen : kSlate200),
            boxShadow: isActive ? [const BoxShadow(color: Colors.black12, blurRadius: 4, offset: Offset(0, 2))] : [],
          ),
          child: Column(
            children: [
              Icon(icon, size: 20, color: isActive ? Colors.white : kSlate600),
              const SizedBox(height: 4),
              Text(label, style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: isActive ? Colors.white : kSlate600)),
            ],
          ),
        ),
      ),
    );
  }
}

