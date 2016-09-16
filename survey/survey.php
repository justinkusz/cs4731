<?php
	session_start();
	if( ! isset( $_SESSION['email']) ){
		header("Location: index.php");
		return;
	}
?>
	
<html>
<head>
<title>Computer Science Exit Interview</title>

</head>

<body >
<h1>Exit Survey</h1>
<h2>Department of Computer Science</h2>
<h2>Valdosta State University</h2>

<form id="form1" method="post" action="submit_survey.php">

      <br>
			What are your plans after graduation?
			<select name="plans">
			<option value="Back to school - at Valdosta State">Back to school - at Valdosta State </option>
			<option value="Back to school - elsewhere">Back to school - elsewhere</option>
			<option value="Employment in CS or a related field">Employment in CS or a related field</option>
			<option value="Employment in some other field">Employment in some other field</option>
			<option value="Taking a year off!">Taking a year off!</option>
			<option value="Not sure yet">Not sure yet</option>
			<option value="Other">Other</option>
			</select>
      <br>

      <br>
			What do you perceive to be the strengths of the computer science program?<br/>
			<textarea name="strengths" cols="54" rows="5"></textarea>
      <br>

      <br>
			What do you perceive to be the weaknesses of the computer science program?<br/>
			<textarea name="weaknesses" cols="56" rows="5"></textarea>
      <br>


<p style="text-align: center; font-weight: bold">Thank you for your time and effort!</p>
<div style="text-align: center"><input type="submit" value="Submit"/></div>
</form>


</body>
</html>