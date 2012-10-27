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
                $(document).ready(function(){
                   // cargaLogo();
                    clickCurso(<?php echo ($_SESSION['curso_seleccionado']); ?>);
                
                })
    
            </script>
            <script>
                function clickCurso($curso_seleccionado){
                    
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                    
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            obtenerGrupos($curso_seleccionado);
                            obtenerEvaluaciones($curso_seleccionado);
                            var config = xmlhttp.responseText.split(",");
                            clickEvaluacion(config[0],$curso_seleccionado,config[1],<?php echo ($_SESSION['id_usuario_profesor']); ?>);
                            graficoAdopcion($curso_seleccionado, <?php echo ($_SESSION['id_usuario_profesor']); ?>);
                        }
                    };
                    xmlhttp.open("POST","cambiarCursoAjax?curso_seleccionado="+$curso_seleccionado,true);
                    xmlhttp.send();
                }
            
                function obtenerGrupos($curso_seleccionado){
                    
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                    
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            $("#gruposusuario_profesor").html(xmlhttp.responseText);
                        }
                    };
                    $("#gruposusuario_profesor").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp.open("POST","obtenerGruposAjax?curso_seleccionado="+$curso_seleccionado+"&id_usuario_profesor=<?php echo ($_SESSION['id_usuario_profesor']); ?>",true);
                    xmlhttp.send();
                }
            
                function obtenerEvaluaciones($curso_seleccionado){
                    var xmlhttp2;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp2=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp2.onreadystatechange=function(){
                    
                        if (xmlhttp2.readyState==4 && xmlhttp2.status==200){
                            $("#evaluacionesCursousuario_profesor").html(xmlhttp2.responseText);
                        }
                    };
                    $("#evaluacionesCursousuario_profesor").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp2.open("POST","obtenerEvaluacionesAjax?curso_seleccionado="+$curso_seleccionado+"&id_usuario_profesor=<?php echo ($_SESSION['id_usuario_profesor']); ?>&id_grupo=<?php echo ($_SESSION['grupo_seleccionado']); ?>",true);
                    xmlhttp2.send();
                
                }
                
                function clickEvaluacion($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor){
                    var xmlhttpDiagnostico;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttpDiagnostico=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                    }
                    xmlhttpDiagnostico.onreadystatechange=function(){
                    
                        if (xmlhttpDiagnostico.readyState==4 && xmlhttpDiagnostico.status==200){
                            //alert(xmlhttpDiagnostico.responseText);
                            if(xmlhttpDiagnostico.responseText == 0)
                            {
                                graficoRanking($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor);
                                matrizDesempeno($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor);
                                tuDesempeno($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor);
                                listaNotasAlumnos($idEvaluacion, $idCurso, $idGrupo);
                            }else{
                                if(xmlhttpDiagnostico.responseText == 1){
                                    //alert("Es diagnostico >>"+xmlhttpDiagnostico.responseText);
                                    graficoRanking($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor);
                                    matrizDesempenoDiagnostico($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor);
                                    tuDesempeno($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor);
                                    listaNotasAlumnos($idEvaluacion, $idCurso, $idGrupo);
                                }
                            }
                        }
                    };
                    xmlhttpDiagnostico.open("POST","esDiagnostico?id_evaluacion="+$idEvaluacion,true);
                    xmlhttpDiagnostico.send();
                }
                
                //                function clickEvaluacion($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor){
                //                    graficoRanking($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor);
                //                    matrizDesempeno($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor);
                //                    tuDesempeno($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor);
                //                    listaNotasAlumnos($idEvaluacion, $idCurso, $idGrupo);
                //                }
                
                
                
                
                
                
                function listaNotasAlumnos($idEvaluacion, $idCurso, $idGrupo){
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                    
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            //alert("idevaluacion "+$idEvaluacion+" idcurso "+$idCurso+" idgrupo "+$idGrupo);
                            $("#listaNotas").html(xmlhttp.responseText);
                            
                            //document.getElementById(resultadosAjax).innerHTML=xmlhttp.responseText;
                          
                        }
                    };
                    $("#listaNotas").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp.open("POST","generarListaNotas?id_evaluacion="+$idEvaluacion+"&id_curso="+$idCurso+"&id_grupo="+$idGrupo,true);
                    xmlhttp.send();
                }                
                
                function graficoRanking($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor){
                    var xmlhttpGrafico;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttpGrafico=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttpGrafico=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttpGrafico.onreadystatechange=function(){
                    
                        if (xmlhttpGrafico.readyState==4 && xmlhttpGrafico.status==200){
                            //alert("idevaluacion "+$idEvaluacion+" idcurso "+$idCurso+" idgrupo "+$idGrupo);
                            $("#script_grafico").html(xmlhttpGrafico.responseText);
                            //document.getElementById(resultadosAjax).innerHTML=xmlhttp.responseText;
                            //matrizDesempeno($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor);
                        }
                    };
                    $("#script_grafico").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttpGrafico.open("POST","obtenerDesempenoCursoAjax?id_evaluacion="+$idEvaluacion+"&id_curso="+$idCurso+"&id_grupo="+$idGrupo+"&id_usuario_profesor="+$idusuario_profesor,true);
                    xmlhttpGrafico.send();
                    
                }
                function graficoAdopcion($idCurso, $idUsuario){
                    var xmlhttpGrafico;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttpGrafico=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttpGrafico=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttpGrafico.onreadystatechange=function(){
                    
                        if (xmlhttpGrafico.readyState==4 && xmlhttpGrafico.status==200){
                            //alert("idevaluacion "+$idEvaluacion+" idcurso "+$idCurso+" idgrupo "+$idGrupo);
                            $("#script_adopcion").html(xmlhttpGrafico.responseText);
                            //document.getElementById(resultadosAjax).innerHTML=xmlhttp.responseText;
                            //matrizDesempeno($idEvaluacion, $idCurso, $idGrupo, $idUsuario);
                        }
                    };
                    $("#script_adopcion").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttpGrafico.open("POST","obtenerAdopcionAlumnosAjax?id_curso="+$idCurso+"&id_usuario="+$idUsuario,true);
                    xmlhttpGrafico.send();
                    
                }
                
                function matrizDesempenoDiagnostico($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor){
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                    
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            //alert("idevaluacion "+$idEvaluacion+" idcurso "+$idCurso+" idgrupo "+$idGrupo);
                            $("#matriz_desempeno").html(xmlhttp.responseText);
                            
                            //document.getElementById(resultadosAjax).innerHTML=xmlhttp.responseText;
                          
                        }
                    };
                    $("#matriz_desempeno").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp.open("POST","obtenerMatrizDesempeñoNivelacionAjax?id_evaluacion="+$idEvaluacion+"&id_curso="+$idCurso+"&id_grupo="+$idGrupo+"&id_usuario_profesor="+$idusuario_profesor,true);
                    xmlhttp.send();                
                }
                function matrizDesempeno($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor){
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                    
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            //alert("idevaluacion "+$idEvaluacion+" idcurso "+$idCurso+" idgrupo "+$idGrupo);
                            $("#matriz_desempeno").html(xmlhttp.responseText);
                            
                            //document.getElementById(resultadosAjax).innerHTML=xmlhttp.responseText;
                          
                        }
                    };
                    $("#matriz_desempeno").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp.open("POST","obtenerMatrizDesempenoAjax?id_evaluacion="+$idEvaluacion+"&id_curso="+$idCurso+"&id_grupo="+$idGrupo+"&id_usuario_profesor="+$idusuario_profesor,true);
                    xmlhttp.send();
                    
                }
                
                function seleccionaEvaluacion($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor){
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                    
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            //alert("idevaluacion "+$idEvaluacion+" idcurso "+$idCurso+" idgrupo "+$idGrupo);
                            //$("#script_grafico").html(xmlhttp.responseText);
                            //document.getElementById(resultadosAjax).innerHTML=xmlhttp.responseText;
                            clickEvaluacion($idEvaluacion, $idCurso, $("#combogrupos").val(), $idusuario_profesor);
                        }
                    };
               
                    xmlhttp.open("POST","setQuizAjax?id_evaluacion="+$idEvaluacion,true);
                    xmlhttp.send();
                    
                }
                function seleccionaGrupo($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor){
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                    
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            //alert("idevaluacion "+$idEvaluacion+" idcurso "+$idCurso+" idgrupo "+$idGrupo);
                            //$("#script_grafico").html(xmlhttp.responseText);
                            //document.getElementById(resultadosAjax).innerHTML=xmlhttp.responseText;
                            clickEvaluacion($("#comboquizes").val(), $idCurso, $idGrupo, $idusuario_profesor);
                        }
                    };

                    xmlhttp.open("POST","setGrupoAjax?id_grupo="+$idGrupo,true);
                    xmlhttp.send();
                    
                }
                
                function tuDesempeno($idEvaluacion, $idCurso, $idGrupo, $idusuario_profesor){ 
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            $("#desempenoprofesor").html(xmlhttp.responseText);
                        }
                    };
                    $("#desempenoprofesor").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp.open("POST","obtenerDatosDesempenoAjax?id_evaluacion="+$idEvaluacion+"&id_curso="+$idCurso+"&id_grupo="+$idGrupo+"&id_usuario_profesor="+$idusuario_profesor,true);
                    xmlhttp.send();
                    
                }
                
                function cargaLogo(){
                    
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            $("#logo_institucion").html(xmlhttp.responseText);
                        }
                    };
                    $("#logo_institucion").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp.open("POST","obtenerImagenInstitucionAjax",true);
                    xmlhttp.send();
                }
                
                function linkAlumno($posicion_alumno){
                    var xmlhttp;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function(){
                        if (xmlhttp.readyState==4 && xmlhttp.status==200){
                            
                            $("#click_alumno").html(xmlhttp.responseText);
                            window.open(xmlhttp.responseText);   
                        }
                       
                    };
                    xmlhttp.open("POST","obtenerLinkAlumnoAjax?posicion_alumno="+$posicion_alumno,true);
                    xmlhttp.send();
                }
                
            </script>


    </head>

    <body id="index" onload="new ElementMaxHeight();">        
        <div id="header_tall">
            <div id="main">
                <!--header -->
                <div id="header">
                    <div class="h_logo">
                        <div class="left">
                            <a href="http://superate.galyleo.net/" target="_blank" ><img class="logo_galyleo" alt="Ir a Galyleo.net" src="../views/images/logos/superate.png" /><br /></a>
                        </div>
                        <div class="right" style="text-align: right;">
                            <h3>Bienvenido</h3>
                            <?php
                            echo strtoupper($usuario_profesor->nombre) . " " . strtoupper($usuario_profesor->apellido) . " ";
                            ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div id="menu">
                        <div class="rightbg">
                            <div class="leftbg">
                                <div class="padding">
                                    <ul>
                                        <?php
                                        $i = 0;
                                        if (count($cursos_profesor) > 0) {
                                            foreach ($cursos_profesor as $curso) {
                                                $i++;
                                                if ($i == count($cursos_profesor)) {
                                                    echo "<li class='last'><button class='boton_menu' onclick='clickCurso(" . $curso->id . ")' >" . $curso->fullname . " </button></li>";
                                                } else {

                                                    echo "<li><button class='boton_menu' onclick='clickCurso(" . $curso->id . ")' >" . $curso->fullname . "</button></li>";
                                                }
                                            }
                                        } else {
                                            
                                        }
                                        ?>
                                    </ul>
                                    <br class="clear" />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="right" style="padding: 0;">
                        <div class="indent_column">&nbsp;</div>
                        <div class="indent">
                            <div align="center" id="logo_institucion">
                                <a href="http://www.galyleo.net" target="_blank" ><img alt="Ir a Galyleo.net" src="../views/images/logos/galyleo.png" style="width: 180px;"/><br /></a>
                            </div>
                            <div class="border">
                                <div class="btall">
                                    <div class="ltall">
                                        <div class="rtall">
                                            <div class="tleft">
                                                <div class="tright">
                                                    <div class="bleft">
                                                        <div class="bright">
                                                            <div class="ind">
                                                                <div class="h_text">
                                                                    <div class="padding">
                                                                        <strong>Grupos:</strong><br />
                                                                    </div>
                                                                </div>
                                                                <div class="padding">
                                                                    <p class="p1" id="gruposusuario_profesor">
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </br>   
                            </div>
                            <div class="border">
                                <div class="btall">
                                    <div class="ltall">
                                        <div class="rtall">
                                            <div class="tleft">
                                                <div class="tright">
                                                    <div class="bleft">
                                                        <div class="bright">
                                                            <div class="ind">
                                                                <div class="h_text">
                                                                    <div class="padding">
                                                                        <strong>Evaluaciones:</strong><br />
                                                                    </div>
                                                                </div>
                                                                <div class="padding">
                                                                    <p class="p1">
                                                                        <div id="evaluacionesCursousuario_profesor">
                                                                        </div>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </br>
                            <div class="border">
                                <div class="btall">
                                    <div class="ltall">
                                        <div class="rtall">
                                            <div class="tleft">
                                                <div class="tright">
                                                    <div class="bleft">
                                                        <div class="bright">
                                                            <div class="ind">
                                                                <div class="h_text">
                                                                    <div class="padding">
                                                                        <strong>Desempeño:</strong><br />
                                                                    </div>
                                                                </div>
                                                                <div class="padding">
                                                                    <p class="p1">
                                                                        <div id="desempenoprofesor">
                                                                        </div>
                                                                    </p>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </br>
                            <div class="border">
                                <div class="btall">
                                    <div class="ltall">
                                        <div class="rtall">
                                            <div class="tleft">
                                                <div class="tright">
                                                    <div class="bleft">
                                                        <div class="bright">
                                                            <div class="ind">
                                                                <div class="h_text">
                                                                    <div class="padding">
                                                                        <strong>Links:</strong><br />
                                                                    </div>
                                                                </div>
                                                                <div class="padding">
                                                                    <p class="p1">
                                                                        <table>
                                                                            <tr><td><a class="" target="_blank" href="http://soporte.galyleo.net">Ir a Soporte Galyleo</a></td>
                                                                            </tr>
                                                                            <tr><td><a class="" target="_blank" href="http://www.galyleo.net">Visita Galyleo.net</a></td>
                                                                            </tr>
                                                                        </table>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
                <!--header end-->
                <div id="middle">
                    <div class="indent">
                        <div class="columns2">
                            <div class="ver_line">
                                <div id="matriz_desempeno" class="column1" style="width: 670px"></div>
                                <div id="script_grafico" class="column1" style="width: 670px"></div>   
                                <div id="script_adopcion" class="column1" style="width: 670px"></div>
                                <div id="listaNotas" class="column1" style="width: 670px"> </div> 
                                <div class="clear"></div>
                            </div>

                        </div>
                    </div>
                </div>
                <!--footer -->
                <div id="footer">
                    <div id="text_footer">
                        <img src="../views/images/icono.ico" width="15px" /> Galyleo - Tecnologías Educativas Colaborativas
                    </div>
                </div>

                </body>
                </html>