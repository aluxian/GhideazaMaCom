<?php $this->load->view('header', array('title' => (urldecode($query)).' | Ghideaza-Ma.com', 'page' => 'place')); ?>

<script type="text/javascript">
	google.load("earth", "1");

	var siteUrl = '<?php echo site_url(); ?>';
	var searchQuery = unescape('<?php echo $query; ?>');

	var mainMap, mainMapDiv, fullMap;
	var currentMap = 'default';

	var photosGalleryShown = 'places';
	var placesGalleryDiv;

	var summaryEmpty = false;
	var fullWikiShown = false;
	var defaultLang = <?php echo $this->uri->segment(4) == 'en' ? 'false' : 'true'; ?>;

	var panoramioAvailable = false;
	var drawingManagerActive = false;
	var panoramioLayerActive = false;
	var weatherLayerActive = false;
	var directionsActive = false;

	var drawingManager, panoramioLayer, weatherLayer;
	var placesService, placeDetails;
	var placeLat, placeLng;

	var placeTypes = {"airport": "Aeroport", "amusement_park": "Parc de distracții", "aquarium": "Acvariu", "art_gallery": "Galerie de artă", "atm": "ATM", "bakery": "Brutărie", "bank": "Bancă", "bar": "Bar", "beauty_salon": "Salon de cosmetică", "bicycle_store": "Magazin", "book_store": "Librărie", "bowling_alley": "Popicărie", "bus_station": "Stație de autobuz", "cafe": "Cafenea", "campground": "Teren camping", "car_dealer": "Dealer auto", "car_rental": "Inchirieri mașini", "car_repair": "Service auto", "car_wash": "Spălătorie auto", "casino": "Cazinou", "cemetery": "Cimitir", "church": "Biserică", "city_hall": "Primărie", "clothing_store": "Magazin", "convenience_store": "Magazin", "courthouse": "Tribunal", "dentist": "Dentist", "department_store": "Magazin", "doctor": "Medic", "electronics_store": "Magazin", "embassy": "Ambasadă", "fire_station": "Pompieri", "florist": "Florărie", "funeral_home": "Servicii funerare", "furniture_store": "Magazin", "gas_station": "Benzinărie", "grocery_or_supermarket": "Magazin alimentar", "gym": "Sala de fitness", "hardware_store": "Magazin", "hindu_temple": "Templu hindus", "home_goods_store": "Magazin", "hospital": "Spital", "insurance_agency": "Agenție de asigurări", "jewelry_store": "Magazin", "laundry": "Spălătorie haine", "lawyer": "Avocat", "library": "Bibliotecă", "liquor_store": "Magazin", "local_government_office": "Birou administrație locală", "mosque": "Moschee", "movie_rental": "Închirieri filme", "movie_theater": "Cinema", "museum": "Muzeu", "night_club": "Club de noapte", "park": "Parc", "parking": "Parcare", "pet_store": "Magazin", "pharmacy": "Farmacie", "place_of_worship": "Lăcaș de cult", "police": "Poliție", "post_office": "Poștă", "restaurant": "Restaurant", "rv_park": "Parc pentru rulote", "school": "Școală", "shoe_store": "Magazin", "shopping_mall": "Mall", "spa": "SPA", "stadium": "Stadion", "store": "Magazin", "subway_station": "Stație de metrou", "synagogue": "Sinagogă", "taxi_stand": "Parcare de taxiuri", "train_station": "Gara", "travel_agency": "Agenție de turism", "university": "Universitate", "veterinary_care": "Veterinar", "zoo": "Zoo", "country": "Țară", "intersection": "Intersecție", "locality": "Localitate", "natural_feature": "Loc natural", "neighborhood": "Cartier", "point_of_interest": "Atracție turistică", "route": "Stradă"};

	/**
	 * Main function that runs after dom load
	 * @return {[type]} [description]
	 */
	function initialize() {
		mainMap = new google.maps.Map(document.getElementById('place_main_map'), {
			mapTypeId: google.maps.MapTypeId.HYBRID,
			zoom: 16
		});

		drawingManager = new google.maps.drawing.DrawingManager();
		panoramioLayer = new google.maps.panoramio.PanoramioLayer();
		weatherLayer = new google.maps.weather.WeatherLayer({temperatureUnits: google.maps.weather.TemperatureUnit.CELSIUS});

		placesService = new google.maps.places.PlacesService(mainMap);
		placesService.textSearch({query: searchQuery}, function(place, status) {
			if (status == google.maps.places.PlacesServiceStatus.OK)
				placesService.getDetails({reference: place[0].reference}, getDetailsCallback);
			else
				displayError();
		});
	}

	/**
	 * Callback for the getDetails places api request
	 * @param  {[type]} place  [description]
	 * @param  {[type]} status [description]
	 * @return {[type]}        [description]
	 */
	function getDetailsCallback(place, status) {
		if (status == google.maps.places.PlacesServiceStatus.OK) {
			// Make the place object available globally
			placeDetails = place;

			// Place title, icon, href
	        document.title = place.name + ' | Ghideaza-Ma.com';
			$('#place_title').html(place.name);
			$('#place_icon').attr('src', place.icon);
			$('#place_title_href').attr('href', siteUrl + 'place/info/' + escape(place.name));

			// Set main map location
			if (placeDetails.geometry.viewport)
				mainMap.fitBounds(placeDetails.geometry.viewport);
			else (placeDetails.geometry.location)
				mainMap.setCenter(placeDetails.geometry.location);

			// Add right sidebar info
			listAdd({
				'Adresa': place.formatted_address,
				'nullTypes': getTypes(place.types)
			}, 'general', 'Informatii generale');

			listAdd({
				'Acum': place.opening_hours ? (place.opening_hours.open_now ? 'deschis' : 'inchis') : null,
				'Telefon': place.international_phone_number,
				'Website': getHref(place.website, place.website, '')
			}, 'contact', 'Contact');

			placeLat = (place.geometry.location.lat()).toString().substring(0, 8);
			placeLng = (place.geometry.location.lng()).toString().substring(0, 8);

			listAdd({
				'Fus orar': place.tz,
				'Latitudine': placeLat,
				'Longitudine': placeLng
			}, 'geographical', 'Informatii geografice');

			listAdd({
				'nullGooglePlacesPage': getHref('Pagina Google Places', place.url, place.url)
			}, 'others', 'Altele');


			/* Script fragment // check if wikipedia.com/wiki/place.name redirects to a different page

			$.ajax({
				dataType: "jsonp",
				url: 'http://ro.wikipedia.org/w/api.php',
				cache: true,
				data: {
					'format': 'json',
					'action': 'query',
					'prop': 'revisions',
					'rvprop': 'content',
					'titles': place.name
				},
				success: function(data) {
					for (var property in data.query.pages) {
						if (data.query.pages.hasOwnProperty(property)) {
							var prop = data.query.pages[property].revisions[0];
							prop = prop['*'];

							if (prop.search('#REDIRECTEAZA') > -1)
								getWiki(prop.substring(16, prop.length - 2));
							else
								getWiki(place.name);
						}

						break;
					}
				}
			});*/

			// Left side click listeners
			$('#defaultMap').click(function() {
				if (currentMap != 'default') {
					$('#sideButtonMap_div').css('height', '100%');
					$('#sideButtonMap_div').css('overflow', 'visible');
					$('#place_main_map').replaceWith(mainMapDiv);

					currentMap = 'default';
					$('#defaultMap').addClass('sideButtonActive');
					$('#earthMap').removeClass('sideButtonActive');
				}
			});


			/* Directions listener // not fully implemeted

			$('#defaultMap_directions').click(function() {
				if (directionsActive) {

					$('#defaultMap_directions').removeClass('sideButtonActive');
					directionsActive = false;
				} else {

					$('#defaultMap_directions').addClass('sideButtonActive');
					directionsActive = true;
				}
			});*/

			// Show drawing manager listener
			$('#defaultMap_drawing').click(function() {
				if (drawingManagerActive) {
					drawingManager.setMap(null);
					$('#defaultMap_drawing').removeClass('sideButtonActive');
					drawingManagerActive = false;
				} else {
					drawingManager.setMap(mainMap);
					$('#defaultMap_drawing').addClass('sideButtonActive');
					drawingManagerActive = true;
				}
			});

			// Show panoramio layer listener
			$('#defaultMap_panoramio').click(function() {
				if (panoramioLayerActive) {
					panoramioLayer.setMap(null);
					$('#defaultMap_panoramio').removeClass('sideButtonActive');
					panoramioLayerActive = false;
				} else {
					panoramioLayer.setMap(mainMap);
					$('#defaultMap_panoramio').addClass('sideButtonActive');
					panoramioLayerActive = true;
				}
			});

			// Show normal map listener
			$('#defaultMap_weather').click(function() {
				if (weatherLayerActive) {
					weatherLayer.setMap(null);
					$('#defaultMap_weather').removeClass('sideButtonActive');
					weatherLayerActive = false;
				} else {
					weatherLayer.setMap(mainMap);
					$('#defaultMap_weather').addClass('sideButtonActive');
					weatherLayerActive = true;
				}
			});

			// Switch to earth map listener
			$('#earthMap').click(function() {
				if (currentMap != 'earth') {
					mainMapDiv = mainMap.getDiv();

					if (directionsActive) {
						// remove panel
						// enlarge main map div
					}

					$('#sideButtonMap_div').css('height', '0');
					$('#sideButtonMap_div').css('overflow', 'hidden');

					$('#place_main_map').replaceWith('<div id="place_main_map"></div>');
					google.earth.createInstance('place_main_map', earthInitCallback, earthFailureCallback);

					currentMap = 'earth';
					$('#defaultMap').removeClass('sideButtonActive');
					$('#earthMap').addClass('sideButtonActive');
				}
			});

			// Show full screen map listener
			$('#fullMap').click(function() {
				$.fancybox.open({
				    content: '<div id="place_full_map"></div>',
				    autoSize: false,
				    width: '90%',
				    height: '90%',
				    afterLoad: function(current, previous) {
				    	setTimeout(function() {
				    		$('#place_full_map').css('width', '100%');
				    		$('#place_full_map').css('height', '100%');

							if (currentMap == 'default') {
								fullMap = new google.maps.Map(document.getElementById('place_full_map'), {
									mapTypeId: mainMap.getMapTypeId(),
									zoom: (mainMap.getZoom()) + 1,
									center: mainMap.getCenter()
								});

							} else if (currentMap == 'earth')
								google.earth.createInstance('place_full_map', earthInitCallback, earthFailureCallback);
						}, 50);
				    },
				    beforeLoad: function() {
				    	if (currentMap == 'earth') {
			    			$('#place_main_map').replaceWith('<div id="place_main_map"></div>');
			    		}
				    },
				    afterClose: function() {
				    	if (currentMap == 'earth') {
			    			google.earth.createInstance('place_main_map', earthInitCallback, earthFailureCallback);
			    		}
				    }
				});
			});

			// Switch wiki language listener
			$('#switchLang').click(function() {
				$('#wiki_content').addClass('infobox');
				$('#wiki_content').html('<span>Se descarcă conținutul wikipedia...</span>');

				if (defaultLang) { // to en
					getWiki(placeDetails.name, 'en');
					defaultLang = false;
					$('#switchLang').text('RO');
				} else { // to ro
					getWiki(placeDetails.name, 'ro');
					defaultLang = true;
					$('#switchLang').text('EN');
				}

				$('#readMore').text('Mai mult...');
				fullWikiShown = false;
				summaryEmpty = false;
			});

			// Show more wiki listener
			$('#readMore').click(function() {
				if (fullWikiShown) { // less
					$('#fullEntry').css("height", "0");
					$('#fullEntry').css("overflow", "hidden");
					$('#readMore').text('Mai mult...');
					fullWikiShown = false;
				} else { // more
					$('#fullEntry').css("height", "100%");
					$('#fullEntry').css("overflow", "visible");
					$('#readMore').text('Mai putin...');
					fullWikiShown = true;
				}
			});

			// Test if Panoramio works (throws exception on urls with ș, ț)
			try {
				var widget = new panoramio.PhotoListWidget('panoramioTest', {
					rect: {
						sw: {
							lat: mainMap.getBounds().getSouthWest().lat(),
							lng: mainMap.getBounds().getSouthWest().lng()
						},
						ne: {
							lat: mainMap.getBounds().getNorthEast().lat(),
							lng: mainMap.getBounds().getNorthEast().lng()
						}
					}
				}, {
					'width': $('#main').width(),
					'height': 180,
					'columns': 4,
					'croppedPhotos': true
				});

				$('#panoramioTest').remove();
				$('#panoramioPhotos').click(function() {
					if (photosGalleryShown != 'panoramio') {
						$('<div id="wapiblock"></div>').insertAfter('#photosDiv');
						$('#photosDiv').css('display', 'none');

						var widget = new panoramio.PhotoListWidget('wapiblock', {
							rect: {
								sw: {
									lat: mainMap.getBounds().getSouthWest().lat(),
									lng: mainMap.getBounds().getSouthWest().lng()
								},
								ne: {
									lat: mainMap.getBounds().getNorthEast().lat(),
									lng: mainMap.getBounds().getNorthEast().lng()
								}
							}
						}, {
							'width': $('#main').width(),
							'height': 180,
							'columns': 4,
							'croppedPhotos': true
						});

						widget.setPosition(0);

						photosGalleryShown = 'panoramio';
						$('#placesPhotos').removeClass('sideButtonActive');
						$('#panoramioPhotos').addClass('sideButtonActive');
					}
				});

				$('#panoramioPhotos').css('visibility', 'visible');
				panoramioAvailable = true;
			} catch (err) {
				$('#panoramioPhotos').remove();
			}

			// Create gallery if panoramio works or if there are photos from google places
			if (place.photos) {
				$('#placesPhotos').css('visibility', 'visible');
				$('#photosDiv').css('visibility', 'visible');

				var removeNo = 10 - (count(place.photos));
				var removeNoCounter = removeNo;

				$.each($('#photosDiv ul li'), function(index, li) {
					if (removeNoCounter > 0) {
						li.remove();
						removeNoCounter--;
					} else {
						index = index - removeNo;
						var url = place.photos[index].getUrl({'maxWidth': 220, 'maxHeight': 140});
						li.getElementsByTagName('a').item(0).setAttribute('href', url);
						li.getElementsByTagName('img').item(0).setAttribute('src', url);
					}
				});

			} else {
				if (panoramioAvailable) {
					$('#panoramioPhotos').addClass('sideButtonActive');
					$('#photosDiv').replaceWith('<div id="wapiblock"></div>');

					var widget = new panoramio.PhotoListWidget('wapiblock', {
						rect: {
							sw: {
								lat: mainMap.getBounds().getSouthWest().lat(),
								lng: mainMap.getBounds().getSouthWest().lng()
							},
							ne: {
								lat: mainMap.getBounds().getNorthEast().lat(),
								lng: mainMap.getBounds().getNorthEast().lng()
							}
						}
					}, {
						'width': $('#main').width(),
						'height': 180,
						'columns': 4,
						'croppedPhotos': true
					});

					widget.setPosition(0);
					photosGalleryShown = 'panoramio';
				} else {
					$('#placesPhotos').remove();
					$('#photosGallery').remove();
				}
			}

			// Show photos from Google Places db button listener
			$('#placesPhotos').click(function() {
				if (photosGalleryShown != 'places') {
					$('#wapiblock').remove();
					$('#photosDiv').css('display', 'block');

					photosGalleryShown = 'places';
					$('#placesPhotos').addClass('sideButtonActive');
					$('#panoramioPhotos').removeClass('sideButtonActive');
				}
			});

			// Load wiki content
			getWiki(place.name, defaultLang ? 'ro' : 'en');

			// Save search in db
			$.ajax({
				type: 'POST',
				url: '/search/storeSearch/' + place.name,
				data: {
					'pic': place.photos ? (place.photos[0].getUrl({'maxWidth': 220, 'maxHeight': 140})) : ('http://maps.googleapis.com/maps/api/staticmap?center='+placeLat+','+placeLng+'&zoom='+mainMap.getZoom()+'&size=220x140&sensor=false'),
					'place': siteUrl + 'place/info/' + escape(place.name)
				}
			});
		} else
			displayError();
	}

	/**
	 * Called for google earth init
	 * @param  {[type]} ge [description]
	 * @return {[type]}    [description]
	 */
	function earthInitCallback(ge) {
	    ge.getWindow().setVisibility(true);

	    ge.getNavigationControl().setVisibility(ge.VISIBILITY_AUTO);
	    ge.getLayerRoot().enableLayerById(ge.LAYER_BORDERS, true);
	    ge.getLayerRoot().enableLayerById(ge.LAYER_ROADS, true);

	    var lookAt = ge.createLookAt('');
	    lookAt.setLatitude(placeDetails.geometry.location.lat());
	    lookAt.setLongitude(placeDetails.geometry.location.lng());
	    lookAt.setRange(5000);
	    ge.getView().setAbstractView(lookAt);
	}

	/**
	 * Google earth error handler
	 * @param  {[type]} errorCode [description]
	 * @return {[type]}           [description]
	 */
	function earthFailureCallback(errorCode) {
		alert("Eroare Google Earth: " + errorCode);

		$('#sideButtonMap_div').css('height', '100%');
		$('#sideButtonMap_div').css('overflow', 'visible');
		$('#place_main_map').replaceWith(mainMapDiv);

		currentMap = 'default';
		$('#defaultMap').addClass('sideButtonActive');
		$('#earthMap').removeClass('sideButtonActive');
	}

	/**
	 * Show error when the places api search fails
	 * @return {[type]} [description]
	 */
	function displayError() {
		$('#content').html('<header class="page-header"><h1 class="page-title">Obiectivul căutat nu a fost găsit.</h1></header>');
		$('#content').append('<h2 class="slogan align-center">Caută, explorează, descoperă!<br><div><form id="search" action="javascript: formSubmit();"><input type="text" placeholder="Cauta un oras, o statiune sau obiective turistice." id="search_box" autocomplete="off"><input type="submit" value="Cauta"></form></div></h2>');
	}

	/**
	 * Get wiki content from /place/wiki
	 * @param  {[type]} pageName [description]
	 * @param  {[type]} lang     [description]
	 * @return {[type]}          [description]
	 */
	function getWiki(pageName, lang) {
		setTimeout(function() {
			pageName = replaceAll(pageName, ' ', '_');
			$('#readMore').css('visibility', 'hidden');
			$('#switchLang').css('visibility', 'hidden');

			$.ajax({
				dataType: "text",
				type: 'GET',
				cache: false,
				url: '<?php echo site_url("/place/wiki/summary/"); ?>' + '/' + lang + '/' + pageName,
				success: function(data) {
					if (trimEnds(data).length == 0)
						summaryEmpty = true;
					else {
						$('#wiki_content').removeClass('infobox');
						$('#wiki_content').html(data);
						$('#wiki_content').append('<div id="fullEntry"></div>');
						$('#switchLang').css('visibility', 'visible');
					}

					$.ajax({
						dataType: "text",
						type: 'GET',
						cache: false,
						url: '<?php echo site_url("/place/wiki/full/"); ?>' + '/' + lang + '/' + pageName,
						success: function(dataFull) {
							if (summaryEmpty) {
								$('#wiki_content').removeClass('infobox');
								$('#wiki_content').html('<div id="fullEntry"></div>');
								$('#fullEntry').html(dataFull);

								$('#fullEntry').css("height", "100%");
								$('#fullEntry').css("overflow", "visible");
								$('#switchLang').css('visibility', 'visible');
								$('#readMore').text('Mai putin...');
							} else {
								$('#fullEntry').html(dataFull);
								$('#readMore').css('visibility', 'visible');
							}

							// if find 'noarticletext'
						}
					});
				}
			});
		}, 10);
	}

	/**
	 * Translates the place types
	 * @param  {[type]} types [description]
	 * @return {[type]}       [description]
	 */
	function getTypes(types) {
		var string = '';

		$.each(types, function(index, type) {
			if (placeTypes[type] && string.indexOf(placeTypes[type] + '</a>') == -1) {
				if (index > 0)
					string += ', ';

				string += getHref(placeTypes[type], '<?php echo site_url("place/tag/"); ?>' + '/' + placeTypes[type], 'Alte atractii cu eticheta \'' + placeTypes[type] + '\'', 'tag');

				/*if (placeTypes[type] == 'Parc')
					mainMap.setMapTypeId(google.maps.MapTypeId.HYBRID);*/
				if (placeTypes[type] == 'Localitate')
					mainMap.setMapTypeId(google.maps.MapTypeId.ROADMAP);
			}
		});

		return string;
	}

	/**
	 * Get link html tag
	 * @param  {[type]} name  [description]
	 * @param  {[type]} url   [description]
	 * @param  {[type]} title [description]
	 * @param  {[type]} tag   [description]
	 * @return {[type]}       [description]
	 */
	function getHref(name, url, title, tag) {
		if (!url)
			return null;

		if (tag == 'tag')
			return '<span class="button" style="padding: 1px 6px 2px 5px; pointer-events: none; cursor: default;">' + name + '</span>';

		return '<a href="' + url + '" title="' + title + '">' + name + '</a>';
	}

	/**
	 * Remove spaces from start/end
	 * @param  {[type]} string [description]
	 * @return {[type]}        [description]
	 */
	function trimEnds(string) {
		return string.replace(/(^\s+|\s+$)/g, '');
	}

	/**
	 * Count children of obj
	 * @param  {[type]} obj [description]
	 * @return {[type]}     [description]
	 */
	function count(obj) {
		var size = 0, key;

		for (key in obj)
			if (obj.hasOwnProperty(key) && obj[key] != '' && obj[key] != null && obj[key] != 'undefined')
				size++;

		return size;
	}

	/**
	 * Replace string in string
	 * @param  {[type]} string [description]
	 * @param  {[type]} str1   [description]
	 * @param  {[type]} str2   [description]
	 * @param  {[type]} ignore [description]
	 * @return {[type]}        [description]
	 */
	function replaceAll(string, str1, str2, ignore) {
		return string.replace(new RegExp(str1.replace(/([\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g, function(c){return "\\" + c;}), "g"+(ignore?"i":"")), str2);
	}

	/**
	 * Insert list into sidebar
	 * @param  {[type]} list     [description]
	 * @param  {[type]} category [description]
	 * @param  {[type]} name     [description]
	 * @return {[type]}          [description]
	 */
	function listAdd(list, category, name) {
		if (count(list) == 0)
			return;

		$('#sidebar').append('<div class="widget"><h6 class="widget-title">' + name + '</h6><ul class="arrow dotted" id="place_info_' + category + '"></ul></div><!-- end .widget -->');

		for (key in list) {
			var value = list[key];

			if (value != null && value != 'undefined' && value != 'NaN') {
				if (key.substring(0, 4) == 'null')
					key = '';

				$('#place_info_' + category).append('<li>' + key + (key == '' ? ' ' : ': ') + value + '</li>');
			}
		}
	}

	// DOM load listener
	google.maps.event.addDomListener(window, 'load', initialize);
</script>

<section id="content" class="container clearfix">

	<header class="page-header">

		<h1 class="page-title">
			<a id="place_title_href">
				<img id="place_icon">
				<span id="place_title"></span>
			</a>
		</h1>

	</header><!-- end .page-header -->

	<div id="place_main_map"></div>

	<div id="sideButtonMapWrapper">
		<button class="sideButtonMap sideButtonActive" id="defaultMap">Google Maps</button>
		<div id="sideButtonMap_div">
			<!-- <button class="sideButtonMap" id="defaultMap_directions">Directions</button> // not implemented yet-->
			<button class="sideButtonMap" id="defaultMap_drawing">Drawing</button>
			<button class="sideButtonMap" id="defaultMap_panoramio">Panoramio</button>
			<button class="sideButtonMap" id="defaultMap_weather">Weather</button>
		</div>
		<button class="sideButtonMap" id="earthMap">Google Earth</button>
		<button class="sideButtonMap" id="fullMap">Fullscreen</button>
	</div>

	<section id="main">

		<article class="entry single clearfix">

			<div class="entry-body">
				<button class="sideButton sideTitle" id="wiki">WIKI</button>
				<button class="sideButton" id="switchLang"><?php echo $this->uri->segment(4) == 'en' ? 'RO' : 'EN'; ?></button>
				<button class="sideButton" id="readMore">Mai mult...</button>

				<div id="wiki_content" class="infobox">
					<span>Se descarcă conținutul wikipedia...</span>
				</div>

				<div id="photosGallery">
					<button class="sideButton sideButtonActive" id="placesPhotos">Google Places</button>
					<button class="sideButton" id="panoramioPhotos">Panoramio</button>

					<div id="photosDiv">
						<h6 class="section-title">Poze Google Places</h6>
						<ul class="projects-carousel clearfix">
							<li>
								<a href="">
									<img src="" alt="">
								</a>
							</li>

							<li>
								<a href="">
									<img src="" alt="">
								</a>
							</li>

							<li>
								<a href="">
									<img src="" alt="">
								</a>
							</li>

							<li>
								<a href="">
									<img src="" alt="">
								</a>
							</li>

							<li>
								<a href="">
									<img src="" alt="">
								</a>
							</li>

							<li>
								<a href="">
									<img src="" alt="">
								</a>
							</li>

							<li>
								<a href="">
									<img src="" alt="">
								</a>
							</li>

							<li>
								<a href="">
									<img src="" alt="">
								</a>
							</li>

							<li>
								<a href="">
									<img src="" alt="">
								</a>
							</li>

							<li>
								<a href="">
									<img src="" alt="">
								</a>
							</li>
						</ul><!-- end .projects-carousel -->
					</div>
					<div id="panoramioTest"></div>
				</div>
			</div>

		</article><!-- end .entry -->

	</section><!-- end #main -->

	<aside id="sidebar"></aside>

</section><!-- end #content -->

<?php $this->load->view('footer'); ?>