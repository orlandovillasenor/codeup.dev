<?php 

echo "<p>POST:</p>";
var_dump($_POST);

echo "<p>GET:</p>";
var_dump($_GET);

?>


<!DOCTYPE html>
<html>
	<head>
		<title>My first HTML form</title>
	</head>
	<body>
		<h2>User Login:</h2>
		<form method="POST" action="">
		    <p>
		        <label for="username">Username:</label>
		        <input id="username" name="username" placeholder="Enter here" type="text">
		    </p>
		    <p>
		        <label for="password">Password:</label>
		        <input id="password" name="password" placeholder="min 6 characters" type="password">
		    </p>
		    <p>
		        <!-- <input name="submit" type="submit" value="Login"> -->
		        <button type="submit">Login</button>
		    </p>
		</form>
		<h2>Compose an Email:</h2>
		<form method="POST" action="">
			<p>
				<label for="to">To:</label>
				<input id="to" name="to" type="text">
			</p>
			<p>
				<label for="from">From:</label>
				<input id="from" name="from" type="text">
			</p>
			<p>
				<label for="subject">Subject:</label>
				<input id="subject" name="subject" type="text">
			</p>
			<p>
				<label for="body">Email:</label>
				<textarea name="body" id="body" cols="30" rows="10"></textarea>
			</p>
			<p>
				<label for="save">
				<input id="save" name"save" type="checkbox" checked>
				Save to Sent Folder?
				</label>
			</p>
			<p>
				<input name="send" type="submit" value="Send">
			</p>
		</form>
		<h2>Multiple Choice:</h2>
			<form method="GET" action="">
				<p>What is the best school in San Antonio?</p>
				<label for="q1a">
					<input id="q1a" name="q1" value="sac" type="radio">
					SAC
				</label>
				<label for="q1b">
					<input id="q1b" name="q1" value="utsa"type="radio">
					UTSA
				</label>
				<label for="q1c">
					<input id="q1c" name="q1" value="codeup" type="radio" checked>
					Codeup
				</label>
				
				<p>What is your favorite programming language?</p>
				<label for="q2a">
					<input id="q1a" name="q2" value="php" type="radio" checked>
					PHP
				</label>
				<label for="q2b">
					<input id="q1b" name="q2" value="JavaScript" type="radio">
					JavaScript
				</label>
				<label for="q2c">
					<input id="q1c" name="q2" value="Ruby" type="radio">
					Ruby
				</label>
			
				<p>What operating systems have you used?</p>
				<label for="os1"><input type="checkbox" id="os1" name="os[]" value="linux"> Linux</label>
				<label for="os2"><input type="checkbox" id="os2" name="os[]" value="osx"> OS X</label>
				<label for="os3"><input type="checkbox" id="os3" name="os[]" value="windows"> Windows</label>
				<p>What kind of computer do you have?</p>
				<label for="comp"></label>
				<select id="comp" name="comp[]" multiple>
				    <option value="dell">Dell</option>
				    <option value="mac">Mac</option>
				    <option value="chromebook">Chromebook</option>
				</select>
					
				<p>
					<input type="submit">
				</p>	
			</form>
			<h2>Select Testing</h2>
			<form method="GET" action="">
				<label for="coding">Do You like coding?</label>
					<select name="coding" id="coding">
						<option>Yes</option>
						<option selected>No</option>
					</select>
				<p>
					<input type="submit">
				</p>	
			</form>

	</body>
</html>


















