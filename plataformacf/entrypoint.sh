chown -R $USER:$USER /var/www/
chmod a+w /var/www -R
exec supervisord -n