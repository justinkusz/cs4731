<?php
    function getFoursquare(){
        $config = parse_ini_file('config.ini');
        $client_id = $config['client_id'];
        $client_secret = $config['client_secret'];

        $url = 'https://api.foursquare.com/v2/venues/search';
        $url .= '?near='.urlencode($_GET['city']);
        $url .= '&query='.urlencode($_GET['query']);
        $url .= '&radius='.urlencode($_GET['range']);
        $url .= '&client_id='.$client_id;
        $url .= '&client_secret='.$client_secret;
        $url .='&v=20140806&m=foursquare';
    
        $file = file_get_contents($url);
        $data = json_decode($file, true);
        $items = $data['response']['venues'];
        return $items;
    }
?>