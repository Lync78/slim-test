###

Petit projet juste pour une monté en compétence sur le mini framework slim:

Création de deux routes API une sur GET et l'autre en POST

J'ai utilisé Mysql et ubuntu pour réaliser ce projet et php 8.
J'ai créé un middleware sur la création de la base de donnée et de la table avant la déclaration des routes.

J'ai créé 4 routes :

- [GET] /api/test
- [POST] /api/register
- [POST] /api/login
- [POST] /api/logout


On peut mettre un middleware qui vérifie si le token est valide et que l'utilisateur dispose les droits necessaire frâce au payload.

On peut rajouter quelques routes :

[GET] /api/profile (permet de voir les données de l'utilisateur) <br>
[PATCH] /api/profile (permet de modifier les données de l'utilisateur) <br>
[DELETE] /api/user/:id (permet de supprimer un user a partir d'un utilisateur) <br>
[PUT] /api/profile (si on remplace tout les champs) <br>

## Docker

- lancer la commande : ``docker compose --env-file .env.docker up -d --build``

## Documentation API

- Pour consulter la docs des API http://localhost:8000/docs


