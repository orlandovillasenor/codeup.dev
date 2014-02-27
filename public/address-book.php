<?php

class AddressDataStore {
	public $filename = '';

	function read_address_book(){
		$contents = [];
	$handle = fopen($this->filename, 'r');
	while (($data = fgetcsv($handle)) !== FALSE) {
		$contents[] = $data;
	}
	fclose($handle);
	return $contents;
	}

	function write_address_book($rows){
		$handle = fopen($this->filename, 'w');
	foreach ($rows as $row) {
		fputcsv($handle, $row);
	}
	fclose($handle);
	}
}

$work_book = new AddressDataStore();
$work_book->filename = "data/address-book.csv";
$address_book = $work_book->read_address_book();

$filename = "data/address-book.csv";
$missing_required = FALSE;
$entry = []; 

// function read_CSV($filename) {
// 	$contents = [];
// 	$handle = fopen($filename, 'r');
// 	while (($data = fgetcsv($handle)) !== FALSE) {
// 		$contents[] = $data;
// 	}
// 	fclose($handle);
// 	return $contents;
// }

// function save_CSV($filename, $rows){
//     $handle = fopen($filename, 'w');
// 	foreach ($rows as $row) {
// 		fputcsv($handle, $row);
// 	}
// 	fclose($handle);
// }

if (isset($_POST['name']) && !empty($_POST['name'])) {
	$name = $_POST['name'];
	$name = ucwords($name);					
	
} else if (empty($_POST['name'])){
	$name = '';
	$missing_required = TRUE;
	
}
if (isset($_POST['address']) && !empty($_POST['address'])) {
	$address = $_POST['address'];
	$address = ucwords($address);
							
} else if (empty($_POST['address'])){
	$address = '';
	$missing_required = TRUE;
}
if (isset($_POST['city']) && !empty($_POST['city'])) {
	$city = $_POST['city'];
	$city = ucwords($city);
	array_push($entry, $city);					
	
} else if (empty($_POST['city'])){
	$city = '';
	$missing_required = TRUE;
}
if (isset($_POST['state']) && !empty($_POST['state'])) {
	$state = $_POST['state'];
	$state = strtoupper($state);				
	
} else if (empty($_POST['state'])){
	$state = '';
	$missing_required = TRUE;
}
if (isset($_POST['zip']) && !empty($_POST['zip'])) {
	$zip = $_POST['zip'];
	$zip = ucwords($zip);					
	
} else if (empty($_POST['zip'])){
	$zip = '';
	$missing_required = TRUE;
}
if (isset($_POST['phone']) && !empty($_POST['phone'])) {
	$phone = $_POST['phone'];
	$phone = ucwords($phone);					
}

if ($missing_required == FALSE) {
	$entry = [$name, $address, $city, $state, $zip];
}
if (isset($_POST['phone']) && !empty($_POST['phone']) && !empty($entry)) {
	$phone = $_POST['phone'];
	$phone = ucwords($phone);
	array_push($entry, $phone);					
}

if ($missing_required == FALSE) {
	array_push($address_book, $entry);
	$work_book->write_address_book($address_book);
}

if (isset($_GET['remove'])) {
	unset($address_book[$_GET['remove']]);
	$work_book->write_address_book($address_book);
	header("Location: address-book.php");
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
<? if ($missing_required == TRUE) : ?>
		<p><?= "Must submit all required fields"; ?></p>
	<? endif; ?>
<h2>Add Address:</h2>
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
</body>
</html>