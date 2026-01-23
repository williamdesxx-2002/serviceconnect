# Guide d'Configuration de l'Authentification Sociale

## 🚀 Vue d'ensemble

Ce guide explique comment configurer l'authentification via Google et Facebook pour votre application ServiceConnect.

## 📋 Prérequis

- Compte Google Cloud Platform
- Compte Facebook Developer
- Accès au fichier `.env` de votre application

## 🔧 Configuration Google OAuth2

### 1. Créer un projet Google Cloud

1. Allez sur [Google Cloud Console](https://console.cloud.google.com/)
2. Créez un nouveau projet ou sélectionnez-en un existant
3. Activez l'API Google+ (ou People API)

### 2. Créer des identifiants OAuth2

1. Dans le menu, allez à **APIs & Services** > **Identifiants**
2. Cliquez sur **Créer des identifiants** > **ID client OAuth**
3. Configurez l'écran de consentement OAuth si nécessaire
4. Remplissez les informations :
   - **Type d'application** : Application web
   - **Origines JavaScript autorisées** : `http://localhost:8001`
   - **URI de redirection autorisés** : `http://localhost:8001/auth/google/callback`

### 3. Obtenir les clés

Une fois créé, vous obtiendrez :
- **Client ID** : `GOOGLE_CLIENT_ID`
- **Client Secret** : `GOOGLE_CLIENT_SECRET`

## 🔧 Configuration Facebook OAuth2

### 1. Créer une application Facebook

1. Allez sur [Facebook Developers](https://developers.facebook.com/)
2. Créez une nouvelle application
3. Choisissez **Business** ou **Gestionnaire d'applications**

### 2. Configurer Facebook Login

1. Dans le tableau de bord, ajoutez le produit **Facebook Login**
2. Configurez les paramètres :
   - **URI de redirection OAuth valides** : `http://localhost:8001/auth/facebook/callback`
   - **Domaines autorisés** : `localhost:8001`

### 3. Obtenir les clés

Dans les paramètres de base de l'application :
- **App ID** : `FACEBOOK_CLIENT_ID`
- **App Secret** : `FACEBOOK_CLIENT_SECRET`

## 📝 Configuration du fichier .env

Ajoutez ces lignes à votre fichier `.env` :

```env
# Google OAuth2
GOOGLE_CLIENT_ID=votre_google_client_id
GOOGLE_CLIENT_SECRET=votre_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8001/auth/google/callback

# Facebook OAuth2
FACEBOOK_CLIENT_ID=votre_facebook_app_id
FACEBOOK_CLIENT_SECRET=votre_facebook_app_secret
FACEBOOK_REDIRECT_URI=http://localhost:8001/auth/facebook/callback
```

## 🔄 Routes disponibles

Les routes suivantes sont maintenant disponibles :

- `GET /auth/google` - Redirection vers Google
- `GET /auth/google/callback` - Callback Google
- `GET /auth/facebook` - Redirection vers Facebook
- `GET /auth/facebook/callback` - Callback Facebook

## 🎯 Fonctionnalités implémentées

### ✅ Authentification
- Connexion via Google et Facebook
- Création automatique de compte si l'utilisateur n'existe pas
- Vérification automatique de l'email
- Gestion des conflits d'email

### ✅ Sécurité
- Validation des fournisseurs (Google, Facebook uniquement)
- Protection contre les conflits de méthodes de connexion
- Gestion des erreurs élégante

### ✅ Redirections intelligentes
- Les admins sont redirigés vers `admin.dashboard`
- Les prestataires vers `services.my`
- Les clients vers `services.index`

## 🧪 Test de l'implémentation

### 1. Test de configuration

```bash
# Vérifier les routes
php artisan route:list --name=social

# Vider les caches
php artisan config:clear
php artisan cache:clear
```

### 2. Test en production

1. Démarrez le serveur : `php artisan serve --host=127.0.0.1 --port=8001`
2. Allez sur `http://localhost:8001/login`
3. Cliquez sur "Continuer avec Google" ou "Continuer avec Facebook"
4. Suivez le processus d'authentification
5. Vérifiez la redirection et la création du compte

## 📊 Base de données

Les nouveaux champs ont été ajoutés à la table `users` :

- `provider` : Nom du fournisseur (google, facebook)
- `provider_id` : ID unique du fournisseur
- Index composite sur `(provider, provider_id)` pour optimisation

## 🚨 Points d'attention

### Sécurité
- Ne jamais exposer les clés secrètes dans le code client
- Utilisez toujours HTTPS en production
- Limitez les domaines autorisés dans les consoles développeurs

### Production
- Mettez à jour les URIs de redirection pour votre domaine de production
- Configurez correctement les domaines autorisés
- Testez avec différents comptes utilisateurs

## 🔍 Dépannage

### Erreur "Fournisseur non supporté"
- Vérifiez que le fournisseur est bien dans la liste `['google', 'facebook']`

### Erreur "Cet email est déjà utilisé"
- L'email est déjà associé à un compte avec une autre méthode de connexion
- L'utilisateur doit se connecter avec sa méthode originale

### Erreur de redirection
- Vérifiez les URIs de redirection dans les consoles développeurs
- Assurez-vous que l'URL correspond exactement (http vs https, port, etc.)

## 📚 Ressources utiles

- [Laravel Socialite Documentation](https://laravel.com/docs/socialite)
- [Google OAuth2 Documentation](https://developers.google.com/identity/protocols/oauth2)
- [Facebook Login Documentation](https://developers.facebook.com/docs/facebook-login)

## 🎉 Conclusion

L'authentification sociale est maintenant entièrement fonctionnelle ! Les utilisateurs peuvent s'inscrire et se connecter via Google et Facebook, avec une gestion robuste des erreurs et des redirections intelligentes selon leur rôle.
