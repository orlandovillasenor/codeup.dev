<?php

//error_reporting(0);

// Get new instance of MySQLi object
$mysqli = @new mysqli('127.0.0.1', 'codeup', 'password', 'todo_db');

// Check for errors
if ($mysqli->connect_errno) {
    throw new Exception('Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

class InvalidInputException extends Exception {}

//$todo_list = new Filestore ('data/todo-list.txt');
//$todo_list = [];
$invalid_file_type = FALSE;
$error_message = '';
$items = [];

// if (filesize('data/todo-list.txt') > 0) {
//     $items = $todo_list->read();
// } else {
//     $items = array();
// }

try {
if (isset($_POST['item']) && strlen($_POST['item']) > 200) {
    throw new InvalidInputException ("Item entered can not be greater than 240 characters. Please try again.");
}
if (isset($_POST['item']) && !empty($_POST['item'])) {
    $new_item = $_POST['item'];
    $new_item = ucwords($new_item);
    //array_push($items, $new_item);                  
    //$todo_list->write($items);
} else if (isset($_POST['item']) && empty($_POST['item'])) {
        throw new InvalidInputException ("You must enter an item!");
} 
} catch (InvalidInputException $e) {
    $error_message = $e->getMessage();
}       

if (isset($_POST['item']) && !empty($_POST['item'])) {
    // Create the prepared statement
    $stmt = $mysqli->prepare("INSERT INTO todos (todo) VALUES (?)");

    // bind parameters
    $stmt->bind_param("s", $new_item);

    // execute query, return result
    $stmt->execute();
}

if (!empty($_POST['remove'])) {
    $stmt = $mysqli->prepare("DELETE FROM todos WHERE id = ?;");
    $stmt->bind_param("i", $_POST['remove']);
    $stmt->execute();
}

if (count($_FILES) > 0 && $_FILES['uploaded_file']['type'] != 'text/plain') {
    $invalid_file_type = TRUE;
} else if (count($_FILES) > 0 && $_FILES['uploaded_file']['error'] == 0 && $_FILES['uploaded_file']['type'] == 'text/plain') {
    // Set the destination directory for uploads
    $upload_dir = '/vagrant/sites/codeup.dev/public/landing-page/uploads/';
    // Grab the filename from the uploaded file by using basename
    $uploaded_filename = basename($_FILES['uploaded_file']['name']);
    // Create the saved filename using the file's original name and our upload directory
    $saved_filename = $upload_dir . $uploaded_filename;
    // Move the file from the temp location to our uploads directory
    move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $saved_filename);
    $upload = new Filestore($saved_filename);
    $uploaded_file = $upload->read($saved_filename);    
    if (isset($_POST['replace_file'])) {
        unset($items);
        $items = $uploaded_file;
        $todo_list->write($items);
    } else {
        foreach ($uploaded_file as $value) {
            array_push($items, $value);
            $todo_list->write($items);      
        }
    }
}

$itemsPerPage = 5;
$currentPage = !empty($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

$result = $mysqli->query("SELECT * FROM todos LIMIT $itemsPerPage OFFSET $offset;");
$allTodos = $mysqli->query("SELECT * FROM todos;"); 
//$result = $mysqli->query("SELECT * FROM todos");

$maxPage = ceil($allTodos->num_rows / $itemsPerPage); 
$prevPage = $currentPage > 1 ? $currentPage - 1 : null;
$nextPage = $currentPage < $maxPage ? $currentPage + 1 : null;

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ToDo List App</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom Google Web Font -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <!-- Add custom CSS here -->
    <link href="css/todo.css" rel="stylesheet">

</head>

<body>

    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="http://codeup.dev/my-site/index.html">Orlando Villaseñor</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="#todo">My List</a>
                    </li>
                    <li><a href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <div class="intro-header">

        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <div class="intro-message">
                        <h1>Go List Pro</h1>
                        <h3>A Basic ToDo List App</h3>
                        <hr class="intro-divider">
                        <ul class="list-inline intro-social-buttons">
                            <!-- <li><a href="https://twitter.com/SBootstrap" class="btn btn-default btn-lg"><i class="fa fa-twitter fa-fw"></i> <span class="network-name">Twitter</span></a>
                            </li> -->
                            <li><a href="#todo" class="btn btn-default btn-lg"><i class="fa fa-tasks" ></i> <span class="network-name">Get Started</span></a>
                            <!-- </li>
                            <li><a href="#" class="btn btn-default btn-lg"><i class="fa fa-linkedin fa-fw"></i> <span class="network-name">Linkedin</span></a>
                            </li> -->
                        </ul>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container -->

    </div>
    <!-- /.intro-header -->

    <div class="content-section-a" id="todo">

        <div class="container">

            <div class="row">
                <div class="col-lg-5 col-sm-6">
                    <hr class="section-heading-spacer">
                    <div class="clearfix"></div>
                    <h2 class="section-heading">My List</h2>
                        <table class="table table-striped">                           
                            <? while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['todo']; ?> </td>
                                <td><button class="btn btn-danger btn-sm pull-right" onclick="removeById(<?= $row['id']; ?>)">Remove</button></td>
                            </tr>
                            <? endwhile; ?>
                        </table>
                    <div class="clearfix">
        <? if ($prevPage != null): ?>
            <a href="?page=<?= $prevPage;  ?>#todo" class="pull-left btn btn-default btn-sm">&lt; Previous</a> 
        <? endif; ?>
 
        <? if ($nextPage != null): ?>
            <a href="?page=<?= $nextPage; ?>#todo" class="pull-right btn btn-default btn-sm">Next &gt;</a> 
        <? endif; ?>
    </div>
                </div>
                
                <div class="col-lg-5 col-lg-offset-2 col-sm-6">
                  <hr class="section-heading-spacer">
                    <div class="clearfix"></div>
                    <h2>Add Item:</h2>
        
                    <? if (!empty($error_message)) : ?>
                        <div class="alert alert-danger"><?= "$error_message"; ?></div>                        
                    <? endif; ?>
            <form method="POST" action="todo.php#todo">
                <p>
                    <label for="item">Enter Item:</label>
                    <input id="item" name="item" type="text">
                </p>
                <p>
                    <button type="submit">Add to list</button>
                </p>
            </form> 

                </div>
            </div>

        </div>
        <!-- /.container -->

    </div>
    <!-- /.content-section-a -->

    
    <!-- /.content-section-a -->

    <div class="banner">

        <div class="container">

            <div class="row">
                <div class="col-lg-5">
                    <h2>Contact the developer:</h2>
                </div>
                <div class="col-lg-7">
                    <ul class="list-inline banner-social-buttons">
                        <li><a href="mailto:orlandovillasenor@me.com" target="" class="btn btn-default btn-lg"><i class="fa fa-envelope fa-fw"></i> <span class="network-name">Email</span></a>
                        </li>
                        <li><a href="https://twitter.com/ododoubleg" target="_blank" class="btn btn-default btn-lg"><i class="fa fa-twitter fa-fw"></i> <span class="network-name">Twitter</span></a>
                        </li>
                        <li><a href="https://linkedin.com/in/orlandovillasenor" target="_blank" class="btn btn-default btn-lg"><i class="fa fa-linkedin fa-fw"></i> <span class="network-name">Linkedin</span></a>
                        </li>
                        <li><a href="https://github.com/orlandovillasenor" target="_blank" class="btn btn-default btn-lg"><i class="fa fa-github fa-fw"></i> <span class="network-name">Github</span></a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <!-- /.container -->

    </div>
    <!-- /.banner -->

    <footer id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="list-inline">
                        <li><a href="#">Home</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li><a href="#todo">My List</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li><a href="#services">Services</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li><a href="#contact">Contact</a>
                        </li>
                    </ul>
                    <p class="copyright text-muted small">Copyright &copy; <a href="http://codeup.dev/my-site/index.html">Orlando Villaseñor</a>  2014</p>
                </div>
            </div>
        </div>
    </footer>


<form id="removeForm" action="todo.php#todo" method="post">
    <input id="removeId" type="hidden" name="remove" value="">
</form>

    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
 <script>
    var form = document.getElementById('removeForm');
    var removeId = document.getElementById('removeId');

    function removeById(id) {
        if (confirm('Are you sure you want to remove item?')) {
        removeId.value = id;
        form.submit();
        }
    }

</script>
</body>

</html>