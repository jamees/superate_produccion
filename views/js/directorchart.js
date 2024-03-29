	
/**
 * Esta funci�n se encarga de responder din�micamente los pedidos que
 * se realizan sobre el gr�fico para directores.
 * 
 * @author cgajardo
 * @date 2012-02-07
 */   
var id_director;
var sede;
var curso;
var grupo;

function loadCursos(){
	//document.getElementById("chart_div").innerHTML='<img class="loading-gif" border="0" src="/reportes/views/images/loading.gif" alt="cargando"/>';
	document.getElementById("chart_nav").innerHTML='<input type="button" onclick="drawChart()" value="Regresar"></input>';
    //recuperamos la id del director
    	id_director = gup('id');
    	var xmlhttp;
    	var selection = chart.getSelection();
    	for (var i = 0; i < selection.length; i++) {
    		var item = selection[i]; 
    	    if (item.row != null) {
    	   	sede = data['D'][item.row]['c'][0]['v'];
    	    } else {
    	    	alert("error");
    	    }
    	}
    	
    	if (window.XMLHttpRequest){
        	// code for IE7+, Firefox, Chrome, Opera, Safari
      		xmlhttp=new XMLHttpRequest();
      	}
    	else{
        	// code for IE6, IE5
      		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      	}
      	
    	xmlhttp.onreadystatechange=function() { 
      		if (xmlhttp.readyState==4 && xmlhttp.status==200){
      			//document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
      			var j = JSON.parse(xmlhttp.responseText);
      			last_data = j;
            	//se sobreescribe el gr�fico 
      			chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

            	data = new google.visualization.DataTable();
               data.addColumn('string', 'Curso');
               data.addColumn('number', 'Tiempo');
               data.addRows(j);
               options = {
                     title: 'Tiempo de uso de la plataforma en Cursos',
                     hAxis: {title: 'Cursos', titleTextStyle: {color: 'blue'},viewWindowMode:'maximized'},
                     vAxis: {title: 'minutos/alumnos', titleTextStyle: {color: 'blue'}, viewWindowMode:'explicit', viewWindow: {min:0}}

                };
                
                chart.draw(data, options);
                google.visualization.events.addListener(chart, 'select', loadGrupos);
      		}
      	};
    	xmlhttp.open("GET","data?director="+id_director+"&sede="+sede,true);
    	xmlhttp.send();
 }

function loadGrupos(){
		//document.getElementById("chart_div").innerHTML='<img class="loading-gif" border="0" src="/reportes/views/images/loading.gif" alt="cargando"/>';
		document.getElementById("chart_nav").innerHTML='<input type="button" onclick="loadCursos()" value="Regresar"></input>';
   	var xmlhttp;
   	var selection = chart.getSelection();
   	for (var i = 0; i < selection.length; i++) {
   		var item = selection[i]; 
   	    if (item.row != null) {
   	   	curso = data['D'][item.row]['c'][0]['v'];
   	    } else {
   	    	alert("error");
   	    }
   	}
   	
   	if (window.XMLHttpRequest){
       	// code for IE7+, Firefox, Chrome, Opera, Safari
     		xmlhttp=new XMLHttpRequest();
     	}
   	else{
       	// code for IE6, IE5
     		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
     	}
     	
   	xmlhttp.onreadystatechange=function() { 
     		if (xmlhttp.readyState==4 && xmlhttp.status==200){
     			//document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
     			document.getElementById("detalleTiempo").innerHTML='';
     			var j = JSON.parse(xmlhttp.responseText);
     			last_data = j;
           	//se sobreescribe el gr�fico 
     			chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

           	data = new google.visualization.DataTable();
              data.addColumn('string', 'Grupo');
              data.addColumn('number', 'Tiempo');
              data.addRows(j);
              options = {
                    title: 'Tiempo de uso de la plataforma en Grupos',
                    hAxis: {title: 'Grupos', titleTextStyle: {color: 'blue'}, viewWindowMode:'maximized'},
                    vAxis: {title: 'minutos/alumnos', titleTextStyle: {color: 'blue'}, viewWindowMode:'explicit', viewWindow: {min:0}}
               };
               
               chart.draw(data, options);
               google.visualization.events.addListener(chart, 'select', loadAlumnos);
     		}
     	};
   	xmlhttp.open("GET","data?director="+id_director+"&sede="+sede+"&curso="+curso,true);
   	xmlhttp.send();
}

function loadAlumnos(){
		//document.getElementById("chart_div").innerHTML='<img class="loading-gif" border="0" src="/reportes/views/images/loading.gif" alt="cargando"/>';
		document.getElementById("chart_nav").innerHTML='<input type="button" onclick="loadGrupos()" value="Regresar"></input>';
   	var xmlhttp;
   	var selection = chart.getSelection();
   	for (var i = 0; i < selection.length; i++) {
   		var item = selection[i]; 
   	    if (item.row != null) {
   	   	 grupo = data['D'][item.row]['c'][0]['v'];
   	    } else {
   	    	alert("error");
   	    }
   	}
   	
   	if (window.XMLHttpRequest){
       	// code for IE7+, Firefox, Chrome, Opera, Safari
     		xmlhttp=new XMLHttpRequest();
     	}
   	else{
       	// code for IE6, IE5
     		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
     	}
     	
   	xmlhttp.onreadystatechange=function() { 
     		if (xmlhttp.readyState==4 && xmlhttp.status==200){
     			//document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
     			var j = JSON.parse(xmlhttp.responseText);
     			last_data = j;
           	//se sobreescribe el gr�fico 
     			chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

           	data = new google.visualization.DataTable();
              data.addColumn('string', 'Alumnos');
              data.addColumn('number', 'Tiempo');
              data.addRows(j);
              options = {
            	 title: 'Tiempo de uso por estudiante',
              	 hAxis: {title: 'Alumnos', titleTextStyle: {color: 'blue'}, viewWindowMode:'maximized'},
              	 vAxis: {title: 'minutos', titleTextStyle: {color: 'blue'}, viewWindowMode:'explicit', viewWindow: {min:0}}

               };
               
               chart.draw(data, options);
               google.visualization.events.addListener(chart, 'select', loadAlumno);
     		}
     	};
   	xmlhttp.open("GET","data?director="+id_director+"&sede="+sede+"&curso="+curso+"&grupo="+grupo,true);
   	xmlhttp.send();
}

//Muestra la matriz de desempe�o de un alumno
function loadAlumno(){
	var xmlhttp;
	var selection = chart.getSelection();
	for (var i = 0; i < selection.length; i++) {
		var item = selection[i]; 
	    if (item.row != null) {
	   	 alumno = data['D'][item.row]['c'][0]['v'];
	    } else {
	    	alert("error");
	    }
	}
	
	if (window.XMLHttpRequest){
    	// code for IE7+, Firefox, Chrome, Opera, Safari
  		xmlhttp=new XMLHttpRequest();
  	}
	else{
    	// code for IE6, IE5
  		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  	}
  	
	xmlhttp.onreadystatechange=function() { 
  		if (xmlhttp.readyState==4 && xmlhttp.status==200){
  			var j = JSON.parse(xmlhttp.responseText);
  			last_data = j;
  			
  			chart2 = new google.visualization.ColumnChart(document.getElementById('detalleTiempo'));

        	data2 = new google.visualization.DataTable();
           data2.addColumn('string', 'Alumnos');
           data2.addColumn('number', 'Tiempo');
           data2.addRows(j);
           options = {
         	 title: 'Tiempo de uso para '+alumno+' durante las ultimas 15 semanas',
           	 hAxis: {title: 'Alumnos', titleTextStyle: {color: 'blue'}, viewWindowMode:'maximized'},
           	 vAxis: {title: 'minutos', titleTextStyle: {color: 'blue'}, viewWindowMode:'explicit', viewWindow: {min:0}}

            };
            
            chart2.draw(data2, options);
  			
  		}
  	};
	//xmlhttp.open("GET","directores/matriz?director="+id_director+"&sede="+sede+"&curso="+curso+"&grupo="+grupo,true);
  	var scapedURL = "matriz?director="+id_director+"&sede="+sede+"&curso="+curso+"&grupo="+grupo+"&alumno="+alumno;
  	xmlhttp.open("GET", scapedURL, true);
	xmlhttp.send();
}


 //otras funciones auxiliares
 function gup(name){
   name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
   var regexS = "[\\?&]"+name+"=([^&#]*)";
   var regex = new RegExp( regexS );
   var results = regex.exec( window.location.href );
   if( results == null )
     return "";
   else
     return results[1];
 }
