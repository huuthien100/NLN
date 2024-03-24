import 'package:flutter/material.dart';

class AttendanceDetailItemScreen extends StatelessWidget {
  final String date;
  final String time;

  const AttendanceDetailItemScreen(
      {super.key, required this.date, required this.time});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Chi tiết buổi điểm danh'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Thông tin buổi điểm danh',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 10),
            Text(
              'Ngày: $date',
              style: const TextStyle(fontSize: 18),
            ),
            Text(
              'Giờ: $time',
              style: const TextStyle(fontSize: 18),
            ),
            const SizedBox(height: 20),
            const Text(
              'Danh sách sinh viên đã điểm danh',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 10),
            Expanded(
              child: ListView.builder(
                itemCount: 20, // Số lượng sinh viên
                itemBuilder: (context, index) {
                  // Thay thế các dữ liệu dưới đây bằng dữ liệu thực tế từ cơ sở dữ liệu của bạn
                  final studentName = 'Sinh viên $index';
                  final attendanceStatus =
                      index % 2 == 0 ? 'Đã điểm danh' : 'Chưa điểm danh';
                  return ListTile(
                    title: Text(studentName),
                    trailing: Text(attendanceStatus),
                  );
                },
              ),
            ),
          ],
        ),
      ),
    );
  }
}
