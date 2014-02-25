<?php
//var_dump($_FILES);
function open_file($filename){
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    $contents_array = explode("\n", $contents);
    return $contents_array;
    fclose($handle);
}
function save_file($filename, $array){
    $handle = fopen($filename, 'w');
    $string = implode("\n", $array);
    fwrite($handle, $string);
    fclose($handle);
}

// if (filesize('data/todo-list.txt') > 0) {
// 	$items = open_file('data/todo-list.txt');
// } else {
// 	$items = array();
// }
// ternary operator for if/else above
$items = (filesize('data/todo-list.txt') > 0) ? open_file('data/todo-list.txt') : array();


if (isset($_POST['item']) && !empty($_POST['item'])) {
		
	$new_item = $_POST['item'];
	$new_item = ucwords($new_item);
	array_push($items, $new_item);					
	save_file("data/todo-list.txt", $items);
}
$invalid_file_type = FALSE;

if (count($_FILES) > 0 && $_FILES['uploaded_file']['type'] != 'text/plain') {
	$invalid_file_type = TRUE;
} else if (count($_FILES) > 0 && $_FILES['uploaded_file']['error'] == 0 && $_FILES['uploaded_file']['type'] == 'text/plain') {
    // Set the destination directory for uploads
    $upload_dir = '/vagrant/sites/codeup.dev/public/uploads/';
    // Grab the filename from the uploaded file by using basename
    $filename = basename($_FILES['uploaded_file']['name']);
    // Create the saved filename using the file's original name and our upload directory
    $saved_filename = $upload_dir . $filename;
    // Move the file from the temp location to our uploads directory
    move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $saved_filename);
    $file_push = open_file($saved_filename);
    foreach ($file_push as $value) {
        $value = ucwords($value);
        array_push($items, $value);
        save_file("data/todo-list.txt", $items);
    }
}


if (isset($_GET['remove'])) {
	$itemsId = $_GET['remove'];
	unset($items[$itemsId]);
	save_file("data/todo-list.txt", $items);
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
<ul>

	<? foreach ($items as $key => $item) : ?>
		<li><?= htmlspecialchars(strip_tags($item)); ?> <a href="?remove=<?=$key;?>" >remove</a></li>
	<? endforeach; ?>

</ul>
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
	</p>
</form>

</body>
</html>
				

				

			



			
			
			    
