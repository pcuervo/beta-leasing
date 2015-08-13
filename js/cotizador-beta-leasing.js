/*!
 * Cotizador Beta Leasing
 * by Pequeño Cuervo
 *
 * Options:
 *	- formatoDinero (boolean) : Se utiliza para darle formato de moneda a un número
 * 	- digitosDecimales (int) : Número de digitos decimales
 */

(function( $ ){
	'use strict';

	$.fn.cotizadorBetaLeasing = function ( options ) {
		
		var opts = $.extend({
			formatoDinero: 		false,
			digitosDecimales: 	2,
		}, options);

		$('input[name="valor_total"]').keydown( function(e) {
			if( invalidKey(e) ){
				e.preventDefault();
				return;
			}
			// Allow arrows
			if(e.keyCode >= 37 && e.keyCode <= 40 ) {
				return;
			}
		});	

		$('input[name="valor_total"]').keyup( function(e) {
			if( invalidKey(e) ){
				e.preventDefault();
				return;
			}
			// Allow arrows
			if( ( e.keyCode >= 37 && e.keyCode <= 40) || e.keyCode == 8 ) {
				return;
			}

			$(this).val(function(i,v) {
				actualizarCotizacion( removeFormatoDinero( v ) );
				return v;
			});

		});	

		$('input[name="valor_total"]').focusout( function(e) {
			actualizarCotizacion( getValorTotal() );
			if ( opts.formatoDinero ) { 
				$(this).val( formatoDinero( $(this).val() ) );
				return;
			};
			$(this).val( $(this).val() );
		});

		$('input[name="valor_total"]').focus( function(e) {
			$(this).val( removeFormatoDinero( $(this).val() ) );
		});


		// return {
	 //        actualizar: function() {
	 //            actualizarCotizacion( getValorTotal() );
	 //        }
	 //    }

        // Public function
		this.actualizar = function() {
            actualizarCotizacion( getValorTotal() );
        }

	    return this;
		


		/***********************************
		* GENERAL FUNCTIONS
		***********************************/		

		function actualizarCotizacion( valorTotal ){

			var valorFactura, pagoInicial, montoFinanciar, valorResidual, plazo, tasaMensual, porcentajeEnganche; 

			setValorFactura( valorTotal );
			setIVA( getValorFactura() );
			porcentajeEnganche = getPorcentajeEnganche();
			pagoInicial = getPagoInicial( porcentajeEnganche, getValorFactura() );
			setMontoFinanciar( getValorFactura(), pagoInicial );		
			
			setRentaMensual( calculaRentaMensual( getTasaMensual(), getPlazoMeses(), getMontoFinanciar(),  getValorResidual() ) * 1.16 );

			// console.clear();
			// console.log('tasa mensual: ' + tasaMensual );
			// console.log('plazo: ' + plazo );
			// console.log('monto a financiar: $' + montoFinanciar );
			// console.log('valorResidual: $' + valorResidual );
			// console.log( 'pago mensual: $' + calculaRentaMensual( tasaMensual, plazo, montoFinanciar,  valorResidual ) * 1.16 );
		}// actualizarCotizacion

		function calculaRentaMensual( tasaMensual, plazo, montoFinanciar, valorResidual ){
			if( ! valorResidual ){ valorResidual = 0; }
			
			return tasaMensual * ( montoFinanciar * -1 * Math.pow((1 + tasaMensual), plazo) + valorResidual ) / (1 - Math.pow((1 + tasaMensual), plazo));
		}// calculaRentaMensual



		/***********************************
		* FORMAT FUNCTIONS
		***********************************/

		function formatoDinero( num ){
			var digitosDecimales = opts.digitosDecimales;
			var signo = num < 0 ? "-" : ""; 
			var numConDecimales = parseInt( num = Math.abs( +num || 0 ).toFixed(digitosDecimales) ) + "";
			var posicionComa = ( posicionComa = numConDecimales.length ) > 3 ? posicionComa % 3 : 0;

			return '$' + signo + ( posicionComa ? numConDecimales.substr( 0, posicionComa ) + "," : "" ) + numConDecimales.substr( posicionComa ).replace( /(\d{3})(?=\d)/g, "$1" + "," ) + ( digitosDecimales ? "." + Math.abs( num - numConDecimales ).toFixed( digitosDecimales ).slice(2) : "" );
		}// formatoDinero

		function removeFormatoDinero( num ){ 
			return num.replace('$','').replace(/,/g, ''); 
		}// removeFormatoDinero



		/***********************************
		* GET / SET FUNCTIONS
		***********************************/

		function getMontoFinanciar(){
			return removeFormatoDinero( $('input[name="monto_financiar"]').val() );
		}// monto_financiar

		function getValorFactura(){
			return removeFormatoDinero( $('input[name="valor_factura"]').val() );
		}// getValorFactura

		function getValorTotal(){
			return removeFormatoDinero( $('input[name="valor_total"]').val() );
		}

		function getComision(){
			return 0.2;
		}

		function getPagoInicial( meses, valorFactura ){
			return meses * valorFactura;
		}

		function getPorcentajeEnganche(){
			var porcentajeEnganche = $( '.js-porcentaje-enganche' ).text();
			return porcentajeEnganche.slice(0, -1) / 100;
		}

		function getPlazoMeses(){
			return parseInt( $( '.js-meses' ).text() );
		}

		function getTasaMensual(){
			return 0.26 / 12;
		}

		function getValorResidual(){
			return getValorFactura() * 0.25;
		}

		function setValorFactura( total ){
			var iva = total / 1.16;

			if ( opts.formatoDinero ) { 
				$('input[name="valor_factura"]').val( formatoDinero( iva ) );
				return;
			};
			$('input[name="valor_factura"]').val( iva.toFixed( opts.digitosDecimales ) );
		}// setValorFactura

		function setIVA( total ){

			var iva = total * 0.16;

			if ( opts.formatoDinero ) { 

				$('input[name="iva"]').val( formatoDinero( iva ) );
				return;
			};
			$('input[name="iva"]').val( iva.toFixed( opts.digitosDecimales ) );
		}

		function setMontoFinanciar( valorFactura, pagoInicial ){
			var monto = valorFactura - pagoInicial;

			if ( opts.formatoDinero ) { 
				$('input[name="monto_financiar"]').val( formatoDinero( monto ) );
				return;
			};
			$('input[name="monto_financiar"]').val( monto.toFixed( opts.digitosDecimales ) );
		}// setMontoFinanciar

		function setRentaMensual( rentaMensual ){

			if ( opts.formatoDinero ) { 
				$('input[name="renta_mensual"]').val( formatoDinero( rentaMensual ) );
				return;
			};
			$('input[name="renta_mensual"]').val( rentaMensual.toFixed(2) );
		}// setRentaMensual



		/***********************************
		* VALIDATION FUNCTIONS
		***********************************/

		function invalidKey( e ) {
			// Allow: backspace, delete, tab, escape, enter and .
			//alert( e.keyCode );
	        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190 ]) !== -1 ||
	             // Allow: Ctrl+A
	            (e.keyCode == 65 && e.ctrlKey === true) ||
	             // Allow: Ctrl+C
	            (e.keyCode == 67 && e.ctrlKey === true) ||
	             // Allow: Ctrl+X
	            (e.keyCode == 88 && e.ctrlKey === true) ||
	             // Allow: home, end, left, right
	            (e.keyCode >= 35 && e.keyCode <= 39)) {
	                 // let it happen, don't do anything
	                 return false;
	        }
	        // Ensure that it is a number and stop the keypress
	        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	            return true;
	        }
		}// invalidKey

	};

}( jQuery ));