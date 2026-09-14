#!/bin/sh

set -eu

APACHE_FOLDER="/etc/apache2/sites-available"
NGINX_AVAILABLE="/etc/nginx/sites-available"
NGINX_ENABLED="/etc/nginx/sites-enabled"

if [ "$#" -ne 2 ]; then
    echo "Usage: $0 <nom_projet> <chemin_projet>"
    exit 1
fi

PROJECT_NAME="$1"
PROJECT_PATH="$(realpath "$2")"

SERVER_NAME="${PROJECT_NAME}.cfpt.loc"

APACHE_VHOST_FILENAME="${PROJECT_NAME}.cfpt.conf"
APACHE_VHOST_FILE="${APACHE_FOLDER}/${APACHE_VHOST_FILENAME}"

NGINX_VHOST_FILENAME="${PROJECT_NAME}.cfpt.conf"
NGINX_VHOST_FILE="${NGINX_AVAILABLE}/${NGINX_VHOST_FILENAME}"
NGINX_VHOST_LINK="${NGINX_ENABLED}/${NGINX_VHOST_FILENAME}"

APACHE_INSTALLED=false
NGINX_INSTALLED=false

if command -v apache2ctl >/dev/null 2>&1; then
    APACHE_INSTALLED=true
fi

if command -v nginx >/dev/null 2>&1; then
    NGINX_INSTALLED=true
fi

if [ ! -d "$PROJECT_PATH" ]; then
    echo "❌ Project directory does not exist:"
    echo "   $PROJECT_PATH"
    exit 1
fi

if [ ! -d "$PROJECT_PATH/public" ]; then
    echo "❌ Public directory does not exist:"
    echo "   $PROJECT_PATH/public"
    exit 1
fi

if [ "$APACHE_INSTALLED" = false ] && [ "$NGINX_INSTALLED" = false ]; then
    echo "❌ Aucun serveur web Apache ou Nginx n'est installé."
    exit 1
fi

echo ""
echo "Projet      : $PROJECT_NAME"
echo "Chemin      : $PROJECT_PATH"
echo "Server name : $SERVER_NAME"
echo ""

if [ "$APACHE_INSTALLED" = true ]; then

    echo "🌐 Apache détecté."
    echo "📝 Création/remplacement du VHost Apache..."

    sudo tee "$APACHE_VHOST_FILE" >/dev/null <<EOF
<VirtualHost *:80>
    ServerName ${SERVER_NAME}

    ServerAdmin webmaster@localhost
    DocumentRoot ${PROJECT_PATH}/public

    <Directory ${PROJECT_PATH}/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/${SERVER_NAME}-error.log
    CustomLog \${APACHE_LOG_DIR}/${SERVER_NAME}-access.log combined
</VirtualHost>
EOF

    echo "✅ VHost Apache écrit :"
    echo "   $APACHE_VHOST_FILE"

    sudo a2ensite "$APACHE_VHOST_FILENAME" >/dev/null

    echo "🔎 Vérification de la configuration Apache..."

    if sudo apache2ctl -t; then
        echo "✅ Configuration Apache valide."

        sudo systemctl reload apache2

        echo "✅ Apache rechargé."
    else
        echo "❌ Configuration Apache invalide."

        sudo a2dissite "$APACHE_VHOST_FILENAME" \
            >/dev/null 2>&1 || true

        exit 1
    fi

elif [ "$NGINX_INSTALLED" = true ]; then

    echo "🌐 Nginx détecté."
    echo "📝 Création/remplacement du server block Nginx..."

    sudo tee "$NGINX_VHOST_FILE" >/dev/null <<EOF
server {
    listen 80;
    listen [::]:80;

    server_name ${SERVER_NAME};

    root ${PROJECT_PATH}/public;
    index index.php index.html;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }

    access_log /var/log/nginx/${SERVER_NAME}-access.log;
    error_log /var/log/nginx/${SERVER_NAME}-error.log;
}
EOF

    echo "✅ Server block Nginx écrit :"
    echo "   $NGINX_VHOST_FILE"

    if [ -e "$NGINX_VHOST_LINK" ] || [ -L "$NGINX_VHOST_LINK" ]; then
        sudo rm -f "$NGINX_VHOST_LINK"
    fi

    sudo ln -s "$NGINX_VHOST_FILE" "$NGINX_VHOST_LINK"

    echo "🔎 Vérification de la configuration Nginx..."

    if sudo nginx -t; then
        echo "✅ Configuration Nginx valide."

        sudo systemctl reload nginx

        echo "✅ Nginx rechargé."
    else
        echo "❌ Configuration Nginx invalide."

        sudo rm -f "$NGINX_VHOST_LINK"

        exit 1
    fi

fi

echo ""
echo "========================================"
echo "✅ VHost configuration completed"
echo "========================================"
echo ""
echo "🌐 http://${SERVER_NAME}"
echo "📁 ${PROJECT_PATH}/public"
echo ""