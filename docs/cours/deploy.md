# Deploy

## je me connecte au serveur

```bash
ssh student@xxxxxxx-server.eddi.cloud
```

### Warning: Permanently added the ECDSA host key for IP address '140.82.121.4' to the list of known hosts."

C'est juste un message d'avertissement la première fois où l'on se connecte à une machine distante
C'est pas grave

## Checklist deploy

Objectif mettre notre site Symfony en ligne

on se déplace dans le bon dossier (`/var/www/html/`) pour faire notre `git clone`

```bash
cd /var/www/html
git clone git@github.com:xxxxxxxxxx.git
```

tips : pour retrouver le git du projet dans vscode de dev:

```bash
git remote -v
origin  git@github.com:O-clock-Radium/symfo-oflix-JB-oclock.git (fetch)
origin  git@github.com:O-clock-Radium/symfo-oflix-JB-oclock.git (push)
```

on rentre dans notre dossier de projet

```bash
cd symfo-oflix-JB-oclock
```

Dans le dossier de notre projet que l'on vient de cloner

* `composer install`
* parametrer Doctrine : `.env.local`
  * on va utiliser un login/mot de passe pour mysql qui a tout les droits sur la BDD
  * `explorateur:Ereul9Aeng`
  * `toto:password`
  * cf > Pour tester le bon login/mdp de mysql
* créer le fichier : `nano .env.local`
  * on copie/colle la chaine de connexion : 
    * `DATABASE_URL="mysql://explorateur:Ereul9Aeng@127.0.0.1:3306/oflix?serverVersion=mariadb-10.3.38&charset=utf8mb4"`
    * `DATABASE_URL="mysql://toto:password@127.0.0.1:3306/oflix?serverVersion=mariadb-10.3.38&charset=utf8mb4"`
  * on pense à tout notre paramétrage
    * OMDB_API_KEY
    * MAINTENANCE_ACTIVE
    * MAIL
  * quand on a fini, on fait `ctrl+x`, on répond à la question, et on valide avec entrée
* créer la BDD : `bin/console doctrine:database:create`
  * Created database `oflix` for connection named default
* créer la structure de notre BDD : `bin/console doctrine:migrations:migrate`
* lancer nos fixtures : `bin/console doctrine:fixtures:load`
* Bonus : la commande pour les posters : `bin/console radium:oflix:poster-load`

on peut tester notre affichage, la page d'acceuil fonctione !!!
mais pas les autres pages...

C'est un problème de réécriture d'URL
On a changé de serveur web, on est avec apache, et on a pas activé la réécriture d'URL
Un pacakge fait ça pour nous

```bash
composer require symfony/apache-pack
Info from https://repo.packagist.org: #StandWithUkraine
Using version ^1.0 for symfony/apache-pack
./composer.json has been updated
Running composer update symfony/apache-pack
Loading composer repositories with package information
Updating dependencies
Lock file operations: 1 install, 0 updates, 0 removals
  - Locking symfony/apache-pack (v1.0.1)
Writing lock file
Installing dependencies from lock file (including require-dev)
Package operations: 1 install, 0 updates, 0 removals
  - Installing symfony/apache-pack (v1.0.1): Extracting archive
Package sensio/framework-extra-bundle is abandoned, you should avoid using it. Use Symfony instead.
Generating optimized autoload files
102 packages you are using are looking for funding.
Use the `composer fund` command to find out more!

Symfony operations: 1 recipe (161c16247cdc56de69cc7baa9129ba7b)
  -  WARNING  symfony/apache-pack (>=1.0): From github.com/symfony/recipes-contrib:main
    The recipe for this package comes from the "contrib" repository, which is open to community contributions.
    Review the recipe at https://github.com/symfony/recipes-contrib/tree/main/symfony/apache-pack/1.0

    Do you want to execute this recipe?
    [y] Yes
    [n] No
    [a] Yes for all packages, only for the current installation session
    [p] Yes permanently, never ask again for this project
    (defaults to n): y
  - Configuring symfony/apache-pack (>=1.0): From github.com/symfony/recipes-contrib:main
Executing script cache:clear [OK]
Executing script assets:install public [OK]

 What's next?


Some files have been created and/or updated to configure your new packages.
Please review, edit and commit them: these files are yours.
```

si cela ne fonctionne toujours pas, c'est que Apache ne lit pas le fichier .htaccess
il faut donc modifier la config de apache

cf annexes

il nous manque plus que le token JWT

```bash
bin/console lexik:jwt:generate-keypair
```

il ne nous reste plus qu'a passer en PROD
Dans le dossier de notre projet

```bash
nano .env
```

```ini
APP_ENV=prod
```

contexte : je fais une modification sur un template, je push mes modifications sur master et git pull sur server (machine virtuelle)
je rafraichis ma page
erreur = mon navigateur n'affiche pas la modification
pourquoi ?
en prod le navigateur garde tout en cache, il faut vider le cache

```bash
sudo APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
```

## annexes

### fatal: could not create work tree dir 'symfo-oflix-JB-oclock': Permission denied

je vérifie les droits du dossier

```bash
$ ls -la
total 20
drwxr-xr-x 2 root root 4096 Apr 17 11:03 .
drwxr-xr-x 3 root root 4096 Apr 17 11:03 ..
-rw-r--r-- 1 root root 10918 Apr 17 11:03 index.html
```

tu n'a pas les droits d'écriture dans le dossier (le dossier courant appartient à root)

**ATTENTION** cette commande est dangereuse car elle modifie les droits de dossier

```bash
sudo chown -R student:www-data .
```

### Pour tester le bon login/mdp de mysql

```bash
mysql -u explorateur -p
Enter password:
Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 40
Server version: 10.3.38-MariaDB-0ubuntu0.20.04.1 Ubuntu 20.04

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [(none)]>
```

Si je voix cet affichage c'est bon, on peut sortir

```bash
MariaDB [(none)]>exit
Bye
```

### config apache

Pour activer la réécriture d'URL dans Apache, il faut:

* activer le module de réécriture: `sudo a2enmod rewrite`

```bash
Enabling module rewrite.
To activate the new configuration, you need to run:
  systemctl restart apache2
```

il nous demande de relancer le serveur apache, ce que l'on fait

```bash
systemctl restart apache2
==== AUTHENTICATING FOR org.freedesktop.systemd1.manage-units ===
Authentication is required to restart 'apache2.service'.
Multiple identities can be used for authentication:
 1.  Ubuntu (ubuntu)
 2.  aurelien
 3.  spada
 4.  hdg
 5.  christophe
 6.  student
Choose identity to authenticate as (1-6): 6
Password:
==== AUTHENTICATION COMPLETE ===
```

Cela ne suffit pas pour que apache lise notre fichier .htaccess

On va aller lui dire d'autoriser la lecture des fichiers .htaccess

```bash
sudo nano /etc/apache2/apache2.conf
```

On scroll jusqu'à `<Directory /var/www/>` (7 fois page down)
On modifie ça :

```text
<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
</Directory>
```

par :

```text
 <Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride all
        Require all granted
</Directory>
 ```

`ctrl+x` on répond à la question et on valide avec `entrée`

On a modifié le fichier de configuration de apache, il faut relancer le serveur

```bash
systemctl restart apache2
==== AUTHENTICATING FOR org.freedesktop.systemd1.manage-units ===
Authentication is required to restart 'apache2.service'.
Multiple identities can be used for authentication:
 1.  Ubuntu (ubuntu)
 2.  aurelien
 3.  spada
 4.  hdg
 5.  christophe
 6.  student
Choose identity to authenticate as (1-6): 6
Password:
==== AUTHENTICATION COMPLETE ===
```
