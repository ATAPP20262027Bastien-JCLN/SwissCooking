#!/bin/sh

set -eu

# ============================================================
# Detect OS / Apache layout
# ============================================================

if [ -f /etc/os-release ]; then
    . /etc/os-release
else
    echo "❌ Cannot detect operating system."
    exit 1
fi

case "$ID" in
    ubuntu|debian|linuxmint)
        APACHE_SERVICE="apache2"
        APACHECTL="apache2ctl"
        APACHE_FOLDER="/etc/apache2/sites-available"
        APACHE_ENABLE_CMD="a2ensite"
        APACHE_LOG_DIR="/var/log/apache2"
        APACHE_MODE="debian"
        ;;

    fedora|rhel|centos|rocky|almalinux)
        APACHE_SERVICE="httpd"
        APACHECTL="apachectl"
        APACHE_FOLDER="/etc/httpd/conf.d"
        APACHE_ENABLE_CMD=""
        APACHE_LOG_DIR="/var/log/httpd"
        APACHE_MODE="redhat"
        ;;

    *)
        echo "❌ Unsupported operating system: $ID"
        exit 1
        ;;
esac


# ============================================================
# Check arguments
# ============================================================

if [ "$#" -ne 2 ]; then
    echo "Usage: $0 <nom_projet> <chemin_projet>"
    exit 1
fi


# ============================================================
# Project
# ============================================================

PROJECT_NAME="$1"
PROJECT_PATH="$(realpath "$2")"

SERVER_NAME="${PROJECT_NAME}.cfpt.loc"

APACHE_VHOST_FILENAME="${PROJECT_NAME}.cfpt.conf"
APACHE_VHOST_FILE="${APACHE_FOLDER}/${APACHE_VHOST_FILENAME}"


# ============================================================
# Check Apache
# ============================================================

APACHE_INSTALLED=false

if command -v "$APACHECTL" >/dev/null 2>&1; then
    APACHE_INSTALLED=true
fi


# ============================================================
# Check project
# ============================================================

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


# ============================================================
# Check web server
# ============================================================

if [ "$APACHE_INSTALLED" = false ]; then
    echo "❌ Apache is not installed."
    echo ""
    echo "Ubuntu/Debian:"
    echo "  sudo apt install apache2"
    echo ""
    echo "Fedora:"
    echo "  sudo dnf install httpd"
    exit 1
fi


# ============================================================
# Information
# ============================================================

echo ""
echo "========================================"
echo "Apache VHost configuration"
echo "========================================"
echo ""
echo "OS           : $ID"
echo "Apache       : $APACHE_SERVICE"
echo "Projet       : $PROJECT_NAME"
echo "Chemin       : $PROJECT_PATH"
echo "Server name  : $SERVER_NAME"
echo "VHost        : $APACHE_VHOST_FILE"
echo ""


# ============================================================
# Create Apache VHost
# ============================================================

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

    ErrorLog ${APACHE_LOG_DIR}/${SERVER_NAME}-error.log
    CustomLog ${APACHE_LOG_DIR}/${SERVER_NAME}-access.log combined

</VirtualHost>
EOF


echo "✅ VHost Apache écrit :"
echo "   $APACHE_VHOST_FILE"
echo ""


# ============================================================
# Enable site
# ============================================================

if [ "$APACHE_MODE" = "debian" ]; then

    echo "🔗 Activation du VHost avec a2ensite..."

    sudo "$APACHE_ENABLE_CMD" "$APACHE_VHOST_FILENAME" >/dev/null

    echo "✅ VHost activé."

else

    echo "🔗 Fedora/RHEL détecté."
    echo "   Les fichiers dans /etc/httpd/conf.d/ sont automatiquement chargés."

fi


echo ""


# ============================================================
# Test Apache configuration
# ============================================================

echo "🔎 Vérification de la configuration Apache..."

if sudo "$APACHECTL" -t; then

    echo "✅ Configuration Apache valide."

else

    echo "❌ Configuration Apache invalide."
    exit 1

fi


# ============================================================
# Reload Apache
# ============================================================

echo ""
echo "🔄 Rechargement d'Apache..."

sudo systemctl reload "$APACHE_SERVICE"

echo "✅ Apache rechargé."


# ============================================================
# Hosts file
# ============================================================

echo ""
echo "📝 Vérification du fichier /etc/hosts..."

if grep -q "[[:space:]]${SERVER_NAME}\$" /etc/hosts; then

    echo "✅ ${SERVER_NAME} est déjà présent dans /etc/hosts."

else

    echo "➕ Ajout de ${SERVER_NAME} dans /etc/hosts."

    echo "127.0.0.1 ${SERVER_NAME}" | sudo tee -a /etc/hosts >/dev/null

    echo "✅ ${SERVER_NAME} ajouté à /etc/hosts."

fi


# ============================================================
# Done
# ============================================================

echo ""
echo "========================================"
echo "✅ VHost configuration completed"
echo "========================================"
echo ""
echo "🌐 http://${SERVER_NAME}"
echo "📁 ${PROJECT_PATH}/public"
echo ""