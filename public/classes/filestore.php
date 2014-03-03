<?php

class Filestore {

    public $filename = '';
    public $is_csv = FALSE;

    public function __construct($filename = '') 
    {
        $this->filename = $filename;

        if (substr($filename, -3) == 'csv') {
            $this->is_csv = TRUE;
        }
        // sets $this->filename
    }

    public function read() {
        if ($this->is_csv == TRUE) {
            return $this->read_csv();
        } else {
            return $this->read_lines();
        }
    }

    public function write($array) {
        if ($this->is_csv == TRUE) {
            $this->write_csv($array);
        } else {
            $this->write_lines($array);
        }
    }

    /**
     * Returns array of lines in $this->filename
     */
    private function read_lines()
    {
    	$handle = fopen($this->filename, "r");
	    $contents = fread($handle, filesize($this->filename));
	    $contents = ucwords($contents);
	    $contents_array = explode("\n", $contents);
	    return $contents_array;
	    fclose($handle);
    }

    /**
     * Writes each element in $array to a new line in $this->filename
     */
    private function write_lines($array)
    {
    	$handle = fopen($this->filename, 'w');
	    $string = implode("\n", $array);
	    fwrite($handle, $string);
	    fclose($handle);
    }

    /**
     * Reads contents of csv $this->filename, returns an array
     */
    private function read_csv()
    {
    	$contents = [];
		$handle = fopen($this->filename, 'r');
		while (($data = fgetcsv($handle)) !== FALSE) {
			$contents[] = $data;
		}
		fclose($handle);
		return $contents;
	}

    /**
     * Writes contents of $array to csv $this->filename
     */
    private function write_csv($array)
    {
    	$handle = fopen($this->filename, 'w');
		foreach ($array as $value) {
			fputcsv($handle, $value); 
		}
		fclose($handle);
	}
}