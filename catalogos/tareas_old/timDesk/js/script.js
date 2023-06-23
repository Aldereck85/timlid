/*
	
*/
//var columnas="";
var handleClass;
var sortable;
var arrColumnas=[];
var tipo;
var indexArray=[]; //Se llenará con la información de las tareas.
var columnasArray=[]; //Se llenará con las columnas de las etapas.
var idProyecto;
var numTareas=[];
var sorteable;
var tablas=[];
var container=""; //clics fuera de algún elemento.
var oCaracter=""; //Suceso adicional a clic fuera del elemento agregar columna.
var mCaracter="";//Suceso fuera del elemento del menú de los grupos.
var hide="";
var numTask=0;//Número que se le asignará cuando se agreguen nuevas tareas.
var picker=0;
var specialClass="";
var paddingClass="";

/***********************************************************************************/
/*######    PRIMERAS FUNCIONES PARA TRAER LA INFORMACIÓN DEL PROYECTO        ######*/
/***********************************************************************************/

function getLevels(id){ //obtener las etapas del proyecto (imprimir etapas y columnas).
	//console.log('id del proyecto: ',id);
	var cont = 1; // Contador para los id's de los iconos para mover las columnas
	caracter='"'; // Rodean al id que se le manda a la función hide icon y show icon
	var identificador="";
	$.ajax({
		url:"php/funciones.php",
		data:{clase:"admin_data",funcion:"getLevels", id:id}, //Variable clase y función se manda a funciones.php
		dataType:"json",
		success:function(resp){
			console.log(resp);
			
			if (resp.length!=0) {//Si existen etapas en el proyecto
				var columnsCount = resp[0][0].length; //Contar las columnas que contiene el proyecto
				$.each(resp, function(i){ //Por Cada elemento del array haz lo siguiente: (imprmiendo las etapas).
					//Imprimiendo las etapas del proyecto:
					//Append Está función sirve para imprimir html 
					$('#boardContent').append('<div id="etapa-'+(i+1)+'" class="hideEtapa container-fluid group_'+this.PKEtapa+' grupo" data-group='+this.PKEtapa+'><div class="contenedor groupBox" onmouseenter="optionGroup('+this.PKEtapa+')" onmouseleave="hideOptionGroup('+this.PKEtapa+')"><div class="encabezado-grupo titulo-etapa estilo-etapa d-flex"><i id="opt-group-'+this.PKEtapa+'" class="opt_group_icon1" style="display:none;" onclick="getCondense('+this.PKEtapa+')"></i><i id="drag-group-'+this.PKEtapa+'" class="opt_group_sort_icon" style="display:none;" onclick="getDrag('+this.PKEtapa+')"></i><div style="padding-top:3px;flex-grow:1;"><span class="color_'+this.PKEtapa+'" style="color:'+this.color+'">'+this.Etapa+'</span></div><div id="append-'+this.PKEtapa+'" class="opt-menu"><i id="menu_group_'+this.PKEtapa+'" class="opt-menu-icon" onclick="menuGroup('+this.PKEtapa+')"></i></div><button id='+this.PKEtapa+' class="btnColorPicker btn ignore-elements picker_'+this.PKEtapa+'" style="float:right;background:'+this.color+'" data-color='+this.color+'></button></div><div id="columnas-principales-etapa-'+this.PKEtapa+'" class="etapas order_'+(i+1)+' d-flex"></div</div>');
					$('#etapa-'+(i+1)).append("<div id='tabla-id-"+this.PKEtapa+"' class='items order_ta_"+(i+1)+"'  data-tab='"+this.PKEtapa+"'></div><div class='mb-5 ml-1 agregarTarea et_id_"+this.PKEtapa+"'><span onclick='addTask("+this.PKEtapa+","+caracter+this.color+caracter+")'>+ Agregar</span></div>");//#7a7a7a
																						//onmouseenter='getSortableRows("+caracter+'tabla-id-'+this.PKEtapa+caracter+")'
					//Imprimiendo las columnas del proyecto por cada etapa
					for(j=0;j<columnsCount;j++){
						$('#columnas-principales-etapa-'+resp[i].PKEtapa).append("<div class='columna-tarea text-center header columna_"+resp[i][0][j].PKColumnaProyecto+" et_id_"+this.PKEtapa+"' data-pos='"+resp[i][0][j].PKColumnaProyecto+"' onmouseenter='showIcon("+caracter+'icon_'+cont+caracter+")' onmouseleave='hideIcon("+caracter+'icon_'+cont+caracter+")'><div class='icon_i'><span class='icon icon_"+cont+"' onmouseenter='getSortable()'></span></div><div id='opciones_"+cont+"' class='icon_r'><span class='icon_row icon_"+cont+"' onclick='options_c("+resp[i][0][j].PKColumnaProyecto+","+cont+","+resp[i][0][j].tipo+")'></span></div><span id='column-id-text-"+resp[i][0][j].PKColumnaProyecto+"' class='text column-name-"+resp[i][0][j].PKColumnaProyecto+"'>"+resp[i][0][j].nombre+"</span>")
						
						tipo = resp[i][0][j].tipo; //Tipo de columna (1:Responsable, 2:Estado, 3:Fecha, etc)
						arrColumnas.push(tipo);//LLenando un array para mandar a la búsqueda de la información de esas columnas
						cont++;//Se incrementa el contador para el id de los iconos para mover las columnas
					}

					console.log("ARRCOLUMNAS: ",arrColumnas);

					tablas.push(this.PKEtapa); //Array de los ids de las etapas del proyecto

					var cajaDeColumnas = $('#columnas-principales-etapa-'+this.PKEtapa); //Columnas de cada etapa
					var columnasEtapas = cajaDeColumnas.children(); //hijos del div con el id columnas-principales-etapa- 1, 2, 3 etc
					var children = columnasEtapas.toArray(); //Transformando a array y aisgnanado a la variable children
					columnasArray.push(children);//Agregando cada contenedor de las columnas al array columnasArray

				})
				//console.log('ARRAY DE COLUMNAS DE TODAS LAS ETAPAS', columnasArray);
				//console.log('array de tipos de columnas: ', arrColumnas);
				getTask(id);
			}else{
				console.log('No existen etapas')
				setTimeout(function(){ 
					swal({
						title:'No hay etapas en el proyecto',
						text: '¡Agrega una etapa y comienza un gran proyecto!',
						icon: "warning",
						buttons: {
							cancelar:{
								text:"Cancelar",
								closeModal:true,
								className:"btn-light"
							},
							agregar:{
								text:"¡Agregar etapa!",
								className:"btn-primary",
								closeModal:false,
								value:"addGroup"
							}
						},
					}).then((value) => {
					  switch (value) {
					 
					    case "addGroup":
					      agregarGrupo();
					      swal.close();
					    break;
					 
					    default:
					      swal.close();
					  }
					});
				}, 2050);
				
			}
			
		},
		error:function(error){
			console.log(error)
		}
	})
}

function getTask(id){ //Obtener las tareas del proyecto (imprimir las líneas)
	$.ajax({
		url:"php/funciones.php",
		data:{clase:"admin_data",funcion:"getTask", id:id},//llamando a la funcion de php getTask para obtener las tareas del proyecto.
		dataType:"json",//Mandalos como json
		success:function(resp){
			console.log("f getTask Total de tareas: ",resp);
			numTareas = resp;
			//Imprimiendo las tareas del proyecto según la Etapa a la que pertenecen
			$.each(resp, function(i){ //es igual a un ciclo for
				//console.log(i)
				$('#tabla-id-'+resp[i].FKEtapa).append("<div id='tarea-"+this.PKTarea+"' class='hideTarea contenedor et_id_"+this.FKEtapa+" task' data-ord="+this.PKTarea+" onmouseenter='showOptTask("+this.PKTarea+")' onmouseleave='hideOptTask("+this.PKTarea+")'><div class='encabezado-tarea titulo-item ml-1 sort_task'><div class='rcorners1 backColor_"+this.FKEtapa+"' style='background:"+this.color+"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>&nbsp;<span id='opt-task-"+this.PKTarea+"' class='opt_task_icon' onclick='optionTask("+this.PKTarea+")' style='display:none;'></span><span id='task-name-"+this.PKTarea+"'>"+this.Tarea+"</span></div></div>");
				$('#tarea-'+resp[i].PKTarea).append("<div id='index-"+(i+1)+"' class='index' style='display: flex;'></div>");//???index-
				// <div id='index-"+this.PKTarea+"' style='display: flex;'></div>
			})
			if (arrColumnas.length!=0) { //Si existen columnas:
				//Obtener información de los elementos (cirulo de responsable, la fecha, etc) por cada columna .
				getInfo(arrColumnas, id, resp);
			}else{ //Si no existen columnas:
				//Hacer solamente las tareas sorteables entre las etapas.
				getTablas(tablas);
			}
		},
		error:function(error){
			console.log(error);
		}

	})
}


function getInfo(array, id, num){ //Obtener la información de cada elemento por cada columna
	//array = el tipo de columnas en el poroyecto
	//id = es el id del proyecto (PKProyecto)
	//num = Es el array donde está la información de las tareas.

	//console.log(array);
	
	var arrFiltrado = getUnique(array); //arrFiltrado es igual a lo que me regrese la función getUnique.
	//console.log('NUM EN GETINFO: ', num)
	console.log('array filtrado: ', arrFiltrado);
	$.ajax({
		url:"php/funciones.php",
		data:{clase:"admin_data",funcion:"getInfo", array:arrFiltrado, id:id},
		dataType:"json",
		success:function(resp){
			console.log('respuesta de la info de las tareas: ', resp);
			console.log(resp[0][0].Texto);
			/*PRUEBA DE FUNCIÓN PRINT_ELEMENT
			var specialClass="";
			var paddingClass="";
			var textToShow="";
			*/
			//imprimiendo los elementos:
			$.each(resp[0], function(i){
				specialClass="";
				paddingClass="";
				//textToShow="";
				//console.log(i);
				/*PRUEBA DE FUNCIÓN PRINT_ELEMENT
				if (this.Tipo == 1) {//Si es de "Responsables"
					specialClass="div_r_"+resp[0][i].id+" pos-rel";
					if (this.Texto==null) {//SI no tiene responsable
						textToShow="<i id='no-lead-"+resp[0][i].id+"' class='noLead-icon imgHover imgActive cursorPointer' data-placement='top' data-toggle='leadTip-"+resp[0][i].id+"' title='Agregar responsable' onclick='getLead("+resp[0][i].id+")' onmouseenter='activeToolTip("+resp[0][i].id+")'></i>";
					}else{//Pero si tiene responsable:
						var str = this.Texto;
						var matches = str.match(/\b(\w)/g);
						var acronym = matches.join('');
						textToShow="<div id='lead-"+resp[0][i].id+"' class='avatar-circle mrl-auto imgHover imgActive cursorPointer' onclick='getLead("+resp[0][i].id+")' style='background:#15589b'><span class='initials' data-toggle='leadTip-"+resp[0][i].id+"' data-placement='top' title='"+this.Texto+"' onmouseenter='activeToolTip("+resp[0][i].id+")'>"+acronym+"</span></div>"
					}
				}
				if (this.Tipo==2) {//Si es de tipo "Estado"
					specialClass='no-padding pos-rel estado-tarea-'+resp[0][i].id;
					console.log(resp[0][i].Texto);
					if (resp[0][i].Texto==" ") {//Si el estado no tiene texto
						paddingClass="pad-26px";
					}else{
						paddingClass="pad-15px"
					}
					
					textToShow="<div class='buttons lighten' onclick='getColor("+resp[0][i].id+","+resp[0][i].PKColumnaProyecto+")'><div id='btn-color-"+resp[0][i].id+"' class='blob-btn "+paddingClass+"' style='background:"+resp[0][i].color+"'><span id='btn-text-"+resp[0][i].id+"'>"+resp[0][i].Texto+"</span></div></div>";
				}
				if (this.Tipo==3) {//Si es de tipo "Fecha"
					textToShow="<input id='fecha-"+resp[0][i].id+"' type='date' name='txtFecha' class='form-control' step='1' style='border: 1px solid #ffffff;' onchange='getFecha("+resp[0][i].id+")' value="+resp[0][i].Texto+">";
				}
				if (this.Tipo==4){
					textToShow = "<div><span>"+resp[0][i].Texto+"</span></div>";
				}
				*/
				var textToShow = print_elements(resp[0][i]);
				//console.log("TEXT TO SHOW: ",textToShow)
				$('#index-'+resp[0][i].tOrden).append("<div class='columna-tarea text-center item-name co_"+resp[0][i].PKColumnaProyecto+" "+specialClass+"' style='order: "+(resp[0][i].cOrden-1)+"'>"+textToShow+"</div>");
																																							//data-co='"+(resp[0][i].cOrden-1)+"'
			});
			console.log("Por cada elemento se llena el indexArray en base a esto: ",num)
			
			//Llenando el indexArray que corresponde a los elementos de las tareas.
			$.each(num, function(i){

				var index = $('#index-'+(i+1)); //000 num[i].PKTarea
				var count = index.children();
				var children = count.toArray();
				for(i=0;i<children.length;i++){
					var hijos = children.sort(function(a, b) {
					    return parseFloat(a.style.order) - parseFloat(b.style.order);
					});
					
				}
				//console.log(hijos);

				indexArray.push(hijos);//Primer llenado del indexArray
				//console.log(num[i].FKEtapa);
				
			});


			console.log("Primer llenado del indexArray: ",indexArray)
			//imprimir de nuevo el html en base al indexarray Array de los elementos de las tareas ordenados por la columna
			for(i=0;i<indexArray.length;i++){// Estableciendo el orden para cada elemento.
				for(j=0;j<indexArray[i].length;j++){
					$('#index-'+(i+1)).append(indexArray[i][j]);//
				}
		    }

			getTablas(tablas);
		},
		error:function(error){
			console.log(error);
		}

	});
}


function showIcon(id){//Muestra icono de la columna
	//$('.'+id).show();
	$('.'+id).css('display','inline-block');
}

function hideIcon(id){//Oculta icono de la columna
	$('.'+id).hide();
}

function showOptTask(id){//Muestra icono para opciones de la tarea
	$('#opt-task-'+id).show();
}

function hideOptTask(id){//Oculta icono para opciones de la tarea
	$('#opt-task-'+id).removeClass('colorOptionBlue');
	$('#opt-task-'+id).hide();
	$('.opcionesTarea').remove();
}

/*****************************************/
/*######    SORTEABLE FUNCTIONS     ######*/
/*****************************************/

/*--- SORTEABLE FUNCTIONS ---*/
function getTablas(array){//Hacer cada lista de las etapas sortable (las tareas)
	var options = {
		group:{
			name:"share" //Grupo dentro del cual se pueden intercambiar tareas, todas las tareas son de este grupo
		},
		animation: 150,
		delay: 100, // time in milliseconds to define when the sorting should start
		//easing:"cubic-bezier(0.895,0.03,0.685,0.22)", //Estilo de animación
		handle:'.sort_task',
		chosenClass:"seleccionado",
		ghostClass:"fantasma",
		dragClass:"drag",
		dataIdAttr: "data-ord",
		direction:'vertical',
		onUpdate: function (e) {
			console.log('On update tarea cambio dentro de la misma etapa');
	        var newO = calcPositionTask();
			console.log(newO);
			
	        $.ajax({
				url:"php/funciones.php",
				dataType:"json",
				data:{clase:"data_order",funcion:"etapaOrder",id:idProyecto,ordenArray:newO},
				success:function(resp){
					console.log(resp);
					for(i=0;i<resp.length;i++){//Actualiza el id "index-n" que se relaciona al orden de las tareas en la BBDD y cálcula el cambio según se muevan las columnas
						var newId = "index-"+resp[i].Orden;
						$('#tarea-'+resp[i].PKTarea+' .index').attr('id', newId);
					}

				},
				error:function(error){
					console.log(error);
				}
			});
		},
		onAdd: function (evt) {
			//console.log('On Add cambio de etapa');
			var tareaCambio = evt.item.getAttribute("data-ord");
			var vEtapa = evt.from.getAttribute("data-tab");
			var nEtapa = evt.to.getAttribute("data-tab");
			console.log('Etapa a la que se movio: ', nEtapa);
			console.log("id que cambio de lista: ",tareaCambio);
	        var newO = calcPositionTask();
	        console.log(newO);

	        $('#tarea-'+tareaCambio).removeClass('et_id_'+vEtapa);
	        $('#tarea-'+tareaCambio).addClass('et_id_'+nEtapa);
	        $('#tarea-'+tareaCambio+' .rcorners1').removeClass('backColor_'+vEtapa);
	        $('#tarea-'+tareaCambio+' .rcorners1').addClass('backColor_'+nEtapa);

	        var theColor = document.getElementById(nEtapa).style.background;
	        
	        console.log('el color de la nueva etapa: ',theColor);

	        var estilo = $('#tarea-'+tareaCambio+' .backColor_'+nEtapa);
	        console.log(estilo);
	        $.each(estilo, function(i){
	        	estilo[i].style.background = theColor;
	        })
	        

	        tieneClase=$('.group_'+nEtapa).hasClass('condenseGroup');
	        console.log('tiene la clase consenseGroup?: ',tieneClase);
	        if (tieneClase) {
	        	$('#tarea-'+tareaCambio).addClass('d-no');
	        }

	        $.ajax({
				url:"php/funciones.php",
				dataType:"json",
				data:{clase:"data_order",funcion:"tablaOrder",id:idProyecto,ordenArray:newO,tarea:tareaCambio,etapa:nEtapa},
				success:function(resp){
					console.log(resp);
					for(i=0;i<resp.length;i++){//Actualiza el id "index-n" que se relaciona al orden de las tareas en la BBDD y cálcula el cambio según se muevan las columnas
						var newId = "index-"+resp[i].Orden;
						$('#tarea-'+resp[i].PKTarea+' .index').attr('id', newId);
					}
				},
				error:function(error){
					console.log(error);
				}
			});
		}
	};
	//console.log('opciones ',options)
	$.each(array, function(i){
		//console.log(array)
		sorteable = document.getElementById('tabla-id-'+array[i])
		sor = Sortable.create(sorteable,options);
	});
}

function getSortable(id){//función cuando se mueve una columna
	$('.opcionesColumna').remove();
	sortable = "";
	var padre = $(event.target).parents(); //contenedores del "manubrio" de la columna para obtener id y clase
    var sortableId = padre[2].id; //Los divs que serán sortables.
    handleClass = padre[0].classList[0]; //El "manubrio"
    //console.log(handleClass);

    var options = {
    	group:"columnas",
		animation: 150,
		delay: 100, // time in milliseconds to define when the sorting should start
		//easing:"cubic-bezier(0.895,0.03,0.685,0.22)", //Estilo de animación
		handle:'.'+handleClass,
		direction:'horizontal',
		chosenClass:"seleccionado",
		ghostClass:"fantasma",
		dragClass:"drag",
		dataIdAttr: "data-pos",
		onEnd: function(evt){
			item_movido = evt.item;
			//console.log('item que se movio: ', item_movido);
			oldPosition = evt.oldIndex;
			//console.log('vieja position ',oldPosition);
			position = evt.newIndex;
			//console.log('nueva position ',position);
			calcPosition(columnasArray,indexArray,oldPosition,position);
		},
		store: {
			set: function(sortable){
				console.log('sortable en "set": ',sortable)
				orden = sortable.toArray();
				console.log(orden);
				
				$.ajax({
					url:"php/funciones.php",
					dataType:"json",
					data:{clase:"data_order",funcion:"columnOrder",id:idProyecto,ordenArray:orden},
					success:function(resp){
						console.log(resp);
					},
					error:function(error){
						console.log(error);
					}
				});
				
			}
		}
	};

    var columnas = document.getElementById(sortableId)

    sortable = Sortable.create(columnas,options);
    //console.log('SE CREA EL SORTABLE: ', sortable)
}

function getDrag(id){ //Función para mover las etapas de posición
	sortableGroups = "";
	var opcionesEtapa = {
		group: {
			name: "sortable-list"
		},
		animation: 250,
		fallbackTolerance: 0,
		forceFallback: true,
		dataIdAttr: "data-group",
		filter:'.ignore-elements',
		
		filter: function(evt){
			$('.popover').remove();
			elId = evt.target.getAttribute('id');
			picker = elId;
			console.log("PICKER: ",elId)
			var color = $('.picker_'+elId).bcp();
			console.log(color);
		},
		
		handle:".opt_group_sort_icon",
		onMove: function () {
			$('.opcionesGrupo').remove();
			$('.opt-menu-icon').removeClass('colorMenuBlue');
			$('.opt-menu').hide();
			for (var i = 0; i < tablas.length; i++) {
				$('.group_'+tablas[i]).addClass('condenseGroup');
				$('.task').addClass('d-no');
				$('.header').addClass('d-no');
				$('.agregarTarea').addClass('d-no');

				$('#drag-group-'+tablas[i]).removeClass('opt_group_sort_icon');
				$('#drag-group-'+tablas[i]).addClass('opt_group_icon2');

				$('#opt-group-'+tablas[i]).remove();
				$('.group_'+tablas[i]+' .groupBox .titulo-etapa').prepend('<i id="opt-group-'+tablas[i]+'" class="no-condense-icon" style="display:none;" onclick="noCondense2('+tablas[i]+')"></i>');

			}
		},
		onEnd: function(evt){
			item_movido = evt.item;
			//console.log('item que se movio: ', item_movido);
			oldPosition = evt.oldIndex;
			//console.log('vieja position ',oldPosition);
			position = evt.newIndex;
			//console.log('nueva position ',position);
			/*
			for (var i = 0; i < tablas.length; i++) {

				$('#drag-group-'+tablas[i]).removeClass('opt_group_icon2');
				$('#drag-group-'+tablas[i]).addClass('opt_group_sort_icon');

				$('#opt-group-'+tablas[i]).remove();
				$('.group_'+tablas[i]+' .groupBox .titulo-etapa').prepend('<i id="opt-group-'+tablas[i]+'" class="no-condense-icon" style="display:none;" onclick="noCondense('+tablas[i]+')"></i>');

			}*/

			showAllTask();
			$('.opt-menu').show();
			//$('#abrirEtapas').show();
		},
		store: {
			set: function(sortable){
				orden = sortable.toArray();
				console.log(orden);

				$.ajax({
					url:"php/funciones.php",
					dataType:"json",
					data:{clase:"data_order",funcion:"groupOrder",id:idProyecto,ordenArray:orden},
					success:function(resp){
						console.log(resp);
						if (resp.info == "tareas") {//Que la etapa tenga tareas
							
							console.log('se actualizan las tareas y las etapas');
							for(i=0;i<resp[0].length;i++){//Actualiza el id "index-n" que se relaciona al orden de las tareas en la BBDD y cálcula el cambio según se muevan las columnas
								var newId = "index-"+resp[0][i].Orden;
								$('#tarea-'+resp[0][i].PKTarea+' .index').attr('id', newId);
							}

							for(i=0;i<resp[1].length;i++){//Actualiza el id etapa-, clases order_ta_, order_
								newId="etapa-"+resp[1][i]['Orden'];
								$('.group_'+resp[1][i]['PKEtapa']).attr('id', newId);
								$('#columnas-principales-etapa-'+resp[1][i]['PKEtapa']).removeClass();
								$('#columnas-principales-etapa-'+resp[1][i]['PKEtapa']).addClass('etapas order_'+resp[1][i]['Orden']+' d-flex');
								$('#tabla-id-'+resp[1][i]['PKEtapa']).removeClass();
								$('#tabla-id-'+resp[1][i]['PKEtapa']).addClass('items order_ta_'+resp[1][i]['Orden']);
							}

						}else{
							for(i=0;i<resp[0].length;i++){//Actualiza el id etapa-, clases order_ta_, order_
								newId="etapa-"+resp[0][i]['Orden'];
								$('.group_'+resp[0][i]['PKEtapa']).attr('id', newId);
								$('#columnas-principales-etapa-'+resp[0][i]['PKEtapa']).removeClass();
								$('#columnas-principales-etapa-'+resp[0][i]['PKEtapa']).addClass('etapas order_'+resp[0][i]['Orden']+' d-flex');
								$('#tabla-id-'+resp[0][i]['PKEtapa']).removeClass();
								$('#tabla-id-'+resp[0][i]['PKEtapa']).addClass('items order_ta_'+resp[0][i]['Orden']);
							}
						}

						sortableGroups = "";
					},
					error:function(error){
						console.log(error);
					}
				});
			}
		}
	};
	containers = document.getElementById("boardContent");
	sortableGroups = Sortable.create(containers,opcionesEtapa);
	
	//new Sortable(containers, opcionesEtapa); //PRIMERA
}

function destroySortable(){//Destruye el objeto sorteable, no se utiliza por el momento
	//console.log('SE DESTRUYE EL SORTABLE')
	sortable.destroy();
}

/**********************************************************/
/*######    CALCULAR POSICIONES DE ELEMENTOS        ######*/
/**********************************************************/

function calcPosition(arrCol, arrIndex, oldPosition, newPosition){//Calcula la posición según la columna y elementos.
	for(i=0;i<arrCol.length;i++){
		arrCol[i].splice(newPosition, 0, arrCol[i].splice(oldPosition, 1)[0]);
	}
    
    //arrCol.splice(newPosition, 0, arrCol.splice(oldPosition, 1)[0]);
	for(i=0;i<arrCol.length;i++){// Estableciendo el orden para cada elemento.
		for(j=0;j<arrCol[i].length;j++){
			//arrCol[i][j].style.order = j;
			$('.order_'+(i+1)).append(arrCol[i][j])
		}
    }

    console.log("indexArray dentro del cálculo al mover columnas: ",arrIndex);
    for(i=0;i<arrIndex.length;i++){// Estableciendo la nueva posición del elemento (persona, estado, etc)
		//console.log(arrIndex[i]);
		arrIndex[i].splice(newPosition, 0, arrIndex[i].splice(oldPosition, 1)[0]);
					//index(posición), elementos a eliminar, (elemento que se va a agregar(index, elemento que se elimina))
    }

    //arrIndex.splice(newPosition, 0, arrIndex.splice(oldPosition, 1)[0]);

	for(i=0;i<arrIndex.length;i++){// Estableciendo el orden para cada elemento.
		
		for(j=0;j<arrIndex[i].length;j++){
		
    		arrIndex[i][j].style.order = j;
    		$('#index-'+(i+1)).append(arrIndex[i][j]);//
    	}
    }
}

function calcPositionTask(){
	var result=[];
	console.log('TOTAL ETAPAS: ', tablas)
	for(i=0;i<tablas.length;i++){//lengthEtapas
		var cajaDeTablas = $('.order_ta_'+(i+1));
		var tablasEtapas = cajaDeTablas.children();
		//console.log(tablasEtapas)
		for (var j = 0; j < tablasEtapas.length; j++) {
            result.push($(tablasEtapas[j]).data('ord'));
        }
		//var children = result.toArray();
	}
	return result;
}

/**********************************************************/
/*###     MODALES DE OPCIONES PARA LOS ELEMENTOS       ###*/
/**********************************************************/

function options_c(id, cont, tipo){//Crea un modal de opciones para la columna
	$('.opcionesGrupo').remove();
	$('.opt-menu-icon').removeClass('colorMenuBlue');
	$('.opcionesColumna').remove();//Se remueve en cada llamado a la función
	//console.log('Diste clic en la columna con id: ', id)
	//console.log('Diste clic en la columna con icon: ', cont)
	//Debajo de cada opción de la columna:
	$('#opciones_'+cont).append('<div id="liOpCo_'+id+'" class="opcionesColumna"><div class="pd-20 columna-opcion"><div class="text-left mr-30"><i class="edit-icon"></i></div><div onclick="editColumn('+id+')"><span>Editar columna</span></div></div><div class="pd-20 columna-opcion"><div class="text-left mr-30"><i class="elim-icon"></i></div><div onclick="eliminarColumna('+id+','+tipo+')"><span>Eliminar columna</span></div></div></div>');
	hide = $('#liOpCo_'+id);
	container = $("div[data-pos='" + id +"']");
}

function optionTask(id){//Crea un modal de opciones para las tareas
	$('.opcionesTarea').remove();
	$('#opt-task-'+id).addClass('colorOptionBlue');
	$('#opt-task-'+id).append('<div class="opcionesTarea"><div class="pd-20 tarea-opcion" onclick="editTask('+id+')"><div class="text-left mr-30"><i class="edit-icon"></i></div><div><span>Editar tarea</span></div></div><div class="pd-20 tarea-opcion" onclick="eliminarTarea('+id+')"><div class="text-left mr-30"><i class="elim-icon"></i></div><div><span>Eliminar tarea</span></div></div></div>')
}

function menuGroup(id){//Crea un modal de opciones para la etapa
	$('.opcionesColumna').remove();
	$('.opcionesGrupo').remove();
	$('#menu_group_'+id).addClass('colorMenuBlue');
	$('#append-'+id).append('<div class="opcionesGrupo"><div class="pd-20 grupo-opcion" onclick="editarGrupo('+id+')"><div class="text-left mr-30"><i class="edit-icon"></i></div><div><span>Editar etapa</span></div></div><div class="pd-20 grupo-opcion" onclick="eliminarGrupo('+id+')"><div class="text-left mr-30"><i class="elim-icon"></i></div><div><span>Eliminar etapa</span></div></div></div>')
	container = $(".opt-menu");
	hide = $(".opcionesGrupo");
	mCaracter=$('.opt-menu-icon');
}

function optionGroup(id){//Muestra las opciones del grupo (etapa) contraer, mover
	$('#opt-group-'+id).show();
	$('#drag-group-'+id).show();
}

function hideOptionGroup(id){//Oculta las opciones del grupo (etapa) contraer, mover
	$('#opt-group-'+id).hide();
	$('#drag-group-'+id).hide();
}

/**********************************************************/
/*######    AGREGAR, ELIMINAR, EDITAR COLUMNAS      ######*/
/**********************************************************/

function getColumn(type){//Agregar columna al proyecto.
	$('.agregarColumna i').removeClass('fa-times-circle');
	$('.agregarColumna i').addClass('fa-plus-circle');
	$('.listaColumnas').hide();
	if (type == "Estado") {
		var tipo = 2;
		var tabla = "estado_tarea";
	}else if(type == "Responsables"){
		var tipo = 1;
		var tabla = "responsables_tarea"
	}else if(type == "Fecha"){
		var tipo = 3;
		var tabla = "fecha_tarea";
	}else if(type == "hypervinculo"){
		var tipo = 3;
		var tabla = "hypervinculo";
	}
	var icon=0;
	var elementText="";
	$.ajax({
		url:"php/funciones.php",
		data:{clase:"add_data",funcion:"addColumn",id:idProyecto,tipo:tipo,tabla:tabla},
		dataType:"json",
		success:function(resp){
			console.log(resp);
			if (resp == "noGroups") {//Si no hay etapas en el proyecto:
				console.log("no hay etapas en el proyecto, agrega una")
				swal({
					title:'No hay etapas en el proyecto',
					text: '¡No puedes agregar columnas sin etapas!',
					icon: "warning",
					buttons: {
						cancelar:{
							text:"Cancelar",
							closeModal:true,
							className:"btn-light"
						},
						agregar:{
							text:"¡Agregar etapa!",
							className:"btn-primary",
							closeModal:false,
							value:"addGroup"
						}
					},
				}).then((value) => {
				  switch (value) {
				 
				    case "addGroup":
				      agregarGrupo();
				      swal.close();
				    break;
				 
				    default:
				      swal.close();
				  }
				});
			}else{
				icon = resp.Orden+resp.id; //Identificador único para cada icono de la columna que se va agregando, este se transforma una vez se recarga la página
				//var specialClass = "";PRUEBA DE PRINT_ELEMENTS
				//var elementText="";PRUEBA DE PRINT_ELEMENTS

				for(i=0;i<tablas.length;i++){//Por cada etapa en el proyecto imprime la nueva columna
					var got = $('.group_'+tablas[i]).hasClass('condenseGroup');//Tiene la clase que contrae la etapa?																																														//style='order: "+(resp.Orden-1)+"'
					$('#columnas-principales-etapa-'+tablas[i]).append("<div class='columna-tarea text-center header columna_"+resp.id+" et_id_"+tablas[i]+"' data-pos='"+resp.id+"' onmouseenter='showIcon("+caracter+'icon_0'+icon+caracter+")' onmouseleave='hideIcon("+caracter+'icon_0'+icon+caracter+")'><div class='icon_i'><span class='icon icon_0"+icon+"'' onmouseenter='getSortable()'></span></div><div></div><div id='opciones_n"+icon+"' class='icon_r'><span class='icon_row icon_0"+icon+"' onclick='options_c("+resp.id+","+caracter+"n"+icon+""+caracter+","+resp.Tipo+")' style='display:none;'></span></div><span id='column-id-text-"+resp.id+"' class='text column-name-"+resp.id+"'>"+resp.Nombre+"</span>");
					icon=icon+columnasArray[0].length;

					if (got) {
						$('.et_id_'+tablas[i]).addClass('d-no');//lengthEtapas por tablas(que sólo trae id de cada etapa)
					}
				}

				for(i=0;i<numTareas.length;i++){//Por cada tarea del proyecto imprime el nuevo elemento default
					specialClass="";
					paddingClass="";
					/*PRUEBA FUNCION PRINT_ELEMENTS
					if (resp.Tipo == 1) {//Si es de tipo responsable
						specialClass="div_r_"+resp[0][i]+" pos-rel";
						elementText ="<i id='no-lead-"+resp[0][i]+"' class='noLead-icon imgHover imgActive cursorPointer' data-placement='top' data-toggle='leadTip-"+resp[0][i]+"' title='Agregar responsable' onclick='getLead("+resp[0][i]+")' onmouseenter='activeToolTip("+resp[0][i]+")'></i>";
					}
					if (resp.Tipo == 2) {
						specialClass="no-padding pos-rel estado-tarea-"+resp.id_color;
						
						elementText="<div class='buttons lighten'  onclick='getColor("+resp.id_color+","+resp.id+")'><div id='btn-color-"+resp.id_color+"' class='blob-btn pad-26px' style='background:#9a9a9a;'><span id='btn-text-"+resp.id_color+"'></span></div></div>";

					}
					if (resp.Tipo == 3) {//Si es de tipo fecha
						elementText = "<input id='fecha-"+resp[0][i]+"' type='date' name='txtFecha' class='form-control' step='1' style='border: 1px solid #ffffff;' onchange='getFecha("+resp[0][i]+")' value="+resp[0][i].Texto+">";
					}
					*/
					var elementText = print_elements(resp)
					//console.log('texto devuelto por print_elements: ', elementText)
					$('#index-'+(i+1)).append("<div class='columna-tarea text-center item-name co_"+resp.PKColumnaProyecto+" "+specialClass+"' style='order: "+(resp.Orden+1)+"'>"+elementText+"</div>");
				}//fIN DEL FOR 																												  //data-co='"+(resp.Orden+1)+"'	

				var cant = columnasArray.length;
				var count = indexArray.length;
				columnasArray = [];

				//Actualizando el array de las columnas.
				for(i=0;i<cant;i++){
					var cajaDeColumnas = $('#columnas-principales-etapa-'+tablas[i]);
					var columnasEtapas = cajaDeColumnas.children(); //hijos del div con el id columnas-principales-etapa- 1, 2, 3 etc
					var children = columnasEtapas.toArray();//Transformando a Array
					columnasArray.push(children);
				}
				//Actualizando el array de la información de las tareas.
				indexArray = [];
				for(i=0;i<numTareas.length;i++){
					var index = $('#index-'+(i+1));
					var count = index.children();
					var children = count.toArray();
					indexArray.push(children);
				}
			}
			
		},
		error:function(error){
			console.log(error);
		}

	});
}

function editColumn(id){
	console.log("id de la columna: ", id);
	var placeholder = $('#column-id-text-'+id).text();
	console.log(placeholder);
	
	swal({
		text: 'Nuevo nombre de la columna '+placeholder+':',
		content:{
	  		element:"input",
	  		attributes:{
	  		placeholder:"Nombre de la columna"
	  	}
	  },
	  button: {
	    text: "Editar",
	    closeModal: false,
	    className:"btn-primary"
	  },
	}).then(name=>{//name: lo que el usuario ponga en el input.
		if (name=="" || !name){ //Si el usuario no pone nada 
			swal.close();
		}else{
			$.ajax({
				url:"php/funciones.php",
				dataType:"json",
				data:{clase:"edit_data",funcion:"editColumn",id_columna:id,nombre:name},
				success:function(resp){
					console.log(resp)
					$('.column-name-'+id).text(name);
					swal.close();
					swal({
						text:"Se ha editado el nombre de la columna",
						icon:"success",
						buttons:{
							Volver:{
								className:"btn-primary",
							}
						}
					});
				},
				error:function(error){
					console.log(error)
				}
			})
		}
	})
	

}

function eliminarColumna(id, tipo){
	console.log(tipo);
	$.ajax({
		url:"php/funciones.php",
		data:{clase:"elim_data",funcion:"elimColumn", id:id, tipo:tipo},
		dataType:"json",
		success:function(resp){
			console.log(resp);
			if (resp=="ok") {
				//remover los elementos del DOM
				$('.columna_'+id).remove();
				$('.co_'+id).remove();
				//rellenar el array de columnas y elementos de las tareas.
				var cant = columnasArray.length;
				var count = indexArray.length;
				columnasArray = [];
				//Actualizando el array de las columnas.
				for(i=0;i<cant;i++){
					var cajaDeColumnas = $('#columnas-principales-etapa-'+tablas[i]);//lengthEtapas
					var columnasEtapas = cajaDeColumnas.children(); //hijos del div con el id columnas-principales-etapa- 1, 2, 3 etc
					var children = columnasEtapas.toArray();//Transformando a Array
					columnasArray.push(children);
				}
				//Actualizando el array de la información de las tareas.
				indexArray = [];
				for(i=0;i<numTareas.length;i++){
					var index = $('#index-'+(i+1));
					var count = index.children();
					var children = count.toArray();
					indexArray.push(children);
				}
				console.log('Nuevo arr columns: ', columnasArray);
				console.log('Nuevo arr index: ', indexArray);
			}
		},
		error:function(error){
			console.log(error);
		}
	});
}

/**********************************************************/
/*######    AGREGAR, ELIMINAR, EDITAR TAREAS        ######*/
/**********************************************************/

function addTask(id_etapa,color){//Agregar una tarea (fila) al proyecto
	$.ajax({
		url:"php/funciones.php",
		dataType:"json",
		data:{clase:"add_data",funcion:"addTask",id_etapa:id_etapa,id_proyecto:idProyecto},
		success:function(resp){
			console.log("Respuesta al agregar tarea: ",resp);
			console.log("IndexArray estaba así antes de agregar tarea al HTML: ",indexArray)
			numTask = indexArray.length+1;
			var theColor = document.getElementById(id_etapa).style.background;
			//Si la respuesta no es undefined significa que existen columnas en el proyecto.
			if (resp.length!=undefined) {
				console.log('existe al menos una columna en el proyecto')
				for(i=0;i<resp[1].length;i++){//Actualiza el id "index-n" que se relaciona al orden de las tareas en la BBDD y cálcula el cambio según se muevan las columnas
					var newId = "index-"+resp[1][i].Orden;
					$('#tarea-'+resp[1][i].PKTarea+' .index').attr('id', newId);
				}

				$('#tabla-id-'+resp[0][0][0].FKEtapa).append("<div id='tarea-"+resp[0][0][0].FKTarea+"' class='contenedor et_id_"+id_etapa+" task' data-ord="+resp[0][0][0].FKTarea+" onmouseenter='showOptTask("+resp[0][0][0].FKTarea+")' onmouseleave='hideOptTask("+resp[0][0][0].FKTarea+")'><div class='encabezado-tarea titulo-item ml-1'><div class='rcorners1 backColor_"+id_etapa+"' style='background:"+theColor+"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>&nbsp;<span id='opt-task-"+resp[0][0][0].FKTarea+"' class='opt_task_icon' onclick='optionTask("+resp[0][0][0].FKTarea+")' style='display:none;'></span><span id='task-name-"+resp[0][0][0].FKTarea+"'>"+resp[0][0][0].Tarea+"</span></div></div>");
				$('#tarea-'+resp[0][0][0].FKTarea).append("<div id='index-"+resp[0][0][0].tOrden+"' class='index' style='display: flex;'></div>");//???index-
				
				$.each(resp[0][0],function(i){
					specialClass="";
					paddingClass="";
					/*PRUEBA DE FUNCION PRINT_ELEMENTS
					if (resp[0][0][i].tipo == 1) {//Si es de "Responsables"
						specialClass="div_r_"+resp[0][0][i].id+" pos-rel";
						if (resp[0][0][i].Texto==null) {
							textToShow="<i id='no-lead-"+resp[0][0][i].id+"' class='noLead-icon imgHover imgAction cursorPointer' data-placement='top' data-toggle='leadTip-"+resp[0][0][i].id+"' onclick='getLead("+resp[0][0][i].id+")' onmouseenter='activeToolTip("+resp[0][0][i].id+")' data-original-title='Agregar responsable'></i>";
						}else{
							var str = resp[0][0][i].Texto;
							var matches = str.match(/\b(\w)/g);
							var acronym = matches.join('');
							textToShow="<div id='lead-"+resp[0][0][i].id+"' class='avatar-circle mrl-auto imgHover imgActive cursorPointer' onclick='getLead("+resp[0][0][i].id+")' style='background:#15589b'><span class='initials' data-toggle='leadTip-"+resp[0][0][i].id+"' data-placement='top' title='"+str+"' onmouseenter='activeToolTip("+resp[0][0][i].id+")'>"+acronym+"</span></div>";
						}
					}
					if (resp[0][0][i].tipo==2) {//Si es de "Estado"
						specialClass="no-padding pos-rel estado-tarea-"+resp[0][0][i].FKColorColumna;
						if (resp[0][0][i].Texto==" ") {//Si el estado no tiene texto
							paddingClass="pad-26px";
						}else{
							paddingClass="pad-15px"
						}
						textToShow="<div class='buttons lighten' onclick='getColor("+resp[0][0][i].FKColorColumna+","+resp[0][0][i].FKColumnaProyecto+")'><div id='btn-color-"+resp[0][0][i].FKColorColumna+"' class='blob-btn "+paddingClass+"' style='background:"+resp[0][0][i].color+";'><span id='btn-text-"+resp[0][0][i].FKColorColumna+"'>"+resp[0][0][i].Texto+"</span></div></div>";
					}
					if (resp[0][0][i].tipo==3) {//Si es de "Fecha"
						textToShow="<input id='fecha-"+resp[0][0][i].id+"' type='date' name='txtFecha' class='form-control' step='1' style='border: 1px solid #ffffff;' onchange='getFecha("+resp[0][0][i].id+")'>";
					}
					*/
					var textToShow = print_elements(resp[0][0][i]);
					//console.log("TEXT TO SHOW: ",textToShow)
					//console.log("specialClass retorno de la funcion: ", specialClass);
					$('#index-'+resp[0][0][0].tOrden).append("<div class='columna-tarea text-center item-name co_"+resp[0][0][i].PKColumnaProyecto+" "+specialClass+"'  style='order: "+(resp[0][0][i].cOrden-1)+"'>"+textToShow+"</div>");
				});																																					  //data-co='"+(resp[0][0][i].cOrden-1)+"'	

				//Actualizando el array de la información de las tareas con la nueva tarea.
				var index = $('#index-'+resp[0][0][0].tOrden);
				var count = index.children();
				var children = count.toArray();
				indexArray.push(children);
				//Actualizando el número de Tareas.
				numTareas.push(children);

				console.log("indexArray después de agregar tarea: ",indexArray);
				console.log("numTareas después de agregar tarea: ",numTareas);
			}else{//No existen columnas en el proyecto.
				for(i=0;i<resp[0].length;i++){//Actualiza el id "index-n" que se relaciona al orden de las tareas en la BBDD y cálcula el cambio según se muevan las columnas
					var newId = "index-"+resp[0][i].Orden;
					$('#tarea-'+resp[0][i].PKTarea+' .index').attr('id', newId);
				}

				$('#tabla-id-'+resp["id_etapa"]).append("<div id='tarea-"+resp["id_tarea"]+"' class='contenedor et_id_"+resp["id_etapa"]+" task' data-ord="+resp["id_tarea"]+" onmouseenter='showOptTask("+resp["id_tarea"]+")' onmouseleave='hideOptTask("+resp["id_tarea"]+")'><div class='encabezado-tarea titulo-item ml-1'><div class='rcorners1' backColor_"+resp["id_etapa"]+"' style='background:"+theColor+"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>&nbsp;<span id='opt-task-"+resp["id_tarea"]+"' class='opt_task_icon' onclick='optionTask("+resp["id_tarea"]+")' style='display:none;'></span><span id='task-name-"+resp["id_tarea"]+"'>"+resp["nombre"]+"</span></div></div>");
				$('#tarea-'+resp["id_tarea"]).append("<div id='index-"+resp["orden"]+"' class='index' style='display: flex;'></div>");//???index-
				
				var index = $('#index-'+resp["orden"]);
				var children = index.toArray();
				//Actualizando el número de Tareas.
				numTareas.push(children);
				indexArray.push(children);
				console.log("indexArray después de agregar tarea: ",indexArray);
				console.log("numTareas después de agregar tarea: ",numTareas);
			}
			
		},
		error:function(error){
			console.log(error);
		}

	});
}

function editTask(id){
	console.log("id de la tarea: ", id);
	var placeholder = $('#task-name-'+id).text();
	console.log(placeholder);
	swal({
		text: 'Nuevo nombre de la tarea '+placeholder+':',
		content:{
	  		element:"input",
	  		attributes:{
	  		placeholder:"Nombre de la tarea"
	  	}
	  },
	  button: {
	    text: "Editar",
	    closeModal: false,
	    className:"btn-primary"
	  },
	}).then(name=>{//name: lo que el usuario ponga en el input.
		if (name=="" || !name){ //Si el usuario no pone nada 
			swal.close();
		}else{

			$.ajax({
				url:"php/funciones.php",
				dataType:"json",
				data:{clase:"edit_data",funcion:"editTask",id_tarea:id,nombre:name},
				success:function(resp){
					console.log(resp)
					$('#task-name-'+id).text(name);
					swal.close();
					swal({
						text:"Se ha editado el nombre de la tarea",
						icon:"success",
						buttons:{
							Volver:{
								className:"btn-primary",
							}
						}
					});
				},
				error:function(error){
					console.log(error)
				}
			})
		}
	})
}

function eliminarTarea(id){
	$.ajax({
		url:"php/funciones.php",
		data:{clase:"elim_data",funcion:"elimTask", id_tarea:id, id_proyecto:idProyecto},
		dataType:"json",
		success:function(resp){
			//console.log(resp);
			if (resp=="ok") {
				numTareas.splice(0,1);//Remueve una tarea del array numTareas
				$('#tarea-'+id).remove();
				
				indexArray = [];
				for(i=0;i<numTareas.length;i++){
					var index = $('#index-'+(i+1));
					var count = index.children();
					var children = count.toArray();
					indexArray.push(children);
				}
				console.log("IndexArray llenado en eliminar tarea: ",indexArray);
			}else{
				console.log('Cambio el orden de las tareas');
				console.log(resp);

				for(i=0;i<resp.length;i++){//Actualiza el id "index-n" que se relaciona al orden de las tareas en la BBDD y cálcula el cambio según se muevan las columnas
					var newId = "index-"+resp[i].Orden;
					$('#tarea-'+resp[i].PKTarea+' .index').attr('id', newId);
				}

				numTareas.splice(0,1);//Remueve una tarea del array numTareas
				$('#tarea-'+id).remove();

				indexArray = [];
				for(i=0;i<numTareas.length;i++){
					var index = $('#index-'+(i+1));
					var count = index.children();
					var children = count.toArray();
					indexArray.push(children);
				}
			}
		},
		error:function(error){
			console.log(error);
		}
	});
}

/**********************************************************/
/*######    AGREGAR, ELIMINAR, EDITAR ETAPAS        ######*/
/**********************************************************/

$('#agregarEtapa').click(function(){
	agregarGrupo();
});

function agregarGrupo(){
	$.ajax({
		url:"php/funciones.php",
		data:{clase:"add_data",funcion:"addGroup", id_proyecto:idProyecto},
		dataType:"json",
		success:function(resp){
			console.log(resp);

			//-- Imprimiendo la etapa: --//
			$('#boardContent').prepend('<div id="etapa-'+resp.Orden+'" class="container-fluid group_'+resp.PKEtapa+' grupo" data-group='+resp.PKEtapa+'><div class="contenedor groupBox" onmouseenter="optionGroup('+resp.PKEtapa+')" onmouseleave="hideOptionGroup('+resp.PKEtapa+')"><div class="encabezado-grupo titulo-etapa estilo-etapa d-flex"><i id="opt-group-'+resp.PKEtapa+'" class="opt_group_icon1" style="display:none;" onclick="getCondense('+resp.PKEtapa+')"></i><i id="drag-group-'+resp.PKEtapa+'" class="opt_group_sort_icon" style="display:none;" onclick="getDrag('+resp.PKEtapa+')"></i><div style="padding-top:3px;flex-grow:1;"><span class="color_'+resp.PKEtapa+'" style="color:#1c4587;">'+resp.Etapa+'</span></div><div id="append-'+resp.PKEtapa+'" class="opt-menu"><i id="menu_group_'+resp.PKEtapa+'" class="opt-menu-icon" onclick="menuGroup('+resp.PKEtapa+')"></i></div><button id='+resp.PKEtapa+' class="btnColorPicker btn ignore-elements picker_'+resp.PKEtapa+'" style="float:right;background:#1c4587;" data-color="#1c4587">&nbsp;</button></div><div id="columnas-principales-etapa-'+resp.PKEtapa+'" class="etapas order_'+resp.Orden+' d-flex"></div</div>');
			$('#etapa-'+resp.Orden).append("<div id='tabla-id-"+resp.PKEtapa+"' class='items order_ta_"+resp.Orden+"'  data-tab='"+resp.PKEtapa+"'></div><div class='mb-5 ml-1 agregarTarea et_id_"+resp.PKEtapa+"'><span onclick='addTask("+resp.PKEtapa+","+caracter+"#1c4587"+caracter+")'>+ Agregar</span></div>");
			
			//-- Imprimiendo las columnas del proyecto --//
			if (resp[0].length!=0) {//Si hay columnas en el proyecto:
				icon = resp[0][0].orden+resp[0][0].PKColumnaProyecto; //Identificador único para cada icono de la columna que se va agregando, este se transforma una vez se recarga la página
				for(j=0;j<resp[0].length;j++){
					$('#columnas-principales-etapa-'+resp.PKEtapa).append("<div class='columna-tarea text-center header columna_"+resp[0][j].PKColumnaProyecto+" et_id_"+resp.PKEtapa+"' data-pos='"+resp[0][j].PKColumnaProyecto+"' onmouseenter='showIcon("+caracter+'icon_0'+icon+caracter+")' onmouseleave='hideIcon("+caracter+'icon_0'+icon+caracter+")' style='order: "+(resp.Orden-1)+"'><div class='icon_i'><span class='icon icon_0"+icon+"' onmouseenter='getSortable()'></span></div><div id='opciones_n"+icon+"' class='icon_r'><span class='icon_row icon_0"+icon+"' onclick='options_c("+resp[0][j].PKColumnaProyecto+","+caracter+"n"+icon+""+caracter+","+resp[0][j].tipo+")'></span></div><span id='column-id-text-"+resp[0][j].PKColumnaProyecto+"' class='text column-name-"+resp[0][j].PKColumnaProyecto+"'>"+resp[0][j].nombre+"</span>");
					icon=icon+columnasArray[0].length;
				}
			}

			if (resp[1].length != 0) {//Que existen más etapas dentro del proyecto, actualiza orden.
				for(i=0;i<resp[1].length;i++){//Actualiza el id etapa-, clases order_ta_, order_
					newId="etapa-"+resp[1][i]['Orden'];
					$('.group_'+resp[1][i]['PKEtapa']).attr('id', newId);
					$('#columnas-principales-etapa-'+resp[1][i]['PKEtapa']).removeClass();
					$('#columnas-principales-etapa-'+resp[1][i]['PKEtapa']).addClass('etapas order_'+resp[1][i]['Orden']+' d-flex');
					$('#tabla-id-'+resp[1][i]['PKEtapa']).removeClass();
					$('#tabla-id-'+resp[1][i]['PKEtapa']).addClass('items order_ta_'+resp[1][i]['Orden']);
				}
			}


			tablas.push(resp.PKEtapa); //Array de los ids de las etapas del proyecto.
			//lengthEtapas.push(resp); //Array de las etapas del proyecto.

			//Agregando el nuevo array de columnas de la nueva etapa:
			var cajaDeColumnas = $('#columnas-principales-etapa-'+resp.PKEtapa); //Columnas de cada etapa
			var columnasEtapas = cajaDeColumnas.children(); //hijos del div con el id columnas-principales-etapa-
			var children = columnasEtapas.toArray(); //Transformando a array y aisgnanado a la variable children
			columnasArray.push(children);//Agregando cada contenedor de las columnas al array columnasArray

			console.log(columnasArray);

			console.log('array tablas (ids de las etapas del proyecto): ', tablas);
			//console.log('array con ')
			getTablas(tablas);

		},
		error:function(error){
			console.log(error);

		}
	});
}

function eliminarGrupo(id){
	swal("Este grupo será eliminado junto con sus tareas asociadas, ¿Desea continuar?",{
		buttons: {
	    cancel: {
	    	text:"Cancelar",
	    	value:null,
	    	visible:true,
	    	className:"btn-danger",
	    	closeModal:true,
	    },
	    confirm: {
			text: "Eliminar etapa",
			value:"delete",
			visible:true,
			className:"btn-primary",
			closeModal:true,
	    },
	  },
	}).then((value) => {
	  switch (value) {
	 
	    case "delete":
	      console.log('iniciará el ajax');
	      $.ajax({
			url:"php/funciones.php",
			dataType:"json",
			data:{clase:"elim_data",funcion:"elimGroup",id_etapa:id,id_proyecto:idProyecto},
			success:function(resp){
				console.log(resp);
				//Remueve la etapa del DOM:
				$('.group_'+id).remove();
				if (resp.accion=="actualizar") {
					for(i=0;i<resp[0].length;i++){//Actualiza el id etapa-, clases order_ta_, order_
						newId="etapa-"+resp[0][i]['Orden'];
						$('.group_'+resp[0][i]['PKEtapa']).attr('id', newId);
						$('#columnas-principales-etapa-'+resp[0][i]['PKEtapa']).removeClass();
						$('#columnas-principales-etapa-'+resp[0][i]['PKEtapa']).addClass('etapas order_'+resp[0][i]['Orden']+' d-flex');
						$('#tabla-id-'+resp[0][i]['PKEtapa']).removeClass();
						$('#tabla-id-'+resp[0][i]['PKEtapa']).addClass('items order_ta_'+resp[0][i]['Orden']);
					}
				}

				if (resp.accion=="actualizarTareas") {
					for(i=0;i<resp[0].length;i++){//Actualiza el id etapa-, clases order_ta_, order_
						newId="etapa-"+resp[0][i]['Orden'];
						$('.group_'+resp[0][i]['PKEtapa']).attr('id', newId);
						$('#columnas-principales-etapa-'+resp[0][i]['PKEtapa']).removeClass();
						$('#columnas-principales-etapa-'+resp[0][i]['PKEtapa']).addClass('etapas order_'+resp[0][i]['Orden']+' d-flex');
						$('#tabla-id-'+resp[0][i]['PKEtapa']).removeClass();
						$('#tabla-id-'+resp[0][i]['PKEtapa']).addClass('items order_ta_'+resp[0][i]['Orden']);
					}

					for(i=0;i<resp[1].length;i++){//Actualiza el id "index-n" que se relaciona al orden de las tareas en la BBDD y cálcula el cambio según se muevan las columnas
						var newId = "index-"+resp[1][i].Orden;
						$('#tarea-'+resp[1][i].PKTarea+' .index').attr('id', newId);
					}
					var remove = resp.numTareas;
					numTareas.splice(0,remove);//Remueve las tareas del array numTareas
					
					indexArray = [];
					for(i=0;i<numTareas.length;i++){
						var index = $('#index-'+(i+1));
						var count = index.children();
						var children = count.toArray();
						indexArray.push(children);
					}

					console.log('indexarray:  ',indexArray)
					console.log('numTareas: ',numTareas)
				}

				if (resp.accion=="eEtapaATareas") {
					var remove = resp.numTareas;
					numTareas.splice(0,remove);//Remueve las tareas del array numTareas
					
					indexArray = [];
					for(i=0;i<numTareas.length;i++){
						var index = $('#index-'+(i+1));
						var count = index.children();
						var children = count.toArray();
						indexArray.push(children);
					}

					console.log('indexarray: ', indexArray);
					console.log('numTareas: ', numTareas);
				}

				if (resp.accion=="actualizarArray") {
					for(i=0;i<resp[0].length;i++){//Actualiza el id etapa-, clases order_ta_, order_
						newId="etapa-"+resp[0][i]['Orden'];
						$('.group_'+resp[0][i]['PKEtapa']).attr('id', newId);
						$('#columnas-principales-etapa-'+resp[0][i]['PKEtapa']).removeClass();
						$('#columnas-principales-etapa-'+resp[0][i]['PKEtapa']).addClass('etapas order_'+resp[0][i]['Orden']+' d-flex');
						$('#tabla-id-'+resp[0][i]['PKEtapa']).removeClass();
						$('#tabla-id-'+resp[0][i]['PKEtapa']).addClass('items order_ta_'+resp[0][i]['Orden']);
					}

					var remove = resp.numTareas;
					numTareas.splice(0,remove);//Remueve las tareas del array numTareas
					
					indexArray = [];
					for(i=0;i<numTareas.length;i++){
						var index = $('#index-'+(i+1));
						var count = index.children();
						var children = count.toArray();
						indexArray.push(children);
					}
				}

				//actualiza el array tablas que sirve en varias funciones:
				var number = id;
				var string = number.toString();
				var index = tablas.indexOf(string);
				console.log(index);
				if (index > -1) {
				  tablas.splice(index, 1);
				}
				console.log(tablas);

				//Actualiza array de las columnas
				columnasArray = [];
				for(i=0;i<tablas.length;i++){
					var cajaDeColumnas = $('#columnas-principales-etapa-'+tablas[i]);
					var columnasEtapas = cajaDeColumnas.children(); //hijos del div con el id columnas-principales-etapa- 1, 2, 3 etc
					var children = columnasEtapas.toArray();//Transformando a Array
					columnasArray.push(children);
				}

				getDrag();

			},
			error:function(error){
				console.log(error)
			}
	      })
	      break;
	 
	    default:
	      break;
	  }
	});
}

function editarGrupo(id){
	$(".opcionesGrupo").remove();
	var placeholder = $('.color_'+id).text();
	console.log(placeholder)
	console.log('id del grupo: '+id);
	swal({
		text: 'Nuevo nombre de la etapa '+placeholder+':',
		content:{
	  		element:"input",
	  		attributes:{
	  		placeholder:"Nombre de la etapa"
	  	}
	  },
	  button: {
	    text: "Editar",
	    closeModal: false,
	    className:"btn-primary"
	  },
	}).then(name=>{//name: lo que el usuario ponga en el input.
		if (name=="" || !name){ //Si el usuario no pone nada 
			swal.close();
		}else{

			$.ajax({
				url:"php/funciones.php",
				dataType:"json",
				data:{clase:"edit_data",funcion:"editGroup",id_etapa:id,nombre:name},
				success:function(resp){
					console.log(resp)
					$('.color_'+id).text(name);
					swal.close();
					swal({
						text:"Se ha editado el nombre de la etapa",
						icon:"success",
						buttons:{
							Volver:{
								className:"btn-primary",
							}
						}
					});
				},
				error:function(error){
					console.log(error)
				}
			})
		}
	})
}

/**********************************************************/
/*######           FUNCIONES VARIAS                 ######*/
/**********************************************************/

function print_elements(array){//Función para imprimir html adaptado según cada tipo de columna.
	//console.log(array)
	var textToShow="";
	//console.log('entrando a print_elements')
	if (array.Tipo == 1) {//Si es de "Responsables"
		specialClass="div_r_"+array.id+" pos-rel";
		if (array.Texto==null) {//SI no tiene responsable
			textToShow="<i id='no-lead-"+array.id+"' class='noLead-icon imgHover imgActive cursorPointer' data-placement='top' data-toggle='leadTip-"+array.id+"' title='Agregar responsable' onclick='getLead("+array.id+")' onmouseenter='activeToolTip("+array.id+")'></i>";
		}else{//Pero si tiene responsable:
			var str = array.Texto;
			//var matches = str.match(/\b(\w)/g);
			//var acronym = matches.join('');
			//textToShow="<div id='lead-"+array.id+"' class='avatar-circle mrl-auto imgHover imgActive cursorPointer' onclick='getLead("+array.id+")' style='background:#15589b'><span class='initials' data-toggle='leadTip-"+array.id+"' data-placement='top' title='"+array.Texto+"' onmouseenter='activeToolTip("+array.id+")'>"+acronym+"</span></div>"
			textToShow="<div id='lead-"+array.id+"' class='avatar-circle mrl-auto imgHover imgActive cursorPointer' onclick='getLead("+array.id+")' onmouseenter='activeToolTip("+array.id+")' data-toggle='leadTip-"+array.id+"' data-placement='top' title='"+array.Texto+"'><span class='initials'></span></div>"
		}
	}
	if (array.Tipo==2) {//Si es de tipo "Estado"
		specialClass='no-padding pos-rel estado-tarea-'+array.id;
		//console.log(array.Texto);
		if (array.Texto==" ") {//Si el estado no tiene texto
			paddingClass="pad-26px";
		}else{
			paddingClass="pad-15px"
		}
		
		textToShow="<div class='buttons lighten' onclick='getColor("+array.id+","+array.PKColumnaProyecto+")'><div id='btn-color-"+array.id+"' class='blob-btn "+paddingClass+"' style='background:"+array.color+"'><span id='btn-text-"+array.id+"'>"+array.Texto+"</span></div></div>";
	}
	if (array.Tipo==3) {//Si es de tipo "Fecha"
		textToShow="<input id='fecha-"+array.id+"' type='date' name='txtFecha' class='form-control' step='1' style='border: 1px solid #ffffff;' onchange='getFecha("+array.id+")' value="+array.Texto+">";
	}
	if (array.Tipo==4){//Si es de hipervinculo
		textToShow = "<div><span>"+array.Texto+"</span></div>";
	}
	//console.log(specialClass, "specialClass al termina funcion print_elements")
	return textToShow;
}

function getCondense(id){//"Contraer" las tareas dentro de un grupo (etapa)
	$('.et_id_'+id).addClass('d-no');
	$('.group_'+id).addClass('condenseGroup');
	$('#opt-group-'+id).remove();
	$('.group_'+id+' .groupBox .titulo-etapa').prepend('<i id="opt-group-'+id+'" class="no-condense-icon" style="display:none;" onclick="noCondense('+id+')"></i>');
	$('#append-'+id).css('left', '-49px');
}

function noCondense(id){//Mostrar las tareas ocultas de las etapas.
	$('.et_id_'+id).removeClass('d-no');
	$('.group_'+id).removeClass('condenseGroup');
	$('#opt-group-'+id).remove();
	$('.group_'+id+' .groupBox .titulo-etapa').prepend('<i id="opt-group-'+id+'" class="opt_group_icon1" style="display:none;" onclick="getCondense('+id+')"></i>');
	$('#append-'+id).css('left', '-25px');
}

function noCondense2(id){//Ya no me acuerdo si es funcional todavía.
	$('.et_id_'+id).removeClass('d-no');
	$('.group_'+id).removeClass('condenseGroup');
	$('#opt-group-'+id).remove();
	$('.group_'+id+' .groupBox .titulo-etapa').prepend('<i id="opt-group-'+id+'" class="opt_group_icon1" style="display:none;" onclick="getCondense('+id+')"></i>');

	for (var i = 0; i < tablas.length; i++) {

		$('#drag-group-'+tablas[i]).removeClass('opt_group_icon2');
		$('#drag-group-'+tablas[i]).addClass('opt_group_sort_icon');

	}
}

function showAllTask(){//Opción en la parte superior derecha para mostrar todas las tareas ocultas
	for (var i = 0; i < tablas.length; i++) {
		$('.group_'+tablas[i]).removeClass('condenseGroup');
		$('.task').removeClass('d-no');
		$('.header').removeClass('d-no');
		$('.agregarTarea').removeClass('d-no');

		$('#opt-group-'+tablas[i]).removeClass('no-condense-icon');
		$('#opt-group-'+tablas[i]).addClass('opt_group_icon1');

		$('#drag-group-'+tablas[i]).removeClass('opt_group_icon2');
		$('#drag-group-'+tablas[i]).addClass('opt_group_sort_icon');
	}

	$('.opt-menu').css('left', '-25px');

	//$('#abrirEtapas').hide();
}

function hideAllTask(){//Opción en la parte superior derecha para ocultar todas las tareas
	for (var i = 0; i < tablas.length; i++) {
		$('.group_'+tablas[i]).addClass('condenseGroup');
		$('.task').addClass('d-no');
		$('.header').addClass('d-no');
		$('.agregarTarea').addClass('d-no');

		$('#opt-group-'+tablas[i]).addClass('no-condense-icon');

		$('#drag-group-'+tablas[i]).addClass('opt_group_icon2');
	}

	$('.opt-menu').css('left', '-49px');
}

function getUnique(array){//obtiene valores únicos de un array, para mandar la información del tipo de columnas en el proyecto
	var uniqueArray = [];//array vacío para el retorno 
	// Loop through array values
    for(i=0; i < array.length; i++){
        if(uniqueArray.indexOf(array[i]) === -1) {
            uniqueArray.push(array[i]);//llenando el array vacío
        }
    }
    return uniqueArray;//devolviendo el array con los nuevos valores (no repetidos)

}

function seeProjects(){
	$('.project-opt').remove()
	$('#div-projects-list').show()
	$.ajax({
		url:"php/funciones.php",
		data:{clase:"get_data",funcion:"getAllProjects"},
		dataType:"json",
		success:function(resp){
			console.log(resp);
			$.each(resp,function(){
				$('#projects-List').append('<option class="project-opt" value='+this.PKProyecto+'>'+this.Proyecto+'</option>')
			})
			
		},
		error:function(error){
			console.log(error);
		}
	});
	container = $('#select-project');
	hide=$('#div-projects-list');
}

function getLead(id){
	console.log(id)
	$('#avatar-circle').remove();
	$.ajax({
		url:"php/funciones.php",
		data:{clase:"get_data",funcion:"getUsers"},
		dataType:"json",
		success:function(resp){
			console.log(resp);
			if (resp.length!=0) {
				$.each(resp, function(){
					$('#leadList').append('<option value='+this.PKUsuario+'>'+this.nombre_empleado+'</option>');
				});
				$('#leadList').append('<option value="no">Dejar sin responsable</option>');
			}else{
				$('.ss-option .ss-disabled').text('No hay usuarios para mostrar.');
			}
		},
		error:function(error){
			console.log(error);

		}
	});
	$('.leadSelect').remove();//$('#link-'+id).append(<div class="linkSelect pos-abs"><div class="d-flex"><input value="Direccion"><input value="Texto"></div><div>)
	$('.div_r_'+id).append("<div class='leadSelect pos-abs'><select id='leadList'><option value='0'>Seleccione un responsable</option></select></div>");
	var seleccion = new SlimSelect({
        select: '#leadList',
        placeholder: 'Seleccione un responsable',
        searchPlaceholder: 'Buscar usuario',
        onChange:(info)=>{
        	console.log(info.value);
        	console.log(info);
        	var str = info.text;
        	if (info.value=="no") {
        		$.ajax({
	        		url:"php/funciones.php",
					data:{clase:"edit_data",funcion:"noLead",pkR:id},
					dataType:"json",
					success:function(resp){
						console.log(resp);
						$("#no-lead-"+id).remove();
						$("#lead-"+id).remove();
						//var matches = str.match(/\b(\w)/g);
						//var acronym = matches.join('');
						$(".div_r_"+id).append("<i id='no-lead-"+id+"' class='noLead-icon imgHover imgActive cursorPointer' onclick='getLead("+id+")' data-toggle='leadTip-"+id+"' title='Agregar responsable' onmouseenter='activeToolTip("+id+")'></i>");


						$('.leadSelect').remove();
					},
					error:function(error){
						console.log(error);
					}
	        	});
        	}else{
        		$.ajax({
	        		url:"php/funciones.php",
					data:{clase:"edit_data",funcion:"setLead", id:info.value, pkR:id},
					dataType:"json",
					success:function(resp){
						$('#lead-'+id).remove();
						console.log(resp);
						$("#no-lead-"+id).remove();
						//var matches = str.match(/\b(\w)/g);
						//var acronym = matches.join('');
						$(".div_r_"+id).append("<div id='lead-"+id+"' class='avatar-circle mrl-auto imgHover imgActive cursorPointer' onclick='getLead("+id+")' data-toggle='leadTip-"+id+"' data-placement='top' title='"+str+"' onmouseenter='activeToolTip("+id+")'><span class='initials' ></span></div>");
						$('.leadSelect').remove();
					},
					error:function(error){
						console.log(error);
					}
	        	});
        	}
        }
    });
    container = $(".div_r_"+id);
	hide = $(".leadSelect");
}

function getFecha(id){
	console.log(id);
	var fecha = $('#fecha-'+id).val();
	console.log(fecha);

	$.ajax({
		url:"php/funciones.php",
		data:{clase:"admin_data",funcion:"getFecha", id:id, fecha:fecha},
		dataType:"json",
		success:function(resp){
			console.log(resp);
		},
		error:function(error){
			console.log(error);

		}
	})
}

function activeToolTip(id){
	console.log('hola mundo')
	$('[data-toggle="leadTip-'+id+'"]').tooltip()
}

function getColor(id_estado, id_columna){//Opciones de color para columnas estado
	console.log("ID DEL ESTADO_TAREA: ",id_estado," ID DE LA COLUMNA: ", id_columna);
	$('.opcionesColorColumna').remove();
	//Solicitando los colores que corresponden a esa columna estado
	
	$.ajax({
		url:"php/funciones.php",
		data:{clase:"admin_data",funcion:"getColorColumna", id_estado:id_estado, id_columna:id_columna},
		dataType:"json",
		success:function(resp){
			console.log(resp)

			$('.estado-tarea-'+id_estado).append('<div class="opcionesColorColumna"><div id="shadowColors-edit"><div id="color-list-edit" class="d-no colors-container-edit"></div><hr style="margin:0;1px solid #e7e7e7"><div id="colorPicker-add"></div></div><div id="shadowColors"><div id="color-list" class="colors-container"></div><hr style="margin:0;1px solid #e7e7e7"><div id="colorPicker"><span class="m-10">Agregar/Editar</span></div></div></div>');
			$.each(resp,function(i){

				if (resp[i].nombre!=" ") {
					$('#color-list').append('<div id='+resp[i].PKColorColumna+' class="color-container imgActive imgHover" style="background:'+resp[i].color+'" onclick=setColorTask('+resp[i].PKColorColumna+','+id_estado+','+caracter+resp[i].color+caracter+','+caracter+resp[i].nombre+caracter+')>'+resp[i].nombre+'</div>');

				}else{
					$('#color-list').append('<div id='+resp[i].PKColorColumna+' class="color-container imgActive imgHover" style="background:'+resp[i].color+'" onclick=setColorTask('+resp[i].PKColorColumna+','+id_estado+','+caracter+resp[i].color+caracter+',"")>'+resp[i].nombre+'</div>');
				}

				$('#color-list-edit').append('<div id='+resp[i].PKColorColumna+' class="color-container imgActive imgHover" style="background:'+resp[i].color+'">'+resp[i].nombre+'</div>');

			});
			$('#color-list-edit').append('<div class="color-container" style="background:grey">Nueva etiqueta</div>');

			getColor_listWidth()
			getColor_listWidth_edit()
		},
		error:function(error){
			console.log(error)
		}
	});

	//<div class="color-container" style="background:red"></div>
}

function getColor_listWidth(){
	var ancho = document.getElementById("color-list");
	var rows = document.getElementById("color-list").children.length;
	var ancho2 = document.getElementById("colorPicker");
	var ancho3 = document.getElementById("shadowColors");
	console.log(rows);

	if (rows <= 5) {
		ancho.style.width = "150px";
		ancho.style.alignItems = "center";
		ancho2.style.width = "150px";
		ancho2.style.alignItems = "center";
		ancho3.style.width = "150px";
		ancho3.style.alignItems = "center";
	}else if(rows >= 6 && rows <= 10){
		ancho.style.width = "220px";
		ancho.style.alignItems = "normal";
		ancho2.style.width = "220px";
		ancho2.style.alignItems = "normal";
		ancho3.style.width = "220px";
		ancho3.style.alignItems = "normal";
	}else if(rows >= 11 && rows <= 15){
		ancho.style.width = "330px";
		ancho2.style.width = "330px";
		ancho3.style.width = "330px";
	}else if(rows >= 16 && rows <= 20){
		ancho.style.width = "450px";
		ancho2.style.width = "450px";
		ancho3.style.width = "450px";
	}else if(rows >= 21 && rows <= 25){
		ancho.style.width = "550px";
		ancho2.style.width = "550px";
		ancho3.style.width = "550px";
	}
}

function getColor_listWidth_edit(){
	var ancho = document.getElementById("color-list-edit");
	var rows = document.getElementById("color-list-edit").children.length;
	var ancho2 = document.getElementById("colorPicker-add");
	var ancho3 = document.getElementById("shadowColors-edit");
	console.log(rows);

	if (rows <= 5) {
		ancho.style.width = "150px";
		ancho.style.alignItems = "center";
		ancho2.style.width = "150px";
		ancho2.style.alignItems = "center";
		ancho3.style.width = "150px";
		ancho3.style.alignItems = "center";
	}else if(rows >= 6 && rows <= 10){
		ancho.style.width = "220px";
		ancho.style.alignItems = "normal";
		ancho2.style.width = "220px";
		ancho2.style.alignItems = "normal";
		ancho3.style.width = "220px";
		ancho3.style.alignItems = "normal";
	}else if(rows >= 11 && rows <= 15){
		ancho.style.width = "330px";
		ancho2.style.width = "330px";
		ancho3.style.width = "330px";
	}else if(rows >= 16 && rows <= 20){
		ancho.style.width = "450px";
		ancho2.style.width = "450px";
		ancho3.style.width = "450px";
	}else if(rows >= 21 && rows <= 25){
		ancho.style.width = "550px";
		ancho2.style.width = "550px";
		ancho3.style.width = "550px";
	}
}

function setColorTask(id_color,id_estado,color,nombre){
	console.log(color,id_estado,id_color,nombre)
	$('.opcionesColorColumna').remove();
	$.ajax({
		url:"php/funciones.php",
		data:{clase:"admin_data",funcion:"setColorTarea", id_estado:id_estado, id_color:id_color},
		dataType:"json",
		success:function(resp){
			console.log(resp)
			if (resp == "ok") {
				var btnColor = document.getElementById('btn-color-'+id_estado);
				btnColor.style.background=color;

				if (nombre=="") {
					console.log('quito 15 y pongo 26')
					$('#btn-color-'+id_estado).removeClass('pad-15px');
					$('#btn-color-'+id_estado).addClass('pad-26px');
				}

				if (nombre!="") {
					console.log('quito 26 y pongo 15')
					$('#btn-color-'+id_estado).removeClass('pad-26px');
					$('#btn-color-'+id_estado).addClass('pad-15px');
				}
				$('#btn-text-'+id_estado).text();
				$('#btn-text-'+id_estado).text(nombre);
			}else{
				console.log(resp);
			}
		},
		error:function(error){
			console.log(error)
		}
	})

}

function goAway(){
	$(".goaway").fadeOut(2000,function(){
		$('#page-content-wrapper').append('<div id="boardContent" class="board-content" style="min-height: 300px;"></div>');
		$('#boardContent').fadeIn("fast");
	});
}
/************************<div class="columna-tarea text-center item-name co_18 no-padding pos-rel estado-tarea-1822" data-co="0" style="order: 0">**********************************/
/*######               EVENTOS VARIOS               ######*/
/**********************************************************/

//evento que llamará a la función para agregar columnas al proyecto
$('.listaColumnas').click(function(event){
	var clicked = $(event.target).text(); //Texto: Estado, Fecha, Personas
	console.log(clicked);
	getColumn(clicked);
	container = $(".fa-times-circle");
	hide = $(".listaColumnas");
});

//evento que llamará a la función para opciones de columnas al proyecto
$('.opcionesColumna').click(function(event){
	var clicked = $(event.target).text(); //Texto: eliminar, editar, etc.
	console.log(clicked);
});

//Buscador
//evento cuando se presiona una tecla
$('#search-input').keyup(function(){
	$('#sin-coincidencias').remove();
	var valor = $('#search-input').val();
	console.log("VALOR DEL INPUT",valor);
	$.ajax({
		url:"php/funciones.php",
		data:{clase:"buscar_data", funcion:"buscar_tarea", usuarioInput:valor,id:idProyecto},
		dataType:"json",
		
		success:function(respuesta){
			console.log(respuesta);
			$('#sin-coincidencias').remove();
				if (respuesta.length>0){
					console.log(respuesta);
					$('.hideEtapa').hide();
					$('.hideTarea').hide();
					$.each(respuesta, function(i){
						//console.log("ID: ",respuesta[i].FKEtapa);
						//console.log("ID TAREA: ",respuesta[i].PKTarea);
						//$('.opt-group'+respuesta[i].PKEtapa).show();
						$('.group_'+respuesta[i].PKEtapa).show();
						$('#tarea-'+respuesta[i].PKTarea).show();
					});
					
				}else{
					$('.hideEtapa').hide();
					$('.hideTarea').hide();
					$('#boardContent').append('<div id="sin-coincidencias" class="text-center"><img src="img/icons/fail.svg" width="80" height="80"></br></br><h1 class="h5 text-blutTim">No se encontraron coincidencias en la búsqueda</h1></div>')
				}
			
			//$('.claseEtapa').hide()//función remove remueve o quita la información
		},
		error:function(error){
			console.log(error);
		}
	})
})

$(document).on("click",function(e) {
	
	console.log('evento: ',e) 
	console.log(container)
	console.log(hide)
	//console.log(oCaracter)
	if (container!="") {
		if (!container.is(e.target) && container.has(e.target).length === 0) { 
			console.log('se ejecuta')
	   		hide.hide();

	   		if (oCaracter!="" && oCaracter) {
		   		var tieneClase = $('.agregarColumna i').hasClass('close-icon');
				if (tieneClase) {
					$('.agregarColumna i').removeClass('close-icon');
					$('.agregarColumna i').addClass('plus-icon');
				}
		   	} 

		   	if (mCaracter!="") {
		   		$('.opt-menu-icon').removeClass('colorMenuBlue');
		   	}

		   	container = "";
		   	hide="";
		   	oCaracter = "";
	   	}
	}

});

getDrag(); //Función que hará las etapas sorteables.
