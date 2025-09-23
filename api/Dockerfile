# Utiliser Python 3.11 slim
FROM python:3.11-slim

# Définir le répertoire de travail
WORKDIR /app

# Copier les dépendances
COPY requirements.txt .

# Installer les dépendances Python
RUN pip install --no-cache-dir -r requirements.txt

# Copier tout le code de l'API
COPY . .

# Exposer le port 5000
EXPOSE 5000

# Commande pour lancer l'API
CMD ["python", "app.py"]
