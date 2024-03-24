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
        ChangeNotifierProvider(create: (ctx) => StudentManager()),
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
            title: 'Attendance App',
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
            home: authManager.isAuth ? MainScreen() : const AuthScreen(),
            routes: {
              '/add-student': (ctx) => AddStudentScreen(),
              '/student-list': (ctx) => StudentListScreen(),
              '/student-detail': (ctx) => StudentDetailScreen(),
            },
          );
        },
      ),
    );
  }
}
