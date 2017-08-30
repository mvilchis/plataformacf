chown -R $USER:$USER /var/www/
chmod +w /var/www -R
exec supervisord -n
