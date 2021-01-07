## Prérequis
 PHP >= 7.4.0 \
 MySQL \
 Composer 

*Installation:
=======

* Composer install
* Create the .env.local file in the root directory and set your local variables values (DATABASE_URL)
* Launch the command line **php bin/console doctrine:database:create** to create the Database. 
* Launch the command line **php bin/console doctrine:migrations:migrate -n** to update the schema.
* Launch the command line **php bin/console hautelook:fixtures:load -n** to fill the User table with fictive one.


*Generate an ssh key:

$ mkdir -p config/jwt
$ openssl genrsa -out config/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

*Configuration v-host:
======================

<VirtualHost *:80>
	ServerName www.gipam.local
	SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1
	DocumentRoot "C:/wamp64/www/gipam/gipam-frontend/dist/"
	#backend
	Alias /gipam-backend "C:/wamp64/www/gipam/gipam-backend/public"
	<Directory "C:/wamp64/www/gipam/gipam-backend/public">
		SetEnv APP_ENV dev
        	Require all granted
        	Allowoverride all
        	Order Allow,Deny
        	Allow from All
        	Options -MultiViews
        	RewriteEngine On
        	RewriteBase /gipam-backend
        	RewriteCond %{REQUEST_FILENAME} !-f
        	RewriteRule ^(.*)$ index.php [QSA,L]
	</Directory>
	#Angular App
    	Alias /frontend "C:/wamp64/www/gipam/gipam-frontend/dist/"
    	<Directory "C:/wamp64/www/gipam/gipam-frontend/dist/">
        	Require all granted
        	Allowoverride all
        	RewriteEngine On
        	RewriteBase /
        	RewriteRule ^index/.html$ - [L]
        	RewriteCond %{REQUEST_FILENAME} !-f
        	RewriteCond %{REQUEST_FILENAME} !-d
    	    	RewriteRule . index.html [L]
    	</Directory>
</VirtualHost>


API Test: 
- lancer la commande: vendor/bin/codecept run api


#### **Recommendations**   
* Use Doctrine migrations instead of php bin/console doctrine:schema:update --force (Take a look at https://symfony.com/doc/current/doctrine.html#migrations-creating-the-database-tables-schema )

*to generate a new version of migration: **php bin/console doctrine:migrations:diff**
