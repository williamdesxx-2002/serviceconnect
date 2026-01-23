# 🔑 Configuration Facebook OAuth - Guide Détaillé

## 🎯 **Objectif**
Résoudre l'erreur "L'identifiant d'application fourni ne semble pas valide"

## ⚡ **Étapes Complètes (15 minutes)**

### Étape 1 : Accéder à Facebook Developers
1. Allez sur : https://developers.facebook.com/
2. Connectez-vous avec votre compte Facebook
3. Cliquez sur **"Commencer"** ou **"Créer une application"**

### Étape 2 : Créer l'application
1. Choisissez le type d'application : **"Entreprise"**
2. Remplissez les informations :
   - **Nom de l'application** : `ServiceConnect`
   - **Email de contact** : votre email
3. Cliquez sur **"Créer une application"**
4. Complétez les informations de sécurité si demandé

### Étape 3 : Ajouter Facebook Login
1. Dans le tableau de bord de l'application, allez à **"Produits"**
2. Cliquez sur **"Ajouter un produit"**
3. Cherchez et ajoutez **"Facebook Login"**
4. Cliquez sur **"Configurer"** pour Facebook Login

### Étape 4 : Configurer les URLs de redirection
1. Dans les paramètres de Facebook Login, allez à **"Paramètres"**
2. Dans **"URI de redirection OAuth valides"**, ajoutez :
   ```
   http://127.0.0.1:8000/auth/facebook/callback
   http://localhost:8000/auth/facebook/callback
   ```
3. Dans **"Domaines autorisés"**, ajoutez :
   ```
   127.0.0.1:8000
   localhost:8000
   ```

### Étape 5 : Obtenir les identifiants
1. Allez dans **"Paramètres"** → **"Général"**
2. Copiez ces deux informations :
   - **ID de l'application** (App ID) : longue chaîne numérique
   - **Clé secrète de l'application** (App Secret) : longue chaîne aléatoire

### Étape 6 : Mettre à jour le fichier .env
Ouvrez votre fichier `.env` et remplacez :
```env
FACEBOOK_CLIENT_ID=votre_vrai_app_id_facebook
FACEBOOK_CLIENT_SECRET=votre_vrai_app_secret_facebook
FACEBOOK_REDIRECT_URI=http://127.0.0.1:8000/auth/facebook/callback
```

### Étape 7 : Configurer l'application en mode développement
1. Dans **"Paramètres"** → **"Général"**
2. Assurez-vous que le mode est **"Développement"**
3. Ajoutez votre email comme **"Testeur"** si nécessaire

### Étape 8 : Vider les caches Laravel
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Étape 9 : Tester l'authentification
1. Démarrez le serveur :
   ```bash
   php artisan serve --host=127.0.0.1 --port=8000
   ```
2. Allez sur : http://127.0.0.1:8000/login
3. Cliquez sur : "Continuer avec Facebook"
4. Vous devriez être redirigé vers Facebook

## 🔍 **Vérification de la configuration**

### Vérifier les identifiants
```bash
php artisan tinker
> config('services.facebook.client_id')
> config('services.facebook.client_secret')
```

### Vérifier les routes
```bash
php artisan route:list --name=social
```

## ⚠️ **Points Critiques à Vérifier**

### 1. URLs Exactes
- **URI de redirection** : `http://127.0.0.1:8000/auth/facebook/callback`
- **Domaines autorisés** : `127.0.0.1:8000`

### 2. Mode de l'application
- **Développement** pour les tests
- **Production** uniquement pour le déploiement

### 3. Permissions requises
- `email` : Accès à l'email
- `public_profile` : Accès au profil public

## 🎯 **Dépannage**

### Si l'erreur persiste :
1. **Vérifiez l'App ID** : Copiez-le directement depuis Facebook Developers
2. **Vérifiez l'App Secret** : Cliquez sur "Afficher" pour voir la clé complète
3. **Vérifiez les URLs** : Doivent correspondre exactement
4. **Mode développement** : Assurez-vous d'être en mode développement

### Erreurs communes :
- **URL incorrecte** : `http://127.0.0.1:8000/auth/facebook/callback` (pas `/auth/facebook`)
- **Mode production** : L'application doit être en mode développement
- **Manque de permissions** : Ajoutez `email` et `public_profile`

## 🎉 **Résultat Attendu**

Une fois configuré correctement :
- ✅ Plus d'erreur "identifiant non valide"
- ✅ Redirection vers Facebook fonctionnelle
- ✅ Connexion/inscription via Facebook opérationnelle
- ✅ Comptes créés automatiquement

## 📚 **Documentation Complémentaire**

- `FACEBOOK_OAUTH_FIX.md` : Guide rapide
- `RESOLUTION_FINALE_OAUTH.md` : Solution pour Google
- `RECONFIGURATION_SUMMARY.md` : État général du projet

---

**🚀 En suivant ces étapes précisément, l'erreur Facebook sera définitivement résolue !**
