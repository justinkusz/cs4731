<?php
    session_start();
    if( ! isset($_SESSION['email']) ){
        header('Location: index.php');
        return;
    }
    $email = $_SESSION['email'];
    $plans = $_POST['plans'];
    $strengths = $_POST['strengths'];
    $weaknesses = $_POST['weaknesses'];

    $config = parse_ini_file('/var/www/survey-config.ini');
    $con = new mysqli("localhost", $config['db_username'], $config['db_password'], $config['dbname']);
    if($con->connect_error){
        echo $con->connect_error;
        return;
    }

    $query = "INSERT INTO Survey (email, plans, strengths, weaknesses) VALUES ('$email', '$plans', '$strengths', '$weaknesses')";
    $result = $con->query($query);
    if($result == false){
        echo $con->error."<br>";
    }
    $con->close();
    echo "<div class='center'><p style='color:green'>Survey submitted. Thank you!</p><p>Redirecting to <a href='login.php'>login</a> page in 3 seconds...</p></div>";
    unset($_SESSION['email']);
    header("refresh:3; url=index.php" );
?>

<html>
<head><title>Survey - Thank you!</title>
<style>
    .center {
        position: absolute;
        left: 0;
        top: 50%;
        width: 100%;
        text-align: center;
        font-size: 18px;
    }
</style>
</head>
</html>
