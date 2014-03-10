<?php
require_once('classes/filestore.php');

class InvalidInputException extends Exception {}

$todo_list = new Filestore ('data/todo-list.txt');
$invalid_file_type = FALSE;
$error_message = '';

if (filesize('data/todo-list.txt') > 0) {
    $items = $todo_list->read();
} else {
    $items = array();
}

try {
if (isset($_POST['item']) && strlen($_POST['item']) > 240) {
    throw new InvalidInputException ("Item entered can not be greater than 240 characters. Please try again.");
}
if (isset($_POST['item']) && !empty($_POST['item'])) {
    $new_item = $_POST['item'];
    $new_item = ucwords($new_item);
    array_push($items, $new_item);                  
    $todo_list->write($items);
} else if (isset($_POST['item']) && empty($_POST['item'])) {
        throw new InvalidInputException ("Please Re-Enter Item.");
} 
} catch (InvalidInputException $e) {
    $error_message = $e->getMessage();
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

if (isset($_GET['remove'])) {
    $itemsId = $_GET['remove'];
    unset($items[$itemsId]);
    $todo_list->write($items);
    header("Location: todo.php");
    exit(0);
}
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
                    <li><a href="#services">Services</a>
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
                    <? if (count($items) > 0) : ?>
        
                        <ul>
                        <? foreach ($items as $key => $item) : ?>
                            <li><?= htmlspecialchars(strip_tags($item)); ?> <span id="remove_item"><a href="?remove=<?= $key; ?>" >remove</a></span></li>
                        <? endforeach; ?>
                        </ul>
                    
                    <? else : ?>
                        <p>Your list is empty</p>
                    <? endif; ?>
                        
                    
                </div>
                
                <div class="col-lg-5 col-lg-offset-2 col-sm-6">
                  <hr class="section-heading-spacer">
                    <div class="clearfix"></div>
                    <h2>Add Item:</h2>
        
                    <? if (!empty($error_message)) : ?>
                        <p><?= "$error_message"; ?></p>
                    <? endif; ?>

<form method="POST" action="">
            <p>
                <label for="item">Enter Item:</label>
                <input id="item" name="item" type="text" autofocus>
            </p>
            <p>
                <button type="submit">Add to list</button>
            </p>
        </form> 

        <? if ($invalid_file_type == TRUE) : ?>
            <p><?= "An invalid file type was uploaded. Please try again"; ?></p>
        <? endif; ?>

        <h2>Upload File</h2>

        <form method="POST" enctype="multipart/form-data" action="">
            <p>
                <label for="uploaded_file">File to add to list</label>
                <input id="uploaded_file" name="uploaded_file" type="file">
            </p>
            <p>
                <button type="submit">Upload:</button>
                <input id="replace_file" name="replace_file" type="checkbox">
                <label for="replace_file">Replace existing list</label>
                
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
                <div class="col-lg-6">
                    <h2>Connect with the developer:</h2>
                </div>
                <div class="col-lg-6">
                    <ul class="list-inline banner-social-buttons">
                        <li><a href="https://twitter.com/ododoubleg" target="_blank" class="btn btn-default btn-lg"><i class="fa fa-twitter fa-fw"></i> <span class="network-name">Twitter</span></a>
                        </li>
                        <li><a href="https://github.com/orlandovillasenor" target="_blank" class="btn btn-default btn-lg"><i class="fa fa-github fa-fw"></i> <span class="network-name">Github</span></a>
                        </li>
                        <li><a href="https://linkedin.com/in/orlandovillasenor" target="_blank" class="btn btn-default btn-lg"><i class="fa fa-linkedin fa-fw"></i> <span class="network-name">Linkedin</span></a>
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

    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>

</body>

</html>