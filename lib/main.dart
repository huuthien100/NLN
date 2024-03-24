import 'ui/screens.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
// ignore_for_file: use_key_in_widget_constructors

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
        ChangeNotifierProxyProvider<AuthManager, StudentManager>(
          create: (ctx) => StudentManager(),
          update: (ctx, authManager, studentManager) {
            studentManager!.authToken = authManager.authToken;
            return studentManager;
          },
        ),
      ],
      child: Consumer<AuthManager>(
        builder: (context, authManager, child) {
          final colorScheme = ColorScheme.fromSwatch(
            primarySwatch: Colors.blue,
          ).copyWith(
            secondary: Colors.deepOrange,
            background: Colors.white,
            surface: Colors.grey[200],
          );

          return MaterialApp(
            title: 'MyShop',
            debugShowCheckedModeBanner: false,
            theme: ThemeData(
              fontFamily: 'Lato',
              colorScheme: colorScheme,
              appBarTheme: AppBarTheme(
                backgroundColor: colorScheme.primary,
                foregroundColor: colorScheme.onPrimary,
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
                ? const MainScreen()
                : FutureBuilder(
                    future: authManager.tryAutoLogin(),
                    builder: (context, snapshot) {
                      return snapshot.connectionState == ConnectionState.waiting
                          ? const SplashScreen()
                          : const AuthScreen();
                    },
                  ),
            routes: {
              AddStudentScreen.routeName: (ctx) =>
                  const SafeArea(child: AddStudentScreen()),
              StudentListScreen.routeName: (ctx) =>
                  const SafeArea(child: StudentListScreen()),
              StudentDetailScreen.routeName: (ctx) =>
                  const SafeArea(child: StudentDetailScreen()),
            },
          );
        },
      ),
    );
  }
}
