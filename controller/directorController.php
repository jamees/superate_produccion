<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of directorController
 *
 * @author JorgePaz
 */
class directorController Extends baseController {

    //put your code here
    public function index() {
        session_start();
        if (isset($_SESSION['usuario_director'])) {
            $usuario_director = $_SESSION['usuario_director'];

            $this->registry->template->usuario_director = $usuario_director;

            $cursos_director = DAOFactory::getCursosDAO()->queryBuscaCursosDirector($usuario_director->usuario);
            //Se despliega la vista indicada
            $this->registry->template->show('director/index_director');
        } else {
            $this->registry->template->blog_heading = "Para acceder a su reporte debe estar logueado";
            $this->registry->template->show('error404');
        }
    }

    public function obtenerGraficoAdopcionAjax() {
        session_start();
        if (isset($_SESSION['usuario_director'])) {
            $usuario_director = $_SESSION['usuario_director'];
            $cursos_director = DAOFactory::getCursosDAO()->queryBuscaCursosDirector($usuario_director->usuario);
            $data_total = "";
            $i = 1;
            $categories = "";
            foreach ($cursos_director as $curso) {
                $adopcion_semanal = DAOFactory::getAdopcionDAO()->queryBuscaAdopcionSemanal($usuario_director->institucion, $usuario_director->campo_institucion, $curso->id);
                //echo "cantidad adopcion semanal: ".count($adopcion_semanal);
                $data_semanal = "";
                $categories = "";
                $j = 1;
                for ($index = count($adopcion_semanal) - 1; $index >= 0; $index--) {
                    if ($j == count($adopcion_semanal)) {
                        $data_semanal .=$adopcion_semanal[$index]->totalUsuariosInstitucion;
                        $categories .="'" . $adopcion_semanal[$index]->ano . " Sem-" . $adopcion_semanal[$index]->semana . "'";
                    } else {
                        $categories .="'" . $adopcion_semanal[$index]->ano . " Sem-" . $adopcion_semanal[$index]->semana . "',";
                        $data_semanal .=$adopcion_semanal[$index]->totalUsuariosInstitucion . ",";
                    }
                    $j++;
                }
                if ($i == count($cursos_director)) {
                    $data_total .= "{
			name: '" . $curso->fullname . "',
			data: [$data_semanal]
		}";
                } else {
                    $data_total .= "{
			name: '" . $curso->fullname . "',
			data: [$data_semanal]
		},";
                }
                $i++;
            }

            echo "<h2>Tiempo de uso semanal:</h2><br>";
            echo "<h3>El siguiente gráfico permite mostrar el tiempo semanal que han dedicado los estudiantes a las actividades de la plataforma.</h3><br>";
            echo "<div id='container_adopcion'></div><script type=\"text/javascript\"> var chart;
        $(document).ready(function() {
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'container_adopcion',
			type: 'line',
		},
		title: {
			text: 'Ingresos semanales institución',
			x: -20 //center
		},
                credits:{
                        enabled: false
                },
		xAxis: {
			categories: [$categories]
		},
		yAxis: {
			title: {
				text: 'Cantidad usuarios'
			},
			plotLines: [{
				value: 0,
				width: 1,
				color: '#808080'
			}]
		},
		tooltip: {
			formatter: function() {
					return '<b>'+ this.series.name +'</b><br/>'+
					this.x +': Usuarios '+ this.y;
			}
		},
		legend: {
			
			align: 'center',
			
		},
		series: [$data_total]
	});
    });</script>";
        }
    }

    public function obtenerGraficoAdopcionGruposAjax() {
        echo "<div id='container_adopcion_grupos'></div><script type=\"text/javascript\"> var chart;
        $(document).ready(function() {
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'container_adopcion_grupos',
			type: 'bar'
		},
		title: {
			text: 'Stacked bar chart'
		},
                credits:{
                        enabled: false
                },
		xAxis: {
			categories: ['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Total fruit consumption'
			}
		},
		legend: {
			backgroundColor: '#FFFFFF',
			reversed: true
		},
		tooltip: {
			formatter: function() {
				return ''+
					this.series.name +': '+ this.y +'';
			}
		},
		plotOptions: {
			series: {
				stacking: 'normal'
			}
		},
			series: [{
			name: 'John',
			data: [5, 3, 4, 7, 2]
		}, {
			name: 'Jane',
			data: [2, 2, 3, 2, 1]
		}, {
			name: 'Joe',
			data: [3, 4, 4, 2, 5]
		}]
	});
});</script>";
    }

    public function obtenerGraficoDesempenoAjax() {
        session_start();
        if (isset($_SESSION['usuario_director'])) {
            $usuario_director = $_SESSION['usuario_director'];
            $cursos_director = DAOFactory::getCursosDAO()->queryBuscaCursosDirector($usuario_director->usuario);
            $listaGrupos = array();
            $curso_actual = 1;
            $data_total = "";
            $cursos_categories = "";
            foreach ($cursos_director as $curso) {
                $listaGrupos[$curso->id] = DAOFactory::getGruposDAO()->queryBuscaGrupoDirector($usuario_director->usuario, $curso->id);
                $promedio_curso = 0;
                $grupo_actual = 1;
                $categories = "";
                $data = "";
                foreach ($listaGrupos[$curso->id] as $value) {
                    $promedio_grupo = DAOFactory::getRankingDAO()->queryBuscaPromedioNotasGrupo($curso->id, $value->id);
                    $promedio_curso +=$promedio_grupo;
                    if ($grupo_actual == count($listaGrupos[$curso->id])) {
                        $categories .= "'" . $value->nombre . "'";
                        $data .= "" . round($promedio_grupo, 2) . "";
                    } else {
                        $categories .= "'" . $value->nombre . "',";
                        $data .= "" . round($promedio_grupo, 2) . ",";
                    }
                    $grupo_actual++;
                    //echo "nota grupo: ".$value->nombre." ". round($promedio_grupo,2). "<br>";
                }
                if (count($listaGrupos[$curso->id]) > 0) {
                    $promedio_curso = round($promedio_curso / count($listaGrupos[$curso->id]), 2);
                } else {
                    $promedio_curso = 0;
                }
                if ($curso_actual == count($cursos_director)) {
                    $data_total .= "{
				y: " . $promedio_curso . ",
				color: colors[0],
				drilldown: {
					name: '" . $curso->fullname . "',
					categories: [" . $categories . "],
					data: [" . $data . "],
					color: colors[0]
				}
			}";
                    $cursos_categories .="'" . $curso->fullname . "'";
                } else {
                    $data_total .="{
				y: " . $promedio_curso . ",
				color: colors[0],
				drilldown: {
					name: '" . $curso->fullname . "',
					categories: [" . $categories . "],
					data: [" . $data . "],
					color: colors[0]
				}
			},";
                    $cursos_categories .="'" . $curso->fullname . "',";
                }
                $curso_actual++;
            }
            echo "<h2>Rendimiento promedio</h2><br>";
            echo "<h3>El siguiente gráfico muestra el promedio de notas institucional por curso, si presiona sobre el grafico de un curso determinado, podrá acceder a los promedios de cada grupo por separado.</h3><br>";
            echo "<div id='container'></div><script type=\"text/javascript\">
        var chart_desempeno;
        $(document).ready(function() {

	var colors = Highcharts.getOptions().colors,
		categories = [$cursos_categories],
		name = 'Cursos',
		data = [$data_total];

	function setChart(name, categories, data, color) {
		chart_desempeno.xAxis[0].setCategories(categories);
		chart_desempeno.series[0].remove();
		chart_desempeno.addSeries({
			name: name,
			data: data,
			color: color || 'white'
		});
	}

	chart_desempeno = new Highcharts.Chart({
		chart: {
			renderTo: 'container',
			type: 'column',
                        height: 400
		},
		title: {
			text: 'Rendimiento general de la institución'
		},
		subtitle: {
			text: 'Click en el curso para ver sus grupos. Presione nuevamente para volver.'
		},
                credits:{
                        enabled: false
                },
		xAxis: {
			categories: categories,
                        labels: {
                                enabled: false,
                                rotation: -90,
                                align: 'right',
                                style: {
                                    fontSize: '11px',
                                    fontFamily: 'Verdana, sans-serif'
                                }
                            }
		},
		yAxis: {min: 0,
			title: {
				text: 'Promedio general',
                                align: 'high'
			},
			labels: {
				overflow: 'justify'
			}
		},
		plotOptions: {
			column: {
				cursor: 'pointer',
				point: {
					events: {
						click: function() {
							var drilldown = this.drilldown;
							if (drilldown) { // drill down
								setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
							} else { // restore
								setChart(name, categories, data);
							}
						}
					}
				},
                                dataLabels: {
                                        enabled: true,
                                        rotation: -90,
                                        color: '#000000',
                                        align: 'right',
                                        x: -2,
                                        y: -35,
                                        formatter: function() {
                                            return this.y;
                                        },
                                        style: {
                                            fontSize: '10px',
                                            fontFamily: 'Verdana, sans-serif'
                                        }
                                    }
//				dataLabels: {
//					enabled: true,
//					color: colors[0],
//					style: {
//						fontWeight: 'bold'
//					},
//					formatter: function() {
//						return this.y +'%';
//					}
//				}
			}
		},
		tooltip: {
			formatter: function() {
				var point = this.point,
					s = '<b> '+this.x +' </b> Promedio <b>'+ this.y +'</b><br/>';
				if (point.drilldown) {
					s += 'Presione para ver grupos de '+ point.category;
				} else {
					s += 'Presione para volver';
				}
				return s;
			}
		},
		series: [{
			name: name,
			data: data,
			color: 'white'
		}],
		exporting: {
			enabled: false
		}
	});
        });</script>";
        }
    }

    public function obtenerGraficoPieAdopcionAlumnoAjax() {
        session_start();
        if (isset($_SESSION['usuario_director'])) {
            $usuario_profesor = $_SESSION['usuario_director'];
            $adopcionAlumnos = DAOFactory::getAdopcionDAO()->queryBuscaAdopcion($usuario_profesor->institucion, $usuario_profesor->campo_institucion, 5);
            echo "<div id='container_adopcion_pie_alumno'></div><script type=\"text/javascript\">
        var chart;
        $(document).ready(function() {
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'container_adopcion_pie_alumno',
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
                        height: 250
		},
		title: {
			text: 'Alumnos'
		},
                credits:{
                        enabled: false
                },
		tooltip: {
			formatter: function() {
				return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
			}
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: false
				},
				showInLegend: true
			}
		},
		series: [{
			type: 'pie',
			name: 'Browser share',
			data: [
				['Con ingreso',   " . round($adopcionAlumnos->porcentaje, 2) . "],
				{
					name: 'Sin ingreso',
					y: " . round((100 - $adopcionAlumnos->porcentaje), 2) . ",
					sliced: true,
					selected: true
				}
			]
		}]
	});
        });</script>";
        }
    }

    public function obtenerGraficoPieAdopcionProfesorAjax() {
        session_start();
        if (isset($_SESSION['usuario_director'])) {
            $usuario_profesor = $_SESSION['usuario_director'];
            $adopcionProfesor = DAOFactory::getAdopcionDAO()->queryBuscaAdopcion($usuario_profesor->institucion, $usuario_profesor->campo_institucion, 4);
            echo "<div id='container_adopcion_pie_profesor'></div><script type=\"text/javascript\">
        var chart;
        $(document).ready(function() {
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'container_adopcion_pie_profesor',
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
                        height: 250
		},
		title: {
			text: 'Profesores'
		},
                credits:{
                        enabled: false
                },
                
		tooltip: {
			formatter: function() {
				return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' %';
			}
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: false
				},
				showInLegend: true
			}
		},
		series: [{
			type: 'pie',
			name: 'Browser share',
			data: [
				['Con ingreso',   " . round($adopcionProfesor->porcentaje, 2) . "],
				{
					name: 'Sin ingreso',
					y: " . round((100 - $adopcionProfesor->porcentaje), 2) . ",
					sliced: true,
					selected: true
				}
			]
		}]
	});
        });</script>";
        }
    }

}

?>
