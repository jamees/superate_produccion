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
                    cargarGraficoDesempeno();
                })
            </script>
            <script>
                $(document).ready(function(){
                    cargarGraficoAdopcionPieAlumno();
                })
            </script>
            <script>
                $(document).ready(function(){
                    cargarGraficoAdopcionPieProfesor();
                })
            </script>   
<!--            <script>
                $(document).ready(function(){
                    cargarGraficoAdopcionGrupos();
                })
            </script>               -->
            <script>
                $(document).ready(function(){
                    cargarGraficoAdopcion();
                })
            </script>       



            <script>
                function cargarGraficoDesempeno(){
                    var xmlhttp1;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp1=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp1.onreadystatechange=function(){
                    
                        if (xmlhttp1.readyState==4 && xmlhttp1.status==200){
                            $('#grafico_desempeno').html(xmlhttp1.responseText);
                        }
                    };
                    $("#grafico_desempeno").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp1.open("POST","obtenerGraficoDesempenoAjax",true);
                    xmlhttp1.send();
                }
                function cargarGraficoAdopcion(){
                    var xmlhttp2;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp2=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp2.onreadystatechange=function(){
                    
                        if (xmlhttp2.readyState==4 && xmlhttp2.status==200){
                            $('#grafico_adopcion').html(xmlhttp2.responseText);
                        }
                    };
                    $("#grafico_adopcion").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp2.open("POST","obtenerGraficoAdopcionAjax",true);
                    xmlhttp2.send();
                }
                function cargarGraficoAdopcionGrupos(){
                    var xmlhttp3;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp3=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp3=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp3.onreadystatechange=function(){
                    
                        if (xmlhttp3.readyState==4 && xmlhttp3.status==200){
                            $('#grafico_adopcion_grupos').html(xmlhttp3.responseText);
                        }
                    };
                    $("#grafico_adopcion_grupos").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp3.open("POST","obtenerGraficoAdopcionGruposAjax",true);
                    xmlhttp3.send();
                }
                function cargarGraficoAdopcionPieAlumno(){
                    var xmlhttp4;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp4=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp4=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp4.onreadystatechange=function(){
                    
                        if (xmlhttp4.readyState==4 && xmlhttp4.status==200){
                            $('#grafico_pie_adopcion_alumno').html(xmlhttp4.responseText);
                        }
                    };
                    $("#grafico_pie_adopcion_alumno").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp4.open("POST","obtenerGraficoPieAdopcionAlumnoAjax",true);
                    xmlhttp4.send();
                    
                }
                function cargarGraficoAdopcionPieProfesor(){
                    var xmlhttp5;
                    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp5=new XMLHttpRequest();
                    }
                    else{// code for IE6, IE5
                        xmlhttp5=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp5.onreadystatechange=function(){
                    
                        if (xmlhttp5.readyState==4 && xmlhttp5.status==200){
                            $('#grafico_pie_adopcion_profesor').html(xmlhttp5.responseText);
                        }
                    };
                    $("#grafico_pie_adopcion_profesor").html("<div style='width:100%; margin-top:5px' align='center'><img src='../views/images/ajax-loading.gif'/></div>");
                    xmlhttp5.open("POST","obtenerGraficoPieAdopcionProfesorAjax",true);
                    xmlhttp5.send();
                    
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
                            <a href="http://www.galyleo.net" target="_blank" ><img class="logo_galyleo" alt="Ir a Galyleo.net" src="../views/images/logos/superate.png" /><br /></a>
                        </div>
                        <div class="right" style="text-align: right;">
                            <h3>Bienvenido</h3>
                            <?php
                            echo strtoupper($usuario_director->nombre) . " " . strtoupper($usuario_director->apellido) . " ";
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
                                                                        <strong>Adopción General:</strong><br>Los siguientes gráficos muestran el porcentaje de alumnos y profesores que han ingresado alguna vez a la plataforma<br>
                                                                    </div>
                                                                </div>
                                                                <div class="padding">
                                                                    <p class="p1" id="gruposusuario_director">
                                                                        <div id="grafico_pie_adopcion_alumno"></div>
                                                                        <div id="grafico_pie_adopcion_profesor"></div>
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
<!--                            <div class="border">
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
                                                                        <div id="evaluacionesCursousuario_director">
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
                            </br>-->
<!--                            <div class="border">
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
                                                                        <div id="desempenodirector">
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
                            </div>-->
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
                                <div id="grafico_desempeno" class="column1" style="width: 670px;"></div>
                                <div id="grafico_adopcion" class="column1" style="width: 670px;"></div>
                                <div id="grafico_adopcion_grupos" class="column1" style="width: 670px;"></div>
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