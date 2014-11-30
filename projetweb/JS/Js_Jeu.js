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
    location.href = "./index.php?a=jeux";
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