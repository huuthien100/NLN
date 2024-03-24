import '../screens.dart';
import '../../models/student.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:flutter/foundation.dart';
// ignore_for_file: use_build_context_synchronously, library_private_types_in_public_api

class StudentListScreen extends StatefulWidget {
  static const routeName = '/student-list';

  const StudentListScreen({super.key});

  @override
  _StudentListScreenState createState() => _StudentListScreenState();
}

class _StudentListScreenState extends State<StudentListScreen> {
  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    _fetchStudents();
  }

  Future<void> _fetchStudents() async {
    setState(() {
      _isLoading = true;
    });
    try {
      final studentManager =
          Provider.of<StudentManager>(context, listen: false);
      await studentManager.fetchStudents();
    } catch (error) {
      if (kDebugMode) {
        print('Lỗi khi tải danh sách sinh viên: $error');
      }
    }
    if (mounted) {
      setState(() {
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final studentManager = Provider.of<StudentManager>(context);
    final List<Student> students = studentManager.students;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Danh sách sinh viên'),
      ),
      body: _isLoading
          ? const Center(
              child: CircularProgressIndicator(),
            )
          : students.isEmpty
              ? const Center(
                  child: Text('Không tìm thấy sinh viên nào.'),
                )
              : ListView.builder(
                  itemCount: students.length,
                  itemBuilder: (ctx, index) {
                    final student = students[index];
                    return ListTile(
                      title: Text(student.name),
                      subtitle: Text(student.mssv),
                      onTap: () {
                        _navigateToStudentDetail(context, student);
                      },
                      trailing: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          IconButton(
                            icon: const Icon(Icons.delete),
                            onPressed: () {
                              _confirmDelete(context, student.id);
                            },
                          ),
                        ],
                      ),
                    );
                  },
                ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          _navigateToAddScreen(context);
        },
        child: const Icon(Icons.add),
      ),
    );
  }

  void _navigateToAddScreen(BuildContext context) {
    Navigator.of(context).pushNamed(AddStudentScreen.routeName);
  }

  void _navigateToStudentDetail(BuildContext context, Student student) {
    Navigator.of(context).pushNamed(
      StudentDetailScreen.routeName,
      arguments: student,
    );
  }

  Future<void> _confirmDelete(BuildContext context, String studentId) async {
    final bool confirm = await showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text(
            "Xác nhận Xóa",
            style: TextStyle(
              color: Colors.black,
            ),
          ),
          content: Text(
            "Bạn có chắc muốn xóa sinh viên có ID là $studentId?",
            style: const TextStyle(
              color: Colors.black,
            ),
          ),
          backgroundColor: const Color.fromARGB(255, 246, 246, 246),
          actions: <Widget>[
            TextButton(
              onPressed: () {
                Navigator.of(context).pop(false);
              },
              child: const Text(
                "Hủy",
                style: TextStyle(
                  color: Colors.black,
                ),
              ),
            ),
            TextButton(
              onPressed: () {
                Navigator.of(context).pop(true);
              },
              child: const Text(
                "Xóa",
                style: TextStyle(color: Colors.red),
              ),
            ),
          ],
        );
      },
    );

    if (confirm) {
      await _deleteStudent(context, studentId);
    }
  }

  Future<void> _deleteStudent(BuildContext context, String studentId) async {
    try {
      final studentManager =
          Provider.of<StudentManager>(context, listen: false);
      await studentManager.deleteStudent(studentId);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: const Text('Xóa sinh viên thành công'),
          duration: const Duration(seconds: 2),
          action: SnackBarAction(
            label: 'Ẩn',
            onPressed: () {},
          ),
        ),
      );
    } catch (error) {
      if (kDebugMode) {
        print('Error deleting student: $error');
      } // In ra chi tiết lỗi
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Không thể xóa sinh viên'),
          duration: Duration(seconds: 2),
        ),
      );
    }
  }
}
