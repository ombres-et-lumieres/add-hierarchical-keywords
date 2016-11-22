add-hierarchical_keywords
=====================


Version de base, 1.0.0

L' objet de ce plugin est de récupérer les éléments de base d' une photo mots clefs, auteur, description, libellé, titre, légende et les coller dans les champs adéquats des photos au moment de l' upload.

Il est conçu pour fonctionner avec le plugin Adobe XMP for WP. 
Comme il est destiné aux photographes au moins expérimentés, qui donc utilisent un catalogueur, le format XMP d' adobe m' a semblé être un choix judicieux parce que plus simple à lire et contenant un maximum d' information. Le plugin qui va récupérer ces infos ne les cherchent pas toutes mais les principales sont là.


A terme je voudrais créer un plugin permettant de gérer une vaste bibliothèque de photos comme celles utilisées, en terme de quantité d' images, quotidiennement par les photographes. Mon modèle est Lightroom. 
Etant un photographe avec quelques compétences dans le domaine de la programmation, je pense pouvoir mener ce projet à bien si j' ai de l' aide de développeurs wordpress chevronnés pour guider mes pas: ma plus grosse perte de temps étant de devoir trop souvent fouiller le codex pour trouver "la fonction" utile.

Du point de vue de mes capacités, si je suis parfaitement à l' aise avec une programmation strictement procédurale, je reste encore incapable d' écrire en orienté objet. Ce qui dans certains cas serait bien utile. Par contre, je n' ai pas trop de problèmes pour lire un code écrit de cette façon.
Lorsque j' ai appris à jouer avec ces drôles de machines, le PC n' existait pas encore et les programmes, qui ne portaient pas encore le nom de logiciels et encore moins de cms, internet n' existait pas, étaient strictement utilitaires. 

Je fais donc appel à tous ceux que ce projet intéresse pour m' aider à avancer dans sa construction.



La première étape est de placer les mots clefs dans une taxonomie propre nommée "hierarchical keywords", pour la différentier de celle, native, de wordpress.
Pour cela le plugin Adobe XMP for WP me fournit un objet  $adobeXMP et une méthode get_xmp( $attachment_id ) qui renvoit un tableau de données XMP.
 
La deuxième étape sera d' aller placer le reste des données dans les champs adéquats.

Et ce sera la fin de la partie facile.





L' écran d' administration des médias est devenu quelque chose de plus pratique au fil des ans, mais manque encore cruellement de fonctions de recherche sur les médias. Il faudra y ajouter au moins deux champs de tri via mots clefs, un champ de tri par date et un champ de tri par article, pour arriver à cerner au plus près les photos recherchées.

D' un autre côté, s' il permet de modifier les datas d' une photo, on est obligé de le faire une par une et non pas sur un ensemble: il peut être judicieux de modifier une légende, adaptée au niveau de son catalogueur parce qu' elle permet de se rapeller un minimum sur la photo, mais moins adaptée sur un site web. Et modifier ce champ photo après photo peut vite se révéler fastidieux lorsque cela dépasse la dizaine de photos. La même réflexion me vient au sujet des mots clefs. 




Vous avez donc ici l' ensemble de mes réflexions sur le sujet, et, surtout, un résumé de ma vision d' un vrai catalogueur dans l' écran des médias.






ce qui est utilisable: la taxonomie, l' ajout de métadonnées









 
 
 
 