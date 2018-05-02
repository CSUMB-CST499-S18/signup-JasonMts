<script>
     function validatePOS() {
            $.ajax({
                type: "GET",
                url: "https://capstone-frontend-kylekern.c9users.io/checkPOS.html",
                dataType: "json",
                data: {
                    'posNum': $('#posNum').val(),
                    'action': 'validate-username'
                },
                success: function(data,status) {
                    debugger;
                    if (data.length>0) {
                        $('#username-valid').html("POS code not in system");
                        $('#username-valid').css("color", "red");
                    } else {
                        $('#username-valid').html("POS code found!"); 
                        $('#username-valid').css("color", "green");
                    }
                  },
                complete: function(data,status) { 
                    //optional, used for debugging purposes
                    //alert(status);
                }
            });
    }
                
    // Filling array for item to be used in autocomplete
    global $con;
    $namedParameters = array();
    $results = null;
    $sql = "SELECT Description
            FROM sales";
    $stmt = $con -> prepare ($sql);
    $stmt -> execute($namedParameters);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // availableItems is the array name used for the prediction
    $availableItems = array();
    foreach($results as $result){
        array_push($availableItems,$result['Description']);
    }
    sort($availableItems);    
                
                
</script>

<?php

session_start();
if(!isset($_SESSION['username'])){
   header("Location:index.html");
}

include 'dbConnection.php';

$con = getDatabaseConnection('heroku_87e7042268995be');


function listUsers() {
    global $con;
    $namedParameters = array();
    $results = null;
    $sql = "SELECT *
            FROM topsales";
    $stmt = $con -> prepare ($sql);
    $stmt -> execute($namedParameters);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table id=\"table1\">
        <tr>
 	    <th> Description &nbsp &nbsp  &nbsp &nbsp  &nbsp&nbsp &nbsp  &nbsp &nbsp  &nbsp</th>
 	    <th> PosCode &nbsp &nbsp  &nbsp &nbsp  &nbsp &nbsp  &nbsp &nbsp  &nbsp&nbsp</th>
 	    <th> Total Sold &nbsp &nbsp  &nbsp &nbsp  &nbsp &nbsp  &nbsp &nbsp </th>
 	    <th> Total Stock &nbsp &nbsp  &nbsp&nbsp &nbsp  &nbsp &nbsp  &nbsp &nbsp  &nbsp</th>
        </tr>";
    foreach($results as $result) {
        echo "<tr>";
        echo "<td><a href=\"info.php?name=".$result['description']."\">". $result['description'] . "</a></td>".
        "<td>".$result['PosCode']."</td>".
        "<td>".$result['salesQuntity']."</td>".
        "<td>".$result['salesAmount']."</td>";
        echo "</tr>";
    }
    echo "</table>";
}


?>

<!DOCTYPE html>
<html>
    <head>
        <title>S.S.P.A.R</title>
        <meta charset="utf-8">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="./css/styles.css" type="text/css" />
    </head>
    
    <body>
    <section class="container">
      <div class="sscs"> 
          <img src="./img/sscs-logo.png" alt="SSCS">
      </div> 
      <div class="search">
      <form action="users.php" method="GET">
        <input id="search" type="text" placeholder="Type here">
         <script>
            $( "#searchItem" ).autocomplete({source: jArray});
        </script>
        <input id="submit" type="submit" value="Search">
      </form>
      <form action="users.php" method="GET">
        <input onchange="validatePOS();"input id="search" type="text" placeholder=" POS search">
        <input id="submit" type="submit" value="Search">
      </form>
      <form action="about.html">
        <input type="submit" value="About Us">
      </form>
      <form action="logout.php">
        <input type="submit" value="Logout" />
      </form>
     </div>
   </section>
      <div class="clear"></div>
     
<section class="container2">
    <center>
   <h2 class="sub-header">Top Selling Items</h2>
   <div id=table>
         <?php 
 	  listUsers();
    ?>
    </div>
</center>
</section>
    </body>
</html>