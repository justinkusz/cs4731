<?php
    if((empty($_GET['hashtag'])) || (empty($_GET['lat'])) || empty($_GET['lng'])){
        echo "Specify a keyword or hashtag, latitutde and longitude, and a distance";
        return;
    }
    require_once('TwitterAPIExchange.php');
    $settings = parse_ini_file('/var/www/config.ini');
    $url = "https://api.twitter.com/1.1/search/tweets.json";
    $requestMethod = "GET";
    $search = urlencode($_GET['hashtag']);
    $lat = $_GET['lat'];
    $lng = $_GET['lng'];
    $radius = $_GET['radius'];
    $location = $lat.','.$lng.','.$radius.'mi';
    $count = 100;
    $getfield = '?q='.$search.'&geocode='.$location.'&count='.$count;
    $twitter = new TwitterAPIExchange($settings);
    $string = json_decode($twitter->setGetField($getfield)->buildOauth($url,$requestMethod)->performRequest(),$assoc=TRUE);
    $statuses = $string['statuses'];
    echo "<a href='$url/$getfield'>Link</a>";  
    if(empty($statuses)){
        echo "No results";
        return;
    }
    foreach($statuses as $item)
    {
        $name = $item['user']['name'];
        $nick = $item['user']['screen_name'];
        $info = "Followers: ".$item['user']['followers_count'];
        $info .= ", Friends: ".$item['user']['friends_count'];
        $info .= ", Listed: ".$item['user']['listed_count'];
        echo '<div style="color:black" class="well well-sm">';
        echo "<p><strong><a title='".$info."' href='https://twitter.com/$nick'>$name</a></strong> tweeted:</p>";
        echo "<p><i>".$item['created_at']." via ".$item['source']."</i></p>";
        echo "<blockquote>".$item['text']."</blockquote>";
        echo '</div>';
    }
?>