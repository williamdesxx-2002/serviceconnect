# 🔑 Configuration Google OAuth - Étape par Étape

## 🎯 **Objectif**
Résoudre définitivement l'erreur "The OAuth client was not found" en configurant correctement Google OAuth.

## ⚡ **Étapes Détaillées (10 minutes)**

### Étape 1 : Accéder à Google Cloud Console
1. Allez sur : https://console.cloud.google.com/
2. Connectez-vous avec votre compte `kpannedescaxx02@gmail.com`
3. Cliquez sur le sélecteur de projet en haut à gauche
4. Cliquez sur **"NOUVEAU PROJET"**
5. Nom du projet : `ServiceConnect-Production`
6. Cliquez sur **"CRÉER"**

### Étape 2 : Activer l'API People
1. Dans le menu de gauche ☰, allez à **"APIs et Services"** → **"Bibliothèque"**
2. Dans la barre de recherche, tapez : `People API`
3. Cliquez sur **"People API"** de Google
4. Cliquez sur **"ACTIVER"**
5. Attendez l'activation (quelques secondes)

### Étape 3 : Configurer l'écran de consentement
1. Allez dans **"APIs et Services"** → **"Écran de consentement OAuth"**
2. Cliquez sur **"CRÉER UN ÉCRAN"**
3. Choisissez **"Externe"** et cliquez sur **"CRÉER"**
4. Remplissez les informations :
   - **Nom de l'application** : `ServiceConnect`
   - **Email de support** : `kpannedescaxx02@gmail.com`
   - **URL des mentions légales** : `http://127.0.0.1:8000/legal` (optionnel)
   - **URL de la politique de confidentialité** : `http://127.0.0.1:8000/privacy` (optionnel)
   - **Email des développeurs** : `kpannedescaxx02@gmail.com`
5. Cliquez sur **"ENREGISTRER ET CONTINUER"**
6. Dans la section "Scopes", cliquez sur **"AJOUTER OU SUPPRIMER DES SCOPES"**
7. Ajoutez ces scopes :
   - `.../auth/userinfo.email`
   - `.../auth/userinfo.profile`
8. Cliquez sur **"METTRE À JOUR"**
9. Cliquez sur **"ENREGISTRER ET CONTINUER"**
10. Cliquez sur **"REVENIR AU TABLEAU DE BORD"**

### Étape 4 : Créer les identifiants OAuth2
1. Allez dans **"APIs et Services"** → **"Identifiants"**
2. Cliquez sur **"+ CRÉER DES IDENTIFIANTS"**
3. Choisissez **"ID client OAuth2"**
4. Configurez comme suit :
   - **Type d'application** : `Application web`
   - **Nom** : `ServiceConnect Web Client`
   - **Origines JavaScript autorisées** :
     ```
     http://127.0.0.1:8000
     http://localhost:8000
     ```
   - **URI de redirection autorisés** :
     ```
     http://127.0.0.1:8000/auth/google/callback
     http://localhost:8000/auth/google/callback
     ```
5. Cliquez sur **"CRÉER"**

### Étape 5 : Copier les clés
Vous obtiendrez une fenêtre avec :
- **ID CLIENT** : (longue chaîne commençant par `....apps.googleusercontent.com`)
- **CLIENT SECRET** : (longue chaîne aléatoire)

**COPIEZ CES DEUX CLÉS**

### Étape 6 : Mettre à jour le fichier .env
Ouvrez votre fichier `.env` et remplacez :
```env
GOOGLE_CLIENT_ID=votre_vrai_client_id_ici
GOOGLE_CLIENT_SECRET=votre_vrai_client_secret_ici
```

### Étape 7 : Vider les caches
```bash
php artisan config:clear
php artisan cache:clear
```

### Étape 8 : Tester
1. Démarrez le serveur : `php artisan serve --host=127.0.0.1 --port=8000`
2. Allez sur : http://127.0.0.1:8000/login
3. Cliquez sur : "Continuer avec Google"
4. Vous devriez être redirigé vers Google

## 🔍 **Vérification**

Après configuration, testez avec :
```bash
curl -I "http://127.0.0.1:8000/auth/google"
```

## ⚠️ **Points Critiques**

- **URL exacte** : `http://127.0.0.1:8000/auth/google/callback`
- **Origine JavaScript** : `http://127.0.0.1:8000`
- **API activée** : People API (pas Google+ API)
- **Compte Google** : `kpannedescaxx02@gmail.com`

## 🎯 **Résultat Attendu**

Une fois configuré correctement :
- ✅ Plus d'erreur "The OAuth client was not found"
- ✅ Redirection vers Google fonctionnelle
- ✅ Connexion/inscription via Google opérationnelle
- ✅ Comptes créés automatiquement

---

**🚀 En suivant ces étapes exactement, l'erreur 401 sera définitivement résolue !**
