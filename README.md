add-hierarchical_keywords
=====================


Version de base, 1.0.0

L' objet de ce plugin est de rendre la gestion de la bibliothèque plus simple pour les photographes qui ont des catalogues qui se comptent en milliers d' images.

Cela se fera à plusieurs niveaux: 

_  La partie la plus importante est la création d' une taxonomie qui récupèrera les mots clefs hiérarchiques pour faciliter la gestion tant dans la back-end que pour le visiteur du site. Dans le back-end, en implantant les outils qui ont fait de Lightroom un incontournable en ce qui concerne l' art du catalogage: la capacité de filtrer par dates et mots clefs successifs.
A cela j' ajouterai un template de page permettant d' afficher l' archive de la taxonomie. 


_  A cela, j' ai ajouté des fonctions qui modifient le comportement de la galerie native pour l' afficher de manière plus seo friendly, mais également en donnant à l' utilisateur la possibilité de choisir son affichage: hauteur égale, en ligne, ou surface égale via l' affichage Masonry. Ici cela fonctionnera une fois que j' aurai la page d' option adéquate.
Une autre page d' option donnera à l' utilisateur une série de choix en ce qui concerne les taillles d' affichage des imagettes, que ce soit dans les galeries ou pour les post thumbnails.

_  La réorganisation des champs de l' écran d' un média. Actuellement les données ne sont pas correctement mises en mémoire: 
   Si le titre du média est bien celui figurant dans les datas de celui-ci, ce n' est pas la bonne méthode parce que, et je ne pense pas être le seul, lors de l' archivage d' une série, je lui donne un titre générique, ce qui va rendre problématique une recherche via le titre de la photo. Donc, je propose de remplacer ce titre par le nom du fichier qui, lui, est unique, ce qui, du point de vue informatique est nettement plus normal.
  La légende s' affiche au bon endroit, mais d' un point de vue de wordpress, comme c' est le texte principal, c' est dans le champ "description" qu' il devrait se placer. dans ce champ, je placerais volontiers le titre.
  Le texte alternatif est vide et je le laisserai ainsi parce que je le construirai de manière dynamique, à la volée, dans l' affichage des photos.
  Il rest un champ de base non utilisé parce qu' il n' existe pas dans wordpress: le Libellé. Je créerai donc un champ supplémentaire pour l' afficher, et tant que j' y suis j' ajouterai dans cette zone un champ pour l' auteur, un autre pour les crédits, un pour le pays, un pour la ville, un pour la ville, un pour le lieu, un pour la date de création, un pour la date de modification, un pour le genre photographique et un pour les coordonnées GPS.
  Et, tant que je suis occupé à réorganiser les données liées à la photo, la date du post ne sera pus la date de l' upload, mais la date de prise de vue, ce qui me semble plus logique pour du tri.

 
 
 Je n' ai pas l' intention de m' arrêter en si bon chemin, donc toute idée sera la bienvenue.
 
 
 
 
 Comme toute collaboration: je ne suis pas un développeur à plein temps, donc je n' ai pas nécessairement les connaissances pour tout mener à bien et il y a des choses que je fais pour la première fois. Ceci pour dire que toute aide sera la bienvenue: ce sera un gain de temps précieux.
 Si vous avez besoin de photos bien archvées, je peux vous en envoyer une série.
 
 
 Collaborations:
 Etienne Mommalier pour le script organisant les mots clefs
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 Ce qui est déjà réalisé et fonctionne: l' ajout de la taxonomie, avec un léger bug sur certains mots clefs et l' ajout des métas
 
 
 Pour fonctionner correctement, le plugin JSM's Adobe XMP / IPTC for WordPress doit être installé et activé.
 (https://wordpress.org/plugins/adobe-xmp-for-wp/)
 
 De plus, parce que je n' ai pas encore écrit le code pour ajouter la colonne des mots clefs dans l' admin des médias, ni ajouté le déroulant de ces derniers, le plugin Enhanced Media Library est nécessaire
 (https://wordpress.org/plugins/enhanced-media-library/)
 
 