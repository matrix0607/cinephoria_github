
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:intl/intl.dart';

import 'qr_code_page.dart'; // Import de la page QR code

class Seance {
  final String film;
  final String affiche;
  final String salle;
  final String dateDebut;
  final String dateFin;
  final int nombrePersonnes;

  Seance({
    required this.film,
    required this.affiche,
    required this.salle,
    required this.dateDebut,
    required this.dateFin,
    required this.nombrePersonnes,
  });

  factory Seance.fromJson(Map<String, dynamic> json) {
    return Seance(
      film: json['film'],
      affiche: json['affiche'],
      salle: json['salle'],
      dateDebut: json['date_heure_debut'],
      dateFin: json['date_heure_fin'],
      nombrePersonnes: json['nombre_personnes'],
    );
  }
}

class SeancesPage extends StatefulWidget {
  final int userId;
  final String userEmail;

  const SeancesPage({super.key, required this.userId, required this.userEmail});

  @override
  State<SeancesPage> createState() => _SeancesPageState();
}

class _SeancesPageState extends State<SeancesPage> {
  late Future<List<Seance>> _seances;

  @override
  void initState() {
    super.initState();
    _seances = fetchSeances();
  }

  Future<List<Seance>> fetchSeances() async {
    final response = await http.get(
      Uri.parse('http://192.168.169.123/cinephoria/api/getUserSessions.php?user_id=${widget.userId}'),
    );

    if (response.statusCode == 200) {
      final List<dynamic> data = json.decode(response.body);
      return data.map((json) => Seance.fromJson(json)).toList();
    } else {
      throw Exception('Erreur lors du chargement des séances');
    }
  }

  @override
  Widget build(BuildContext context) {
    final dateFormatter = DateFormat('dd/MM/yyyy HH:mm');
    final today = DateTime.now();

    return Scaffold(
      appBar: AppBar(
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('Séances'),
            Text(
              'Connecté : ${widget.userEmail}',
              style: const TextStyle(fontSize: 14),
            ),
          ],
        ),
      ),
      body: FutureBuilder<List<Seance>>(
        future: _seances,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(child: Text('Erreur : ${snapshot.error}'));
          } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return const Center(
              child: Text(
                'Aucune séance disponible.',
                textAlign: TextAlign.center,
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.w500),
              ),
            );
          } else {
            final seancesDuJour = snapshot.data!.where((seance) {
              final date = DateTime.parse(seance.dateDebut);
              return date.year == today.year &&
                     date.month == today.month &&
                     date.day == today.day;
            }).toList();

            final seancesAVenir = snapshot.data!.where((seance) {
              final date = DateTime.parse(seance.dateDebut);
              return date.isAfter(today);
            }).toList();

            return ListView(
              children: [
                if (seancesDuJour.isNotEmpty)
                  const Padding(
                    padding: EdgeInsets.all(8.0),
                    child: Text(
                      'Séances du jour',
                      style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                    ),
                  ),
                ...seancesDuJour.map((seance) => buildSeanceCard(seance, dateFormatter)),

                if (seancesAVenir.isNotEmpty)
                  const Padding(
                    padding: EdgeInsets.all(8.0),
                    child: Text(
                      'Séances à venir',
                      style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                    ),
                  ),
                ...seancesAVenir.map((seance) => buildSeanceCard(seance, dateFormatter)),
              ],
            );
          }
        },
      ),
    );
  }

  Widget buildSeanceCard(Seance seance, DateFormat formatter) {
    return Card(
      margin: const EdgeInsets.all(8),
      child: ListTile(
        leading: Image.network(
          'http://192.168.169.123/cinephoria/assets/images/${seance.affiche}',
          width: 50,
          fit: BoxFit.cover,
          errorBuilder: (context, error, stackTrace) =>
              const Icon(Icons.image_not_supported),
        ),
        title: Text(seance.film),
        subtitle: Text(
          'Début : ${formatter.format(DateTime.parse(seance.dateDebut))}\n'
          'Fin : ${formatter.format(DateTime.parse(seance.dateFin))}\n'
          'Salle : ${seance.salle}\n'
          'Personnes : ${seance.nombrePersonnes}',
        ),
        onTap: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => QRCodePage(
                seance: seance,
                userEmail: widget.userEmail,
              ),
            ),
          );
        },
      ),
    );
  }
}
