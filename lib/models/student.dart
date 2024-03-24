class Student {
  final String id;
  final String name;
  final String email;
  final String imageUrl;
  final String mssv;

  Student({
    required this.id,
    required this.name,
    required this.email,
    required this.imageUrl,
    required this.mssv,
  });

  Student copyWith({
    String? id,
    String? name,
    String? email,
    String? imageUrl,
    String? mssv,
  }) {
    return Student(
      id: id ?? this.id,
      name: name ?? this.name,
      email: email ?? this.email,
      imageUrl: imageUrl ?? this.imageUrl,
      mssv: mssv ?? this.mssv,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'imageUrl': imageUrl,
      'mssv': mssv,
    };
  }

  static Student fromJson(Map<String, dynamic> json) {
    return Student(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      imageUrl: json['imageUrl'],
      mssv: json['mssv'], // Lấy giá trị mssv từ JSON
    );
  }
}
