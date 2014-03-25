<?php 
require_once('classes/address-data-store.php');

class InvalidInputException extends Exception {}

$filename = "data/address-book.csv";
$work_book = new AddressDataStore($filename);
$address_book = $work_book->read();

$missing_required = FALSE;
$invalid_file_type = FALSE;
$entry = []; 
$error_message = '';

try {
if (!empty($_POST)) {
	$name = ucwords($_POST['name']);
	$address = ucwords($_POST['address']);
	$city = ucwords($_POST['city']);
	$state = strtoupper($_POST['state']);
	$zip = ucwords($_POST['zip']);
	$entry = ['name' => $name, 'address' => $address, 'city' => $city, 'state' => $state, 'zip' => $zip];	
	foreach ($entry as $key => $value){
		if (empty($value)) {
			$missing_required = TRUE;
			$value = '';
		} else if (strlen($value) > 125) {
			throw new InvalidInputException ("$key must be less than 125 characters");
		} 		
	}
	if ($missing_required == FALSE) {
		array_push($address_book, $entry);
		$work_book->write($address_book);
	}
}
} catch (InvalidInputException $e) {
	$missing_required = TRUE;
	$error_message = $e->getMessage();
}

if (count($_FILES) > 0 && $_FILES['uploaded_file']['type'] != 'text/csv') {
	$invalid_file_type = TRUE;
} else if (count($_FILES) > 0 && $_FILES['uploaded_file']['error'] == 0 && $_FILES['uploaded_file']['type'] == 'text/csv') {
    // Set the destination directory for uploads
    $upload_dir = '/vagrant/sites/codeup.dev/public/uploads/';
    // Grab the filename from the uploaded file by using basename
    $base_filename = basename($_FILES['uploaded_file']['name']);
    // Create the saved filename using the file's original name and our upload directory
    $saved_filename = $upload_dir . $base_filename;
    // Move the file from the temp location to our uploads directory
    move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $saved_filename);
    //$uploaded_file = read_CSV($saved_filename);
    $upload = new AddressDataStore($saved_filename);
    $uploaded_file = $upload->read($saved_filename);
    foreach ($uploaded_file as $value) {
     		//$value = ucwords($value);
     		array_push($address_book, $value);
 	       	//$work_book->write_address_book($address_book);		
     	}
    $work_book->write($address_book);
}


if (isset($_GET['remove'])) {
	unset($address_book[$_GET['remove']]);
	$work_book->write($address_book);
	header("Location: addy.php#addy");
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

    <title>Address Book App</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom Google Web Font -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <!-- Add custom CSS here -->
    <link href="css/landing-page.css" rel="stylesheet">

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
                    <li><a href="#addy">My Address Book</a>
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
                        <h1>Address Book</h1>
                        <h3>A Basic Address Book App</h3>
                        <hr class="intro-divider">
                        <ul class="list-inline intro-social-buttons">
                            <!-- <li><a href="https://twitter.com/SBootstrap" class="btn btn-default btn-lg"><i class="fa fa-twitter fa-fw"></i> <span class="network-name">Twitter</span></a>
                            </li> -->
                            <li><a href="#addy" class="btn btn-default btn-lg"><i class="fa fa-book"></i> <span class="network-name">Get Started</span></a>
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

    <div class="content-section-a" id="addy">

        <div class="container">

            <div class="row">
                <div class="col-lg-5 col-sm-6">
                    <hr class="section-heading-spacer">
                    <div class="clearfix"></div>
                    <h2 class="section-heading">Address Book</h2>
                    <table>
                        <? foreach ($address_book as $key => $rows) : ?>
                            <tr>
                                <? foreach ($rows as $row) : ?>
                                    <td><?= htmlspecialchars(strip_tags($row)); ?></td>
                                <? endforeach; ?>
                                    <td><a href="?remove=<?= $key; ?>" >remove</a></td>
                            </tr>
                                
                        <? endforeach; ?>   
                    </table>
                        
                    
                </div>
                
                <div class="col-lg-5 col-lg-offset-2 col-sm-6">
                  <hr class="section-heading-spacer">
                    <div class="clearfix"></div>
                    <h2 class="section-heading">Add Address</h2>  
					<? if (!empty($error_message)) : ?>
	<p><?= "$error_message"; ?></p>
<? endif; ?>

<p>* required field</p>
<form method="POST" action="">
		<p>
			<label for="name">Name: </label>
			<input id="name" name="name" type="text" value="<?php echo isset($_POST['name'])? $_POST['name'] : "" ?>"> * <? if(empty($name)) {echo "Name Required";} ?>
		</p>
		<p>
			<label for="address">Address: </label>
			<input id="address" name="address" type="text" value="<?php echo isset($_POST['address'])? $_POST['address'] : "" ?>"> * <? if(empty($address)) {echo "Address Required";} ?>
		</p>
		<p>
			<label for="city">City: </label>
			<input id="city" name="city" type="text" value="<?php echo isset($_POST['city'])? $_POST['city'] : "" ?>"> * <? if(empty($city)) {echo "City Required";} ?>
		</p>
		<p>
			<label for="state">State: </label>
			<input id="state" name="state" type="text" value="<?php echo isset($_POST['state'])? $_POST['state'] : "" ?>"> * <? if(empty($state)) {echo "State Required";} ?>
		</p>
		<p>
			<label for="zip">Zip: </label>
			<input id="zip" name="zip" type="text" value="<?php echo isset($_POST['zip'])? $_POST['zip'] : "" ?>"> * <? if(empty($zip)) {echo "Zip Code Required";} ?>
		</p>
		<p>
			<label for="phone">Phone: </label>
			<input id="phone" name="phone" type="text" value="<?php echo isset($_POST['phone'])? $_POST['phone'] : "" ?>">
		</p>
		<p>
			<button type="submit">Submit</button> 
		</p>
	</form>
	<? if ($invalid_file_type == TRUE) : ?>
		<p><?= "Uploaded file must be a .csv file. Please try again."; ?></p>
	<? endif; ?>
	<h2>Upload File:</h2>

	<form method="POST" enctype="multipart/form-data" action="">
		<p>
			<label for="uploaded_file">Add .CSV file to address book:</label>
			<input id="uploaded_file" name="uploaded_file" type="file">
		</p>
		<p>
			<button type="submit">Upload</button>
			<!--
			<input id="replace_file" name="replace_file" type="checkbox">
			<label for="replace_file">Replace existing list</label>
			!-->
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
                        <li><a href="#addy">Address Book</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li><a href="#services">Services</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li><a href="#contact">Contact</a>
                        </li>
                    </ul>
                    <p class="copyright text-muted small">Copyright &copy; <a href="http://codeup.dev/my-site/index.html">Orlando Villaseñor</a>  2013</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>

</body>

</html>