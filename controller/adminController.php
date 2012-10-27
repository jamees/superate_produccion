<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of adminController
 *
 * @author JorgePaz
 */
class adminController Extends baseController {

//put your code here
    public function login() {
        
    }

    public function index() {
        session_start();
        $usuario_admin = $_SESSION['usuario_admin'];
        if (isset($_SESSION['usuario_admin'])) {
            $this->registry->template->usuario_admin = $usuario_admin;
            $this->registry->template->show('admin/index_admin');
        } else {
            $this->registry->template->blog_heading = "Para acceder a su reporte debe estar logueado";
            $this->registry->template->show('error404');
        }
    }

    public function tipoPreguntas() {
        $id_curso = $_GET['curso_id'];
        echo '<br> 
            <h2>Seleccion el tipo de preguntas que contienen los quiz del curso:</h2><br>
            <input type="radio" name="group1" value="1" onclick="TipoPregSelect(this.value,' . $id_curso . ')"> Con multianswer <br>
            <input type="radio" name="group1" value="0" onclick="TipoPregSelect(this.value,' . $id_curso . ')"> Sin multianswer <br><br>';
    }

    public function quizesTodos() {
        $id_curso = $_GET['curso_id'];
        $multians = $_GET['multianswer'];
        $quizes = DAOFactory::getQuizDAO()->queryBuscaTodoQuiz($id_curso);
        echo '<h2>Seleccion el quiz al que desea ingresar el contenido de la matriz de desempeño:</h2><br>';
        echo '<select id="comboquizes" name="comboquizes" onchange="BotonGenerarMatriz(this.value,' . $multians . ')")">';
        echo '<option value="-2">Seleccione un Quiz</option>';
        foreach ($quizes as $quiz) {
            echo '<option value="' . $quiz->id . '" >' . $quiz->NombreQuiz . '</option>';
        }
        echo '</select><br><br>';
    }

    public function botonMatriz() {
        $id_quiz = $_GET['quiz_id'];
        $multianswer = $_GET['multianswer'];
        $es_diagnostico = $_GET['es_diagnostico'];
        echo '<input class="boton_form" value="Generar Matriz" onclick="confirmarMatriz(' . $id_quiz . ',' . $multianswer . ',' . $es_diagnostico . ')"><br><br>';
    }

    public function validarMatrices() {
        $id_quiz = $_GET['quiz_id'];
        $multianswer = (int) $_GET['multianswer'];
        $matriz = DAOFactory::getMatrizDAO()->queryBuscaMatriz($id_quiz);
        $preguntas = DAOFactory::getPreguntasDAO()->queryBuscaPreguntasQuiz($id_quiz);
        if ($multianswer == 0) {

            if (count($matriz) > 0) {
                if (count($matriz) == count($preguntas)) {
//solo se muestra
                    echo "1";
                } else {
//echo "<b> SI se encontro matriz de desempeño, pero con diferencias en el numero de preguntas " . count($matriz) . " en reportes y " . count($preguntas) . " en la plataforma, por lo cual se reiniciara la matriz</b>";
                    echo "2";
                }
            } else {
//echo "<b> NO encontro matriz de desempeño, en un momento se generara y debera completarla</b>";
                echo "3";
            }
        } else {
            /* se genera matriz con subpreguntas */
//echo '<b>Se desplegara matriz con subpreguntas</b><br>';
            $cant = 0;
            $preguntas_array = array();
            foreach ($preguntas as $value) {
                $cant += 1;
                $preguntas_aux = new Preguntas();
                $preguntas_aux->numeropregunta = $cant;
                $preguntas_aux->tipopregunta = $value->tipopregunta;
                $preguntas_aux->correcto = "";
                $preguntas_aux->respuestaAlumno = "";
                $preguntas_array[$cant] = $preguntas_aux;
                if ($value->tipopregunta == "multianswer") {
                    $subpreguntas = explode(";", $value->correcto);
                    for ($i = 0; $i < count($subpreguntas); $i++) {
                        $preguntas_aux = new Preguntas();
                        $preguntas_aux->numeropregunta = $cant . "." . ($i + 1);
                        $preguntas_aux->tipopregunta = "subpregunta";
                        $preguntas_aux->correcto = "";
                        $preguntas_aux->respuestaAlumno = "";
                        $preguntas_array[$cant . "." . ($i + 1)] = $preguntas_aux;
                    }
                }
            }
            $preguntas = $preguntas_array;
            if (count($matriz) > 0) {
                if (count($matriz) == count($preguntas)) {
//se muestra la matriz
//si el numero de preguntas de los intentos es diferentes a la de la matriz, se reinica la matriz de reportes
//y se crea con la nueva informacion
                    echo "4";
                } else {
//echo "<b> SI se encontro matriz de desempeño, pero con diferencias en el numero de preguntas " . count($matriz) . " en reportes y " . count($preguntas) . " en la plataforma, por lo cual se reiniciara la matriz</b>";
                    echo "5";
                }
//no se encentra matriz en reportes   
            } else {
//echo "<b> NO encontro matriz de desempeño, en un momento se generara y debera completarla</b>";
                echo "6";
            }
        }
    }

    public function preguntasQuiz() {
        $id_quiz = $_GET['quiz_id'];
        $multianswer = (int) $_GET['multianswer'];
        $es_diagnostico = $_GET['es_diagnostico'];
        $matriz = DAOFactory::getMatrizDAO()->queryBuscaMatriz($id_quiz);
        $preguntas = DAOFactory::getPreguntasDAO()->queryBuscaPreguntasQuiz($id_quiz);

        print_r("Diagnostico? " . $es_diagnostico . "<br>");
        if ($multianswer == 0) {
            /* se genera matriz sin subpreguntas
             */
// echo '<b>Se desplegara matriz sin subpreguntas</b><br>';
            if (count($matriz) > 0) {
                if (count($matriz) == count($preguntas)) {
//echo "<b> SI se encontro matriz de desempeño con " . count($matriz) . " preguntas (En plataforma y Sistema reportes)</b>";
                    $cantidad = 0;
                    echo "<table border='1' class='TablaDatos' id='TablaDatos'>";
                    $cantidad = 0;
                    foreach ($matriz as $value) {
                        if ($cantidad == 0) {
                            if ((int) $es_diagnostico == 1) {
                                echo "<tr><td>idDiagnostico</td><td># Pregunta</td><td>Contenido</td><td>Eje</td><td>Link quiz</td><td>idQuiz</td><td>Ingrersar contenido</td></tr>";
                            } else {
                                echo "<tr><td>idQuiz</td><td># Pregunta</td><td>Contenido</td><td>Eje</td><td>Link repaso</td><td>Ingrersar contenido</td></tr>";
                            }
                        }
                        $cantidad += 1;
                        if ((int) $es_diagnostico == 1) {
                            echo "<tr><td><input id='id_diagnostico_" . $cantidad . "' value='" . $value->idquiz . "' size = '4' disabled='disabled'/></td><td><input id='numero_preg_" . $cantidad . "' value='" . $value->n_pregunta . "' size = '4' disabled='disabled'/></td><td> <input id='contenido_" . $cantidad . "' value='" . $value->contenido . "' maxlength = '250'/> </td><td> <input id='eje_" . $cantidad . "' value='" . $value->eje . "' maxlength = '250'/> </td><td> <input id='link_" . $cantidad . "' value='" . $value->link_repaso . "' maxlength = '250' /> </td><td> <input id='id_quiz_" . $cantidad . "' value='" . $value->idquiz_diagnostico . "' /> </td><td><table style='margin:0;'><tr><td><button class='boton_form' onclick='actualizaFilaMatrizDiagnostico(" . $cantidad . ")'>Ingresar</button></td><td> <div id='res_" . $cantidad . "'></div></td></tr></table> </td></tr>";
                        } else {
                            echo "<tr><td><input id='id_quiz_" . $cantidad . "' value='" . $value->idquiz . "' size = '4' disabled='disabled'/></td><td><input id='numero_preg_" . $cantidad . "' value='" . $value->n_pregunta . "' size = '4' disabled='disabled'/></td><td> <input id='contenido_" . $cantidad . "' value='" . $value->contenido . "' maxlength = '250'/> </td><td> <input id='eje_" . $cantidad . "' value='" . $value->eje . "' maxlength = '250'/> </td><td> <input id='link_" . $cantidad . "' value='" . $value->link_repaso . "' maxlength = '250'/> </td><td><table style='margin:0;'><tr><td><button class='boton_form' onclick='actualizaFila(" . $cantidad . ")'>Ingresar</button></td><td> <div id='res_" . $cantidad . "'></div></td></tr></table> </td></tr>";
                        }
                    }
                    echo "</table><br>";
                } else {
// echo "<b> SI se encontro matriz de desempeño, pero con diferencias en el numero de preguntas " . count($matriz) . " en reportes y " . count($preguntas) . " en la plataforma, por lo cual se reiniciara la matriz</b>";
                    foreach ($matriz as $value) {
//verificar eliminado
                        $deleted = DAOFactory::getMatrizDAO()->queryDeleteMatriz($value->idquiz);
                    }
                    echo "<table border='1' class='TablaDatos' id='TablaDatos'>";
                    $cantidad = 0;
                    foreach ($preguntas as $value) {
                        if ($cantidad == 0) {
                            if ((int) $es_diagnostico == 1) {
                                echo "<tr><td>idDiagnostico</td><td># Pregunta</td><td>Contenido</td><td>Eje</td><td>Link quiz</td><td>idQuiz</td><td>Ingrersar contenido</td></tr>";
                            } else {
                                echo "<tr><td>idQuiz</td><td># Pregunta</td><td>Contenido</td><td>Eje</td><td>Link repaso</td><td>Ingrersar contenido</td></tr>";
                            }
                        }
                        $cantidad += 1;
//verficar insertado
                        
                        if ((int) $es_diagnostico == 1) {
                            echo "<tr><td><input id='id_diagnostico_" . $cantidad . "' value='" . $id_quiz . "' size = '4' disabled='disabled'/></td><td><input id='numero_preg_" . $cantidad . "' value='" . $cantidad . "' size = '4' disabled='disabled'/></td><td> <input id='contenido_" . $cantidad . "' maxlength = '250'/> </td><td> <input id='eje_" . $cantidad . "' maxlength = '250'/> </td><td> <input id='link_" . $cantidad . "' maxlength = '250' /> </td><td> <input id='id_quiz_" . $cantidad . "' value='" . $value->idquiz_diagnostico . "' /> </td><td><table style='margin:0;'><tr><td><button class='boton_form' onclick='actualizaFilaMatrizDiagnostico(" . $cantidad . ")'>Ingresar</button> </td><td><div id='res_" . $cantidad . "'></div></td></tr></table></td></tr>";
                        } else {
                            echo "<tr><td><input id='id_quiz_" . $cantidad . "' value='" . $id_quiz . "' size = '4' disabled='disabled'/></td><td><input id='numero_preg_" . $cantidad . "' value='" . $cantidad . "' size = '4' disabled='disabled'/></td><td> <input id='contenido_" . $cantidad . "' maxlength = '250'/> </td><td> <input id='eje_" . $cantidad . "' maxlength = '250'/> </td><td> <input id='link_" . $cantidad . "' maxlength = '250' /> </td><td><table style='margin:0;'><tr><td><button class='boton_form' onclick='actualizaFila(" . $cantidad . ")'>Ingresar</button> </td><td><div id='res_" . $cantidad . "'></div></td></tr></table></td></tr>";
                        }
                    }
                    echo "</table><br>";
                }
            } else {
// echo "<b> NO encontro matriz de desempeño, en un momento se generara y debera completarla</b>";
                echo "<table border='1' class='TablaDatos' id='TablaDatos'>";
                $cantidad = 0;
                foreach ($preguntas as $value) {
                    if ($cantidad == 0) {
                        if ((int) $es_diagnostico == 1) {
                            echo "<tr><td>idDiagnostico</td><td># Pregunta</td><td>Contenido</td><td>Eje</td><td>Link quiz</td><td>idQuiz</td><td>Ingrersar contenido</td></tr>";
                        } else {
                            echo "<tr><td>idQuiz</td><td># Pregunta</td><td>Contenido</td><td>Eje</td><td>Link repaso</td><td>Ingrersar contenido</td></tr>";
                        }
                    }
                    $cantidad += 1;
                    $inserted = DAOFactory::getMatrizDAO()->queryInsertMatriz($id_quiz, $cantidad, "", "", "", "", -1);
                    if ((int) $es_diagnostico == 1) {
                        echo "<tr><td><input id='id_diagnostico_" . $cantidad . "' value='" . $id_quiz . "' size = '4' disabled='disabled'/></td><td><input id='numero_preg_" . $cantidad . "' value='" . $cantidad . "' size = '4' disabled='disabled'/></td><td> <input id='contenido_" . $cantidad . "' maxlength = '250'/> </td><td> <input id='eje_" . $cantidad . "' maxlength = '250'/> </td><td> <input id='link_" . $cantidad . "' maxlength = '250'/> </td><td> <input id='id_quiz_" . $cantidad . "' value='" . $value->idquiz_diagnostico . "' /> </td><td><table style='margin:0;'><tr><td><button class='boton_form' onclick='actualizaFilaMatrizDiagnostico(" . $cantidad . ")'>Ingresar</button></td><td><div id='res_" . $cantidad . "'></div> </td></tr></table></td></tr>";
                    } else {
                        echo "<tr><td><input id='id_quiz_" . $cantidad . "' value='" . $id_quiz . "' size = '4' disabled='disabled'/></td><td><input id='numero_preg_" . $cantidad . "' value='" . $cantidad . "' size = '4' disabled='disabled'/></td><td> <input id='contenido_" . $cantidad . "' maxlength = '250'/> </td><td> <input id='eje_" . $cantidad . "' maxlength = '250'/> </td><td> <input id='link_" . $cantidad . "' maxlength = '250'/> </td><td><table style='margin:0;'><tr><td><button class='boton_form' onclick='actualizaFila(" . $cantidad . ")'>Ingresar</button></td><td><div id='res_" . $cantidad . "'></div> </td></tr></table></td></tr>";
                    }
                }
                echo "</table><br>";
            }
        } else {
            /* se genera matriz con subpreguntas */
// echo '<b>Se desplegara matriz con subpreguntas</b><br>';
            $cant = 0;
            $preguntas_array = array();
            foreach ($preguntas as $value) {
                $cant += 1;
                $preguntas_aux = new Preguntas();
                $preguntas_aux->numeropregunta = $cant;
                $preguntas_aux->tipopregunta = $value->tipopregunta;
                $preguntas_aux->correcto = "";
                $preguntas_aux->respuestaAlumno = "";
                $preguntas_array[$cant] = $preguntas_aux;
                if ($value->tipopregunta == "multianswer") {
                    $subpreguntas = explode(";", $value->correcto);
                    for ($i = 0; $i < count($subpreguntas); $i++) {
                        $preguntas_aux = new Preguntas();
                        $preguntas_aux->numeropregunta = $cant . "." . ($i + 1);
                        $preguntas_aux->tipopregunta = "subpregunta";
                        $preguntas_aux->correcto = "";
                        $preguntas_aux->respuestaAlumno = "";
                        $preguntas_array[$cant . "." . ($i + 1)] = $preguntas_aux;
                    }
                }
            }
            $preguntas = $preguntas_array;
//si se encuentra matriz ingresada en reportes
            if (count($matriz) > 0) {
//si la cantidad de preguntas ingresadas en la matriz de reportes y igual a la traida desde los intentos
//se muestra y genera tabla para modificaciones
//sacar subpreguntas y renombrar la variable $preguntas
                if (count($matriz) == count($preguntas)) {
                    $cantidad = 0;
                    echo "<table border='1' class='TablaDatos' id='TablaDatos'>";
                    $cantidad = 0;

//se recorre la matriz para mostrar y permitir modificar los valores desde reportes
                    if ((int) $es_diagnostico == 1) {
                        echo "<tr><td>idDiagnostico</td><td># Pregunta</td><td>Contenido</td><td>Eje</td><td>Link quiz</td><td>idQuiz</td><td>Ingrersar contenido</td></tr>";
                    } else {
                        echo "<tr><td>idQuiz</td><td># Pregunta</td><td>Contenido</td><td>Eje</td><td>Link repaso</td><td>Ingrersar contenido</td></tr>";
                    }
                    foreach ($matriz as $value) {
                        $cantidad += 1;
                        if ((int) $es_diagnostico == 1) {
                           echo "<tr><td><input id='id_diagnostico_" . $cantidad . "' value='" . $value->idquiz . "' size = '4' disabled='disabled'/></td><td><input id='numero_preg_" . $cantidad . "' value='" . $value->n_pregunta . "' size = '4' disabled='disabled'/></td><td> <input id='contenido_" . $cantidad . "' value='" . $value->contenido . "' maxlength = '250'/> </td><td> <input id='eje_" . $cantidad . "' value='" . $value->eje . "' maxlength = '250'/> </td><td> <input id='link_" . $cantidad . "' value='" . $value->link_repaso . "' maxlength = '250'/> </td><td> <input id='id_quiz_" . $cantidad . "' value='" . $value->idquiz_diagnostico . "' /> </td><td><table style='margin:0;'><tr><td><button class='boton_form' onclick='actualizaFilaMatrizDiagnostico(" . $cantidad . ")'>Ingresar</button></td><td><div id='res_" . $value->numeropregunta . "'></div> </td></tr></table></td></tr>"; 
                        } else {
                            echo "<tr><td><input id='id_quiz_" . $cantidad . "' value='" . $value->idquiz . "' size = '4' disabled='disabled'/></td><td><input id='numero_preg_" . $cantidad . "' value='" . $value->n_pregunta . "' size = '4' disabled='disabled'/></td><td> <input id='contenido_" . $cantidad . "' value='" . $value->contenido . "' maxlength = '250'/> </td><td> <input id='eje_" . $cantidad . "' value='" . $value->eje . "' maxlength = '250'/> </td><td> <input id='link_" . $cantidad . "' value='" . $value->link_repaso . "' maxlength = '250'/> </td><td><table style='margin:0;'><tr><td><button class='boton_form' onclick='actualizaFila(" . $cantidad . ")'>Ingresar</button></td><td><div id='res_" . $value->numeropregunta . "'></div> </td></tr></table></td></tr>";
                        }
                    }
                    echo "</table><br>";

//si el numero de preguntas de los intentos es diferentes a la de la matriz, se reinica la matriz de reportes
//y se crea con la nueva informacion
                } else {
//se reinicia la matriz del quiz seleccionado
                    foreach ($matriz as $value) {
//verificar eliminado
                        $deleted = DAOFactory::getMatrizDAO()->queryDeleteMatriz($value->idquiz);
                    }

//se crea la nueva matriz con la informacion proveniente desde los intentos
                    echo "<table border='1' class='TablaDatos' id='TablaDatos'>";
//se recorren las preguntas del intento iniciado nuevamente la matriz
                    if ((int) $es_diagnostico == 1) {
                        echo "<tr><td>idDiagnostico</td><td># Pregunta</td><td>Contenido</td><td>Eje</td><td>Link quiz</td><td>idQuiz</td><td>Ingrersar contenido</td></tr>";
                    } else {
                        echo "<tr><td>idQuiz</td><td># Pregunta</td><td>Contenido</td><td>Eje</td><td>Link repaso</td><td>Ingrersar contenido</td></tr>";
                    }
                    foreach ($preguntas as $value) {

//verficar insertado
                        $inserted = DAOFactory::getMatrizDAO()->queryInsertMatriz($id_quiz, $value->numeropregunta, "", "", "", $value->tipopregunta, -1);
                        if ((int) $es_diagnostico == 1) {
                            echo "<tr><td><input id='id_diagnostico_" . $value->numeropregunta . "' value='" . $id_quiz . "' size = '4' disabled='disabled'/></td><td><input id='numero_preg_" . $value->numeropregunta . "' value='" . $value->numeropregunta . "' size = '4' disabled='disabled'/></td><td> <input id='contenido_" . $value->numeropregunta . "' maxlength = '250'/> </td><td> <input id='eje_" . $value->numeropregunta . "' maxlength = '250'/> </td><td> <input id='link_" . $value->numeropregunta . "' maxlength = '250'/> </td><td> <input id='id_quiz_" . $value->numeropregunta . "' value='" . $value->idquiz_diagnostico . "' /> </td><td><table style='margin:0;'><tr><td><button class='boton_form' onclick='actualizaFilaMatrizDiagnostico(" . $value->numeropregunta . ")'>Ingresar</button></td><td><div id='res_" . $value->numeropregunta . "'></div> </td></tr></table></td></tr>";
                        } else {
                            echo "<tr><td><input id='id_quiz_" . $value->numeropregunta . "' value='" . $id_quiz . "' size = '4' disabled='disabled'/></td><td><input id='numero_preg_" . $value->numeropregunta . "' value='" . $value->numeropregunta . "' size = '4' disabled='disabled'/></td><td> <input id='contenido_" . $value->numeropregunta . "' maxlength = '250'/> </td><td> <input id='eje_" . $value->numeropregunta . "' maxlength = '250'/> </td><td> <input id='link_" . $value->numeropregunta . "' maxlength = '250'/> </td><td><table style='margin:0;'><tr><td><button class='boton_form' onclick='actualizaFila(" . $value->numeropregunta . ")'>Ingresar</button></td><td><div id='res_" . $value->numeropregunta . "'></div> </td></tr></table></td></tr>";
                        }
                    }
                    echo "</table><br>";
                }
//no se encentra matriz en reportes   
            } else {
                echo "<table border='1' class='TablaDatos' id='TablaDatos'>";
                if ((int) $es_diagnostico == 1) {
                    echo "<tr><td>idDiagnostico</td><td># Pregunta</td><td>Contenido</td><td>Eje</td><td>Link quiz</td><td>idQuiz</td><td>Ingrersar contenido</td></tr>";
                } else {
                    echo "<tr><td>idQuiz</td><td># Pregunta</td><td>Contenido</td><td>Eje</td><td>Link repaso</td><td>Ingrersar contenido</td></tr>";
                }
                foreach ($preguntas as $value) {
                    $inserted = DAOFactory::getMatrizDAO()->queryInsertMatriz($id_quiz, $value->numeropregunta, "", "", "", $value->tipopregunta, -1);
                    if ((int) $es_diagnostico == 1) {
                        echo "<tr><td><input id='id_diagnostico_" . $value->numeropregunta . "' value='" . $id_quiz . "' size = '4' disabled='disabled'/></td><td><input id='numero_preg_" . $value->numeropregunta . "' value='" . $value->numeropregunta . "' size = '4' disabled='disabled'/></td><td> <input id='contenido_" . $value->numeropregunta . "' maxlength = '250'/> </td><td> <input id='eje_" . $value->numeropregunta . "' maxlength = '250'/> </td><td> <input id='link_" . $value->numeropregunta . "' maxlength = '250'/> </td><td> <input id='id_quiz_" . $value->numeropregunta . "' value='" . $value->idquiz_diagnostico . "' /> </td><td><table style='margin:0;'><tr><td><button class='boton_form' onclick='actualizaFilaMatrizDiagnostico(" . $value->numeropregunta . ")'>Ingresar</button></td><td><div id='res_" . $value->numeropregunta . "'></div></td></tr></table></td></tr>";
                    } else {
                        echo "<tr><td><input id='id_quiz_" . $value->numeropregunta . "' value='" . $id_quiz . "' size = '4' disabled='disabled'/></td><td><input id='numero_preg_" . $value->numeropregunta . "' value='" . $value->numeropregunta . "' size = '4' disabled='disabled'/></td><td> <input id='contenido_" . $value->numeropregunta . "' maxlength = '250'/> </td><td> <input id='eje_" . $value->numeropregunta . "' maxlength = '250'/> </td><td> <input id='link_" . $value->numeropregunta . "' maxlength = '250'/> </td><td><table style='margin:0;'><tr><td><button class='boton_form' onclick='actualizaFila(" . $value->numeropregunta . ")'>Ingresar</button></td><td><div id='res_" . $value->numeropregunta . "'></div></td></tr></table></td></tr>";
                    }
                }
                echo "</table><br>";
            }
        }
    }

    public function actualizaContenido() {
        $idquiz = $_GET['idquiz'];
        $numeropregunta = $_GET['numeropregunta'];
        $contenido = $_GET['contenido'];
        $eje = $_GET['eje'];
        $link = $_GET['link'];
        $id_insertado = DAOFactory::getMatrizDAO()->queryUpdateMatriz($idquiz, $numeropregunta, $contenido, $eje, $link, -1);
        if ($id_insertado > 0) {
            echo "<img src='../views/images/icons/Tick.png' title='Se ha insertado correctamente' style='width:35px;'>";
        } else {
            echo "<img src='../views/images/icons/Alert.png' title='No ha ingresado nuevos datos' style='width:35px;'>";
        }
    }
    
    public function actualizaContenidoDiagnostico() {
        $id_diagnostico = $_GET['id_diagnostico'];
        $numero_preg = $_GET['numero_preg'];
        $contenido = $_GET['contenido'];
        $eje = $_GET['eje'];
        $link = $_GET['link'];
        $id_quiz = $_GET['id_quiz'];
        $id_insertado = DAOFactory::getMatrizDAO()->queryUpdateMatriz($id_diagnostico, $numero_preg, $contenido, $eje, $link, $id_quiz);
        if ($id_insertado > 0) {
            echo "<img src='../views/images/icons/Tick.png' title='Se ha insertado correctamente' style='width:35px;'>";
        } else {
            echo "<img src='../views/images/icons/Alert.png' title='No ha ingresado nuevos datos' style='width:35px;'>";
        }
    }    
    

    public function agregarContenido() {
        $idquiz = $_GET['idquiz'];
        $numeropregunta = $_GET['numeropregunta'];
        $contenido = $_GET['contenido'];
        $eje = $_GET['eje'];
        $link = $_GET['link'];
        $id_insertado = DAOFactory::getMatrizDAO()->queryInsertMatriz($idquiz, $numeropregunta, $contenido, $eje, $link, -1);
        if ($id_insertado > 0) {

            echo "Contenido insertado correctamente";
        } else {

            echo "No ha ingresado nuevos datos";
        }
    }

    public function agregarContenidoSite() {
        $cursos_moodle = DAOFactory::getCursosDAO()->queryBuscaTodoCursos();
        echo '<div>';
        echo "<h2>Seleccion un curso:</h2><br>";
        $combo = '<select onchange="Quizes(this.value)">';
        $combo.='<option value="-1">Seleccione un Curso</option>';
        foreach ($cursos_moodle as $curso) {
            $combo .= '<option value="' . $curso->id . '" >' . $curso->fullname . '</option>';
        }
        $combo.='</select><br>';
        echo $combo;
        echo '</div>';
        echo '<div id="tipoPreguntas"></div>';
        echo '<div id="quizes_todos"></div>';
        echo '<div id="boton_generar"></div>';
        echo '<div id="preguntas_quiz_select"></div>';
        echo '<div id="matriz"></div>';
        echo '<div id="resultado_insert"></div> ';
    }

    public function formularioPerfil() {
        echo '<h3><a href="#" onClick="listarPerfil()">Modificar / Eliminar perfil</a></h3><br>';
        echo '<h2>Nuevo perfil</h2><br>';
        echo '<h3>Complete el siguiente formulario para ingresar un nuevo perfil</h3><br>';
        echo "<table border='1' class='TablaIngresoPerfil' id='TablaIngresoPerfil' style=\"width: 660px;\">";
        echo "<tr><td>Nombre del Rol</td><td><input id='nombre_rol' size = '30' /></td></tr>";
        echo "<tr><td>Nombre de Usuario</td><td><input id='nombre_usuario' size = '30' /></td></tr>";
        echo "<tr><td>Password</td><td><input type='password' id='password' size = '30' /></td></tr>";
        echo "<tr><td>Nombre</td><td><input id='nombres' size = '30' /></td></tr>";
        echo "<tr><td>Apellidos</td><td><input id='apellidos' size = '30' /></td></tr>";
        echo "<tr><td>Nombre Institución</td><td><input id='nombre_institucion' size = '30' /></td></tr>";
        echo "<tr><td>Campo Institución</td><td><input id='campo_institucion' size = '30' /></td></tr>";
//echo "<tr><td><input id='id_quiz_" . $value->numeropregunta . "' value='" . $id_quiz . "' size = '4' disabled='disabled'/></td><td><input id='numero_preg_" . $value->numeropregunta . "' value='" . $value->numeropregunta . "' size = '4' disabled='disabled'/></td><td> <input id='contenido_" . $value->numeropregunta . "' size = '250'/> </td><td> <input id='eje_" . $value->numeropregunta . "' size = '250'/> </td><td> <input id='link_" . $value->numeropregunta . "' size = '250'/> </td><td><button onclick='actualizaFila(" . $value->numeropregunta . ")'>Ingresar</button> </td></tr>";
        echo "</table><br>";
        echo '<input type="button" class="boton_form"  value="Ingresar perfil" onclick="insertaPerfil()"><br>';
    }

    public function listarPerfiles() {
        echo '<h2>Modificar / Eliminar perfil</h2><br>';
        $usuarios_otrosRoles = DAOFactory::getOtroRolDAO()->queryBuscaPersonaOtroRolTodos();
        echo '<div>';
        echo "<h2>Seleccione un Usuario:</h2><br>";
        $combo = '<select onchange="modificarPerfil(this.value)">';
        $combo.='<option value="-1">Institución | Rol | Usuario</option>';
        foreach ($usuarios_otrosRoles as $otros) {
            $combo .= '<option value="' . $otros->id . '" >' . $otros->institucion . ' | ' . $otros->rolMoodle . ' | ' . $otros->usuario . '</option>';
        }
        $combo.='</select><br>';
        echo $combo;
        echo '</div>';
        echo '<div id="camposModificar"></div><br>';
    }

    public function modificarPerfil() {
        $id_otroRol = $_GET['id_otroRol'];
        $datos_otroRol = DAOFactory::getOtroRolDAO()->queryBuscaPersonaOtroRolPlataforma($id_otroRol);
        if (!((int) $id_otroRol == -1)) {
            echo '<br><h3>Modifique los campos presentados a continuación o seleleccione eliminar, segun desee</h3><br>';
            echo "<table border='1' class='TablaIngresoPerfil' id='TablaIngresoPerfil' style=\"width: 660px;\">";
            echo "<tr><td>Nombre del Rol</td><td><input id='nombre_rol' size = '30' value='" . $datos_otroRol->rolMoodle . "'/></td></tr>";
            echo "<tr><td>Nombre de Usuario</td><td><input id='nombre_usuario' size = '30' value='" . $datos_otroRol->usuario . "'/></td></tr>";
            echo "<tr><td>Password</td><td><input type='password' id='password' size = '30' /></td></tr>";
            echo "<tr><td>Nombre</td><td><input id='nombres' size = '30' value='" . $datos_otroRol->nombre . "'/></td></tr>";
            echo "<tr><td>Apellidos</td><td><input id='apellidos' size = '30' value='" . $datos_otroRol->apellido . "'/></td></tr>";
            echo "<tr><td>Nombre Institución</td><td><input id='nombre_institucion' size = '30' value='" . $datos_otroRol->institucion . "'/></td></tr>";
            echo "<tr><td>Campo Institución</td><td><input id='campo_institucion' size = '30' value='" . $datos_otroRol->campo_institucion . "'/></td></tr>";
            echo "</table><br>";
            echo '<input type="button" class="boton_form"  value="Modificar perfil" onclick="actualizarPerfil(' . $id_otroRol . ')"><br>';
            echo '<br><h3> Ó <h3><br>';
            echo '<input type="button" class="boton_form"  value="Eliminar perfil" onclick="eliminarPerfil(' . $id_otroRol . ')"><br>';
        } else {
            echo '<br><h3>Selccione un perfil</h3><br>';
        }
    }

    public function actualizaPerfil() {
        $nombre_rol = $_GET['nombre_rol'];
        $nombre_usuario = $_GET['nombre_usuario'];
        $password = $_GET['password'];
        $nombres = $_GET['nombres'];
        $apellidos = $_GET['apellidos'];
        $nombre_institucion = $_GET['nombre_institucion'];
        $campo_institucion = $_GET['campo_institucion'];
        $id_otroRol = $_GET['id_otroRol'];
        $insertado = DAOFactory::getOtroRolDAO()->queryUpdateOtroRol($nombre_rol, $nombre_usuario, $nombre_institucion, $nombres, $apellidos, $campo_institucion, $password, $id_otroRol);
        if ((int) $insertado > 0) {
            echo "<br><h3>Perfil actualizado exitosamente</h3>";
        } else {
            echo "<br><h3>El perfil no se pudo actualizar o no realizo modificación</h3>";
        }
    }

    public function eliminarPerfil() {
        echo "entro elimina";
        $id = $_GET['id_otroRol'];
        $eliminado = DAOFactory::getOtroRolDAO()->queryDeleteOtroRol($id);
        if ((int) $eliminado > 0) {
            echo "<br><h3>Perfil eliminado exitosamente</h3>";
        } else {
            echo "<br><h3>El perfil no se pudo aliminar</h3>";
        }
    }

    public function insertaPerfil() {
        $nombre_rol = $_GET['nombre_rol'];
        $nombre_usuario = $_GET['nombre_usuario'];
        $password = $_GET['password'];
        $nombres = $_GET['nombres'];
        $apellidos = $_GET['apellidos'];
        $nombre_institucion = $_GET['nombre_institucion'];
        $campo_institucion = $_GET['campo_institucion'];
        $insertado = DAOFactory::getOtroRolDAO()->queryInsertOtroRol($nombre_rol, $nombre_usuario, $nombre_institucion, $nombres, $apellidos, $campo_institucion, $password);
        if ((int) $insertado > 0) {
            echo "<h3>Perfil ingresado exitosamente</h3>";
        } else {
            echo "<h3>El perfil no se pudo ingresar</h3>";
        }
    }

    public function formularioInstitucion() {
        echo '<h2>Nueva institución</h2><br>';
        echo '<h3>Complete el siguiente formulario para ingresar los datos de la institución</h3><br>';
        echo "<table border='1' class='TablaIngresoInstitucion' id='TablaIngresoInstitucion' style=\"width: 660px;\">";
        echo "<tr><td>Nombre institución</td><td><input id='nombre' size = '30' /></td></tr>";
        echo "<tr><td>Plataforma</td><td><input id='plataforma' size = '30' /></td></tr>";
        echo "<tr><td>Imagen</td><td><input id='imagen' size = '30' /></td></tr>";
        echo "<tr><td>Campo Institución</td><td><input id='campo_institucion_inst' size = '30' /></td></tr>";
        echo "</table><br>";
        echo '<input class="boton_form"  type="button" value="Ingresar institución" onclick="insertaInstitucion()"><br>';
    }

    public function insertaInstitucion() {
        $nombre = $_GET['nombre'];
        $plataforma = $_GET['plataforma'];
        $imagen = $_GET['imagen'];
        $campo_institucion_inst = $_GET['campo_institucion_inst'];
        $insertado = DAOFactory::getMantenedorDAO()->queryInsertInstitucion($nombre, $plataforma, $imagen, $campo_institucion_inst);
        if ((int) $insertado > 0) {
            echo "<h3>Institución ingresado exitosamente</h3>";
        } else {
            echo "<h3>El institución no se pudo ingresar</h3>";
        }
    }

}

?>
