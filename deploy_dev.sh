
composer install

# Rights settings.
HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs

# Create db.
php app/console doctrine:database:drop --force -q
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
echo "y" | php app/console doctrine:fixture:load
