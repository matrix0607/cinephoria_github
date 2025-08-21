
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class ResetPasswordPage extends StatefulWidget {
  const ResetPasswordPage({super.key});

  @override
  State<ResetPasswordPage> createState() => _ResetPasswordPageState();
}

class _ResetPasswordPageState extends State<ResetPasswordPage> {
  final TextEditingController emailController = TextEditingController();

  Future<void> resetPassword() async {
    final response = await http.post(
      Uri.parse('http://192.168.169.123/cinephoria/api/reset_password.php'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'email': emailController.text}),
    );

    final data = jsonDecode(response.body);
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text('Réinitialisation'),
        content: Text(
          data['message'] +
              (data['new_password'] != null
                  ? "\nNouveau mot de passe : ${data['new_password']}"
                  : ""),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('OK'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Mot de passe oublié')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            const Text(
              'Entrez votre email pour réinitialiser votre mot de passe',
              style: TextStyle(fontSize: 16),
            ),
            const SizedBox(height: 20),
            TextField(
              controller: emailController,
              decoration: const InputDecoration(
                labelText: 'Email',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: resetPassword,
              child: const Text('Réinitialiser le mot de passe'),
            ),
          ],
        ),
      ),
    );
  }
}
