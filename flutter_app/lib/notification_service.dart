import 'dart:convert';
import 'dart:io';
import 'package:flutter/foundation.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:path_provider/path_provider.dart';
import 'package:http/http.dart' as http;
import 'package:permission_handler/permission_handler.dart';

class NotificationService {
  static final FlutterLocalNotificationsPlugin _localNotifications = FlutterLocalNotificationsPlugin();

  static Future<void> requestPermission() async {
    if (kIsWeb) return;
    
    // First, try Firebase native permission request (this handles token generation properly)
    final messaging = FirebaseMessaging.instance;
    final settings = await messaging.requestPermission(
      alert: true,
      badge: true,
      sound: true,
    );
    debugPrint('Firebase permission status: ${settings.authorizationStatus}');

    // Fallback to permission handler just in case
    final permissionStatus = await Permission.notification.request();
    debugPrint('Notification permission status: $permissionStatus');
  }

  static Future<void> initialize({Function(Map<String, dynamic>)? onNotificationTap}) async {
    if (kIsWeb) return;

    await Firebase.initializeApp();
    
    // Initialize local notifications for foreground
    const androidInit = AndroidInitializationSettings('@mipmap/ic_launcher');
    const initSettings = InitializationSettings(android: androidInit);
    await _localNotifications.initialize(
      initSettings,
      onDidReceiveNotificationResponse: (NotificationResponse response) {
        if (response.payload != null && onNotificationTap != null) {
          try {
            final data = jsonDecode(response.payload!) as Map<String, dynamic>;
            onNotificationTap(data);
          } catch (e) {
            debugPrint('Failed to parse notification payload: $e');
          }
        }
      },
    );

    // Create a high importance channel

    // Create a high importance channel
    const channel = AndroidNotificationChannel(
      'high_importance_channel', 
      'High Importance Notifications',
      description: 'This channel is used for important notifications.',
      importance: Importance.max,
    );

    await _localNotifications
        .resolvePlatformSpecificImplementation<AndroidFlutterLocalNotificationsPlugin>()
        ?.createNotificationChannel(channel);

    // Ensure notification presentation options are enabled for iOS and macOS.
    await FirebaseMessaging.instance.setForegroundNotificationPresentationOptions(
      alert: true,
      badge: true,
      sound: true,
    );

    final fcmToken = await FirebaseMessaging.instance.getToken();
    debugPrint('FCM token: $fcmToken');

    FirebaseMessaging.instance.getInitialMessage().then((RemoteMessage? message) {
      if (message != null && onNotificationTap != null) {
        onNotificationTap(message.data);
      }
    });

    FirebaseMessaging.onMessageOpenedApp.listen((RemoteMessage message) {
      debugPrint('FCM notification opened: ${message.messageId}');
      if (onNotificationTap != null) {
        onNotificationTap(message.data);
      }
    });

    FirebaseMessaging.onMessage.listen((RemoteMessage message) async {
      RemoteNotification? notification = message.notification;
      AndroidNotification? android = message.notification?.android;

      if (notification != null && android != null) {
        String? imageUrl = android.imageUrl;
        BigPictureStyleInformation? bigPictureStyle;
        
        if (imageUrl != null && imageUrl.isNotEmpty) {
          final String largeIconPath = await _downloadAndSaveFile(imageUrl, 'largeIcon');
          bigPictureStyle = BigPictureStyleInformation(
            FilePathAndroidBitmap(largeIconPath),
            hideExpandedLargeIcon: true,
            contentTitle: notification.title,
            summaryText: notification.body,
          );
        }

        _localNotifications.show(
          notification.hashCode,
          notification.title,
          notification.body,
          NotificationDetails(
            android: AndroidNotificationDetails(
              channel.id,
              channel.name,
              channelDescription: channel.description,
              icon: android.smallIcon ?? '@mipmap/ic_launcher',
              styleInformation: bigPictureStyle,
              importance: Importance.max,
              priority: Priority.high,
              number: 1, // Fallback for launcher badges
            ),
          ),
          payload: jsonEncode(message.data),
        );
      }
    });

    try {
      await FirebaseMessaging.instance.subscribeToTopic('all_users');
      debugPrint('Subscribed to FCM topic all_users');
    } catch (error) {
      debugPrint('Failed to subscribe to all_users topic: $error');
    }
  }

  /// Subscribe to a user-specific FCM topic so booking notifications
  /// (e.g. cancellations) are delivered only to this user's device.
  /// Topic name mirrors what the server derives: user_{md5(lower(email))}
  static Future<void> subscribeToUserTopic(String email) async {
    if (kIsWeb || email.isEmpty) return;
    try {
      // Compute the same md5 hash the PHP server uses
      final normalised = email.trim().toLowerCase();
      final hash = _md5Hex(normalised);
      await FirebaseMessaging.instance.subscribeToTopic('user_$hash');
    } catch (e) {
      debugPrint('FCM user topic subscribe failed: $e');
    }
  }

  /// Unsubscribe from the user-specific topic on logout.
  static Future<void> unsubscribeFromUserTopic(String email) async {
    if (kIsWeb || email.isEmpty) return;
    try {
      final normalised = email.trim().toLowerCase();
      final hash = _md5Hex(normalised);
      await FirebaseMessaging.instance.unsubscribeFromTopic('user_$hash');
    } catch (e) {
      debugPrint('FCM user topic unsubscribe failed: $e');
    }
  }

  /// Simple MD5 hex implementation matching PHP's md5() output.
  static String _md5Hex(String input) {
    // Dart's built-in crypto is not included; we replicate PHP md5 output
    // using a simple byte-level approach. The dart:convert library does not
    // provide MD5 natively — we compute it manually via the standard
    // dart:typed_data approach to match PHP md5(strtolower(trim(email))).
    final bytes = utf8.encode(input);
    final digest = _computeMd5(bytes);
    return digest.map((b) => b.toRadixString(16).padLeft(2, '0')).join();
  }

  // MD5 implementation ported from the RSA Data Security reference.
  static List<int> _computeMd5(List<int> input) {
    // Per-round shift amounts
    const s = [
      7, 12, 17, 22, 7, 12, 17, 22, 7, 12, 17, 22, 7, 12, 17, 22,
      5,  9, 14, 20, 5,  9, 14, 20, 5,  9, 14, 20, 5,  9, 14, 20,
      4, 11, 16, 23, 4, 11, 16, 23, 4, 11, 16, 23, 4, 11, 16, 23,
      6, 10, 15, 21, 6, 10, 15, 21, 6, 10, 15, 21, 6, 10, 15, 21,
    ];
    // Precomputed table T[i] = floor(2^32 * abs(sin(i+1)))
    const k = [
      0xd76aa478, 0xe8c7b756, 0x242070db, 0xc1bdceee,
      0xf57c0faf, 0x4787c62a, 0xa8304613, 0xfd469501,
      0x698098d8, 0x8b44f7af, 0xffff5bb1, 0x895cd7be,
      0x6b901122, 0xfd987193, 0xa679438e, 0x49b40821,
      0xf61e2562, 0xc040b340, 0x265e5a51, 0xe9b6c7aa,
      0xd62f105d, 0x02441453, 0xd8a1e681, 0xe7d3fbc8,
      0x21e1cde6, 0xc33707d6, 0xf4d50d87, 0x455a14ed,
      0xa9e3e905, 0xfcefa3f8, 0x676f02d9, 0x8d2a4c8a,
      0xfffa3942, 0x8771f681, 0x6d9d6122, 0xfde5380c,
      0xa4beea44, 0x4bdecfa9, 0xf6bb4b60, 0xbebfbc70,
      0x289b7ec6, 0xeaa127fa, 0xd4ef3085, 0x04881d05,
      0xd9d4d039, 0xe6db99e5, 0x1fa27cf8, 0xc4ac5665,
      0xf4292244, 0x432aff97, 0xab9423a7, 0xfc93a039,
      0x655b59c3, 0x8f0ccc92, 0xffeff47d, 0x85845dd1,
      0x6fa87e4f, 0xfe2ce6e0, 0xa3014314, 0x4e0811a1,
      0xf7537e82, 0xbd3af235, 0x2ad7d2bb, 0xeb86d391,
    ];

    int a0 = 0x67452301;
    int b0 = 0xefcdab89;
    int c0 = 0x98badcfe;
    int d0 = 0x10325476;

    // Pre-processing
    final msgLen = input.length;
    final bitLen = msgLen * 8;
    final padLen = (msgLen % 64 < 56) ? (56 - msgLen % 64) : (120 - msgLen % 64);
    final msg = Uint8List(msgLen + padLen + 8);
    msg.setAll(0, input);
    msg[msgLen] = 0x80;
    final view = ByteData.sublistView(msg);
    view.setUint32(msgLen + padLen, bitLen & 0xFFFFFFFF, Endian.little);
    view.setUint32(msgLen + padLen + 4, (bitLen >> 32) & 0xFFFFFFFF, Endian.little);

    for (int chunkStart = 0; chunkStart < msg.length; chunkStart += 64) {
      final m = List<int>.generate(16, (i) => view.getUint32(chunkStart + i * 4, Endian.little));
      int a = a0, b = b0, c = c0, d = d0;
      for (int i = 0; i < 64; i++) {
        int f, g;
        if (i < 16) { f = (b & c) | (~b & d); g = i; }
        else if (i < 32) { f = (d & b) | (~d & c); g = (5 * i + 1) % 16; }
        else if (i < 48) { f = b ^ c ^ d; g = (3 * i + 5) % 16; }
        else { f = c ^ (b | ~d); g = (7 * i) % 16; }
        f = (f + a + k[i] + m[g]) & 0xFFFFFFFF;
        a = d; d = c; c = b;
        b = (b + ((f << s[i]) | (f >>> (32 - s[i])))) & 0xFFFFFFFF;
      }
      a0 = (a0 + a) & 0xFFFFFFFF;
      b0 = (b0 + b) & 0xFFFFFFFF;
      c0 = (c0 + c) & 0xFFFFFFFF;
      d0 = (d0 + d) & 0xFFFFFFFF;
    }

    final result = ByteData(16);
    result.setUint32(0, a0, Endian.little);
    result.setUint32(4, b0, Endian.little);
    result.setUint32(8, c0, Endian.little);
    result.setUint32(12, d0, Endian.little);
    return result.buffer.asUint8List();
  }

  static Future<String> _downloadAndSaveFile(String url, String fileName) async {
    final Directory directory = await getApplicationDocumentsDirectory();
    final String filePath = '${directory.path}/$fileName.png';
    final http.Response response = await http.get(Uri.parse(url));
    final File file = File(filePath);
    await file.writeAsBytes(response.bodyBytes);
    return filePath;
  }
}

// Background handler (must be top level)
@pragma('vm:entry-point')
Future<void> firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  await Firebase.initializeApp();
}
