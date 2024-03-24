import 'dart:io';
import '../../models/student.dart';
import '../../models/auth_token.dart';
import 'package:flutter/foundation.dart';
import '../../services/student_service.dart';
import 'package:path_provider/path_provider.dart';

class StudentManager with ChangeNotifier {
  List<Student> _students = [];
  final StudentService _studentService;

  StudentManager([AuthToken? authToken])
      : _studentService = StudentService(authToken);

  set authToken(AuthToken? authToken) {
    _studentService.authToken = authToken;
  }

  Future<void> fetchStudents() async {
    _students = await _studentService.fetchStudents();
    notifyListeners();
  }

  Future<String> _saveImageLocally(File image, String imageName) async {
    final directory = await getApplicationDocumentsDirectory();
    final imagePath = '${directory.path}/$imageName';
    await image.copy(imagePath);
    return imagePath;
  }

  Future<void> addStudent(Student student,
      {File? image, required String imageName}) async {
    try {
      if (image != null) {
        final imagePath = await _saveImageLocally(image, imageName);
        student = student.copyWith(imageUrl: imagePath);
      }

      final newStudent =
          await _studentService.addStudent(student, image: image);
      if (newStudent != null) {
        _students.add(newStudent);
        notifyListeners();
      }
    } catch (error) {
      if (kDebugMode) {
        print('Error adding student: $error');
      }
    }
  }

  Future<void> deleteStudent(String id) async {
    final index = _students.indexWhere((item) => item.id == id);
    Student? existingProduct = _students[index];
    _students.removeAt(index);
    notifyListeners();

    if (!await _studentService.deleteStudent(id)) {
      _students.insert(index, existingProduct);
      notifyListeners();
    }
  }

  int get studentCount {
    return _students.length;
  }

  List<Student> get students {
    return [..._students];
  }

  Student? findById(String id) {
    try {
      return _students.firstWhere((item) => item.id == id);
    } catch (error) {
      return null;
    }
  }
}
