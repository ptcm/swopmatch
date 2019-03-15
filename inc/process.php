<?php
include_once ('functions.php');

if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$error_message = "";
	if (isset($_POST["gender"])){
		$gender = $_POST["gender"];
	}
	

	 $userFirstName = trim(filter_input(INPUT_POST, "user_first_name", FILTER_SANITIZE_STRING));
	 $userLastName = trim(filter_input(INPUT_POST, "user_last_name", FILTER_SANITIZE_STRING));
	 $mobileNumber = trim(filter_input(INPUT_POST, "mobile_number", FILTER_SANITIZE_NUMBER_INT));
	 $ecNumber = trim(filter_input(INPUT_POST, "ec_number", FILTER_SANITIZE_STRING));
	 $userEmail = trim(filter_input(INPUT_POST, "user_email", FILTER_SANITIZE_EMAIL));
	 $userPassword = trim($_POST["user_password"]);
	 filter_input(INPUT_GET, "select_by", FILTER_SANITIZE_SPECIAL_CHARS);
	
	


	if (!empty($_POST["level_taught"])){
		$levelTaught = strtoupper($_POST["level_taught"]);
	}

	

	if (isset($_POST["preferred_province"])){
				$prefProvince = $_POST["preferred_province"];
				
				switch ($prefProvince){
					case 'Harare':
						$prefProv_id = 1;
						break;
					case 'Bulawayo':
						$prefProv_id = 2;
						break;					
					case 'Mashonaland East':
						$prefProv_id = 3;
						break;
					case 'Mashonaland Central':
						$prefProv_id = 4;
						break;
					case 'Mashonaland West':
						$prefProv_id = 5;
						break;
					case 'Matebeleland North':
						$prefProv_id = 6;
						break;
					case 'Matebeleland South':
						$prefProv_id = 7;
						break;
					case 'Midlands':
						$prefProv_id = 8;
						break;
					case 'Masvingo':
						$prefProv_id = 9;
						break;
					case 'Manicaland':
						$prefProv_id = 10;
						break;
					}
			}


				

	if (isset($_POST["current_province"])){
				$currentProvince = $_POST["current_province"];
				
				switch ($currentProvince){
					case 'Harare':
						$currProv_id = 1;
						break;
					case 'Bulawayo':
						$currProv_id = 2;
						break;					
					case 'Mashonaland East':
						$currProv_id = 3;
						break;
					case 'Mashonaland Central':
						$currProv_id = 4;
						break;
					case 'Mashonaland West':
						$currProv_id = 5;
						break;
					case 'Matebeleland North':
						$currProv_id = 6;
						break;
					case 'Matebeleland South':
						$currProv_id = 7;
						break;
					case 'Midlands':
						$currProv_id = 8;
						break;
					case 'Masvingo':
						$currProv_id = 9;
						break;
					case 'Manicaland':
						$currProv_id = 10;
						break;
					}
			}

				

		$currentDistrict = $_POST["current_district"];
			
			foreach($districts as $key=>$value){
						if(in_array($currentDistrict,$value)){
							  $currDistr_id = $value['distr_id'];
						}
					}
				
	

		$currentSchool = $_POST["current_school"];
		
			foreach($schools as $key=>$value){
						if(in_array($currentSchool,$value)){
							  $currSch_id = $value['school_id'];
						}
					}
						

	if ($userFirstName == "" || $userLastName == "" || $ecNumber == "" || $mobileNumber == ""){
		
		$error_message = 'Please fill in all your Basic Details!';
		//exit;
	}

	

	if ($_POST["blank"] != ""){
		
		echo "Bad form input";
		exit;
	}


if (isset($_POST["subject1"])){
	$subject1 = $_POST["subject1"];
}
if (isset($_POST["subject2"])){
	$subject2 = $_POST["subject2"];
}
if (isset($_POST["subject3"])){
	$subject3 = $_POST["subject3"];
}
if (isset($_POST["subject4"])){
	$subject4 = $_POST["subject4"];
}
if (isset($_POST["subject5"])){
	$subject5 = $_POST["subject5"];
}
if (isset($_POST["subject6"])){
	$subject6 = $_POST["subject6"];
}
if (isset($_POST["subject7"])){
	$subject7 = $_POST["subject7"];
}
if (isset($_POST["subject8"])){
	$subject8 = $_POST["subject8"];
}
if (isset($_POST["subject9"])){
	$subject9 = $_POST["subject9"];
}
if (isset($_POST["subject10"])){
	$subject10 = $_POST["subject10"];
}
if (isset($_POST["subject11"])){
	$subject11 = $_POST["subject11"];
}
if (isset($_POST["subject12"])){
	$subject12 = $_POST["subject12"];
}
if (isset($_POST["subject13"])){
	$subject13 = $_POST["subject13"];
}
if (isset($_POST["subject14"])){
	$subject14 = $_POST["subject14"];
}
if (isset($_POST["subject15"])){
	$subject15 = $_POST["subject15"];
}
if (isset($_POST["subject16"])){
	$subject16 = $_POST["subject16"];
}
if (isset($_POST["subject17"])){
	$subject17 = $_POST["subject17"];
}
if (isset($_POST["subject18"])){
	$subject18 = $_POST["subject18"];
}
if (isset($_POST["subject19"])){
	$subject19 = $_POST["subject19"];
}
if (isset($_POST["subject20"])){
	$subject20 = $_POST["subject20"];
}
if (isset($_POST["subject21"])){
	$subject21 = $_POST["subject21"];
}
if (isset($_POST["subject22"])){
	$subject22 = $_POST["subject22"];
}
if (isset($_POST["subject23"])){
	$subject23 = $_POST["subject23"];
}
if (isset($_POST["subject24"])){
	$subject24 = $_POST["subject24"];
}
if (isset($_POST["subject25"])){
	$subject25 = $_POST["subject25"];
}
if (isset($_POST["subject26"])){
	$subject26 = $_POST["subject26"];
}
if (isset($_POST["subject27"])){
	$subject27 = $_POST["subject27"];
}
if (isset($_POST["subject28"])){
	$subject28 = $_POST["subject28"];
}
if (isset($_POST["subject29"])){
	$subject29 = $_POST["subject29"];
}
if (isset($_POST["subject30"])){
	$subject30 = $_POST["subject30"];
}
if (isset($_POST["subject31"])){
	$subject31 = $_POST["subject31"];
}
if (isset($_POST["subject32"])){
	$subject32 = $_POST["subject32"];
}
if (isset($_POST["subject33"])){
	$subject33 = $_POST["subject33"];
}


	if (isset($_POST["preferred_district1"])){
		$prefDistrict1 = $_POST["preferred_district1"];
		
			foreach($districts as $key=>$value){
					if(in_array($prefDistrict1,$value)){
						  $prefDistr1_id = $value['distr_id'];
					}
				}
			}
		

	 if (isset($_POST["preferred_district2"]) && !empty($prefDistrict1)){
		$prefDistrict2 = $_POST["preferred_district2"];
				
			foreach($districts as $key=>$value){
				if(in_array($prefDistrict2,$value)){
					  $prefDistr2_id = $value['distr_id'];
				}
			}
		}elseif (isset($_POST["preferred_district2"]) && empty($prefDistrict1)){
			$error_message = 'You can not choose District second option without choosing a first option';
			//exit;
		}
	
			

			
			if (isset($_POST["preferred_town"])){
				$prefTown = $_POST["preferred_town"];
				
					foreach($towns as $key=>$value){
						if(in_array($prefTown,$value)){
							  $prefTown_id = $value['town_id'];
						}
					}
				}	
			
	
				
			if (isset($_POST["preferred_location1"])){
				$prefLocations1 = $_POST["preferred_location1"];
				
				foreach($locations as $key=>$value){
						if(in_array($prefLocations1,$value)){
							  $prefLoc1_id = $value['loc_id'];
						}
					}
				}
			
	
				
			if (isset($_POST["preferred_location2"]) && !empty($prefLocations1)){
				$prefLocations2 = $_POST["preferred_location2"];
		
			foreach($locations as $key=>$value){
				if(in_array($prefLocations2,$value)){
					  $prefLoc2_id = $value['loc_id'];
					}
				}
			}elseif (isset($_POST["preferred_location2"]) && empty($prefLocations1)){
				$error_message = 'You can not choose Location second option without choosing a first option';
				//exit;
			}
		
	
				
			if (isset($_POST["preferred_location3"]) && !empty($prefLocations2)){
						$prefLocations3 = $_POST["preferred_location3"];
				
					foreach($locations as $key=>$value){
						if(in_array($prefLocations3,$value)){
							  $prefLoc3_id = $value['loc_id'];
							}
						}
					}elseif (isset($_POST["preferred_location3"]) && empty($prefLocations2)){
						$error_message = 'You can not choose Location third option without choosing a second option';
						//exit;
					}
				

				
			if (isset($_POST["preferred_schools1"])){
				$prefSchools1 = $_POST["preferred_schools1"];
				
				foreach($schools as $key=>$value){
						if(in_array($prefSchools1,$value)){
							  $prefSchool1_id = $value['school_id'];
						}
					}
				}
			

				
			if (isset($_POST["preferred_schools2"]) && !empty($prefSchools1)){
				$prefSchools2 = $_POST["preferred_schools2"];
				
				foreach($schools as $key=>$value){
					if(in_array($prefSchools2,$value)){
						  $prefSchool2_id = $value['school_id'];
					}
				}
			}elseif(isset($_POST["preferred_schools2"]) && empty($prefSchools1)){
				$error_message = 'You can not choose Schools second option without choosing a first option';
			}	
		

		
			if (isset($_POST["preferred_schools3"]) && !empty($prefSchools2)){
				$prefSchools3 = $_POST["preferred_schools3"];
				
				foreach($schools as $key=>$value){
					if(in_array($prefSchools3,$value)){
						  $prefSchool3_id = $value['school_id'];
					}
				}
			}elseif (isset($_POST["preferred_schools3"]) && empty($prefSchools2)){
				$error_message = 'You can not choose Schools third option without choosing a second option';
			}
		
			
					
			if (isset($_POST["preferred_schools4"]) && !empty($prefSchools3)){
				$prefSchools4 = $_POST["preferred_schools4"];
				
				foreach($schools as $key=>$value){
					if(in_array($prefSchools4,$value)){
						  $prefSchool4_id = $value['school_id'];
					}
				}
			}elseif (isset($_POST["preferred_schools4"]) && empty($prefSchools3)){
				$error_message = 'You can not choose Schools fourth option without choosing a third option';
			}
		
	
				
			if (isset($_POST["preferred_schools5"]) && !empty($prefSchools4)){
				$prefSchools5 = $_POST["preferred_schools5"];
				
				foreach($schools as $key=>$value){
					if(in_array($prefSchools5,$value)){
						  $prefSchool5_id = $value['school_id'];
					}
				}
			}elseif (isset($_POST["preferred_schools5"]) && empty($prefSchools4)){
				$error_message = 'You can not choose Schools fifth option without choosing a fourth option';
			}
		
	
					
			if (isset($_POST["preferred_schools6"]) && !empty($prefSchools5)){
				$prefSchools6 = $_POST["preferred_schools6"];
				
				foreach($schools as $key=>$value){
					if(in_array($prefSchools6,$value)){
						  $prefSchool6_id = $value['school_id'];
					}
				}
			}elseif (isset($_POST["preferred_schools6"]) && empty($prefSchools5)){
				$error_message = 'You can not choose Schools sixth option without choosing a fifth option';
			}
		
	
				
			if (isset($_POST["preferred_schools7"]) && !empty($prefSchools6)){
				$prefSchools7 = $_POST["preferred_schools7"];
				
				foreach($schools as $key=>$value){
					if(in_array($prefSchools7,$value)){
						  $prefSchool7_id = $value['school_id'];
					}
				}
			}elseif (isset($_POST["preferred_schools7"]) && empty($prefSchools6)){
				$error_message = 'You can not choose Schools seventh option without choosing a sixth option';
			}
		
	
				
			if (isset($_POST["preferred_schools8"]) && !empty($prefSchools7)){
				$prefSchools8 = $_POST["preferred_schools8"];
				
				foreach($schools as $key=>$value){
					if(in_array($prefSchools8,$value)){
						  $prefSchool8_id = $value['school_id'];
					}
				}
			}elseif (isset($_POST["preferred_schools8"]) && empty($prefSchools7)){
				$error_message = 'You can not choose Schools eighth option without choosing a seventh option';
			}
		
	
				
			if (isset($_POST["preferred_schools9"]) && !empty($prefSchools8)){
				$prefSchools9 = $_POST["preferred_schools9"];
				
				foreach($schools as $key=>$value){
					if(in_array($prefSchools9,$value)){
						  $prefSchool9_id = $value['school_id'];
					}
				}
			}elseif (isset($_POST["preferred_schools9"]) && empty($prefSchools8)){
				$error_message = 'You can not choose Schools ninth option without choosing a eighth option';
			}
		
	
				
			if (isset($_POST["preferred_schools10"]) && !empty($prefSchools9)){
				$prefSchools10 = $_POST["preferred_schools10"];
				
				foreach($schools as $key=>$value){
					if(in_array($prefSchools10,$value)){
						  $prefSchool10_id = $value['school_id'];
					}
				}
			}elseif (isset($_POST["preferred_schools10"]) && empty($prefSchools9)){
				$error_message = 'You can not choose Schools tenth option without choosing a ninth option';
			}
		

		
		$dateCreated = date('d-m-Y H:i:s');
		$status = "OPEN";
		$dateMatched = "";

				create_client($ecNumber, 
						$userFirstName, 
						$userLastName, 
						$gender, 
						$mobileNumber, 
						$userEmail, 
						$userPassword, 
						$levelTaught, 
						$dateCreated, 
						$status, 
						$dateMatched);
						
	client_curr_sch($ecNumber, $currSch_id, $currDistr_id, $currProv_id, $levelTaught);


if (isset($_POST["preferred_province"]) && (($_POST["level_taught"] == "High School - ZJC") || 
											($_POST["level_taught"] == "High School - O Level")|| 
											($_POST["level_taught"] == "High School - A Level"))){
		include('selected_subs.php');
		client_pref_prov($prefProv_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if(!empty($_POST["preferred_province"])){
		client_pref_prov($prefProv_id, $ecNumber, $levelTaught);
	}

if (isset($_POST["preferred_town"]) && (($_POST["level_taught"] == "High School - ZJC") || 
										($_POST["level_taught"] == "High School - O Level")|| 
										($_POST["level_taught"] == "High School - A Level"))){
		include('selected_subs.php');
		client_pref_town($prefTown_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if(!empty($_POST["preferred_town"])){
		client_pref_town($prefTown_id, $ecNumber, $levelTaught);
	}

if (isset($_POST["preferred_district1"]) && (($_POST["level_taught"] == "High School - ZJC") || 
											($_POST["level_taught"] == "High School - O Level")|| 
											($_POST["level_taught"] == "High School - A Level"))){
		include('selected_subs.php');
		client_pref_distr1($prefDistr1_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if (!empty($_POST["preferred_district1"])){
		client_pref_distr1($prefDistr1_id, $ecNumber, $levelTaught);
	}

if (isset($_POST["preferred_district2"]) && (($_POST["level_taught"] == "High School - ZJC") || 
											($_POST["level_taught"] == "High School - O Level")|| 
											($_POST["level_taught"] == "High School - A Level"))){
		include('selected_subs.php');
		client_pref_distr2($prefDistr2_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if (!empty($_POST["preferred_district2"]) && !empty($_POST["preferred_district1"])){
		client_pref_distr2($prefDistr2_id, $ecNumber, $levelTaught);
	}elseif (!empty($_POST["preferred_district2"]) && empty($_POST["preferred_district1"])){
		$error_message = 'Invalid form input: You cannot select option 2 before option 1';
	}
if (isset($_POST["preferred_location1"]) && (($_POST["level_taught"] == "High School - ZJC") || 
											($_POST["level_taught"] == "High School - O Level")|| 
											($_POST["level_taught"] == "High School - A Level"))){
		include('selected_subs.php');
		client_pref_loc1($prefLoc1_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if (!empty($_POST["preferred_location1"])){
		client_pref_loc1($prefLoc1_id, $ecNumber, $levelTaught);
	}

if (isset($_POST["preferred_location2"]) && (($_POST["level_taught"] == "High School - ZJC") || 
											($_POST["level_taught"] == "High School - O Level")|| 
											($_POST["level_taught"] == "High School - A Level"))){
		include ('selected_subs.php');
		client_pref_loc2($prefLoc2_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if (!empty($_POST["preferred_location2"]) && !empty($_POST["preferred_location1"])){
		client_pref_loc2($prefLoc2_id, $ecNumber, $levelTaught);
	}elseif (!empty($_POST["preferred_location2"]) && empty($_POST["preferred_location1"])){
		$error_message = 'Invalid form input: You cannot select option 2 before option 1';
	}

if (isset($_POST["preferred_location3"]) && (($_POST["level_taught"] == "High School - ZJC") || 
											($_POST["level_taught"] == "High School - O Level")|| 
											($_POST["level_taught"] == "High School - A Level"))){
		include_once ('selected_subs.php');
		client_pref_loc3($prefLoc3_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if (!empty($_POST["preferred_location3"]) && !empty($_POST["preferred_location2"])){
		client_pref_loc3($prefLoc3_id, $ecNumber, $levelTaught);
	}elseif (!empty($_POST["preferred_location3"]) && empty($_POST["preferred_location2"])){
		$error_message = 'Invalid form input: You cannot select option 3 before option 2';
	}
	
if (isset($_POST["preferred_schools1"]) && (($_POST["level_taught"] == "High School - ZJC") || 
											($_POST["level_taught"] == "High School - O Level")|| 
											($_POST["level_taught"] == "High School - A Level"))){
		include('selected_subs.php');
		client_pref_sch1($prefSchool1_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if (!empty($_POST["preferred_schools1"])){
		client_pref_sch1($prefSchool1_id, $ecNumber, $levelTaught);
	}

if (isset($_POST["preferred_schools2"]) && (($_POST["level_taught"] == "High School - ZJC") || 
											($_POST["level_taught"] == "High School - O Level")|| 
											($_POST["level_taught"] == "High School - A Level")) 
											&& (!empty($_POST["preferred_schools1"]))){
		include('selected_subs.php');
		client_pref_sch2($prefSchool2_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if (!empty($_POST["preferred_schools2"]) && !empty($_POST["preferred_schools1"])){
		client_pref_sch1($prefSchool2_id, $ecNumber, $levelTaught);
	}elseif (!empty($_POST["preferred_schools2"]) && empty($_POST["preferred_schools1"])){
		$error_message = 'Invalid form input: You cannot select option 2 before option 1';
	}
	
if (isset($_POST["preferred_schools3"]) && (($_POST["level_taught"] == "High School - ZJC") || 
											($_POST["level_taught"] == "High School - O Level")|| 
											($_POST["level_taught"] == "High School - A Level"))
											&& (!empty($_POST["preferred_schools2"]))){
		include('selected_subs.php');
		client_pref_sch3($prefSchool3_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if (!empty($_POST["preferred_schools3"]) && !empty($_POST["preferred_schools2"])){
		client_pref_sch3($prefSchool3_id, $ecNumber, $levelTaught);
	}elseif (!empty($_POST["preferred_schools3"]) && empty($_POST["preferred_schools2"])){
		$error_message = 'Invalid form input: You cannot select option 3 before option 2';
	}
	
if (isset($_POST["preferred_schools4"]) && (($_POST["level_taught"] == "High School - ZJC") || 
											($_POST["level_taught"] == "High School - O Level")|| 
											($_POST["level_taught"] == "High School - A Level"))
											 && (!empty($_POST["preferred_schools3"]))){
		include('selected_subs.php');
		client_pref_sch4($prefSchool4_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if (!empty($_POST["preferred_schools4"]) && !empty($_POST["preferred_schools3"])){
		client_pref_sch4($prefSchool4_id, $ecNumber, $levelTaught);
	}elseif (!empty($_POST["preferred_schools4"]) && empty($_POST["preferred_schools3"])){
		$error_message = 'Invalid form input: You cannot select option 4 before option 3';
	}
	
if (isset($_POST["preferred_schools5"]) && (($_POST["level_taught"] == "High School - ZJC") || 
											($_POST["level_taught"] == "High School - O Level")|| 
											($_POST["level_taught"] == "High School - A Level"))
											 && (!empty($_POST["preferred_schools4"]))){
		include('selected_subs.php');
		client_pref_sch5($prefSchool5_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if (!empty($_POST["preferred_schools5"]) && !empty($_POST["preferred_schools4"])){
		client_pref_sch5($prefSchool5_id, $ecNumber, $levelTaught);
	}elseif (!empty($_POST["preferred_schools5"]) && empty($_POST["preferred_schools4"])){
		$error_message = 'Invalid form input: You cannot select option 5 before option 4';
	}
	
if (isset($_POST["preferred_schools6"]) && (($_POST["level_taught"] == "High School - ZJC") || 
											($_POST["level_taught"] == "High School - O Level")|| 
											($_POST["level_taught"] == "High School - A Level"))
											 && (!empty($_POST["preferred_schools5"]))){
		include('selected_subs.php');
		client_pref_sch6($prefSchool6_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if (!empty($_POST["preferred_schools6"]) && !empty($_POST["preferred_schools5"])){
		client_pref_sch6($prefSchool6_id, $ecNumber, $levelTaught);
	}elseif (!empty($_POST["preferred_schools6"]) && empty($_POST["preferred_schools5"])){
		$error_message = 'Invalid form input: You cannot select option 6 before option 5';
	}
	
if (isset($_POST["preferred_schools7"]) && (($_POST["level_taught"] == "High School - ZJC") || 
											($_POST["level_taught"] == "High School - O Level")|| 
											($_POST["level_taught"] == "High School - A Level"))
											 && (!empty($_POST["preferred_schools6"]))){
		include('selected_subs.php');
		client_pref_sch7($prefSchool7_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if (!empty($_POST["preferred_schools7"]) && !empty($_POST["preferred_schools6"])){
		client_pref_sch7($prefSchool7_id, $ecNumber, $levelTaught);
	}elseif (!empty($_POST["preferred_schools7"]) && !empty($_POST["preferred_schools6"])){
		$error_message = 'Invalid form input: You cannot select option 7 before option 6';
	}
	
if (isset($_POST["preferred_schools8"]) && (($_POST["level_taught"] == "High School - ZJC") || 
											($_POST["level_taught"] == "High School - O Level")|| 
											($_POST["level_taught"] == "High School - A Level"))
											 && (!empty($_POST["preferred_schools7"]))){
		include('selected_subs.php');
		client_pref_sch8($prefSchool8_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if (!empty($_POST["preferred_schools8"]) && !empty($_POST["preferred_schools7"])){
		client_pref_sch8($prefSchool8_id, $ecNumber, $levelTaught);
	}elseif (!empty($_POST["preferred_schools8"]) && empty($_POST["preferred_schools7"])){
		$error_message = 'Invalid form input: You cannot select option 8 before option 7';
	}
	
if (isset($_POST["preferred_schools9"]) && (($_POST["level_taught"] == "High School - ZJC") || 
											($_POST["level_taught"] == "High School - O Level")|| 
											($_POST["level_taught"] == "High School - A Level"))
											 && (!empty($_POST["preferred_schools7"]))){
		include('selected_subs.php');
		client_pref_sch9($prefSchool9_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if (!empty($_POST["preferred_schools9"]) && !empty($_POST["preferred_schools8"])){
		client_pref_sch9($prefSchool9_id, $ecNumber, $levelTaught);
	}elseif (!empty($_POST["preferred_schools9"]) && empty($_POST["preferred_schools8"])){
		$error_message = 'Invalid form input: You cannot select option 9 before option 8';
	}

if (isset($_POST["preferred_schools10"]) && (($_POST["level_taught"] == "High School - ZJC") || 
											($_POST["level_taught"] == "High School - O Level")|| 
											($_POST["level_taught"] == "High School - A Level"))
											 && (!empty($_POST["preferred_schools9"]))){
		include('selected_subs.php');
		client_pref_sch10($prefSchool10_id, $ecNumber, $levelTaught, $sub1_id, $sub2_id);
	}else if (!empty($_POST["preferred_schools10"]) && !empty($_POST["preferred_schools9"])){
		client_pref_sch10($prefSchool10_id, $ecNumber, $levelTaught);
	}elseif (!empty($_POST["preferred_schools10"]) && empty($_POST["preferred_schools9"])){
		$error_message = 'Invalid form input: You cannot select option 10 before option 9';
	}
}

header("Location:../Account_manage.php");	
//create_prefs();
/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/	

/*echo "<pre>";
print_r($error_message);
echo "</pre>";

echo "<pre>";
print_r($_POST);
echo "</pre>";	
echo "<pre>";
print_r($sub2_id);
echo "</pre>";	
echo "<pre>";
print_r($subs);
echo "</pre>";	
echo "<pre>";
print_r($dateCreated);
echo "</pre>";*/
/*echo "<pre>";
print_r($subjects);
echo "</pre>";		

*/			
?>