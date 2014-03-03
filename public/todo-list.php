<?php
require_once('classes/filestore.php');

//$cleared_items = array();
$todo_list = new Filestore ('data/todo-list.txt');

if (filesize('data/todo-list.txt') > 0) {
	//$items = $todo_list->read_lines();
	$items = $todo_list->read();
} else {
	$items = array();
}

if (isset($_POST['item']) && strlen($_POST['item']) > 240) {
	throw new Exception ("item entered is greater than 240 characters");
}

if (isset($_POST['item']) && !empty($_POST['item'])) {
	$new_item = $_POST['item'];
	$new_item = ucwords($new_item);
	array_push($items, $new_item);					
	//$todo_list->write_lines($items);
	$todo_list->write($items);
} else if (isset($_POST['item']) && empty($_POST['item'])) {
 		throw new Exception("item entered is empty.");
} 


		
$invalid_file_type = FALSE;

if (count($_FILES) > 0 && $_FILES['uploaded_file']['type'] != 'text/plain') {
	$invalid_file_type = TRUE;
} else if (count($_FILES) > 0 && $_FILES['uploaded_file']['error'] == 0 && $_FILES['uploaded_file']['type'] == 'text/plain') {
    // Set the destination directory for uploads
    $upload_dir = '/vagrant/sites/codeup.dev/public/uploads/';
    // Grab the filename from the uploaded file by using basename
    $uploaded_filename = basename($_FILES['uploaded_file']['name']);
    // Create the saved filename using the file's original name and our upload directory
    $saved_filename = $upload_dir . $uploaded_filename;
    // Move the file from the temp location to our uploads directory
    move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $saved_filename);
    $upload = new Filestore($saved_filename);
    $uploaded_file = $upload->read($saved_filename);
    //$uploaded_file = $todo_list->read($saved_filename); 	
	if (isset($_POST['replace_file'])) {
    	unset($items);
    	$items = $uploaded_file;
    	$todo_list->write($items);
    } else {
    	foreach ($uploaded_file as $value) {
    		//$value = ucwords($value);
    		array_push($items, $value);
 	        $todo_list->write($items);		
    	}
    }
}

if (isset($_GET['remove'])) {
	$itemsId = $_GET['remove'];
	unset($items[$itemsId]);
	$todo_list->write($items);
	header("Location: todo-list.php");
	exit(0);
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>TODO List</title>
</head>
<body>
	<h1>My List:</h1>
	<? if (count($items) > 0) : ?>
	<ul>
		<? foreach ($items as $key => $item) : ?>
			<li><?= htmlspecialchars(strip_tags($item)); ?> <a href="?remove=<?= $key; ?>" >remove</a></li>
		<? endforeach; ?>
	</ul>
	<? else : ?>
		<p>Your list is empty</p>
	<? endif; ?>
	<h2>Add Item:</h2>

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
		<p><?= "An invalid file type was uploaded"; ?></p>
	<? endif; ?>

	<h2>Upload File:</h2>

	<form method="POST" enctype="multipart/form-data" action="">
		<p>
			<label for="uploaded_file">File to add to list</label>
			<input id="uploaded_file" name="uploaded_file" type="file">
		</p>
		<p>
			<button type="submit">Upload</button>
			<input id="replace_file" name="replace_file" type="checkbox">
			<label for="replace_file">Replace existing list</label>
			
		</p>
	</form>

</body>
</html>
				

				

			



			
			
			    
