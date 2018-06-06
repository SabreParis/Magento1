# SABRE - Website Magento

## Environnement technique
* Magento 1.9.2.4
* Mysql version > 5.5
* PHP v5.6

## Informations de déploiement
https://docs.google.com/a/ayaline.com/document/d/1b3uvFMnBFeBi2E059rT8TPiVJh0qvwYZDS9ILlKfVkk/edit?usp=sharing

## URLs de développement
Sur la machine virtuelle de développement, les URLs d'accès sont les suivantes :
 * website FR : http://www.sabre-fr.dev
 * website DE : http://www.sabre-de.dev
 * website NL : http://www.sabre-nl.dev
 * website UK : http://www.sabre-uk.dev
 * website BE : http://www.sabre-be.dev
 * website IT : http://www.sabre-it.dev
 * website USA : http://www.sabre-com.dev
 * backoffice : admin.sabre-fr.dev
   * Utilisateur : admin
   * Mot de passe : admin123
   
## Exemple de configuration pour le vhost
```apacheconfig

<VirtualHost *:80>
    DocumentRoot /var/www/sabre/www/backend
    ServerName admin.sabre-fr.dev
    
    # Possible values include: debug, info, notice, warn, error, crit,
    # alert, emerg.
    LogLevel warn
    
    ErrorLog ${APACHE_LOG_DIR}/magento.admin.sabre.dev-error.log
    CustomLog ${APACHE_LOG_DIR}/magento.admin.sabre.dev-access.log common
    #RewriteLog ${APACHE_LOG_DIR}/magento.admin.sabre.dev-urlrewrite.log
    #RewriteLogLevel 0
    AcceptPathInfo On
    DirectoryIndex index.php
    
    SetEnv MAGE_IS_DEVELOPER_MODE 1
    
    <Directory "/var/www/sabre/dev/www/backend">
        Options FollowSymLinks Includes
        AllowOverride all
        Order allow,deny
        Allow from all
    </Directory>

    Alias /js /var/www/sabre/js
    Alias /media /var/www/sabre/media
    Alias /skin /var/www/sabre/skin

</VirtualHost>


##########################################
# Configuration pour sabre France
##########################################
<VirtualHost *:80>
    DocumentRoot /var/www/sabre/www/frontend
    ServerName www.sabre-fr.dev
    ServerAlias www.sabre-de.dev
    ServerAlias www.sabre-nl.dev
    ServerAlias www.sabre-uk.dev
    ServerAlias www.sabre-be.dev
    ServerAlias www.sabre-it.dev
    ServerAlias www.sabre-com.dev

    # Possible values include: debug, info, notice, warn, error, crit,
    # alert, emerg.
    LogLevel info

    ErrorLog ${APACHE_LOG_DIR}/magento.www.sabre-fr.dev-error.log
    CustomLog ${APACHE_LOG_DIR}/magento.www.sabre-fr.dev-access.log common
    #RewriteLog ${APACHE_LOG_DIR}/magento.www.sabre-fr.dev-urlrewrite.log
    #RewriteLogLevel 0
    AcceptPathInfo On
    DirectoryIndex index.php

    SetEnvIf Host www.sabre-fr.dev MAGE_RUN_CODE=sabre_fr
    SetEnvIf Host www.sabre-de.dev MAGE_RUN_CODE=sabre_de
    SetEnvIf Host www.sabre-nl.dev MAGE_RUN_CODE=sabre_nl
    SetEnvIf Host www.sabre-uk.dev MAGE_RUN_CODE=sabre_uk
    SetEnvIf Host www.sabre-be.dev MAGE_RUN_CODE=sabre_be
    SetEnvIf Host www.sabre-it.dev MAGE_RUN_CODE=sabre_it
    SetEnvIf Host www.sabre-com.dev MAGE_RUN_CODE=sabre_com

    SetEnv MAGE_RUN_TYPE website
    SetEnv MAGE_IS_DEVELOPER_MODE 1

    <Directory "/var/www/sabre/www/frontend">
        Options FollowSymLinks Includes
        AllowOverride all
        Order allow,deny
        Allow from all
    </Directory>
        
    Alias /js /var/www/sabre/js
    Alias /media /var/www/sabre/media
    Alias /skin /var/www/sabre/skin
    Alias /robots.txt /var/www/sabre/www/fr/robots.txt
    Alias /var/magicento/eval.php /var/www/sabre/var/magicento/eval.php

</VirtualHost>

```

## Compilation des CSS
Pour les pré-requis d'installation, se reporter au document de déploiement.

En shell : 
```
cd /var/www/sabre
node-sass --output-style compressed --source-map true skin/frontend/sabre/default/scss/main.scss skin/frontend/sabre/default/css/main.css
node-sass --output-style expanded --source-map true skin/frontend/sabre/default/scss/ups/iframe.scss skin/frontend/sabre/default/css/ups_iframe.css
n98-magerun cache:clean
```

Il est aussi possible de passer directement par le compilateur de PHPStorm.
Installation : https://www.jetbrains.com/help/phpstorm/2016.3/transpiling-sass-less-and-scss-to-css.html
Les css compilées ne sont pas prises en compte par git (cf .gitignore)

