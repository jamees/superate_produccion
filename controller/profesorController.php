<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of profesorController
 *
 * @author JorgePaz
 */
class profesorController Extends baseController {

    //put your code here
    public function index() {
        session_start();
        if (isset($_SESSION['usuario_profesor'])) {
            $usuario_profesor = $_SESSION['usuario_profesor'];
            //Se selecciona por defecto el primer curso
            $cursos_profesor = DAOFactory::getCursosDAO()->queryBuscaCursos($usuario_profesor->id, $usuario_profesor->rolMoodle);
            $i = 0;
            $curso_seleccionado = "0";
            foreach ($cursos_profesor as $curso) {
                if ($i == 0) {
                    $curso_seleccionado = $curso->id;
                }
                $i++;
            }

            $grupos_profesor = DAOFactory::getGruposDAO()->queryBuscaGrupo($usuario_profesor->id, $curso_seleccionado);
            $grupo_seleccionado = "0";
            $i = 0;
            foreach ($grupos_profesor as $grupo) {
                if ($i == 0) {
                    $grupo_seleccionado = $grupo->id;
                }
                $i++;
            }

            $quiz_profesor = DAOFactory::getQuizDAO()->queryBuscaTodoQuiz($curso_seleccionado);
            $quiz_seleccionado = "0";
            $i = 0;
            foreach ($quiz_profesor as $quiz) {
                if ($i == 0) {
                    $quiz_seleccionado = $quiz->id;
                }
                $i++;
            }
            //Se guardan las variables en la vista
            $this->registry->template->usuario_profesor = $usuario_profesor;
            $this->registry->template->cursos_profesor = $cursos_profesor;
            $_SESSION['id_usuario_profesor'] = $usuario_profesor->id;
            $_SESSION['quiz_seleccionado'] = $quiz_seleccionado;
            $_SESSION['grupo_seleccionado'] = $grupo_seleccionado;
            $_SESSION['curso_seleccionado'] = $curso_seleccionado;

            //todos los quizes y grupos de un profesor
            $_SESSION['quiz_profesor'] = $quiz_profesor;
            $_SESSION['grupos_profesor'] = $grupos_profesor;


            //
            //Se despliega la vista indicada
            $this->registry->template->show('profesor/index_profesor');
        } else {
            $this->registry->template->blog_heading = "Para acceder a su reporte debe estar logueado";
            $this->registry->template->show('error404');
        }
    }

    public function cambiarCursoAjax() {
        if (isset($_GET['curso_seleccionado'])) {
            session_start();
            $usuario_profesor = $_SESSION['usuario_profesor'];
            $grupos_profesor = DAOFactory::getGruposDAO()->queryBuscaGrupo($usuario_profesor->id, $_GET['curso_seleccionado']);
            $grupo_seleccionado = "0";
            $i = 0;
            foreach ($grupos_profesor as $grupo) {
                if ($i == 0) {
                    $grupo_seleccionado = $grupo->id;
                }
                $i++;
            }

            $quiz_profesor = DAOFactory::getQuizDAO()->queryBuscaTodoQuiz($_GET['curso_seleccionado']);
            $quiz_seleccionado = "0";
            $i = 0;
            foreach ($quiz_profesor as $quiz) {
                if ($i == 0) {
                    $quiz_seleccionado = $quiz->id;
                }
                $i++;
            }
            $_SESSION['quiz_seleccionado'] = $quiz_seleccionado;
            $_SESSION['grupo_seleccionado'] = $grupo_seleccionado;
            $_SESSION['curso_seleccionado'] = $_GET['curso_seleccionado'];
            $_SESSION['quiz_profesor'] = $quiz_profesor;
            $_SESSION['grupos_profesor'] = $grupos_profesor;

            echo $_SESSION['quiz_seleccionado'] . "," . $_SESSION['grupo_seleccionado'] . "," . $_SESSION['curso_seleccionado'];
        }
    }

    public function obtenerGruposAjax() {
        //Se comprueba que los parametros esten definidos
        if (isset($_GET['curso_seleccionado']) && isset($_GET['id_usuario_profesor'])) {
            //$curso_seleccionado = $_GET['curso_seleccionado'];
            session_start();
            $curso_seleccionado = $_GET['curso_seleccionado'];
            $quiz_seleccionado = $_SESSION['quiz_seleccionado'];

            $id_usuario_profesor = $_SESSION['id_usuario_profesor'];
            $grupos_profesor = DAOFactory::getGruposDAO()->queryBuscaGrupo($id_usuario_profesor, $curso_seleccionado);
            if (count($grupos_profesor) > 0) {
                $i = 0;
                $tooltip = "";
                //$tooltip = "title='Estos son los grupos a los que perteneces' rel='tooltip'";
                echo "<select " . $tooltip . " id='combogrupos' style='font-size: 11px;  width: 100%;'  onchange='seleccionaGrupo(" . $quiz_seleccionado . "," . $curso_seleccionado . ",this.value," . $id_usuario_profesor . ")'>";
                foreach ($grupos_profesor as $grupo) {
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
        if (isset($_GET['curso_seleccionado']) && isset($_GET['id_usuario_profesor']) && isset($_GET['id_grupo'])) {

            session_start();
            $grupo_seleccionado = $_GET['id_grupo'];
            $curso_seleccionado = $_GET['curso_seleccionado'];
            $id_usuario_profesor = $_SESSION['id_usuario_profesor'];

            $quiz_profesor = DAOFactory::getQuizDAO()->queryBuscaTodoQuiz($curso_seleccionado);
            $i = 0;
            $tooltip = "";
            //$tooltip = "title='Presione para acceder a sus evaluaciones' rel='tooltip'";
            if (count($quiz_profesor) > 0) {
                echo "<select " . $tooltip . " id='comboquizes' style='font-size: 11px;  width: 100%;' onchange='seleccionaEvaluacion(this.value," . $curso_seleccionado . "," . $grupo_seleccionado . "," . $id_usuario_profesor . ")'>";
                //echo "<table style=\"width: 100%;\">";
                //Se muestra la lista de quiz que tiene el usuario_profesor en el curso y su nota
                foreach ($quiz_profesor as $quiz) {
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
            if ($grupoSeleccionado == 'undefined') {
                $grupoSeleccionado = "0";
            }
            $this->obtenerGraficoRanking($curso_seleccionado, $id_evaluacion, $grupoSeleccionado);
        }
    }

    public function generarListaNotas() {
        if (isset($_GET['id_evaluacion']) && isset($_GET['id_curso']) && isset($_GET['id_grupo'])) {
            session_start();
            $curso_seleccionado = $_GET['id_curso'];
            $grupoSeleccionado = $_GET['id_grupo'];
            $id_evaluacion = $_GET['id_evaluacion'];
            if ($id_evaluacion == 'undefined') {
                $id_evaluacion = "0";
            }
            if ($grupoSeleccionado == 'undefined') {
                $grupoSeleccionado = "0";
            }
            $lista_rank = DAOFactory::getRankingDAO()->queryBuscaRanking($curso_seleccionado, $id_evaluacion, $grupoSeleccionado);
            if (count($lista_rank) > 0) {
                echo "<h2>Notas de los alumnos:</h2><br>";
                echo "<h3 style=\"text-align: justify;\">La tabla mostrada a continuación,  identifica a los alumnos que han rendido la evaluación seleccionada,  presentando el ranking, nombre y nota de cada alumno.</h3>";
                echo "<div style='overflow-x: auto; overflow-y: hidden;'>";
                echo "<table style=\"width: 660px;\">";
                echo "<tr><th>Lugar</th><th>Nombre(s)</th><th>Apellido(s)</th><th>Nota</th></tr>";
                $i = 1;
                foreach ($lista_rank as $value) {
                    echo "<tr>";
                    //echo $value->nombres . " " . $value->apellidos . " " . round($value->nota, 2) . "<br>";
                    echo "<td style=\"vertical-align: top;\ vertical-align: top; padding-top: 0px; padding-bottom: 0px; padding-left: 5px; padding-right: 5px; font-size: 13px;\">" . $i . "</td>
                    <td style=\"vertical-align: top;\ vertical-align: top; padding-top: 0px; padding-bottom: 0px; padding-left: 5px; padding-right: 5px; font-size: 13px;\">" . strtoupper($value->nombres) . "</td>
                    <td style=\"vertical-align: top;\ vertical-align: top; padding-top: 0px; padding-bottom: 0px; padding-left: 5px; padding-right: 5px; font-size: 13px;\">" . strtoupper($value->apellidos) . "</td>
                    <td style=\"vertical-align: top;\ vertical-align: top; padding-top: 0px; padding-bottom: 0px; padding-left: 5px; padding-right: 5px; font-size: 13px;\">" . strtoupper(round($value->nota, 2)) . "</td>";
                    echo "</tr>";
                    $i++;
                }
                echo "</tr>";
                echo "</table>";
                echo "</div>";
                echo "<br>";
            }
        }
    }

    public function obtenerGraficoRanking($curso_seleccionado, $id_evaluacion, $grupoSeleccionado) {
        $lista_rank = DAOFactory::getRankingDAO()->queryBuscaRanking($curso_seleccionado, $id_evaluacion, $grupoSeleccionado);
        $_SESSION['lista_rank'] = $lista_rank;
        $usuario_profesor = $_SESSION['usuario_profesor'];

        $data = "";
        $categorias = "";
        $index = 0;

        foreach ($lista_rank as $rank) {
            if ($index == count($lista_rank) - 1) {
                if (!($usuario_profesor->id == $rank->userid)) {
                    $data = $data . "" . round($rank->nota, 2);
                } else {
                    $data = $data . "{ y: " . round($rank->nota, 2) . ", marker: { symbol: 'url(../views/images/icons/pin_verde.png)' } }";
                }

                $categorias = $categorias . "'" . $rank->nombres . " " . $rank->apellidos . "'";   //Profesor
            } else {

                if (!($usuario_profesor->id == $rank->userid)) {
                    $data = $data . "" . round($rank->nota, 2) . ",";
                } else {
                    $data = $data . "" . "{ y: " . round($rank->nota, 2) . ", marker: { symbol: 'url(../views/images/icons/pin_verde.png)' } },";
                }
                $categorias = $categorias . "'" . $rank->nombres . " " . $rank->apellidos . "'" . ","; // Profesor
            }
            $index++;
        }


        $quiz_profesor = $_SESSION['quiz_profesor'];
        $grupos_profesor = $_SESSION['grupos_profesor'];
        $titulo = "";
        $subtitulo = "";
        foreach ($quiz_profesor as $quiz) {
            if ($quiz->id == $id_evaluacion) {
                $titulo = $quiz->NombreQuiz;
            }
        }
        foreach ($grupos_profesor as $grupo) {
            if ($grupo->id == $grupoSeleccionado) {
                $subtitulo = $grupo->nombre;
            }
        }
        //echo "<script src=\"../views/js/modules/exporting.js\"></script>";
        echo "<h2>Ranking: </h2><br>";
        echo "<h3 style=\"text-align: justify;\">Este gráfico muestra el ranking de los alumnos que han realizado la evaluación que ha seleccionado. Al posicionar el cursor sobre los círculos del gráfico, podrá ver la información de cada alumno, además, si presiona sobre él puede ingresar al informe personalizado del alumno seleccionado.</h3>";
        echo "<div id=\"container\" class=\"column1\" style=\" width: 670px; height: 300px;\"></div>";

        $this->graficoDeLinea("{
                            name: 'Notas',
                            data: [" . $data . "]
                        }", $categorias, $titulo, $subtitulo);
    }

    public function obtenerMatrizDesempeñoNivelacionAjax() {
        session_start();
        if (isset($_GET['id_evaluacion']) && isset($_GET['id_curso']) && isset($_GET['id_grupo']) && isset($_SESSION['lista_rank'])) {

            $usuario_profesor = $_SESSION['usuario_profesor'];
            $quizSeleccionado = $_GET['id_evaluacion'];

            $contenidos_matriz = null;
            $matriz_quiz = DAOFactory::getMatrizDAO()->queryBuscaMatriz($quizSeleccionado);
            if (count($matriz_quiz) > 0) {
                $ranking = $_SESSION['lista_rank'];
                $intentos_alumnos = array();

                $contiene_sub_pregunta = 0;
                foreach ($matriz_quiz as $value) {
                    if ($value->contenido != "") {
                        $contenidos_matriz[] = $value->contenido;
                        if (!(strcmp($value->tipopregunta, "") == 0)) {
                            $contiene_sub_pregunta = 1;
                        }
                    }
                }
                $intentos_aux = array();

//                echo ">> " . count($ranking) . "<br>";

                foreach ($ranking as $rank) {
                    //echo ">> ".$rank->userid."<br>";
                    $intent = DAOFactory::getIntentosDAO()->queryBuscaIntentos($quizSeleccionado, $rank->userid);
                    $max = 0;
                    $max_nota = 0;

                    for ($i = 0; $i < count($intent); $i++) {
                        if ($intent[$i]->notaFinalQuiz >= $max_nota) {
                            $max = $intent[$i]->intento;
                            $intent[$i]->username = $rank->userid;
                            //echo "intent: ".$intent[$i]->username."<br>";
                            if ($intent[$i]->puntajeObtenido == NULL) {
                                $intent[$i]->puntajeObtenido = 0;
                                $intent[$i]->username = $rank->userid;
                            }
                            $max_nota = $intent[$i]->notaFinalQuiz;
                        }
                    }

                    if (count($intent) > 0) {
                        $cant = 0;

                        foreach ($intent as $intento) {
                            if ($intento->intento == $max) {
                                // Se realiza para cambiar a la modalidad con sub preguntas
                                if ($contiene_sub_pregunta == 1) {
                                    $cant +=1;
                                    $intentos_max = new Intentos();
                                    $intentos_max->estado = $intento->estado;
                                    $intentos_max->intento = $intento->intento;
                                    $intentos_max->notaFinalQuiz = $intento->notaFinalQuiz;
                                    $intentos_max->numeroPregunta = $cant;
                                    $intentos_max->puntajeMaximo = $intento->puntajeMaximo;
                                    $intentos_max->puntajeObtenido = $intento->puntajeObtenido;
                                    $intentos_max->respCorrecta = $intento->respCorrecta;
                                    $intentos_max->respuesta = $intento->respuesta;
                                    $intentos_max->tipoPregunta = $intento->tipoPregunta;
                                    //$intentos_aux->username = $rank->userid;
//                                    echo "1 >> ".$intentos_aux->username."<br>";
//                                    echo "1 >> ".$rank->userid."<br>";
                                    $intentos_aux[] = $intentos_max;

                                    if ($intento->tipoPregunta == "multianswer") {

                                        $respuesta_alumno = explode(";", $intento->respuesta);
                                        $respuesta_correcta = explode(";", $intento->respCorrecta);

                                        for ($i = 0; $i < count($respuesta_correcta); $i++) {
                                            $intentos_max = new Intentos();
                                            $intentos_max->estado = $intento->estado;
                                            $intentos_max->intento = $intento->intento;
                                            $intentos_max->notaFinalQuiz = $intento->notaFinalQuiz;
                                            $intentos_max->numeroPregunta = $cant . "." . ($i + 1);
                                            if (strcmp($respuesta_correcta[$i], $respuesta_alumno[$i]) == 0) {
                                                $intentos_max->puntajeMaximo = 1;
                                                $intentos_max->puntajeObtenido = 1;
                                            } else {

                                                $intentos_max->puntajeMaximo = 1;
                                                $intentos_max->puntajeObtenido = 0;
                                            }
                                            $intentos_max->respCorrecta = $respuesta_correcta[$i];
                                            $intentos_max->respuesta = $respuesta_alumno[$i];
                                            $intentos_max->tipoPregunta = $intento->tipoPregunta;
                                            //$intentos_aux->username = $rank->userid;
//                                            echo "1.1 >> ".$intentos_aux->username."<br>";
//                                            echo "1.1 >> ".$rank->userid."<br>";
                                            $intentos_aux[] = $intentos_max;
                                        }
                                    }
                                } else {
                                    $cant +=1;
                                    $intento->numeroPregunta = $cant;
                                    $intentos_aux[] = $intento;
                                }
                            }
                        }
                    }
                }
                $intentos_alumnos = $intentos_aux;
                $into = $intentos_aux;

                //Definición de umbrales
                $nota_aprobado = 60;
                $nota_suficiente = 40;
                if (count($contenidos_matriz) > 0) {
                    //limpia la matriz y deja solo los contenidos distintos
                    $contenidos_matriz = array_values(array_unique($contenidos_matriz));
                    //obtengo los link de la matriz
                    $quizes_diagnostico = null;
                    foreach ($contenidos_matriz as $contenido) {
                        foreach ($matriz_quiz as $value) {
                            if (strcmp($contenido, $value->contenido) == 0) {
                                $links_matriz[] = $value->link_repaso;
                                $quizes_diagnostico[] = $value->idquiz_diagnostico;
                                break;
                            }
                        }
                    }

                    //generacion matriz de desempeño
                    $id_matriz_quiz_diagnostico = 0;
                    $logro_contenido_avance = array();
                    foreach ($contenidos_matriz as $contenido_m) {
//                        echo "<h2>" . $contenido_m . "</h2>";
                        $suma_nuevo_logro_grupal = 0;
                        $suma_nota = 0;
                        $suma_puntajes = 0;
                        $primer_contenido_distinto = 0;
                        foreach ($matriz_quiz as $pregunta) {

                            if (strcmp($pregunta->contenido, $contenido_m) == 0) {
                                foreach ($intentos_alumnos as $pregunta_intento) {
                                    if ($pregunta_intento->numeroPregunta == $pregunta->n_pregunta) {
                                        $suma_nota += (float) $pregunta_intento->puntajeObtenido;
                                        $suma_puntajes += (float) $pregunta_intento->puntajeMaximo;

                                        if ($primer_contenido_distinto == 0) {
                                            //
                                            $suma_nota2 = 0;
                                            $suma_puntajes2 = 0;
                                            $logro_individual_contenido = 0;
                                            foreach ($matriz_quiz as $value1) {
                                                if (strcmp($value1->contenido, $contenido_m) == 0) {
                                                    $numero_pregunta = 0;
                                                    foreach ($into as $value2) {
                                                        if ($value2->username == $pregunta_intento->username) {
                                                            if ($value2->puntajeObtenido == NULL) {
                                                                $value2->puntajeObtenido = 0;
                                                            }
                                                            if ($value2->intento == $pregunta_intento->intento) {
                                                                $numero_pregunta +=1;
                                                                if ($numero_pregunta == $value1->n_pregunta) {
                                                                    $suma_nota2 += (float) $value2->puntajeObtenido;
                                                                    //echo "idusuario " . $value2->username . " " . $value2->puntajeObtenido . "<br>";
                                                                    $suma_puntajes2 += (float) $value2->puntajeMaximo;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            if ($suma_puntajes2 > 0) {
                                                $logro_individual_contenido = ($suma_nota2 / $suma_puntajes2) * 100;
                                            } else {
                                                $logro_individual_contenido = 0;
                                            }

//                                            echo "quiz_asociado a contenido : " . $contenido_m . " es " . $quizes_diagnostico[$id_matriz_quiz_diagnostico] . " alumno " . $pregunta_intento->username . "<br>";
                                            $nota_quiz_ind = DAOFactory::getQuizDAO()->queryBuscaNotaQuiz($quizes_diagnostico[$id_matriz_quiz_diagnostico], $pregunta_intento->username);
                                            if ($nota_quiz_ind != "") {
//                                                echo "Nota alumno en quiz asociado: " . $nota_quiz_ind . "<br>";
//                                                echo "logro obtenido en diagnostico: " . $logro_individual_contenido . "<br>";
//                                                echo "nuevo logro avance: " . $nota_quiz_ind . "<br>";
                                                $suma_nuevo_logro_grupal +=(float) $nota_quiz_ind;
                                            } else {
//                                                echo "Nota alumno en quiz asociado: NO RENDIDO<br>";
//                                                echo "logro obtenido en diagnostico: " . $logro_individual_contenido . "<br>";
//                                                echo "nuevo logro avance: " . $logro_individual_contenido . "<br>";
                                                $suma_nuevo_logro_grupal +=(float) $logro_individual_contenido;
                                            }
                                        }
                                    }
                                }
                                $primer_contenido_distinto = 1;
                            }
                        }
//                        echo "-------------------------------<br>";
//                        echo "logro grupal " . $suma_nuevo_logro_grupal . "<br>";
//                        echo "logro grupal " . ($suma_nuevo_logro_grupal/count($ranking)) . "<br>";
//                        echo "-------------------------------<br>";
                        ///Division por cero
                        if ($suma_puntajes > 0) {
                            $logro_contenido[] = ($suma_nota / $suma_puntajes) * 100;
                        } else {
                            $logro_contenido[] = 0;
                        }

                        if ($suma_nuevo_logro_grupal > 0) {
                            $logro_contenido_avance[] = $suma_nuevo_logro_grupal / count($ranking);
                        } else {
                            $logro_contenido_avance[] = 0;
                        }

                        //echo ">> quiz_asociado a contenido : ".$contenido_m." es ".$quizes_diagnostico[$id_matriz_quiz_diagnostico]."<br>";
                        $id_matriz_quiz_diagnostico++;
                        //echo "------------------------------------<br>";
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


                                if ($logro_contenido_avance[$index] >= $nota_aprobado) {
                                    $color_celda_d = "#64FE2E";
                                } else if ($logro_contenido_avance[$index] >= $nota_suficiente) {
                                    $color_celda_d = "#F7FE2E";
                                } else {
                                    $color_celda_d = "#FE642E";
                                }

                                $tdata_d = $tdata_d . "<td><a target='_blank' title='Presiona aquí para acceder a este contenido' rel='tooltip' href='" . $links_matriz[$index] . "'><strong>" . $contenidos_matriz[$index] . "</strong></a></td>";
                                $tdata_d = $tdata_d . "<td style=\"background: " . $color_celda_d . "; width: 20%;\"><strong title='Tu logro en este contenido fue de " . round($logro_contenido_avance[$index], 2) . "%' rel='tooltip'>" . round($logro_contenido_avance[$index], 2) . "%</strong></td>";
                                $tdata_d = $tdata_d . "</tr>";
                            }
                        }
                        $tdata = $tdata . "</table></td>";
                        $tdata_d = $tdata_d . "</table></td>";
                    }

                    echo "<h2>Matriz de desempeño:</h2><br>";
                    echo "<h3 style=\"text-align: justify;\">A continuación podrá ver la Matriz de Desempeño de la prueba de diagnóstico realizada por sus estudiantes.
                            Esta matriz se organiza por ejes temáticos y unidades de aprendizaje esperados. Según el desempeño promedio del curso en cada unidad de aprendizaje, las celdas se presentarán en rojo, amarillo o verde respectivamente. 
                            </h3>";
                    echo "<br>";
                    echo "<h3 style=\"text-align: justify;\">El objetivo de esta matriz es identificar los aprendizajes esperados más descendidos y realizar las actividades asociadas a ellos en la plataforma. De esta  manera dejarán de estar en rojo y en un futuro reporte podrán verse en verde.
                            Cada estudiante recibe esta información de forma individual junto con el listado de actividades que deben realizar semanalmente.
                            </h3>";
                    echo "<div style='overflow-x: auto; overflow-y: hidden;'>";
                    echo "<table style=\"width: 660px;\">";
                    echo "<tr>" . $thead . "</tr>";
                    echo "<tr>" . $tdata . "</tr>";
                    echo "</table>";
                    echo "</div>";
                    echo "<br>";

                    echo "<h2>Matriz de avance:</h2><br>";
                    echo "<h3 style=\"text-align: justify;\">La siguiente Matriz de avance refleja el progreso que han logrado sus estudiantes en los aprendizajes esperados luego de seguir sus Rutas de aprendizajes individuales sugeridas de cada uno de ellos. Puede realizar la comparación respecto de la matriz anterior, para verificar que aprendizajes esperados han sido mejorado por sus alumnos.</h3>";
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


                    //**************************************************************************************************************************************
                    //Cambiar por los alumnos que no rindieron en un contenido
                    //$this->rutaDeAprendizaje($contenidos_matriz, $logro_contenido, $links_matriz);
                    //**************************************************************************************************************************************
                    //Imprimo el tooltip para la matriz de desempeño
                    $this->tooltip();
                    //
                } else {
                    //echo "No se ha definido una matriz para su curso";
                }
            }
        }
    }

    public function obtenerAdopcionAlumnosAjax() {
        session_start();
        if (isset($_GET['id_curso']) && isset($_SESSION['usuario']) && isset($_SESSION['lista_rank'])) {
            //$usuario = $_SESSION['usuario'];
            $lista_rank = $_SESSION['lista_rank'];
            $curso = $_GET['id_curso'];
            $factor = 3; //cantidad de minutos por click
            $dias = 30; //cantidad de dias a monitorear
            $categorias = "";
            $data = "";
            //$logs = DAOFactory::getLogDAO()->queryLogs(7437);
            for ($i = $dias; $i > 0; $i--) {
                $fecha_fin = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - $i, date("Y")));
                $fecha_inicial = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - $i - 1, date("Y")));
                $total = 0;
                foreach ($lista_rank as $user) {
                    $total += DAOFactory::getLogDAO()->queryCantidadLogsIntervaloCurso($user->userid, $fecha_inicial, $fecha_fin, $curso);
                }
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
            echo "<h3 >El siguiente gráfico permite mostrar el tiempo total que le han dedicado sus estudiantes a las  actividades en la plataforma día a día.</h3>";
            echo "<div id=\"container_adopcion\" class=\"column1\" style=\" width: 670px; height: 350px;\"></div>";
            //$data, $categorias, $titulo, $subtitulo
            $this->graficoDeBarras($data, $categorias, $titulo, $subtitulo);
        }
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

    public function obtenerMatrizDesempenoAjax() {
        session_start();
        if (isset($_GET['id_evaluacion']) && isset($_GET['id_curso']) && isset($_GET['id_grupo']) && isset($_SESSION['lista_rank'])) {

            $usuario_profesor = $_SESSION['usuario_profesor'];
            $quizSeleccionado = $_GET['id_evaluacion'];

            $contenidos_matriz = null;
            $matriz_quiz = DAOFactory::getMatrizDAO()->queryBuscaMatriz($quizSeleccionado);
            if (count($matriz_quiz) > 0) {
                $ranking = $_SESSION['lista_rank'];
                $intentos_alumnos = array();

                $contiene_sub_pregunta = 0;
                foreach ($matriz_quiz as $value) {
                    if ($value->contenido != "") {
                        $contenidos_matriz[] = $value->contenido;
                        if (!(strcmp($value->tipopregunta, "") == 0)) {
                            $contiene_sub_pregunta = 1;
                        }
                    }
                }
                $intentos_aux = array();
                foreach ($ranking as $rank) {

                    $intent = DAOFactory::getIntentosDAO()->queryBuscaIntentos($quizSeleccionado, $rank->userid);
                    //obtengo el maximo intento
                    $max = 0;
                    $max_nota = 0;

                    for ($i = 0; $i < count($intent); $i++) {
                        if ($intent[$i]->notaFinalQuiz >= $max_nota) {
                            $max = $intent[$i]->intento;
                            if ($intent[$i]->puntajeObtenido == NULL) {
                                $intent[$i]->puntajeObtenido = 0;
                            }
                            $max_nota = $intent[$i]->notaFinalQuiz;
                        }
                    }

                    if (count($intent) > 0) {
                        $cant = 0;

                        foreach ($intent as $intento) {
                            if ($intento->intento == $max) {
                                // Se realiza para cambiar a la modalidad con sub preguntas
                                if ($contiene_sub_pregunta == 1) {
                                    $cant +=1;
                                    $intentos_max = new Intentos();
                                    $intentos_max->estado = $intento->estado;
                                    $intentos_max->intento = $intento->intento;
                                    $intentos_max->notaFinalQuiz = $intento->notaFinalQuiz;
                                    $intentos_max->numeroPregunta = $cant;
                                    $intentos_max->puntajeMaximo = $intento->puntajeMaximo;
                                    $intentos_max->puntajeObtenido = $intento->puntajeObtenido;
                                    $intentos_max->respCorrecta = $intento->respCorrecta;
                                    $intentos_max->respuesta = $intento->respuesta;
                                    $intentos_max->tipoPregunta = $intento->tipoPregunta;
                                    $intentos_aux[] = $intentos_max;

                                    if ($intento->tipoPregunta == "multianswer") {

                                        $respuesta_alumno = explode(";", $intento->respuesta);
                                        $respuesta_correcta = explode(";", $intento->respCorrecta);

                                        for ($i = 0; $i < count($respuesta_correcta); $i++) {
                                            $intentos_max = new Intentos();
                                            $intentos_max->estado = $intento->estado;
                                            $intentos_max->intento = $intento->intento;
                                            $intentos_max->notaFinalQuiz = $intento->notaFinalQuiz;
                                            $intentos_max->numeroPregunta = $cant . "." . ($i + 1);
                                            if (strcmp($respuesta_correcta[$i], $respuesta_alumno[$i]) == 0) {
                                                $intentos_max->puntajeMaximo = 1;
                                                $intentos_max->puntajeObtenido = 1;
                                            } else {

                                                $intentos_max->puntajeMaximo = 1;
                                                $intentos_max->puntajeObtenido = 0;
                                            }
                                            $intentos_max->respCorrecta = $respuesta_correcta[$i];
                                            $intentos_max->respuesta = $respuesta_alumno[$i];
                                            $intentos_max->tipoPregunta = $intento->tipoPregunta;
                                            $intentos_aux[] = $intentos_max;
                                        }
                                    }
                                } else {
                                    $cant +=1;
                                    $intento->numeroPregunta = $cant;
                                    $intentos_aux[] = $intento;
                                }
                            }
                        }
                    }
                }
                $intentos_alumnos = $intentos_aux;

                //Definición de umbrales
                $nota_aprobado = 60;
                $nota_suficiente = 40;
                if (count($contenidos_matriz) > 0) {
                    //limpia la matriz y deja solo los contenidos distintos
                    $contenidos_matriz = array_values(array_unique($contenidos_matriz));
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
                    foreach ($contenidos_matriz as $contenido_m) {
                        $suma_nota = 0;
                        $suma_puntajes = 0;
                        foreach ($matriz_quiz as $pregunta) {
                            if (strcmp($pregunta->contenido, $contenido_m) == 0) {
                                // $numero_pregunta = 0;
                                foreach ($intentos_alumnos as $pregunta_intento) {
                                    //$numero_pregunta +=1;
                                    //echo " ".$pregunta_intento->numeroPregunta." ".$pregunta->n_pregunta."<br>";
                                    if ($pregunta_intento->numeroPregunta == $pregunta->n_pregunta) {
                                        //if ($numero_pregunta == $pregunta->n_pregunta) {
                                        $suma_nota += (float) $pregunta_intento->puntajeObtenido;
                                        $suma_puntajes += (float) $pregunta_intento->puntajeMaximo;
                                        // echo " ".$suma_nota." ".$suma_puntajes."<br>";
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

                    $thead = "";
                    $tdata = "";
                    $ejes_distintos_matriz = array_values(array_unique($ejes_matriz));

                    foreach ($ejes_distintos_matriz as $value) {
                        $thead = $thead . "<th>" . $value . "</th>";
                        //echo "<td style=\"vertical-align: top;\" >".$value."<br>";
                        $tdata = $tdata . "<td style=\"vertical-align: top;\ vertical-align: top; padding-top: 0px; padding-bottom: 0px; padding-left: 5px; padding-right: 5px; font-size: 13px;\"><table>";
                        for ($index = 0; $index < count($ejes_matriz); $index++) {

                            if (strcmp($value, $ejes_matriz[$index]) == 0) {

                                $tdata = $tdata . "<tr>";

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
                    echo "<h3 style=\"text-align: justify;\">A continuación podrá ver la Matriz de Desempeño de la evaluación seleccionada y realizada por sus estudiantes.
Esta matriz se organiza por ejes temáticos y unidades de aprendizaje esperados. Según el desempeño promedio del curso en cada aprendizaje esperado, las celdas se presentarán en rojo, amarillo o verde respectivamente. 
</h3><br>";
                    echo "<h3 style=\"text-align: justify;\">El objetivo de esta matriz es identificar los aprendizajes esperados más descendidos y realizar las actividades asociadas a ellos en la plataforma. De esta  manera dejarán de estar en rojo y en un futuro reporte podrán verse en verde.
Cada estudiante recibe esta información de forma individual junto con el listado de actividades que deben realizar semanalmente.
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
                    //**************************************************************************************************************************************
                    //Cambiar por los alumnos que no rindieron en un contenido
                    //$this->rutaDeAprendizaje($contenidos_matriz, $logro_contenido, $links_matriz);
                    //**************************************************************************************************************************************
                    //Imprimo el tooltip para la matriz de desempeño
                    $this->tooltip();
                    //
                } else {
                    //echo "No se ha definido una matriz para su curso";
                }
            }
        }
    }

    public function obtenerDatosDesempenoAjax() {
        session_start();
        if (isset($_GET['id_evaluacion']) && isset($_GET['id_curso']) && isset($_GET['id_grupo']) && isset($_SESSION['usuario_profesor']) && isset($_SESSION['lista_rank'])) {


            $usuario_profesor = $_SESSION['usuario_profesor'];
            $curso_seleccionado = $_GET['id_curso'];
            $id_evaluacion = $_GET['id_evaluacion'];
            $grupoSeleccionado = $_GET['id_grupo'];
            if (!($id_evaluacion == "undefined") && !($grupoSeleccionado == "undefined")) {
                //$ranking = $_SESSION['lista_rank'];
                $ranking = DAOFactory::getRankingDAO()->queryBuscaRanking($curso_seleccionado, $id_evaluacion, $grupoSeleccionado);

                $suma_notas = 0;
                $nota_quiz = 0;
                //echo "<tr><td colspan='2'>Ranking de profesors de la evaluacion seleccionada</td></tr>";
                // echo "<tr><td>Nombre</td><td>Apellido</td><td>usuario_profesor</td><td>Id</td><td>Nota</td></tr>";
                $i = 1;
                $lugar = 0;
                foreach ($ranking as $rank) {
                    //  echo "<tr><td>" . $rank->nombres . "</td><td>" . $rank->apellidos . "</td><td>" . $rank->usuario_profesor . "</td><td>" . $rank->userid . "</td><td>" . round($rank->nota, 2) . "</td></tr>";
                    $suma_notas += floor($rank->nota);
                    if ($usuario_profesor->id == $rank->userid) {
                        $nota_quiz = $rank->nota;
                        $lugar = $i;
                    }
                    $i++;
                }
                $todosalumnosgrupo = DAOFactory::getPersonasDAO()->queryBuscaAlumnosGrupo($grupoSeleccionado, $curso_seleccionado);
                echo "<div id='rendimiento'>";

                echo "<table>";
                // echo "<tr><td><img src='../views/images/icons/Academic-Hat.png' width='20px'/></td><td>Nota evaluación</td><td><strong>" . round($nota_quiz, 2) . "</strong></td></tr>";
                // echo "<tr><td><img src='../views/images/icons/Map.png' width='20px'/></td><td>Lugar</td><td><strong>" . $lugar . "</strong></td></tr>";
                if (count($ranking) > 0) {
                    echo "<tr><td><img src='../views/images/icons/Globe.png' width='20px'/></td><td>Alumnos del grupo</td><td><strong>" . count($todosalumnosgrupo) . "</strong></td><tr>";
                    echo "<tr><td><img src='../views/images/icons/Globe.png' width='20px'/></td><td>Alumnos que rindieron evaluación</td><td><strong>" . count($ranking) . "</strong></td><tr>";
                    echo "<tr><td><img src='../views/images/icons/Books.png' width='20px'/></td><td>Promedio del curso</td><td><strong>" . round(($suma_notas / count($ranking)), 2) . "</strong></td><tr>";
                } else {
                    echo "<tr><td><img src='../views/images/icons/Globe.png' width='20px'/></td><td>Alumnos del grupo</td><td><strong>" . count($todosalumnosgrupo) . "</strong></td><tr>";
                    echo "<tr><td><img src='../views/images/icons/Globe.png' width='20px'/></td><td>Alumnos que asistieron</td><td><strong>0</strong></td><tr>";
                    echo "<tr><td><img src='../views/images/icons/Books.png' width='20px'/></td><td>Promedio del curso</td><td><strong>0</strong></td><tr>";
                }
                echo "</table>";
                echo "</div>";
            } else {
                echo "<table><tr><td>No se puede obtener desempeño de este curso</td></tr></table>";
            }
        }
    }

    public function obtenerListaAlumnos() {
        if (isset($_SESSION['lista_rank']) && isset($_SESSION['usuario_profesor'])) {
            $ranking = $_SESSION['lista_rank'];
            $usuario_profesor = $_SESSION['usuario_profesor'];
        }
    }

    public function obtenerImagenInstitucionAjax() {

        session_start();
        if (isset($_SESSION['usuario_profesor'])) {
            $usuario_profesor = $_SESSION['usuario_profesor'];
            $mantenedor = DAOFactory::getMantenedorDAO()->queryBuscaImagenInstitucion($usuario_profesor->institucion);
            echo "<img src=\"" . $mantenedor->imagen . "\"/><br>";
//            if ($usuario_profesor->institucion == "USM") {
//                echo "<img src=\"../views/images/logos/usm.jpg\"/><br>";
//            }
//            if ($usuario_profesor->institucion == "UTALCA") {
//                echo "<img src=\"../views/images/logos/utalca.gif\"/><br>";
//            }
//            if ($usuario_profesor->institucion == "UVM") {
//                echo "<img src=\"../views/images/logos/uvm.jpg\"/><br><br>";
//            }
        }
    }

    public function obtenerLinkAlumnoAjax() {
        session_start();
        if (isset($_GET['posicion_alumno']) && isset($_SESSION['lista_rank'])) {
            $i = 0;
            $ranking = $_SESSION['lista_rank'];
            foreach ($ranking as $rank) {
                if ($i == $_GET['posicion_alumno']) {
                    $valor = $this->encrypter->encode("vista=profesor&username=" . $rank->usuario);
                    echo "../enrutador/index?params=" . $valor;
                }
                $i++;
            }
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
					return '<b>Nombre: </b>'+
					this.x +': <b>Nota: </b>'+ this.y;
			}
		},
                plotOptions: {
			series: {
				cursor: 'pointer',
				point: {
					events: {
						click: function() {
                                                    linkAlumno(this.x);
                                                    //alert($('#click_alumno').html());
                                                    //new Boxy($('#click_alumno').html(), {title: this.x});;
                                                   
						}
					}
				},
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

    public function rutaDeAprendizaje($contenidos_matriz, $logro_contenido, $links_matriz) {
        $cantidad_links = 3;
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
