<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <!--        <meta http-equiv="refresh" content="3" >-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Galyleo - Gestión del Aprendizaje</title>
        <link href="../views/css/score_layout.css" rel="stylesheet" type="text/css" />
        <link href="../views/css/layout.css" rel="stylesheet" type="text/css" />
        <script src="../views/js/jquery_1.7.1.js" type="text/javascript"></script>
        <script src="../views/js/jquery.boxy.js"></script>
        <script src="../views/js/monitoreo.js"></script>
        <!--Fin pruebas para recargar ajax-->
        <link rel="shortcut icon" href="../views/images/icono.ico?1.0" type="image/x-icon" />
    </head>
    <body>        
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
                            echo strtoupper($usuario_monitoreo->nombre) . " " . strtoupper($usuario_monitoreo->apellido) . " ";
                            ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div id="menu">
                        <div class="rightbg">
                            <div class="leftbg">
                                <div class="padding">
                                    <ul>
                                        <!-- Cursos del monitoreo -->
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

                            </br>

                            <br>
                        </div>
                    </div>
                </div>
                <!--header end-->
                <div id="middle">
                    <div class="indent">
                        <div class="columns2">
                            <div class="ver_line">
                                <div id="mostrarMonitoreo" class="column1"></div>
                                <div class="clear"></div>
                            </div>

                        </div>
                    </div>
                </div>
              

                </body>
                </html>