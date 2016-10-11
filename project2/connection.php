<?php
	$config = parse_ini_file('/var/www/config.ini');
    $con = new mysqli("localhost", $config['db_username'], $config['db_password'], $config['dbname']);
    if($con->connect_error){
        echo 'error';
        die('Connect Error');
    }
?>