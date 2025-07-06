#!/bin/bash

# Déplacement dans le dossier du projet
cd /var/www/musiki || exit 1

# Ajout des modifications sauf fichiers ignorés
git add .

# Vérifie s'il y a des modifications à committer
if ! git diff --cached --quiet; then
    git commit -m "🔄 Auto commit - $(date +'%Y-%m-%d %H:%M:%S')"
    git push origin main
else
    echo "🟢 Rien à mettre à jour - $(date +'%Y-%m-%d %H:%M:%S')"
fi

