var $=jQuery.noConflict();

/*------------------------------------*\
	#GENERAL FUNCTIONS
\*------------------------------------*/

/**
 * Create a Google Map
 * @param float lat
 * @param float lon
 * @param int zoom
 * @param string mapColor (hex)
**/
function loadMap( lat, lon, zoom, mapColor){

	var map;
	var styles = [{
    	stylers: [
        	{ hue: mapColor }
     	]
	}];
	var styledMap = new google.maps.StyledMapType( styles, { name: "Styled Map" } );

	function initialize() {
		map = new google.maps.Map(document.getElementById('map-canvas'), {
			scrollwheel: false,
			zoom: zoom,
			scrollwheel: false,
			draggable: false,
			mapTypeIds: [ 'map_style' ],
			center: { lat: lat, lng: lon }
		});

		map.mapTypes.set('map_style', styledMap);
		map.setMapTypeId('map_style');
	}

	google.maps.event.addDomListener(window, 'load', initialize);
}// loadMap

/**
 * Create a Chart using Chart.js
**/
function createChart(){

	var data = {
		labels: ["Mes 1", "Mes 6", "Mes 11", "Mes 16", "Mes 21", "Mes 26", "Mes 31", "Mes 36"],
		datasets: [
			{
				label: "Arrendamiento puro",
				fillColor: "rgba(3, 2, 115,0.2)",
				strokeColor: "rgba(3, 2, 115,1)",
				pointColor: "rgba(3, 2, 115,1)",
				pointStrokeColor: "#fff",
				pointHighlightFill: "#fff",
				pointHighlightStroke: "rgba(3, 2, 115,1)",
				data: [2708.3, 16250, 29791.6, 43333.3, 56875, 70416.6, 83958.3, 97500]
			},
			{
				label: "Comprado de contado",
				fillColor: "rgba(127, 179, 237,0.2)",
				strokeColor: "rgba(151,187,205,1)",
				pointColor: "rgba(127, 179, 237,1)",
				pointStrokeColor: "#fff",
				pointHighlightFill: "#fff",
				pointHighlightStroke: "rgba(151,187,205,1)",
				data: [5706, 34236, 62766, 91296, 119826, 148356, 176886, 205416]
			}
		]
	};
	var ctx = $("#myChart").get(0).getContext("2d");
	var myLineChart = new Chart(ctx).Line(data, {
		bezierCurve: false,
		responsive: true,

		scaleOverride: true,
		scaleSteps: 5,
		scaleStepWidth: 50000,
		scaleStartValue: 0,

		scaleLabel: "<%= formatoDinero(value) %>",
		multiTooltipTemplate: "<%= formatoDinero(value)  %>",

		animationSteps: 150,

	});

}// createChart


function scrollTo(elemento, offset, speed){
	console.log( $(elemento) );
	var divPosicion =  $(elemento).offset().top,
		divPosicion = divPosicion - offset;
	$('html, body').animate({scrollTop: divPosicion}, speed);
}


/**
 * Check if the user has scrolled to the given element.
 * @param element elem
**/
function isScrolledIntoView(elem)
{
	var docViewTop = $(window).scrollTop();
	var docViewBottom = docViewTop + $(window).height();

	var elemTop = $(elem).offset().top;
	var elemBottom = elemTop + $(elem).height();

	return ( (elemTop <= docViewBottom) && (elemBottom >= docViewTop) );

}// isScrolledIntoView

/**
 * Send email requesting more information.
 * @param elemnt form
 */
function sendContactEmail( form ){

	var data = $( form ).serialize();
	$.post(
		'php/send_contact_email.php',
		data,
		function( response ){
			//console.log(response);
			var jsonResponse = $.parseJSON( response );

			if( jsonResponse.error === 1) {
				showContactErrorHTML( jsonResponse.message );
				return;
			}

			$( form ).empty();
			$( form ).append( getContactSuccessHTML( jsonResponse.message ) );
		}
	);

}// sendContactEmail


function onlyNumbers( e ) {
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
}// onlyNumbers


/*------------------------------------*\
	#GET/SET FUNCTIONS
\*------------------------------------*/

/**
 * Get header's height
 */
function getHeaderHeight(){
	return $('header').outerHeight();
}// getHeaderHeight

/**
 * Get footer's height
 */
function getFooterHeight(){
	return $('footer').height();
}// getFooterHeight

/**
 * Get the scrolled pixels in Y axis
 */
function getScrollY() {
	return $(window).scrollTop();
}// getScrollY


/**
 * Set main's padding top
 */
function setMainPaddingTop(){
	var headerHeight = getHeaderHeight();
	$('.main').css('padding-top', headerHeight + 20);
}// setMainPaddingTop

/**
 * Show HTML if contact form was sent succesfully.
 * @param string message
 * @return successHTML
**/
function getContactSuccessHTML( message ){
	return '<h4 class="[ text-center ][ text-shadow ][ color-light ]">' + message + '</h4>';
}// getContactSuccessHTML

/**
 * Show HTML if contact form was not sent succesfully.
 * @param string message
 * @return errorHTML
**/
function getContactErrorHTML( message ){
	return '<p>' + message + '</p>';
}// showContactErrorHTML



/*------------------------------------*\
	#AJAX FUNCTIONS
\*------------------------------------*/



/*------------------------------------*\
	#TOGGLE FUNCTIONS
\*------------------------------------*/

/**
 * Toggle action buttons
 */
 function toggleActionButtons(){
	//Get the header height so we can now when
	//to change the heade state
	var headerHeight = getHeaderHeight();
	//Scrolled pixels in Y axis
	var sy = getScrollY();
	//Compare the two numbers, when they are the same or less
	//add fixed class to the header.
	if ( sy >= headerHeight ) {
		$('.action-buttons').addClass('opened');
	} else {
		$('.action-buttons').removeClass('opened');
	}
}// toggleActionButtons

/**
 * Toggle Modal
 * @param element to be toggled
**/
function toggleModal(element){

	$('body').toggleClass('overflow-hidden');

	if ( undefined === element ){
		$('.modal-wrapper').addClass('hide');
		return;
	}
	$(element).toggleClass('hide');

}//toggleModal

/**
 * Toggle any element
 * @param element to be shown
**/
function toggleVisibility(element){
	$(element).toggleClass('opened');
}// toggleVisibility


/**
 * Close accordion
 */
function closeAccordionSection() {
	$('.accordion .accordion-section-title').removeClass('active');
	$('.accordion .accordion-section-content').slideUp(300).removeClass('open');
}//closeAccordionSection



/*------------------------------------*\
	#HELPER FUNCTIONS
\*------------------------------------*/

function imgToSvg(){
	$('img.svg').each(function(){
		var $img = $(this);
		var imgID = $img.attr('id');
		var imgClass = $img.attr('class');
		var imgURL = $img.attr('src');

		$.get(imgURL, function(data) {
			// Get the SVG tag, ignore the rest
			var $svg = $(data).find('svg');

			// Add replaced image's ID to the new SVG
			if(typeof imgID !== 'undefined') {
				$svg = $svg.attr('id', imgID);
			}
			// Add replaced image's classes to the new SVG
			if(typeof imgClass !== 'undefined') {
				$svg = $svg.attr('class', imgClass+' replaced-svg');
			}

			// Remove any invalid XML tags as per http://validator.w3.org
			$svg = $svg.removeAttr('xmlns:a');

			// Replace image with new SVG
			$img.replaceWith($svg);

		}, 'xml');

	});
} //imgToSvg

/**
 * Convert an RGB color into a hexadecimal color
 * @param array rgb
 * @return string
**/
function rgb2hex( rgb ) {
	rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
 	return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}// rgb2hex

/**
 * Return the hexadecimal number of a decimal number
 * @param float num
 * @return string
**/
function hex( num ) {
	var hexDigits = new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f");
 	return isNaN( num ) ? "00" : hexDigits[( num - num % 16) / 16] + hexDigits[num % 16];
}// hex



/*------------------------------------*\
	#FORMAT FUNCTIONS
\*------------------------------------*/

/**
 * Add money format to a number
 * @param float num
 * @return string
**/
function formatoDinero( num ){
	var digitosDecimales = 0;
	var signo = num < 0 ? "-" : "";
	var numConDecimales = parseInt( num = Math.abs( +num || 0 ).toFixed(digitosDecimales) ) + "";
	var posicionComa = ( posicionComa = numConDecimales.length ) > 3 ? posicionComa % 3 : 0;

	return '$' + signo + ( posicionComa ? numConDecimales.substr( 0, posicionComa ) + "," : "" ) + numConDecimales.substr( posicionComa ).replace( /(\d{3})(?=\d)/g, "$1" + "," ) + ( digitosDecimales ? "." + Math.abs( num - numConDecimales ).toFixed( digitosDecimales ).slice(2) : "" );
}// formatoDinero