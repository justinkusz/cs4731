<?php
    session_start();
    if( ! isset($_SESSION['admin'])){
        header('Location: index.php');
        return;
    }
    
    echo "<a href='logout.php' class='logout'>Log out</a>";

    $config = parse_ini_file('/var/www/survey-config.ini');
    $con = new mysqli("localhost", $config['username'], $config['password'], $config['dbname']);
    if($con->connect_error){
        $_SESSION['error'] = $con->connect_error;
    }
    
    if($_GET['sort'] == "desc"){
        $sql = "SELECT * FROM Survey ORDER BY created DESC";
    }
    else{
        $sql = "SELECT * FROM Survey";
    }
    $result = $con->query($sql);
    echo "<p>Sort: <a href='admin.php?sort=desc'>Newest</a> | <a href='admin.php?sort=asc'>Oldest</a></p>";
    echo "<table>";

     // Print the table's header row
     $numFields = $result->field_count;
     $finfo = $result->fetch_fields();
     echo "<tr>";
     foreach ($finfo as $val) {
         echo "<td><b><u>" . $val->name . "</u></b></td>";
     }
     echo "</tr>";

     // Print each row
     while($row = $result->fetch_row())
     {
         $id = $row[0];
         echo "<tr>";
         for($j = 0; $j < $numFields; $j++)
         {
             echo "<td>" . $row[$j] . "</td>";
         }
         echo '<td><form onsubmit="return confirm(' . "Delete survey?'" . ');"' . "
                method='post' action='delete.php'><input type='hidden' name='delete' value='$id'>
                <input class='delete' type='submit' value='Delete'></form></td>";
         echo "</tr>";
     }
     echo "</table>";

    if( isset($_SESSION['status'])){
        echo "<p style='color:green'>" . $_SESSION['status'] . "</p>";
        unset($_SESSION['status']);
    }
    if( isset($_SESSION['error'])){
        echo "<p style='color:red'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
?>

<html>
<head><title>Survey - Admin</title>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        text-align: left;
        padding: 8px;
    }
    tr:nth-child(even){background-color: #f2f2f2}

    th {
        background-color: #4CAF50;
        color: white;
    }
    .delete {
        background-color: #f44336;
        border: none;
        color: white;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
    }
    
    .logout {
        position: absolute;
        right: 0px;
        background-color: #4CAF50;
        border: none;
        color: white;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
    }
</style>
</head>
</html>
