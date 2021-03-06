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
	header("Location: abtest.php");
	exit(0);
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Address Book</title>
	</head>
<body>
	<h1>Address Book</h1>
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
<h2>Add Address:</h2>

<? if (!empty($error_message)) : ?>
	<p><?= "$error_message"; ?></p>
<? endif; ?>

<p>* required field</p>
<form method="POST" action="">
		<p>
			<label for="name">Name: </label>
			<input id="name" name="name" type="text" value="<?php echo isset($_POST['name'])? $_POST['name'] : "" ?>" autofocus> * <? if(empty($name)) {echo "Name Required";} ?>
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
			<label for="uploaded_file">Add file to address book</label>
			<input id="uploaded_file" name="uploaded_file" type="file">
		</p>
		<p>
			<button type="submit">Upload</button>			
		</p>
	</form>	
</body>
</html>
