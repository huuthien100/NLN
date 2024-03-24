import 'package:flutter/material.dart';
import 'attendance_detail_screen.dart';
// ignore_for_file: library_private_types_in_public_api

class AttendanceScreen extends StatefulWidget {
  const AttendanceScreen({super.key});

  @override
  _AttendanceScreenState createState() => _AttendanceScreenState();
}

class _AttendanceScreenState extends State<AttendanceScreen> {
  int _currentPage = 0;
  final int _itemsPerPage = 6; // Number of items per page
  late int _totalPages; // Total number of pages

  @override
  void initState() {
    super.initState();
    _totalPages = 0; // Initialize _totalPages
  }

  @override
  Widget build(BuildContext context) {
    final List<String> attendanceList = [
      '01/04/2024 08:00 - 10:00',
      '02/04/2024 09:00 - 11:00',
      '03/04/2024 10:00 - 12:00',
      '03/04/2024 10:00 - 12:00',
      '03/04/2024 10:00 - 12:00',
      '03/04/2024 10:00 - 12:00',
      '03/04/2024 10:00 - 12:00',
      '03/04/2024 10:00 - 12:00',
      '03/04/2024 10:00 - 12:00',
      '03/04/2024 10:00 - 12:00',
      '03/04/2024 10:00 - 12:00',
      '03/04/2024 10:00 - 12:00',
      '03/04/2024 10:00 - 12:00',
      '03/04/2024 10:00 - 12:00',
      '03/04/2024 10:00 - 12:00',
    ];

    _totalPages =
        (attendanceList.length / _itemsPerPage).ceil(); // Calculate total pages

    return Scaffold(
      appBar: AppBar(
        title: const Text('Thông tin buổi điểm danh'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Expanded(
              child: PageView.builder(
                itemCount: _totalPages,
                onPageChanged: (int page) {
                  setState(() {
                    _currentPage = page;
                  });
                },
                itemBuilder: (BuildContext context, int index) {
                  return ListView.builder(
                    itemCount: _itemsPerPage,
                    itemBuilder: (BuildContext context, int idx) {
                      final itemIndex = index * _itemsPerPage + idx;
                      if (itemIndex < attendanceList.length) {
                        return _buildAttendanceItem(
                            context,
                            attendanceList[itemIndex].split(' ')[0],
                            attendanceList[itemIndex].split(' ')[1]);
                      } else {
                        return const SizedBox(); // Return empty container if no more items
                      }
                    },
                  );
                },
              ),
            ),
            _buildPageIndicator(),
          ],
        ),
      ),
    );
  }

  Widget _buildAttendanceItem(BuildContext context, String date, String time) {
    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
              builder: (context) =>
                  AttendanceDetailItemScreen(date: date, time: time)),
        );
      },
      child: Container(
        padding: const EdgeInsets.all(16),
        margin: const EdgeInsets.only(bottom: 16),
        decoration: BoxDecoration(
          border: Border.all(color: Theme.of(context).primaryColor),
          borderRadius: BorderRadius.circular(10),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Ngày: $date',
              style: const TextStyle(fontSize: 18),
            ),
            const SizedBox(height: 10),
            Text(
              'Giờ: $time',
              style: const TextStyle(fontSize: 18),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildPageIndicator() {
    return Row(
      mainAxisAlignment: MainAxisAlignment.center,
      children: List<Widget>.generate(
        _totalPages,
        (int index) {
          return Container(
            width: 8.0,
            height: 8.0,
            margin: const EdgeInsets.symmetric(vertical: 10.0, horizontal: 2.0),
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              color: _currentPage == index ? Colors.blueAccent : Colors.grey,
            ),
          );
        },
      ),
    );
  }
}
