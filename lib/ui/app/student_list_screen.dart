import '../screens.dart';
import '../../models/student.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class StudentListScreen extends StatefulWidget {
  static const routeName = '/student-list';

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
      print('Lỗi khi tải danh sách sinh viên: $error');
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
        title: Text('Danh sách sinh viên'),
      ),
      body: _isLoading
          ? Center(
              child: CircularProgressIndicator(),
            )
          : students.isEmpty
              ? Center(
                  child: Text('Không tìm thấy sinh viên nào.'),
                )
              : ListView.builder(
                  itemCount: students.length,
                  itemBuilder: (ctx, index) {
                    final student = students[index];
                    return ListTile(
                      title: Text(student.name),
                      subtitle: Text(student.id),
                      onTap: () {
                        _navigateToStudentDetail(context, student);
                      },
                      trailing: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          IconButton(
                            icon: Icon(Icons.delete),
                            onPressed: () {
                              _confirmDelete(context, student);
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
        child: Icon(Icons.add),
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

  Future<void> _confirmDelete(BuildContext context, Student student) async {
    final bool confirm = await showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text(
            "Xác nhận Xóa",
            style: TextStyle(
              color: Colors.black,
            ),
          ),
          content: Text(
            "Bạn có chắc muốn xóa sinh viên ${student.name}?",
            style: TextStyle(
              color: Colors.black,
            ),
          ),
          backgroundColor: const Color.fromARGB(255, 246, 246, 246),
          actions: <Widget>[
            TextButton(
              onPressed: () {
                Navigator.of(context).pop(false);
              },
              child: Text(
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
              child: Text(
                "Xóa",
                style: TextStyle(color: Colors.red),
              ),
            ),
          ],
        );
      },
    );

    if (confirm) {
      await _deleteStudent(context, student);
    }
  }

  Future<void> _deleteStudent(BuildContext context, Student student) async {
    try {
      final studentManager =
          Provider.of<StudentManager>(context, listen: false);
      await studentManager.deleteStudent(student.id);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Xóa sinh viên thành công'),
          duration: Duration(seconds: 2),
          action: SnackBarAction(
            label: 'Ẩn',
            onPressed: () {
              // Nếu bạn muốn cài đặt lại sinh viên, hãy thêm mã ở đây
            },
          ),
        ),
      );
    } catch (error) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Không thể xóa sinh viên'),
          duration: Duration(seconds: 2),
        ),
      );
    }
  }
}
