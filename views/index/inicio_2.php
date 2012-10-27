<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Galyleo - Gestión del Aprendizaje</title>
        <link href="views/css/style_layout.css" rel="stylesheet" type="text/css" />
        <link href="views/css/layout.css" rel="stylesheet" type="text/css" />
        <script src="views/js/maxheight.js" type="text/javascript"></script>
    </head>

    <body id="index" onload="new ElementMaxHeight();">
        <div id="header_tall">
            <div align="center"><h1>
                <br>
                <br>
                <img src="http://www.galyleo.net/imgslib/block_logos/galyleo.png" width="200" />
                <br>
            </h1>
            <div  style="text-align: left; width: 300px; border-color: black; border-width: 2;">
                <br>
                <div id="div_reportes"></div>
                <div>
                    <?php
                    
                    
                    echo 'Prueba utilizando los siguientes links para entrar como:<br><br>
                        ';
                    echo 'Alumno:';
                    for ($i = 0; $i < count($links_alumnos); $i++) {
                        echo '<br>';
                        echo "<a href='".$links_alumnos[$i]."' > Reporte ".($i+1)."</a>";
                    }
                    echo '<br>';
                    echo '<br>';
                    echo 'Profesor:';
                    for ($i = 0; $i < count($links_profesores); $i++) {
                        echo '<br>';
                        echo "<a href='".$links_profesores[$i]."' > Reporte ".($i+1)."</a>";
                    }
                    echo '<br>';
                    echo '<br>';
                    echo 'Director:';
                    for ($i = 0; $i < count($links_directores); $i++) {
                        echo '<br>';
                        echo "<a href='".$links_directores[$i]."' > Reporte ".($i+1)."</a>";
                    }
                    echo '<br>';
                    echo '<br>';
                    echo 'Administrador:';
                    for ($i = 0; $i < count($links_admin); $i++) {
                        echo '<br>';
                        echo "<a href='".$links_admin[$i]."' > Reporte ".($i+1)."</a>";
                    }
                    ?>
                </div>

            </div>
        </div>
        </div>
    </body>
</html>      