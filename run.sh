#!/bin/sh

# Run our defined exec if args empty
if [ -z "$1" ]; then
    role=${CONTAINER_ROLE:-app}

    if [ "$role" = "app" ]; then
        echo "Running PHP-FPM..."
        exec php /var/www/artisan queue:work --queue=notifications &
        exec php-fpm
    else
        echo "Could not match the container role \"$role\""
        exit 1
    fi

else
    exec "$@"
fi
