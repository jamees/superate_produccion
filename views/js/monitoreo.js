/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 */

//Pruebas para recargar ajax
var auto_refresh = setInterval(
    function()
    {
        $("#mostrarMonitoreo").fadeOut("slow").load("getIntentos").fadeIn("slow");
    }, 20000);

//Al iniciar cargar el monitoreo

$(document).ready(function(){
    //cargaLogo();
    cargarMonitoreo();
})


//Declaracion de funciones
function cargarMonitoreo(){
    var xmlhttp;
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else{// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            $("#mostrarMonitoreo").html(xmlhttp.responseText);
        }
    };
    $("#mostrarMonitoreo").html("<div align='center'><img src='../views/images/ajax-loading.gif'/></div>");
    xmlhttp.open("POST","getIntentos",true);
    xmlhttp.send();
} 