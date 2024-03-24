import 'dart:io';
import 'dart:convert';
import 'firebase_service.dart';
import '../models/student.dart';
import 'package:flutter/foundation.dart';

class StudentService extends FirebaseService {
  StudentService([super.authToken]);

  Future<List<Student>> fetchStudents() async {
    final List<Student> students = [];

    try {
      final studentsMap =
          await httpFetch('$databaseUrl/students.json?auth=$token')
              as Map<String, dynamic>?;

      studentsMap?.forEach((studentId, studentData) {
        students.add(
          Student.fromJson({
            'id': studentId,
            ...studentData,
          }),
        );
      });

      return students;
    } catch (error) {
      if (kDebugMode) {
        print(error);
      }
      return students;
    }
  }

  Future<Student?> addStudent(Student student, {File? image}) async {
    try {
      final newStudent = await httpFetch(
        '$databaseUrl/students.json?auth=$token',
        method: HttpMethod.post,
        body: jsonEncode(
          {
            ...student.toJson(),
          },
        ),
      ) as Map<String, dynamic>?;

      return Student(
        id: newStudent!['name'],
        name: student.name,
        email: student.email,
        imageUrl: student.imageUrl,
        mssv: student.mssv,
      );
    } catch (error) {
      if (kDebugMode) {
        print(error);
      }
      return null;
    }
  }

  Future<bool> deleteStudent(String id) async {
    try {
      await httpFetch(
        '$databaseUrl/students/$id.json?auth=$token',
        method: HttpMethod.delete,
      );
      return true;
    } catch (error) {
      if (kDebugMode) {
        print(error);
      }
      return false;
    }
  }
}
