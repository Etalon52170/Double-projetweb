function GetXmlHttpObject()
{
    var xmlhttpreq = null;
    if (window.XMLHttpRequest)
    {
        xmlhttpreq = new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
        xmlhttpreq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    return(xmlhttpreq);
}

//////////////////////////////////////////Deconnection////////////////////////////////

function deconnection() {
    XmlHttp = GetXmlHttpObject();
    if (XmlHttp == null) {
        alert("Objets HTTP non supportés");
    } else {
        XmlHttp.onreadystatechange = stateChanged;
        XmlHttp.open("POST", "./Controller/ReponsesAjax.php", false);
        XmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        XmlHttp.send("action=deconn");
    }

}

function stateChanged()
{
    var json;
    if (XmlHttp.readyState == 4) {
        json = JSON.parse(XmlHttp.responseText);
        if (json != '') {
            window.location.href = "./index.php";
        } else {
            alert("La deconnexion n'est pas possible !")
        }
    }
}
//////////////////////////////////////////Fin - Deconnection////////////////////////////////

function newGame() {
    location.href = "./index.php?a=partie";
}

function joinGame(id) {
    location.href = "./index.php?a=partie&g=" + id;
}

//////////////////////////////////////////Actualiser la barre de chargement des joueurs////////////////////////////////

var nbplayers;
var request;
function actualiserJoueurs(nbjoueurs) {
    nbplayers = nbjoueurs;
    request = GetXmlHttpObject();
    var param = "action=actuJ&nbPlayers=" + nbjoueurs;
    if (request == null) {
        alert("Objets HTTP non supportés");
    } else {
        request.onreadystatechange = stateChangedActualiserJoueurs;
        request.open("POST", "./Controller/ReponsesAjax.php", true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(param);
    }
}

function stateChangedActualiserJoueurs()
{
    var json;
    if (request.readyState == 4) {
        json = JSON.parse(request.responseText);
        if (json.code != "relance") {
            if (json.nbJoueurs < 4) {
                window.document.getElementById("barre").innerHTML = json.code;
                nbplayers = json.nbJoueurs;
                setTimeout(function() {
                    actualiserJoueurs(nbplayers);
                }, 1000);
            } else {
                window.location.href = "./index.php?a=arene";
            }
        } else {
            actualiserJoueurs(nbplayers);
        }
    }
}
//////////////////////////////////////////Fin - actualiser barre joueurs////////////////////////////////

function versJeux() {
    location.href = "./index.php?a=arene";
}
//////////////////////////////////////////Retour////////////////////////////////

function retour() {
    request.abort();
    XmlHttp = GetXmlHttpObject();
    var param = "action=decPlay";
    if (XmlHttp == null) {
        alert("Objets HTTP non supportés");
    } else {
        XmlHttp.onreadystatechange = stateChangedDecrementerJoueurs;
        XmlHttp.open("POST", "./Controller/ReponsesAjax.php", false);
        XmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        XmlHttp.send(param);
    }
}

function stateChangedDecrementerJoueurs()
{
    var json;
    if (XmlHttp.readyState == 4) {
        json = JSON.parse(XmlHttp.responseText);
        if (json.decrementer == "ok") {
            location.href = "./index.php?a=jeux";
        }
    }
}

//////////////////////////////////////////Fin - Retour////////////////////////////////

//////////////////////////////////////////Retour du Classement////////////////////////////////

function retourLobby() {
    XmlHttp = GetXmlHttpObject();
    var param = "action=retLob";
    if (XmlHttp == null) {
        alert("Objets HTTP non supportés");
    } else {
        XmlHttp.onreadystatechange = stateChangedRetourLobby;
        XmlHttp.open("POST", "./Controller/ReponsesAjax.php", false);
        XmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        XmlHttp.send(param);
    }
}

function stateChangedRetourLobby()
{
    var json;
    if (XmlHttp.readyState == 4) {
        json = JSON.parse(XmlHttp.responseText);
        if (json.retour == "ok") {
            location.href = "./index.php?a=jeux";
        }
    }
}
//////////////////////////////////////////Fin - Retour du Classement////////////////////////////////

//////////////////////////////////////////Envoi du Symbol////////////////////////////////

var value_symbol;
function SendInfo() {
    value_symbol = $(this).attr('id');
    var param = "action=checkSym&id_symbol=" + value_symbol;
    XmlHttp = GetXmlHttpObject();
    if (XmlHttp == null) {
        alert("Objets HTTP non supportés");
    } else {
        XmlHttp.onreadystatechange = stateChangedCheckSymbol;
        XmlHttp.open("POST", "./Controller/ReponsesAjax.php", true);
        XmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        XmlHttp.send(param);
    }

}

function stateChangedCheckSymbol() {
    var json;
    if (XmlHttp.readyState == 4) {
        json = JSON.parse(XmlHttp.responseText);
        if (json.code == "bad") {
            document.getElementById(value_symbol).style.borderColor = "red";
        } else {
            if (json.end != 'ok') {
                if (json.code != "relance") {
                    //mettre un truc comme quoi tu as gagné
                    var audioElement = document.createElement('audio');
                    audioElement.setAttribute('src', 'Monster.mp3');
                    $.get();
                    audioElement.addEventListener("load", function() {
                        audioElement.play();
                    }, true);
                    audioElement.play();
                    window.document.getElementById("ActuJeu").innerHTML = json.code;
                    actualiserJeu(json.new_ind);
                    ajouteJS();
                    //ajouter un "+" lorsqu'une personne marque un point à coté de son nombre de points
                    document.getElementById('plus').style.color = "#00FF00";
                    setTimeout(function() {
                        document.getElementById('plus').style.color = "#FFFFFF";
                    }, 2000);
                }else{
                    actualiserJeu(index);
                }
            }
        }
    }
}
//////////////////////////////////////////Fin - Retour////////////////////////////////

//////////////////////////////////////////Actualiser le Jeu////////////////////////////////

var index;
var requete;
function actualiserJeu(index_courant) {
    index = index_courant;
    var param = "action=actuJeu&ind=" + index;
    requete = GetXmlHttpObject();
    if (requete == null) {
        alert("Objets HTTP non supportés");
    } else {
        requete.onreadystatechange = stateChangedActualiserJeu;
        requete.open("POST", "./Controller/ReponsesAjax.php", true);
        requete.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        requete.send(param);
    }
}

function stateChangedActualiserJeu() {
    var json;
    if (requete.readyState == 4) {
        json = JSON.parse(requete.responseText);
        if (json.code != "relance") {
            if (json.end == 'ok') {
                //une fois la partie finie, on stop la requete ajax pour raffraichir la vue
                //meme si il est impossible qu'un joueur recréé une partie avec le meme id
                //mais on ne sait jamais !!!
                requete.abort();
                window.document.getElementById("ActuJeu").innerHTML = json.code;
            } else {
                window.document.getElementById("ActuJeu").innerHTML = json.code;
                actualiserJeu(json.new_ind);
                ajouteJS();
            }
        }else{
            actualiserJeu(index);
                ajouteJS();
        }
    }
}
/*function stateChangedActualiserJeu() {
 var json;
 if (requete.readyState == 4) {
 json = JSON.parse(requete.responseText);
 if (json.code != "relance") {
 //mettre un truc comme quoi tu as gagné
 window.document.getElementById("ActuJeu").innerHTML = json.code;
 actualiserJeu(json.new_ind);
 ajouteJS();
 } else {
 //mettre un truc comme quoi tu as perdu
 actualiserJeu(index);
 }
 }
 }*/

var request;
function actualiserLooby() {
    request = GetXmlHttpObject();
    var param = "action=actuL";
    if (request == null) {
        alert("Objets HTTP non supportés");
    } else {
        request.onreadystatechange = stateChangedActualiserLooby;
        request.open("POST", "./Controller/ReponsesAjax.php", true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(param);
    }
}

function stateChangedActualiserLooby()
{
    var json;
    if (request.readyState == 4) {
            window.document.getElementById("bs-example").innerHTML = JSON.parse(request.responseText);
            setTimeout(function() {actualiserLooby();}, 1000);
    }
}
//////////////////////////////////////////Fin - Actualiser le Jeu////////////////////////////////

function ajouteJS() {
    $('p.Cperso').mouseover(hoverDiv);
    $('p.Cperso').mouseleave(leaveDiv);
    $('p.Cperso').click(SendInfo);
    //$('button#hide').click(switch1);
    //$('button#add').click(addvanish);
}


function hoverDiv() {
    $(this).css("border-color", "Black");
    $(this).css("background-color", "#F83400");
}
function leaveDiv() {
    $(this).css("border-color", "#FFFFFF");
    $(this).css("background-color", "");
}

/*function SendInfo()
 {
 var value = $(this).attr('id'); // récupére l'attribue de l'icone séléctionner
 console.log(value);
 var my_data;
 $.ajax({
 url: "resto.php",
 type: "GET",
 data: { a : "value"},
 
 })
 // console.log(data); ne fonctionne pas ici
 .done(function( data ) {
 my_data = data;
 $.each(my_data, function(key, val) {
 console.log(val); 
 var message = $//('<section class="theme" data-id="'+val.id+'"><img src="'+val.imageUri+'" alt="logo"><div class="resto"><p>'+val.nom+'</p><a href="#"> Voir la carte</a></div></section>');
 
 ('<div id="container_block"><div id="index_'+val.nom+'" class="blockIndex"><h2 class="title-blockIndex">'+val.nom+'</h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet sed lacus ac aliquam. Aliquam a consequat nulla. Mauris ullamcorper, sem sit amet aliquam convallis, dolor mauris dignissim urna, eu iaculis nibh odio sit amet ligula.</p><a href="pizza.php" class="btn_blockIndex plat">Voir les plats</a></div></div>');
 $("#container").append(message); 
 message.click(); 
 });
 });
 }*/

$(document).ready(function() {
    $('p.Cperso').mouseover(hoverDiv);
    $('p.Cperso').mouseleave(leaveDiv);
    $('p.Cperso').click(SendInfo);
    /*
     
     $.get();
     
     audioElement.addEventListener("load", function() {
     audioElement.play();
     }, true);
     
     $('.play').click(function() {
     audioElement.play();
     });
     
     $('.pause').click(function() {
     audioElement.pause();
     });*/
    //$('button#hide').click(switch1);
    //$('button#add').click(addvanish);
}

);

