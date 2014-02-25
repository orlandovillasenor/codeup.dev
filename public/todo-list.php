
<!DOCTYPE html>
<html>
	<head>
		<title>TODO List</title>
	</head>
	<body>
		<h1>My List:</h1>
		<ul>
			<?php 
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
				    return fwrite($handle, $string);
				    fclose($handle);
				}
				
				if (filesize('data/todo-list.txt') > 0) {
					$items = open_file('data/todo-list.txt');
				} else {
					$items = array();
				}
		
				if (isset($_POST['item']) && !empty($_POST)) {
						
						$new_item = $_POST['item'];
						$new_item = ucwords($new_item);
						array_push($items, $new_item);					
						save_file("data/todo-list.txt", $items);
					}
				
				if (isset($_GET['remove'])) {
						$itemsId = $_GET['remove'];
						unset($items[$itemsId]);
						save_file("data/todo-list.txt", $items);
						header("Location: todo-list.php");
						exit(0);
					}
				
				foreach ($items as $key => $item) {
					echo "<li>$item <a href=\"?remove=$key\">Remove Item</a> </li>";
				}
			
				//var_dump($_POST);
			?>
		</ul>
		
		<!-- <h3>Add Item to List</h3> -->
			<form method="POST" action="">
				<p>
				<label for="item">Enter Item:</label>
				<input id="item" name="item" type="text" autofocus>
				</p>
				<p>
					<button type="submit">Add to list</button>
				</p>
			</form>
			


	</body>
</html>
				

				

			



			
			
			    
