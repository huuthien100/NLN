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
          student.toJson(),
        ),
      ) as Map<String, dynamic>?;

      return Student(
        id: newStudent!['name'],
        name: student.name,
        email: student.email,
        imageUrl: student.imageUrl,
      );
    } catch (error) {
      print(error);
      return null;
    }
  }

  Future<Student> updateStudent(Student student) async {
    try {
      final String idToUpdate = student.id;
      final url = '$databaseUrl/students/$idToUpdate.json?auth=$token';
      final response = await http.put(
        Uri.parse(url),
        headers: {
          HttpHeaders.contentTypeHeader: 'application/json',
        },
        body: json.encode(
          student.toJson(),
        ),
      );

      if (response.statusCode == 200) {
        return student;
      } else {
        throw Exception('Không thể cập nhật sinh viên: ${response.statusCode}');
      }
    } catch (error) {
      throw Exception('Không thể cập nhật sinh viên: $error');
    }
  }

  Future<void> deleteStudent(String studentId) async {
    try {
      final url = '$databaseUrl/students/$studentId.json?auth=$token';
      final response = await http.delete(
        Uri.parse(url),
        headers: {
          HttpHeaders.contentTypeHeader: 'application/json',
        },
      );

      if (response.statusCode == 200) {
        print('Sinh viên đã được xóa thành công');
      } else {
        throw Exception('Không thể xóa sinh viên: ${response.statusCode}');
      }
    } catch (error) {
      throw Exception('Không thể xóa sinh viên: $error');
    }
  }
}
