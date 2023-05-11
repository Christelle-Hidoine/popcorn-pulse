# E02 : création du projet
## création du projet symfony avec composer
composer create-project symfony/skeleton:"^5.4" my_project_directory
## déplacement des fichiers à la racine
mv ./my_project_directory/* ./my_project_directory/.* .
## suppression du dossier temporaire
rmdir ./my_project_directory
## pour pouvoir utiliser @Route()
composer require annotations
## TWIG !
composer require twig

# E03
## pour faire des liens correct dans twig vers nos assets
composer require symfony/asset
## vive le debug : la barre de debug avec le backoffice de debug : profiler
composer require --dev symfony/profiler-pack
## permet au dump de ne plus être dans la page, mais dans le WebDebugToolbar
composer require --dev symfony/debug-bundle
## le maker
composer require --dev symfony/maker-bundle

# E06
# création des fixtures
composer require orm-fixtures --dev

# E08
# faker général
composer require fakerphp/faker

# E09
# faker image
composer require --dev bluemmb/faker-picsum-photos-provider ^2.0

# E10
# formulaire et token csrf
composer require form validator
composer require security-csrf 

# E12
# sécurité
composer require symfony/security-bundle

# E05
## installation de tout les composants pour Doctrine
## A METTRE EN DERNIER car il nous pose une question à laquelle on répond 'x'
echo "x" | composer require symfony/orm-pack