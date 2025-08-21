
from flask import Flask, request, jsonify
from flask_cors import CORS
import mysql.connector

app = Flask(__name__)
CORS(app)

# Connexion à la base MySQL
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="cinephoria"
)
cursor = db.cursor(dictionary=True)

# Récupérer tous les incidents d'une salle
@app.route('/incidents', methods=['GET'])
def get_incidents():
    salle_id = request.args.get('salle_id')
    date_debut = request.args.get('date_debut')
    date_fin = request.args.get('date_fin')

    if date_debut and date_fin:
        cursor.execute("""
            SELECT id, salle_id, description, statut, date_creation 
            FROM incidents 
            WHERE salle_id = %s AND date_creation BETWEEN %s AND %s
            ORDER BY date_creation DESC
        """, (salle_id, date_debut, date_fin))
    else:
        cursor.execute("""
            SELECT id, salle_id, description, statut, date_creation 
            FROM incidents 
            WHERE salle_id = %s 
            ORDER BY date_creation DESC
        """, (salle_id,))
    incidents = cursor.fetchall()
    return jsonify(incidents)

# Ajouter un nouvel incident
@app.route('/incidents', methods=['POST'])
def add_incident():
    data = request.get_json()
    cursor.execute("""
        INSERT INTO incidents (salle_id, description, statut) 
        VALUES (%s, %s, %s)
    """, (data['salle_id'], data['description'], data.get('statut', 'ouvert')))
    db.commit()
    return jsonify({"message": "Incident ajouté"}), 201

# Mettre à jour le statut et la description d’un incident
@app.route('/incidents/<int:incident_id>', methods=['PUT'])
def update_incident(incident_id):
    data = request.get_json()
    description = data.get('description')
    statut = data.get('statut')

    if not description or not statut:
        return jsonify({"error": "Champs manquants"}), 400

    cursor.execute("""
        UPDATE incidents 
        SET description = %s, statut = %s 
        WHERE id = %s
    """, (description, statut, incident_id))
    db.commit()
    return jsonify({"message": "Incident modifié"}), 200

# Supprimer un incident
@app.route('/incidents/<int:incident_id>', methods=['DELETE'])
def delete_incident(incident_id):
    cursor.execute("DELETE FROM incidents WHERE id = %s", (incident_id,))
    db.commit()
    return jsonify({"message": "Incident supprimé"}), 200

# Récupérer les incidents par statut
@app.route('/incidents/statut', methods=['GET'])
def get_incidents_by_statut():
    statut = request.args.get('statut')
    cursor.execute("""
        SELECT id, salle_id, description, statut, date_creation 
        FROM incidents 
        WHERE statut = %s 
        ORDER BY date_creation DESC
    """, (statut,))
    incidents = cursor.fetchall()
    return jsonify(incidents)

# Récupérer la liste des salles
@app.route('/salles', methods=['GET'])
def get_salles():
    cursor.execute("SELECT id, nom FROM salles")
    salles = cursor.fetchall()
    return jsonify(salles)

if __name__ == '__main__':
    app.run(port=5000)
