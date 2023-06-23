var columnas="";
var handleClass;
var sortable;
var arrColumnas=[];
var tipo;
var indexArray=[]; //Se llenará con la información de las tareas.
var columnasArray=[]; //Se llenará con las columnas de las etapas.
var sortable="";
var numEtapas=[];
var idProyecto = 1;
var numTareas = [];

function getLevels(id){ //obtener las etapas del proyecto
	//console.log('id del proyecto: ',id);
	cont = 1; // Contador para los id's de los iconos para mover las columnas
	caracter='"'; // Rodean al id que se le manda a la función hide icon y show icon
	var identificador="";
	$.ajax({
		url:"../php/funciones.php",
		data:{clase:"admin_data",funcion:"getLevels", id:id},
		dataType:"json",
		success:function(resp){
			console.log(resp)
			var columnsCount = resp[0][0].length; //Contar las columnas que contiene el proyecto
			console.log(columnsCount);
			$.each(resp, function(i){
				//Imprimiendo las etapas del proyecto:
				$('#boardContent').append('<div id="etapa-'+this.PKEtapa+'" class="container-fluid"><div class="contenedor"><div class="encabezado-grupo titulo-etapa">'+this.Etapa+'</div><div id="columnas-principales-etapa-'+this.PKEtapa+'" class="etapas order_'+(i+1)+' d-flex"></div</div>');
				$('#etapa-'+(i+1)).append("<div id='tabla-id-"+this.PKEtapa+"' class='items' onmouseenter='getSortableRows("+caracter+'tabla-id-'+this.PKEtapa+caracter+")'></div><div class='mb-3 ml-3 agregarTarea'> + Agregar</div>");

				//Imprimiendo las columnas del proyecto
				for(j=0;j<columnsCount;j++){
					$('#columnas-principales-etapa-'+resp[i].PKEtapa).append("<div class='columna-tarea text-center item-Persona  header' data-pos='"+resp[i][0][j].PKColumnaProyecto+"' onmouseenter='showIcon("+caracter+'icon_'+cont+caracter+")' onmouseleave='hideIcon("+caracter+'icon_'+cont+caracter+")'><div class='identificador_"+cont+"'><span id='icon_"+cont+"' class='icon' onmouseenter='getSortable()'></span></div><span class='text'>"+resp[i][0][j].nombre+"</span>")
					//console.log(this.Etapa)
					//console.log(resp[i][0][j].nombre)
					//console.log(resp[i][0][j].tipo)
					tipo = resp[i][0][j].tipo; //Tipo de columna (1:Responsable, 2:Estado, 3:Fecha, etc)
					arrColumnas.push(tipo);//LLenando un array para mandar a la búsqueda de la información de esas columnas
					cont++;//Se incrementa el contador para el id de los iconos para mover las columnas
				}

				var cajaDeColumnas = $('#columnas-principales-etapa-'+this.PKEtapa);
				var columnasEtapas = cajaDeColumnas.children(); //hijos del div con el id columnas-principales-etapa- 1, 2, 3 etc
				//console.log(columnasEtapas)
				var children = columnasEtapas.toArray();
				//console.log(children)
				//columnasArray.concat(arrayCEtapas);
				columnasArray.push(children);
				//$('#columnas-principales-etapa-'+resp[i].PKEtapa).append("<div class='agregarColumna'><i class='fas fa-plus-circle imgHover imgActive cursorPointer'></i></div>");

			})
			//console.log('ARRAY DE COLUMNAS DE TODAS LAS ETAPAS', columnasArray);
			//console.log('array de tipos de columnas: ', arrColumnas);
			getTask(id);
		},
		error:function(error){
			console.log(error)
		}
	})
}

function getTask(id){ //Obtener las tareas del proyecto
	//console.log('id del proyecto: ', id);
	$.ajax({
		url:"../php/funciones.php",
		data:{clase:"admin_data",funcion:"getTask", id:id},
		dataType:"json",
		success:function(resp){
			console.log(resp);
			//Imprimiendo las tareas del proyecto según la Etapa a la que pertenecen
			$.each(resp, function(i){
				$('#tabla-id-'+resp[i].FKEtapa).append("<div id='tarea-"+this.PKTarea+"' class='contenedor' data-ord="+this.PKTarea+"><div class='encabezado-etapa titulo-item ml-3'>"+this.Tarea+"</div></div>");
				$('#tarea-'+resp[i].PKTarea).append("<div id='index-"+(i+1)+"' class='index' style='display: flex;'></div>");
				// <div id='index-"+this.PKTarea+"' style='display: flex;'></div>
			})
			getInfo(arrColumnas, id, resp);
		},
		error:function(error){
			console.log(error);
		}

	})
}

function getInfo(array, id, num){ //Obtener la información de cada columna
	//console.log(array);
	numTareas = num;
	var arrFiltrado = getUnique(array);
	//console.log('NUM EN GETINFO: ', num)
	//console.log('array filtrado: ', arrFiltrado);
	$.ajax({
		url:"../php/funciones.php",
		data:{clase:"admin_data",funcion:"getInfo", array:arrFiltrado, id:id},
		dataType:"json",
		success:function(resp){
			console.log('Array de la info de las tareas: ', resp);
			console.log(num);
			$.each(resp[0], function(i){
				console.log(resp[0][i]);
				$('#index-'+resp[0][i].FKTarea).append("<div id="+this.id+" class='columna-tarea text-center item-name Persona' data-ord='item-name'>"+this.Texto+"</div>");
			});

			$.each(num, function(i){
				var index = $('#index-'+num[i].PKTarea);
				var count = index.children();
				var children = count.toArray();
				indexArray.push(children);

				numEtapas.push(num[i].FKEtapa);
			});

			var totalEtapas = getUnique(numEtapas);
			console.log('El array de los numEtapas: ',totalEtapas)
			//$.each(resp, function(i){
				//$('#tarea-'+resp[i].FKTarea).append("<div class='index-"+this.FKTarea+"' style='display: flex;'><div class='columna-tarea text-center item-name Persona' data-id='item-name'>"+this.Estado+"</div></div>");
				//console.log(this.FKTarea, this.Estado);
			//});
			getTablas(totalEtapas);
		},
		error:function(error){
			console.log(error);
		}

	})

}

function showIcon(id){
	console.log(id)
	$('#'+id).show();
}

function hideIcon(id){
	$('#'+id).hide();
}

function getTablas(array){
	var options = {
		group:{
			name:"share"
		},
		animation: 150,
		delay: 100, // time in milliseconds to define when the sorting should start
		//easing:"cubic-bezier(0.895,0.03,0.685,0.22)", //Estilo de animación
		//handle:'#'+id,
		chosenClass:"seleccionado",
		ghostClass:"fantasma",
		dragClass:"drag",
		dataIdAttr: "data-ord",
		direction:'vertical',
		onEnd: function(evt){
			item_movido = evt.item;
			console.log('item que se movio: ', item_movido);
			oldPosition = evt.oldIndex;
			console.log('vieja position ',oldPosition);
			position = evt.newIndex;
			console.log('nueva position ',position);
			var para =  evt.to;; // target list
			var desde = evt.from; // previous list
			console.log('Para: ',para);
			console.log('Desde: ',desde);
		},
		onUpdate: function (evt) {
			console.log('dentro de la misma lista')
		},
		store: {
			set: function(sortable){
				console.log('sortable en "set de filas": ',sortable)
				orden = sortable.toArray();
				console.log(orden);
				/*
				$.ajax({
					url:"../php/funciones.php",
					dataType:"json",
					data:{clase:"data_order",funcion:"columnOrder",id:idProyecto,ordenArray:orden},
					success:function(resp){
						console.log(resp);
					},
					error:function(error){
						console.log(error);
					}
				})
				*/
			}
		}
	};
	$.each(array, function(i){
		var sorteable = document.getElementById('tabla-id-'+array[i])
		Sortable.create(sorteable,options);
	})
}

function getSortableRows(id){
	//console.log('función sortable rows id: ', id);
	/*
	var options = {
		group:{
			name:"share",
			pull:true,
			put:true
		},
		animation: 150,
		delay: 100, // time in milliseconds to define when the sorting should start
		//easing:"cubic-bezier(0.895,0.03,0.685,0.22)", //Estilo de animación
		handle:'#'+id,
		chosenClass:"seleccionado",
		ghostClass:"fantasma",
		dragClass:"drag",
		dataIdAttr: "data-pos",
		direction:'vertical',
		onEnd: function(evt){
			item_movido = evt.item;
			console.log('item que se movio: ', item_movido)
			oldPosition = evt.oldIndex;
			console.log('vieja position ',oldPosition)
			position = evt.newIndex;
			console.log('nueva position ',position)
		}
	};

	lineaTarea = document.getElementById(id)

    sortable = Sortable.create(lineaTarea,options);
    console.log('SE CREA EL SORTABLE: ', sortable)
    */
}

function getSortable(id){
	sortable = "";
	console.log("---------------------------------------------------------------------------------------------")
	console.log('CUANDO ENTRA A LA FUNCIÓN')
	console.log(sortable)
	console.log('entra el mouse')

	var padre = $(event.target).parents(); //contenedores del "manubrio" de la columna para obtener id y clase
    console.log(padre[2].id)
    var sortableId = padre[2].id; //Los divs que serán sortables.
    handleClass = padre[0].classList[0]; //El "manubrio"
    console.log(handleClass);

    var options = {
    	group:"columnas",
		animation: 150,
		delay: 100, // time in milliseconds to define when the sorting should start
		//easing:"cubic-bezier(0.895,0.03,0.685,0.22)", //Estilo de animación
		handle:'.'+handleClass,
		direction:'horizontal',
		dataIdAttr: "data-pos",
		filter:'.agregarColumna',
		onEnd: function(evt){
			item_movido = evt.item;
			console.log('item que se movio: ', item_movido);
			oldPosition = evt.oldIndex;
			console.log('vieja position ',oldPosition);
			position = evt.newIndex;
			console.log('nueva position ',position);
			calcPositionColumnas(columnasArray,oldPosition, position);
			calcPositionRows(indexArray,oldPosition, position);
		},
		store: {
			set: function(sortable){
				console.log('sortable en "set": ',sortable)
				orden = sortable.toArray();
				console.log(orden);

				$.ajax({
					url:"../php/funciones.php",
					dataType:"json",
					data:{clase:"data_order",funcion:"columnOrder",id:idProyecto,ordenArray:orden},
					success:function(resp){
						console.log(resp);
					},
					error:function(error){
						console.log(error);
					}
				})

			}
		}
	};

    columnas = document.getElementById(sortableId)

    sortable = Sortable.create(columnas,options);
    console.log('SE CREA EL SORTABLE: ', sortable)
}

function destroySortable(){
	console.log('SE DESTRUYE EL SORTABLE')
	sortable.destroy();
}

function getUnique(array){
	var uniqueArray = [];
	// Loop through array values
    for(i=0; i < array.length; i++){
        if(uniqueArray.indexOf(array[i]) === -1) {
            uniqueArray.push(array[i]);
        }
    }
    return uniqueArray;

}

function calcPositionRows(arr, oldPosition, newPosition){
	console.log('calcPositionRows');
	console.log('el array de index: ', arr)

    for(i=0;i<arr.length;i++){// Estableciendo la nueva posición del elemento (persona, estado, etc)
		//console.log(arr[i]);
		arr[i].splice(newPosition, 0, arr[i].splice(oldPosition, 1)[0]);
    }

    console.log("después del for");
    console.log(arr)

    //arr.splice(newPosition, 0, arr.splice(oldPosition, 1)[0]);

	for(i=0;i<arr.length;i++){// Estableciendo el orden para cada elemento.

		for(j=0;j<arr[i].length;j++){

    		arr[i][j].style.order = j;
    	}
    }
}

function calcPositionColumnas(arr, oldPosition, newPosition){
	console.log('calcPositionColumnas');
	//console.log(arr[i]);
	for(i=0;i<arr.length;i++){
		arr[i].splice(newPosition, 0, arr[i].splice(oldPosition, 1)[0]);
	}

    //arr.splice(newPosition, 0, arr.splice(oldPosition, 1)[0]);
	for(i=0;i<arr.length;i++){// Estableciendo el orden para cada elemento.
		for(j=0;j<arr[i].length;j++){
			arr[i][j].style.order = j;
			$('.order_'+(i+1)).append(arr[i][j])
		}
    }



    console.log("después del for");
    console.log(arr)
   //destroySortable();
}

function getColumn(type){
	$('.agregarColumna i').removeClass('fa-times-circle');
	$('.agregarColumna i').addClass('fa-plus-circle');
	$('.listaColumnas').hide();
	if (type == "Estado") {
		var tipo = 2;
		var tabla = "estado_tarea";
	}
	var icon="";
	var totalEtapas = getUnique(numEtapas);

	/*
	for(i=0;i<totalEtapas.length;i++){//Por cada etapa en el proyecto
		$('#columnas-principales-etapa-'+totalEtapas[i]).append("<div class='columna-tarea text-center item-Persona  header' data-pos='4' onmouseenter='showIcon(4)' onmouseleave='hideIcon(4)'><div class='identificador_4'><span id='icon_4' class='icon' onmouseenter='getSortable()'></span></div><span class='text'>Estado 1</span>");
		//icon=icon+columnasArray[0].length;
	}
	for(i=0;i<indexArray.length;i++){//Por cada tarea del proyecto
		$('#index-'+(i+1)).append("<div id='4' class='columna-tarea text-center item-name Persona' data-id='item-name'></div>");
	}
	*/
	$.ajax({
		url:"../php/funciones.php",
		data:{clase:"add_data",funcion:"addColumn", id:idProyecto, tipo:tipo, tabla:tabla},
		dataType:"json",
		success:function(resp){
			//console.log(resp);
			icon = resp.Orden;
			for(i=0;i<totalEtapas.length;i++){//Por cada etapa en el proyecto
				$('#columnas-principales-etapa-'+totalEtapas[i]).append("<div class='columna-tarea text-center item-Persona  header' data-pos='"+resp.id+"' onmouseenter='showIcon("+caracter+'icon_'+icon+caracter+")' onmouseleave='hideIcon("+caracter+'icon_'+icon+caracter+")'><div class='identificador_"+icon+"'><span id='icon_"+icon+"' class='icon' onmouseenter='getSortable()'></span></div><span class='text'>"+resp.Nombre+"</span>");
				icon=icon+columnasArray[0].length;
			}

			for(i=0;i<indexArray.length;i++){//Por cada tarea del proyecto
				$('#index-'+(i+1)).append("<div id="+resp[0][i]+" class='columna-tarea text-center item-name Persona' data-id='item-name'></div>");
			}

			var cant = columnasArray.length;
			var count = indexArray.length;
			columnasArray = [];
			//Actualizando el array de las columnas.
			for(i=0;i<cant;i++){
				var cajaDeColumnas = $('#columnas-principales-etapa-'+(i+1));
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
		},
		error:function(error){
			console.log(error);
		}

	})
}

$('.listaColumnas').click(function(event){
	var clicked = $(event.target).text();
	console.log(clicked);
	getColumn(clicked);
});

//DISPLAY BLOCK FAS ICONS
