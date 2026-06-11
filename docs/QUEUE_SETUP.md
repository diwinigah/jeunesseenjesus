# Configuration Queue Worker

## Contexte
Cette documentation explique comment configurer et lancer le **Queue Worker** pour traiter les jobs en arrière-plan, notamment les notifications utilisateurs.

## Configuration

### 1. Vérifier le fichier `.env`

Assurez-vous que la ligne suivante est présente :

```env
QUEUE_CONNECTION=database
```

C'est déjà configuré par défaut dans le projet.

### 2. Vérifier la migration de la table `jobs`

Lancez les migrations si ce n'est pas fait :

```bash
php artisan migrate
```

Cela crée la table `jobs` utilisée pour stocker les jobs en attente.

## Développement local

### Lancer le worker

Pour démarrer le worker en mode écoute :

```bash
php artisan queue:work
```

Le worker traite les jobs au fur et à mesure qu'ils arrivent.

### Lancer avec options avancées

Pour un meilleur contrôle :

```bash
php artisan queue:work --timeout=60 --tries=3 --sleep=3
```

| Option | Description |
|--------|-------------|
| `--timeout=60` | Délai max (en secondes) pour exécuter un job |
| `--tries=3` | Nombre d'essais avant d'échouer |
| `--sleep=3` | Délai avant de vérifier les prochains jobs (en secondes) |

### Vérifier les jobs en attente

```bash
php artisan queue:monitor
```

Affiche le nombre de jobs en attente par queue.

### Lancer le scheduler + worker (en parallèle)

Utilisez le script `npm run dev` (déjà configuré dans `package.json`) :

```bash
npm run dev
```

Cela lance :
- ✅ Serveur Laravel (`php artisan serve`)
- ✅ Worker des jobs (`php artisan queue:listen`)
- ✅ Logs en temps réel (`php artisan pail`)
- ✅ Vite (assets front-end)

## Production (Supervisor)

Sur un serveur de production, utilisez **Supervisor** pour gérer le worker en arrière-plan.

### Installation de Supervisor

```bash
sudo apt-get install supervisor
```

### Configuration Supervisor

Créez le fichier `/etc/supervisor/conf.d/laravel-worker.conf` :

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php artisan queue:work --sleep=3 --tries=3 --timeout=90
autostart=true
autorestart=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/log/laravel-worker.log
stopwaitsecs=3600
```

### Démarrer Supervisor

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### Vérifier l'état

```bash
sudo supervisorctl status laravel-worker:*
```

## Jobs et Notifications

Les notifications avec `ShouldQueue` sont traitées automatiquement :

```php
class NewRegistrationNotification extends Notification implements ShouldQueue
{
    // ...
}
```

Tant que le worker tourne, les notifications sont envoyées en arrière-plan.

## Troubleshooting

| Problème | Solution |
|----------|----------|
| **Jobs non traités** | Vérifiez que `QUEUE_CONNECTION=database` dans `.env` |
| **Worker qui crash** | Vérifiez les logs : `storage/logs/laravel.log` |
| **Timeout** | Augmentez `--timeout` selon la durée de vos jobs |
| **Pas de retry** | Vérifiez que `--tries` est > 1 |

## Références
- [Laravel Queue Documentation](https://laravel.com/docs/12/queues)
- [Supervisor Documentation](http://supervisord.org/)
