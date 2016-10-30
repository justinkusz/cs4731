<?php
	$city = $_GET['city'];
    $class = $_GET['class'];

	// Configuration
	$dbhost = 'localhost';
	$dbname = 'tripadvisor';

	// Connect to database
	$m = new MongoClient();
	$db = $m->$dbname;
    if(empty($class)){
        $class = 0;
    }
    else{
        $class = doubleval($class);
    }
    header("Content-type: application/json");
	searchCity($db, $city, $class);

	function searchCity($db, $city, $class){

		$query = array('address.locality' => new MongoRegex('/.*'.$city.'.*/i'),
                'hotel_class' => array('$gte' => $class));
        
		$result = $db->hotels->find($query);
        echo json_encode(iterator_to_array($result, false));
	}
 ?>
