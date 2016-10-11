<?php
require 'connection.php';

    if((! empty($_GET['name'])) && (! empty($_GET['address'])) && (! empty($_GET['description']))){
        $name = $_GET['name'];
        $address = $_GET['address'];
        $description = $_GET['description'];
        addPlace($con, $name, $address, $description);
    }

    function addPlace($sqli, $name, $address, $description){
        $addr = urlencode($address);
        $url ='http://maps.googleapis.com/maps/api/geocode/json?address='.$addr.'&sensor=false';
        $geocode = file_get_contents($url);
        $results = json_decode($geocode, true);
        if($results['status']=='OK'){
            $lat = $results['results'][0]['geometry']['location']['lat'];
            $lng = $results['results'][0]['geometry']['location']['lng'];
        }
        $query = "INSERT INTO Places (title, address, description, lat, lng) VALUES ('$name', '$address', '$description', '$lat', '$lng')";

        $result = $sqli->query($query);
    }

    function getPlaces($sqli){
        $query = "SELECT * FROM Places";
        $results = $sqli->query($query);
        return $results;
    }

    function searchPlaces($sqli, $keyword){
        $query = "SELECT * FROM Places WHERE description LIKE '%$keyword%'";
        $results = $sqli->query($query);
        return $results;
    }
?>