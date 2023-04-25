# Routes de l'application

| URL | Méthode HTTP | Contrôleur       | Méthode | Titre HTML           | Commentaire    |
| --- | ------------ | ---------------- | ------- | -------------------- | -------------- |
| `/` | `GET`        | `MainController` | `home`  | Bienvenue sur O'flix | Page d'accueil / Liste de tous les films|
| `/favoris` | `GET`        | `FavoritesController` | `favorites`  | Ma liste | Page des films/séries favoris d'un utilisateur |
| `/film/{id}` | `GET`        | `MainController` | `show`  | no title | Détails du film/série |
| `/films` | `GET`        | `MainController` | `list`  | Les genres - Les films et séries/Résultats de recherche | Résultats de la recherche avec affichage de la liste des films|
