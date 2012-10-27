<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Galyleo - Gestión del Aprendizaje</title>


        <link href="../views/css/style_layout.css" rel="stylesheet" type="text/css" />
        <link href="../views/css/layout.css" rel="stylesheet" type="text/css" />
        <link href="../views/css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="../views/css/boxy.css" rel="stylesheet" type="text/css" media="screen" />


        <script src="../views/js/maxheight.js" type="text/javascript"></script>
        <script src="../views/js/jquery_1.7.1.js" type="text/javascript"></script>
        <script src="../views/js/modules/exporting.js"></script>
        <script src="../views/js/highcharts.js"></script>
        <script src="../views/js/jquery.boxy.js"></script>
        <link rel="shortcut icon" href="../views/images/icono.ico?1.0" type="image/x-icon">

            <script>
                function TipoPregSelect($tipopregunta,$valor){
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            document.getElementById('quizes_todos').innerHTML=xmlhttp.responseText;
                        }
                    };
                    xmlhttp.open("POST","quizesTodos?curso_id="+$valor+"&multianswer="+$tipopregunta,true);
                    xmlhttp.send();
                }            
            
                function Quizes($valor){
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            document.getElementById('tipoPreguntas').innerHTML=xmlhttp.responseText;
                        }
                    };
                    xmlhttp.open("POST","tipoPreguntas?curso_id="+$valor,true);
                    xmlhttp.send();
                } 

                //al precionar el boton se genera la matriz
                function BotonGenerarMatriz($idquiz,$multians){
                    //alert("BotonGeneraMatriz quiz: "+$idquiz+" multi: "+$multians+" text: "+$("#comboquizes option:selected").text());
                
                    var texto_comboquizes = $("#comboquizes option:selected").text();
                    var patron = /(D|d)iagn(o|O|ó|Ó)stico/;
                    var es_diagnostico = 0;
                    if(texto_comboquizes.match(patron))
                    {
                        //alert("es diagnostico");
                        es_diagnostico = 1;
                    }
                
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            document.getElementById('boton_generar').innerHTML=xmlhttp.responseText;
                        }
                    };
                    xmlhttp.open("POST","botonMatriz?quiz_id="+$idquiz+"&multianswer="+$multians+"&es_diagnostico="+es_diagnostico,true);
                    xmlhttp.send();
                } 

                function confirmarMatriz($valor,$multians,$es_diagnostico){
                    var xmlhttp1;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp1=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp1.onreadystatechange=function(){
                        if (xmlhttp1.readyState==4 && xmlhttp1.status==200){
                            var respuesta = xmlhttp1.responseText;
                            //alert("entro "+respuesta);
                            if(respuesta=="1" | respuesta=="4")
                            {
                                alert("La matriz ya existe y mostrara");
                                QuizSelect($valor,$multians,$es_diagnostico);
                            }else{
                                if(respuesta=="2" | respuesta=="5")
                                {
                                    var answer = confirm("Se encontro matriz de desempeño, pero con diferencias en el numero de preguntas en reportes y la plataforma, por lo cual se reiniciara la matriz, esta seguro de continuar? ")
                                    if (answer){
                                        QuizSelect($valor,$multians,$es_diagnostico);
                                    }
                                    else{
                                        alert("Pruebe seleccionando otro tipo de pregunta")
                                    }
                                }else{
                                    if(respuesta=="3" | respuesta=="6")
                                    {
                                        alert("No se encontro matriz de desempeño, en un momento se generara y debera completarla");
                                        QuizSelect($valor,$multians,$es_diagnostico);
                                    }else{

                                    }
                                }
                            }
                        }
                    };
                    $("#matriz").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp1.open("POST","validarMatrices?quiz_id="+$valor+"&multianswer="+$multians,true);
                    xmlhttp1.send();
                }
            
                function QuizSelect($valor,$multians,$es_diagnostico){
                    //alert($es_diagnostico);
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            //document.getElementById('preguntas_quiz_select').innerHTML=xmlhttp.responseText;
                            $("#matriz").html(xmlhttp.responseText);
                        }
                    };
                    $("#matriz").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp.open("POST","preguntasQuiz?quiz_id="+$valor+"&multianswer="+$multians+"&es_diagnostico="+$es_diagnostico,true);
                    xmlhttp.send();
                }  
                        
                function insertarFila($nmro_fila){
                    var idquiz = document.getElementById('id_quiz_'+$nmro_fila).value;
                    var numeropregunta = document.getElementById('numero_preg_'+$nmro_fila).value;
                    var contenido = document.getElementById('contenido_'+$nmro_fila).value;
                    var eje = document.getElementById('eje_'+$nmro_fila).value;
                    var link = document.getElementById('link_'+$nmro_fila).value;
                    link = link.replace("&","%26");
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            document.getElementById('resultado_insert').innerHTML=xmlhttp.responseText;
                        }
                    };
                    xmlhttp.open("POST","agregarContenido?idquiz="+idquiz+"&numeropregunta="+numeropregunta+"&contenido="+contenido+"&eje="+eje+"&link="+link,true);
                    xmlhttp.send();            
                    //alert("agregarContenido?idquiz="+idquiz+"&numeropregunta="+numeropregunta+"&contenido="+contenido+"&eje="+eje+"&link="+link);
                }
            
                function isUrl(s) {
                    var regexp = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
                    return regexp.test(s);
                }
            
                function actualizaFila($nmro_fila){
                    var idquiz = document.getElementById('id_quiz_'+$nmro_fila).value;
                    var numeropregunta = document.getElementById('numero_preg_'+$nmro_fila).value;
                    var contenido = document.getElementById('contenido_'+$nmro_fila).value;
                    var eje = document.getElementById('eje_'+$nmro_fila).value;
                    var link = document.getElementById('link_'+$nmro_fila).value;
                    link = link.replace("&","%26");
                    if(isUrl(link) | link==""){
                        var xmlhttp;
                        if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                            xmlhttp=new XMLHttpRequest();
                        }
                        else{// code for IE6, IE5
                            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        xmlhttp.onreadystatechange=function(){
                            if (xmlhttp.readyState==4 && xmlhttp.status==200){
                                document.getElementById('res_'+$nmro_fila).innerHTML=xmlhttp.responseText;
                            }
                        };
                        xmlhttp.open("POST","actualizaContenido?idquiz="+idquiz+"&numeropregunta="+numeropregunta+"&contenido="+contenido+"&eje="+eje+"&link="+link,true);
                        xmlhttp.send();            
                    }else
                    {
                        alert("Debe ingrear la URL del link de repaso completa.");
                    }
                }
                
                function actualizaFilaMatrizDiagnostico($nmro_fila){
                    var id_diagnostico = document.getElementById('id_diagnostico_'+$nmro_fila).value;
                    var numero_preg = document.getElementById('numero_preg_'+$nmro_fila).value;
                    var contenido = document.getElementById('contenido_'+$nmro_fila).value;
                    var eje = document.getElementById('eje_'+$nmro_fila).value;
                    var link = document.getElementById('link_'+$nmro_fila).value;
                    var id_quiz = document.getElementById('id_quiz_'+$nmro_fila).value;
                    link = link.replace("&","%26"); 
                    if(isUrl(link) | link==""){
                        var xmlhttp;
                        if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                            xmlhttp=new XMLHttpRequest();
                        }
                        else{// code for IE6, IE5
                            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        xmlhttp.onreadystatechange=function(){
                            if (xmlhttp.readyState==4 && xmlhttp.status==200){
                                document.getElementById('res_'+$nmro_fila).innerHTML=xmlhttp.responseText;
                            }
                        };
                        xmlhttp.open("POST","actualizaContenidoDiagnostico?id_diagnostico="+id_diagnostico+"&numero_preg="+numero_preg+"&contenido="+contenido+"&eje="+eje+"&link="+link+"&id_quiz="+id_quiz,true);
                        xmlhttp.send();            
                    }else
                    {
                        alert("Debe ingrear la URL del link de repaso completa.");
                    }
                }

                function cargaMenuContenido(){
                    //alert($es_diagnostico);
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            //document.getElementById('preguntas_quiz_select').innerHTML=xmlhttp.responseText;
                            $("#carga_contenido_site").html(xmlhttp.responseText);
                        }
                    };
                    $("#carga_contenido_site").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp.open("POST","agregarContenidoSite",true);
                    xmlhttp.send();
                }  
                
                function cargaMenuPerfil(){
                    //alert($es_diagnostico);
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            //document.getElementById('preguntas_quiz_select').innerHTML=xmlhttp.responseText;
                            $("#carga_contenido_site").html(xmlhttp.responseText);
                        }
                    };
                    $("#carga_contenido_site").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp.open("POST","formularioPerfil",true);
                    xmlhttp.send();
                }
                function insertaPerfil(){
                    var nombre_rol = document.getElementById('nombre_rol').value;
                    var nombre_usuario = document.getElementById('nombre_usuario').value;
                    var password = document.getElementById('password').value;
                    var nombres = document.getElementById('nombres').value;
                    var apellidos = document.getElementById('apellidos').value;
                    var nombre_institucion = document.getElementById('nombre_institucion').value;
                    var campo_institucion = document.getElementById('campo_institucion').value;
                    
                    //if(isUrl(link) | link==""){
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            $("#carga_contenido_site").html(xmlhttp.responseText);
                            //document.getElementById('resultado_insert').innerHTML=xmlhttp.responseText;
                        }
                    };
                    $("#carga_contenido_site").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp.open("POST","insertaPerfil?nombre_rol="+nombre_rol+"&nombre_usuario="+nombre_usuario+"&password="+password+"&nombres="+nombres+"&apellidos="+apellidos+"&nombre_institucion="+nombre_institucion+"&campo_institucion="+campo_institucion,true);
                    xmlhttp.send();            
                    //}else
                    // {
                    //    alert("Debe ingrear la URL del link de repaso completa.");
                    //}
                } 
                
                function listarPerfil(){
                    //alert($es_diagnostico);
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            //document.getElementById('preguntas_quiz_select').innerHTML=xmlhttp.responseText;
                            $("#carga_contenido_site").html(xmlhttp.responseText);
                        }
                    };
                    $("#carga_contenido_site").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp.open("POST","listarPerfiles",true);
                    xmlhttp.send();
                } 
                
                function modificarPerfil(id_otrorol){
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            //document.getElementById('preguntas_quiz_select').innerHTML=xmlhttp.responseText;
                            $("#camposModificar").html(xmlhttp.responseText);
                        }
                    };
                    $("#camposModificar").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp.open("POST","modificarPerfil?id_otroRol="+id_otrorol,true);
                    xmlhttp.send();
                }  
                
                function actualizarPerfil(id_otrorol){
                    var nombre_rol = document.getElementById('nombre_rol').value;
                    var nombre_usuario = document.getElementById('nombre_usuario').value;
                    var password = document.getElementById('password').value;
                    var nombres = document.getElementById('nombres').value;
                    var apellidos = document.getElementById('apellidos').value;
                    var nombre_institucion = document.getElementById('nombre_institucion').value;
                    var campo_institucion = document.getElementById('campo_institucion').value;               
                    
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            $("#camposModificar").html(xmlhttp.responseText);
                        }
                    };
                    $("#camposModificar").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp.open("POST","actualizaPerfil?nombre_rol="+nombre_rol+"&nombre_usuario="+nombre_usuario+"&password="+password+"&nombres="+nombres+"&apellidos="+apellidos+"&nombre_institucion="+nombre_institucion+"&campo_institucion="+campo_institucion+"&id_otroRol="+id_otrorol,true);
                    xmlhttp.send();            

                }
                function eliminarPerfil(id_otrorol){
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            //document.getElementById('preguntas_quiz_select').innerHTML=xmlhttp.responseText;
                            $("#camposModificar").html(xmlhttp.responseText);
                        }
                    };
                    $("#camposModificar").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp.open("POST","eliminarPerfil?id_otroRol="+id_otrorol,true);
                    xmlhttp.send();
                }                 

                
                function cargaMenuInstitucion(){
                    //alert($es_diagnostico);
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            //document.getElementById('preguntas_quiz_select').innerHTML=xmlhttp.responseText;
                            $("#carga_contenido_site").html(xmlhttp.responseText);
                        }
                    };
                    $("#carga_contenido_site").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp.open("POST","formularioInstitucion",true);
                    xmlhttp.send();
                }
                function insertaInstitucion(){
                    var nombre = document.getElementById('nombre').value;
                    var plataforma = document.getElementById('plataforma').value;
                    var imagen = document.getElementById('imagen').value;
                    var campo_institucion_inst = document.getElementById('campo_institucion_inst').value;
                
                    //if(isUrl(link) | link==""){
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            $("#carga_contenido_site").html(xmlhttp.responseText);
                            //document.getElementById('resultado_insert').innerHTML=xmlhttp.responseText;
                        }
                    };
                    $("#carga_contenido_site").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp.open("POST","insertaInstitucion?nombre="+nombre+"&plataforma="+plataforma+"&imagen="+imagen+"&campo_institucion_inst="+campo_institucion_inst,true);
                    xmlhttp.send();            
                    //}else
                    // {
                    //    alert("Debe ingrear la URL del link de repaso completa.");
                    //}
                }                 
                
            </script>
    </head>
    <body id="index">        
        <div id="header_tall">
            <div id="main">
                <!--header -->
                <div id="header">
                    <div class="h_logo">
                        <div class="left">
                            <a href="http://www.galyleo.net" target="_blank" ><img alt="Ir a Galyleo.net" src="../views/images/logos/galyleo.png" /><br /></a>
                        </div>
                        <div class="right" style="text-align: right;">
                            <h3>Bienvenido Administrador</h3>
                            <?php
                            echo strtoupper($usuario_admin->nombre) . " " . strtoupper($usuario_admin->apellido) . " ";
                            ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div id="menu">
                        <div class="rightbg">
                            <div class="leftbg">
                                <div class="padding">
                                    <ul>
                                        <!-- Cursos del Director -->
                                        <?php
                                        echo "<li class='last'><button class='boton_menu' onclick='cargaMenuContenido()'>Ingresar contenido</button></li>";
                                        echo "<li class='last'><button class='boton_menu' onclick='cargaMenuPerfil()'>Ingresar nuevo perfil</button></li>";
                                        echo "<li class='last'><button class='boton_menu' onclick='cargaMenuInstitucion()'>Ingresar instituciones</button></li>";
                                        //echo "<li class='last'><button class='boton_menu' onclick='cargaMenuInstitucion()'>Datos demo</button></li>";
                                        ?>
                                    </ul>
                                    <br class="clear" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="right" style="padding: 0;">
                        <div class="indent_column"></div>
                    </div>
                </div>
                <!--header end-->
                <br/>
                <div id="middle">
                    <div class="indent">
                        <div class="columns2">
                            <div class="ver_line">
                                <div id="carga_contenido_site" class="column1" style="width: 100%;"></div>
                                <div class="clear"></div>
                            </div>

                        </div>
                    </div>
                </div>
                <br></br>
                <br></br>
                <!--footer -->
                <div id="footer">
                    <div id="text_footer">
                        <img src="../views/images/icono.ico" width="15px" /> Galyleo - Tecnologías Educativas Colaborativas
                    </div>
                </div>

                </body>
                </html>