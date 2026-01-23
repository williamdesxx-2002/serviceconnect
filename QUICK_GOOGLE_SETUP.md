# 🚀 Configuration Rapide Google OAuth - Guide Complet

## 🎯 **Objectif**
Résoudre l'erreur "The OAuth client was not found" en configurant correctement Google OAuth.

## ⚡ **Étapes Rapides (5 minutes)**

### 1. **Créer le Projet Google Cloud**
- Allez sur : https://console.cloud.google.com/
- Cliquez sur "Sélectionner un projet" → "Nouveau projet"
- Nom : `ServiceConnect`
- Créez

### 2. **Activer l'API People**
- Menu → "APIs et Services" → "Bibliothèque"
- Recherchez : `People API`
- Cliquez sur "Activer"

### 3. **Configurer l'écran de consentiment**
- Menu → "APIs et Services" → "Écran de consentement OAuth"
- Choisissez : `Externe`
- Remplissez :
  - Nom de l'application : `ServiceConnect`
  - Email de support : `williamdesxx@gmail.com`
- Cliquez sur "Enregistrer"

### 4. **Créer les identifiants OAuth2**
- Menu → "APIs et Services" → "Identifiants"
- Cliquez sur "Créer des identifiants" → "ID client OAuth2"
- Configuration :
  - Type d'application : `Application web`
  - Nom : `ServiceConnect Web`
  - Origines JavaScript autorisées : `http://127.0.0.1:8000`
  - URI de redirection autorisés : `http://127.0.0.1:8000/auth/google/callback`
- Cliquez sur "Créer"

### 5. **Copier les clés**
Vous obtiendrez deux clés :
- **Client ID** : Copiez cette longue chaîne
- **Client Secret** : Copiez cette longue chaîne

### 6. **Mettre à jour .env**
Remplacez dans votre fichier `.env` :
```env
GOOGLE_CLIENT_ID=votre_client_id_ici
GOOGLE_CLIENT_SECRET=votre_client_secret_ici
```

### 7. **Vider les caches**
```bash
php artisan config:clear
php artisan cache:clear
```

### 8. **Tester**
```bash
php artisan serve --host=127.0.0.1 --port=8000
# Allez sur http://127.0.0.1:8000/login
# Cliquez sur "Continuer avec Google"
```

## 🔍 **Vérification**

Après configuration, testez avec :
```bash
curl -I "http://127.0.0.1:8000/auth/google"
```

Vous devriez être redirigé vers Google.

## ⚠️ **Points Importants**

- **URL exacte** : `http://127.0.0.1:8000/auth/google/callback`
- **Port correct** : `8000` (pas 8001)
- **API activée** : People API
- **Origine JavaScript** : `http://127.0.0.1:8000`

## 🎉 **Résultat**

Une fois configuré, l'authentification Google fonctionnera parfaitement et les utilisateurs pourront s'inscrire/se connecter avec leur compte Google !

---

**🚀 En suivant ces étapes, votre authentification Google sera opérationnelle en quelques minutes !**
