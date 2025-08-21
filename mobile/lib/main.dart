
import 'package:flutter/material.dart';
import 'login_page.dart'; // ← Assure-toi que ce fichier existe

void main() {
  runApp(const CinephoriaApp());
}

class CinephoriaApp extends StatelessWidget {
  const CinephoriaApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Cinéphoria',
      theme: ThemeData(
        brightness: Brightness.dark,
        primaryColor: Colors.deepPurple,
        scaffoldBackgroundColor: Colors.black,
        fontFamily: 'Montserrat', // ← Assure-toi que la police est bien ajoutée dans pubspec.yaml
        textTheme: const TextTheme(
          headlineLarge: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Colors.white),
          bodyMedium: TextStyle(fontSize: 16, color: Colors.white70),
        ),
        appBarTheme: const AppBarTheme(
          backgroundColor: Colors.deepPurple,
          foregroundColor: Colors.white,
        ),
        cardTheme: CardThemeData(
          color: Colors.grey[900],
          elevation: 4,
          margin: const EdgeInsets.all(10),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        ),
      ),
      home: const LoginPage(), // ← Page de démarrage
    );
  }
}
