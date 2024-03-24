import 'ui/screens.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';

Future<void> main() async {
  await dotenv.load();
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (ctx) => AuthManager()),
      ],
      child: Consumer<AuthManager>(
        builder: (context, authManager, child) {
          final colorScheme = ColorScheme.fromSwatch(
            primarySwatch: const MaterialColor(0xFF42A5F5, {
              50: Color(0xFFE3F2FD),
              100: Color(0xFFBBDEFB),
              200: Color(0xFF90CAF9),
              300: Color(0xFF64B5F6),
              400: Color(0xFF42A5F5),
              500: Color(0xFF2196F3),
              600: Color(0xFF1E88E5),
              700: Color(0xFF1976D2),
              800: Color(0xFF1565C0),
              900: Color(0xFF0D47A1),
            }),
          ).copyWith(
            secondary: Colors.deepOrange,
            background: Colors.white,
            surface: Colors.grey[200],
          );

          return MaterialApp(
            title: 'Điểm Danh',
            debugShowCheckedModeBanner: false,
            theme: ThemeData(
              fontFamily: 'Lato',
              colorScheme: colorScheme,
              appBarTheme: AppBarTheme(
                backgroundColor: colorScheme.primary,
                foregroundColor: Colors.white,
                elevation: 4,
                shadowColor: colorScheme.shadow,
              ),
              dialogTheme: DialogTheme(
                titleTextStyle: TextStyle(
                  color: colorScheme.onBackground,
                  fontSize: 24,
                  fontWeight: FontWeight.bold,
                ),
                contentTextStyle: TextStyle(
                  color: colorScheme.onBackground,
                  fontSize: 20,
                ),
              ),
            ),
            home: authManager.isAuth
                ? MainScreen()
                : FutureBuilder(
                    future: authManager.tryAutoLogin(),
                    builder: (context, snapshot) {
                      return snapshot.connectionState == ConnectionState.waiting
                          ? const SplashScreen()
                          : const AuthScreen();
                    },
                  ),
          );
        },
      ),
    );
  }
}
