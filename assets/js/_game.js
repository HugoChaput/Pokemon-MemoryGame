//Changer la forme de la grille selon le nombre de colonnes
changeGridTemplateColumns ();

//const group = document.querySelector('.box');-------------------------
document.getElementById('pokemonGrid').addEventListener('click', handleClick, false);
//-------------------- Vérifier qu'on clique au bon endroit (préciser un id au addEventListener? mettre un if dans handleClick() ?) ----------------

let clickNumber=0; //Nombre de clics effectués (1er ou 2eme)
let pairsFoundNumber=0; //Nombre de paires trouvées
let pairsNumber=(document.getElementById("pokemonGrid").childElementCount)/2; //Le nombre total de paires
let firstBoxId; //Pour stocker l'id de la 1ere case cliquée
let dataToSend=[];
var fetchResponse;
var fetchResult;

async function handleClick(e) {
    // Deconstruct the target property and grab the element's dataset, parentNode, and tagName
    let { dataset, id } = e.target; //const ?
    
    clickNumber++; //Incrémentation du nombre de clics

    //Stockage de la position de la case cliquée et du nombre de clics effectués
    dataToSend = {
        "boxClickedPositionX" : dataset.x,
        "boxClickedPositionY" : dataset.y,
        "clickNumber" : clickNumber
    };

    //Préparation et envoi du fetch, pour envoyer la position de la case cliquée et si c'est le 1ere ou 2eme cliquée.
    //Et réception de l'adresse de l'image du pokemon à afficher sur la case cliquée et si les 2 cases sont identiques lors d'un 2eme clic.
    let options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify(dataToSend)
    }

    //Envoie une requête et attend la réponse
    fetchResponse = await fetch('/frontBackExchange', options);
    //Transforme la réponse (json) en array
    fetchResult = await fetchResponse.json();

    //Changement de l'image de la case cliquée
    //document.getElementById(id).style.backgroundImage="url(\""+fetchResult.image+"\")";//-------------- A VOIR
    document.getElementById(id).innerHTML="<img src=\""+fetchResult.image+"\" width=\"150px\" height=\"150px\">";

    if (clickNumber==1)
    {
        firstBoxId=id;
    }
    //Si c'est le 2eme clic alors on vérifie si on a une paire
    else if (clickNumber==2)
    {
        clickNumber=0; //Réinitialisation du nombre de clics

        //Si les 2 cases sont identiques
        if (fetchResult.identicalBoxes)//---------------------------- Voir s'il faut tout récupérer dans le .then
        {
            //Incrémentation du nombre de paires trouvées
            pairsFoundNumber++;

            //Si toutes les paires ont été trouvées
            if(pairsFoundNumber==pairsNumber)
            {
                document.getElementById("finPartie").style.display = "block";
                document.getElementById("finPartie").innerHTML="Score : "+fetchResult.score+"<br><br><a href=\"/user-profile\">Profil Utilisateur</a>";
            }
        }
        else
        {
            //Attendre quelques secondes avant de cacher les images
            setTimeout(function(){
                //Cacher à nouveau les images des cases cliquées
                document.getElementById(firstBoxId).innerHTML="";
                document.getElementById(id).innerHTML="";
            }, 3000);
        }
    }
}


//Changer la forme de la grille selon le nombre de colonnes
function changeGridTemplateColumns ()
{
    //Récupère l'élément root
    var r = document.querySelector(':root');

    //Change la forme de la grille en fonction du nombre de colonnes
    document.addEventListener('DOMContentLoaded', function() {
        //Récupère le nombre de colonnes
        var gridColumns = document.querySelector('.gridColumns');
        var columnsNumber = gridColumns.dataset.columnsNumber;

        //Crée la nouvelle valeur de grid-template-columns du CSS
        var gridTemplateColumnsValue='';
        for (i=0 ; i<columnsNumber ; i++)
        {
            gridTemplateColumnsValue += 'auto ';
        }

        //Affecte la nouvelle valeur de grid-template-columns du CSS
        r.style.setProperty('--numberGridColumns', gridTemplateColumnsValue);
    });
}

