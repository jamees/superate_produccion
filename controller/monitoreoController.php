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
        $usuarios = $this->obtenerUsuarios($intentos);

        //print_r($intentos);
        if (count($intentos) > 0) {
            foreach ($usuarios as $usuario) {
                echo $usuario->nombre . " " . $usuario->apellido . " <br> ";
                foreach ($usuario->respuestas as $respuesta) {
                    echo $respuesta->numeroPregunta . " | " . round($respuesta->puntajeObtenido, 2) . "<br>";
                }
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

        $resultado_usuarios = array();
        $resultado_global = array();
        $intentos_aux = $intentos;

        foreach ($intentos as $intento) {
            $estudiante = new Estudiante();
            $estudiante->nombre = $intento->nombre;
            $estudiante->apellido = $intento->apellido;
            $estudiante->username = $intento->username;
            //Si el usuario no esta en la lista lo agrego y busco sus respuestas
           print_r(sizeof($resultado_usuarios)." - ");
           print_r($estudiante);
           print_r("<br>");
           print_r(!in_array($estudiante, $resultado_usuarios));
           print_r("<br>");
           print_r($resultado_usuarios);
           print_r("<br>");
            if (in_array($estudiante, $resultado_usuarios) == 0) {

                $respuestas_usuario = array();
                //Recorro todos los intentos del usuario y los agrego a su lita
                print_r(sizeof($intentos_aux)." | ");
                for ($i = 0; $i < sizeof($intentos_aux);$i++) {
                  
                    //Si encontre una respuesta del estudiante, se agrega
                    if ($intentos_aux[$i]->username == $estudiante->username) {
                        
                        $respuesta = new Respuesta();
                        $respuesta->estado = $intentos_aux[$i]->estado;
                        $respuesta->intento = $intentos_aux[$i]->intento;
                        $respuesta->notaFinalQuiz = $intentos_aux[$i]->notaFinalQuiz;
                        $respuesta->numeroPregunta = $intentos_aux[$i]->numeroPregunta;
                        $respuesta->puntajeMaximo = $intentos_aux[$i]->puntajeMaximo;
                        $respuesta->puntajeObtenido = $intentos_aux[$i]->puntajeObtenido;
                        $respuesta->respCorrecta = $intentos_aux[$i]->respCorrecta;
                        $respuesta->respuesta = $intentos_aux[$i]->respuesta;
                        $respuesta->tipoPregunta = $intentos_aux[$i]->tipoPregunta;
                        if (!in_array($respuesta, $respuestas_usuario)) {
                            $respuestas_usuario[] = $respuesta;
                        }
                    }else{
                        echo "No son iguales <br>";
                    }
                }
                
                //Agrego a resultado_usuarios la lista de los usuarios unicos
                $resultado_usuarios[] = $estudiante;

                //Agrego a resultado_global la lista de los usuarios con sus intentos
                $estudiante_aux = $estudiante;
                $estudiante_aux->respuestas = $respuestas_usuario;
                $resultado_global[] = $estudiante_aux;
            } else {
              
            }
        }
        return $resultado_global;
    }

}

?>
