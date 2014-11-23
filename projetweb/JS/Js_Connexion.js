
function validerConnexion() {

    var acc = document.getElementById("login").value;
    var pass = document.getElementById("password").value;
    XmlHttp = GetXmlHttpObject();
    if (XmlHttp == null) {
        alert("Objets HTTP non supportés");
    } else {
        XmlHttp.onreadystatechange = stateChanged;
        XmlHttp.open("GET", "./Controller/ReponsesAjax.php?action=conn&pwd=" + pass + "&log=" + acc, false);
        XmlHttp.send(null);
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
    if (XmlHttp.readyState == 4) {
        json = JSON.parse(XmlHttp.responseText);
        if (json.find == "ok") {
            window.location.href = "http://localhost/PhpProject1/Double-projetweb/projetweb/index.php?a=jeux";
        } else {
            document.getElementById('mdpIncorrect').style.display = "block";
            setTimeout(function() {
                document.getElementById('mdpIncorrect').style.display = "none";
            }, 3000);
        }
    }
}

function versInscription() {
    location.href = "http://localhost/PhpProject1/Double-projetweb/projetweb/index.php?a=inscri";
}

function retour() {
    location.href = "http://localhost/PhpProject1/Double-projetweb/projetweb/index.php";
}

function inscription() {
    var acc = document.getElementById("log").value;
    var pass = document.getElementById("pwd").value;
    var email = document.getElementById("email").value;
    if (acc == "" || pass == "" || email == "" || pass.length < 6) {
        document.getElementById('messageError').style.display = "block";
        setTimeout(function() {
            document.getElementById('messageError').style.display = "none";
        }, 6000);
    } else {
        document.getElementById('messageError').style.display = "none";
        document.getElementById('messageOK').style.display = "block";
        setTimeout(
                function() {
                    XmlHttp = GetXmlHttpObject();
                    if (XmlHttp == null) {
                        alert("Objets HTTP non supportés");
                    } else {
                        XmlHttp.onreadystatechange = stateChangedInscription;
                        XmlHttp.open("GET", "./Controller/ReponsesAjax.php?action=inscri&pwd=" + pass + "&log=" + acc + "&email=" + email, false);
                        XmlHttp.send(null);
                    }
                }, 2000);
    }
}

function stateChangedInscription()
{
    if (XmlHttp.readyState == 4) {
        json = JSON.parse(XmlHttp.responseText);
        if (json.inscri) {
            //avant d'appeller la méthode pour connecter l'utilisateur, je nouris les champs textes dont il a besoin !
            document.getElementById("login").value = document.getElementById("log").value;
            document.getElementById("password").value = document.getElementById("pwd").value;
            validerConnexion()
        } else {
            alert("L'inscription ne peut pas avoir lieu.");
        }
    }
}