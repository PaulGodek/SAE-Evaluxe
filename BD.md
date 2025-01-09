# sae3a-base

Dépôt de base de notre SAE3A.


Nom de notre BD MySQL: SAE3A_Q1E


Toutes nos fonctions SQL que nous effectuons sont dans nos Repository. Nous avons privilégié ça plutôt que d'écrire des procédures dans la Base De Donnée, à cause du manque de temps que nous avions sur la fin par manque d'organisations. Cependant, nous avons malgré tout effectué beaucoup de requêtes différentes. Déjà, à la manière de procédures stockées, nous avons généraliser beaucoup de requêtes, que ce soit dans notre AbstractRepository ou dans les autres Repository eux même (mettreAJour, ajouter, supprimer par exemple pour AbstractRepository, triEtudiant et les autres tris (recupererEtudiantsOrdonneParNom, Prenom, Parcours) par exemple pour EtudantRepository). Nous avons aussi fait attention à ne pas utiliser de requêtes imbriquées car nous savons que ce n'est pas optimisé, et nous avons préféré les joins. Mais comme nos requêtes restaient plutôt simples, nous n'avons jamais eu besoin de faire des requêtes très compliquées. Et pour remplacer nos triggers, nous avons simplement fait en sorte que dans les Repository où nous faisons des update, des insert etc, nous faisions ensuite manuellement les insert ou update etc adaptés dans les tables concernées.