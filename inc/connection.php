<?php
	
	try {
	    $dsn = 'mysql:host=localhost;dbname=swopmatc_sMatch';
	        //$username = 'swopmatc_swopmat';
			//$password = 'swopenter1';
	        $username = 'root';
			$password = '';

			$db = new PDO($dsn, $username, $password);
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			
	}catch (Exception $e){
			echo 'Unable to connect';
            //echo BR;
			echo $e->getMessage();
			exit;
	}
?>