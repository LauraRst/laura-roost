### Roost Laura | Projet Symfony Web | BACINFO 3 - 2020/2021

Projet : boutique en ligne d'un magasin de meubles / décorations
****

### Identifiants

**Compte utilisateur**

Login : user1

Mot de passe : user1

**Compte admin**

Login : admin

Mot de passe : admin
****
### Bundles 

- Breadcrumbs:https://packagist.org/packages/mhujer/breadcrumbs-bundle

- Easy Admin 3 
- Vich uploader
****

### **Fonctionnalités**

#### Front End

**Homepage**

- Affichage des produits les plus récents
- Affichage de tags en fonction du type (Pièce)
- Affichage des produits favoris (= les + ajoutés dans la liste d'envie des utilsateurs )
- Affichage des derniers articles 

**Produits**
- Plusieurs images possibles pour un produit
- Navigation par catégories ou par tag (Un produit peut avoir plusieurs tags)
- Produits en promotion
- Moyenne des notes du produit  




**Utilisateurs**

Produits : 
- Ajouter ou enlever un produit à sa liste d'envie
- Ajout au panier + possible d'ajouter en plus depuis la page produit (utilisateurs inscrits)
- Ajouter un commentaire et une note (utilisateurs inscrits)

Compte : 
- Visualiser ses commandes + le détail 
- Modifier ses informations (identifiants, profil,...)
- Consulter sa liste d'envie

Simulation commande : 
- modifier les produits de son panier / vider son panier
- voir le prix selon la quantité
- calcul du montant total de la commande 
- passe la commande "En cours de préparation"

****
#### Back End

**Admin** 

- Création d'un Dashboard pour le ROLE ADMIN
- Crud Users
- Crud produits : Upload de plusieurs image slug automatique
- Crud catégories et tags : slug automatique 
- Order : Consulter et Modifier le statut des commandes
- Avis : Consulter et Supprimer les avis
- Crud blog
- Event subscriber pour produits et post (ajout de l'utilisateur et update date lors d'ajout/modification)

