#!/bin/bash

echo "========================================"
echo "  ServiceConnect - Serveur de Dev"
echo "========================================"
echo

echo "[1/4] Vérification de PHP..."
if ! command -v php &> /dev/null; then
    echo "❌ PHP n'est pas installé"
    exit 1
fi
echo "✅ PHP trouvé: $(php --version | head -n 1)"

echo
echo "[2/4] Vérification de Composer..."
if ! command -v composer &> /dev/null; then
    echo "❌ Composer n'est pas installé"
    exit 1
fi
echo "✅ Composer trouvé: $(composer --version)"

echo
echo "[3/4] Vérification du projet Laravel..."
if [ ! -f "artisan" ]; then
    echo "❌ Fichier artisan non trouvé"
    echo "Veuillez vous assurer d'être dans le répertoire du projet Laravel"
    exit 1
fi
echo "✅ Projet Laravel trouvé"

echo
echo "[4/4] Démarrage du serveur de développement..."
echo "Serveur: http://127.0.0.1:8000"
echo "Pour arrêter: Ctrl+C"
echo "========================================"
echo

php artisan serve --host=127.0.0.1 --port=8000

echo
echo "Serveur arrêté"
