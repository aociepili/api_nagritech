#Admin

Login
{
"email":"esdras@gmail.com",
"password":"1234"
}

Create and Update
{
"nom": "DAKHINO",
"postnom": "AKEMANI",
"prenom": "Eric",
"sexe": "M",
"ville": "Goma",
"commune": "Goma",
"quartier": "Kyeshero",
"avenue": "Palais de Justice",
"email": "dakhino@gmail.com",
"telephone": "0975413269",
"password": "1234"
}


##Agent

Login
{
"email":"esdras@gmail.com",
"password":"1234"
}

Create and Update
{
"nom": "DAKHINO",
"postnom": "AKEMANI",
"prenom": "Eric",
"sexe": "M",
"ville": "Goma",
"commune": "Goma",
"quartier": "Kyeshero",
"avenue": "Palais de Justice",
"email": "dakhino@gmail.com",
"telephone": "0975413269",
"password": "1234"
}


##Client
0 Systeme

Login
{
"telephone":"0975413269",
"password":"1234"
}

Create and Update
{
"nom": "EBUMBE",
"postnom": "EPILI",
"prenom": "Elie",
"sexe": "M",
"ville": "Goma",
"commune": "Goma",
"quartier": "Himbi",
"avenue": "Du Musee",
"email": "ebumbe@gmail.com",
"telephone": "+243991234567",
"password": "1234",
"tranche_age_id":1
}

client Moral


##Commande_aliment
##Commande_biogaz
##Commande_oeuf
##Commande_poussin
Login
Create and Update avec variation de la nature

{
"statusCmd_id": 4,
"natures_idNature": 15,
"clients_idClient": 2,
"quantite": 250,
"montant":0
}

changeStatus
{
"statusCmd_id": 4,
"natures_idNature": 15,
"clients_idClient": 2,
"agents_idAgent": 2
}

##entree_aliment
##entree_biogaz
##entree_oeuf
##entree_poussin
Login
Create and Update avec variation de la nature
{
"designation_lot": "Bloc 2 ",
"natures_idNature": 6,
"quantite": 7,
"stock_Aliments_idStock": 4
}


##etat_rapport
##motif_sortie
{
"designation":"TEST"
}

## Fournisseur
create and Update
{
"nom": "DAKHINO",
"postnom": "AKEMANI",
"prenom": "Eric",
"sexe": "M",
"ville": "Goma",
"commune": "Goma",
"quartier": "Kyeshero",
"avenue": "Palais de Justice",
"email": "dakhino@gmail.com",
"telephone": "0975413269",
"password": "1234"
}


## Incubateur.create Oeuf
{
"quantite": 40,
"dateEntree": "2024-04-01 12:15:33",
"agents_idAgent": 2,
"natures_idNature": 5,
"status_rapport_id": 4
}

##Nature Idem

##Notification
create and Update
{
"titre": "Livraison des Poulet",
"description": "Bonjour cher client, nous effectuerons les Livraisons directement a votre restaurant faites nous signes
si vous serez empeches"
}

desactive

##rapport_aliment
##rapport_biogaz
##rapport_oeuf
##rapport_poussin
create
{
"quantite": "250",
"etat_rapportID": 1,
"commentaire": "A Refaire",
"agents_idAgent": 2,
"natures_idNature": 15
}

changeStatus PUT and id
{
"status_rapport_id": 4
}


##requete
Create
{

"question": "Avez-vous aussi de fromage ?",
"reponse": "Apana marahaba",
"destinateur": "All Customer",
"expediteur": "NagriTech"

}

##Reservation_CF idem
## reservation poulet sans date

##sortie_aliment
##sortie_biogaz
##sortie_oeuf
{
"natures_idNature": 15,
"motifSorties_idMotif": 5,
"agents_id": 1,
"quantite": 350,
"clients_id": 1
}


#Table Operation
#Table Status
{
"designation":"commande fournisseur"
}

#client Moral
{
"nom": "AOCI",
"nom_entreprise": "AEG entreprise",
"titre": "Directeur General",
"annee_existence": 3,
"sexe": "M",
"email": "aoci@aegInc.com",
"ville": "Goma",
"commune": "Goma",
"quartier": "Himbi ",
"avenue": "De la Justice",
"telephone": "+243840984758",
"password": "1234"
}

#nature



#fournisseur
{
"nom": "EBUMBE",
"postnom": "EPILI",
"prenom": "Elie",
"sexe": "M",
"pays": "RDC",
"ville": "Goma",
"commune": "Goma",
"quartier": "Himbi",
"avenue": "Musee",
"telephone": "0975413269",
"email": "elie@gmail.com",
"logo": "../public/img/fournisseur/Logo5960.jpg",
"cat_produit_id": 1
}

EntreeProduit
{
"designation_lot": "lot Elie 03042024",
"natures_idNature": 1,
"quantite": 900,
"fournisseur_id": 1
}