import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:flutter/foundation.dart' show kIsWeb;
import 'package:url_launcher/url_launcher.dart';

void main() {
  runApp(const MyApp());
}

// ==========================================
// BRAND COLORS
// ==========================================
const kGreen = Color(0xFF216417);
const kPink = Color(0xFFEE018D);
const kBgLight = Color(0xFFF8FAFC);
const kSlate800 = Color(0xFF1E293B);
const kSlate600 = Color(0xFF475569);
const kSlate500 = Color(0xFF64748B);
const kSlate400 = Color(0xFF94A3B8);
const kSlate200 = Color(0xFFE2E8F0);
const kSlate100 = Color(0xFFF1F5F9);
const kSlate50 = Color(0xFFF8FAFC);

// ==========================================
// GLOBAL SESSION
// ==========================================
class UserSession {
  static bool isLoggedIn = false;
  static String username = 'Traveler';
  static String email = 'user@amigagracia.com';
  static String token = '';

  static String getBaseUrl() {
    if (kIsWeb) return '';
    return 'http://192.168.1.8:8000';
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

  // Step 3 — Passengers with discounts
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
  const MyApp({super.key});

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
      home: const MainScreen(),
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

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      key: _scaffoldKey,
      drawer: AppDrawer(onLogout: () => setState(() {})),
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(Icons.menu, color: Colors.white),
          onPressed: () => _scaffoldKey.currentState?.openDrawer(),
        ),
        title: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(5),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(8),
              ),
              child: const Icon(Icons.directions_boat, color: kGreen, size: 22),
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
            onBookFerry: () => setState(() => _selectedIndex = 1),
            onBookAirline: () => setState(() => _selectedIndex = 1),
          ),
          const TravelScreen(),
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
            icon: Icon(Icons.directions_boat_outlined),
            activeIcon: Icon(Icons.directions_boat),
            label: 'Travel',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.history_outlined),
            activeIcon: Icon(Icons.history),
            label: 'Activity',
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
  bool _promoLoading = true;

  final List<Map<String, dynamic>> _domesticPackages = [
    {
      'name': 'El Nido Adventure',
      'desc': '3D/2N · Flight + Hotel + Island Tour',
      'price': '₱8,999',
      'tag': 'Best Seller',
      'tagColor': Color(0xFF216417),
      'gradient': [Color(0xFF1565C0), Color(0xFF42A5F5)],
    },
    {
      'name': 'Boracay Island Escape',
      'desc': '3D/2N · Flight + Hotel + Transfers',
      'price': '₱7,499',
      'tag': 'Popular',
      'tagColor': Color(0xFF1565C0),
      'gradient': [Color(0xFF00BCD4), Color(0xFF006064)],
    },
    {
      'name': 'Siargao Surf & Island Tour',
      'desc': '4D/3N · Hotel + Island Hopping + Surf',
      'price': '₱9,299',
      'tag': 'Trending',
      'tagColor': Color(0xFF7B1FA2),
      'gradient': [Color(0xFF00897B), Color(0xFF004D40)],
    },
  ];

  final List<Map<String, dynamic>> _internationalPackages = [
    {
      'name': 'Bangkok & Pattaya',
      'desc': '4D/3N · Flight + 4★ Hotel + City Tour',
      'price': '₱18,499',
      'tag': 'Fly to Bangkok',
      'tagColor': Color(0xFFEE018D),
      'gradient': [Color(0xFFE91E63), Color(0xFF880E4F)],
    },
    {
      'name': 'Seoul & Nami Island',
      'desc': '5D/4N · Flight + Hotel + Visa Assist',
      'price': '₱24,999',
      'tag': 'K-Culture Tour',
      'tagColor': Color(0xFF7B1FA2),
      'gradient': [Color(0xFF7B1FA2), Color(0xFF4A148C)],
    },
    {
      'name': 'Tokyo, Kyoto & Osaka',
      'desc': '6D/5N · Flight + Bullet Train + Hotel',
      'price': '₱38,999',
      'tag': 'Cherry Blossom',
      'tagColor': Color(0xFFC62828),
      'gradient': [Color(0xFFC62828), Color(0xFF7F0000)],
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
          // Hero Banner
          Container(
            height: 190,
            width: double.infinity,
            margin: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              gradient: const LinearGradient(
                colors: [kGreen, Color(0xFF0e2709)],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
              borderRadius: BorderRadius.circular(20),
              boxShadow: [
                BoxShadow(color: kGreen.withOpacity(0.4), blurRadius: 16, offset: const Offset(0, 6))
              ],
            ),
            child: Stack(
              children: [
                Positioned(
                  right: -10,
                  bottom: -10,
                  child: Opacity(
                    opacity: 0.08,
                    child: const Icon(Icons.travel_explore, size: 180, color: Colors.white),
                  ),
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
                        child: const Text(
                          'Kay Amiga, Hassle Free Ka!',
                          style: TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold),
                        ),
                      ),
                      const SizedBox(height: 10),
                      const Text(
                        'Book Ferry Tickets\n& Flights Online',
                        style: TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.w900, height: 1.2),
                      ),
                      const SizedBox(height: 6),
                      const Text(
                        'Calapan • Batangas • Puerto Galera',
                        style: TextStyle(color: Colors.white70, fontSize: 12),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),

          // Promotions Banner (landscape, low height)
          if (_promotions.isNotEmpty) ...[
            SizedBox(
              height: 110,
              child: ListView.builder(
                scrollDirection: Axis.horizontal,
                padding: const EdgeInsets.symmetric(horizontal: 16),
                itemCount: _promotions.length,
                itemBuilder: (context, i) {
                  final promo = _promotions[i];
                  final imgUrl = promo['image_url'] as String?;
                  return Container(
                    width: MediaQuery.of(context).size.width - 48,
                    margin: const EdgeInsets.only(right: 12),
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(14),
                      color: kSlate100,
                    ),
                    clipBehavior: Clip.antiAlias,
                    child: imgUrl != null
                        ? Image.network(imgUrl, fit: BoxFit.cover, errorBuilder: (_, __, ___) => const Center(child: Icon(Icons.image, color: kSlate400, size: 40)))
                        : const Center(child: Icon(Icons.image, color: kSlate400, size: 40)),
                  );
                },
              ),
            ),
            const SizedBox(height: 16),
          ],

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
                    childAspectRatio: 1.5,
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

  @override
  void initState() {
    super.initState();
    _tripTabController = TabController(length: 2, vsync: this);
    _tripTabController.addListener(() => setState(() {}));
    _fetchOrigins();
  }

  @override
  void dispose() {
    _tripTabController.dispose();
    super.dispose();
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
                            value: _origin,
                            hint: const Text('Select Origin'),
                            items: _origins.map((c) => DropdownMenuItem(value: c, child: Text(c))).toList(),
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
                                  value: _destination,
                                  hint: const Text('Select Destination'),
                                  items: _destinations.map((c) => DropdownMenuItem(value: c, child: Text(c))).toList(),
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
                                    onTap: () async {
                                      final url = Uri.parse('http://10.0.2.2:8000/services');
                                      if (await canLaunchUrl(url)) await launchUrl(url, mode: LaunchMode.externalApplication);
                                    },
                                    child: const Row(
                                      children: [
                                        Text('Learn More', style: TextStyle(color: kPink, fontWeight: FontWeight.bold, fontSize: 13)),
                                        SizedBox(width: 4),
                                        Icon(Icons.arrow_forward, color: kPink, size: 14),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ],
                          const SizedBox(height: 24),

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
  final _emailCtrl = TextEditingController();
  final _passCtrl = TextEditingController();
  final _nameCtrl = TextEditingController();
  bool _isLoading = false;
  bool _obscure = true;
  bool _isSignUp = false;

  void _submitAuth() async {
    final email = _emailCtrl.text.trim();
    final password = _passCtrl.text;
    final name = _nameCtrl.text.trim();

    if (email.isEmpty || password.isEmpty || (_isSignUp && name.isEmpty)) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please fill out all required fields.'), backgroundColor: Colors.red),
      );
      return;
    }

    setState(() => _isLoading = true);

    try {
      final baseUrl = UserSession.getBaseUrl();
      final endpoint = _isSignUp ? '/api/register' : '/api/login';
      final body = _isSignUp
          ? {'name': name, 'email': email, 'password': password}
          : {'email': email, 'password': password};

      final response = await http.post(
        Uri.parse('$baseUrl$endpoint'),
        headers: {'Accept': 'application/json'},
        body: body,
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200 && data['status'] == 'success') {
        setState(() {
          UserSession.isLoggedIn = true;
          UserSession.username = data['user']['name'];
          UserSession.email = data['user']['email'];
          UserSession.token = data['token'];
        });
        widget.onLoginSuccess();
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(_isSignUp ? 'Registration successful!' : 'Welcome back, ${data['user']['name']}!'),
            backgroundColor: kGreen,
          ),
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
    if (!UserSession.isLoggedIn) {
      return SingleChildScrollView(
        padding: const EdgeInsets.symmetric(horizontal: 28, vertical: 32),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            const SizedBox(height: 16),
            Container(
              height: 88, width: 88,
              decoration: const BoxDecoration(color: kGreen, shape: BoxShape.circle),
              child: const Icon(Icons.directions_boat, color: Colors.white, size: 46),
            ),
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

            if (_isSignUp) ...[
              TextField(
                controller: _nameCtrl,
                keyboardType: TextInputType.name,
                decoration: InputDecoration(
                  labelText: 'Full Name',
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
                onPressed: _isLoading ? null : _submitAuth,
                style: ElevatedButton.styleFrom(
                  backgroundColor: kPink,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  elevation: 4,
                ),
                child: _isLoading
                    ? const SizedBox(width: 22, height: 22, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5))
                    : Text(_isSignUp ? 'Register' : 'Login', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
              ),
            ),
            const SizedBox(height: 12),
            TextButton(
              onPressed: () => setState(() { _isSignUp = !_isSignUp; _emailCtrl.clear(); _passCtrl.clear(); _nameCtrl.clear(); }),
              child: Text(
                _isSignUp ? 'Already have an account? Login' : "Don't have an account? Register",
                style: const TextStyle(color: kPink, fontWeight: FontWeight.bold),
              ),
            ),
          ],
        ),
      );
    }

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        const Text('My Bookings', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18, color: kSlate800)),
        const SizedBox(height: 12),
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
        ),
      ],
    );
  }
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
              final url = Uri.parse('http://127.0.0.1:8000');
              if (await canLaunchUrl(url)) await launchUrl(url, mode: LaunchMode.externalApplication);
            },
          ),
          const Spacer(),
          if (UserSession.isLoggedIn)
            ListTile(
              leading: const Icon(Icons.logout, color: Colors.redAccent),
              title: const Text('Log out', style: TextStyle(color: Colors.redAccent)),
              onTap: () {
                UserSession.isLoggedIn = false;
                Navigator.pop(context);
                onLogout();
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
                      : Text('$step', style: TextStyle(color: active ? Colors.white : kSlate500, fontSize: 12, fontWeight: FontWeight.bold)),
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

  static const _steps = ['Route', 'Schedule', 'Discount', 'Stay', 'Submit'];

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

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Select Schedule')),
      body: Column(
        children: [
          _StepProgress(currentStep: 2, steps: _steps),
          // Trip summary
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
                                  onTap: () {
                                    widget.booking.selectedSchedule = Map<String, dynamic>.from(s);
                                    // sync passengers from adults+children
                                    widget.booking.passengers = [
                                      for (int i = 0; i < widget.booking.adults; i++) {'type': 'adult', 'name': '', 'discount_id': null},
                                      for (int i = 0; i < widget.booking.children; i++) {'type': 'child', 'name': '', 'discount_id': null},
                                    ];
                                    Navigator.push(context, MaterialPageRoute(builder: (_) => DiscountScreen(booking: widget.booking)));
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
                                            Container(
                                              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                                              decoration: BoxDecoration(color: kGreen.withOpacity(0.1), borderRadius: BorderRadius.circular(12)),
                                              child: Text(s['service'] ?? 'Standard', style: const TextStyle(color: kGreen, fontWeight: FontWeight.bold, fontSize: 12)),
                                            ),
                                            Text('₱${s['price']}', style: const TextStyle(color: kPink, fontWeight: FontWeight.w900, fontSize: 18)),
                                          ],
                                        ),
                                        const SizedBox(height: 14),
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

  static const _steps = ['Route', 'Schedule', 'Discount', 'Stay', 'Submit'];

  @override
  void initState() {
    super.initState();
    _nameControllers = List.generate(widget.booking.passengers.length, (i) {
      return TextEditingController(text: widget.booking.passengers[i]['name'] ?? '');
    });
    _fetchDiscounts();
  }

  @override
  void dispose() {
    for (var c in _nameControllers) c.dispose();
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
                                  ..._discounts.map((d) => DropdownMenuItem<int?>(
                                    value: d['id'] as int,
                                    child: Text('${d['name']} (${d['percentage']}% off)'),
                                  )),
                                ],
                                onChanged: (v) => setState(() => pax[i]['discount_id'] = v),
                                decoration: InputDecoration(
                                  labelText: 'Discount',
                                  prefixIcon: const Icon(Icons.local_offer, color: kGreen, size: 18),
                                  border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                                ),
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
// STEP 4: STAY (Accommodations)
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

  static const _steps = ['Route', 'Schedule', 'Discount', 'Stay', 'Submit'];

  @override
  void initState() {
    super.initState();
    _fetchAccommodations();
  }

  void _fetchAccommodations() async {
    try {
      final baseUrl = UserSession.getBaseUrl();
      final res = await http.get(Uri.parse('$baseUrl/api/accommodations'));
      if (res.statusCode == 200) {
        final data = jsonDecode(res.body);
        if (data['status'] == 'success') {
          setState(() => _accommodations = List<Map<String, dynamic>>.from(data['accommodations']));
        }
      }
    } catch (_) {}
    finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Add-on Stay')),
      body: Column(
        children: [
          _StepProgress(currentStep: 4, steps: _steps),
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator(color: kGreen))
                : ListView(
                    padding: const EdgeInsets.all(16),
                    children: [
                      const Text(
                        'Optional Add-On Accommodations',
                        style: TextStyle(fontWeight: FontWeight.bold, fontSize: 15, color: kSlate800),
                      ),
                      const SizedBox(height: 4),
                      const Text('Select one or more stays to add to your booking.', style: TextStyle(color: kSlate500, fontSize: 12)),
                      const SizedBox(height: 16),

                      if (_accommodations.isEmpty)
                        const Center(
                          child: Padding(
                            padding: EdgeInsets.symmetric(vertical: 32),
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

  static const _steps = ['Route', 'Schedule', 'Discount', 'Stay', 'Submit'];

  @override
  void initState() {
    super.initState();
    _clientNameCtrl = TextEditingController(text: UserSession.isLoggedIn ? UserSession.username : widget.booking.clientName);
    _clientEmailCtrl = TextEditingController(text: UserSession.isLoggedIn ? UserSession.email : widget.booking.clientEmail);
  }

  @override
  void dispose() {
    _clientNameCtrl.dispose();
    _clientEmailCtrl.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _isSubmitting = true);

    try {
      final baseUrl = UserSession.getBaseUrl();
      final res = await http.post(
        Uri.parse('$baseUrl/api/bookings'),
        headers: {'Accept': 'application/json', 'Content-Type': 'application/json'},
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
        }),
      );
      final data = jsonDecode(res.body);
      if (res.statusCode == 200 && data['status'] == 'success') {
        Navigator.pushAndRemoveUntil(
          context,
          MaterialPageRoute(
            builder: (_) => BookingSuccessScreen(
              transactionNumber: data['transaction_number'],
              totalPrice: (data['total_price'] as num).toDouble(),
            ),
          ),
          (route) => route.isFirst,
        );
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

  @override
  Widget build(BuildContext context) {
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
                  if (widget.booking.selectedAccommodationIds.isNotEmpty)
                    _SummarySection(title: 'Add-on Stays', children: [
                      ...widget.booking.selectedAccommodationIds.map((id) {
                        final acc = widget.booking.availableAccommodations.firstWhere(
                          (a) => a['id'] == id, orElse: () => {'name': 'Accommodation #$id', 'price': '—'},
                        );
                        return _SummaryRow(acc['name'] as String, '₱${acc['price']}');
                      }),
                    ]),
                  const SizedBox(height: 16),

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

  static const _domestic = [
    {'name': 'El Nido Adventure', 'detail': '3 Days & 2 Nights · Flight + Hotel + Island Tour', 'desc': 'Discover limestone cliffs, crystal clear lagoons, and pristine beaches of Bacuit Bay. Includes a guided Island Tour A.', 'price': '₱8,999/pax', 'tag': 'Best Seller', 'gradient': [Color(0xFF1565C0), Color(0xFF42A5F5)]},
    {'name': 'Boracay Island Escape', 'detail': '3 Days & 2 Nights · Flight + Hotel + Transfers', 'desc': 'Relax on the world-famous white sand beach. Enjoy sunset paraw sailing, vibrant island nightlife, and local water sports.', 'price': '₱7,499/pax', 'tag': 'Popular', 'gradient': [Color(0xFF00BCD4), Color(0xFF006064)]},
    {'name': 'Siargao Surf & Island Tour', 'detail': '4 Days & 3 Nights · Hotel + Island Hopping + Surf Lesson', 'desc': 'Discover the surfing capital. Tour Guyam, Daku, and Naked island, followed by a professional beginner surf lesson at Cloud 9.', 'price': '₱9,299/pax', 'tag': 'Trending', 'gradient': [Color(0xFF00897B), Color(0xFF004D40)]},
  ];

  static const _international = [
    {'name': 'Bangkok & Pattaya Highlights', 'detail': '4 Days & 3 Nights · Flight + 4★ Hotel + City Tour', 'desc': 'Experience majestic Buddhist temples, vibrant street food markets, and the beach resorts of Pattaya. Includes Grand Palace tour.', 'price': '₱18,499/pax', 'tag': 'Fly to Bangkok', 'gradient': [Color(0xFFE91E63), Color(0xFF880E4F)]},
    {'name': 'Seoul & Nami Island Experience', 'detail': '5 Days & 4 Nights · Flight + Hotel + Visa Assist', 'desc': 'Explore Gyeongbokgung Palace in traditional Hanbok clothing. Cruise to scenic Nami Island and shop in Myeongdong district.', 'price': '₱24,999/pax', 'tag': 'K-Culture Tour', 'gradient': [Color(0xFF7B1FA2), Color(0xFF4A148C)]},
    {'name': 'Tokyo, Kyoto & Osaka Classic', 'detail': '6 Days & 5 Nights · Flight + Bullet Train + Hotel', 'desc': 'Witness the futuristic Tokyo streets, take the Shinkansen bullet train to historic Kyoto shrines, and enjoy street food in Dotonbori, Osaka.', 'price': '₱38,999/pax', 'tag': 'Cherry Blossom', 'gradient': [Color(0xFFC62828), Color(0xFF7F0000)]},
  ];

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
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
                _PackageList(packages: _domestic),
                _PackageList(packages: _international),
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
                          onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const RequestBookingScreen())),
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
  const RequestBookingScreen({super.key});

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
