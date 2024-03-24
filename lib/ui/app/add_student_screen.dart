import 'dart:io';
import 'main_sceen.dart';
import 'student_manager.dart';
import '../../models/student.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:image_picker/image_picker.dart';

class AddStudentScreen extends StatefulWidget {
  static const routeName = '/add-student';

  @override
  _AddStudentScreenState createState() => _AddStudentScreenState();
}

class _AddStudentScreenState extends State<AddStudentScreen> {
  late File _imageFile;
  final _imagePicker = ImagePicker();
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _idController;
  late TextEditingController _nameController;
  late TextEditingController _emailController;

  @override
  void initState() {
    super.initState();
    _idController = TextEditingController();
    _nameController = TextEditingController();
    _emailController = TextEditingController();
    _imageFile = File(''); // Initialize with a default empty file
  }

  @override
  void dispose() {
    _idController.dispose();
    _nameController.dispose();
    _emailController.dispose();
    super.dispose();
  }

  Future<void> _takePicture() async {
    final imageFile = await _imagePicker.pickImage(
      source: ImageSource.camera,
      maxWidth: 600,
    );

    if (imageFile != null) {
      setState(() {
        _imageFile = File(imageFile.path);
      });
    }
  }

  Future<void> _saveStudent(BuildContext context) async {
    if (_formKey.currentState!.validate()) {
      _formKey.currentState!.save();

      final studentManager =
          Provider.of<StudentManager>(context, listen: false);

      final newStudent = Student(
        id: _idController.text,
        name: _nameController.text,
        email: _emailController.text,
        imageUrl: _imageFile.path,
      );

      try {
        await studentManager.addStudent(
          newStudent,
          image: _imageFile,
          imageName: _imageFile.path.split('/').last,
        );

        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Thêm sinh viên thành công.'),
            duration: Duration(seconds: 2),
          ),
        );

        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (context) => MainScreen()),
        );
      } catch (error) {
        print('Error saving student: $error');
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Thêm Sinh Viên'),
        actions: [
          IconButton(
            onPressed: () => _saveStudent(context),
            icon: Icon(Icons.save),
          ),
        ],
      ),
      body: SingleChildScrollView(
        child: Padding(
          padding: EdgeInsets.all(16.0),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: <Widget>[
                TextFormField(
                  controller: _idController,
                  decoration: InputDecoration(labelText: 'Mã Sinh Viên'),
                  validator: (value) {
                    if (value!.isEmpty) {
                      return 'Vui lòng nhập mã sinh viên';
                    }
                    return null;
                  },
                ),
                TextFormField(
                  controller: _nameController,
                  decoration: InputDecoration(labelText: 'Họ và Tên'),
                  validator: (value) {
                    if (value!.isEmpty) {
                      return 'Vui lòng nhập họ và tên';
                    }
                    return null;
                  },
                ),
                TextFormField(
                  controller: _emailController,
                  decoration: InputDecoration(labelText: 'Email'),
                  validator: (value) {
                    if (value!.isEmpty || !value.contains('@')) {
                      return 'Vui lòng nhập địa chỉ email hợp lệ';
                    }
                    return null;
                  },
                ),
                SizedBox(height: 20),
                _imageFile.path.isNotEmpty
                    ? Image.file(
                        _imageFile,
                        height: 200,
                        width: double.infinity,
                        fit: BoxFit.cover,
                      )
                    : SizedBox(),
                SizedBox(height: 20),
                Center(
                  child: ElevatedButton.icon(
                    onPressed: _takePicture,
                    icon: Icon(Icons.camera),
                    label: Text('Chụp Ảnh'),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
