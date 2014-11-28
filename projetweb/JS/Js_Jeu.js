
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
    //var json;
    if (XmlHttp.readyState == 4) {
        json = JSON.parse(XmlHttp.responseText);
        if (json.deco) {
            window.location.href = "http://localhost/PhpProject1/Double-projetweb/projetweb/index.php";
        } else {
            alert("La deconnexion n'est pas possible !")
        }
    }
}
