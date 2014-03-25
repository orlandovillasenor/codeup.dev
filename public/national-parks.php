<?php 
//error_reporting(0);

// Get new instance of MySQLi object
$mysqli = @new mysqli('127.0.0.1', 'codeup', 'password', 'codeup_mysqli_test_db');

// Check for errors
if ($mysqli->connect_errno) {
    throw new Exception('Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$entry = [];
$missing_required = FALSE;
try {
  if (!empty($_POST)) {
    $name = ucwords($_POST['name']);
    $location = ucwords($_POST['location']);
    $description = ucfirst($_POST['description']);
    $date_established = ($_POST['date_established']);
    $area_in_acres = ($_POST['area_in_acres']);
    $entry = ['name' => $name, 'location' => $location, 'description' => $description, 'date_established' => $date_established, 'area_in_acres' => $area_in_acres];
    foreach ($entry as $key => $value){
      if (empty($value)) {
        $missing_required = TRUE;
        $value = '';
      } 
    }

    if ($missing_required == FALSE) {
      // Create the prepared statement
      $stmt = $mysqli->prepare("INSERT INTO national_parks (name, location, description, date_established, area_in_acres) VALUES (?, ?, ?, ?, ?)");

      // bind parameters
      $stmt->bind_param("ssssd", $name, $location, $description, $date_established, $area_in_acres);

      // execute query, return result
      $stmt->execute();
    }
  }
} catch (InvalidInputException $e) {
    $missing_required = TRUE;
    $error_message = $e->getMessage();
  }

$valid_get = ['name', 'location', 'date_established'];

$sortCol = 'name';
if (!empty($_GET['sort_column']) && in_array($_GET['sort_column'], $valid_get)) {
  $sortCol = $_GET['sort_column'];
}

$sortOrder = 'asc';
if (!empty($_GET['sort_order']) && $_GET['sort_order'] == 'desc') {
  $sortOrder = $_GET['sort_order'];
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>US National Parks</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <!-- My CSS -->
    <link rel="stylesheet" href="/css/national-parks.css">
    <link href='http://fonts.googleapis.com/css?family=Nothing+You+Could+Do' rel='stylesheet' type='text/css'>

  </head>
  <body>

    <!-- Navbar top -->
      <div class="container">
    <div class="navbar navbar-inverse navbar-static-top">
      <a href="#" class="navbar-brand">US National Parks</a>
        <button class="navbar-toggle" data-toggle = "collapse" data-target = ".navHeaderCollapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse navHeaderCollapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="#page-table">Parks</a></li>
            </li>
            <li><a href="#page-form">Submit Form</a></li>           
          </ul>
        </div>
      </div>
    </div>

    <!-- Jumbotron -->
    <div class="container">
      <div class="widewrapper main">
            <img src="/img/arches-photo.jpg" class="img-responsive" alt="">
      </div>    
      <div class="jumbotron">
        <h1 class="text-center">United States National Parks</h1>
      </div>     
    </div>

    <!-- Table -->
  <section id="page-table" class="page-table">   
    <div class="container">
      <div class="table-responsive">
        <table class="table table-bordered">
          <tr>
            <th width="15%"><h3>Name &nbsp <a href="?sort_column=name&sort_order=asc&#page-table" ><span class="glyphicon glyphicon-chevron-up"></span></a><a href="?sort_column=name&sort_order=desc&#page-table"><span class="glyphicon glyphicon-chevron-down"></span></a></h3>
            </th>
            <th width="15%"><h3>Location &nbsp <a href="?sort_column=location&sort_order=asc&#page-table"><span class="glyphicon glyphicon-chevron-up"></span></a><a href="?sort_column=location&sort_order=desc&#page-table"><span class="glyphicon glyphicon-chevron-down"></span></a></h3>              
            </th>
            <th width="30%"><h3>Description</h3></th>
            <th width="15%"><h3>Date Est. &nbsp <a href="?sort_column=date_established&sort_order=asc&#page-table"><span class="glyphicon glyphicon-chevron-up"></span></a><a href="?sort_column=date_established&sort_order=desc&#page-table"><span class="glyphicon glyphicon-chevron-down"></span></a></h3>             
            </th>
            <th width="15%"><h3>Area in Acres</h3></th>
          </tr>
          <?php 
            if (isset($_GET['sort_column']) || isset($_GET['sort_order'])) {
              $result = $mysqli->query("SELECT * FROM national_parks ORDER BY $sortCol $sortOrder");
            } else {
              $result = $mysqli->query("SELECT * FROM national_parks");
            }
            $mysqli->close();
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars(strip_tags($row['name'])) . "</td>";
                echo "<td>" . htmlspecialchars(strip_tags($row['location'])) . "</td>";
                echo "<td>" . htmlspecialchars(strip_tags($row['description'])) . "</td>";
                echo "<td>" . htmlspecialchars(strip_tags($row['date_established'])) . "</td>";
                echo "<td>" . htmlspecialchars(strip_tags($row['area_in_acres'])) . " Acres</td>";
                echo "</tr>";
            }

          ?>
        </table>
      </div>
    </div>
  </section>

    <!-- Form -->
  <section id="page-form" class="page-form">
    <div class="container">
      <form method="POST" action="" class="form-horizontal" role="form">
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Park Name </label>
              <div class="col-sm-10">
            <input id="name" name="name" type="text" placeholder="Park Name" value="<?php echo isset($_POST['name'])? $_POST['name'] : "" ?>"> * <? if(empty($name)) {echo "Park Name Required";} ?>
              </div>
        </div>
        <div class="form-group">
            <label for="location" class="col-sm-2 control-label">Location </label>
            <div class="col-sm-10">
            <input id="location" name="location" type="text" placeholder="State" value="<?php echo isset($_POST['location'])? $_POST['location'] : "" ?>"> * <? if(empty($location)) {echo "Location Required";} ?>
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="col-sm-2 control-label">Description </label>
            <div class="col-sm-10">
            <input id="description" name="description" type="text" placeholder="Park Description" value="<?php echo isset($_POST['description'])? $_POST['description'] : "" ?>"> * <? if(empty($description)) {echo "Description Required";} ?>
            </div>
        </div>
        <div class="form-group">
            <label for="date_established" class="col-sm-2 control-label">Date Est. </label>
            <div class="col-sm-10">
            <input id="date_established" name="date_established" type="text" placeholder="YYYY-MM-YY" value="<?php echo isset($_POST['date_established'])? $_POST['date_established'] : "" ?>"> * <? if(empty($date_established)) {echo "Date Established Required";} ?>
            </div>
        </div>
        <div class="form-group">
            <label for="area_in_acres" class="col-sm-2 control-label">Area in Acres </label>
            <div class="col-sm-10">
            <input id="area_in_acres" name="area_in_acres" type="text" placeholder="Acres (ex. 4000.00)" value="<?php echo isset($_POST['area_in_acres'])? $_POST['area_in_acres'] : "" ?>"> * <? if(empty($area_in_acres)) {echo "Acres Required";} ?>
            </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-info">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </section>

  <!--Footer-->
  <div class="container">
    <div class="navbar navbar-inverse navbar-bottom">
      <p class="navbar-text copyright text-muted small">Copyright &copy; <a href="http://codeup.dev/my-site/index.html">Orlando Villaseñor</a>  2014</p>
      <a href="http://en.wikipedia.org/wiki/List_of_national_parks_of_the_United_States" target="_blank" class="navbar-btn btn-danger btn pull-right">More Park Fun</a>
    </div>
  </div>  
  
 
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
  </body>
</html>