# VOCABULLE

## Une application pour créer et partager des listes de vocabulaire personnalisées

Vocabulle permettra aux utilisateurs de créer ses propres listes de vocabulaire entre deux langues. Puis de s'entraîner avec des quizz (de différentes difficultés) sur ces listes, ainsi que de garder les scores de l'utilisateur sur chaque liste.

Un professeur pourrait ainsi créer une liste de vocabulaire concernant son cours et la partager à ses élèves.
Un étudiant pourrait créer une liste de vocabulaire sur le livre qu'il lit et s'entraîner dessus jusqu'à ce qu'il ait un score parfait à ses quizz.

## Fonctionnalités

**Utilisateurs** :

- Se connecter / S'enregistrer
- CRUD sur des listes de vocabulaire
- Système de favoris sur les listes de vocabulaire
- Noter les listes de vocabulaire
- Garde les meilleurs scores, c'est à dire un % de réussite sur chaque niveau de difficulté (à l'affichage des listes de vocabulaire on notera uniquement le meilleur score du niveau de difficulté le plus élevé)

**Listes de vocabulaire** :

- Statut public ou privé
- Note globale (sur 5 étoiles)
- Pouvoir choisir de quelle langue vers quelle langue on travaille
- Tri par date du dernier quizz, par note, par ordre alphabétique, public/privé, par score, par favori ou non
- Recherche par titre ou créateur (pour les listes publiques)

**Langues** :

- Gestion des langues où les majuscules sont importantes (exemple : l'Allemand)
- Gestion des caractères spéciaux, qui apparaitront ensuite sous le quizz pour être accessibles plus aisément. (exemples : ñ, ß ...)

**Quizz** :

- 3 niveaux de quizz : Facile (QCM), Moyen (on donne la première lettre du nom), Difficile (on ne donne plus rien)
- Score qui s'actualise en direct
- Algorithme qui reconnaît quand le mot est "presque" juste et propose alors une chance supplémentaire à l'utilisateur
- A la fin d'un quizz, possibilité de voir les erreurs commises et de refaire un quizz avec seulement les erreurs

## UML de classes

[Lien vers fichier draw.io](https://drive.google.com/file/d/120x8kbdiL4xEqbv1OLh-uDazwSMnCZ4c/view?usp=sharing)

## Maquettes figma du projet

[Lien figma vers les maquettes](https://www.figma.com/design/9cJeLelwdNiLxcp4IqOev0/Untitled?m=auto&t=GqfgZCk6xk5SCV6Q-1)
