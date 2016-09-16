<?php
	session_start();
	if( isset($_POST['email'])){
		$email = $_POST['email'];
		if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                    $config = parse_ini_file('/var/www/survey-config.ini');
                    $con = new mysqli("localhost", $config['username'], $config['password'], $config['dbname']);
			if($con->connect_error){
        		$_SESSION['error'] = $con->connect_error;
				header('Location: index.php');
				return;
  			}
			$query = "SELECT 1 FROM Survey WHERE email='$email'";
			$result = $con->query($query);
			$con->close();
			if($result->num_rows > 0){
				$_SESSION['error']="$email already submitted survey.";
  				header('Location: index.php');
				return;
			}
			$_SESSION['email'] = $email;
			header('Location: survey.php');
			return;
		}
		else{
			$_SESSION['error'] = "Invalid email address";
			header('Location: index.php');
			return;
		}
	}
	elseif( isset($_POST['uname'])){
		$config = parse_ini_file('/var/www/survey-config.ini');
		$username = $config['username'];
		$password = $config['password']; 
		if( ($_POST['uname'] == $username) && ($_POST['pwd'] == $password) ){
			$_SESSION['admin'] = true;
			header('Location: admin.php');
			return;
		}
		else{
			$_SESSION['error'] = "Invalid admin username / password";
			header('Location: index.php');
			return;
		}
	}
	else{
		header('Location: index.php');
		return;
	}
?>
