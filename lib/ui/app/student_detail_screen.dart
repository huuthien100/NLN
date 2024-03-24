import 'dart:io';
import '../../models/student.dart';
import 'package:flutter/material.dart';

class StudentDetailScreen extends StatelessWidget {
  static const routeName = '/student-detail';

  @override
  Widget build(BuildContext context) {
    final Student student =
        ModalRoute.of(context)!.settings.arguments as Student;

    return Scaffold(
      appBar: AppBar(
        title: Text('Chi tiết sinh viên'),
      ),
      body: SingleChildScrollView(
        padding: EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Center(
              child: ClipRRect(
                borderRadius: BorderRadius.circular(8.0),
                child: Image.file(
                  File(student.imageUrl),
                  width: 200,
                  height: 200,
                  fit: BoxFit.cover,
                ),
              ),
            ),
            SizedBox(height: 16.0),
            Text(
              'Tên: ${student.name}',
              style: TextStyle(
                fontSize: 20.0,
              ),
            ),
            SizedBox(height: 8.0),
            Text(
              'Mã sinh viên: ${student.mssv}',
              style: TextStyle(fontSize: 18.0),
            ),
            SizedBox(height: 8.0),
            Text(
              'Email: ${student.email}',
              style: TextStyle(fontSize: 18.0),
            ),
          ],
        ),
      ),
    );
  }
}
