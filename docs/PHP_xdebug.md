# PHP xDebug

## keske C ?

Pendant le développement, on aime bien savoir ce qui se passe dans notre code, à un moment précis.
Qu'est ce qu'il y a dans tel variable ?
Pour cela on fait des `dump()` ou des `dd()`.

Mais il existe une autre façon de faire : le debug en direct.

vous avez pour cela sur vos VSCode une extension : PHP Debug.

## Comment ça s'utilise ?

Sur la barre de raccourcis, une icône avec un 'bug' et un triangle 'play'
Si vous ne l'avez pas, faites click doirt sur la barre, et cochez 'executer et deboger'

Nous allons d'abord la personaliser en cliquand sur le lien 'créer un fichier launch.json'

On sélectionne PHP, et cela génère un fichier de paramétrage JSON

Dedans nous avons plusieurs configurations, on va adapter une configuration à notre sauce.

```js
"name": "Launch Built-in web server",
"type": "php",
"request": "launch",
"runtimeArgs": [
    "-dxdebug.mode=debug",
    "-dxdebug.start_with_request=yes",
    "-S",
    "0.0.0.0:8000",
    "-t",
    "public"
],
```

on modifie la partie runtime pour ajouter le bon port, et le paramètre `-t public`

ensuite on le lance en utilisant le bouton en haut de la fenêtre en sélectionnant `Launch Built-in web server`

Cela lance le serveur PHP comme d'habitude, sur le port 8000 :tada:

## keske ça fait de plus ?

On a maintenant la possibilité de 'poser' des points d'arrêts sur des lignes de notre code.
un point d'arrêt fera pause dans l'éxecution de notre code, ce qui nous permet de voir le contenu des variables en direct live.

TODO : expliquer les espions

## annexes : errors

### Symfony\Component\DependencyInjection\Exception\EnvNotFoundException: Environment variable not found: "APP_RUNTIME_ENV"

je lance le serveur j'ai cette erreur