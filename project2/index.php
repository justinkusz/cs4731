<?php
	include('places.php');
	include('foursquare.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CS4731 - Mini-project 2</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/scrolling-nav.css" rel="stylesheet">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> 
	<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyDGsF2B86a8Ozm21uhLGhj2upEaR2SUV3Y"></script>
    <script type="text/javascript" src="gmaps.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$('#left').scroll(function(){	//Hide twitter results when scrolling to other apps
			if($('#places').offset().top > 0 || $('#foursquare').offset().top < 100){
				$('#results').hide();
				$('#left').removeClass('col-xs-4').addClass('col-xs-8');
			}
		});
		$('#results').hide();

		$('#addplace').click(function(){
			var place = $(this.form).serializeArray();
			$.get("places.php", place);
		});
		$('#tweetsearch').click(function(){
			var search = $(this.form).serializeArray();
			$.get("search.php", search,function(data){
				$('#results').html(data);
			});
			$('#left').removeClass('col-xs-8').addClass('col-xs-4');
			$('#results').addClass('tweets');
			$('#results').show();
			$('#results').scrollTop(0);
		});
		var map = new GMaps({
			div: '#map-canvas',
			lat: 0,
			lng: 0,       
			zoom: 3,
		});
		GMaps.geolocate({
			success: function(position) {
				$('#lat').val(position.coords.latitude);
				$('#lng').val(position.coords.longitude);				
			},
			error: function(error) {
				alert('Geolocation failed: '+error.message);
			},
			not_supported: function() {
				alert("Your browser does not support geolocation");
			}
		});
		<?php
			if(! empty($_GET['query'])){
				$results = getFoursquare();
				foreach($results as $row){
					$name = filter_var($row['name'],FILTER_SANITIZE_STRING);
					$lat = $row['location']['lat'];
					$lng = $row['location']['lng'];
					$address = $row['location']['formattedAddress'];
					print "map.addMarker({
					lat:$lat,
					lng:$lng,
					infoWindow: {
					content: '<p><b>$name</b></p><p>$address[0]<br>$address[1]</p>'}
					});\n";
				}
				$address = urlencode($_GET['city']);
				$url ='https://maps.googleapis.com/maps/api/geocode/json?address='.$address;
				$geocode = file_get_contents($url);
				$json = json_decode($geocode, true);
				$zoom = 11;
				if ($json['status'] == 'OK')
				{
					$lat = $json['results'][0]['geometry']['location']['lat'];
					$lng = $json['results'][0]['geometry']['location']['lng'];
					echo "map.setCenter($lat,$lng);\n";
					echo "map.setZoom($zoom);\n";
				}
			}
			else{
				if (! empty($_GET['keyword'])){
					$keyword = $_GET['keyword'];
					$results = searchPlaces($con, $keyword);
				}
				else{
					$results = getPlaces($con);
				}
				while($row = $results->fetch_assoc()){
					$name = $row['title'];
					$description = $row['description'];
					$address = $row['address'];
					$lat = $row['lat'];
					$lng = $row['lng'];
					echo "map.addMarker({\n";
					echo "lat:".$lat.",\n";
					echo "lng:".$lng.",\n";
					echo "infoWindow: {\n";
					echo "content: '<p><b>".$name."</b></p><p>".$description."</p><p>".$address."</p>' }\n";
					echo "});\n";
				}
				echo "GMaps.geolocate({\n";
				echo "success: function(position){\n";
				echo "map.setCenter(position.coords.latitude, position.coords.longitude);\n";
				echo "},\n";

				echo "error: function(error){\n";
				echo "alert('Geolocation failed: '+error.message);\n";
				echo "},\n";

				echo "not_supported: function(){\n";
				echo "alert('Your browser does not support geolocation');\n";
				echo "}\n";
				echo "});\n";
			}
		?>
	});
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<div class="navbar navbar-custom navbar-fixed-top">
 <div class="navbar-header"><a class="navbar-brand" href="./">Mini-project 2</a>
      <a class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#places">MyFavoritePlaces</a></li>
        <li><a href="#tweets">Tweets Search</a></li>
        <li><a href="#foursquare">Foursquare Search</a></li>
        <li>&nbsp;</li>
      </ul>
    </div>
</div>
<div id="map-canvas"></div>
<div class="container-fluid" id="main">
  <div class="row">
  	<div class="col-xs-8" id="left">
	  <div id="places" class="container-fluid places">
		<h2>MyFavoritePlaces</h2><br>
		<form method="get" action="#places">
			<div class="form-group">
				<label for="name">Name</label>
				<input type="text" class="form-control" id="name" name="name">
			</div>
			<div class="form-group">
				<label for="address">Address</label>
				<input type="text" class="form-control" id="address" name="address">
			</div>
			<div class="form-group">
				<label for="description">Description</label>
				<input type="text" class="form-control" id="description" name="description">
			</div>
			<button type="submit" class="btn btn-primary">Add place</button>
        </form>
		<br><br>
        <form method="get" action="#places">
			<div class="form-group">
				<label for="keyword">Search places</label>
				<input type="text" class="form-control" id="keyword" name="keyword" placeholder="Keyword to search for">
			</div>
            <button id="search" type="submit" class="btn btn-primary">Search</button>
        </form>
	  </div>
	  <div id="tweets" class="container-fluid tweets">
	  	<h2>Tweets Search</h2>
		<form>
		<div class="form-group">
			<label for="hashtag">Keyword</label>
			<input type="text" class="form-control" id="hashtag" name="hashtag" placeholder="Keyword or hashtag">
		</div>
		<div class="form-group">
			<label for="lat">Latitutde</label>
			<input type="text" class="form-control" id="lat" name="lat" placeholder="Ex: 48.8584">
		</div>
		<div class="form-group">
			<label for="lng">Longitude</label>
			<input type="text" class="form-control" id="lng" name="lng" placeholder="Ex: 2.2945">
		</div>
		<div class="form-group">
			<label for="radius">Search radius (mi)</label>
			<select class="form-control" id="radius" name="radius">
				<option selected>Search radius</option>
				<option value="10">10</option>
				<option value="50">50</option>
				<option value="250">250</option>
			</select>
		</div>
		<button type="button" id="tweetsearch" class="btn btn-primary">Search</button>
		</form>
	  </div>
	  <div id="foursquare" class="container-fluid foursquare">
	  	<h2>Foursquare Search</h2>
		<form action="#foursquare" method="get">
			<div class="form-group">
				<label for="query">Keyword</label>
				<input id="query" type="text" class="form-control" name="query" placeholder="Ex: Pizza">
			</div>
			<div class="form-group">
				<label for="city">City</label>
				<input id="city" type="text" class="form-control" name="city" placeholder="Ex: Orlando, FL">
			</div>
			<div class="form-group">
				<label for="range">Search radius (m)</label>
				<select class="form-control" id="range" name="range">
					<option selected>Search radius</option>
					<option value="1000">1000</option>
					<option value="5000">5000</option>
					<option value="25000">25000</option>
				</select>
			</div>
			<button type="submit" id="foursquareSearch" class="btn btn-primary">Search</button>
		</form>
	  </div>
    </div>
	<div class="col-xs-4" id="results">
	</div>
    <div class="col-xs-4"><!--map-canvas will be postioned here--></div>
  </div>
</div>
</body>
</html>