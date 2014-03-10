<?php

require_once('classes/filestore.php');

class AddressDataStore extends Filestore {
	
    

    public function __construct($filename = '') 
    {
        $filename = strtolower($filename);
        parent::__construct($filename);
        
    }
	
	// public function read_address_book(){
	// 	$contents = [];
	// 	$handle = fopen($this->filename, 'r');
	// 	while (($data = fgetcsv($handle)) !== FALSE) {
	// 		$contents[] = $data;
	// 	}
	// 	fclose($handle);
	// 	return $contents;
	// }

	// public function write_address_book($rows){
	// 	$handle = fopen($this->filename, 'w');
	// 	foreach ($rows as $row) {
	// 		fputcsv($handle, $row);
	// 	}
	// 	fclose($handle);
	// }
}