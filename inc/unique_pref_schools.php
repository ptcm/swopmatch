<?php
	
	if (isset($_POST["preferred_schools1"])){
		$preferred_schools1 = $_POST["preferred_schools1"];
	}
	
	if (isset($_POST["preferred_schools2"])){
		$preferred_schools2 = $_POST["preferred_schools2"];
	}
	
	if (isset($_POST["preferred_schools3"])){
		$preferred_schools3 = $_POST["preferred_schools3"];
	}
	
	if (isset($_POST["preferred_schools4"])){
		$preferred_schools4 = $_POST["preferred_schools4"];
	}
	
	if (isset($_POST["preferred_schools5"])){
		$preferred_schools5 = $_POST["preferred_schools5"];
	}
	
	if (isset($_POST["preferred_schools6"])){
		$preferred_schools6 = $_POST["preferred_schools6"];
	}
	
	if (isset($_POST["preferred_schools7"])){
		$preferred_schools7 = $_POST["preferred_schools7"];
	}
	
	if (isset($_POST["preferred_schools8"])){
		$preferred_schools8 = $_POST["preferred_schools8"];
	}
	
	if (isset($_POST["preferred_schools9"])){
		$preferred_schools9 = $_POST["preferred_schools9"];
	}
	
	if (isset($_POST["preferred_schools10"])){
		$preferred_schools10 = $_POST["preferred_schools10"];
	}
		
	
	
				$unique_pref_schools = [];
				if (isset($_POST["preferred_schools1"])){
					$unique_pref_schools[] = $preferred_schools1;
				}
				if (isset($_POST["preferred_schools2"])){
					$unique_pref_schools[] = $preferred_schools2;
				}
				if (isset($_POST["preferred_schools3"])){
					$unique_pref_schools[] = $preferred_schools3;
				}
				if (isset($_POST["preferred_schools4"])){
					$unique_pref_schools[] = $preferred_schools4;
				}
				if (isset($_POST["preferred_schools5"])){
					$unique_pref_schools[] = $preferred_schools5;
				}
				if (isset($_POST["preferred_schools6"])){
					$unique_pref_schools[] = $preferred_schools6;
				}
				if (isset($_POST["preferred_schools7"])){
					$unique_pref_schools[] = $preferred_schools7;
				}
				if (isset($_POST["preferred_schools8"])){
					$unique_pref_schools[] = $preferred_schools8;
				}
				if (isset($_POST["preferred_schools9"])){
					$unique_pref_schools[] = $preferred_schools9;
				}
				if (isset($_POST["preferred_schools10"])){
					$unique_pref_schools[] = $preferred_schools10;
				}
						
				if(count($unique_pref_schools) != count(array_unique($unique_pref_schools))){
					  $error_message = 'Invalid form input: Preferred Schools options must be all unique! Please recheck your preferred schools options';
					}
		
			
			
	
	?>