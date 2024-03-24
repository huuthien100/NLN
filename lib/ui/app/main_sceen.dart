import '../screens.dart';
import '../../main.dart' as app;
import 'package:flutter/material.dart';

class MainScreen extends StatelessWidget {
  const MainScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final AuthManager authManager = AuthManager();

    return Scaffold(
      appBar: AppBar(
        title: const Text('Màn hình chính'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(40.0),
        child: GridView.count(
          crossAxisCount: 2,
          mainAxisSpacing: 35,
          crossAxisSpacing: 35,
          children: <Widget>[
            buildMenuItem(
              context: context,
              icon: Icons.person_add,
              title: 'Thêm thông tin sinh viên',
              onTap: () {
                Navigator.push(
                    context,
                    MaterialPageRoute(
                        builder: (context) => const AddStudentScreen()));
              },
            ),
            buildMenuItem(
              context: context,
              icon: Icons.event_available,
              title: 'Điểm danh sinh viên',
              onTap: () {
                Navigator.push(context,
                    MaterialPageRoute(builder: (context) => const CheckOut()));
              },
            ),
            buildMenuItem(
              context: context,
              icon: Icons.event_note,
              title: 'Buổi điểm danh',
              onTap: () {
                Navigator.push(
                    context,
                    MaterialPageRoute(
                        builder: (context) => const AttendanceScreen()));
              },
            ),
            buildMenuItem(
              context: context,
              icon: Icons
                  .list, // Đây là nơi bạn thêm icon cho mục "Danh sách sinh viên"
              title: 'Danh sách sinh viên', // Tiêu đề của mục
              onTap: () {
                // Hành động khi mục được nhấp
                Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (context) => const StudentListScreen()),
                );
              },
            ),
            buildMenuItem(
              context: context,
              icon: Icons.exit_to_app,
              title: 'Logout',
              onTap: () {
                authManager.logout();
                Navigator.pushReplacement(
                  context,
                  MaterialPageRoute(builder: (context) => const app.MyApp()),
                );
              },
            ),
          ],
        ),
      ),
    );
  }

  Widget buildMenuItem(
      {required BuildContext context,
      required IconData icon,
      required String title,
      required void Function() onTap}) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        decoration: BoxDecoration(
          color: Theme.of(context).colorScheme.primary.withOpacity(0.1),
          borderRadius: BorderRadius.circular(10),
          border: Border.all(color: Theme.of(context).primaryColor, width: 2),
        ),
        child: Padding(
          padding: const EdgeInsets.all(8.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: <Widget>[
              Icon(icon, size: 48),
              const SizedBox(height: 8),
              Text(title,
                  textAlign: TextAlign.center,
                  style: const TextStyle(fontSize: 16)),
            ],
          ),
        ),
      ),
    );
  }
}
