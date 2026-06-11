# Configuration Gmail SMTP

## Contexte
Cette documentation explique comment configurer l'envoi d'emails via Gmail SMTP pour la plateforme **Jeunesse en Jésus**.

## Prérequis
- Un compte Gmail actif
- La validation en 2 étapes activée sur votre compte Google
- Accès aux paramètres de sécurité Google

## Étapes de configuration

### 1. Générer un App Password Gmail

1. Accédez à votre compte Google : https://accounts.google.com
2. Cliquez sur **"Sécurité"** dans la barre latérale gauche
3. Activez la **"Validation en 2 étapes"** si ce n'est pas déjà fait
4. Une fois activée, allez dans **"Mots de passe des applications"**
5. Sélectionnez :
   - **Appli** : Mail
   - **Appareil** : Windows, Mac ou Linux (selon votre OS)
6. Cliquez sur **"Générer"**
7. Copiez le mot de passe affiché (16 caractères sans espaces)

### 2. Configurer le fichier `.env`

Ouvrez le fichier `.env` à la racine du projet et modifiez la section MAIL :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre-email@gmail.com
MAIL_FROM_NAME="Jeunesse en Jésus"
```

**⚠️ IMPORTANT** :
- Remplacez `votre-email@gmail.com` par votre adresse Gmail réelle
- Remplacez `votre-app-password` par le mot de passe d'application généré à l'étape 1
- **Ne commitez JAMAIS le fichier `.env` avec des vrais credentials** — utilisez `.env.example` pour la documentation

### 3. Vérifier la configuration (optionnel)

Pour tester l'envoi d'un email :

```bash
php artisan tinker
>>> Mail::raw('Test', function($m) { $m->to('test@example.com')->subject('Test'); })
```

Vérifiez dans la boîte de réception du destinataire.

## En production

- Utilisez un gestionnaire de secrets (AWS Secrets Manager, HashiCorp Vault, etc.)
- Ne stockez JAMAIS les credentials dans le code source
- Rotez les App Passwords régulièrement
- Surveillez les logs de sécurité Gmail

## Troubleshooting

| Erreur | Solution |
|--------|----------|
| **"535 5.7.8 Authentication failed"** | Vérifiez le mot de passe d'application (pas le mot de passe Gmail) |
| **"Connection refused"** | Vérifiez que le port 587 est ouvert (firewall) |
| **"SSL error"** | Assurez-vous que `MAIL_ENCRYPTION=tls` est défini |
| **Email non reçu** | Vérifiez les logs et activez le debug dans `.env` : `MAIL_DEBUG=true` |

## Références
- [Google Account Security](https://accounts.google.com/signin/safety)
- [Laravel Mail Documentation](https://laravel.com/docs/12/mail)
