
function validerConnexion() {
    var acc = document.getElementById("log").value;
    var pass = document.getElementById("pwd").value;
    XmlHttp = GetXmlHttpObject();
    if (XmlHttp == null) {
        alert("Objets HTTP non supportés");
    } else {
        XmlHttp.onreadystatechange = stateChanged;
        XmlHttp.open("GET", "./Controller/ReponsesAjax.php?pwd=" + pass + "&log=" + acc, false);
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
    //var json;
    if (XmlHttp.readyState == 4) {
        json = JSON.parse(XmlHttp.responseText);
        if (json.find == "ok") {
            window.location.href = "http://localhost/PhpProject1/Double-projetweb/projetweb/";
        } else {
            document.getElementById('mdpIncorrect').style.display = "block";
            setTimeout(function() { document.getElementById('mdpIncorrect').style.display = "none"; },3000);
        }
    }
}
