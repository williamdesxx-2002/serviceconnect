# 🛠️ Résolution de l'Erreur Google OAuth - "redirect_uri"

## 🚨 **Problème**
Erreur 400 : `invalid_request` - `Missing required parameter: redirect_uri`

## 🔍 **Cause**
L'URI de redirection n'est pas correctement configurée dans la console Google Cloud.

## ✅ **Solution Étape par Étape**

### 1. **Configuration du Fichier .env** ✅
Votre fichier `.env` contient maintenant :
```env
APP_URL=http://127.0.0.1:8001
GOOGLE_CLIENT_ID=votre_google_client_id
GOOGLE_CLIENT_SECRET=votre_google_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8001/auth/google/callback
```

### 2. **Configuration Google Cloud Console**

#### Étape A : Accéder à la console
1. Allez sur [Google Cloud Console](https://console.cloud.google.com/)
2. Sélectionnez votre projet
3. Dans le menu, allez à **APIs & Services** > **Identifiants**

#### Étape B : Configurer OAuth2
1. Trouvez votre **ID Client OAuth2**
2. Cliquez sur **Modifier** (icône crayon)
3. Dans **Origines JavaScript autorisées**, ajoutez :
   ```
   http://127.0.0.1:8001
   http://localhost:8001
   ```
4. Dans **URI de redirection autorisés**, ajoutez :
   ```
   http://127.0.0.1:8001/auth/google/callback
   http://localhost:8001/auth/google/callback
   ```

#### Étape C : Activer les APIs requises
1. Allez dans **APIs & Services** > **Bibliothèque**
2. Activez **Google+ API** OU **People API**
3. Activez **OAuth2 API** si nécessaire

### 3. **Obtenir les Vraies Clés Google**

#### Étape A : Créer des identifiants
1. Si vous n'avez pas de projet :
   - Créez un nouveau projet
   - Configurez l'écran de consentement OAuth
2. Allez dans **Identifiants** > **Créer des identifiants** > **ID client OAuth2**
3. Choisissez **Application web**
4. Remplissez les informations comme ci-dessus

#### Étape B : Remplacer les clés dans .env
Remplacez dans votre fichier `.env` :
```env
GOOGLE_CLIENT_ID=votre_vrai_client_id
GOOGLE_CLIENT_SECRET=votre_vrai_client_secret
```

### 4. **Tester la Configuration**

#### Étape A : Vider les caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

#### Étape B : Démarrer le serveur
```bash
php artisan serve --host=127.0.0.1 --port=8001
```

#### Étape C : Tester
1. Allez sur `http://127.0.0.1:8001/login`
2. Cliquez sur "Continuer avec Google"
3. Vous devriez être redirigé vers Google

## 🔧 **Dépannage Avancé**

### Si l'erreur persiste :

#### 1. **Vérifier l'URL exacte**
- L'URL dans Google doit être **exactement** la même que dans `.env`
- `http://127.0.0.1:8001/auth/google/callback` (pas `localhost`)
- Vérifiez le port (8001 et non 8000)

#### 2. **Vérifier les permissions**
- Assurez-vous que l'API Google+ ou People API est activée
- Vérifiez que l'écran de consentement OAuth est configuré

#### 3. **Tester avec curl**
```bash
curl -I "http://127.0.0.1:8001/auth/google"
```

#### 4. **Vérifier les logs**
```bash
php artisan log:clear
# Testez l'authentification
php artisan log:show
```

## 📋 **Checklist de Configuration**

- [ ] `.env` configuré avec les bonnes URLs
- [ ] Google Cloud Console configurée
- [ ] Origines JavaScript autorisées ajoutées
- [ ] URI de redirection autorisés ajoutés
- [ ] APIs requises activées
- [ ] Clés réelles placées dans `.env`
- [ ] Caches Laravel vidés
- [ ] Serveur démarré sur le bon port

## 🎯 **URLs Exactes à Configurer**

### Dans Google Cloud Console :
```
Origines JavaScript autorisées :
http://127.0.0.1:8001
http://localhost:8001

URI de redirection autorisés :
http://127.0.0.1:8001/auth/google/callback
http://localhost:8001/auth/google/callback
```

### Dans votre fichier .env :
```env
APP_URL=http://127.0.0.1:8001
GOOGLE_REDIRECT_URI=http://127.0.0.1:8001/auth/google/callback
```

## 🚀 **Une fois Configuré**

1. **Redémarrez votre serveur** :
   ```bash
   php artisan serve --host=127.0.0.1 --port=8001
   ```

2. **Testez l'authentification** :
   - Allez sur `http://127.0.0.1:8001/login`
   - Cliquez sur "Continuer avec Google"
   - Authentifiez-vous
   - Vérifiez la redirection et la création du compte

## 📞 **Support**

Si vous rencontrez toujours des problèmes :
1. Vérifiez que toutes les URLs correspondent exactement
2. Assurez-vous que les APIs sont activées
3. Consultez les logs Laravel pour plus de détails

---

**🎉 L'authentification Google sera fonctionnelle une fois ces étapes suivies !**
