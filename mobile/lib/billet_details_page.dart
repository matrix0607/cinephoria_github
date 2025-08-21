
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class BilletDetailsPage extends StatelessWidget {
  final Map<String, dynamic> details;

  const BilletDetailsPage({super.key, required this.details});

  Future<void> validerBillet(BuildContext context) async {
    try {
      final response = await http.post(
        Uri.parse('http://192.168.169.123/cinephoria/api/valider_billet.php'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'reservation_id': details['id']}),
      );

      final result = jsonDecode(response.body);
      final success = result['success'] ?? false;
      final message = result['message'] ?? 'Réponse inconnue';

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(message),
          backgroundColor: success ? Colors.green : Colors.red,
        ),
      );
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Erreur de connexion au serveur'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Détails du billet')),
      body: Padding(
        padding: const EdgeInsets.all(20.0),
        child: Card(
          elevation: 6,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          child: Padding(
            padding: const EdgeInsets.all(20.0),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  '🎬 Film : ${details['titre']}',
                  style: Theme.of(context).textTheme.headlineSmall,
                ),
                const SizedBox(height: 10),
                Text('📅 Date : ${details['date_heure']}'),
                Text('📍 Salle : ${details['salle']}'),
                Text('📧 Email : ${details['email']}'),
                const SizedBox(height: 20),
                ElevatedButton.icon(
                  icon: const Icon(Icons.check_circle),
                  label: const Text('Valider le billet'),
                  style: ElevatedButton.styleFrom(backgroundColor: Colors.green),
                  onPressed: () => validerBillet(context),
                ),
                const SizedBox(height: 10),
                ElevatedButton(
                  onPressed: () => Navigator.pop(context),
                  child: const Text('Retour'),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
