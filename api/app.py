from flask import Flask, request, jsonify
from flask_cors import CORS
import sqlite3
import os

app = Flask(__name__)
CORS(app)

# Chemin du fichier SQLite (persisté dans le volume Fly.io)
DB_PATH = "/data/cinephoria.db"

# Création de la base et des tables si elles n'existent pas
def init_db():
    with sqlite3.connect(DB_PATH) as conn:
        cursor = conn.cursor()
        cursor.execute("""
            CREATE TABLE IF NOT EXISTS salles (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nom TEXT NOT NULL
            )
        """)
        cursor.execute("""
            CREATE TABLE IF NOT EXISTS incidents (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                salle_id INTEGER,
                description TEXT,
                statut TEXT DEFAULT 'ouvert',
                date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY(salle_id) REFERENCES salles(id)
            )
        """)
        conn.commit()

init_db()

# -------------------
# ROUTES INCIDENTS
# -------------------

@app.route('/incidents', methods=['GET'])
def get_incidents():
    salle_id = request.args.get('salle_id')
    date_debut = request.args.get('date_debut')
    date_fin = request.args.get('date_fin')
    query = "SELECT * FROM incidents WHERE salle_id = ?"
    params = [salle_id]

    if date_debut and date_fin:
        query += " AND date_creation BETWEEN ? AND ?"
        params.extend([date_debut, date_fin])

    query += " ORDER BY date_creation DESC"
    with sqlite3.connect(DB_PATH) as conn:
        conn.row_factory = sqlite3.Row
        cursor = conn.cursor()
        cursor.execute(query, params)
        incidents = [dict(row) for row in cursor.fetchall()]

    return jsonify(incidents)

@app.route('/incidents', methods=['POST'])
def add_incident():
    data = request.get_json()
    with sqlite3.connect(DB_PATH) as conn:
        cursor = conn.cursor()
        cursor.execute("""
            INSERT INTO incidents (salle_id, description, statut) 
            VALUES (?, ?, ?)
        """, (data['salle_id'], data['description'], data.get('statut', 'ouvert')))
        conn.commit()
    return jsonify({"message": "Incident ajouté"}), 201

@app.route('/incidents/<int:incident_id>', methods=['PUT'])
def update_incident(incident_id):
    data = request.get_json()
    description = data.get('description')
    statut = data.get('statut')

    if not description or not statut:
        return jsonify({"error": "Champs manquants"}), 400

    with sqlite3.connect(DB_PATH) as conn:
        cursor = conn.cursor()
        cursor.execute("""
            UPDATE incidents 
            SET description = ?, statut = ?
            WHERE id = ?
        """, (description, statut, incident_id))
        conn.commit()

    return jsonify({"message": "Incident modifié"}), 200

@app.route('/incidents/<int:incident_id>', methods=['DELETE'])
def delete_incident(incident_id):
    with sqlite3.connect(DB_PATH) as conn:
        cursor = conn.cursor()
        cursor.execute("DELETE FROM incidents WHERE id = ?", (incident_id,))
        conn.commit()
    return jsonify({"message": "Incident supprimé"}), 200

@app.route('/incidents/statut', methods=['GET'])
def get_incidents_by_statut():
    statut = request.args.get('statut')
    with sqlite3.connect(DB_PATH) as conn:
        conn.row_factory = sqlite3.Row
        cursor = conn.cursor()
        cursor.execute("""
            SELECT * FROM incidents WHERE statut = ? ORDER BY date_creation DESC
        """, (statut,))
        incidents = [dict(row) for row in cursor.fetchall()]
    return jsonify(incidents)

# -------------------
# ROUTES SALLES
# -------------------

@app.route('/salles', methods=['GET'])
def get_salles():
    with sqlite3.connect(DB_PATH) as conn:
        conn.row_factory = sqlite3.Row
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM salles")
        salles = [dict(row) for row in cursor.fetchall()]
    return jsonify(salles)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=int(os.environ.get("PORT", 5000)), debug=True)
