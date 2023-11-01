# Systeme-de-Gestion-de-Scolarite
Réalisé avec Symfony 6 et MySQL.
C'est un projet qui gère la scolarité au sein d'une université. Il y a la gestion des candidatures, des étudiants admis et des paramètres au sein de l'établissement.
On a deux profils qui utilise l'application.
L'administrateur qui peut gérer les candidatures,les étudiants admis et les paramètres.
Il peut ajouter,modifier et supprimer les candidatures, il peut effectuer l'admission des candidats pour les transformer en étudiant 
en prenant en compte le nombre de places disponibles selon le paramètre.
L'administrateur saisit la moyenne par semestre de chaque étudiant. On peut obtenir alors le résultat par semestre pour tous les étudiants.
L'administrateur saisit le paiement de l'écolage par semestre de chaque étudiant. On peut en payer plusieurs fois, donc on a le montant déjà payé pour chaque semestre
associé avec le reste à payer selon le montant total de l'écolage du semestre dans le paramètre.
L'administrateur peut ajuster les paramètres de gestion comme le montant de l'écolage pour chaque semestre, le nombre de places disponibles pour accueillir les nouveaux étudiants
admis.
Pour l'autre utilisateur de l'application qui est l'étudiant, il peut voir sa fiche qui contient toutes ses informations ainsi que le montant de l'écolage qu'il a dejà payé
pour chaque semestre,le reste à payer. Il peut aussi voir ses résultats par semestre.
