# 🚀 Serveur de Développement ServiceConnect

## ✅ **Scripts de Démarrage Créés**

J'ai créé des scripts pour démarrer facilement le serveur de développement :

### 🪟 **Pour Windows (run_dev.bat)**
- ✅ **Vérification automatique** de PHP
- ✅ **Vérification automatique** de Composer
- ✅ **Vérification automatique** du projet Laravel
- ✅ **Démarrage automatique** du serveur
- ✅ **Messages clairs** d'état

### 🐧 **Pour Linux/Mac (run_dev.sh)**
- ✅ **Vérification automatique** de PHP
- ✅ **Vérification automatique** de Composer
- ✅ **Vérification automatique** du projet Laravel
- ✅ **Démarrage automatique** du serveur
- ✅ **Messages clairs** d'état

## 🎯 **Utilisation**

### Windows
```bash
# Double-cliquer sur le fichier
run_dev.bat

# Ou exécuter dans le terminal
.\run_dev.bat
```

### Linux/Mac
```bash
# Rendre exécutable
chmod +x run_dev.sh

# Exécuter
./run_dev.sh
```

## 📊 **Ce que font les scripts**

### Étape 1: Vérification PHP
- Confirme que PHP est installé
- Affiche la version de PHP
- Arrête si PHP n'est pas trouvé

### Étape 2: Vérification Composer
- Confirme que Composer est installé
- Affiche la version de Composer
- Arrête si Composer n'est pas trouvé

### Étape 3: Vérification Projet
- Confirme que le fichier `artisan` existe
- Vérifie qu'on est dans un projet Laravel
- Arrête si le projet n'est pas trouvé

### Étape 4: Démarrage Serveur
- Démarre le serveur sur `http://127.0.0.1:8000`
- Affiche l'URL d'accès
- Affiche comment arrêter (Ctrl+C)

## 🌐 **Accès à l'Application**

Une fois le serveur démarré :

### URL Principales
- **Accueil** : http://127.0.0.1:8000/
- **Inscription** : http://127.0.0.1:8000/register
- **Inscription simplifiée** : http://127.0.0.1:8000/register-simple
- **Connexion** : http://127.0.0.1:8000/login
- **Services** : http://127.0.0.1:8000/services
- **Mes Services** : http://127.0.0.1:8000/my-services
- **Admin** : http://127.0.0.1:8000/admin/dashboard

### Comptes de Test
- **Admin** : admin@serviceconnect.com / Admin123!
- **Client** : alice.client@example.com / Password123!
- **Prestataire** : charles.provider@example.com / Password123!

## 🔧 **Commandes Manuelles**

Si les scripts ne fonctionnent pas :

### Démarrage Manuel
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

### Vérification PHP
```bash
php --version
```

### Vérification Composer
```bash
composer --version
```

### Vider les Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 🚨 **Dépannage**

### Si PHP n'est pas trouvé
- **Windows** : Ajouter PHP au PATH système
- **Linux/Mac** : Installer PHP via gestionnaire de paquets

### Si Composer n'est pas trouvé
- **Windows** : Télécharger et installer Composer
- **Linux/Mac** : Installer via curl ou gestionnaire

### Si le serveur ne démarre pas
- **Vérifier** que le port 8000 est libre
- **Vérifier** les permissions du fichier artisan
- **Vérifier** les erreurs dans le terminal

## 📱 **Test sur Mobile**

Une fois le serveur démarré :

1. **Ouvrir** un navigateur mobile
2. **Accéder** à http://127.0.0.1:8000
3. **Tester** le responsive design
4. **Tester** l'inscription et la connexion

## 🎯 **Workflow de Développement**

### 1. Démarrer le Serveur
```bash
# Windows
.\run_dev.bat

# Linux/Mac
./run_dev.sh
```

### 2. Ouvrir le Navigateur
```
http://127.0.0.1:8000/register-simple
```

### 3. Tester les Fonctionnalités
- **Inscription** : Formulaire simplifié
- **Connexion** : Tous les rôles
- **Redirection** : Selon le rôle
- **Services** : Création et gestion

### 4. Vérifier les Logs
```bash
# En temps réel
tail -f storage/logs/laravel.log
```

## 🔄 **Redémarrage Rapide**

Pour redémarrer le serveur :

1. **Arrêter** : Ctrl+C dans le terminal
2. **Relancer** : Réexécuter le script
3. **Vider caches** : Au besoin

---

**🚀 Le serveur de développement est maintenant facile à démarrer !**

Utilisez `run_dev.bat` (Windows) ou `run_dev.sh` (Linux/Mac) pour un démarrage rapide et vérifié.**
