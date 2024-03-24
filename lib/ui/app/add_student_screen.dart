import 'dart:io';
import 'main_sceen.dart';
import 'student_manager.dart';
import 'package:uuid/uuid.dart';
import '../../models/student.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:flutter/foundation.dart';
import 'package:image_picker/image_picker.dart';
// ignore_for_file: library_private_types_in_public_api, use_build_context_synchronously

class AddStudentScreen extends StatefulWidget {
  static const routeName = '/add-student';

  const AddStudentScreen({super.key});

  @override
  _AddStudentScreenState createState() => _AddStudentScreenState();
}

class _AddStudentScreenState extends State<AddStudentScreen> {
  late File _imageFile;
  final _imagePicker = ImagePicker();
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _nameController;
  late TextEditingController _emailController;
  late TextEditingController _mssvController;

  @override
  void initState() {
    super.initState();
    _nameController = TextEditingController();
    _emailController = TextEditingController();
    _mssvController = TextEditingController();
    _imageFile = File('');
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _mssvController.dispose();
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
        id: const Uuid().v4(),
        name: _nameController.text,
        email: _emailController.text,
        imageUrl: _imageFile.path,
        mssv: _mssvController.text,
      );

      try {
        await studentManager.addStudent(
          newStudent,
          image: _imageFile,
          imageName: _imageFile.path.split('/').last,
        );

        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Thêm sinh viên thành công.'),
            duration: Duration(seconds: 2),
          ),
        );

        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (context) => const MainScreen()),
        );
      } catch (error) {
        if (kDebugMode) {
          print('Error saving student: $error');
        }
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Thêm Sinh Viên'),
        actions: [
          IconButton(
            onPressed: () => _saveStudent(context),
            icon: const Icon(Icons.save),
          ),
        ],
      ),
      body: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: <Widget>[
                TextFormField(
                  controller: _mssvController,
                  decoration: const InputDecoration(labelText: 'Mã Sinh Viên'),
                  validator: (value) {
                    if (value!.isEmpty) {
                      return 'Vui lòng nhập mã sinh viên';
                    }
                    return null;
                  },
                ),
                TextFormField(
                  controller: _nameController,
                  decoration: const InputDecoration(labelText: 'Họ và Tên'),
                  validator: (value) {
                    if (value!.isEmpty) {
                      return 'Vui lòng nhập họ và tên';
                    }
                    return null;
                  },
                ),
                TextFormField(
                  controller: _emailController,
                  decoration: const InputDecoration(labelText: 'Email'),
                  validator: (value) {
                    if (value!.isEmpty || !value.contains('@')) {
                      return 'Vui lòng nhập địa chỉ email hợp lệ';
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 20),
                _imageFile.path.isNotEmpty
                    ? Image.file(
                        _imageFile,
                        height: 200,
                        width: double.infinity,
                        fit: BoxFit.cover,
                      )
                    : const SizedBox(),
                const SizedBox(height: 20),
                Center(
                  child: ElevatedButton.icon(
                    onPressed: _takePicture,
                    icon: const Icon(Icons.camera),
                    label: const Text('Chụp Ảnh'),
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
