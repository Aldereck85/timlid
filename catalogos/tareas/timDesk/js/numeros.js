/* 
La columna números permite definir en un input un número int o float y mediante un modal que se despliega por debajo del elemento configurar un símbolo
de moneada (euro, pesos, dolares) o porcentaje, así como definir de que lado del número aparece el símbolo.
*/


function change_data_number(id,symbol,idC){
	$('#boardContent').animate({scrollTop: $('#boardContent').height() }, 1000);//Recorrer scroll hacía abajo
	varContainer = '.opcionesColumnaNumeros';
	$('.attr-'+id).attr('onclick','close_number_modal('+id+','+caracter2+symbol+caracter2+','+idC+')')
	$('.symbol-active-'+idC).removeClass("active")
	$('.input-symbol-'+idC).attr("checked","false")
	
	$('.numeric-options-'+id).append('<div class="opcionesColumnaNumeros pos-abs">&nbsp;Simbolo<center><div class="btn-group btn-group-toggle" id="btnMoney" data-toggle="buttons">'+
		'<label id="option-symbol-dollar" class="btn btn-light cursorPointer symbol-active-'+idC+'" onclick="set_symbol('+id+','+idC+','+caracter2+'$'+
		caracter2+')"><input type="radio" name="options" id="input_symbol_dollar" class="input-symbol-'+idC+'" autocomplete="off">$</label>'+
		'<label id="option-symbol-euro" class="btn btn-light cursorPointer symbol-active-'+idC+'" onclick="set_symbol('+id+','+idC+','+caracter2+'&euro;'+
		caracter2+')"><input type="radio" name="options" id="input_symbol_euro" class="input-symbol-'+idC+'" autocomplete="off">&euro;</label>'+
		'<label id="option-symbol-pound" class="btn btn-light cursorPointer symbol-active-'+idC+'" onclick="set_symbol('+id+','+idC+','+caracter2+'&pound;'+
		caracter2+')"><input type="radio" name="options" id="input_symbol_pound" class="input-symbol-'+idC+'" autocomplete="off">&pound;</label>'+
		'<label id="option-symbol-porcent" class="btn btn-light cursorPointer symbol-active-'+idC+'" onclick="set_symbol('+id+','+idC+','+caracter2+'%'+
		caracter2+')"><input type="radio" name="options" id="input_symbol_porcent" class="input-symbol-'+idC+'" autocomplete="off">%</label>'+
		'</div></center>'+
		'&nbsp;Alineación<center><div class="btn-group btn-group-toggle" id="btnAlineacion" data-toggle="buttons">'+
		'<label class="btn btn-light cursorPointer btn-symbol-left-'+idC+'" onclick="change_symbol_side('+caracter2+'left'+caracter2+','+caracter2+symbol+caracter2+','+idC+')">'+
		'<input id="opcionIzq-numbers" type="radio" name="alineacion" autocomplete="off"> I</label>'+
		'<label class="btn btn-light cursorPointer btn-symbol-right-'+idC+'"  onclick="change_symbol_side('+caracter2+'right'+caracter2+','+
		caracter2+symbol+caracter2+','+idC+')"><input type="radio" name="alineacion" id="opcionDer-numbers" autocomplete="off"> D</label>'+
		'</div></center></div>')

	let comprobar = $('.co_'+idC+' .input-group').hasClass('timlid-input-group-right'); //Comprobar si está del lado derecho el simbolo para cambiar de color I o D del modal
	console.log("comprobar lado: ", comprobar);
	if (comprobar) {//Está del lado derecho
		$('#opcionDer-numbers').attr('checked','checked')
		$('#opcionIzq-numbers').attr('checked','false')
		let active_element = $('#opcionDer-numbers').parent()
		active_element.addClass('active')
	}else{//Está del lado izquierdo
		$('#opcionIzq-numbers').attr('checked','checked')
		$('#opcionDer-numbers').attr('checked','false')
		let active_element = $('#opcionIzq-numbers').parent()
		active_element.addClass('active')
	}

	//Comprobar cuál símbolo está en uso:
	for(i=0;i<symbols_used.length;i++){
		console.log("symbol", symbol,"symbols_used[i]", symbols_used[i]);
		if (symbols_used[i][0] == symbol ) {
			$('#option-symbol-'+symbols_used[i][1]).addClass('active');
			$('#input_symbol_'+symbols_used[i][1]).attr('checked','true');
			// $('#option_symbol_'+symbol).attr('checked','checked');
			// i = symbols_used.length
		}
	}
}

function close_number_modal(id,symbol,idC){
	$('.opcionesColumnaNumeros').remove()
	$('.attr-'+id).attr('onclick','change_data_number('+id+','+caracter2+symbol+caracter2+','+idC+')')
}

function change_symbol_side(side,symbol,id){ //Animación del cuadro del símbolo ($,%,etc...)
	if (side=="left") {
		if ($(".side-left-"+id)[0]){//Comprobar si existe ya elemento del lado izquierdo
		    // Do something if class exists
		 	console.log('ya existe la clase, no hagas la animación')
			
	    }else{
	    	$('.change-left-to-right-'+id+'').css('width','84%')//Aumentar el div del elemento para hacer el efecto de que se recorre de derecha a izquierda

			$('.symbol-show-'+id+'').html(symbol)//accediendo al símbolo del elemento ($ o % o etc...)
			$('.change-left-to-right-'+id+'').animate({//Animando el elemento
		        width:"0" //(de 84% a 0)
		    },{
		        duration:200,
		        specialEasing:{
		            width:"swing"
		        },
		        function: change_symbol_side_lr(side, id)
		    });
	    }

	}else{
		if ($(".side-right-"+id)[0]){//Comprobar si existe ya elemento del lado derecho
		    // Do something if class exists
			console.log('ya existe la clase, no hagas la animación')
			
	    } else{
	    	
	    	$('.symbol-show-'+id+'').html(symbol)
			$('.change-left-to-right-'+id+'').animate({
		        width:"84%"
		    },{
		        duration:200,
		        specialEasing:{
		            width:"swing"
		        },
		        function: change_symbol_side_lr(side, id)
		    });
	    }
	}
}

function change_symbol_side_lr(side, id){
	console.log("side en change_symbol_side_lr: ", side);

	let symbol = $('.numeric-symbol-'+id);//obtener cada elemento con simbolo según la columna
	let ids = document.querySelectorAll('.numeric-column-'+id);//obteniendo los ids de la columna

	if (side=="left") {//si va al lado izquierdo

		$('.opcionesColumnaNumeros').css('left','3px');//cambiando la posición del modal
		setTimeout(function(){
			
			//Como estaba del lado derecho, no se creó el div del lado izq. Hay que crearlo:
			for(i=0;i<symbol.length;i++){//Imprimiendo el div con el símbolo del lado izquierdo:
				$('[data-symbol='+ids[i].dataset.symbol+']').prepend('<div class="input-group-prepend" style="width:28px;margin-right:-7px;"></div>');
				$('[data-symbol='+ids[i].dataset.symbol+'] .input-group-prepend').addClass('left-side-numbers-'+ids[i].dataset.symbol);
				$('.left-side-numbers-'+ids[i].dataset.symbol).prepend(symbol[i]);
			}

			//Cambiando la configuración del input para que se acomode acorde:
			$('.numeric-column-'+id+'').removeClass('timlid-input-group-right');
			$('.numeric-column-'+id+'').addClass('timlid-input-group-left');
			$('.numeric-symbol-'+id).addClass('side-left-'+id);
			$('.numeric-symbol-'+id).removeClass('side-right-'+id);
			$('.numeric-symbol-'+id).removeAttr('data-side');
			$('.numeric-symbol-'+id).attr('data-side', side);
			//$('.numeric-symbol.side-right').remove();
			$('.symbol-show-'+id+'').html(" ")//Vaciando el símbolo de la animación
		}, 300);
		

	}else{//si va al lado derecho

		setTimeout(function(){
			
			$('.opcionesColumnaNumeros').css('left','-1px');
			$('.numeric-column-'+id+' .input-group-prepend').remove();//Removiendo el elemento con el símbolo del lado izquierdo

			for(i=0;i<symbol.length;i++){//Imprimiendo elemento de lado derecho:
				$('.right-side-numbers-'+ids[i].dataset.symbol).prepend(symbol[i]);
			}

			$('.numeric-column-'+id+'').removeClass('timlid-input-group-left');
			$('.numeric-column-'+id+'').addClass('timlid-input-group-right');
			$('.numeric-symbol-'+id).addClass('side-right-'+id);
			$('.numeric-symbol-'+id).removeClass('side-left-'+id);
			$('.numeric-symbol-'+id).attr('data-side', side);
			$('.change-left-to-right-'+id+'').css('width','0')//Cambiando el css para la animación
			$('.symbol-show-'+id+'').html(" ")
		}, 300);

	}

	//Definiendo el lado en la BBDD:
	$.ajax({
		url:"php/funciones.php",
		data:{clase:"edit_data", funcion:"define_symbol_side",id:id,side:side},
		dataType:"json",
		success:function(respuesta){
			console.log(respuesta);
		},
		error:function(error){
			console.log(error);
		}
	})
}

function set_numeric_value(id){
	let num = $('#number-'+id).val();
	console.log("num", num, "id: ", id);
	
	$.ajax({
		url:"php/funciones.php",
		data:{clase:"edit_data", funcion:"set_numeric_value",id:id,num:num},
		dataType:"json",
		success:function(respuesta){
			console.log(respuesta);
		},
		error:function(error){
			console.log(error);
		}
	})
	
}

function set_symbol(id,idC,symbol){
	console.log(id, symbol)
	$.ajax({
		url:"php/funciones.php",
		data:{clase:"edit_data", funcion:"set_symbol_column",id:idC,symbol:symbol},
		dataType:"json",
		success:function(respuesta){
			console.log(respuesta);
			$('.numeric-symbol-'+idC).html(symbol);
			let ids = document.querySelectorAll('.numeric-column-'+idC);
			
			for(i=0;i<ids.length;i++){
				console.log(ids[i].dataset.symbol)
				$('.attr-'+ids[i].dataset.symbol).attr('onclick','close_number_modal('+ids[i].dataset.symbol+','+caracter2+symbol+caracter2+','+idC+')');
				$('.btn-symbol-left-'+idC).attr('onclick','change_symbol_side('+caracter2+'left'+caracter2+','+caracter2+symbol+caracter2+','+idC+')');
				$('.btn-symbol-right-'+idC).attr('onclick','change_symbol_side('+caracter2+'right'+caracter2+','+caracter2+symbol+caracter2+','+idC+')');
			}
		},
		error:function(error){
			console.log(error);
		}
	})
}

symbols_used = [
	
	["$","dollar"],
	["€","euro"],
	["£","pound"],
	["%","porcent"]

];
