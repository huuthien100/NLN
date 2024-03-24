import 'dart:io';
import 'dart:convert';
import 'firebase_service.dart';
import '../models/student.dart';
import '../models/auth_token.dart';
import 'package:http/http.dart' as http;

class StudentService extends FirebaseService {
  StudentService([AuthToken? authToken]) : super(authToken);

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
      print(error);
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
            'mssv': student.mssv, // Bổ sung trường mssv vào dữ liệu gửi đi
          },
        ),
      ) as Map<String, dynamic>?;

      return Student(
        id: newStudent!['name'],
        name: student.name,
        email: student.email,
        imageUrl: student.imageUrl,
        mssv: student.mssv, // Lấy giá trị mssv từ đối tượng gửi đi
      );
    } catch (error) {
      print(error);
      return null;
    }
  }

  Future<void> deleteStudent(String id) async {
    try {
      print('Deleting student with ID: $id');
      final response = await http.delete(
        Uri.parse('$databaseUrl/students/$id.json?auth=$token'),
      );

      // In ra yêu cầu gửi đi
      print(
          'Request URL: ${Uri.parse('$databaseUrl/students/$id.json?auth=$token')}');
      print('Request Method: DELETE');
      print('Request Headers: ${response.headers}');

      // In ra phản hồi từ máy chủ Firebase
      print('Response Status Code: ${response.statusCode}');
      print('Response Body: ${response.body}');

      if (response.statusCode != 200) {
        print('Failed to delete student. Status code: ${response.statusCode}');
        throw HttpException('Failed to delete student');
      } else {
        print('Student deleted successfully.');
      }
    } catch (error) {
      print('Error deleting student: $error');
      rethrow;
    }
  }
}
