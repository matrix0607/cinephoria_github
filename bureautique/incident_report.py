
import tkinter as tk
from tkinter import ttk, messagebox
import requests
import csv
from datetime import datetime

API_URL = "http://localhost:5000/incidents"
SALLE_API_URL = "http://localhost:5000/salles"

salles_dict = {}
incidents_cache = []

def charger_salles():
    global salles_dict
    try:
        response = requests.get(SALLE_API_URL)
        salles = response.json()
        salles_dict = {s['nom']: s['id'] for s in salles}
        salle_dropdown['values'] = list(salles_dict.keys())
    except Exception as e:
        messagebox.showerror("Erreur", f"Impossible de charger les salles : {e}")

def ajouter_incident():
    nom_salle = salle_var.get()
    salle_id = salles_dict.get(nom_salle)
    description = description_entry.get()
    statut = statut_var.get()

    if not salle_id or not description or not statut:
        messagebox.showerror("Erreur", "Veuillez remplir tous les champs.")
        return

    data = {"salle_id": salle_id, "description": description, "statut": statut}

    try:
        response = requests.post(API_URL, json=data)
        if response.status_code == 201:
            messagebox.showinfo("Succès", "Incident ajouté.")
            description_entry.delete(0, tk.END)
            statut_dropdown.set("")
            charger_incidents()
        else:
            messagebox.showerror("Erreur", "Échec de l'ajout.")
    except Exception as e:
        messagebox.showerror("Erreur", str(e))

def charger_incidents():
    global incidents_cache
    nom_salle = salle_var.get()
    salle_id = salles_dict.get(nom_salle)
    if not salle_id:
        messagebox.showwarning("Attention", "Veuillez sélectionner une salle.")
        return
    try:
        response = requests.get(API_URL, params={"salle_id": salle_id})
        incidents = response.json()
        incidents_cache = incidents
        afficher_incidents(incidents)
    except Exception as e:
        messagebox.showerror("Erreur", str(e))

def afficher_incidents(incidents):
    incident_table.delete(*incident_table.get_children())
    for incident in incidents:
        incident_table.insert("", "end", values=(
            incident["description"],
            incident["date_creation"],
            incident["statut"],
            incident["id"]
        ))

def supprimer_incident():
    selected = incident_table.selection()
    if not selected:
        messagebox.showwarning("Attention", "Sélectionnez un incident à supprimer.")
        return
    incident_id = incident_table.item(selected[0])['values'][3]
    try:
        response = requests.delete(f"{API_URL}/{incident_id}")
        if response.status_code == 200:
            messagebox.showinfo("Succès", "Incident supprimé.")
            charger_incidents()
        else:
            messagebox.showerror("Erreur", "Échec de la suppression.")
    except Exception as e:
        messagebox.showerror("Erreur", str(e))

def exporter_csv():
    try:
        with open("incidents_export.csv", "w", newline="", encoding="utf-8") as f:
            writer = csv.writer(f)
            writer.writerow(["Description", "Date de création", "Statut", "ID"])
            for row_id in incident_table.get_children():
                values = incident_table.item(row_id)['values']
                writer.writerow(values)
        messagebox.showinfo("Export réussi", "Les incidents ont été exportés dans incidents_export.csv.")
    except Exception as e:
        messagebox.showerror("Erreur", f"Échec de l'export : {e}")

def filtrer_incidents():
    mot_cle = filtre_var.get().lower()
    incidents_filtres = [i for i in incidents_cache if mot_cle in i["description"].lower()]
    afficher_incidents(incidents_filtres)

def filtrer_par_date():
    nom_salle = salle_var.get()
    salle_id = salles_dict.get(nom_salle)
    date_debut = date_debut_var.get()
    date_fin = date_fin_var.get()

    if not salle_id or not date_debut or not date_fin:
        messagebox.showerror("Erreur", "Veuillez remplir tous les champs de filtre.")
        return

    try:
        datetime.strptime(date_debut, "%Y-%m-%d")
        datetime.strptime(date_fin, "%Y-%m-%d")

        response = requests.get(API_URL, params={
            "salle_id": salle_id,
            "date_debut": date_debut,
            "date_fin": date_fin
        })
        incidents = response.json()
        afficher_incidents(incidents)
    except ValueError:
        messagebox.showerror("Erreur", "Format de date invalide. Utilisez YYYY-MM-DD.")
    except Exception as e:
        messagebox.showerror("Erreur", str(e))

def charger_incident_selectionne():
    selected = incident_table.selection()
    if not selected:
        messagebox.showwarning("Attention", "Sélectionnez un incident à modifier.")
        return
    values = incident_table.item(selected[0])['values']
    description_entry.delete(0, tk.END)
    description_entry.insert(0, values[0])
    statut_dropdown.set(values[2])
    modifier_btn.config(state="normal")

def modifier_incident():
    selected = incident_table.selection()
    if not selected:
        messagebox.showwarning("Attention", "Sélectionnez un incident.")
        return
    incident_id = incident_table.item(selected[0])['values'][3]
    nouvelle_description = description_entry.get()
    nouveau_statut = statut_var.get()
    if not nouvelle_description or not nouveau_statut:
        messagebox.showerror("Erreur", "Tous les champs doivent être remplis.")
        return
    try:
        response = requests.put(f"{API_URL}/{incident_id}", json={
            "description": nouvelle_description,
            "statut": nouveau_statut
        })
        if response.status_code == 200:
            messagebox.showinfo("Succès", "Incident modifié.")
            description_entry.delete(0, tk.END)
            statut_dropdown.set("")
            modifier_btn.config(state="disabled")
            charger_incidents()
        else:
            messagebox.showerror("Erreur", "Échec de la modification.")
    except Exception as e:
        messagebox.showerror("Erreur", str(e))

# Interface
root = tk.Tk()
root.title("Gestion des incidents - Cinéphoria")
root.geometry("850x750")

salle_var = tk.StringVar()
ttk.Label(root, text="Salle :").pack(pady=5)
salle_dropdown = ttk.Combobox(root, textvariable=salle_var)
salle_dropdown.pack()
charger_salles()

ttk.Label(root, text="Description de l'incident :").pack(pady=5)
description_entry = ttk.Entry(root, width=60)
description_entry.pack()

ttk.Label(root, text="Statut de l'incident :").pack(pady=5)
statut_var = tk.StringVar()
statut_dropdown = ttk.Combobox(root, textvariable=statut_var, values=["En attente", "En cours", "Résolu"])
statut_dropdown.pack()

ttk.Button(root, text="Ajouter l'incident", command=ajouter_incident).pack(pady=10)

ttk.Label(root, text="Incidents existants :").pack(pady=5)

incident_table = ttk.Treeview(root, columns=("description", "date", "statut", "id"), show="headings")
incident_table.heading("description", text="Description")
incident_table.heading("date", text="Date de création")
incident_table.heading("statut", text="Statut")
incident_table.heading("id", text="ID")
incident_table.column("description", width=300)
incident_table.column("date", width=150)
incident_table.column("statut", width=100)
incident_table.column("id", width=50)
incident_table.pack(pady=10, fill="both", expand=True)

ttk.Button(root, text="Recharger les incidents", command=charger_incidents).pack(pady=5)
ttk.Button(root, text="Supprimer l'incident sélectionné", command=supprimer_incident).pack(pady=5)
ttk.Button(root, text="Exporter en CSV", command=exporter_csv).pack(pady=10)

ttk.Label(root, text="Filtrer par mot-clé :").pack(pady=5)
filtre_var = tk.StringVar()
filtre_entry = ttk.Entry(root, textvariable=filtre_var, width=40)
filtre_entry.pack()
ttk.Button(root, text="Appliquer le filtre", command=filtrer_incidents).pack(pady=5)

ttk.Label(root, text="Filtrer par période :").pack(pady=5)
date_debut_var = tk.StringVar()
date_fin_var = tk.StringVar()
ttk.Label(root, text="Date début (YYYY-MM-DD) :").pack()
date_debut_entry = ttk.Entry(root, textvariable=date_debut_var)
date_debut_entry.pack()
ttk.Label(root, text="Date fin (YYYY-MM-DD) :").pack()
date_fin_entry = ttk.Entry(root, textvariable=date_fin_var)
date_fin_entry.pack()
ttk.Button(root, text="Filtrer par date", command=filtrer_par_date).pack(pady=5)

ttk.Button(root, text="Modifier l'incident sélectionné", command=charger_incident_selectionne).pack(pady=5)
modifier_btn = ttk.Button(root, text="Enregistrer la modification", command=modifier_incident, state="disabled")
modifier_btn.pack(pady=5)

root.mainloop()
