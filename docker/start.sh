#!/bin/sh
set -e

# Render ne fournit APP_KEY qu'une fois : on la génère à la première exécution si elle manque.
# --show affiche la clé sans tenter de l'écrire dans un fichier .env (qui n'existe pas ici).
if [ -z "$APP_KEY" ]; then
  APP_KEY=$(php artisan key:generate --show --no-ansi | tail -n1 | tr -d '\r\n')
  echo "=============================================================="
  echo "APP_KEY générée : $APP_KEY"
  echo "Copiez-la dans Environment -> APP_KEY sur Render, puis Save Changes,"
  echo "pour qu'elle reste stable d'un redémarrage à l'autre."
  echo "=============================================================="
  export APP_KEY
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ "$RUN_MIGRATIONS" != "false" ]; then
  echo "Application des migrations..."
  php artisan migrate --force
fi

echo "Démarrage sur le port ${PORT:-10000}..."
exec php artisan serve --host 0.0.0.0 --port "${PORT:-10000}"