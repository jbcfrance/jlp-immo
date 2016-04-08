var ContactPage = function () {

	return {

		//Basic Map
		initMap: function () {
			var map;
			$(document).ready(function(){
				map = new GMaps({
					div: '#map',
					scrollwheel: false,
					lat: 45.905473,
					lng: 6.700906
				});

				var marker = map.addMarker({
					lat: 45.905473,
					lng: 6.700906,
					title: 'JLP-Immo - Immobilier de montagne'
				});
			});
		},

		//Panorama Map
		initPanorama: function () {
			var panorama;
			$(document).ready(function(){
				panorama = GMaps.createPanorama({
					el: '#panorama',
					lat : 45.905473,
					lng : 6.700906
				});
			});
		}

	};
}();
