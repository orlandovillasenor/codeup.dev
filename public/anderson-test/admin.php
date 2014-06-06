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

if (isset($_POST['download'])) {
	
	header('Content-type: text/csv');
	header('Content-Disposition: attachment; filename="users.csv');
	readfile('data/users.csv');
	exit(0);
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Admin Page</title>
	</head>
	<body>
		<h2>Users</h2>
        <table>
        	<thead>
        		<th>First Name</th>
        		<th>Last Name</th>
        		<th>Email</th>
        		<th>Company</th>
        		<th>Phone #</th>
        	</thead>
        	<tbody>
	            <? foreach ($users_data as $key => $rows) : ?>
	                <tr>
	                    <? foreach ($rows as $row) : ?>
	                        <td><?= htmlspecialchars(strip_tags($row)); ?></td>
	                    <? endforeach; ?>
	                </tr>
	             <? endforeach; ?>
            </tbody>   
        </table>
        <h3>Download User List As .csv</h3>
        <form method="post" action"">
		<p>
	        <button type="submit" name="download">Download</button>
	    </p>
	    </form>
	</body>
</html>