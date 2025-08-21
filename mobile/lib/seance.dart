
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
