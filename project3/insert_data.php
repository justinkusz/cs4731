<?php
// Configuration
$dbhost = 'localhost';
$dbname = 'tripadvisor';

// Connect to database
$m = new MongoClient();
$db = $m->$dbname;

$handle = fopen("/var/www/offering.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
			$hotel = json_decode($line, true);
			$db->hotels->insert($hotel);
    }
    fclose($handle);
} else {
    echo "error opening the file.";
}

$handle = fopen("/var/www/review.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
			$review = json_decode($line, true);
			$db->reviews->insert($review);
    }
    fclose($handle);
} else {
    echo "error opening the file.";
}
?>
