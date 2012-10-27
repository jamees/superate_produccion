<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of alumnoController
 *
 * @author JorgePaz
 */
class alumnoController Extends baseController {

    //put your code here
    public function index() {
        session_start();
        if (isset($_SESSION['usuario'])) {
            $usuario = $_SESSION['usuario'];
            //Se selecciona por defecto el primer curso
            $cursos_alumno = DAOFactory::getCursosDAO()->queryBuscaCursos($usuario->id, $usuario->rolMoodle);
            $i = 0;
            $curso_seleccionado = "0";
            foreach ($cursos_alumno as $curso) {
                if ($i == 0) {
                    $curso_seleccionado = $curso->id;
                }
                $i++;
            }

            $grupos_alumno = DAOFactory::getGruposDAO()->queryBuscaGrupo($usuario->id, $curso_seleccionado);
            $grupo_seleccionado = "0";
            $i = 0;
            foreach ($grupos_alumno as $grupo) {
                if ($i == 0) {
                    $grupo_seleccionado = $grupo->id;
                }
                $i++;
            }

            $quiz_alumno = DAOFactory::getQuizDAO()->queryBuscaQuiz($curso_seleccionado, $usuario->id);
            $quiz_seleccionado = "0";
            $i = 0;
            foreach ($quiz_alumno as $quiz) {
                if ($i == 0) {
                    $quiz_seleccionado = $quiz->id;
                }
                $i++;
            }

            //Se guardan las variables en la vista
            $this->registry->template->usuario = $usuario;
            $this->registry->template->cursos_alumno = $cursos_alumno;
            $_SESSION['id_usuario'] = $usuario->id;
            $_SESSION['quiz_seleccionado'] = $quiz_seleccionado;
            $_SESSION['grupo_seleccionado'] = $grupo_seleccionado;
            $_SESSION['curso_seleccionado'] = $curso_seleccionado;

            //todos los quizes y grupos de un alumno
            $_SESSION['quiz_alumno'] = $quiz_alumno;
            $_SESSION['grupos_alumno'] = $grupos_alumno;


            //
            //Se despliega la vista indicada
            $this->registry->template->show('alumno/index_alumno');
        } else {
            $this->registry->template->blog_heading = "Para acceder a su reporte debe estar logueado";
            $this->registry->template->show('error404');
        }
    }

    public function cambiarCursoAjax() {
        if (isset($_GET['curso_seleccionado'])) {
            session_start();
            $usuario = $_SESSION['usuario'];
            $grupos_alumno = DAOFactory::getGruposDAO()->queryBuscaGrupo($usuario->id, $_GET['curso_seleccionado']);
            $grupo_seleccionado = "0";
            $i = 0;
            foreach ($grupos_alumno as $grupo) {
                if ($i == 0) {
                    $grupo_seleccionado = $grupo->id;
                }
                $i++;
            }

            $quiz_alumno = DAOFactory::getQuizDAO()->queryBuscaQuiz($_GET['curso_seleccionado'], $usuario->id);
            $quiz_seleccionado = "0";
            $i = 0;
            foreach ($quiz_alumno as $quiz) {
                if ($i == 0) {
                    $quiz_seleccionado = $quiz->id;
                }
                $i++;
            }
            $_SESSION['quiz_seleccionado'] = $quiz_seleccionado;
            $_SESSION['grupo_seleccionado'] = $grupo_seleccionado;
            $_SESSION['curso_seleccionado'] = $_GET['curso_seleccionado'];
            $_SESSION['quiz_alumno'] = $quiz_alumno;
            $_SESSION['grupos_alumno'] = $grupos_alumno;

            echo $_SESSION['quiz_seleccionado'] . "," . $_SESSION['grupo_seleccionado'] . "," . $_SESSION['curso_seleccionado'];
        }
    }

    public function obtenerGruposAjax() {
        //Se comprueba que los parametros esten definidos
        if (isset($_GET['curso_seleccionado']) && isset($_GET['id_usuario'])) {
            //$curso_seleccionado = $_GET['curso_seleccionado'];
            session_start();
            $curso_seleccionado = $_GET['curso_seleccionado'];
            $quiz_seleccionado = $_SESSION['quiz_seleccionado'];
            $id_usuario = $_SESSION['id_usuario'];
            $grupos_alumno = DAOFactory::getGruposDAO()->queryBuscaGrupo($id_usuario, $curso_seleccionado);
            if (count($grupos_alumno) > 0) {
                $i = 0;
                $tooltip = "";
                //$tooltip = "title='Estos son los grupos a los que perteneces' rel='tooltip'";
                echo "<select " . $tooltip . " id='combogrupos' style='font-size: 11px;  width: 100%;'  onchange='seleccionaGrupo(" . $quiz_seleccionado . "," . $curso_seleccionado . ",this.value," . $id_usuario . ")'>";
                foreach ($grupos_alumno as $grupo) {
                    if ($i == 0) {
                        $_SESSION['grupo_seleccionado'] = $grupo->id;
                    }
                    $i++;

                    echo '<option value="' . $grupo->id . '" >' . $grupo->nombre . '</option>';
                }
                echo '</select>';
                //$this->tooltip();
            } else {
                echo '<table><tr><td>No se encontraron grupos</td></tr></table>';
            }
        }
    }

    public function obtenerEvaluacionesAjax() {
        //Se comprueba que los parametros esten definidos
        if (isset($_GET['curso_seleccionado']) && isset($_GET['id_usuario']) && isset($_GET['id_grupo'])) {

            session_start();
            $grupo_seleccionado = $_GET['id_grupo'];
            $curso_seleccionado = $_GET['curso_seleccionado'];
            $id_usuario = $_SESSION['id_usuario'];
            $quiz_alumno = DAOFactory::getQuizDAO()->queryBuscaQuiz($curso_seleccionado, $id_usuario);
            $i = 0;
            $tooltip = "";
            //$tooltip = "title='Presione para acceder a sus evaluaciones' rel='tooltip'";
            if (count($quiz_alumno) > 0) {
                echo "<select " . $tooltip . " id='comboquizes' style='font-size: 11px;  width: 100%;' onchange='seleccionaEvaluacion(this.value," . $curso_seleccionado . "," . $grupo_seleccionado . "," . $id_usuario . ")'>";

                //echo "<table style=\"width: 100%;\">";
                //Se muestra la lista de quiz que tiene el usuario en el curso y su nota
                foreach ($quiz_alumno as $quiz) {
                    if ($i == 0) {
                        $_SESSION['quiz_seleccionado'] = $quiz->id;
                    }
                    $i++;
                    echo '<option value="' . $quiz->id . '" >' . $quiz->NombreQuiz . '</option>';
                }
                echo '</select>';
            } else {
                echo '<table><tr><td>No se encontraron evaluaciones</td></tr></table>';
            }
            //$this->tooltip();
        }
    }

    public function setQuizAjax() {
        if (isset($_GET['id_evaluacion'])) {
            $_SESSION['quiz_seleccionado'] = $_GET['id_evaluacion'];
        }
    }

    public function setGrupoAjax() {
        if (isset($_GET['id_grupo'])) {
            $_SESSION['grupo_seleccionado'] = $_GET['id_grupo'];
        }
    }

    public function obtenerDesempenoCursoAjax() {
        //Se comprueba que los parametros esten definidos
        if (isset($_GET['id_evaluacion']) && isset($_GET['id_curso']) && isset($_GET['id_grupo'])) {
            session_start();
            $curso_seleccionado = $_GET['id_curso'];
            $grupoSeleccionado = $_GET['id_grupo'];
            $id_evaluacion = $_GET['id_evaluacion'];
            if ($id_evaluacion == 'undefined') {
                $id_evaluacion = "0";
            }
            //$grupoSeleccionado = $_SESSION['grupo_seleccionado'];
            //$id_evaluacion = $_SESSION['quiz_seleccionado'];
            $this->obtenerGraficoRanking($curso_seleccionado, $id_evaluacion, $grupoSeleccionado);
        }
    }

    public function obtenerDesempenoCursoDiagnosticoAjax() {
        //Se comprueba que los parametros esten definidos
        if (isset($_GET['id_evaluacion']) && isset($_GET['id_curso']) && isset($_GET['id_grupo'])) {
            session_start();
            $curso_seleccionado = $_GET['id_curso'];
            $grupoSeleccionado = $_GET['id_grupo'];
            $id_evaluacion = $_GET['id_evaluacion'];
            if ($id_evaluacion == 'undefined') {
                $id_evaluacion = "0";
            }
            //$grupoSeleccionado = $_SESSION['grupo_seleccionado'];
            //$id_evaluacion = $_SESSION['quiz_seleccionado'];
            $this->obtenerGraficoRanking($curso_seleccionado, $id_evaluacion, $grupoSeleccionado);
        }
    }

    public function obtenerGraficoRanking($curso_seleccionado, $id_evaluacion, $grupoSeleccionado) {
        $lista_rank = DAOFactory::getRankingDAO()->queryBuscaRanking($curso_seleccionado, $id_evaluacion, $grupoSeleccionado);
        $_SESSION['lista_rank'] = $lista_rank;
        $usuario = $_SESSION['usuario'];

        $data = "";
        $categorias = "";
        $index = 0;

        foreach ($lista_rank as $rank) {
            // echo $rank->nombres." ".$rank->apellidos." ".$rank->nota." ".$rank->usuario."<br>";
            if ($index == count($lista_rank) - 1) {
                if (!($usuario->id == $rank->userid)) {
                    $data = $data . "" . round($rank->nota, 2);
                } else {
                    $data = $data . "{ y: " . round($rank->nota, 2) . ", marker: { symbol: 'url(../views/images/icons/pin_verde.png)' } }";
                }
                $categorias = $categorias . "" . ($index + 1) . ""; //Alumno
                //$categorias = $categorias . "'" .$rank->nombres." ".$rank->apellidos."'";   //Profesor
            } else {

                if (!($usuario->id == $rank->userid)) {
                    $data = $data . "" . round($rank->nota, 2) . ",";
                } else {
                    $data = $data . "" . "{ y: " . round($rank->nota, 2) . ", marker: { symbol: 'url(../views/images/icons/pin_verde.png)' } },";
                }
                $categorias = $categorias . "" . round($index + 1, 1) . ","; //Alumno
                //$categorias = $categorias . "'" .$rank->nombres." ".$rank->apellidos."'". ","; // Profesor
            }
            $index++;
        }


        $quiz_alumno = $_SESSION['quiz_alumno'];
        $grupos_alumno = $_SESSION['grupos_alumno'];
        $titulo = "";
        $subtitulo = "";
        foreach ($quiz_alumno as $quiz) {
            if ($quiz->id == $id_evaluacion) {
                $titulo = $quiz->NombreQuiz;
            }
        }
        foreach ($grupos_alumno as $grupo) {
            if ($grupo->id == $grupoSeleccionado) {
                $subtitulo = $grupo->nombre;
            }
        }
        //echo "<script src=\"../views/js/modules/exporting.js\"></script>";
        echo "<h2>Ranking: </h2><br>";
        echo "<h3 style=\"text-align: justify;\">Este gráfico muestra el ranking de alumnos que han realizado la evaluación que seleccionaste. Tu posición en el ranking esta demarcada por el punto verde en el gráfico.</h3>";
        echo "<div id=\"container\" class=\"column1\" style=\" width: 670px; height: 300px;\"></div>";

        $this->graficoDeLinea("{
                            name: 'Notas',
                            data: [" . $data . "]
                        }", $categorias, $titulo, $subtitulo);
        echo "<br>";
    }

    //matriz hibirda dignostico + acompañamiento
    //
    public function obtenerMatrizDesempeñoNivelacionAjax() {
        session_start();
        if (isset($_GET['id_evaluacion']) && isset($_GET['id_curso']) && isset($_GET['id_grupo']) && isset($_SESSION['lista_rank'])) {

            $usuario = $_SESSION['usuario'];
            $cursoSeleccionado = $_GET['id_curso'];
            $grupoSeleccionado = $_GET['id_grupo'];
            $quizSeleccionado = $_GET['id_evaluacion'];

            if ($quizSeleccionado == "undefined") {
                $quizSeleccionado = 0;
            }
            $ranking = $_SESSION['lista_rank'];
            $intent = DAOFactory::getIntentosDAO()->queryBuscaIntentos($quizSeleccionado, $usuario->id);

            $suma_notas = 0;
            $nota_quiz = 0;

            //calculo de maximo intento
            $max = 0;
            $max_nota = 0;
            foreach ($intent as $value) {
                if ($value->notaFinalQuiz >= $max_nota) {
                    $max = $value->intento;
                    $max_nota = $value->notaFinalQuiz;
                }
            }

            //muestra todos los intentos y notas por preguntas
            foreach ($intent as $value) {
                if ($value->puntajeObtenido == NULL) {
                    $value->puntajeObtenido = 0;
                }
            }

            //busca solo los contenidos
            $array_contenidos = null;
            $matriz_quiz = DAOFactory::getMatrizDAO()->queryBuscaMatriz($quizSeleccionado);

            $contiene_sub_pregunta = 0;
            foreach ($matriz_quiz as $value) {
                if (!(strcmp($value->contenido, "") == 0)) {
                    $array_contenidos[] = $value->contenido;
                    if (!(strcmp($value->tipopregunta, "") == 0)) {
                        $contiene_sub_pregunta = 1;
                    }
                }
            }
            // Se realiza para cambiar a la modalidad con sub preguntas
            if ($contiene_sub_pregunta == 1) {
                $cant = 0;
                $intentos_aux = array();
                foreach ($intent as $value) {
                    if ((int) $value->intento == $max) {
                        $cant +=1;
                        $intentos_max = new Intentos();
                        $intentos_max->estado = $value->estado;
                        $intentos_max->intento = $value->intento;
                        $intentos_max->notaFinalQuiz = $value->notaFinalQuiz;
                        $intentos_max->numeroPregunta = $cant;
                        $intentos_max->puntajeMaximo = $value->puntajeMaximo;
                        $intentos_max->puntajeObtenido = $value->puntajeObtenido;
                        $intentos_max->respCorrecta = $value->respCorrecta;
                        $intentos_max->respuesta = $value->respuesta;
                        $intentos_max->tipoPregunta = $value->tipoPregunta;
                        $intentos_aux[$cant] = $intentos_max;
                        if ($value->tipoPregunta == "multianswer") {
                            //echo "*****Entro al multianswer<br>";
                            $respuesta_alumno = explode(";", $value->respuesta);
                            $respuesta_correcta = explode(";", $value->respCorrecta);

                            for ($i = 0; $i < count($respuesta_correcta); $i++) {

                                if (strcmp($respuesta_correcta[$i], $respuesta_alumno[$i]) == 0) {

                                    $intentos_max = new Intentos();
                                    $intentos_max->estado = $value->estado;
                                    $intentos_max->intento = $value->intento;
                                    $intentos_max->notaFinalQuiz = $value->notaFinalQuiz;
                                    $intentos_max->numeroPregunta = $cant . "." . ($i + 1);
                                    $intentos_max->puntajeMaximo = 1;
                                    $intentos_max->puntajeObtenido = 1;
                                    $intentos_max->respCorrecta = $respuesta_correcta[$i];
                                    $intentos_max->respuesta = $respuesta_alumno[$i];
                                    $intentos_max->tipoPregunta = $value->tipoPregunta;
                                    $intentos_aux[$cant . "." . ($i + 1)] = $intentos_max;
                                } else {
                                    $intentos_max = new Intentos();
                                    $intentos_max->estado = $value->estado;
                                    $intentos_max->intento = $value->intento;
                                    $intentos_max->notaFinalQuiz = $value->notaFinalQuiz;
                                    $intentos_max->numeroPregunta = $cant . "." . ($i + 1);
                                    $intentos_max->puntajeMaximo = 1;
                                    $intentos_max->puntajeObtenido = 0;
                                    $intentos_max->respCorrecta = $respuesta_correcta[$i];
                                    $intentos_max->respuesta = $respuesta_alumno[$i];
                                    $intentos_max->tipoPregunta = $value->tipoPregunta;
                                    $intentos_aux[$cant . "." . ($i + 1)] = $intentos_max;
                                }
                            }
                        }
                    }
                }
                $intent = $intentos_aux;
            }

            //Definición de umbrales
            $nota_aprobado = 60;
            $nota_suficiente = 40;



            foreach ($matriz_quiz as $value) {
                if ($value->contenido != "") {
                    $array_contenidos[] = $value->contenido;
                }
            }

            //variable para mantener la relacion contenido - quiz
            $array_notas_quiz_diagostico = null;
            if (count($array_contenidos) > 0) {
                //limpia la matriz y deja solo los contenidos distintos
                $contenidos_matriz = array_values(array_unique($array_contenidos));
                //obtengo los link de la matriz
                foreach ($contenidos_matriz as $contenido) {
                    foreach ($matriz_quiz as $value) {
                        if (strcmp($contenido, $value->contenido) == 0) {
                            $links_matriz[] = $value->link_repaso;
                            $nota_quiz_diagnostico = DAOFactory::getQuizDAO()->queryBuscaNotaQuiz($value->idquiz_diagnostico, $usuario->id);
                            if ((int) $nota_quiz_diagnostico > 0) {
                                $array_notas_quiz_diagostico[] = $nota_quiz_diagnostico;
                            } else {
                                $array_notas_quiz_diagostico[] = -1;
                            }

                            break;
                        }
                    }
                }

                //generacion matriz de desempeño
                $indice_nota_quiz = 0;
                foreach ($contenidos_matriz as $value0) {
                    $suma_nota = 0;
                    $suma_puntajes = 0;
                    foreach ($matriz_quiz as $value1) {
                        if (strcmp($value1->contenido, $value0) == 0) {
                            $numero_pregunta = 0;
                            foreach ($intent as $value2) {
                                if ($value2->puntajeObtenido == NULL) {
                                    $value2->puntajeObtenido = 0;
                                }
                                if ($value2->intento == $max) {
                                    $numero_pregunta +=1;
                                    if ($numero_pregunta == $value1->n_pregunta) {
                                        $suma_nota += (float) $value2->puntajeObtenido;
                                        $suma_puntajes += (float) $value2->puntajeMaximo;
                                    }
                                }
                            }
                        }
                    }

                    ///Division por cero
                    if ($suma_puntajes > 0) {
                        $logro_contenido[] = ($suma_nota / $suma_puntajes) * 100;
                        if ($array_notas_quiz_diagostico[$indice_nota_quiz] == -1) {
                            $logro_contenido_diagnostico[] = ($suma_nota / $suma_puntajes) * 100;
//                                echo "nota nuevo logro : ".(($suma_nota / $suma_puntajes) * 100)."<br>";
                        } else {
                            $logro_contenido_diagnostico[] = $array_notas_quiz_diagostico[$indice_nota_quiz];
//                                echo "nota nuevo logro : ".$array_notas_quiz_diagostico[$indice_nota_quiz]."<br>";
                        }
                    } else {
                        $logro_contenido[] = 0;
                    }
                    $indice_nota_quiz++;
                }

                //recupera el eje de cada contenido
                foreach ($contenidos_matriz as $value) {
                    $eje = "";
                    foreach ($matriz_quiz as $value1) {
                        if ($value == $value1->contenido) {
                            $eje = $value1->eje;
                        }
                    }
                    $ejes_matriz[] = $eje;
                }
                //Matri de desempeño sin eje
                $thead = "";
                $tdata = "";
                $thead_d = "";
                $tdata_d = "";
                $ejes_distintos_matriz = array_values(array_unique($ejes_matriz));

                foreach ($ejes_distintos_matriz as $value) {
                    $thead = $thead . "<th>" . $value . "</th>";
                    $thead_d = $thead_d . "<th>" . $value . "</th>";
                    $tdata = $tdata . "<td style=\"vertical-align: top;\ vertical-align: top; padding-top: 0px; padding-bottom: 0px; padding-left: 5px; padding-right: 5px; font-size: 13px;\"><table>";
                    $tdata_d = $tdata_d . "<td style=\"vertical-align: top;\ vertical-align: top; padding-top: 0px; padding-bottom: 0px; padding-left: 5px; padding-right: 5px; font-size: 13px;\"><table>";
                    for ($index = 0; $index < count($ejes_matriz); $index++) {

                        if (strcmp($value, $ejes_matriz[$index]) == 0) {

                            $tdata = $tdata . "<tr>";
                            $tdata_d = $tdata_d . "<tr>";

                            if ($logro_contenido[$index] >= $nota_aprobado) {
                                $color_celda = "#64FE2E";
                            } else if ($logro_contenido[$index] >= $nota_suficiente) {
                                $color_celda = "#F7FE2E";
                            } else {
                                $color_celda = "#FE642E";
                            }

                            $tdata = $tdata . "<td><a target='_blank' title='Presiona aquí para acceder a este contenido' rel='tooltip' href='" . $links_matriz[$index] . "'><strong>" . $contenidos_matriz[$index] . "</strong></a></td>";
                            $tdata = $tdata . "<td style=\"background: " . $color_celda . "; width: 20%;\"><strong title='Tu logro en este contenido fue de " . round($logro_contenido[$index], 2) . "%' rel='tooltip'>" . round($logro_contenido[$index], 2) . "%</strong></td>";
                            $tdata = $tdata . "</tr>";

                            if ($logro_contenido_diagnostico[$index] >= $nota_aprobado) {
                                $color_celda_d = "#64FE2E";
                            } else if ($logro_contenido_diagnostico[$index] >= $nota_suficiente) {
                                $color_celda_d = "#F7FE2E";
                            } else {
                                $color_celda_d = "#FE642E";
                            }

                            $tdata_d = $tdata_d . "<td><a target='_blank' title='Presiona aquí para acceder a este contenido' rel='tooltip' href='" . $links_matriz[$index] . "'><strong>" . $contenidos_matriz[$index] . "</strong></a></td>";
                            $tdata_d = $tdata_d . "<td style=\"background: " . $color_celda_d . "; width: 20%;\"><strong title='Tu logro en este contenido fue de " . round($logro_contenido_diagnostico[$index], 2) . "%' rel='tooltip'>" . round($logro_contenido_diagnostico[$index], 2) . "%</strong></td>";
                            $tdata_d = $tdata_d . "</tr>";
                        }
                    }
                    $tdata = $tdata . "</table></td>";
                    $tdata_d = $tdata_d . "</table></td>";
                }
                echo "<h2>Matriz de desempeño:</h2><br>";
                echo "<h3 style=\"text-align: justify;\">A continuación te presentamos tu Matriz de Desempeño en la prueba de  diagnóstico. En ella  se presentan los aprendizajes esperados evaluados con sus respectivos porcentajes de logro. </h3>";
                echo "<div style='overflow-x: auto; overflow-y: hidden;'>";
                echo "<table style=\"width: 660px;\">";
                echo "<tr>" . $thead . "</tr>";
                echo "<tr>" . $tdata . "</tr>";
                echo "</table>";
                echo "</div>";
                echo "<br>";

                echo "<h2>Matriz de avance:</h2><br>";
                echo "<h3 style=\"text-align: justify;\">La siguiente Matriz de avance refleja el progreso que has logrado en los aprendizajes esperados luego de seguir tu ruta individual sugerida en la Ruta de aprendizaje</h3>";
                echo "<div style='overflow-x: auto; overflow-y: hidden;'>";
                echo "<table style=\"width: 660px;\">";
                echo "<tr>" . $thead_d . "</tr>";
                echo "<tr>" . $tdata_d . "</tr>";
                echo "</table>";
                echo "</div>";
                echo "<br>";
                echo "<h3>El siguiente  cuadro describe los casos en que tu matriz adquiere los colores verde, amarillo o rojo</h3>";
                echo "<div align=\"center\">";
                echo "<table style=\"width: 500px;border=\"1\">";
                echo "<tr>";
                echo "<td style=\"background:#64FE2E;\">Tu logro es mayor a 60%</td>";
                echo "<td style=\"background:#F7FE2E;\"><p>Tu logro es menor de 60% <br />";
                echo "pero mayor que 40%</p></td>";
                echo "<td style=\"background:#FE642E;\">Tu logro es menor que 40%</td>";
                echo "</tr>";
                echo "</table>";
                echo "</div>";
                //Calculo la ruta de aprendizaje
                $this->rutaDeAprendizaje($contenidos_matriz, $logro_contenido, $links_matriz);
                //Imprimo el tooltip para la matriz de desempeño
                $this->tooltip();
                //
            } else {
                //echo "No se ha definido una matriz para su curso";
            }
        }
    }

    //
    //matriz de desempeño sin comparacion de controles
    public function obtenerMatrizDesempenoAjax() {
        session_start();
        if (isset($_GET['id_evaluacion']) && isset($_GET['id_curso']) && isset($_GET['id_grupo']) && isset($_SESSION['lista_rank'])) {

            $usuario = $_SESSION['usuario'];
            $cursoSeleccionado = $_GET['id_curso'];
            $grupoSeleccionado = $_GET['id_grupo'];
            $quizSeleccionado = $_GET['id_evaluacion'];
            //$grupoSeleccionado = $_SESSION['grupo_seleccionado'];
            //$quizSeleccionado = $_SESSION['quiz_seleccionado'];
            if ($quizSeleccionado == "undefined") {
                $quizSeleccionado = 0;
            }
            //$ranking = DAOFactory::getRankingDAO()->queryBuscaRanking($cursoSeleccionado, $quizSeleccionado, $grupoSeleccionado);
            $ranking = $_SESSION['lista_rank'];
            $intent = DAOFactory::getIntentosDAO()->queryBuscaIntentos($quizSeleccionado, $usuario->id);
            //echo "<table border='1'>";

            $suma_notas = 0;
            $nota_quiz = 0;
            //echo "<tr><td colspan='2'>Ranking de alumnos de la evaluacion seleccionada</td></tr>";
            // echo "<tr><td>Nombre</td><td>Apellido</td><td>Usuario</td><td>Id</td><td>Nota</td></tr>";


            foreach ($ranking as $rank) {
                //  echo "<tr><td>" . $rank->nombres . "</td><td>" . $rank->apellidos . "</td><td>" . $rank->usuario . "</td><td>" . $rank->userid . "</td><td>" . round($rank->nota, 2) . "</td></tr>";
                $suma_notas += floor($rank->nota);
                if ($usuario->id == $rank->userid) {
                    $nota_quiz = $rank->nota;
                }
            }
//            echo "<div id='rendimiento'>";
//            if (count($ranking) > 0) {
//
//
//                echo "<p>Cantidad de Alumnos: " . count($ranking) . "</p>";
//                echo "<p>Promedio del curso: " . round(($suma_notas / count($ranking)), 2) . "</p>";
//            } else {
//                echo "<p>Cantidad de Alumnos: 0</p>";
//                echo "<p>Cantidad de Alumnos: 0</p>";
//            }
//            echo "<p>Nota propia: " . round($nota_quiz, 2) . "</p>";
//            echo "</div>";
            //calculo de maximo intento
            $max = 0;
            $max_nota = 0;
            foreach ($intent as $value) {
                if ($value->notaFinalQuiz >= $max_nota) {
                    $max = $value->intento;
                    $max_nota = $value->notaFinalQuiz;
                }
            }

            //muestra todos los intentos y notas por preguntas
//              echo "Intento en que se obtubo la nota maxima: " . $max . "<br>";
            foreach ($intent as $value) {
                if ($value->puntajeObtenido == NULL) {
                    $value->puntajeObtenido = 0;
                }
                //               echo " Nota final: " . $value->notaFinalQuiz . " Intento: " . $value->intento . " N pregunta: " . $value->numeroPregunta . " Puntaje obtenido: " . $value->puntajeObtenido . " Puntaje maximo: " . $value->puntajeMaximo . "<br>";
            }

            //busca solo los contenidos
            $array_contenidos = null;
            $matriz_quiz = DAOFactory::getMatrizDAO()->queryBuscaMatriz($quizSeleccionado);

            $contiene_sub_pregunta = 0;
            foreach ($matriz_quiz as $value) {
                if (!(strcmp($value->contenido, "") == 0)) {
                    $array_contenidos[] = $value->contenido;
                    if (!(strcmp($value->tipopregunta, "") == 0)) {
                        $contiene_sub_pregunta = 1;
                    }
                }
            }
            // Se realiza para cambiar a la modalidad con sub preguntas
            if ($contiene_sub_pregunta == 1) {
                $cant = 0;
                $intentos_aux = array();
                foreach ($intent as $value) {
                    if ((int) $value->intento == $max) {
                        $cant +=1;
                        $intentos_max = new Intentos();
                        $intentos_max->estado = $value->estado;
                        $intentos_max->intento = $value->intento;
                        $intentos_max->notaFinalQuiz = $value->notaFinalQuiz;
                        $intentos_max->numeroPregunta = $cant;
                        $intentos_max->puntajeMaximo = $value->puntajeMaximo;
                        $intentos_max->puntajeObtenido = $value->puntajeObtenido;
                        $intentos_max->respCorrecta = $value->respCorrecta;
                        $intentos_max->respuesta = $value->respuesta;
                        $intentos_max->tipoPregunta = $value->tipoPregunta;
                        $intentos_aux[$cant] = $intentos_max;
                        //echo " " . $value->tipoPregunta . "<br>";
                        // echo " Comparacion " . strcmp($value->tipoPregunta, "multianswer") . "<br>";
                        if ($value->tipoPregunta == "multianswer") {
                            //echo "*****Entro al multianswer<br>";
                            $respuesta_alumno = explode(";", $value->respuesta);
                            $respuesta_correcta = explode(";", $value->respCorrecta);

                            for ($i = 0; $i < count($respuesta_correcta); $i++) {

                                if (strcmp($respuesta_correcta[$i], $respuesta_alumno[$i]) == 0) {

                                    $intentos_max = new Intentos();
                                    $intentos_max->estado = $value->estado;
                                    $intentos_max->intento = $value->intento;
                                    $intentos_max->notaFinalQuiz = $value->notaFinalQuiz;
                                    $intentos_max->numeroPregunta = $cant . "." . ($i + 1);
                                    $intentos_max->puntajeMaximo = 1;
                                    $intentos_max->puntajeObtenido = 1;
                                    $intentos_max->respCorrecta = $respuesta_correcta[$i];
                                    $intentos_max->respuesta = $respuesta_alumno[$i];
                                    $intentos_max->tipoPregunta = $value->tipoPregunta;
                                    $intentos_aux[$cant . "." . ($i + 1)] = $intentos_max;
                                } else {
                                    $intentos_max = new Intentos();
                                    $intentos_max->estado = $value->estado;
                                    $intentos_max->intento = $value->intento;
                                    $intentos_max->notaFinalQuiz = $value->notaFinalQuiz;
                                    $intentos_max->numeroPregunta = $cant . "." . ($i + 1);
                                    $intentos_max->puntajeMaximo = 1;
                                    $intentos_max->puntajeObtenido = 0;
                                    $intentos_max->respCorrecta = $respuesta_correcta[$i];
                                    $intentos_max->respuesta = $respuesta_alumno[$i];
                                    $intentos_max->tipoPregunta = $value->tipoPregunta;
                                    $intentos_aux[$cant . "." . ($i + 1)] = $intentos_max;
                                }
                            }
                        }
                    }
                }
                $intent = $intentos_aux;
            }

            //Definición de umbrales
            $nota_aprobado = 60;
            $nota_suficiente = 40;


            foreach ($matriz_quiz as $value) {
                if ($value->contenido != "") {
                    $array_contenidos[] = $value->contenido;
                }
            }

            if (count($array_contenidos) > 0) {
                //limpia la matriz y deja solo los contenidos distintos
                $contenidos_matriz = array_values(array_unique($array_contenidos));
                //obtengo los link de la matriz
                foreach ($contenidos_matriz as $contenido) {
                    foreach ($matriz_quiz as $value) {
                        if (strcmp($contenido, $value->contenido) == 0) {
                            $links_matriz[] = $value->link_repaso;
                            break;
                        }
                    }
                }
                //generacion matriz de desempeño
                foreach ($contenidos_matriz as $value0) {
                    $suma_nota = 0;
                    $suma_puntajes = 0;
                    foreach ($matriz_quiz as $value1) {
                        if (strcmp($value1->contenido, $value0) == 0) {
                            $numero_pregunta = 0;
                            foreach ($intent as $value2) {
                                if ($value2->puntajeObtenido == NULL) {
                                    $value2->puntajeObtenido = 0;
                                }
                                if ($value2->intento == $max) {
                                    $numero_pregunta +=1;
                                    if ($numero_pregunta == $value1->n_pregunta) {
                                        $suma_nota += (float) $value2->puntajeObtenido;
                                        $suma_puntajes += (float) $value2->puntajeMaximo;
                                    }
                                }
                            }
                        }
                    }

                    ///Division por cero
                    if ($suma_puntajes > 0) {
                        $logro_contenido[] = ($suma_nota / $suma_puntajes) * 100;
                    } else {
                        $logro_contenido[] = 0;
                    }
                }

                //recupera el eje de cada contenido
                foreach ($contenidos_matriz as $value) {
                    $eje = "";
                    foreach ($matriz_quiz as $value1) {
                        if ($value == $value1->contenido) {
                            $eje = $value1->eje;
                        }
                    }
                    $ejes_matriz[] = $eje;
                }
                //Matri de desempeño sin eje
//        for ($index1 = 0; $index1 < count($contenidos_matriz); $index1++) {
//            echo "Contenido: ".$contenidos_matriz[$index1] ." Eje:".$ejes_matriz[$index1]." Logro:" . $logro_contenido[$index1] . "<br>";
//        } 
                $thead = "";
                $tdata = "";
                $ejes_distintos_matriz = array_values(array_unique($ejes_matriz));

                foreach ($ejes_distintos_matriz as $value) {
                    $thead = $thead . "<th>" . $value . "</th>";
                    //echo "<td style=\"vertical-align: top;\" >".$value."<br>";
                    $tdata = $tdata . "<td style=\"vertical-align: top;\ vertical-align: top; padding-top: 0px; padding-bottom: 0px; padding-left: 5px; padding-right: 5px; font-size: 13px;\"><table>";
                    for ($index = 0; $index < count($ejes_matriz); $index++) {

                        //  echo "<table>";
                        if (strcmp($value, $ejes_matriz[$index]) == 0) {

                            $tdata = $tdata . "<tr>";
                            //verde #64FE2E
                            //rojo #FE642E
                            //amarillo #F7FE2E

                            if ($logro_contenido[$index] >= $nota_aprobado) {
                                $color_celda = "#64FE2E";
                            } else if ($logro_contenido[$index] >= $nota_suficiente) {
                                $color_celda = "#F7FE2E";
                            } else {
                                $color_celda = "#FE642E";
                            }


                            $tdata = $tdata . "<td><a target='_blank' title='Presiona aquí para acceder a este contenido' rel='tooltip' href='" . $links_matriz[$index] . "'><strong>" . $contenidos_matriz[$index] . "</strong></a></td>";
                            $tdata = $tdata . "<td style=\"background: " . $color_celda . "; width: 20%;\"><strong title='Tu logro en este contenido fue de " . round($logro_contenido[$index], 2) . "%' rel='tooltip'>" . round($logro_contenido[$index], 2) . "%</strong></td>";
                            $tdata = $tdata . "</tr>";
                        }
                    }
                    $tdata = $tdata . "</table></td>";
                }
                echo "<h2>Matriz de desempeño:</h2><br>";
                echo "<h3 style=\"text-align: justify;\">A continuación te presentamos tu Matriz de Desempeño en la evaluación seleccionada. En ella  se presentan los aprendizajes esperados evaluados con sus respectivos porcentajes de logro. 
Para lograr transformar esta matriz completa a verde, debes realizar las actividades sugeridas en la Ruta de aprendizaje.
</h3>";
                echo "<div style='overflow-x: auto; overflow-y: hidden;'>";
                echo "<table style=\"width: 660px;\">";
                echo "<tr>" . $thead . "</tr>";
                echo "<tr>" . $tdata . "</tr>";
                echo "</table>";
                echo "</div>";
                echo "<br>";

                echo "<h3>El siguiente  cuadro describe los casos en que tu matriz adquiere los colores verde, amarillo o rojo</h3>";
                echo "<div align=\"center\">";
                echo "<table style=\"width: 500px;border=\"1\">";
                echo "<tr>";
                echo "<td style=\"background:#64FE2E;\">Tu logro es mayor a 60%</td>";
                echo "<td style=\"background:#F7FE2E;\"><p>Tu logro es menor de 60% <br />";
                echo "pero mayor que 40%</p></td>";
                echo "<td style=\"background:#FE642E;\">Tu logro es menor que 40%</td>";
                echo "</tr>";
                echo "</table>";
                echo "</div>";
                //Calculo la ruta de aprendizaje
                $this->rutaDeAprendizaje($contenidos_matriz, $logro_contenido, $links_matriz);
                //Imprimo el tooltip para la matriz de desempeño
                $this->tooltip();
                //
            } else {
                //echo "No se ha definido una matriz para su curso";
            }
        }
    }

    public function obtenerDatosDesempenoAjax() {
        session_start();
        if (isset($_GET['id_evaluacion']) && isset($_GET['id_curso']) && isset($_GET['id_grupo']) && isset($_SESSION['usuario']) && isset($_SESSION['lista_rank'])) {
            $curso_seleccionado = $_GET['id_curso'];
            $id_evaluacion = $_GET['id_evaluacion'];
            $grupoSeleccionado = $_GET['id_grupo'];
            if (!($id_evaluacion == "undefined")) {


                //$ranking = $_SESSION['lista_rank'];
                $ranking = DAOFactory::getRankingDAO()->queryBuscaRanking($curso_seleccionado, $id_evaluacion, $grupoSeleccionado);

                $usuario = $_SESSION['usuario'];
                $suma_notas = 0;
                $nota_quiz = 0;
                $i = 1;
                $lugar = 0;
                foreach ($ranking as $rank) {
                    $suma_notas += floor($rank->nota);
                    if ($usuario->id == $rank->userid) {
                        $nota_quiz = $rank->nota;
                        $lugar = $i;
                    }
                    $i++;
                }
                echo "<div id='rendimiento'>";

                echo "<table>";
                echo "<tr><td><img src='../views/images/icons/Academic-Hat.png' width='20px'/></td><td>Nota evaluación</td><td><strong>" . round($nota_quiz, 2) . "</strong></td></tr>";
                echo "<tr><td><img src='../views/images/icons/Map.png' width='20px'/></td><td>Lugar</td><td><strong>" . $lugar . "</strong></td></tr>";
                if (count($ranking) > 0) {
                    echo "<tr><td><img src='../views/images/icons/Globe.png' width='20px'/></td><td>Cantidad de alumnos</td><td><strong>" . count($ranking) . "</strong></td><tr>";
                    echo "<tr><td><img src='../views/images/icons/Books.png' width='20px'/></td><td>Promedio del curso</td><td><strong>" . round(($suma_notas / count($ranking)), 2) . "</strong></td><tr>";
                } else {
                    echo "<tr><td><img src='../views/images/icons/Globe.png' width='20px'/></td><td>Cantidad de alumnos</td><td><strong>0</strong></td><tr>";
                    echo "<tr><td><img src='../views/images/icons/Books.png' width='20px'/></td><td>Promedio del curso</td><td><strong>0</strong></td><tr>";
                }
                echo "</table>";
                echo "</div>";
            } else {
                echo "<table><tr><td>No se puede obtener desempeño de este curso</td></tr></table>";
            }
        }
    }

    public function obtenerAdopcionAlumnoAjax() {
        session_start();
        if (isset($_GET['id_curso']) && isset($_SESSION['usuario'])) {
            $usuario = $_SESSION['usuario'];
            $curso = $_GET['id_curso'];
            $factor = 3; //cantidad de minutos por click
            $dias = 30; //cantidad de dias a monitorear
            $categorias = "";
            $data = "";
            //$logs = DAOFactory::getLogDAO()->queryLogs(7437);
            for ($i = $dias; $i > 0; $i--) {
                $fecha_fin = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - $i, date("Y")));
                $fecha_inicial = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - $i - 1, date("Y")));
                $total = DAOFactory::getLogDAO()->queryCantidadLogsIntervaloCurso($usuario->id, $fecha_inicial, $fecha_fin, $curso);
                if ($i == 1) {
                    $categorias.="'" . $fecha_fin . "'";
                    $data.=($total * $factor);
                } else {
                    $categorias.="'" . $fecha_fin . "',";
                    $data.=($total * $factor) . ",";
                }
            }

            $titulo = "Ingresos diarios";
            $subtitulo = "";
            echo "<h2>Tiempo de uso diario: </h2><br>";
            echo "<h3 style=\"text-align: justify;\">El siguiente gráfico permite mostrar el tiempo que le has dedicado a tus actividades en la plataforma día a día.</h3>";
            echo "<div id=\"container_adopcion\" class=\"column1\" style=\" width: 670px; height: 350px;\"></div>";
            //$data, $categorias, $titulo, $subtitulo
            $this->graficoDeBarras($data, $categorias, $titulo, $subtitulo);
        }
    }

    public function obtenerImagenInstitucion() {

        session_start();
        if (isset($_SESSION['usuario'])) {
            $usuario = $_SESSION['usuario'];
            $mantenedor = DAOFactory::getMantenedorDAO()->queryBuscaImagenInstitucion($usuario->institucion);
            echo "<img src=\"" . $mantenedor->imagen . "\"/><br>";
//            if ($usuario->institucion == "USM") {
//                echo "<img src=\"../views/images/logos/usm.jpg\"/><br>";
//            }
//            if ($usuario->institucion == "UTALCA") {
//                echo "<img src=\"../views/images/logos/utalca.gif\"/><br>";
//            }
//            if ($usuario->institucion == "UVM") {
//                echo "<img src=\"../views/images/logos/uvm.jpg\"/><br><br>";
//            }
        }
    }

    public function graficoDeLinea($series, $categorias, $titulo, $subtitulo) {

        $grafico = "<script type=\"text/javascript\">
            var chart;
            $(document).ready(function() {
            chart = new Highcharts.Chart({
		chart: {
			renderTo: 'container',
			type: 'line'
		},
                credits:{
                        enabled: false
                },
		title: {
			text: '$titulo',
			x: -20 //center
		},
                subtitle: {
			text: '$subtitulo',
			x: -20
		},
		xAxis: {min: 0,
			categories: [$categorias],
                        labels: {enabled: false}
		},
		yAxis: {min: 0,
			title: {
				text: 'Porcentaje de Logro'
			},
			plotLines: [{
				value: 0,
				width: 1,
				color: '#FFFFFF'
			}] 
                        
		},
		tooltip: {
			formatter: function() {
					return '<b>Lugar: </b>'+
					this.x +': <b>Nota: </b>'+ this.y;
			}
		},
                plotOptions: {
			series: {
//				cursor: 'pointer',
//				point: {
//					events: {
//						click: function() {
//
//                                                    window.open('http://www.google.cl');
//						}
//					}
//				},
				marker: {
					lineWidth: 1
				}
			}
		},
		series: [$series]
	});
});</script>";
        echo $grafico;
    }

    public function graficoDeBarras($data, $categorias, $titulo, $subtitulo) {
        echo "<script type=\"text/javascript\">var chart;
        $(document).ready(function() {
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'container_adopcion',
			type: 'column',
                        margin: [ 50, 50, 100, 80], 
                        height: 350
		},
		title: {
			text: '$titulo'
		},
                credits:{
                        enabled: false
                },
		xAxis: {min: 0,
			categories: [
				$categorias
			],
                        labels: {
				rotation: -90,
				align: 'right'
			}
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Minutos'
			}
		},
		legend: {
			layout: 'vertical',
			backgroundColor: '#FFFFFF',
			align: 'left',
			verticalAlign: 'top',
			x: 100,
			y: 70,
			floating: true,
			shadow: true
		},
		tooltip: {
			formatter: function() {
				return ''+
					this.x +': <b>'+ this.y +'</b> (min)';
			}
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
			series: [{
			name: 'Minutos',
			data: [$data]

		}]
	});
        });</script>";
    }

    public function rutaDeAprendizaje($contenidos_matriz, $logro_contenido, $links_matriz) {
        $cantidad_links = 5;
        echo "<h2>Ruta de aprendizaje:</h2><br>";
        echo "<h3 style=\"text-align: justify;\">Para lograr transformar la matriz completa a verde, debes realizar las actividades sugeridas a continuación</h3>";
        echo "<br>";
        $i = 0;
        foreach ($contenidos_matriz as $contenido) {
            $array_notas[$contenido] = $logro_contenido[$i];
            $array_links[$contenido] = $links_matriz[$i];
            $i++;
        }


        asort($array_notas);


        $i = 1;
        foreach ($array_notas as $key => $val) {
            if ($i <= $cantidad_links) {
                echo "<a rel='tooltip' title='Presione para seguir la ruta recomendada' href='" . $array_links[$key] . "' target='_blank'>" . $key . "</a><br>";
                $i++;
            } else {
                break;
            }
        }
        echo "<br>";
    }

    public function tooltip() {

        echo" <script>
                $( document ).ready( function()
                {
                    var targets = $( '[rel~=tooltip]' ),
                    target  = false,
                    tooltip = false,
                    title   = false;
 
                    targets.bind( 'mouseenter', function()
                    {
                        target  = $( this );
                        tip     = target.attr( 'title' );
                        tooltip = $( '<div id=\"tooltip\"></div>' );
 
                        if( !tip || tip == '' )
                            return false;
 
                        target.removeAttr( 'title' );
                        tooltip.css( 'opacity', 0 )
                        .html( tip )
                        .appendTo( 'body' );
 
                        var init_tooltip = function()
                        {
                            if( $( window ).width() < tooltip.outerWidth() * 1.5 )
                                tooltip.css( 'max-width', $( window ).width() / 2 );
                            else
                                tooltip.css( 'max-width', 340 );
 
                            var pos_left = target.offset().left + ( target.outerWidth() / 2 ) - ( tooltip.outerWidth() / 2 ),
                            pos_top  = target.offset().top - tooltip.outerHeight() - 20;
 
                            if( pos_left < 0 )
                            {
                                pos_left = target.offset().left + target.outerWidth() / 2 - 20;
                                tooltip.addClass( 'left' );
                            }
                            else
                                tooltip.removeClass( 'left' );
 
                            if( pos_left + tooltip.outerWidth() > $( window ).width() )
                            {
                                pos_left = target.offset().left - tooltip.outerWidth() + target.outerWidth() / 2 + 20;
                                tooltip.addClass( 'right' );
                            }
                            else
                                tooltip.removeClass( 'right' );
 
                            if( pos_top < 0 )
                            {
                                var pos_top  = target.offset().top + target.outerHeight();
                                tooltip.addClass( 'top' );
                            }
                            else
                                tooltip.removeClass( 'top' );
 
                            tooltip.css( { left: pos_left, top: pos_top } )
                            .animate( { top: '+=10', opacity: 1 }, 50 );
                        };
 
                        init_tooltip();
                        $( window ).resize( init_tooltip );
 
                        var remove_tooltip = function()
                        {
                            tooltip.animate( { top: '-=10', opacity: 0 }, 50, function()
                            {
                                $( this ).remove();
                            });
 
                            target.attr( 'title', tip );
                        };
 
                        target.bind( 'mouseleave', remove_tooltip );
                        tooltip.bind( 'click', remove_tooltip );
                    });
                });        
        
            </script>";
    }

    public function esDiagnostico() {
        if (isset($_GET['id_evaluacion'])) {
            $id_evaluacion = $_GET['id_evaluacion'];
            $esdiagnostico = DAOFactory::getQuizDAO()->queryEsDiagnostico($id_evaluacion);
            if ((int) $esdiagnostico > 0) {
                $matriz_diagnostico = DAOFactory::getMatrizDAO()->queryBuscaMatriz($id_evaluacion);
                if (count($matriz_diagnostico) > 0) {
                    $quizes_asociado = 0;
                    foreach ($matriz_diagnostico as $idquizes) {
                        if ((int) $idquizes->idquiz_diagnostico > 0) {
                            $quizes_asociado++;
                        }
                    }
                    if ($quizes_asociado > 0) {
                        echo 1;
                    } else {
                        echo 0;
                    }
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
        }
    }

}

?>
