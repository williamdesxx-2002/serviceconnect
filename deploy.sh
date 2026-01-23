#!/bin/bash

# 🚀 Script de Déploiement Production - ServiceConnect

echo "🚀 Déploiement de ServiceConnect en Production..."

# 1. Mise à jour du code
echo "📥 Mise à jour du code..."
git pull origin main

# 2. Installation des dépendances
echo "📦 Installation des dépendances..."
composer install --no-dev --optimize-autoloader
npm install --production
npm run build

# 3. Configuration de l'environnement
echo "⚙️ Configuration de l'environnement..."
cp .env.production .env
php artisan config:clear
php artisan config:cache

# 4. Génération de la clé APP
echo "🔑 Génération de la clé application..."
php artisan key:generate --force

# 5. Optimisation
echo "⚡ Optimisation de l'application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Base de données
echo "🗄️ Migration de la base de données..."
php artisan migrate --force

# 7. Permissions
echo "🔐 Configuration des permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# 8. Cache et optimisation
echo "🧹 Nettoyage du cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 9. Redémarrage des services
echo "🔄 Redémarrage des services..."
php artisan queue:restart

echo "✅ Déploiement terminé avec succès !"
