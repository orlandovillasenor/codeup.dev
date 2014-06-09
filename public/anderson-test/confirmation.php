<?php
require_once('filestore.php');

class UserDataStore extends Filestore {

    public function __construct($filename = '') 
    {
        $filename = strtolower($filename);
        parent::__construct($filename);       
    }
}

$filename = "data/users.csv";
$users = new UserDataStore($filename);
$users_data = $users->read();
$prospects = [];

if (isset($_POST['submit'])) {
 	
 	$firstname = $_POST['firstname'];
 	$lastname = $_POST['lastname'];
 	$email = $_POST['email'];
 	$company = $_POST['company'];
 	$phone = $_POST['phone'];
	$to = 'orlandovillasenor@me.com';
	$subject = "New Contact Info From Online Form";
	
	$message = "A prospect has sent the following contact info.\r\n
	Full Name: $firstname\r\n
	Last Name: $lastname\r\n
	Email Address: $email\r\n
	Company: $company\r\n
	Phone: $phone";
	mail($to, $subject, $message);
	// mail("orlandovillasenor@me.com", "Contact Info Submitted", "Thank You For submitting your contact info for the purpose of downloading our 2013 Economic Development Report.", "From: orlandovillasenor@me.com");
	$prospects = [$firstname, $lastname, $email, $company, $phone];
	array_push($users_data, $prospects);
	$users->write($users_data);
 }

if (isset($_POST['download'])) {
	
	header('Content-type: application/pdf');
	header('Content-Disposition: attachment; filename="tip-sheet-white-Space-2014.pdf"');
	readfile('data/tip-sheet-white-Space-2014.pdf');
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Confirmation Page</title>
	</head>
	<body>
		<p>Thanks for sharing your information. You can download the Economic
		Development Intelligence Report here.</p>
		<form method="post" action"">
		<p>
	        <button type="submit" name="download">Download</button>
	    </p>
	    </form>
	</body>
</html>