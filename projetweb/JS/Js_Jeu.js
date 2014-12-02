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

function stateChanged()
{
    var json;
    if (XmlHttp.readyState == 4) {
        json = JSON.parse(XmlHttp.responseText);
        if (json.deco) {
            window.location.href = "./index.php";
        } else {
            alert("La deconnexion n'est pas possible !")
        }
    }
}

function newGame() {
    location.href = "./index.php?a=partie";
}

function joinGame(id) {
    location.href = "./index.php?a=partie&g=" + id;
}

var nbplayers;
function actualiserJoueurs(nbjoueurs) {
    nbplayers = nbjoueurs;
    XmlHttp = GetXmlHttpObject();
    var param = "action=actuJ&nbPlayers=" + nbjoueurs;
    if (XmlHttp == null) {
        alert("Objets HTTP non supportés");
    } else {
        XmlHttp.onreadystatechange = stateChangedActualiserJoueurs;
        XmlHttp.open("POST", "./Controller/ReponsesAjax.php", false);
        XmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        XmlHttp.send(param);
    }
}

function stateChangedActualiserJoueurs()
{
    var json;
    if (XmlHttp.readyState == 4) {
        json = XmlHttp.responseText;
        if (json != "") {
            json = JSON.parse(XmlHttp.responseText);
            if (json.nbJoueurs < 4) {
                window.document.getElementById("barre").innerHTML = json.code;
                nbplayers = json.nbJoueurs;
                setTimeout(function() {
                    actualiserJoueurs(nbplayers);
                }, 1000);
            } else {
                window.location.href = "./index.php?a=jeux";
            }
        } else {
            setTimeout(function() {
                actualiserJoueurs(nbplayers);
            }, 1000);
        }
    }
}

function versJeux() {
    location.href = "./index.php?a=arene";
}

function retour() {
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


///////////////////////////////////////////PARTIE YANNICK/////////////////////////////////////////



function hoverDiv(){
        $(this).css("border-color","Black");
        $(this).css("background-color","#F83400");
}
function leaveDiv(){
	$(this).css("border-color","#FFFFFF");
        $(this).css("background-color","");
}

function SendInfo()
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
    /*.done(function( data ) {
	my_data = data;
	$.each(my_data, function(key, val) {
            console.log(val); 
            var message = $//('<section class="theme" data-id="'+val.id+'"><img src="'+val.imageUri+'" alt="logo"><div class="resto"><p>'+val.nom+'</p><a href="#"> Voir la carte</a></div></section>');

            ('<div id="container_block"><div id="index_'+val.nom+'" class="blockIndex"><h2 class="title-blockIndex">'+val.nom+'</h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet sed lacus ac aliquam. Aliquam a consequat nulla. Mauris ullamcorper, sem sit amet aliquam convallis, dolor mauris dignissim urna, eu iaculis nibh odio sit amet ligula.</p><a href="pizza.php" class="btn_blockIndex plat">Voir les plats</a></div></div>');
            $("#container").append(message); 
            message.click(); 
        });
    });*/
}

$(document).ready( function(){
	$('p.Cperso').mouseover(hoverDiv);
        $('p.Cperso').mouseleave(leaveDiv);
	$('p.Cperso').click(SendInfo);
	//$('button#hide').click(switch1);
	//$('button#add').click(addvanish);
});

