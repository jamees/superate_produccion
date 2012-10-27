<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of monitoreoController
 *
 * @author JorgePaz
 */
class monitoreoController Extends baseController {

//put your code here
    public function index() {
        session_start();
        if (isset($_SESSION['usuario_monitoreo'])) {
            $usuario_monitoreo = $_SESSION['usuario_monitoreo'];

            $this->registry->template->usuario_monitoreo = $usuario_monitoreo;

//Se despliega la vista indicada
            $this->registry->template->show('monitoreo/index_monitoreo');
        } else {
            $this->registry->template->blog_heading = "Para acceder a su reporte debe estar logueado";
            $this->registry->template->show('error404');
        }
    }

    public function getIntentos() {

//        $intentos = DAOFactory::getIntentosDAO()->queryBuscaIntentosRealTime();
//        if (count($intentos) > 0) {
//            echo "<table border='1' class='TablaDatos' id='TablaDatos'>";
//            echo "<tr><td>Nombre(s)</td><td>Apellido(s)</td><td>Nota Final</td><td>N° Pregunta</td><td>Puntaje Max Pregunta</td><td>Puntaje Obtenido</td></tr>";
//            foreach ($intentos as $intento) {
//
//
//                echo "<tr><td>" . $intento->nombre . "</td><td>" . $intento->apellido . "</td><td>" . $intento->notaFinalQuiz . "</td><td>" . $intento->numeroPregunta . "</td><td>" . $intento->puntajeMaximo . "</td><td>" . $intento->puntajeObtenido . "</td></tr>";
//            }
//            echo "</table>";
//        }else
//            {
//                echo "<h2>Aun no hay respuestas enviadas por los alumnos....</h2>";
//            }
//    }

        $intentos = DAOFactory::getIntentosDAO()->queryBuscaIntentosRealTime();

        //print_r($intentos);
        if (count($intentos) > 0) {

            $usuarios = $this->obtenerUsuarios($intentos);
            
            foreach ($usuarios as $usuario) {
                
                echo $usuario->nombre." ".$usuario->apellido." | ";
            }
//            foreach ($usuarios as $intento) {
//
//                echo "<table border='1' class='TablaDatos' id='TablaDatos'>";
//                if (strcmp($usuario_alumno, $intento->username) != 0) {
//                    echo "<tr><td colspan='2' >" . $intento->nombre . "<br>" . $intento->apellido . "</td></tr>";
//                    echo "<tr><td>[" . $intento->numeroPregunta . "]</td><td>" . round($intento->puntajeObtenido, 2) . "</td></tr>";
//                    $usuario_alumno = $intento->username;
//                } else {
//                    echo "<tr><td>[" . $intento->numeroPregunta . "]</td><td>" . round($intento->puntajeObtenido, 2) . "</td></tr>";
//                }
//                echo "</table>";
//            }
        } else {
            echo "<h2>Aun no hay respuestas enviadas por los alumnos....</h2>";
        }
    }

    public function obtenerUsuarios($intentos) {

//    var $intento;
//    var $notaFinalQuiz;
//    var $numeroPregunta;
//    var $tipoPregunta;
//    var $puntajeMaximo;
//    var $estado;
//    var $puntajeObtenido;
//    var $respCorrecta;
//    var $respuesta; 
//    var $username; 
//    var $nombre;
//    var $apellido;

        $resultado_usuarios = array();
        $intentos = array();
        foreach ($intentos as $intento) {
            
            $usuario = new Usuario();
            $usuario->nombre = $intento->nombre;
            $usuario->apellido = $intento->apellido;
            $usuario->username = $intento->username;
            
            //Si el usuario no esta en la lista
            if (!in_array($usuario, $resultado_usuarios)) {
                $resultado_usuarios = $usuario;
            }else{
                //Agregar intento
            }
        }
        return $resultado_usuarios;
    }

}

?>
