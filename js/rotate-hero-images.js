/*!
 * Rotate hero background images
 * by Pequeño Cuervo
 *
 * Options:
 *	- formatoDinero (boolean) : Se utiliza para darle formato de moneda a un número
 * 	- digitosDecimales (int) : Número de digitos decimales
 */

(function( $ ){
	'use strict';

	$.fn.rotateHeroImages = function ( options ) {

		var opts = $.extend({
			totalImages: 	3,
			imgPrefix: 		'hero-home-',
			imgFormat: 		'.jpg',
			imgFolderPath: 	'images/',
			interval: 		3000
		}, options);

		var $hero = this;
		var currentImgIndex = 1;
		var timingRun = setInterval(function() { sliderTiming(); }, opts.interval );

		return this;



		/***********************************
		* GENERAL FUNCTIONS
		***********************************/

		/**
		 * Change hero background image.
		 * @param string imgNumber
		**/
		function changeBackgroundImage( imgNumber ) {
			var imageUrl = opts.imgFolderPath + opts.imgPrefix + imgNumber + opts.imgFormat;
			$hero.css('background-image', 'url("' + imageUrl + '")');
		}

		/**
		 * Slider timing to change the background image.
		**/
		function sliderTiming() {
			currentImgIndex === opts.totalImages ? currentImgIndex = 1 : currentImgIndex = currentImgIndex+1;
			changeBackgroundImage( currentImgIndex );
		}// sliderTiming

	};

}( jQuery ));