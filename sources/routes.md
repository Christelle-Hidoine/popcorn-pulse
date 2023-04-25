# Routes de l'application

| URL | Méthode HTTP | Contrôleur       | Méthode | Titre HTML           | Commentaire    |
| --- | ------------ | ---------------- | ------- | -------------------- | -------------- |
| `/` | `GET`        | `MainController` | `home`  | Bienvenue sur O'flix | Page d'accueil |
| `/favorites` | `GET`        | `MainController` | `favorites`  | Ma liste | Page des films/séries favoris d'un utilisateur |
| `/movies/{id}` | `GET`        | `MainController` | `show`  | no title | Page du film/série |
| `/movies` | `GET`        | `MainController` | `list`  | Les genres - Les films et séries/Résultats de recherche | Liste des films ou résultats de la recherche |
