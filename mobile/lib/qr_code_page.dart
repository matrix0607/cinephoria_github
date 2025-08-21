
import 'package:flutter/material.dart';
import 'package:qr_flutter/qr_flutter.dart';
import 'seances_page.dart'; // Assure-toi que Seance est bien défini ici

class QRCodePage extends StatelessWidget {
  final Seance seance;
  final String userEmail;

  const QRCodePage({super.key, required this.seance, required this.userEmail});

  @override
  Widget build(BuildContext context) {
    final qrData = '${userEmail}_${seance.film}_${seance.dateDebut}_${seance.salle}';

    return Scaffold(
      appBar: AppBar(title: const Text('Votre billet')),
      body: SingleChildScrollView(
        child: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const SizedBox(height: 30),
              QrImageView(
                data: qrData,
                version: QrVersions.auto,
                size: 250.0,
                backgroundColor: Colors.white, // ✅ Fond blanc pour visibilité
              ),
              const SizedBox(height: 20),
              Text(
                'Film : ${seance.film}\nSalle : ${seance.salle}\nDate : ${seance.dateDebut}\nEmail : $userEmail',
                textAlign: TextAlign.center,
                style: const TextStyle(fontSize: 16),
              ),
              const SizedBox(height: 30),
            ],
          ),
        ),
      ),
    );
  }
}
