<?php 

echo "<p>POST:</p>";
var_dump($_POST);

echo "<p>GET:</p>";
var_dump($_GET);

?>



<!DOCTYPE html>
<html>
	<head>
		<title>TODO List</title>
	</head>
	<body>
		<h1>TODO List:</h1>
			<ul>
				<li>Wake up</li>
				<li>Work out</li>
				<li>Go to Codeup</li>
				<li>Learn to code</li>
			</ul>
		<h2>Add Item to List:</h2>
			<form method="GET" action="">
				<p>
				<label for="add">Enter Item:</label>
				<input id="add" name="add" type="text">
				</p>
				<p>
					<button type="submit">Add to list</button>
				</p>
			</form>
	</body>
</html>
