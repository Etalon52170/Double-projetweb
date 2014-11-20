
function validerConnexion() {
    var acc = document.getElementById("log").value;
    var pass = document.getElementById("pwd").value;
    XmlHttp = GetXmlHttpObject();
    if (XmlHttp == null) {
        alert("Objets HTTP non supportés");
    } else {
        
        XmlHttp.onreadystatechange = stateChanged;
        XmlHttp.open("GET", "vue.php", true);
        XmlHttp.send(null);
    }

}

function GetXmlHttpObject()
{ var xmlhttpreq=null;
 if(window.XMLHttpRequest)
 { xmlhttpreq = new XMLHttpRequest(); }
 else if(window.ActiveXObject)
 { xmlhttpreq = new ActiveXObject("Microsoft.XMLHTTP"); }
 return(xmlhttpreq);
}

function stateChanged()
{
    if (XmlHttp.readyState == 4)
        if (XmlHttp.responseText == false) {
            alert("tamere");
        } else {
            //window.location.href = "http://localhost/PhpProject1/Double-projetweb/projetweb/";
            alert("Alerte aux gogoles ! alerte aux gogoles les ")
        }
}
