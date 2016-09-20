<?php
    session_start();
    if( ! isset($_SESSION['admin'])){
        header('Location: login.php');
        return;
    }
    if( isset($_POST['delete'])){
        $del_id = $_POST['delete'];
        $sql = "DELETE FROM Survey WHERE id=$del_id";
        $config = parse_ini_file('/var/www/survey-config.ini');
        $con = new mysqli("localhost", $config['db_username'], $config['db_password'], $config['dbname']);
        if($con->connect_error){
            $_SESSION['error'] = $con->connect_error;
            header('Location: admin.php');
            return;
        }
        $result = $con->query($sql);
        if($result == true){
            $_SESSION['status'] = "Survey $del_id deleted.";
            header('Location: admin.php');
            return;
        }
    }
    else{
        header('Location: login.php');
        return;
    }
?>
