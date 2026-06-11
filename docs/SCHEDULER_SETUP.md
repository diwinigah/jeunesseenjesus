# Configuration Scheduler - Clôture automatique des éditions

## Contexte
Cette documentation explique comment configurer et lancer le **Scheduler** pour exécuter automatiquement la clôture des éditions de camp expirées.

## Configuration

### 1. Vérifier la commande Artisan

La commande est définie dans `app/Console/Commands/CloseExpiredCampEditions.php`.

Vérifiez qu'elle fonctionne manuellement :

```bash
php artisan editions:close-expired
```

### 2. Enregistrer dans le Scheduler

La commande est enregistrée dans `routes/console.php` :

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('editions:close-expired')
    ->hourly()
    ->withoutOverlapping();
```

La tâche s'exécute **toutes les heures** sans risque de chevauchement.

## Développement local

### Lancer le scheduler

Pour démarrer le scheduler en mode écoute :

```bash
php artisan schedule:work
```

Le scheduler vérifie chaque minute si une tâche doit s'exécuter.

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
- ✅ **Scheduler** (via les dépendances)

### Tester la commande manuellement

```bash
php artisan editions:close-expired
```

Vérifiez les logs :

```bash
tail -f storage/logs/laravel.log
```

## Production (Cron)

Sur un serveur de production, utilisez une **Cron Job** pour lancer le scheduler toutes les minutes.

### Ajouter la Cron Job

Ouvrez le crontab :

```bash
crontab -e
```

Ajoutez la ligne suivante :

```cron
* * * * * cd /path/to/laravel-project && php artisan schedule:run >> /dev/null 2>&1
```

Remplacez `/path/to/laravel-project` par le chemin réel du projet.

### Vérifier les Cron Jobs actives

```bash
crontab -l
```

### Logs des exécutions

Les exécutions sont enregistrées dans `storage/logs/laravel.log` :

```bash
grep "editions:close-expired" storage/logs/laravel.log
```

## Logique de clôture

Chaque heure, la commande :

1. ✅ Cherche les éditions avec :
   - `status` ≠ `closed` ET ≠ `archived`
   - `registration_close_at` < maintenant

2. ✅ Pour chaque édition trouvée :
   - Change le statut à `closed`
   - Utilise `saveQuietly()` (évite les observers)
   - Enregistre l'action dans les logs

3. ✅ Affiche le résultat : nombre d'éditions fermées

## Exemple de flux

### Avant clôture
```
Édition 1 | status: open | registration_close_at: 2025-06-05 10:00:00 ← EXPIRÉE
Édition 2 | status: open | registration_close_at: 2025-06-10 10:00:00 ← OK
Édition 3 | status: closed | registration_close_at: 2025-05-01 10:00:00 ← DÉJÀ FERMÉE
```

### Après exécution du scheduler
```
Édition 1 | status: closed ✓ | registration_close_at: 2025-06-05 10:00:00 ← FERMÉE
Édition 2 | status: open   | registration_close_at: 2025-06-10 10:00:00 ← INCHANGÉE
Édition 3 | status: closed | registration_close_at: 2025-05-01 10:00:00 ← INCHANGÉE
```

## Troubleshooting

| Problème | Solution |
|----------|----------|
| **Cron job ne s'exécute pas** | Vérifiez : `crontab -l` et les logs système |
| **Commande ne s'exécute pas localement** | Lancez `php artisan schedule:work` |
| **Erreur d'exécution** | Vérifiez `storage/logs/laravel.log` |
| **Éditions non fermées** | Vérifiez les statuts dans la DB et les timestamps |

## Paramétrage (optionnel)

Pour modifier la fréquence, éditez `routes/console.php` :

```php
// Toutes les 30 minutes
Schedule::command('editions:close-expired')
    ->everyThirtyMinutes()
    ->withoutOverlapping();

// Deux fois par jour (8h et 20h)
Schedule::command('editions:close-expired')
    ->twiceDaily(8, 20)
    ->withoutOverlapping();
```

## Références
- [Laravel Scheduling Documentation](https://laravel.com/docs/12/scheduling)
- [Cron Job Tutorial](https://crontab.guru/)
