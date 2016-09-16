<?php
	session_start();
?>
<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script>$(document).ready(function(){
					$("#admin").hide();
					$("#adminLogin").click(function(){
						$("#admin").toggle();
					})
				});
		</script>
		<title>Exit Survey - Login</title>
	</head>
	<body>
		<h1>Please Log In to take Survey</h1>
		<?php
			if ( isset($_SESSION['error']) ){
				echo('<p style="color:red">'.$_SESSION['error']."</p>\n");
				unset($_SESSION['error']);
			}
		?>
		<form id="student" method="post" action="login.php">
			<p>Email address: <input type="text" name="email"></p>
			<p><input type="submit" value="Log In"></p>
		</form>
		<p><a id="adminLogin" href="#">Administrator login</a></p>
		<form id="admin" method="post" action="login.php">
			<p>Username: <input type="text" name="uname"></p>
			<p>Password: <input type="password" name="pwd"></p>
			<p><input type="submit" value="Admin Log In"></p>
		</form>
	</body>
</html>
