#!/bin/sh
set -e

# Render ne fournit APP_KEY qu'une fois : on la génère à la première exécution si elle manque.
if [ -z "$APP_KEY" ]; then
  echo "APP_KEY absente : génération (à copier dans les variables d'environnement Render pour la garder)."
  php artisan key:generate --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migre la base à chaque démarrage. Sans danger : les migrations déjà appliquées sont ignorées.
# Pour désactiver (ex. pendant un débogage), mettre RUN_MIGRATIONS=false dans les variables Render.
if [ "$RUN_MIGRATIONS" != "false" ]; then
  echo "Application des migrations..."
  php artisan migrate --force
fi

echo "Démarrage sur le port ${PORT:-10000}..."
exec php artisan serve --host 0.0.0.0 --port "${PORT:-10000}"
