<?php 


// Get new instance of MySQLi object
$mysqli = @new mysqli('127.0.0.1', 'codeup', 'password', 'codeup_mysqli_test_db');

// Check for errors
if ($mysqli->connect_errno) {
    throw new Exception('Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$sortCol = '';
if (!empty($_GET['sort_column'])) {
  $sortCol = $_GET['sort_column'];
}

$sortOrd = '';
if (!empty($_GET['sort_order'])) {
  $sortOrder = $_GET['sort_order'];
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>National Parks</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

  </head>
  <body>
    <h1>US National Parks</h1>
    <table class="table table-bordered">
      <tr>
        <th>Name
          <a href="?sort_column=name&sort_order=asc" ><span class="glyphicon glyphicon-chevron-up"></span></a>
          <a href="?sort_column=name&sort_order=desc"><span class="glyphicon glyphicon-chevron-down"></span></a>
        </th>
        <th>Location
          <a href="?sort_column=location&sort_order=asc"><span class="glyphicon glyphicon-chevron-up"></span></a>
          <a href="?sort_column=location&sort_order=desc"><span class="glyphicon glyphicon-chevron-down"></span></a>
        </th>
        <th>Description</th>
        <th>Date Est.
          <a href="?sort_column=date_established&sort_order=asc"><span class="glyphicon glyphicon-chevron-up"></span></a>
          <a href="?sort_column=date_established&sort_order=desc"><span class="glyphicon glyphicon-chevron-down"></span></a>
        </th>
        <th>Area in Acres</th>
      </tr>
    <?php 
      if (isset($_GET['sort_column'])) {
        $result = $mysqli->query("SELECT * FROM national_parks ORDER BY $sortCol $sortOrder");
      } else {
        $result = $mysqli->query("SELECT * FROM national_parks");
      }
      
      while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $row['name'] . "</td>";
          echo "<td>" . $row['location'] . "</td>";
          echo "<td>" . $row['description'] . "</td>";
          echo "<td>" . $row['date_established'] . "</td>";
          echo "<td>" . $row['area_in_acres'] . "</td>";
          echo "</tr>";
      }

    ?>
    </table>

    
  


  
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
  </body>
</html>