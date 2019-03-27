# Bedya Server

Below you can see the steps to follow to set up your **Bedya Server**.
The following steps are only tested under an **Ubuntu Server 16.04**.

All the files necessary for the server to work are located in the [Server](https://github.com/RafaMunoz/Bedya/tree/master/Server) folder of this repository.

## Install

We will start installing Apache and PHP 7.

    sudo apt install apache2
    sudo apt install libapache2-mod-php php7.0 php7.0-dev php-gettext php-curl

In the php.ini of /etc/php/7.0/apache2 add:

    extension=mongodb.so

Configure and secure Apache

    sudo nano /etc/apache2/apache2.conf

You have to edit the <Directory/var/www/> section as follows:

    <Directory /var/www/>
	        Options FollowSymLinks
	        AllowOverride All
	        Require all granted
			ErrorDocument 403 /server-error/403-error.html
			ErrorDocument 404 /server-error/404-error.html
			ErrorDocument 500 /server-error/500-error.html
			ErrorDocument 502 /server-error/502-error.html
			ErrorDocument 503 /server-error/503-error.html
			ErrorDocument 504 /server-error/504-error.html
    </Directory>

To hide the apache version is added to the end of the file:

    ServerSignature Off
    ServerTokens Prod

Activate the rewrite module and restart the web server.

    a2enmod rewrite
    service apache2 restart

If we want to activate the firewall we can execute the following commands.

    sudo ufw disable
    sudo ufw default deny incoming
    sudo ufw allow ssh 
    sudo ufw allow 80/tcp
    sudo ufw allow 443/tcp
    sudo ufw enable
    sudo ufw status

The Bedoya server uses MongoDB database, if you do not want to install it on your server, [MongoDB Atlas](https://www.mongodb.com/cloud/atlas) has a free plan with 512MB of storage that will be more than enough.

We copy the web and rename htaccess by .htaccess

    sudo cp -R html/ /var/www/
    sudo mv /var/www/html/htaccess /var/www/html/.htaccess
    sudo mv /var/www/html/api/htaccess /var/www/html/api/.htaccess
    sudo chmod -R 755 /var/www/html/

We customize the file with web configurations.

    sudo nano /var/www/html/conexiones.php

    # Connection with MongoDB database
    $mongodbatlas="URI con la conexion a la BD de MongoDB";

    # Bot for authentication (Optional)
    $bottoken = "Token de tu bot idbedya";

We need to install composer for the operation of the api

    sudo php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');"
    sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
    cd /var/www/html/api
    sudo composer require slim/slim "^3.0"

If we want to use the HTTPS protocol instead of HTTP we can use [Certbot](https://certbot.eff.org/).

We installed the library pyTelegramBotAPI and pymongo.

    sudo pip install pyTelegramBotAPI
    sudo pip install pymongo

Now we will configure the bot that our Telegram ID will tell us and the one that will allow us to register on our server.

    sudo mkdir /var/log/idbedya
    sudo chmod 644 /var/log/idbedya
    sudo cp -R idbedya/ /usr/local/
    cd /usr/local/idbedya/

We edit the settings for the idBedya bot:

    sudo nano /usr/local/idbedya/idbedya.conf
    
    token = Bot token that you will use for this bot
    bd = URI with the connection to the BD of MongoDB

We create the service that is responsible for starting this bot when the server starts.

    sudo chmod +x /usr/local/idbedya/idbedya.py
    sudo mv idbedya.service /etc/systemd/system/
    sudo chmod +x /etc/systemd/system/idbedya.service
    sudo systemctl enable idbedya.service
    sudo systemctl daemon-reload

Now we can start the bot and we have you available to register

    sudo service idbedya start

You can access the web of your Bedoya server by putting the ip address in your web browser.
