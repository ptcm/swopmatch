<?php
ob_end_flush();
ob_start();
session_start();



error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('html_errors', 1);

/* Displays user information and some useful messages */

include 'inc/functions.php';

        $sub1_id = 0;
        $sub2_id = 0;
        $currLoc_id = 0;
        $client_id = '';
        $prefSchool1_id = 0;
        $prefSchool2_id = 0;
        $prefSchool3_id = 0;
        $prefSchool4_id = 0;
        $prefSchool5_id = 0;
        $prefSchool6_id = 0;
        $prefSchool7_id = 0;
        $prefSchool8_id = 0;
        $prefSchool9_id = 0;
        $prefSchool10_id = 0;
        $prefLoc1_id = 0;
        $prefLoc2_id = 0;
        $prefLoc3_id = 0;
        $prefLoc4_id = 0;
        $prefLoc5_id = 0;
        $prefTown_id = 0;
        $prefTown2_id = 0;
        $prefTown3_id = 0;
        $prefDistr1_id = 0;
        $prefDistr2_id = 0;
        $prefDistr3_id = 0;
        $prefDistr4_id = 0;
        $prefProv_id = 0;
        $prefProv2_id = 0;
        
        if(!isset($_SESSION['logged_in'])){
           $_SESSION['logged_in'] = '';
        }
        
        if(!isset($_SESSION['logged_status'])){
           $_SESSION['logged_status'] = '';
        }
        
        //declare SESSION variable to use when updating admin password
        if(isset($_SESSION['logged_status'])){
           $logged_status = $_SESSION['logged_status'];
        }
        
        //declare SESSION variable to use when updating admin password
        if(isset($_SESSION['logged_in'])){
           $logged_ec = $_SESSION['logged_in'];
        }
        
        //declare GET variable to use when updating admin password
        if(isset($_GET['id'])){
           $_SESSION['id'] = $_GET['id'];
        }
      
      // Check if user is logged in using the session variable
if ($_POST){
  if(isset($_SESSION['logged_in']) != isset($_SESSION['ec_number'])){
    
    header("location: index.php");
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
  $mpp_match_ec_no = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
  $mpd_match_ec_no = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
  $mpt_match_ec_no = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
  $mpl_match_ec_no = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
  $mps_match_ec_no = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
  $mcs_match_ec_no = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
  $mpd_id1 = filter_input(INPUT_POST,'id', FILTER_SANITIZE_NUMBER_INT);
  $mpd_id2 = filter_input(INPUT_POST,'id', FILTER_SANITIZE_NUMBER_INT);
  $prefDistr1 = filter_input(INPUT_POST,'id', FILTER_SANITIZE_NUMBER_INT);
  $prefDistr2 = filter_input(INPUT_POST,'id', FILTER_SANITIZE_NUMBER_INT);
  $prefDistr3 = filter_input(INPUT_POST,'id', FILTER_SANITIZE_NUMBER_INT);
  $prefDistr4 = filter_input(INPUT_POST,'id', FILTER_SANITIZE_NUMBER_INT);
  
	if (isset($_POST["gender"])){
		$gender = $_POST["gender"];
	}

	 $userFirstName = trim(filter_input(INPUT_POST, 'user_first_name', FILTER_SANITIZE_STRING));
	 $userLastName = trim(filter_input(INPUT_POST, 'user_last_name', FILTER_SANITIZE_STRING));
	 $mobileNumber = trim(filter_input(INPUT_POST, 'mobile_number', FILTER_SANITIZE_NUMBER_INT));
	 $clientAgent = trim(filter_input(INPUT_POST, 'referrer_agent', FILTER_SANITIZE_NUMBER_INT));
	 
	 //check whether the mobile # is a netone number and declare the mobile # variable accordingly
    if(substr($mobileNumber, 0, 3) === '071'){
	    $mobile = '263'.substr($mobileNumber, 1); //for use in sending sms
	}else{
	    $mobile = $mobileNumber;
	}
	
	 if(!empty($_POST['client_id'])){
      $ecNumber = trim(strtoupper(filter_input(INPUT_POST, 'ec_number', FILTER_SANITIZE_STRING)));
       }else{
         $ecNumber = trim(strtoupper(substr($userFirstName, 0, 1))).trim(strtoupper(substr($userLastName, 0, 1))).trim(strtoupper(substr($mobileNumber, -4)));
       }
       
	 $userEmail = trim(filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL));
   $userPassword = trim(password_hash(filter_input(INPUT_POST, 'user_password', FILTER_SANITIZE_STRING), PASSWORD_BCRYPT));
   
   $prev_status = trim(filter_input(INPUT_POST, 'prev_status', FILTER_SANITIZE_STRING));
   
	if (!empty($_POST["level_taught"])){
		$levelTaught = strtoupper($_POST["level_taught"]);
	}
  
  if($levelTaught){
    if($levelTaught == 'Primary - ECD' || $levelTaught == 'Primary - General' || $levelTaught == 'Primary - Special Needs'){
          $level = 'Primary';
        }else{
          $level = 'Secondary';
    }
  }

	if (!empty($_POST["preferred_province"])){
				$prefProv_id = filter_input(INPUT_POST, 'preferred_province', FILTER_SANITIZE_NUMBER_INT);
			}
			
	//checks if option 1 provinces has been selected before 2nd option can be selected else raises an error alert
	if (!empty($_POST["preferred_province2"]) && !empty($prefProv_id)){
				$prefProv2_id = filter_input(INPUT_POST, 'preferred_province2', FILTER_SANITIZE_NUMBER_INT);
			}elseif(empty($error_message) && !empty($_POST["preferred_province2"]) && empty($prefProv_id)){
			$error_message = 'Whoa!! You can not choose Province second option without choosing a first option';
		}

	if (!empty($_POST["current_province"])){
				$currProv_id = filter_input(INPUT_POST, 'current_province', FILTER_SANITIZE_NUMBER_INT);
			}


if (!empty($_POST["preferred_district1"])){
		$preferredDistrict1 = $_POST["preferred_district1"];
}

if (!empty($_POST["preferred_district2"])){
		$preferredDistrict2 = $_POST["preferred_district2"];
}

    //current district for current details section
    $currDistr_id = filter_input(INPUT_POST, 'current_district', FILTER_SANITIZE_NUMBER_INT);

	//force specific town for districts in specific towns
	switch ($currDistr_id){
	    case '7': $currTown_id = 3; break;
	    case '55': $currTown_id = 3; break;
	    case '17': $currTown_id = 11; break;
		case '25': $currTown_id = 11; break;
		case '35': $currTown_id = 11; break;
		case '43': $currTown_id = 11; break;
		case '57': $currTown_id = 11; break;
		case '69': $currTown_id = 11; break;
		case '15': $currTown_id = 8; break;
		case '50': $currTown_id = 18; break;
		//case '12': $currTown_id = 6; break;
		//case '39': $currTown_id = 16; break;
		//case '9': $currTown_id = 4; break;
		//case '31': $currTown_id = 13; break;
		//case '40': $currTown_id = 17; break;
		//case '1': $currTown_id = 1; break;
	    //case '24': $currTown_id = 10; break;
		//case '64': $currTown_id = 22; break;
		default:
			$currTown_id = filter_input(INPUT_POST, 'current_town', FILTER_SANITIZE_NUMBER_INT);
		}
	
	//declare an array of districts with towns	
	$wtowns = [1, 3, 7, 9, 12, 13, 15, 17, 19, 24, 25, 27, 31, 33, 35, 37, 39, 40, 43, 50, 55, 57, 64, 69];
	
	//Rusape is the only town accepted for Makoni district
	if((($currDistr_id == 37) && ($currTown_id != 21)) ||
	    //Gokwe is the only town accepted for Gokwe South district
	    (($currDistr_id == 19) && ($currTown_id != 9)) ||
	    //Kwekwe or Redcliff are the only towns accepted for Kwekwe district
	    (($currDistr_id == 33) && ($currTown_id != 15)) ||
	    //(($currDistr_id == 33) && ($currTown_id != 20)) ||
	    !in_array($currDistr_id, $wtowns)){
	    $currTown_id = 0;
	}
    
    //check if selected location is in the town selected and clear location in cases where a location has been selected based on province and the system enforces a specific town
    if(isset($_POST["current_location"]) && !empty($_POST["current_location"])){
        $lok = filter_input(INPUT_POST, 'current_location', FILTER_SANITIZE_NUMBER_INT);
        if(!empty($currTown_id)){
           $results_currLoc = $db->query("SELECT loc_id FROM locations WHERE loc_town_id = $currTown_id AND loc_id = $lok");
                $currLoc = $results_currLoc->fetchColumn();
                if(empty($currLoc)){
                   $currLoc_id = 0; 
                }elseif(!empty($currLoc)){
                    $currLoc_id = intval($currLoc);
                }
        }else{
           $currLoc_id = filter_input(INPUT_POST, 'current_location', FILTER_SANITIZE_NUMBER_INT); 
        }
    }
    
    
    $currSch_id  = filter_input(INPUT_POST, 'current_school', FILTER_SANITIZE_NUMBER_INT);
   
    
    if(!empty($currSch_id)){
    $results_currTown_id = $db->query("SELECT school_town_id FROM schools WHERE school_id = $currSch_id");
    $currTown_id = $results_currTown_id->fetchColumn();
    
    $results_currLoc_id = $db->query("SELECT school_loc_id FROM schools WHERE school_id = $currSch_id");
    $currLoc_id = $results_currLoc_id->fetchColumn();
    }
    
    
  //prevent inserting a record in the database if the current station details are not fully provided
  if ($currProv_id == "" || $currDistr_id == "" /* || $currSch_id == "" */){
		$error_message = 'Whoa! Please fully provide your Current Station Details before you can proceed!';
	}  
	
  //prevent inserting a record in the database if the basic details are not fully provided
  if (empty($error_message) && ($userFirstName == "" || $userLastName == "" || $ecNumber == "" || $mobileNumber == "" || $gender == "")){
		$error_message = 'Whoa! Please fill in ALL your Basic Details!';
	}

  //check if a robot tries to insert records in the database
	if (empty($error_message) && $_POST["blank"] != ""){
		$error_message = "Bad form input";
		exit;
	}


if (isset($_POST["subject1"])){
	$subject1 = filter_input(INPUT_POST, 'subject1', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject2"])){
	$subject2 = filter_input(INPUT_POST, 'subject2', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject3"])){
	$subject3 = filter_input(INPUT_POST, 'subject3', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject4"])){
	$subject4 = filter_input(INPUT_POST, 'subject4', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject5"])){
	$subject5 = filter_input(INPUT_POST, 'subject5', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject6"])){
	$subject6 = filter_input(INPUT_POST, 'subject6', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject7"])){
	$subject7 = filter_input(INPUT_POST, 'subject7', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject8"])){
	$subject8 = filter_input(INPUT_POST, 'subject8', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject9"])){
	$subject9 = filter_input(INPUT_POST, 'subject9', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject10"])){
	$subject10 = filter_input(INPUT_POST, 'subject10', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject11"])){
	$subject11 = filter_input(INPUT_POST, 'subject11', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject12"])){
	$subject12 = filter_input(INPUT_POST, 'subject12', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject13"])){
	$subject13 = filter_input(INPUT_POST, 'subject13', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject14"])){
	$subject14 = filter_input(INPUT_POST, 'subject14', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject15"])){
	$subject15 = filter_input(INPUT_POST, 'subject15', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject16"])){
	$subject16 = filter_input(INPUT_POST, 'subject16', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject17"])){
	$subject17 = filter_input(INPUT_POST, 'subject17', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject18"])){
	$subject18 = filter_input(INPUT_POST, 'subject18', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject19"])){
	$subject19 = filter_input(INPUT_POST, 'subject19', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject20"])){
	$subject20 = filter_input(INPUT_POST, 'subject20', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject21"])){
	$subject21 = filter_input(INPUT_POST, 'subject21', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject22"])){
	$subject22 = filter_input(INPUT_POST, 'subject22', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject23"])){
	$subject23 = filter_input(INPUT_POST, 'subject23', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject24"])){
	$subject24 = filter_input(INPUT_POST, 'subject24', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject25"])){
	$subject25 = filter_input(INPUT_POST, 'subject25', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject26"])){
	$subject26 = filter_input(INPUT_POST, 'subject26', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject27"])){
	$subject27 = filter_input(INPUT_POST, 'subject27', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject28"])){
	$subject28 = filter_input(INPUT_POST, 'subject28', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject29"])){
	$subject29 = filter_input(INPUT_POST, 'subject29', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject30"])){
	$subject30 = filter_input(INPUT_POST, 'subject30', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject31"])){
	$subject31 = filter_input(INPUT_POST, 'subject31', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject32"])){
	$subject32 = filter_input(INPUT_POST, 'subject32', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject33"])){
	$subject33 = filter_input(INPUT_POST, 'subject33', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject34"])){
	$subject34 = filter_input(INPUT_POST, 'subject34', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject35"])){
	$subject35 = filter_input(INPUT_POST, 'subject35', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject36"])){
	$subject36 = filter_input(INPUT_POST, 'subject36', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject37"])){
	$subject37 = filter_input(INPUT_POST, 'subject37', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject38"])){
	$subject38 = filter_input(INPUT_POST, 'subject38', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject39"])){
	$subject39 = filter_input(INPUT_POST, 'subject39', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject40"])){
	$subject40 = filter_input(INPUT_POST, 'subject40', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject41"])){
	$subject41 = filter_input(INPUT_POST, 'subject41', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject42"])){
	$subject42 = filter_input(INPUT_POST, 'subject42', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject43"])){
	$subject43 = filter_input(INPUT_POST, 'subject43', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject44"])){
	$subject44 = filter_input(INPUT_POST, 'subject44', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject45"])){
	$subject45 = filter_input(INPUT_POST, 'subject45', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject46"])){
	$subject46 = filter_input(INPUT_POST, 'subject46', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject47"])){
	$subject47 = filter_input(INPUT_POST, 'subject47', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject48"])){
	$subject48 = filter_input(INPUT_POST, 'subject48', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject49"])){
	$subject49 = filter_input(INPUT_POST, 'subject49', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject50"])){
	$subject50 = filter_input(INPUT_POST, 'subject50', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject51"])){
	$subject51 = filter_input(INPUT_POST, 'subject51', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject52"])){
	$subject52 = filter_input(INPUT_POST, 'subject52', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject53"])){
	$subject53 = filter_input(INPUT_POST, 'subject53', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject54"])){
	$subject54 = filter_input(INPUT_POST, 'subject54', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject55"])){
	$subject55 = filter_input(INPUT_POST, 'subject55', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject56"])){
	$subject56 = filter_input(INPUT_POST, 'subject56', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject57"])){
	$subject57 = filter_input(INPUT_POST, 'subject57', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject58"])){
	$subject58 = filter_input(INPUT_POST, 'subject58', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject59"])){
	$subject59 = filter_input(INPUT_POST, 'subject59', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject60"])){
	$subject60 = filter_input(INPUT_POST, 'subject60', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject61"])){
	$subject61 = filter_input(INPUT_POST, 'subject61', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject62"])){
	$subject62 = filter_input(INPUT_POST, 'subject62', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject63"])){
	$subject63 = filter_input(INPUT_POST, 'subject63', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject64"])){
	$subject64 = filter_input(INPUT_POST, 'subject64', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject65"])){
	$subject65 = filter_input(INPUT_POST, 'subject65', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject66"])){
	$subject66 = filter_input(INPUT_POST, 'subject66', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject67"])){
	$subject67 = filter_input(INPUT_POST, 'subject67', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject68"])){
	$subject68 = filter_input(INPUT_POST, 'subject68', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject69"])){
	$subject69 = filter_input(INPUT_POST, 'subject69', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject70"])){
	$subject70 = filter_input(INPUT_POST, 'subject70', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject71"])){
	$subject71 = filter_input(INPUT_POST, 'subject71', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject72"])){
	$subject72 = filter_input(INPUT_POST, 'subject72', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject73"])){
	$subject73 = filter_input(INPUT_POST, 'subject73', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject74"])){
	$subject74 = filter_input(INPUT_POST, 'subject74', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject75"])){
	$subject75 = filter_input(INPUT_POST, 'subject75', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject76"])){
	$subject76 = filter_input(INPUT_POST, 'subject76', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject77"])){
	$subject77 = filter_input(INPUT_POST, 'subject77', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject78"])){
	$subject78 = filter_input(INPUT_POST, 'subject78', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject79"])){
	$subject79 = filter_input(INPUT_POST, 'subject79', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject80"])){
	$subject80 = filter_input(INPUT_POST, 'subject80', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject81"])){
	$subject81 = filter_input(INPUT_POST, 'subject81', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject82"])){
	$subject82 = filter_input(INPUT_POST, 'subject82', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject83"])){
	$subject83 = filter_input(INPUT_POST, 'subject83', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject84"])){
	$subject84 = filter_input(INPUT_POST, 'subject84', FILTER_SANITIZE_STRING);
}
if (isset($_POST["subject85"])){
	$subject85 = filter_input(INPUT_POST, 'subject85', FILTER_SANITIZE_STRING);
}

	//declares variables if specific locations option is chosen to be used to check for duplicated options later in the code.
	if (!empty($_POST["preferred_location1"])){
    $pref1_distr_id = filter_input(INPUT_POST, 'loc_name1_distr', FILTER_SANITIZE_NUMBER_INT);
		$preferred_locations1 = filter_input(INPUT_POST, 'preferred_location1', FILTER_SANITIZE_STRING);
	}
	
	
    //declares variables for province and town in which the preferred location option 1 is
    if(!empty($pref1_distr_id)){
    $results_pref1_id = $db->query("SELECT distr_province_id, distr_town_id FROM districts WHERE distr_id = $pref1_distr_id");
    $rows = $results_pref1_id->fetch(PDO::FETCH_ASSOC);
    $pref1_loc_province_id = $rows['distr_province_id'];
    $pref1_town_id = $rows['distr_town_id'];
    }

	if (!empty($_POST["preferred_location2"])){
    $pref2_distr_id = filter_input(INPUT_POST, 'loc_name2_distr', FILTER_SANITIZE_NUMBER_INT);
		$preferred_locations2 = filter_input(INPUT_POST, 'preferred_location2', FILTER_SANITIZE_STRING);
	}
	
	//declares variables for province and town in which the preferred location option 2 is
    if(!empty($pref2_distr_id)){
    $results_pref2_id = $db->query("SELECT distr_province_id, distr_town_id FROM districts WHERE distr_id = $pref2_distr_id");
    $rows = $results_pref2_id->fetch(PDO::FETCH_ASSOC);
    $pref2_loc_province_id = $rows['distr_province_id'];
    $pref2_town_id = $rows['distr_town_id'];
    }

	if (!empty($_POST["preferred_location3"])){
    $pref3_distr_id = filter_input(INPUT_POST, 'loc_name3_distr', FILTER_SANITIZE_NUMBER_INT);
		$preferred_locations3 = filter_input(INPUT_POST, 'preferred_location3', FILTER_SANITIZE_STRING);
	}
	
	//declares variables for province and town in which the preferred location option 3 is
    if(!empty($pref3_distr_id)){
    $results_pref3_id = $db->query("SELECT distr_province_id, distr_town_id FROM districts WHERE distr_id = $pref3_distr_id");
    $rows = $results_pref3_id->fetch(PDO::FETCH_ASSOC);
    $pref3_loc_province_id = $rows['distr_province_id'];
    $pref3_town_id = $rows['distr_town_id'];
    }
	
	if (!empty($_POST["preferred_location4"])){
    $pref4_distr_id = filter_input(INPUT_POST, 'loc_name4_distr', FILTER_SANITIZE_NUMBER_INT);
		$preferred_locations4 = filter_input(INPUT_POST, 'preferred_location4', FILTER_SANITIZE_STRING);
	}
	
	//declares variables for province and town in which the preferred location option 4 is
    if(!empty($pref4_distr_id)){
    $results_pref4_id = $db->query("SELECT distr_province_id, distr_town_id FROM districts WHERE distr_id = $pref4_distr_id");
    $rows = $results_pref4_id->fetch(PDO::FETCH_ASSOC);
    $pref4_loc_province_id = $rows['distr_province_id'];
    $pref4_town_id = $rows['distr_town_id'];
    }
	
	if (!empty($_POST["preferred_location5"])){
    $pref5_distr_id = filter_input(INPUT_POST, 'loc_name5_distr', FILTER_SANITIZE_NUMBER_INT);
		$preferred_locations5 = filter_input(INPUT_POST, 'preferred_location5', FILTER_SANITIZE_STRING);
	}
	
	//declares variables for province and town in which the preferred location option 5 is
    if(!empty($pref5_distr_id)){
    $results_pref5_id = $db->query("SELECT distr_province_id, distr_town_id FROM districts WHERE distr_id = $pref5_distr_id");
    $rows = $results_pref5_id->fetch(PDO::FETCH_ASSOC);
    $pref5_loc_province_id = $rows['distr_province_id'];
    $pref5_town_id = $rows['distr_town_id'];
    }

	//declares variables if specific schools option is chosen to be used to check for duplicated options later in the code
	if (!empty($_POST["preferred_schools1"])){
    $pref1_distr_id = filter_input(INPUT_POST, 'preferred_schools1_distr', FILTER_SANITIZE_NUMBER_INT);
		$preferred_schools1 = filter_input(INPUT_POST, 'preferred_schools1', FILTER_SANITIZE_NUMBER_INT);
	}

	if (!empty($_POST["preferred_schools2"])){
    $pref2_distr_id = filter_input(INPUT_POST, 'preferred_schools2_distr', FILTER_SANITIZE_NUMBER_INT);
		$preferred_schools2 = filter_input(INPUT_POST, 'preferred_schools2', FILTER_SANITIZE_NUMBER_INT);
	}

	if (!empty($_POST["preferred_schools3"])){
    $pref3_distr_id = filter_input(INPUT_POST, 'preferred_schools3_distr', FILTER_SANITIZE_NUMBER_INT);
		$preferred_schools3 = filter_input(INPUT_POST, 'preferred_schools3', FILTER_SANITIZE_NUMBER_INT);
	}

	if (!empty($_POST["preferred_schools4"])){
    $pref4_distr_id = filter_input(INPUT_POST, 'preferred_schools4_distr', FILTER_SANITIZE_NUMBER_INT);
		$preferred_schools4 = filter_input(INPUT_POST, 'preferred_schools4', FILTER_SANITIZE_NUMBER_INT);
	}

	if (!empty($_POST["preferred_schools5"])){
    $pref5_distr_id = filter_input(INPUT_POST, 'preferred_schools5_distr', FILTER_SANITIZE_NUMBER_INT);
		$preferred_schools5 = filter_input(INPUT_POST, 'preferred_schools5', FILTER_SANITIZE_NUMBER_INT);
	}

	if (!empty($_POST["preferred_schools6"])){
    $pref6_distr_id = filter_input(INPUT_POST, 'preferred_schools6_distr', FILTER_SANITIZE_NUMBER_INT);
		$preferred_schools6 = filter_input(INPUT_POST, 'preferred_schools6', FILTER_SANITIZE_NUMBER_INT);
	}

	if (!empty($_POST["preferred_schools7"])){
    $pref7_distr_id = filter_input(INPUT_POST, 'preferred_schools7_distr', FILTER_SANITIZE_NUMBER_INT);
		$preferred_schools7 = filter_input(INPUT_POST, 'preferred_schools7', FILTER_SANITIZE_NUMBER_INT);
	}

	if (!empty($_POST["preferred_schools8"])){
    $pref8_distr_id = filter_input(INPUT_POST, 'preferred_schools8_distr', FILTER_SANITIZE_NUMBER_INT);
		$preferred_schools8 = filter_input(INPUT_POST, 'preferred_schools8', FILTER_SANITIZE_NUMBER_INT);
	}

	if (!empty($_POST["preferred_schools9"])){
    $pref9_distr_id = filter_input(INPUT_POST, 'preferred_schools9_distr', FILTER_SANITIZE_NUMBER_INT);
		$preferred_schools9 = filter_input(INPUT_POST, 'preferred_schools9', FILTER_SANITIZE_NUMBER_INT);
	}

	if (!empty($_POST["preferred_schools10"])){
    $pref10_distr_id = filter_input(INPUT_POST, 'preferred_schools10_distr', FILTER_SANITIZE_NUMBER_INT);
		$preferred_schools10 = filter_input(INPUT_POST, 'preferred_schools10', FILTER_SANITIZE_NUMBER_INT);
	}

	//creates selected preferred schools into an array to check for duplicate values
		$unique_pref_schools = [];
		if (!empty($_POST["preferred_schools1"])){
			$unique_pref_schools[] = $preferred_schools1;
		}
		if (!empty($_POST["preferred_schools2"])){
			$unique_pref_schools[] = $preferred_schools2;
		}
		if (!empty($_POST["preferred_schools3"])){
			$unique_pref_schools[] = $preferred_schools3;
		}
		if (!empty($_POST["preferred_schools4"])){
			$unique_pref_schools[] = $preferred_schools4;
		}
		if (!empty($_POST["preferred_schools5"])){
			$unique_pref_schools[] = $preferred_schools5;
		}
		if (!empty($_POST["preferred_schools6"])){
			$unique_pref_schools[] = $preferred_schools6;
		}
		if (!empty($_POST["preferred_schools7"])){
			$unique_pref_schools[] = $preferred_schools7;
		}
		if (!empty($_POST["preferred_schools8"])){
			$unique_pref_schools[] = $preferred_schools8;
		}
		if (!empty($_POST["preferred_schools9"])){
			$unique_pref_schools[] = $preferred_schools9;
		}
		if (!empty($_POST["preferred_schools10"])){
			$unique_pref_schools[] = $preferred_schools10;
		}

		//creates selected preferred locations into an array to check for duplicate values

		$unique_pref_locations = [];
		if (!empty($_POST["preferred_location1"])){
			$unique_pref_locations[] = $preferred_locations1;
		}
		if (!empty($_POST["preferred_location2"])){
			$unique_pref_locations[] = $preferred_locations2;
		}
		if (!empty($_POST["preferred_location3"])){
			$unique_pref_locations[] = $preferred_locations3;
		}


	//if preferred options are by districts, declares variables containing the chosen districts IDs
  if (!empty($_POST["preferred_district1"])){
    $pref1_province_id = filter_input(INPUT_POST, 'distr_name1_province', FILTER_SANITIZE_NUMBER_INT);
		$prefDistr1_id = filter_input(INPUT_POST, 'preferred_district1', FILTER_SANITIZE_NUMBER_INT);
			}

    //checks if option 1 districts has been selected before 2nd option can be selected else raises an error alert
	 if (!empty($_POST["preferred_district2"]) && !empty($prefDistr1_id)){
    $pref2_province_id = filter_input(INPUT_POST, 'distr_name2_province', FILTER_SANITIZE_NUMBER_INT);
		$prefDistr2_id = filter_input(INPUT_POST, 'preferred_district2', FILTER_SANITIZE_NUMBER_INT);
		}elseif (empty($error_message) && !empty($_POST["preferred_district2"]) && empty($prefDistr2_id)){
			$error_message = 'You can not choose District second option without choosing a first option';
		}
		
	//checks if option 2 districts has been selected before 3rd option can be selected else raises an error alert
	 if (!empty($_POST["preferred_district3"]) && !empty($prefDistr2_id)){
    $pref3_province_id = filter_input(INPUT_POST, 'distr_name3_province', FILTER_SANITIZE_NUMBER_INT);
		$prefDistr3_id = filter_input(INPUT_POST, 'preferred_district3', FILTER_SANITIZE_NUMBER_INT);
		}elseif (empty($error_message) && !empty($_POST["preferred_district3"]) && empty($prefDistr2_id)){
			$error_message = 'You can not choose District third option without choosing a second option';
		}
		
	//checks if option 3 districts has been selected before 4th option can be selected else raises an error alert
	 if (!empty($_POST["preferred_district4"]) && !empty($prefDistr3_id)){
    $pref4_province_id = filter_input(INPUT_POST, 'distr_name4_province', FILTER_SANITIZE_NUMBER_INT);
		$prefDistr4_id = filter_input(INPUT_POST, 'preferred_district4', FILTER_SANITIZE_NUMBER_INT);
		}elseif (empty($error_message) && !empty($_POST["preferred_district4"]) && empty($prefDistr3_id)){
			$error_message = 'You can not choose District fourth option without choosing a third option';
		}



    //when preference is by town, declares variables containing town province ID
	 if (!empty($_POST["town_name1_province"])){
    $pref_town_province_id = filter_input(INPUT_POST, 'town_name1_province', FILTER_SANITIZE_NUMBER_INT);
	     
	 }
    
    //when preference is by town, declares variables containing town name and town ID
			if (empty($error_message) && isset($_POST["preferred_town"])){
				$prefTown_id = filter_input(INPUT_POST, 'preferred_town', FILTER_SANITIZE_NUMBER_INT);
				}
				
	//when preference is by town, declares variables containing town 2 province ID
	 if (!empty($_POST["town_name2_province"])){
    $pref2_town_province_id = filter_input(INPUT_POST, 'town_name2_province', FILTER_SANITIZE_NUMBER_INT);
	     
	 }
				
	//when preference is by town, declares variables containing town name 2 and town 2 ID
			if (empty($error_message) && isset($_POST["preferred_town2"])){
				$prefTown2_id = filter_input(INPUT_POST, 'preferred_town2', FILTER_SANITIZE_NUMBER_INT);
				}else{
				    $prefTown2_id = '';
				}
				
	//when preference is by town, declares variables containing town 3 province ID
	 if (!empty($_POST["town_name3_province"])){
    $pref3_town_province_id = filter_input(INPUT_POST, 'town_name3_province', FILTER_SANITIZE_NUMBER_INT);
	     
	 }
	
	//when preference is by town, declares variables containing town name 3 and town 3 ID
			if (empty($error_message) && isset($_POST["preferred_town3"])){
				$prefTown3_id = filter_input(INPUT_POST, 'preferred_town3', FILTER_SANITIZE_NUMBER_INT);
				}else{
				    $prefTown3_id = '';
				}


      //when preference is by locations, declares variables containing location name and location ID
			if (empty($error_message) && isset($_POST["preferred_location1"])){
				$prefLoc1_id = filter_input(INPUT_POST, 'preferred_location1', FILTER_SANITIZE_NUMBER_INT);
                /*
				foreach($locations as $key=>$value){
						if(in_array($prefLocations1,$value)){
							  $prefLoc1_id = $value['loc_id'];
						}
					}*/
				}


      //when preference is by locations, declares variables containing location name and location ID
			if (empty($error_message) && isset($_POST["preferred_location2"]) && !empty($prefLoc1_id)){
				$prefLoc2_id = filter_input(INPUT_POST, 'preferred_location2', FILTER_SANITIZE_STRING);
				
       //ensure 1st option is selected before 2nd option
			}elseif (empty($error_message) && !empty($_POST["preferred_location2"]) && empty($prefLoc1_id)){
				$error_message = 'You can not choose Location second option without choosing a first option';
				//exit;
			}


      //when preference is by locations, declares variables containing location name and location ID
			if (empty($error_message) && !empty($_POST["preferred_location3"]) && !empty($prefLoc2_id)){
						$prefLoc3_id = filter_input(INPUT_POST, 'preferred_location3', FILTER_SANITIZE_STRING);
						
          //ensure 2nd option is selected before option 3  
					}elseif (empty($error_message) && !empty($_POST["preferred_location3"]) && empty($prefLoc2_id)){
						$error_message = 'You can not choose Location third option without choosing a second option';
						//exit;
					}
					
		//when preference is by locations, declares variables containing location name and location ID
			if (empty($error_message) && !empty($_POST["preferred_location4"]) && !empty($prefLoc3_id)){
						$prefLoc4_id = filter_input(INPUT_POST, 'preferred_location4', FILTER_SANITIZE_STRING);
						
          //ensure 3rd option is selected before option 4  
					}elseif (empty($error_message) && !empty($_POST["preferred_location4"]) && empty($prefLoc3_id)){
						$error_message = 'You can not choose Location 4th option without choosing a 3rd option';
						//exit;
					}
					
		//when preference is by locations, declares variables containing location name and location ID
			if (empty($error_message) && !empty($_POST["preferred_location5"]) && !empty($prefLoc4_id)){
						$prefLoc5_id = filter_input(INPUT_POST, 'preferred_location5', FILTER_SANITIZE_STRING);
						
          //ensure 4th option is selected before option 5  
					}elseif (empty($error_message) && !empty($_POST["preferred_location5"]) && empty($prefLoc4_id)){
						$error_message = 'You can not choose Location 5th option without choosing a 4th option';
						//exit;
					}


      //declares variable for preferred school option 1 ID and related district ID when preference is by schools
			if (isset($_POST["preferred_schools1"])){
        $prefSchool1_id = filter_input(INPUT_POST, 'preferred_schools1', FILTER_SANITIZE_NUMBER_INT);
        $preferred_schools1_distr = filter_input(INPUT_POST, 'preferred_schools1_distr', FILTER_SANITIZE_NUMBER_INT);
				}


      //declares variable for preferred school option 2 ID and related district ID when preference is by schools
			if (empty($error_message) && !empty($_POST["preferred_schools2"]) && !empty($prefSchool1_id)){
        $prefSchool2_id = filter_input(INPUT_POST, 'preferred_schools2', FILTER_SANITIZE_NUMBER_INT);
        $preferred_schools2_distr = filter_input(INPUT_POST, 'preferred_schools2_distr', FILTER_SANITIZE_NUMBER_INT);
      //ensure option 1 is selected before option 2 can be selected
			}elseif(empty($error_message) && !empty($_POST["preferred_schools2"]) && empty($prefSchool1_id)){
				$error_message = 'You can not choose Schools second option without choosing a first option';
			}


      //declares variable for preferred school option 3 ID and related district ID when preference is by schools
			if (empty($error_message) && !empty($_POST["preferred_schools3"]) && !empty($prefSchool2_id)){
        $prefSchool3_id = filter_input(INPUT_POST, 'preferred_schools3', FILTER_SANITIZE_NUMBER_INT);
        $preferred_schools3_distr = filter_input(INPUT_POST, 'preferred_schools3_distr', FILTER_SANITIZE_NUMBER_INT);
      //ensure option 2 is selected before option 3 can be selected
			}elseif (empty($error_message) && !empty($_POST["preferred_schools3"]) && empty($prefSchool2_id)){
				$error_message = 'You can not choose Schools third option without choosing a second option';
			}


      //declares variable for preferred school option 4 ID and related district ID when preference is by schools
			if (empty($error_message) && !empty($_POST["preferred_schools4"]) && !empty($prefSchool3_id)){
        $prefSchool4_id = filter_input(INPUT_POST, 'preferred_schools4', FILTER_SANITIZE_NUMBER_INT);
        $preferred_schools4_distr = filter_input(INPUT_POST, 'preferred_schools4_distr', FILTER_SANITIZE_NUMBER_INT);
      //ensure option 3 is selected before option 4 can be selected
			}elseif (empty($error_message) && !empty($_POST["preferred_schools4"]) && empty($prefSchool3_id)){
				$error_message = 'You can not choose Schools fourth option without choosing a third option';
			}


      //declares variable for preferred school option 5 ID and related district ID when preference is by schools
			if (empty($error_message) && !empty($_POST["preferred_schools5"]) && !empty($prefSchool4_id)){
        $prefSchool5_id = filter_input(INPUT_POST, 'preferred_schools5', FILTER_SANITIZE_NUMBER_INT);
        $preferred_schools5_distr = filter_input(INPUT_POST, 'preferred_schools5_distr', FILTER_SANITIZE_NUMBER_INT);
      //ensure option 4 is selected before option 5 can be selected
			}elseif (empty($error_message) && !empty($_POST["preferred_schools5"]) && empty($prefSchool4_id)){
				$error_message = 'You can not choose Schools fifth option without choosing a fourth option';
			}

			//declares variable for preferred school option 6 ID and related district ID when preference is by schools
      if (empty($error_message) && !empty($_POST["preferred_schools6"]) && !empty($prefSchool5_id)){
        $prefSchool6_id = filter_input(INPUT_POST, 'preferred_schools6', FILTER_SANITIZE_NUMBER_INT);
        $preferred_schools6_distr = filter_input(INPUT_POST, 'preferred_schools6_distr', FILTER_SANITIZE_NUMBER_INT);
      //ensure option 5 is selected before option 6 can be selected
			}elseif (empty($error_message) && !empty($_POST["preferred_schools6"]) && empty($prefSchool5_id)){
				$error_message = 'You can not choose Schools sixth option without choosing a fifth option';
			}


     //declares variable for preferred school option 7 ID and related district ID when preference is by schools
			if (empty($error_message) && !empty($_POST["preferred_schools7"]) && !empty($prefSchool6_id)){
        $prefSchool7_id = filter_input(INPUT_POST, 'preferred_schools7', FILTER_SANITIZE_NUMBER_INT);
        $preferred_schools7_distr = filter_input(INPUT_POST, 'preferred_schools7_distr', FILTER_SANITIZE_NUMBER_INT);
      //ensure option 6 is selected before option 7 can be selected
			}elseif (empty($error_message) && !empty($_POST["preferred_schools7"]) && empty($prefSchool6_id)){
				$error_message = 'You can not choose Schools seventh option without choosing a sixth option';
			}


    //declares variable for preferred school option 8 ID and related district ID when preference is by schools
			if (empty($error_message) && !empty($_POST["preferred_schools8"]) && !empty($prefSchool7_id)){
        $prefSchool8_id = filter_input(INPUT_POST, 'preferred_schools8', FILTER_SANITIZE_NUMBER_INT);
        $preferred_schools8_distr = filter_input(INPUT_POST, 'preferred_schools8_distr', FILTER_SANITIZE_NUMBER_INT);
      //ensure option 7 is selected before option 8 can be selected
			}elseif (empty($error_message) && !empty($_POST["preferred_schools8"]) && empty($prefSchool7_id)){
				$error_message = 'You can not choose Schools eighth option without choosing a seventh option';
			}


    //declares variable for preferred school option 9 ID and related district ID when preference is by schools
			if (empty($error_message) && !empty($_POST["preferred_schools9"]) && !empty($prefSchool8_id)){
        $prefSchool9_id = filter_input(INPUT_POST, 'preferred_schools9', FILTER_SANITIZE_NUMBER_INT);
        $preferred_schools9_distr = filter_input(INPUT_POST, 'preferred_schools9_distr', FILTER_SANITIZE_NUMBER_INT);
      //ensure option 8 is selected before option 9 can be selected
			}elseif (empty($error_message) && !empty($_POST["preferred_schools9"]) && empty($prefSchool8_id)){
				$error_message = 'You can not choose Schools ninth option without choosing an eighth option';
			}


    //declares variable for preferred school option 10 ID and related district ID when preference is by schools
			if (empty($error_message) && !empty($_POST["preferred_schools10"]) && !empty($prefSchool9_id)){
        $prefSchool10_id = filter_input(INPUT_POST, 'preferred_schools10', FILTER_SANITIZE_NUMBER_INT);
        $preferred_schools10_distr = filter_input(INPUT_POST, 'preferred_schools10_distr', FILTER_SANITIZE_NUMBER_INT);
      //ensure option 9 is selected before option 10 can be selected
			}elseif (empty($error_message) && !empty($_POST["preferred_schools10"]) && empty($prefSchool9_id)){
				$error_message = 'You can not choose Schools tenth option without choosing a ninth option';
			}

	//checks to make sure that a preferred option is selected first before creating any records in databases
	if (empty($error_message) && empty($_POST["preferred_province"]) && empty($_POST["preferred_town"]) &&
									empty($_POST["preferred_district1"]) &&
									empty($_POST["preferred_location1"]) &&
									empty($_POST["preferred_schools1"])){
		$error_message = 'Whoa! Please select your preferences for relocation before proceeding';
	}

	//checks for duplicated preferred locations options and returns an error if any are found
	if(count($unique_pref_locations) != count(array_unique($unique_pref_locations))){
	  $error_message = 'Whoa! Preferred Locations options must be all unique! Please recheck your preferred locations options';
	}

	//checks for duplicated preferred schools options and returns an error if any are found
	if(count($unique_pref_schools) != count(array_unique($unique_pref_schools))){
	  $error_message = 'Whoa! Preferred Schools options must all be unique! Please recheck your preferred schools options.';
	}

	//checks if the preferred province is unique from the current province and returns error if not unique
	if (empty($error_message) && !empty($_POST["preferred_province"]) && $_POST["preferred_province"] == $_POST["current_province"]){
		$error_message = 'Whoa! Preferred Province may not be the same as Current Province';
	}

	//checks for duplicated preferred districts options and returns an error if any are found
	if (empty($error_message) && (isset($_POST["preferred_district1"]) && !empty($_POST["preferred_district2"])) &&
			(($_POST["preferred_district1"] == $_POST["preferred_district2"]))){
		$error_message = 'Whoa! Both Preferred District Options may not refer to the same District name';
	}

	//checks if the preferred district is unique from the current district and returns error if not unique
  if (empty($error_message) && (!empty($_POST["preferred_district1"]) || !empty($_POST["preferred_district2"])) &&
			($_POST["preferred_district1"] === $_POST["current_district"] || (!empty($_POST["preferred_district2"]) === $_POST["current_district"]))){ 
		$error_message = 'Whoa! Preferred District may not be the same as Current District';
	}
  
  //the below checks whether for high school options subjects are selected. If not selected an error is thrown.
     if (empty($error_message) && 
      (($_POST["level_taught"] == "High School - Up To O Level")||
      ($_POST["level_taught"] == "High School - Up To A Level"))){
        include_once('inc/selected_subs.php');
        if(empty($subs)){
          $error_message = 'Whoa!  Please select at least one subject that you specialize in teaching.';
        }
      }
    
    $clientRanum = mt_rand(11111, 99999); //used for resetting passwords
    date_default_timezone_set('Africa/Harare');
		$dateCreated = date('d-m-Y H:i:s');
		
		//if status is not set, set default status
		if(empty($_POST['client_status'])){
		    $status = "N";
		}else{
		    $status = $_POST['client_status'];
		}
		$dateMatched = "";
    
   //variable used to determine whether a record is to be updated or inserted
   if(!empty($_POST["client_id"])){
       $client_id = $_POST["client_id"];
   }
	
	//stop the client from submitting a form if they have not agreed to the terms and conditions unless they have admin rights
  if($_SESSION['logged_status'] != 'SU' && $_SESSION['logged_status'] != 'AD'){
	if(empty($_POST['agree']) || $_POST['agree'] != 'agree') {
        $error_message = 'Please indicate that you have read and agree to the Terms and Conditions';
    }
  }
    
    //set subjects id to NULL for Primary level
    if($_POST["level_taught"] == 'Primary - ECD' || $_POST["level_taught"] == 'Primary - General' || $_POST["level_taught"] == 'Primary - Special Needs'){
          $sub1_id = NULL;
          $sub2_id = NULL;
      }
        
if (empty($error_message) && !empty($_POST["preferred_province"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))){
											    
		if($_POST["preferred_province"] != $_POST["preferred_province2"]){
    		include_once('inc/selected_subs.php');
    		$optional = array('mpp_sub1_id'=>$sub1_id, 'mpp_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_prov($prefProv_id, $ecNumber, $levelTaught,$currProv_id,/*$currSch_id,*/ $optional, $client_id);  //inserts the preferred province option in the database for high school clients
		}else{
		    $error_message = 'Whoa!  Preferred provinces options must be unique!'; 
		}
	}elseif(empty($error_message) && !empty($_POST["preferred_province"])){
											    
		if(!empty($_POST["preferred_province2"]) && ($_POST["preferred_province"] != $_POST["preferred_province2"])){
		    $optional = array('mpp_sub1_id'=>$sub1_id, 'mpp_sub2_id'=>$sub2_id); 
		    client_pref_prov($prefProv_id, $ecNumber, $levelTaught,$currProv_id,$currSch_id, $optional, $client_id);  //inserts the preferred province option 2 in the database for primary school clients
		    }else{
		        $error_message = 'Whoa!  Preferred provinces options must be unique!'; 
		    }
	}
	
if (empty($error_message) && !empty($_POST["preferred_province2"]) && !empty($_POST["preferred_province"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))){
											    
		if($_POST["preferred_province"] != $_POST["preferred_province2"]){
    		include_once('inc/selected_subs.php');
    		$optional = array('mpp2_sub1_id'=>$sub1_id, 'mpp2_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_prov2($prefProv2_id, $ecNumber, $levelTaught,$currProv_id,$currSch_id, $optional, $client_id);  //inserts the preferred province option in the database for high school clients
		}else{
		    $error_message = 'Whoa!  Preferred provinces options must be unique!'; 
		}
	}elseif(empty($error_message) && !empty($_POST["preferred_province"]) && !empty($_POST["preferred_province2"])){
											    
		if($_POST["preferred_province"] != $_POST["preferred_province2"]){
		    $optional = array('mpp2_sub1_id'=>$sub1_id, 'mpp2_sub2_id'=>$sub2_id); 
		    client_pref_prov2($prefProv2_id, $ecNumber, $levelTaught,$currProv_id,/*$currSch_id,*/ $optional, $client_id);  //inserts the preferred province option 2 in the database for primary school clients
		    }else{
		    $error_message = 'Whoa!  Preferred provinces options must be unique!'; 
		    }
	}elseif(empty($error_message) && !empty($_POST["preferred_province2"]) && empty($_POST["preferred_province"])){
		$error_message = 'Whoa!  You cannot select provinces option 2 before option 1';
	}

if (empty($error_message) && !empty($_POST["preferred_town"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
										($_POST["level_taught"] == "High School - Up To A Level"))){
											    
		if($_POST["preferred_town"] != $_POST["preferred_town2"] && $_POST["preferred_town"] != $_POST["preferred_town3"] && $_POST["preferred_town"] != $_POST["current_town"]){
    		include_once('inc/selected_subs.php');
    		$optional = array('mpt_sub1_id'=>$sub1_id, 'mpt_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_town($prefTown_id, $ecNumber,$currLoc_id,$currTown_id,$currDistr_id,$currProv_id, $pref_town_province_id, $levelTaught, /*$currSch_id,*/ $optional, $client_id); //inserts the preferred town option in the database for high school clients
    		}else{
		    $error_message = 'Whoa!  Preferred towns options must be unique and different from current town!'; 
		    }
	}elseif(empty($error_message) && !empty($_POST["preferred_town"])){
											    
		if($_POST["preferred_town"] != $prefTown2_id && $_POST["preferred_town"] != $prefTown3_id && $_POST["preferred_town"] != $_POST["current_town"]){
    		$optional = array('mpt_sub1_id'=>$sub1_id, 'mpt_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_town($prefTown_id, $ecNumber,$currLoc_id,$currTown_id,$currDistr_id,$currProv_id, $pref_town_province_id, $levelTaught, /*$currSch_id,*/ $optional, $client_id); //inserts the preferred town option in the database for primary school clients
    		}else{
		    $error_message = 'Whoa!  Preferred towns options must be unique and different from current town!'; 
		    }
	}
	
	if(empty($error_message) && !empty($_POST["preferred_town2"]) && !empty($_POST["preferred_town"])){
											    
		if($_POST["preferred_town2"] != $_POST["preferred_town"] && $_POST["preferred_town2"] != $_POST["current_town"]){
    		$optional = array('mpt2_sub1_id'=>$sub1_id, 'mpt2_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_town2($prefTown2_id, $ecNumber,$currLoc_id,$currTown_id,$currDistr_id,$currProv_id, $pref2_town_province_id, $levelTaught, /*$currSch_id,*/ $optional, $client_id); //inserts the preferred town option 2 in the database
    		}else{
		        $error_message = 'Whoa!  Preferred towns options must be unique and different from current town!'; 
		    }
	}elseif(empty($error_message) && !empty($_POST["preferred_town2"]) && empty($_POST["preferred_town"])){
		$error_message = 'Whoa!  You cannot select towns option 2 before option 1';
	}
	
	if(empty($error_message) && !empty($_POST["preferred_town3"]) && !empty($_POST["preferred_town2"])){
											    
		if($_POST["preferred_town3"] != $_POST["preferred_town"] && $_POST["preferred_town3"] != $_POST["preferred_town2"] && $_POST["preferred_town3"] != $_POST["current_town"]){
    		$optional = array('mpt3_sub1_id'=>$sub1_id, 'mpt3_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_town3($prefTown3_id, $ecNumber,$currLoc_id,$currTown_id,$currDistr_id,$currProv_id, $pref3_town_province_id, $levelTaught, /*$currSch_id,*/ $optional, $client_id); //inserts the preferred town option 3 in the database
		}else{
		    $error_message = 'Whoa!  Preferred towns options must be unique and different from current town!'; 
		}
	}elseif(empty($error_message) && !empty($_POST["preferred_town3"]) && empty($_POST["preferred_town2"])){
		$error_message = 'Whoa!  You cannot select towns option 3 before option 2';
	}

  if (empty($error_message) && !empty($_POST["preferred_district1"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))){
											    
		if($_POST["preferred_district1"] != $_POST["preferred_district2"] && $_POST["preferred_district1"] != $_POST["preferred_district3"] && $_POST["preferred_district1"] != $_POST["preferred_district4"] && $_POST["preferred_district1"] != $_POST["current_district"]){
		    
    		include_once('inc/selected_subs.php');
    		$optional = array('mpd_sub1_id'=>$sub1_id, 'mpd_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_distr1($prefDistr1_id, $ecNumber, $levelTaught, $pref1_province_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id, /*$currSch_id,*/ $optional, $client_id);  //inserts the preferred district option 1 in the database for high school clients
		}else{
		    $error_message = 'Whoa!  Preferred districts options must be unique and different from current district!'; 
		}
		
	}elseif (empty($error_message) && !empty($_POST["preferred_district1"])){
											    
		if($_POST["preferred_district1"] != $_POST["preferred_district2"] && $_POST["preferred_district1"] != $_POST["preferred_district3"] && $_POST["preferred_district1"] != $_POST["preferred_district4"] && $_POST["preferred_district1"] != $_POST["current_district"]){
		$optional = array('mpd_sub1_id'=>$sub1_id, 'mpd_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_distr1($prefDistr1_id, $ecNumber, $levelTaught, $pref1_province_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id, /*$currSch_id,*/ $optional, $client_id); //inserts the preferred district option 1 in the database for primary school clients
		}else{
		    $error_message = 'Whoa!  Preferred districts options must be unique and different from current district!'; 
		}
	}

if (empty($error_message) && !empty($_POST["preferred_district2"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))){
											    
		if($_POST["preferred_district2"] != $_POST["preferred_district1"] && $_POST["preferred_district2"] != $_POST["preferred_district3"] && $_POST["preferred_district2"] != $_POST["preferred_district4"] && $_POST["preferred_district2"] != $_POST["current_district"]){
		include_once('inc/selected_subs.php');
		$optional = array('mpd2_sub1_id'=>$sub1_id, 'mpd2_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_distr2($prefDistr2_id, $ecNumber, $levelTaught, $pref2_province_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id, /*$currSch_id,*/ $optional); //inserts the preferred district option 2 in the database for high school clients
		}else{
		    $error_message = 'Whoa!  Preferred districts options must be unique and different from current district!'; 
		}
	}elseif (empty($error_message) && !empty($_POST["preferred_district2"]) && !empty($_POST["preferred_district1"])){
											    
		if($_POST["preferred_district2"] != $_POST["preferred_district1"] && $_POST["preferred_district2"] != $_POST["preferred_district3"] && $_POST["preferred_district2"] != $_POST["preferred_district4"] && $_POST["preferred_district2"] != $_POST["current_district"]){
    		$optional = array('mpd2_sub1_id'=>$sub1_id, 'mpd2_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_distr2($prefDistr2_id, $ecNumber, $levelTaught, $pref2_province_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id, /*$currSch_id,*/ $optional); //inserts the preferred district option 2 in the database for primary school clients
		}else{
		    $error_message = 'Whoa!  Preferred districts options must be unique and different from current district!'; 
		}
	}elseif (empty($error_message) && !empty($_POST["preferred_district2"]) && empty($_POST["preferred_district1"])){
		$error_message = 'Whoa!  You cannot select option 2 before option 1';
	}
	
	if (empty($error_message) && !empty($_POST["preferred_district3"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))){
											    
		if($_POST["preferred_district3"] != $_POST["preferred_district1"] && $_POST["preferred_district3"] != $_POST["preferred_district2"] && $_POST["preferred_district3"] != $_POST["preferred_district4"] && $_POST["preferred_district3"] != $_POST["current_district"]){
    		include_once('inc/selected_subs.php');
    		$optional = array('mpd3_sub1_id'=>$sub1_id, 'mpd3_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_distr3($prefDistr3_id, $ecNumber, $levelTaught, $pref3_province_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id, /*$currSch_id,*/ $optional); //inserts the preferred district option 3 in the database for high school clients
		}else{
		    $error_message = 'Whoa!  Preferred districts options must be unique and different from current district!'; 
		}
	}elseif (empty($error_message) && !empty($_POST["preferred_district3"]) && !empty($_POST["preferred_district2"])){
											    
		if($_POST["preferred_district3"] != $_POST["preferred_district1"] && $_POST["preferred_district3"] != $_POST["preferred_district2"] && $_POST["preferred_district3"] != $_POST["preferred_district4"] && $_POST["preferred_district3"] != $_POST["current_district"]){
    		$optional = array('mpd3_sub1_id'=>$sub1_id, 'mpd3_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_distr3($prefDistr3_id, $ecNumber, $levelTaught, $pref3_province_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id, /*$currSch_id,*/ $optional); //inserts the preferred district option 3 in the database for primary school clients
		}else{
		    $error_message = 'Whoa!  Preferred districts options must be unique and different from current district!'; 
		}
	}elseif (empty($error_message) && !empty($_POST["preferred_district3"]) && empty($_POST["preferred_district2"])){
		$error_message = 'Whoa!  You cannot select option 3 before option 2';
	}
	
	if (empty($error_message) && !empty($_POST["preferred_district4"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))){
											    
		if($_POST["preferred_district4"] != $_POST["preferred_district1"] && $_POST["preferred_district4"] != $_POST["preferred_district2"] && $_POST["preferred_district4"] != $_POST["preferred_district3"] && $_POST["preferred_district4"] != $_POST["current_district"]){
    		include_once('inc/selected_subs.php');
    		$optional = array('mpd4_sub1_id'=>$sub1_id, 'mpd4_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_distr4($prefDistr4_id, $ecNumber, $levelTaught, $pref4_province_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id, /*$currSch_id,*/ $optional); //inserts the preferred district option 4 in the database for high school clients
		}else{
		    $error_message = 'Whoa!  Preferred districts options must be unique and different from current district!'; 
		}
	}elseif(empty($error_message) && !empty($_POST["preferred_district4"]) && !empty($_POST["preferred_district3"])){
											    
		if($_POST["preferred_district4"] != $_POST["preferred_district1"] && $_POST["preferred_district4"] != $_POST["preferred_district2"] && $_POST["preferred_district4"] != $_POST["preferred_district3"] && $_POST["preferred_district4"] != $_POST["current_district"]){
    		$optional = array('mpd4_sub1_id'=>$sub1_id, 'mpd4_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_distr4($prefDistr4_id, $ecNumber, $levelTaught, $pref4_province_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id, /*$currSch_id,*/ $optional); //inserts the preferred district option 4 in the database for primary school clients
		}else{
		    $error_message = 'Whoa!  Preferred districts options must be unique and different from current district!'; 
		}
	}elseif (empty($error_message) && !empty($_POST["preferred_district4"]) && empty($_POST["preferred_district3"])){
		$error_message = 'Whoa!  You cannot select option 4 before option 3';
	}
	
if (empty($error_message) && !empty($_POST["preferred_location1"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))){
											    
		if($_POST["preferred_location1"] != $_POST["preferred_location2"] && $_POST["preferred_location1"] != $_POST["preferred_location3"] && $_POST["preferred_location1"] != $_POST["preferred_location4"] && $_POST["preferred_location1"] != $_POST["preferred_location5"] && $_POST["preferred_location1"] != $_POST["current_location"]){
        	include_once('inc/selected_subs.php');
        	$optional = array('mpl_sub1_id'=>$sub1_id, 'mpl_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
        	client_pref_loc1($prefLoc1_id, $ecNumber, $levelTaught, $pref1_loc_province_id, $pref1_distr_id, $pref1_town_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id,/*$currSch_id,*/ $optional, $client_id); //inserts the preferred location option 1 in the database for high school clients
		}else{
		    $error_message = 'Whoa!  Preferred districts options must be unique and different from current location!'; 
		}
	}else if (empty($error_message) && !empty($_POST["preferred_location1"])){
											    
		if($_POST["preferred_location1"] != $_POST["preferred_location2"] && $_POST["preferred_location1"] != $_POST["preferred_location3"] && $_POST["preferred_location1"] != $_POST["preferred_location4"] && $_POST["preferred_location1"] != $_POST["preferred_location5"] && $_POST["preferred_location1"] != $_POST["current_location"]){
    		$optional = array('mpl_sub1_id'=>$sub1_id, 'mpl_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_loc1($prefLoc1_id, $ecNumber, $levelTaught, $pref1_loc_province_id, $pref1_distr_id, $pref1_town_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id,/*$currSch_id,*/ $optional, $client_id); //inserts the preferred location option 1 in the database for primary school clients
		}else{
		    $error_message = 'Whoa!  Preferred locations options must be unique and different from current location!'; 
		}
	}

if (empty($error_message) && !empty($_POST["preferred_location2"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))){
											    
		if($_POST["preferred_location2"] != $_POST["preferred_location1"] && $_POST["preferred_location2"] != $_POST["preferred_location3"] && $_POST["preferred_location2"] != $_POST["preferred_location4"] && $_POST["preferred_location2"] != $_POST["preferred_location5"] && $_POST["preferred_location2"] != $_POST["current_location"]){
    		include ('inc/selected_subs.php');
    		$optional = array('mpl2_sub1_id'=>$sub1_id, 'mpl2_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_loc2($prefLoc2_id, $ecNumber, $levelTaught, $pref2_loc_province_id, $pref2_distr_id, $pref2_town_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id,/*$currSch_id,*/ $optional); //inserts the preferred location option 2 in the database for high school clients
		}else{
		    $error_message = 'Whoa!  Preferred locations options must be unique and different from current location!'; 
		}
	}else if (empty($error_message) && !empty($_POST["preferred_location2"]) && !empty($_POST["preferred_location1"])){
											    
		if($_POST["preferred_location2"] != $_POST["preferred_location1"] && $_POST["preferred_location2"] != $_POST["preferred_location3"] && $_POST["preferred_location2"] != $_POST["preferred_location4"] && $_POST["preferred_location2"] != $_POST["preferred_location5"] && $_POST["preferred_location2"] != $_POST["current_location"]){
    		$optional = array('mpl2_sub1_id'=>$sub1_id, 'mpl2_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_loc2($prefLoc2_id, $ecNumber, $levelTaught, $pref2_loc_province_id, $pref2_distr_id, $pref2_town_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id,/*$currSch_id,*/ $optional); //inserts the preferred location option 2 in the database for primary school clients
		}else{
		    $error_message = 'Whoa!  Preferred locations options must be unique and different from current location!'; 
		}
	}elseif (empty($error_message) && !empty($_POST["preferred_location2"]) && empty($_POST["preferred_location1"])){
		$error_message = 'Whoa! You cannot select option 2 before option 1';
	}

if (empty($error_message) && !empty($_POST["preferred_location3"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))){
											    
		if($_POST["preferred_location3"] != $_POST["preferred_location1"] && $_POST["preferred_location3"] != $_POST["preferred_location2"] && $_POST["preferred_location3"] != $_POST["preferred_location4"] && $_POST["preferred_location3"] != $_POST["preferred_location5"] && $_POST["preferred_location3"] != $_POST["current_location"]){
    		include_once ('inc/selected_subs.php');
    		$optional = array('mpl3_sub1_id'=>$sub1_id, 'mpl3_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_loc3($prefLoc3_id, $ecNumber, $levelTaught, $pref3_loc_province_id, $pref3_distr_id, $pref3_town_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id,/*$currSch_id,*/ $optional); //inserts the preferred location 3 option in the database for high school clients
		}else{
		    $error_message = 'Whoa!  Preferred locations options must be unique and different from current location!'; 
		}
	}elseif(empty($error_message) && !empty($_POST["preferred_location3"]) && !empty($_POST["preferred_location2"])){
											    
		if($_POST["preferred_location3"] != $_POST["preferred_location1"] && $_POST["preferred_location3"] != $_POST["preferred_location2"] && $_POST["preferred_location3"] != $_POST["preferred_location4"] && $_POST["preferred_location3"] != $_POST["preferred_location5"] && $_POST["preferred_location3"] != $_POST["current_location"]){
    		$optional = array('mpl3_sub1_id'=>$sub1_id, 'mpl3_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_loc3($prefLoc3_id, $ecNumber, $levelTaught, $pref3_loc_province_id, $pref3_distr_id, $pref3_town_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id,/*$currSch_id,*/ $optional); //inserts the preferred location option 3 in the database for high school clients
		}else{
		    $error_message = 'Whoa!  Preferred locations options must be unique and different from current location!'; 
		}
	}elseif (empty($error_message) && !empty($_POST["preferred_location3"]) && empty($_POST["preferred_location2"])){
		$error_message = 'Whoa! You cannot select option 3 before option 2';
	}
	
	if (empty($error_message) && !empty($_POST["preferred_location4"]) && !empty($_POST["preferred_location3"])){
											    
		if($_POST["preferred_location4"] != $_POST["preferred_location1"] && $_POST["preferred_location4"] != $_POST["preferred_location2"] && $_POST["preferred_location4"] != $_POST["preferred_location3"] && $_POST["preferred_location4"] != $_POST["preferred_location5"] && $_POST["preferred_location4"] != $_POST["current_location"]){
    		$optional = array('mpl4_sub1_id'=>$sub1_id, 'mpl4_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_loc4($prefLoc4_id, $ecNumber, $levelTaught, $pref4_loc_province_id, $pref4_distr_id, $pref4_town_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id,/*$currSch_id,*/ $optional); //inserts the preferred location option 4 in the database for high school clients
		}else{
		    $error_message = 'Whoa!  Preferred locations options must be unique and different from current location!'; 
		}
	}elseif (empty($error_message) && !empty($_POST["preferred_location4"]) && empty($_POST["preferred_location3"])){
		$error_message = 'Whoa! You cannot select option 4 before option 3';
	}
	
	if (empty($error_message) && !empty($_POST["preferred_location5"]) && !empty($_POST["preferred_location4"])){
											    
		if($_POST["preferred_location5"] != $_POST["preferred_location1"] && $_POST["preferred_location5"] != $_POST["preferred_location2"] && $_POST["preferred_location5"] != $_POST["preferred_location3"] && $_POST["preferred_location5"] != $_POST["preferred_location4"] && $_POST["preferred_location5"] != $_POST["current_location"]){
    		$optional = array('mpl5_sub1_id'=>$sub1_id, 'mpl5_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
    		client_pref_loc5($prefLoc5_id, $ecNumber, $levelTaught, $pref5_loc_province_id, $pref5_distr_id, $pref5_town_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id,/*$currSch_id,*/ $optional); //inserts the preferred location option 5 in the database for high school clients
		}else{
		    $error_message = 'Whoa!  Preferred locations options must be unique and different from current location!'; 
		}
	}elseif (empty($error_message) && !empty($_POST["preferred_location5"]) && empty($_POST["preferred_location4"])){
		$error_message = 'Whoa! You cannot select option 5 before option 4';
	}
	
	//the below checks to ensure that every posted preferred district province has a corresponding preferred district
  if(!empty($pref1_province_id) && empty($prefDistr1_id) ||
     !empty($pref2_province_id) && empty($prefDistr2_id)){
    $error_message = 'Whoa! Please ensure every selected preferred district province has a corresponding preferred district ELSE Deselect the district province.';
  }
  /*
  //the below checks to ensure that every posted preferred location town has a corresponding preferred location
  if(!empty($pref1_town_id) && empty($prefLoc1_id) ||
     !empty($pref2_town_id) && empty($prefLoc2_id) ||
     !empty($pref3_town_id) && empty($prefLoc3_id) ){
    $error_message = 'Whoa! Please ensure every selected preferred location town has a corresponding preferred location ELSE Deselect the location town.';
  }
  */
  //the below checks to ensure that every posted preferred district has a corresponding preferred school
  if (empty($_POST["preferred_location1"])){ //below runs when preferences are not by locations
      if((!empty($pref1_distr_id) && empty($preferred_schools1)) ||
         (!empty($pref2_distr_id) && empty($preferred_schools2)) ||
         (!empty($pref3_distr_id) && empty($preferred_schools3)) ||
         (!empty($pref4_distr_id) && empty($preferred_schools4)) ||
         (!empty($pref5_distr_id) && empty($preferred_schools5)) ||
         (!empty($pref6_distr_id) && empty($preferred_schools6)) ||
         (!empty($pref7_distr_id) && empty($preferred_schools7)) ||
         (!empty($pref8_distr_id) && empty($preferred_schools8)) ||
         (!empty($pref9_distr_id) && empty($preferred_schools9)) ||
         (!empty($pref10_distr_id) && empty($preferred_schools10))){
        $error_message = 'Whoa! Please ensure every selected preferred district has a corresponding preferred school ELSE Deselect the district.';
      }   
  }

if (empty($error_message) && !empty($_POST["preferred_schools1"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))){ 
		include_once('inc/selected_subs.php');  //included if level taught is high school and there hasn't been any error in previous procedures
    $optional = array('mps_sub1_id'=>$sub1_id, 'mps_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch1($prefSchool1_id, $ecNumber, $levelTaught, $pref1_distr_id, $currSch_id, $optional, $client_id);  //creates a record in the 'match_pref_schools' database
	}else if (empty($error_message) && !empty($_POST["preferred_schools1"])){
    $optional = array('mps_sub1_id'=>$sub1_id, 'mps_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch1($prefSchool1_id, $ecNumber, $levelTaught, $pref1_distr_id, $currSch_id, $optional, $client_id);  //creates a record in the 'match_pref_schools' database
	}

if (empty($error_message) && !empty($_POST["preferred_schools2"]) && (($_POST["level_taught"] == "High School - Up To O Level") ||
											($_POST["level_taught"] == "High School - Up To A Level"))
											&& (!empty($_POST["preferred_schools1"]))){
		include_once('inc/selected_subs.php');
    $optional = array('mps2_sub1_id'=>$sub1_id, 'mps2_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch2($prefSchool2_id, $ecNumber, $levelTaught, $pref2_distr_id, $currSch_id, $optional); //inserts the preferred school option 1 in the database for high school clients
	}else if (empty($error_message) && !empty($_POST["preferred_schools2"]) && !empty($_POST["preferred_schools1"])){
    $optional = array('mps2_sub1_id'=>$sub1_id, 'mps2_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch2($prefSchool2_id, $ecNumber, $levelTaught, $pref2_distr_id, $currSch_id, $optional); //inserts the preferred school option 1 in the database for primary school clients
	}elseif (empty($error_message) && !empty($_POST["preferred_schools2"]) && empty($_POST["preferred_schools1"])){
		$error_message = 'Whoa!  You cannot select option 2 before option 1';
	}

if (empty($error_message) && !empty($_POST["preferred_schools3"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))
											&& (!empty($_POST["preferred_schools2"]))){
		include_once('inc/selected_subs.php');
    $optional = array('mps3_sub1_id'=>$sub1_id, 'mps3_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch3($prefSchool3_id, $ecNumber, $levelTaught, $pref3_distr_id, $currSch_id, $optional); //inserts the preferred school option 3 in the database for high school clients
	}else if (empty($error_message) && !empty($_POST["preferred_schools3"]) && !empty($_POST["preferred_schools2"])){
    $optional = array('mps3_sub1_id'=>$sub1_id, 'mps3_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
	client_pref_sch3($prefSchool3_id, $ecNumber, $levelTaught, $pref3_distr_id, $currSch_id, $optional); //inserts the preferred school option 3 in the database for primary school clients
	}elseif (empty($error_message) && !empty($_POST["preferred_schools3"]) && empty($_POST["preferred_schools2"])){
		$error_message = 'Whoa!  You cannot select option 3 before option 2';
	}

if (empty($error_message) && !empty($_POST["preferred_schools4"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))
											 && (!empty($_POST["preferred_schools3"]))){
		include_once('inc/selected_subs.php');
    $optional = array('mps4_sub1_id'=>$sub1_id, 'mps4_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch4($prefSchool4_id, $ecNumber, $levelTaught, $pref4_distr_id, $currSch_id, $optional); //inserts the preferred school option 4 in the database for high school clients
	}else if (empty($error_message) && !empty($_POST["preferred_schools4"]) && !empty($_POST["preferred_schools3"])){
    $optional = array('mps4_sub1_id'=>$sub1_id, 'mps4_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch4($prefSchool4_id, $ecNumber, $levelTaught, $pref4_distr_id, $currSch_id, $optional); //inserts the preferred school option 4 in the database for primary school clients
	}elseif (empty($error_message) && !empty($_POST["preferred_schools4"]) && empty($_POST["preferred_schools3"])){
		$error_message = 'Whoa!  You cannot select option 4 before option 3';
	}

if (empty($error_message) && !empty($_POST["preferred_schools5"]) && ($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level")
											 && (!empty($_POST["preferred_schools4"]))){
		include_once('inc/selected_subs.php');
    $optional = array('mps5_sub1_id'=>$sub1_id, 'mps5_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch5($prefSchool5_id, $ecNumber, $levelTaught, $pref5_distr_id, $currSch_id, $optional); //inserts the preferred school option 5 in the database for high school clients
	}else if (empty($error_message) && !empty($_POST["preferred_schools5"]) && !empty($_POST["preferred_schools4"])){
    $optional = array('mps5_sub1_id'=>$sub1_id, 'mps5_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch5($prefSchool5_id, $ecNumber, $levelTaught, $pref5_distr_id, $currSch_id, $optional); //inserts the preferred school option 5 in the database for primary school clients
	}elseif (empty($error_message) && !empty($_POST["preferred_schools5"]) && empty($_POST["preferred_schools4"])){
		$error_message = 'Whoa!  You cannot select option 5 before option 4';
	}

if (empty($error_message) && !empty($_POST["preferred_schools6"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))
											 && (!empty($_POST["preferred_schools5"]))){
		include_once('inc/selected_subs.php');
    $optional = array('mps6_sub1_id'=>$sub1_id, 'mps6_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch6($prefSchool6_id, $ecNumber, $levelTaught, $pref6_distr_id, $currSch_id, $optional); //inserts the preferred school option 6 in the database for high school clients
	}else if (empty($error_message) && !empty($_POST["preferred_schools6"]) && !empty($_POST["preferred_schools5"])){
    $optional = array('mps6_sub1_id'=>$sub1_id, 'mps6_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch6($prefSchool6_id, $ecNumber, $levelTaught, $pref6_distr_id, $currSch_id, $optional); //inserts the preferred school option 6 in the database for primary school clients
	}elseif (empty($error_message) && !empty($_POST["preferred_schools6"]) && empty($_POST["preferred_schools5"])){
		$error_message = 'Whoa!  You cannot select option 6 before option 5';
	}

if (empty($error_message) && !empty($_POST["preferred_schools7"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))
											 && (!empty($_POST["preferred_schools6"]))){
		include_once('inc/selected_subs.php');
    $optional = array('mps7_sub1_id'=>$sub1_id, 'mps7_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch7($prefSchool7_id, $ecNumber, $levelTaught, $pref7_distr_id, $currSch_id, $optional); //inserts the preferred school option 7 in the database for high school clients
	}else if (empty($error_message) && !empty($_POST["preferred_schools7"]) && !empty($_POST["preferred_schools6"])){
    $optional = array('mps7_sub1_id'=>$sub1_id, 'mps7_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch7($prefSchool7_id, $ecNumber, $levelTaught, $pref7_distr_id, $currSch_id, $optional); //inserts the preferred school option 7 in the database for primary school clients
	}elseif (!empty($_POST["preferred_schools7"]) && empty($_POST["preferred_schools6"])){
		$error_message = 'Whoa!  You cannot select option 7 before option 6';
	}

if (empty($error_message) && !empty($_POST["preferred_schools8"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))
											 && (!empty($_POST["preferred_schools7"]))){
		include_once('inc/selected_subs.php');
    $optional = array('mps8_sub1_id'=>$sub1_id, 'mps8_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch8($prefSchool8_id, $ecNumber, $levelTaught, $pref8_distr_id, $currSch_id, $optional); //inserts the preferred school option 8 in the database for high school clients
	}else if (empty($error_message) && !empty($_POST["preferred_schools8"]) && !empty($_POST["preferred_schools7"])){
    $optional = array('mps8_sub1_id'=>$sub1_id, 'mps8_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch8($prefSchool8_id, $ecNumber, $levelTaught, $pref8_distr_id, $currSch_id, $optional); //inserts the preferred school option 8 in the database for primary school clients
	}elseif (empty($error_message) && !empty($_POST["preferred_schools8"]) && empty($_POST["preferred_schools7"])){
		$error_message = 'Whoa!  You cannot select option 8 before option 7';
	}

if (empty($error_message) && !empty($_POST["preferred_schools9"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))
											 && (!empty($_POST["preferred_schools8"]))){
		include_once('inc/selected_subs.php');
    $optional = array('mps9_sub1_id'=>$sub1_id, 'mps9_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch9($prefSchool9_id, $ecNumber, $levelTaught, $pref9_distr_id, $currSch_id, $optional); //inserts the preferred school option 9 in the database for high school clients
	}else if (empty($error_message) && !empty($_POST["preferred_schools9"]) && !empty($_POST["preferred_schools8"])){
    $optional = array('mps9_sub1_id'=>$sub1_id, 'mps9_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_pref_sch9($prefSchool9_id, $ecNumber, $levelTaught, $pref9_distr_id, $currSch_id, $optional); //inserts the preferred school option 9 in the database for primary school clients
	}elseif (empty($error_message) && !empty($_POST["preferred_schools9"]) && empty($_POST["preferred_schools8"])){
		$error_message = 'Whoa!  You cannot select option 9 before option 8';
	}

if (empty($error_message) && !empty($_POST["preferred_schools10"]) && (($_POST["level_taught"] == "High School - Up To O Level")||
											($_POST["level_taught"] == "High School - Up To A Level"))
											 && (!empty($_POST["preferred_schools9"]))){
		include_once('inc/selected_subs.php');
    $optional = array('mps10_sub1_id'=>$sub1_id, 'mps10_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
	client_pref_sch10($prefSchool10_id, $ecNumber, $levelTaught, $pref10_distr_id, $currSch_id, $optional); //inserts the preferred school option 10 in the database for high school clients
	}else if (empty($error_message) && !empty($_POST["preferred_schools10"]) && !empty($_POST["preferred_schools9"])){
    $optional = array('mps10_sub1_id'=>$sub1_id, 'mps10_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
	client_pref_sch10($prefSchool10_id, $ecNumber, $levelTaught, $pref10_distr_id, $currSch_id, $optional); //inserts the preferred school option 10 in the database for primary school clients
	}elseif (empty($error_message) && !empty($_POST["preferred_schools10"]) && empty($_POST["preferred_schools9"])){
		$error_message = 'Whoa!  You cannot select option 10 before option 9';
	}
  
	//inserts a record into the current schools database if there is no error in the form
	if (empty($error_message)){
    $optional = array('mcs_sub1_id'=>$sub1_id, 'mcs_sub2_id'=>$sub2_id);  //creates an array to hold optional arguments which are only relevant when the level taught is high school
		client_curr_sch($ecNumber, $currSch_id, $currDistr_id, $currProv_id, $currTown_id, $currLoc_id, $levelTaught, $optional, $prefSchool1_id, $prefSchool2_id, $prefSchool3_id, $prefSchool4_id, $prefSchool5_id, $prefSchool6_id, $prefSchool7_id, $prefSchool8_id, $prefSchool9_id, $prefSchool10_id, $prefLoc1_id, $prefLoc2_id, $prefLoc3_id, $prefLoc4_id, $prefLoc5_id, $prefTown_id, $prefTown2_id, $prefTown3_id, $prefDistr1_id, $prefDistr2_id, $prefDistr3_id, $prefDistr4_id, $prefProv_id, $prefProv2_id);
		}
    
    //inserts a client into the clients database if there is no error in the form
	if (empty($error_message)){
		create_client($ecNumber, 
            $client_id,     //used when detrmine whether to update or insert
						$userFirstName, 
						$userLastName, 
						$gender, 
						$mobileNumber, 
						$userEmail, 
						$userPassword, 
						$levelTaught,
                        $clientAgent,
            $clientRanum,
						$dateCreated,
						$status, 
            $dateMatched);
  }
		
	//if registration or update is successful, redirect to the logout page
	if (empty($error_message)){
	    //sends a welcome SMS to newly registered clients..
	    if(empty($client_ec_no) && empty($client_id)){
            $sender = 'SwopMatch';
            
            //for bulkSMSweb only
            $webtoken = '7e51fe1d78da5158df9aeeb5b029443a';
            
            //$body0 = 'A warm welcome '.$userFirstName.'!! You have successfully registered on our SwopMatch Handler platform! Please always check for SMSs from SwopMatch for any updates on our journey together. Where action is required on your part; please act within the time limits given. Together we will surely get there..';
            
            $body0 = 'A warm welcome '.$userFirstName.'!! Your registration has been submitted for review.  Please pay your registration fee of $2.00 using our PatchIT Ecocash Biller Code 204320 and your A/C # ('.$ecNumber.') within 48hrs and your profile will be activated. Together we will surely get there..';
            
            $body = urlencode($body0);
             
            //check whether the mobile # is a netone number and declare the username variable accordingly
            if(substr($mobileNumber, 0, 3) === '071'){
                $user = "263775263810";
            }else{
                $user = "patch";
            }
            
            $pass = "patchit";
            
            //bulkSMSweb service
            //$url  = "http://portal.bulksmsweb.com/index.php?app=ws&u=".$user."&h=".$webtoken."&op=pv&to=".$mobile."&msg=".$body;
            
            //if mobile # is for netone declare url for etxt else declare for bluedots
            if(substr($mobileNumber, 0, 3) === '071'){
                 $url = "http://etext.co.zw/sendsms.php?user=".$user."&password=".$pass."&mobile=".$mobile."&senderid=".$sender."&message=".$body;
            }else{
              $url = "http://api.bluedotsms.com/api/mt/SendSMS?user=".$user."&password=".$pass."&senderid=".$sender."&channel=Normal&DCS=0&flashsms=0&number=".$mobile."&text=".$body;
            }
            
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_exec($ch);
                curl_close($ch);
	    }elseif(!empty($prev_status) && $prev_status === 'N' && $_POST['client_status'] === 'A'){
            $sender = 'SwopMatch';
            
            //for bulkSMSweb only
            $webtoken = '7e51fe1d78da5158df9aeeb5b029443a';
            
            $body0 = 'Congratulations '.$userFirstName.'!! Your registration has been approved and activated! Please always check for SMSs from SwopMatch for updates on our journey together. Where you are required to act; please act within the time limits given. Together we will surely get there..';
            
            $body = urlencode($body0);
             
            //check whether the mobile # is a netone number and declare the username variable accordingly
            if(substr($mobileNumber, 0, 3) === '071'){
                $user = "263775263810";
            }else{
                $user = "patch";
            }
            
            $pass = "patchit";
            
            //bulkSMSweb service
            //$url  = "http://portal.bulksmsweb.com/index.php?app=ws&u=".$user."&h=".$webtoken."&op=pv&to=".$mobile."&msg=".$body;
            
            //if mobile # is for netone declare url for etxt else declare for bluedots
            if(substr($mobileNumber, 0, 3) === '071'){
                 $url = "http://etext.co.zw/sendsms.php?user=".$user."&password=".$pass."&mobile=".$mobile."&senderid=".$sender."&message=".$body;
            }else{
              $url = "http://api.bluedotsms.com/api/mt/SendSMS?user=".$user."&password=".$pass."&senderid=".$sender."&channel=Normal&DCS=0&flashsms=0&number=".$mobile."&text=".$body;
            }
            
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_exec($ch);
                curl_close($ch);
	    }elseif(!empty($prev_status) && $prev_status === 'N' && $_POST['client_status'] === 'R'){
            $sender = 'SwopMatch';
            
            //for bulkSMSweb only
            $webtoken = '7e51fe1d78da5158df9aeeb5b029443a';
            
            $body0 = $userFirstName.' we regret to inform you that your registration failed to meet the validity criteria acceptable for all new applicants and was therefore rejected. If need be, please meticulously go through the registration process again. Together we will surely get there..';
            
            $body = urlencode($body0);
             
            //check whether the mobile # is a netone number and declare the username variable accordingly
            if(substr($mobileNumber, 0, 3) === '071'){
                $user = "263775263810";
            }else{
                $user = "patch";
            }
            
            $pass = "patchit";
            
            //bulkSMSweb service
            //$url  = "http://portal.bulksmsweb.com/index.php?app=ws&u=".$user."&h=".$webtoken."&op=pv&to=".$mobile."&msg=".$body;
            
            //if mobile # is for netone declare url for etxt else declare for bluedots
            if(substr($mobileNumber, 0, 3) === '071'){
                 $url = "http://etext.co.zw/sendsms.php?user=".$user."&password=".$pass."&mobile=".$mobile."&senderid=".$sender."&message=".$body;
            }else{
              $url = "http://api.bluedotsms.com/api/mt/SendSMS?user=".$user."&password=".$pass."&senderid=".$sender."&channel=Normal&DCS=0&flashsms=0&number=".$mobile."&text=".$body;
            }
            
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_exec($ch);
                curl_close($ch);
	    }
	    //redirects to the logout page after user edits own record else redirects to admin client area
	    if($_SESSION['logged_status'] == 'SU' || $_SESSION['logged_status'] == 'AD'){
            header("location: Account_manage.php?id=$ecNumber");
	    }else{
            header("location: logout.php");
	    }
	}
}

//the below creates variables containing details of a client called for possible updating

if(isset($_GET['id'])){ //checks if the user wants to view a profile
    if(($_SESSION['logged_in'] != isset($_SESSION['ec_number']) ||
        $_GET['id'] != $_SESSION['logged_in']) && ($_SESSION['logged_status'] != 'SU' && $_SESSION['logged_status'] != 'AD')){ //checks if the $_GET['id'] is for the client who logged in before displaying any information  
    
        $error_message = "Whoa! You must be logged in as ".'"'.$_GET['id'].'"'." for you to view this profile!";
    }else{ 
    list($client_id,
          $client_ec_no,
          $client_first_name,
          $client_last_name,
          $client_sex,
          $client_mobile_no,
          $client_email,
          $client_status,
          //$client_password,
          $client_level_taught,
          $client_agent_ac_no,
          $match_raw_exp_date,
          $mpp_id,
          $mpp_province_id,
          $mpp2_id,
          $mpp2_province_id,
          $mpd_id,
          $mpd_distr_id,
          $mpd1_distr_province_id,
          $mpd2_id,
          $mpd2_distr_id,
          $mpd2_distr_province_id,
          $mpd3_id,
          $mpd3_distr_id,
          $mpd3_distr_province_id,
          $mpd4_id,
          $mpd4_distr_id,
          $mpd4_distr_province_id,
          $mpt_id,
          $mpt_town_id,
          $mpt_town_province_id,
          $mpt2_id,
          $mpt2_town_id,
          $mpt2_town_province_id,
          $mpt3_id,
          $mpt3_town_id,
          $mpt3_town_province_id,
          $mpl_id,
          $mpl_loc_id,
          $mpl_loc_distr_id,
          $mpl2_id,
          $mpl2_loc_id,
          $mpl2_loc_distr_id,
          $mpl3_id,
          $mpl3_loc_id,
          $mpl3_loc_distr_id,
          $mpl4_id,
          $mpl4_loc_id,
          $mpl4_loc_distr_id,
          $mpl5_id,
          $mpl5_loc_id,
          $mpl5_loc_distr_id,
          $mps_id,
          $mps_school_id,
          $mps_school_distr_id,
          $mps2_id,
          $mps2_school_id,
          $mps2_school_distr_id,
          $mps3_id,
          $mps3_school_id,
          $mps3_school_distr_id,
          $mps4_id,
          $mps4_school_id,
          $mps4_school_distr_id,
          $mps5_id,
          $mps5_school_id,
          $mps5_school_distr_id,
          $mps6_id,
          $mps6_school_id,
          $mps6_school_distr_id,
          $mps7_id,
          $mps7_school_id,
          $mps7_school_distr_id,
          $mps8_id,
          $mps8_school_id,
          $mps8_school_distr_id,
          $mps9_id,
          $mps9_school_id,
          $mps9_school_distr_id,
          $mps10_id,
          $mps10_school_id,
          $mps10_school_distr_id) = get_client(filter_input(INPUT_GET,'id', FILTER_SANITIZE_STRING));
    }
}

        //re-format reservation expiration date
        if(!empty($match_raw_exp_date)){
            //remove hours, minutes, and seconds
            $match_raw_exp_date = substr($match_raw_exp_date, 0, 10);
            $raw_d = date_create_from_format('d-m-Y', $match_raw_exp_date);
            //make the format more readable
            $match_exp_date = date_format($raw_d, 'd-M-Y');
        }
  
  
        //the below creates variables containing the current details of a client extracted from the 'match_current_schools' database
        
        if (isset($_GET['id']) && ($_SESSION['logged_status'] == 'SU' || $_SESSION['logged_status'] == 'AD')){ //checks if the administrator is logged in
              
          $mcs_match_ec_no = $_GET['id'];
            foreach($mcs as $key=>$value){                                             //starts a check to see if the client has registered based on preferred provinces
              if(in_array($mcs_match_ec_no,$value)){                                       //extracts province and taught subjects ids to be used to get actual related names
                  $match_sub1_id = $value['mcs_sub1_id'];
                  $match_sub2_id = $value['mcs_sub2_id'];
                  $curr_school_id = $value['mcs_school_id'];
                  $curr_province_id = $value['mcs_province_id'];
                  $curr_distr_id = $value['mcs_distr_id'];
                  $curr_town_id = $value['mcs_town_id'];
                  $curr_loc_id = $value['mcs_loc_id'];
            }
          }
        }elseif(isset($_GET['id']) && $_GET['id'] == 
            $_SESSION['logged_in'] && $_SESSION['logged_in'] == 
            isset($_SESSION['ec_number'])){ //checks if the $_GET['id'] is for the client who logged in before displaying any information
              
          $mcs_match_ec_no = $_GET['id'];
            foreach($mcs as $key=>$value){                                             //starts a check to see if the client has registered based on preferred provinces
              if(in_array($mcs_match_ec_no,$value)){                                       //extracts province and taught subjects ids to be used to get actual related names
                  $match_sub1_id = $value['mcs_sub1_id'];
                  $match_sub2_id = $value['mcs_sub2_id'];
                  $curr_school_id = $value['mcs_school_id'];
                  $curr_province_id = $value['mcs_province_id'];
                  $curr_distr_id = $value['mcs_distr_id'];
                  $curr_town_id = $value['mcs_town_id'];
                  $curr_loc_id = $value['mcs_loc_id'];
            }
          }
        }
        
        //set the page title when and when not logged in       
        if(!empty($client_ec_no)){
        		$pageTitle = 'SwopMatch Handler | '.$client_first_name.' Logged In';
        }else{
            $pageTitle = 'SwopMatch Handler | Register';
        }
        include 'inc/header.php';
        /*
        echo '<pre>';
       //print_r( $_POST);
        echo '</pre>';
        echo '<pre>';
        //var_dump($territory_id);
        echo '</pre>';
        if(!empty($_SESSION['logged_status'])  &&
        $_SESSION['logged_status'] == 'SU'){
        echo '<pre>';
       print_r($matched_schools);
        echo '</pre>';
        
       }*/
        
?>
<div class="row" style="z-index: 1">
    <div class="col-1">
    </div>
        <div class="col-10">
            <div class="container">
                <div class="jumbotron my-1 py-3">
                <?php if(!empty($error_message)){
                          echo '<div class = "alert text-center">';
                          echo '<span class = "err">'.$error_message.'</span>';
                          echo '</div>';
                          goto resume;
                    }elseif(!empty($client_ec_no) && (isset($_GET['id']) && $_GET['id'] != $_SESSION['logged_in']) && ($_SESSION['logged_status'] == 'SU' || $_SESSION['logged_status'] == 'AD')){
                           echo '<h1 id="welcome" class="text-center display-5 brand-name"><b> '.$client_first_name.' '.$client_last_name;
                        }elseif(!empty($client_ec_no) && (isset($_GET['id']) && $_GET['id'] == $_SESSION['logged_in']) && ($_SESSION['logged_status'] == 'SU' || $_SESSION['logged_status'] == 'AD')){
                              echo '<h1 id="welcome" class="text-center display-5 brand-name"><b>Administrator '.$client_first_name;
                        }elseif(!empty($client_ec_no)){
                              echo '<h1 id="welcome" class="text-center display-5 brand-name"><b> Welcome Back '.$client_first_name;
                        }else{
                          echo '<h1 id="register" class="text-center display-5 brand-name"><b>Register';
                        } 
                        if(empty($error_message)){
                          echo '!';
                        }
                        if(!empty($client_ec_no) && !empty($client_id) && $client_status == 'A'){
                          echo '<h5 id = "info" class="text-center my-2"><small><b>**You may update your details below if you wish!**</b></small></h5>';
                        }
                        echo '</b></h1>'; 
                        
                        if(!empty($client_id) && $client_status == 'A'){ //not deactivated client?>
                        
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-8 d-flex justify-content-center">
                                <button type="button"  id="view_status" class="btn btn-info mr-1 mb-2">Account Status</button>
                                <button type="button"  id="view_update" class="btn btn-info ml-1 mb-2">View/Update Profile</button>
                            </div>
                            <div class="col-2"></div>
                        </div>
                        
                  <?php }elseif(!empty($client_id)){ //deactivated client ?>
                    
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-8 d-sm-block d-md-flex justify-content-center flex-sm-wrap">
                                <button type="button"  id="view_status" class="btn btn-info tabs mr-1 mb-1">Account Status</button>
                                <button type="button"  id="view_update" class="btn btn-info tabs ml-1">View Profile</button>
                            </div>
                            <div class="col-2"></div>
                        </div>
                    
                  <?php } ?>
                    <div id = "profile">
                    <form id="account" action = "Account_manage.php" method = "post">
                      <fieldset <?php if(!empty($client_id) && !empty($client_status) && $client_status != 'A' && $client_status != 'R' && $client_status != 'N'){//disables the form for any client whose status is not active 'A'
                        echo 'disabled="disabled"'; } ?>>
                        <legend class="text-center" id="1"><span class="badge badge-pill badge-info mr-2">1</span>Your Current Station Details</legend>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for ="level_taught"><b>Level <span>(required)</span></b></label>
                            <select id="level_taught" class="form-control" name="level_taught">
                              <option value="">Select One Option</option>
                                <optgroup label="Primary Level" id="primary">
                                  <option value="Primary - ECD" <?php
                                  
                                  if(isset($_POST['level_taught'])){//retrieve client level during registration if submission fails
                                if($_POST['level_taught'] == 'Primary - ECD'){
                                  echo 'selected';
                                }
                              }elseif(isset($client_level_taught) && $client_level_taught  == 'PRIMARY - ECD'){//retrieve client level during updating
                                  echo 'selected';
                                } ?>>Primary - ECD</option>
                                  <option value="Primary - General" <?php if(isset($_POST['level_taught'])){//retrieve client level during registration if submission fails
                                if($_POST['level_taught'] == 'Primary - General'){
                                  echo 'selected';
                                }
                              }elseif(isset($client_level_taught) && $client_level_taught == 'PRIMARY - GENERAL'){//retrieve client level during updating
                                  echo 'selected';
                                } ?>>Primary - General</option>
                                  <option value="Primary - Special Needs" <?php
                                  
                                  if(isset($_POST['level_taught'])){//retrieve client level during registration if submission fails
                                if($_POST['level_taught'] == 'Primary - Special Needs'){
                                  echo 'selected';
                                }
                              }elseif(isset($client_level_taught) && $client_level_taught  == 'PRIMARY - SPECIAL NEEDS'){//retrieve client level during updating
                                  echo 'selected';
                                } ?>>Primary - Special Needs</option>
                                </optgroup>
                                <optgroup label="High School" id="secondary">
                                  <option value="High School - Up To O Level" <?php if(isset($_POST['level_taught'])){//retrieve client level during registration if submission fails
                                if($_POST['level_taught'] == 'High School - Up To O Level'){
                                  echo 'selected';
                                }
                              }elseif(isset($client_level_taught) && $client_level_taught == 'HIGH SCHOOL - UP TO O LEVEL'){//retrieve client level during updating
                                  echo 'selected';
                                } ?>>High School - Up To O Level</option>
                                  <option value="High School - Up To A Level" <?php if(isset($_POST['level_taught'])){//retrieve client level during registration if submission fails
                                if($_POST['level_taught'] == 'High School - Up To A Level'){
                                  echo 'selected';
                                }
                              }elseif(isset($client_level_taught) && $client_level_taught == 'HIGH SCHOOL - UP TO A LEVEL'){//retrieve client level during updating
                                  echo 'selected';
                                } ?>>High School - Up To A Level</option>
                                </optgroup>
                            </select>
                          </div>
                          <div class="form-group col-md-6">
                            <label for ="current_province"><b>Province <span>(required)</span></b></label>
                            <select id="current_province" class="form-control" name="current_province">
                              <option value="">Select Level Taught First</option>
                              <?php
                                all_provinces_curr($provinces);
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for ="current_district"><b>District <span>(required)</span></b></label>
                            <select id="current_district" class="form-control" name="current_district">
                              <option value="">Select Province First</option>
                               <?php
                                pull_curr_distr();
                               ?>
                            </select>
                          </div>
                          <div class="form-group col-md-6">
                            <label for ="current_town"><b>Town <span>(optional)</span></b></label>
                            <select id="current_town" class="form-control" name="current_town">
                              <option value="">Select province first</option>
                               <?php
                                pull_curr_town($towns);
                               ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for ="current_location"><b>Location <span>(optional)</span></b></label>
                            <select id="current_location" class="form-control" name="current_location">
                              <option value="">Select province or town first</option>
                               <?php
                                pull_curr_loc($locations);
                               ?>
                            </select>
                          </div>
                          <div class="form-group col-md-6">
                            <label for ="current_school"><b>School <span>(required)</span></b></label>
                            <select id="current_school" class="form-control" name="current_school">
                              <option value="">Select District First</option>
                               <?php
                                pull_curr_sch();
                               ?>
                            </select>
                          </div>
                        </div>
                        <hr>
                          <legend class="text-center"><span class="badge badge-pill badge-info mr-2">2</span>Your Basic Information</legend>
                        <fieldset class="form-group">
                          <div class="row">
                            <legend class="col-form-label col-sm-2 pt-0">Gender</legend>
                            <div class="col-sm-10">
                              <div class="form-check">
                                <input class="form-check-input" type="radio"  id="male" value="Male" name="gender" <?php if(isset($_POST['gender'])){//for remembering data during registration
                                                                              if (($_POST['gender'] == 'Male')) echo 'checked';
                                                                              }elseif(!empty($client_sex) && $client_sex == 'Male') echo 'checked';
                                                                              //for querying data during updating of client details
                                                                              ?>>
                                <label class="form-check-label"  for="male" name="male" id="light">
                                  Male
                                </label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="radio"  id="female" value="Female" name="gender" <?php if(isset($_POST['gender'])){//for remembering data during registration
                                                                                    if (($_POST['gender'] == 'Female')) echo 'checked';
                                                                                    }elseif(!empty($client_sex) && $client_sex == 'Female') echo 'checked';
                                                                                    //for querying data during updating of client details
                                                                                    ?>>
                                <label class="form-check-label"  for="female" name="female" id="light">
                                  Female
                                </label>
                              </div>
                            </div>
                          </div>
                        </fieldset>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="first_name"><b>First Name <span>(required)</span>:</b></label>
                              <input type="text" class="form-control" id="first_name" name = "user_first_name" value = "<?php if(!empty($client_first_name)){
                                                                                      echo $client_first_name;
                                                                                    }elseif(isset($userFirstName) && !empty($userFirstName)){
                                                                                      echo htmlspecialchars($userFirstName);
                                                                                      } ?>" placeholder="First Name">
                            </div>
                          <div class="form-group col-md-6">
                              <label for="last_name"><b>Last Name <span>(required)</span>:</b></label>
                              <input type="text" class="form-control" id="last_name" name = "user_last_name" value = "<?php if(!empty($client_last_name)){
                                                                                      echo $client_last_name;
                                                                                    }elseif(isset($userLastName) && !empty($userLastName)){
                                                                                      echo htmlspecialchars($userLastName);
                                                                                      } ?>" placeholder="Last Name">
                          </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="mobile_number"><b>Mobile Number <span>(required)</span>:</b></label>
                              <input type="text" class="form-control" id="mobile_number" name = "mobile_number" value = "<?php if(!empty($client_mobile_no)){//retrieve client mobile number during updating
                                                                                      echo $client_mobile_no;
                                                                                      }elseif(isset($mobileNumber) && !empty($mobileNumber)){
                                                                                      echo htmlspecialchars($mobileNumber);
                                                                                      }?>" placeholder="Mobile Number">
                            </div>
                          <div class="form-group col-md-6">
                            <label for="email"><b>Email</b></label>
                            <input type="email" class="form-control" id="email" name = "user_email" value = "<?php if(!empty($client_email)){//retrieve client email during updating
                                                                                      echo $client_email;
                                                                                      }elseif(isset($userEmail) && !empty($userEmail)){
                                                                                      echo htmlspecialchars($userEmail);
                                                                                      } ?>" placeholder="E-mail">
                          </div>
                        </div>
                        <?php //hide password field if admin is logged
                        if($_SESSION['logged_status'] != 'SU' && $_SESSION['logged_status'] != 'AD'){ ?>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="password"><b>Password</b></label>
                            <input type="password" class="form-control" id="password" name = "user_password" placeholder="Password">
                          </div>
                          <div class="form-group col-md-6">
                            <label for="pass_confirm"><b>Confirm Password:</b></label>
                            <input type="password" class="form-control" id="pass_confirm" name = "user_pass_confirm" placeholder="Confirm Password">
                          </div>
                        </div>
                        <?php } ?> 
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for ="referrer_agent"><b>Referrer Agent ID:</b></label>
                            <select id="referrer_agent" class="form-control" name="referrer_agent" readonly>
                              <option value="">None</option>
                               <?php
                                pull_agent();
                               ?>
                            </select>
                          </div>
                          <div class="form-group col-md-6" <?php if(empty($client_id)){echo 'style = "display:none"'; } ?>>
                              <label for="ec_number"><b>Account Number</b></label>
                              <input type="text" class="form-control" id="ec_number" name = "ec_number" value = "<?php if(!empty($client_ec_no)){//retrieve client A/C Number during updating
                                                                                      echo $client_ec_no;
                                                                                      }elseif(isset($ecNumber) && !empty($ecNumber)){
                                                                                      echo htmlspecialchars($ecNumber);
                                                                                      }?>"<?php 
                                                                                      if(!empty($client_ec_no)){//turns the html attribute to read only to avoid changing the A/C Number
                                                                                        echo 'readonly';
                                                                                      }?> placeholder="A/C Number">
                          </div>
                        </div>
                        <div class="form-row d-none">
                          <div class="form-group col-md-6">
                            <label for="prev_status">Previous Status:</label>
                            <input type="hidden" class="form-control" id="prev_status" name = "prev_status" value = "<?php echo $client_status; ?>">
                          </div>
                          <div class="form-group col-md-6">
                            <label for="blank">Leave This Field Blank:</label>
                            <input type="hidden" class="form-control" id="blank" name = "blank">
                          </div>
                        </div>
                        
                        <hr>
                          <legend id="pref_butts" class="text-center"><span class="badge badge-pill badge-info mr-2">3</span>Your Preferred Station Details</legend>
                          <h4 class="text-center"><small>Choose Preferred Schools By:</small></h4>
                          <div class="d-sm-block d-md-flex justify-content-between flex-sm-wrap">
                              <a href="#pref_butts" class="btn btn-info prefButtons my-1" role="button" id="province">Province</a>
                              <a href="#pref_butts" class="btn btn-info prefButtons my-1" role="button" id="district">Districts</a>
                              <a href="#pref_butts" class="btn btn-info prefButtons my-1" role="button" id="town">Town</a>
                              <a href="#pref_butts" class="btn btn-info prefButtons my-1" role="button" id="location">Locations</a>
                              <a href="#pref_butts" class="btn btn-info prefButtons my-1" role="button" id="school">Specific Schools</a>
                          </div>
                          <div id="provinces" <?php if($_SERVER["REQUEST_METHOD"] == "POST"){
                                                                    if(isset($_POST['preferred_province'])){
                                                                      echo 'style = "display:block"';
                                                                      }else{
                                                                            echo 'style = "display:none"';
                                                                            }
                                                                      }elseif(!empty($mpp_id)){
                                                                        echo 'style = "display:block"';
                                                                      }else{
                                                                        echo 'style = "display:none"';}?>>
                              <h5 class="text-center my-2"><small><b>Select Your Preferred Province(s)</b></small></h5>
                            <div class="row">
                              <div class="form-group align-self-center col-2">
                                <h5><em>Option 1</em></h5>
                              </div>
                              <div class="form-group col-10">
                                <select id="preferred_province" class="form-control mySelect" name="preferred_province">
                                  <option value="" selected disabled>Please select one option -- (only if applicable)</option>
                                  <?php all_provinces($provinces);?>
                                </select>
                              </div>
                              </div>
                            <div class="row">
                                  <div class="form-group align-self-center col-2">
                                    <h5><em>Option 2</em></h5>
                                    </div>
                                    <div class="form-group col-10">
                                    <select id="preferred_province2" class="form-control mySelect" name="preferred_province2">
                                      <option value="" selected disabled>Please select second option -- (only if applicable)</option>
                                      <?php all_provinces2($provinces);?>
                                    </select>
                                  </div>
                                  </div>
                              </div>
                          <div id="districts" <?php  if($_SERVER["REQUEST_METHOD"] == "POST"){
                                                        if(isset($_POST['preferred_district1']) && !empty($_POST['preferred_district1'])){
                                                          echo 'style = "display:block"';
                                                          }else{
                                                           echo 'style = "display:none"';
                                                          }
                                                      }elseif(!empty($mpd_id)){
                                                        echo 'style = "display:block"';
                                                      }else{ 
                                                        echo 'style = "display:none"';
                                                      }?>>
                              <h5 class="text-center my-2"><small><b>Select Your Preferred District(s) - Up To Four Options</b></small></h5>
                            <div class="form-row">
                              <div class="form-group col-md-2 align-self-center">
                                <h5><em>Option 1</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <label for ="distr_name1_province"><b>Province</b></label>
                                <select id="distr_name1_province" class="form-control mySelect" name="distr_name1_province">
                                  <option value="" selected>Province</option>
                                  <?php all_provs_pref_distr1($provinces);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <label for ="preferred_district1"><b>District</b></label>
                                <select id="preferred_district1" class="form-control mySelect" name="preferred_district1">
                                  <option value="" selected>Select Province First</option>
                                  <?php pull_pref_distr1();?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 2</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="distr_name2_province" class="form-control mySelect" name="distr_name2_province">
                                  <option value="" selected>Province</option>
                                  <?php all_provs_pref_distr2($provinces);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_district2" class="form-control mySelect" name="preferred_district2">
                                  <option value="" selected>Select Province First</option>
                                  <?php pull_pref_distr2();?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 3</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="distr_name3_province" class="form-control mySelect" name="distr_name3_province">
                                  <option value="" selected>Province</option>
                                  <?php all_provs_pref_distr3($provinces);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_district3" class="form-control mySelect" name="preferred_district3">
                                  <option value="" selected>Select Province First</option>
                                  <?php pull_pref_distr3();?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 4</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="distr_name4_province" class="form-control mySelect" name="distr_name4_province">
                                  <option value="" selected>Province</option>
                                  <?php all_provs_pref_distr4($provinces);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_district4" class="form-control mySelect" name="preferred_district4">
                                  <option value="" selected>Select Province First</option>
                                  <?php pull_pref_distr4();?>
                                </select>
                              </div>
                              </div>
                          </div>
                          <div id="towns" <?php if($_SERVER["REQUEST_METHOD"] == "POST"){
                                                                  if(isset($_POST['preferred_town']) && !empty($_POST['preferred_town'])){
                                                                    echo 'style = "display:block"';
                                                                    }
                                                                }elseif(!empty($mpt_town_id)){
                                                                      echo 'style = "display:block"';
                                                                }else{
                                                                      echo 'style = "display:none"';
                                                                }
                                                                  ?>>
                              <h5 class="text-center my-2"><small><b>Select Your Preferred Town(s)</b></small></h5>
                            <div class="form-row">
                              <div class="form-group col-md-2 align-self-center">
                                <h5><em>Option 1</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <label for ="town_name1_province"><b>Province</b></label>
                                <select id="town_name1_province" class="form-control mySelect" name="town_name1_province">
                                  <option value="" selected>Province</option>
                                  <?php all_provs_pref_town1($provinces);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <label for ="town_name"><b>Province</b></label>
                                <select id="town_name" class="form-control mySelect" name="preferred_town">
                                  <option value="" selected disabled>Please select one option -- (only if applicable)</option>
                                  <?php pull_pref_town1($towns);?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 2</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="town_name2_province" class="form-control mySelect" name="town_name2_province">
                                  <option value="" selected>Province</option>
                                  <?php all_provs_pref_town2($provinces);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="town_name2" class="form-control mySelect" name="preferred_town2">
                                  <option value="" selected disabled>Please select second option -- (only if applicable)</option>
                                  <?php  pull_pref_town2($towns);?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 3</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="town_name3_province" class="form-control mySelect" name="town_name3_province">
                                  <option value="" selected>Province</option>
                                  <?php all_provs_pref_town3($provinces);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="town_name3" class="form-control mySelect" name="preferred_town3">
                                  <option value="" selected disabled>Please select second option -- (only if applicable)</option>
                                  <?php pull_pref_town3($towns);?>
                                </select>
                              </div>
                              </div>
                          </div>
                          <div id="locations" <?php if($_SERVER["REQUEST_METHOD"] == "POST"){
                                                      if(isset($_POST['preferred_location1']) && !empty($_POST['preferred_location1'])){
                                                        echo 'style = "display:block"';
                                                        }else{
                                                          echo 'style = "display:none"';
                                                        }
                                                    }elseif(!empty($mpl_id)){
                                                          echo 'style = "display:block"';
                                                    }else{
                                                      echo 'style = "display:none"';}?>>
                              <h5 class="text-center my-2"><small><b>Select Your Preferred Location(s) - Up To Five Options</b></small></h5>
                            <div class="form-row">
                              <div class="form-group col-md-2 align-self-center">
                                <h5><em>Option 1</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <label for ="loc_name1_distr"><b>District</b></label>
                                <select id="loc_name1_distr" class="form-control mySelect" name="loc_name1_distr">
                                  <option value="" selected>District</option>
                                  <?php all_distr_pref_loc1($districts);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <label for ="loc_name1"><b>District</b></label>
                                <select id="loc_name1" class="form-control mySelect" name="preferred_location1">
                                  <option value="" selected>Select District First</option>
                                  <?php pull_pref_loc1();?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 2</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="loc_name2_distr" class="form-control mySelect" name="loc_name2_distr">
                                  <option value="" selected>District</option>
                                  <?php all_distr_pref_loc2($districts);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="loc_name2" class="form-control mySelect" name="preferred_location2">
                                  <option value="" selected disabled>Select District First</option>
                                  <?php pull_pref_loc2();?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 3</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="loc_name3_distr" class="form-control mySelect" name="loc_name3_distr">
                                  <option value="" selected>District</option>
                                  <?php all_distr_pref_loc3($districts);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="loc_name3" class="form-control mySelect" name="preferred_location3">
                                  <option value="" selected disabled>Select District First</option>
                                  <?php pull_pref_loc3();?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 4</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="loc_name4_distr" class="form-control mySelect" name="loc_name4_distr">
                                  <option value="" selected>District</option>
                                  <?php all_distr_pref_loc4($districts);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="loc_name4" class="form-control mySelect" name="preferred_location4">
                                  <option value="" selected disabled>Select District First</option>
                                  <?php pull_pref_loc4();?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 5</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="loc_name5_distr" class="form-control mySelect" name="loc_name5_distr">
                                  <option value="" selected>District</option>
                                  <?php all_distr_pref_loc5($districts);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="loc_name5" class="form-control mySelect" name="preferred_location5">
                                  <option value="" selected disabled>Select District First</option>
                                  <?php pull_pref_loc5();?>
                                </select>
                              </div>
                              </div>
                          </div>
                          <div id="specific_schs" <?php if($_SERVER["REQUEST_METHOD"] == "POST"){
                                                          if(isset($_POST['preferred_schools1']) && !empty($_POST['preferred_schools1'])){
                                                            echo 'style = "display:block"';
                                                            }else{
                                                              echo 'style = "display:none"';
                                                              }
                                                            }elseif(!empty($mps_id)){
                                                              echo 'style = "display:block"';
                                                            }else{
                                                              echo 'style = "display:none"';
                                                            }  ?>>
                              <h5 class="text-center my-2"><small><b>Select Your Preferred Schools Maximum of 10 Schools</b></small></h5>
                            <div class="form-row">
                              <div class="form-group col-md-2 align-self-center">
                                <h5><em>Option 1</em><h5>
                              </div>
                              <div class="form-group col-md-5">
                                <label for ="preferred_schools1_distr"><b>District</b></label>
                                <select id="preferred_schools1_distr" class="form-control mySelect" name="preferred_schools1_distr">
                                  <option value="" selected>District</option>
                                  <?php all_distr_pref_sch1($districts);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <label for ="preferred_schools1"><b>School</b></label>
                                <select id="preferred_schools1" class="form-control mySelect schools" name="preferred_schools1">
                                  <option value="" selected>Select District First</option>
                                   <?php
                                      pull_pref_sch1();
                                   ?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 2</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools2_distr" class="form-control mySelect" name="preferred_schools2_distr">
                                  <option value="" selected>District</option>
                                  <?php all_distr_pref_sch2($districts);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools2" class="form-control mySelect schools" name="preferred_schools2">
                                  <option value="" selected>Select District First</option>
                                   <?php
                                      pull_pref_sch2();
                                   ?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 3</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools3_distr" class="form-control mySelect" name="preferred_schools3_distr">
                                  <option value="" selected>District</option>
                                  <?php all_distr_pref_sch3($districts);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools3" class="form-control mySelect schools" name="preferred_schools3">
                                  <option value="" selected>Select District First</option>
                                   <?php
                                      pull_pref_sch3();
                                   ?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 4</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools4_distr" class="form-control mySelect" name="preferred_schools4_distr">
                                  <option value="" selected>District</option>
                                  <?php all_distr_pref_sch4($districts);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools4" class="form-control mySelect schools" name="preferred_schools4">
                                  <option value="" selected>Select District First</option>
                                   <?php
                                      pull_pref_sch4();
                                   ?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 5</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools5_distr" class="form-control mySelect" name="preferred_schools5_distr">
                                  <option value="" selected>District</option>
                                  <?php all_distr_pref_sch5($districts);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools5" class="form-control mySelect schools" name="preferred_schools5">
                                  <option value="" selected>Select District First</option>
                                   <?php
                                    pull_pref_sch5();
                                   ?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 6</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools6_distr" class="form-control mySelect" name="preferred_schools6_distr">
                                  <option value="" selected>District</option>
                                  <?php all_distr_pref_sch6($districts);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools6" class="form-control mySelect schools" name="preferred_schools6">
                                  <option value="" selected>Select District First</option>
                                   <?php
                                      pull_pref_sch6();
                                   ?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 7</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools7_distr" class="form-control mySelect" name="preferred_schools7_distr">
                                  <option value="" selected>District</option>
                                  <?php all_distr_pref_sch7($districts);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools7" class="form-control mySelect schools" name="preferred_schools7">
                                  <option value="" selected>Select District First</option>
                                   <?php
                                      pull_pref_sch7();
                                   ?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 8</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools8_distr" class="form-control mySelect" name="preferred_schools8_distr">
                                  <option value="" selected>District</option>
                                  <?php all_distr_pref_sch8($districts);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools8" class="form-control mySelect schools" name="preferred_schools8">
                                  <option value="" selected>Select District First</option>
                                   <?php
                                      pull_pref_sch8();
                                   ?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 9</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools9_distr" class="form-control mySelect" name="preferred_schools9_distr">
                                  <option value="" selected>District</option>
                                  <?php all_distr_pref_sch9($districts);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools9" class="form-control mySelect schools" name="preferred_schools9">
                                  <option value="" selected>Select District First</option>
                                   <?php
                                      pull_pref_sch9();
                                   ?>
                                </select>
                              </div>
                              </div>
                            <div class="form-row">
                              <div class="form-group col-md-2">
                                <h5><em>Option 10</em></h5>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools10_distr" class="form-control mySelect" name="preferred_schools10_distr">
                                  <option value="" selected>District</option>
                                  <?php all_distr_pref_sch10($districts);?>
                                </select>
                              </div>
                              <div class="form-group col-md-5">
                                <select id="preferred_schools10" class="form-control mySelect schools" name="preferred_schools10">
                                  <option value="" selected>Select District First</option>
                                   <?php
                                      pull_pref_sch10();
                                   ?>
                                </select>
                              </div>
                              </div>
                          </div>
                          <div id="subjects" <?php if($_SERVER["REQUEST_METHOD"] == "POST"){
                                                      if(isset($_POST['level_taught']) &&
                                                        (($_POST['level_taught'] == "Primary - ECD" ) ||
                                                        ($_POST['level_taught'] == "Primary - General" ) ||
                                                        ($_POST['level_taught'] == "Primary - Special Needs" ))){
                                                        echo 'style = "display:none"';
                                                        }
                                                        }elseif(!empty($match_sub1_id) || !empty($match_sub2_id)){
                                                        echo 'style = "display:block"';
                                                        }else{
                                                        echo 'style = "display:none"';
                                                        }?>>
                            <hr>
                            <legend class="text-center"><span class="badge badge-pill badge-info mr-2">4</span>Subjects Taught:</legend>
                            <div class="container">
                                <h5 class="text-center my-2"><small><b>**At least one (1) and a maximum of two (2) subjects**</b></small></h5>
                                <div class="row"> <!--
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="1" id="subject1" name="subject1" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 1) || ($match_sub2_id == 1)){echo 'checked';}}elseif(isset($_POST['subject1'])){echo 'checked';}  ?>>
                                              <label class="form-check-label" for="subject1">
                                                Additional Maths
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="34" id="subject34" name="subject34" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 34) || ($match_sub2_id == 34)){echo 'checked';}}elseif(isset($_POST['subject34'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject34">
                                                Agriculture
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="2" id="subject2" name="subject2" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 2) || ($match_sub2_id == 2)){echo 'checked';}}else{echo (isset($_POST['subject2'])?'checked="checked"':'');}?>>
                                              <label class="form-check-label" for="subject2">
                                                Art
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="3" id="subject3" name="subject3" <?php  //if(isset($_GET['id'])){if(($match_sub1_id == 3) || ($match_sub2_id == 3)){echo 'checked';}}elseif(isset($_POST['subject3'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject3">
                                                Biology
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="35" id="subject35" name="subject35" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 35) || ($match_sub2_id == 35)){echo 'checked';}}elseif(isset($_POST['subject35'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject35">
                                                Building Tech. and Design
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="4" id="subject4" name="subject4" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 4) || ($match_sub2_id == 4)){echo 'checked';}}elseif(isset($_POST['subject4'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject4">
                                                Business Studies
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="5" id="subject5" name="subject5" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 5) || ($match_sub2_id == 5)){echo 'checked';}}elseif(isset($_POST['subject5'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject5">
                                                Chemistry
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="6" id="subject6" name="subject6" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 6) || ($match_sub2_id == 6)){echo 'checked';}}elseif(isset($_POST['subject6'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject6">
                                                Commerce
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="7" id="subject7" name="subject7" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 7) || ($match_sub2_id == 7)){echo 'checked';}}elseif(isset($_POST['subject7'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject7">
                                                Computer Studies
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="8" id="subject8" name="subject8" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 8) || ($match_sub2_id == 8)){echo 'checked';}}elseif(isset($_POST['subject8'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject8">
                                                Economics
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="9" id="subject9" name="subject9" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 9) || ($match_sub2_id == 9)){echo 'checked';}}elseif(isset($_POST['subject9'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject9">
                                                English Language
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="10" id="subject10" name="subject10" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 10) || ($match_sub2_id == 10)){echo 'checked';}}elseif(isset($_POST['subject10'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject10">
                                                English Literature
                                              </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="11" id="subject11" name="subject11" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 11) || ($match_sub2_id == 11)){echo 'checked';}}elseif(isset($_POST['subject11'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject11">
                                                Fashion And Fabrics
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="12" id="subject12" name="subject12" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 12) || ($match_sub2_id == 12)){echo 'checked';}}elseif(isset($_POST['subject12'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject12">
                                                Food And Nutrition
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="13" id="subject13" name="subject13" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 13) || ($match_sub2_id == 13)){echo 'checked';}}elseif(isset($_POST['subject13'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject13">
                                                French
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="14" id="subject14" name="subject14" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 14) || ($match_sub2_id == 14)){echo 'checked';}}elseif(isset($_POST['subject14'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject14">
                                                Geography
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="15" id="subject15" name="subject15" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 15) || ($match_sub2_id == 15)){echo 'checked';}}elseif(isset($_POST['subject15'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject15">
                                                History
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="16" id="subject16" name="subject16" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 16) || ($match_sub2_id == 16)){echo 'checked';}}elseif(isset($_POST['subject16'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject16">
                                                Home Management
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="17" id="subject17" name="subject17" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 17) || ($match_sub2_id == 17)){echo 'checked';}}elseif(isset($_POST['subject17'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject17">
                                                Human And Social Biology
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="18" id="subject18" name="subject18" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 18) || ($match_sub2_id == 18)){echo 'checked';}}elseif(isset($_POST['subject18'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject18">
                                                Integrated Science
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="19" id="subject19" name="subject19" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 19) || ($match_sub2_id == 19)){echo 'checked';}}elseif(isset($_POST['subject19'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject19">
                                                Law
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="20" id="subject20" name="subject20" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 20) || ($match_sub2_id == 20)){echo 'checked';}}elseif(isset($_POST['subject20'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject20">
                                                Mathematics
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="21" id="subject21" name="subject21" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 21) || ($match_sub2_id == 21)){echo 'checked';}}elseif(isset($_POST['subject21'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject13">
                                                Metalwork
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="22" id="subject22" name="subject22" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 22) || ($match_sub2_id == 22)){echo 'checked';}}elseif(isset($_POST['subject22'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject22">
                                                Music
                                              </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="23" id="subject23" name="subject23" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 23) || ($match_sub2_id == 23)){echo 'checked';}}elseif(isset($_POST['subject23'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject23">
                                                Ndebele
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="36" id="subject36" name="subject36" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 36) || ($match_sub2_id == 36)){echo 'checked';}}elseif(isset($_POST['subject36'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject36">
                                                  Physical Education
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="24" id="subject24" name="subject24" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 24) || ($match_sub2_id == 24)){echo 'checked';}}elseif(isset($_POST['subject24'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject24">
                                                Physical Science
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="25" id="subject25" name="subject25" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 25) || ($match_sub2_id == 25)){echo 'checked';}}elseif(isset($_POST['subject25'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject25">
                                                Physics
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="26" id="subject26" name="subject26" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 26) || ($match_sub2_id == 26)){echo 'checked';}}elseif(isset($_POST['subject26'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject26">
                                                Principles Of Accounts
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="27" id="subject27" name="subject27" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 27) || ($match_sub2_id == 27)){echo 'checked';}}elseif(isset($_POST['subject27'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject27">
                                                Religious Studies
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="28" id="subject28" name="subject28" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 28) || ($match_sub2_id == 28)){echo 'checked';}}elseif(isset($_POST['subject28'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject28">
                                                Shona
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="29" id="subject29" name="subject29" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 29) || ($match_sub2_id == 29)){echo 'checked';}}elseif(isset($_POST['subject29'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject29">
                                                Sociology
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="30" id="subject30" name="subject30" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 30) || ($match_sub2_id == 30)){echo 'checked';}}elseif(isset($_POST['subject30'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject30">
                                                Statistics
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="31" id="subject31" name="subject31" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 31) || ($match_sub2_id == 31)){echo 'checked';}}elseif(isset($_POST['subject31'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject31">
                                                Technical Graphics
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="32" id="subject32" name="subject32" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 32) || ($match_sub2_id == 32)){echo 'checked';}}elseif(isset($_POST['subject32'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject32">
                                                Woodwork
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="33" id="subject33" name="subject33" <?php //if(isset($_GET['id'])){if(($match_sub1_id == 33) || ($match_sub2_id == 33)){echo 'checked';}}elseif(isset($_POST['subject33'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject33">
                                                Tonga
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row"></div>
                                        <div class="row"></div>
                                        <div class="row"></div>
                                        <div class="row"></div>
                                        <div class="row"></div>
                                        <div class="row"></div>
                                        <div class="row"></div>
                                        <div class="row"></div>
                                        <div class="row"></div>
                                        <div class="row"></div>
                                        <div class="row"></div>
                                    </div>  <!-- --> 
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="37" id="subject37" name="subject37" <?php if(isset($_GET['id'])){if(($match_sub1_id == 37) || ($match_sub2_id == 37)){echo 'checked';}}elseif(isset($_POST['subject37'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject37">
                                                Accounting
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="1" id="subject1" name="subject1" <?php if(isset($_GET['id'])){if(($match_sub1_id == 1) || ($match_sub2_id == 1)){echo 'checked';}}elseif(isset($_POST['subject1'])){echo 'checked';}  ?>>
                                              <label class="form-check-label" for="subject1">
                                                Additional Mathematics
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="38" id="subject38" name="subject38" <?php if(isset($_GET['id'])){if(($match_sub1_id == 38) || ($match_sub2_id == 38)){echo 'checked';}}elseif(isset($_POST['subject38'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject38">
                                                Agricultural Engineering
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="34" id="subject34" name="subject34" <?php if(isset($_GET['id'])){if(($match_sub1_id == 34) || ($match_sub2_id == 34)){echo 'checked';}}elseif(isset($_POST['subject34'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject34">
                                                Agriculture
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="39" id="subject39" name="subject39" <?php if(isset($_GET['id'])){if(($match_sub1_id == 39) || ($match_sub2_id == 39)){echo 'checked';}}elseif(isset($_POST['subject39'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject39">
                                                Animal Science
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="2" id="subject2" name="subject2" <?php if(isset($_GET['id'])){if(($match_sub1_id == 2) || ($match_sub2_id == 2)){echo 'checked';}}else{echo (isset($_POST['subject2'])?'checked="checked"':'');}?>>
                                              <label class="form-check-label" for="subject2">
                                                Art
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="3" id="subject3" name="subject3" <?php  if(isset($_GET['id'])){if(($match_sub1_id == 3) || ($match_sub2_id == 3)){echo 'checked';}}elseif(isset($_POST['subject3'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject3">
                                                Biology
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="35" id="subject35" name="subject35" <?php if(isset($_GET['id'])){if(($match_sub1_id == 35) || ($match_sub2_id == 35)){echo 'checked';}}elseif(isset($_POST['subject35'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject35">
                                                Building Technology and Design
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="40" id="subject40" name="subject40" <?php if(isset($_GET['id'])){if(($match_sub1_id == 40) || ($match_sub2_id == 40)){echo 'checked';}}elseif(isset($_POST['subject40'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject40">
                                                Business & Enterprise Skills
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="5" id="subject5" name="subject5" <?php if(isset($_GET['id'])){if(($match_sub1_id == 5) || ($match_sub2_id == 5)){echo 'checked';}}elseif(isset($_POST['subject5'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject5">
                                                Business Studies
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="6" id="subject6" name="subject6" <?php if(isset($_GET['id'])){if(($match_sub1_id == 6) || ($match_sub2_id == 6)){echo 'checked';}}elseif(isset($_POST['subject6'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject6">
                                                Chemistry
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="41" id="subject41" name="subject41" <?php if(isset($_GET['id'])){if(($match_sub1_id == 41) || ($match_sub2_id == 41)){echo 'checked';}}elseif(isset($_POST['subject41'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject41">
                                                Combined Science
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="7" id="subject7" name="subject7" <?php if(isset($_GET['id'])){if(($match_sub1_id == 7) || ($match_sub2_id == 7)){echo 'checked';}}elseif(isset($_POST['subject7'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject7">
                                                Commerce
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="42" id="subject42" name="subject42" <?php if(isset($_GET['id'])){if(($match_sub1_id == 42) || ($match_sub2_id == 42)){echo 'checked';}}elseif(isset($_POST['subject42'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject42">
                                                Commercial Studies
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input ALevel" type="checkbox" value="43" id="subject43" name="subject43" <?php if(isset($_GET['id'])){if(($match_sub1_id == 43) || ($match_sub2_id == 43)){echo 'checked';}}elseif(isset($_POST['subject43'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject43">
                                                Communication Skills
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input ALevel" type="checkbox" value="8" id="subject8" name="subject8" <?php if(isset($_GET['id'])){if(($match_sub1_id == 8) || ($match_sub2_id == 8)){echo 'checked';}}elseif(isset($_POST['subject8'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject8">
                                                Computer Science
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input ALevel" type="checkbox" value="44" id="subject44" name="subject44" <?php if(isset($_GET['id'])){if(($match_sub1_id == 44) || ($match_sub2_id == 44)){echo 'checked';}}elseif(isset($_POST['subject44'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject44">
                                                Crop Science
                                              </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="45" id="subject45" name="subject45" <?php if(isset($_GET['id'])){if(($match_sub1_id == 45) || ($match_sub2_id == 45)){echo 'checked';}}elseif(isset($_POST['subject45'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject45">
                                                Dance
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="46" id="subject46" name="subject46" <?php if(isset($_GET['id'])){if(($match_sub1_id == 46) || ($match_sub2_id == 46)){echo 'checked';}}elseif(isset($_POST['subject46'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject46">
                                                Design Technology
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="47" id="subject47" name="subject47" <?php if(isset($_GET['id'])){if(($match_sub1_id == 47) || ($match_sub2_id == 47)){echo 'checked';}}elseif(isset($_POST['subject47'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject47">
                                                Economic History
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="9" id="subject9" name="subject9" <?php if(isset($_GET['id'])){if(($match_sub1_id == 9) || ($match_sub2_id == 9)){echo 'checked';}}elseif(isset($_POST['subject9'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject9">
                                                Economics
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="10" id="subject10" name="subject10" <?php if(isset($_GET['id'])){if(($match_sub1_id == 10) || ($match_sub2_id == 10)){echo 'checked';}}elseif(isset($_POST['subject10'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject10">
                                                English Language
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="27" id="subject27" name="subject27" <?php if(isset($_GET['id'])){if(($match_sub1_id == 27) || ($match_sub2_id == 27)){echo 'checked';}}elseif(isset($_POST['subject27'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject27">
                                                Family and Religious Education
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="48" id="subject48" name="subject48" <?php if(isset($_GET['id'])){if(($match_sub1_id == 48) || ($match_sub2_id == 48)){echo 'checked';}}elseif(isset($_POST['subject48'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject48">
                                                Film
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="16" id="subject16" name="subject16" <?php if(isset($_GET['id'])){if(($match_sub1_id == 16) || ($match_sub2_id == 16)){echo 'checked';}}elseif(isset($_POST['subject16'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject16">
                                                Food Technology and Design
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="49" id="subject49" name="subject49" <?php if(isset($_GET['id'])){if(($match_sub1_id == 49) || ($match_sub2_id == 49)){echo 'checked';}}elseif(isset($_POST['subject49'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject49">
                                                Foreign Languages
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="13" id="subject13" name="subject13" <?php if(isset($_GET['id'])){if(($match_sub1_id == 13) || ($match_sub2_id == 13)){echo 'checked';}}elseif(isset($_POST['subject13'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject13">
                                                Geography
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="50" id="subject50" name="subject50" <?php if(isset($_GET['id'])){if(($match_sub1_id == 50) || ($match_sub2_id == 50)){echo 'checked';}}elseif(isset($_POST['subject50'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject50">
                                                Guidance and Counseling
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="51" id="subject51" name="subject51" <?php if(isset($_GET['id'])){if(($match_sub1_id == 51) || ($match_sub2_id == 51)){echo 'checked';}}elseif(isset($_POST['subject51'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject51">
                                                Heritage Studies
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="14" id="subject14" name="subject14" <?php if(isset($_GET['id'])){if(($match_sub1_id == 14) || ($match_sub2_id == 14)){echo 'checked';}}elseif(isset($_POST['subject14'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject14">
                                                History
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="17" id="subject17" name="subject17" <?php if(isset($_GET['id'])){if(($match_sub1_id == 17) || ($match_sub2_id == 17)){echo 'checked';}}elseif(isset($_POST['subject17'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject17">
                                                Home Management and Design
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="52" id="subject52" name="subject52" <?php if(isset($_GET['id'])){if(($match_sub1_id == 52) || ($match_sub2_id == 52)){echo 'checked';}}elseif(isset($_POST['subject52'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject52">
                                                Horticulture
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="64" id="subject64" name="subject64" <?php if(isset($_GET['id'])){if(($match_sub1_id == 64) || ($match_sub2_id == 64)){echo 'checked';}}elseif(isset($_POST['subject64'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject64">
                                                Life Skills Orientation Prgramme (LOP)
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="11" id="subject11" name="subject11" <?php if(isset($_GET['id'])){if(($match_sub1_id == 11) || ($match_sub2_id == 11)){echo 'checked';}}elseif(isset($_POST['subject11'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject11">
                                                Literature in English
                                              </label>
                                            </div>
                                        </div>
                                    </div> <!--
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="12" id="subject12" name="subject12" <?php if(isset($_GET['id'])){if(($match_sub1_id == 12) || ($match_sub2_id == 12)){echo 'checked';}}elseif(isset($_POST['subject12'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject12">
                                                French
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="15" id="subject15" name="subject15" <?php if(isset($_GET['id'])){if(($match_sub1_id == 15) || ($match_sub2_id == 15)){echo 'checked';}}elseif(isset($_POST['subject15'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject15">
                                                Fashion And Fabrics 
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="18" id="subject18" name="subject18" <?php if(isset($_GET['id'])){if(($match_sub1_id == 18) || ($match_sub2_id == 18)){echo 'checked';}}elseif(isset($_POST['subject18'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject18">
                                                Human & Social Biology
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="19" id="subject19" name="subject19" <?php if(isset($_GET['id'])){if(($match_sub1_id == 19) || ($match_sub2_id == 19)){echo 'checked';}}elseif(isset($_POST['subject19'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject19">
                                                Integrated Science
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="20" id="subject20" name="subject20" <?php if(isset($_GET['id'])){if(($match_sub1_id == 20) || ($match_sub2_id == 20)){echo 'checked';}}elseif(isset($_POST['subject20'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject20">
                                                Law
                                              </label>
                                            </div>
                                        </div> -->
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="21" id="subject21" name="subject21" <?php if(isset($_GET['id'])){if(($match_sub1_id == 21) || ($match_sub2_id == 21)){echo 'checked';}}elseif(isset($_POST['subject21'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject13">
                                                Mathematics
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="79" id="subject79" name="subject79" <?php if(isset($_GET['id'])){if(($match_sub1_id == 79) || ($match_sub2_id == 79)){echo 'checked';}}elseif(isset($_POST['subject79'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject79">
                                                Mechanical Mathematics
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="22" id="subject22" name="subject22" <?php if(isset($_GET['id'])){if(($match_sub1_id == 22) || ($match_sub2_id == 22)){echo 'checked';}}elseif(isset($_POST['subject22'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject22">
                                                Metal Technology and Design
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="23" id="subject23" name="subject23" <?php if(isset($_GET['id'])){if(($match_sub1_id == 23) || ($match_sub2_id == 23)){echo 'checked';}}elseif(isset($_POST['subject23'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject23">
                                                Musical Arts
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="36" id="subject36" name="subject36" <?php if(isset($_GET['id'])){if(($match_sub1_id == 36) || ($match_sub2_id == 36)){echo 'checked';}}elseif(isset($_POST['subject36'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject36">
                                                  Physical Education, Sports and Mass Displays
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="4" id="subject4" name="subject4" <?php if(isset($_GET['id'])){if(($match_sub1_id == 4) || ($match_sub2_id == 4)){echo 'checked';}}elseif(isset($_POST['subject4'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject4">
                                                Physics
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="25" id="subject25" name="subject25" <?php if(isset($_GET['id'])){if(($match_sub1_id == 25) || ($match_sub2_id == 25)){echo 'checked';}}elseif(isset($_POST['subject25'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject25">
                                                Physical Science
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="26" id="subject26" name="subject26" <?php if(isset($_GET['id'])){if(($match_sub1_id == 26) || ($match_sub2_id == 26)){echo 'checked';}}elseif(isset($_POST['subject26'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject26">
                                                Principles of Accounting
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="80" id="subject80" name="subject80" <?php if(isset($_GET['id'])){if(($match_sub1_id == 80) || ($match_sub2_id == 80)){echo 'checked';}}elseif(isset($_POST['subject80'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject80">
                                                Pure Mathematics
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="29" id="subject29" name="subject29" <?php if(isset($_GET['id'])){if(($match_sub1_id == 29) || ($match_sub2_id == 29)){echo 'checked';}}elseif(isset($_POST['subject29'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject29">
                                                Sociology
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="81" id="subject81" name="subject81" <?php if(isset($_GET['id'])){if(($match_sub1_id == 81) || ($match_sub2_id == 81)){echo 'checked';}}elseif(isset($_POST['subject81'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject81">
                                                Software Engineering
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="82" id="subject82" name="subject82" <?php if(isset($_GET['id'])){if(($match_sub1_id == 82) || ($match_sub2_id == 82)){echo 'checked';}}elseif(isset($_POST['subject82'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject82">
                                                Sports Management
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="83" id="subject83" name="subject83" <?php if(isset($_GET['id'])){if(($match_sub1_id == 83) || ($match_sub2_id == 83)){echo 'checked';}}elseif(isset($_POST['subject83'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject83">
                                                Sports Science and Technology
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="30" id="subject30" name="subject30" <?php if(isset($_GET['id'])){if(($match_sub1_id == 30) || ($match_sub2_id == 30)){echo 'checked';}}elseif(isset($_POST['subject30'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject30">
                                                Statistics
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="31" id="subject31" name="subject31" <?php if(isset($_GET['id'])){if(($match_sub1_id == 31) || ($match_sub2_id == 31)){echo 'checked';}}elseif(isset($_POST['subject31'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject31">
                                                Technical Graphics and Design
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="84" id="subject84" name="subject84" <?php if(isset($_GET['id'])){if(($match_sub1_id == 84) || ($match_sub2_id == 84)){echo 'checked';}}elseif(isset($_POST['subject84'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject84">
                                                Textile Technology and Design
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="85" id="subject85" name="subject85" <?php if(isset($_GET['id'])){if(($match_sub1_id == 85) || ($match_sub2_id == 85)){echo 'checked';}}elseif(isset($_POST['subject85'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject85">
                                                Theatre Arts
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="32" id="subject32" name="subject32" <?php if(isset($_GET['id'])){if(($match_sub1_id == 32) || ($match_sub2_id == 32)){echo 'checked';}}elseif(isset($_POST['subject32'])){echo 'checked';} ?>>
                                              <label class="form-check-label" for="subject32">
                                                Wood Technology and Design
                                              </label>
                                            </div>
                                        </div>
                                        <div class="row"></div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="accordion" id="accordionSubs">
                                              <div class="card" id="IndL">
                                                <div class="card-header" id="il">
                                                  <h5 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseIl" aria-expanded="true" aria-controls="collapseIl">
                                                      Indigenous</br>Languages
                                                    </button>
                                                  </h5>
                                                </div>
                                            
                                                <div id="collapseIl" class="collapse" aria-labelledby="headingOne" data-parent="#accordionSubs">
                                                  <div class="card-body">
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="53" id="subject53" name="subject53" <?php if(isset($_GET['id'])){if(($match_sub1_id == 53) || ($match_sub2_id == 53)){echo 'checked';}}elseif(isset($_POST['subject53'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject53">
                                                            Barwe
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="54" id="subject54" name="subject54" <?php if(isset($_GET['id'])){if(($match_sub1_id == 54) || ($match_sub2_id == 54)){echo 'checked';}}elseif(isset($_POST['subject54'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject54">
                                                            Chewa
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="55" id="subject55" name="subject55" <?php if(isset($_GET['id'])){if(($match_sub1_id == 55) || ($match_sub2_id == 55)){echo 'checked';}}elseif(isset($_POST['subject55'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject55">
                                                            Kalanga
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="56" id="subject56" name="subject56" <?php if(isset($_GET['id'])){if(($match_sub1_id == 56) || ($match_sub2_id == 56)){echo 'checked';}}elseif(isset($_POST['subject56'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject56">
                                                            Khoisan
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="57" id="subject57" name="subject57" <?php if(isset($_GET['id'])){if(($match_sub1_id == 57) || ($match_sub2_id == 57)){echo 'checked';}}elseif(isset($_POST['subject57'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject57">
                                                            Nambya
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="58" id="subject58" name="subject58" <?php if(isset($_GET['id'])){if(($match_sub1_id == 58) || ($match_sub2_id == 58)){echo 'checked';}}elseif(isset($_POST['subject58'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject58">
                                                            Ndau
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="24" id="subject24" name="subject24" <?php if(isset($_GET['id'])){if(($match_sub1_id == 24) || ($match_sub2_id == 24)){echo 'checked';}}elseif(isset($_POST['subject24'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject24">
                                                            Ndebele
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="28" id="subject28" name="subject28" <?php if(isset($_GET['id'])){if(($match_sub1_id == 28) || ($match_sub2_id == 28)){echo 'checked';}}elseif(isset($_POST['subject28'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject28">
                                                            Shona
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="59" id="subject59" name="subject59" <?php if(isset($_GET['id'])){if(($match_sub1_id == 59) || ($match_sub2_id == 59)){echo 'checked';}}elseif(isset($_POST['subject59'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject59">
                                                            Sotho
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="33" id="subject33" name="subject33" <?php if(isset($_GET['id'])){if(($match_sub1_id == 33) || ($match_sub2_id == 33)){echo 'checked';}}elseif(isset($_POST['subject33'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject33">
                                                            Tonga
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="60" id="subject60" name="subject60" <?php if(isset($_GET['id'])){if(($match_sub1_id == 60) || ($match_sub2_id == 60)){echo 'checked';}}elseif(isset($_POST['subject60'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject60">
                                                            Tshvenda
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="61" id="subject61" name="subject61" <?php if(isset($_GET['id'])){if(($match_sub1_id == 61) || ($match_sub2_id == 61)){echo 'checked';}}elseif(isset($_POST['subject61'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject61">
                                                            Tswana
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="62" id="subject62" name="subject62" <?php if(isset($_GET['id'])){if(($match_sub1_id == 62) || ($match_sub2_id == 62)){echo 'checked';}}elseif(isset($_POST['subject62'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject62">
                                                            Xangani
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="63" id="subject63" name="subject63" <?php if(isset($_GET['id'])){if(($match_sub1_id == 63) || ($match_sub2_id == 63)){echo 'checked';}}elseif(isset($_POST['subject63'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject63">
                                                            Xhosa
                                                          </label>
                                                        </div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div></div>
                                        <div class="row"></div>
                                        <div class="row">
                                            <div class="accordion" id="accordionLIZIL">
                                              <div class="card" id="lizindl">
                                                <div class="card-header" id="lizil">
                                                  <h5 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseLizil" aria-expanded="true" aria-controls="collapseLizil">
                                                      Literature In</br>Zimbabwean</br>Indigenous</br>Languages
                                                    </button>
                                                  </h5>
                                                </div>
                                            
                                                <div id="collapseLizil" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSubs">
                                                  <div class="card-body">
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="65" id="subject65" name="subject65" <?php if(isset($_GET['id'])){if(($match_sub1_id == 65) || ($match_sub2_id == 65)){echo 'checked';}}elseif(isset($_POST['subject65'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject65">
                                                            Barwe
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="66" id="subject66" name="subject66" <?php if(isset($_GET['id'])){if(($match_sub1_id == 66) || ($match_sub2_id == 66)){echo 'checked';}}elseif(isset($_POST['subject66'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject66">
                                                            Chewa
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="67" id="subject67" name="subject67" <?php if(isset($_GET['id'])){if(($match_sub1_id == 67) || ($match_sub2_id == 67)){echo 'checked';}}elseif(isset($_POST['subject67'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject67">
                                                            Kalanga
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="68" id="subject68" name="subject68" <?php if(isset($_GET['id'])){if(($match_sub1_id == 68) || ($match_sub2_id == 68)){echo 'checked';}}elseif(isset($_POST['subject68'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject68">
                                                            Khoisan
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="69" id="subject69" name="subject69" <?php if(isset($_GET['id'])){if(($match_sub1_id == 69) || ($match_sub2_id == 69)){echo 'checked';}}elseif(isset($_POST['subject69'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject69">
                                                            Nambya
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="70" id="subject70" name="subject70" <?php if(isset($_GET['id'])){if(($match_sub1_id == 70) || ($match_sub2_id == 70)){echo 'checked';}}elseif(isset($_POST['subject70'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject70">
                                                            Ndau
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="71" id="subject71" name="subject71" <?php if(isset($_GET['id'])){if(($match_sub1_id == 71) || ($match_sub2_id == 71)){echo 'checked';}}elseif(isset($_POST['subject71'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject71">
                                                            Ndebele
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="72" id="subject72" name="subject72" <?php if(isset($_GET['id'])){if(($match_sub1_id == 72) || ($match_sub2_id == 72)){echo 'checked';}}elseif(isset($_POST['subject72'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject72">
                                                            Shona
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="73" id="subject73" name="subject73" <?php if(isset($_GET['id'])){if(($match_sub1_id == 73) || ($match_sub2_id == 73)){echo 'checked';}}elseif(isset($_POST['subject73'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject73">
                                                            Sotho
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="74" id="subject74" name="subject74" <?php if(isset($_GET['id'])){if(($match_sub1_id == 74) || ($match_sub2_id == 74)){echo 'checked';}}elseif(isset($_POST['subject74'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject74">
                                                            Tonga
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="75" id="subject75" name="subject75" <?php if(isset($_GET['id'])){if(($match_sub1_id == 75) || ($match_sub2_id == 75)){echo 'checked';}}elseif(isset($_POST['subject75'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject75">
                                                            Tshvenda
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="76" id="subject76" name="subject76" <?php if(isset($_GET['id'])){if(($match_sub1_id == 76) || ($match_sub2_id == 76)){echo 'checked';}}elseif(isset($_POST['subject76'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject76">
                                                            Tswana
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="77" id="subject77" name="subject77" <?php if(isset($_GET['id'])){if(($match_sub1_id == 77) || ($match_sub2_id == 77)){echo 'checked';}}elseif(isset($_POST['subject77'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject77">
                                                            Xangani
                                                          </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="checkbox" value="78" id="subject78" name="subject78" <?php if(isset($_GET['id'])){if(($match_sub1_id == 78) || ($match_sub2_id == 78)){echo 'checked';}}elseif(isset($_POST['subject78'])){echo 'checked';}  ?>>
                                                          <label class="form-check-label" for="subject78">
                                                            Xhosa
                                                          </label>
                                                        </div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div></div>
                                        <div class="row"></div>
                                        <div class="row"></div>
                                        <div class="row"></div>
                                        <div class="row"></div>
                                        <div class="row"></div>
                                        <div class="row"></div>
                                        <div class="row"></div>
                                        <div class="row"></div>
                                    </div> <!-- -->
                                </div>
                            </div>
                          </div>
                          <?php if(!empty($client_id)){
                                      echo '<input type="hidden" value="'.$client_id.'" name="client_id"/>';
                                }
                                if(!empty($mpp_id)){
                                      echo '<input type="hidden" value="'.$mpp_id.'"/>';
                                }
                                if(!empty($mpt_id)){
                                      echo '<input type="hidden" value="'.$mpt_id.'"/>';
                                }
                                if(isset($mpd_id)){
                                      echo '<input type="hidden" value="'.$mpd_id.'"/>';
                                }
                                if(isset($mpd2_id)){
                                      echo '<input type="hidden" value="'.$mpd2_id.'"/>';
                                }
                                if(!empty($mpl_id)){
                                      echo '<input type="hidden" value="'.$mpl_id.'"/>';
                                }

                                if(!empty($mpl2_id)){
                                      echo '<input type="hidden" value="'.$mpl2_id.'"/>';
                                }
                                if(!empty($mpl3_id)){
                                      echo '<input type="hidden" value="'.$mpl3_id.'"/>';
                                }
                                if(!empty($mps_id)){
                                      echo '<input type="hidden" value="'.$mps_id.'"/>';
                                }
                                if(!empty($mps2_id)){
                                      echo '<input type="hidden" value="'.$mps2_id.'"/>';
                                }
                                if(!empty($mps3_id)){
                                      echo '<input type="hidden" value="'.$mps3_id.'"/>';
                                }
                                if(!empty($mps4_id)){
                                      echo '<input type="hidden" value="'.$mps4_id.'"/>';
                                }
                                if(!empty($mps5_id)){
                                      echo '<input type="hidden" value="'.$mps5_id.'"/>';
                                }
                                if(!empty($mps6_id)){
                                      echo '<input type="hidden" value="'.$mps6_id.'"/>';
                                }
                                if(!empty($mps7_id)){
                                      echo '<input type="hidden" value="'.$mps7_id.'"/>';
                                }
                                if(!empty($mps8_id)){
                                      echo '<input type="hidden" value="'.$mps8_id.'"/>';
                                }
                                if(!empty($mps9_id)){
                                      echo '<input type="hidden" value="'.$mps9_id.'"/>';
                                }
                                if(!empty($mps10_id)){
                                      echo '<input type="hidden" value="'.$mps10_id.'"/>';
                                }
                          ?>
                          <hr>
                          <?php //hide Terms of service agreement confrmation for admins
                          if($_SESSION['logged_status'] != 'SU' && $_SESSION['logged_status'] != 'AD'){ ?>
                          <div class="container">
                              <div class="row">
                                  <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="agree" id="agree" name="agree">
                                    <label class="form-check-label" for="agree">
                                      I have read and agree to the <a href="inc/docs/Terms_and_Conditions_for_SwopMatch_ Handler_Service_v1.pdf">Terms and Conditions</a>
                                    </label>
                                  </div>
                              </div>
                          </div>
                          <?php }
                          if(!empty($_SESSION['logged_in']) &&
                              ($_SESSION['logged_status'] == 'SU' || $_SESSION['logged_status'] == 'AD') && !empty($_GET['id']) && $_GET['id'] != 'suser'){ //checks if the administrator is logged in and hides status for super admin 
                          ?>
                          <!-- <div id="hide-group"> -->
                          <div class="row my-1" <?php //disable select for non-superuser if client status is active
                                                    if($logged_status != 'SU'){ echo 'style = "display:none"'; } ?>>
                              <div class="col-2"></div>
                              <div class="col-8 text-center">
                                  <label for ="client_status"><b>Client Status</b></label>
                                  <select id="client_status" name="client_status" class="custom-select">
                                    <option value="">Select One Option</option>
                                    <?php pull_status($statuses);?>
                                  </select>
                                </div>
                              <div class="col-2"></div>
                          </div>
                          <?php } ?>
                          <div class="row my-1">
                              <div class="col-2"></div>
                              <div class="col-8">
                                  <button type="submit" id="button" class="btn btn-primary btn-lg btn-block" <?php if(!empty($client_id) && !empty($client_status) && $client_status != 'A' && $client_status != 'N' && $client_status != 'R' && $client_status != 'AD' && $client_status != 'SU'){//disables the submit button for any client whose status is not active 'A' or 'N' or 'R'
                                  echo 'disabled = "disabled"'; }?>>Submit</button>
                              </div>
                              <div class="col-2"></div>
                          </div>
                          <!-- </div> -->
                          </div>
                      <div id = "status">
                      <?php
                          if(!empty($client_status) && $client_status == 'A'){?>
                             <blockquote class="blockquote text-center"><small>Your account is <strong>ACTIVE</strong> and a match has not been found yet. We will notify you as soon as a match is found. You may change your preferences by updating your profile if you so wish.</small></blockquote><blockquote class="blockquote text-center"></small>Thank you for using our service.</small></blockquote>
                            <?php 
                                  }elseif(!empty($client_status) && $client_status == 'D'){?>
                                     <blockquote class="blockquote text-center"><small>Your account is <strong>DEACTIVATED.</small></strong></blockquote><blockquote class="blockquote text-center">Thank you for using our service.</blockquote>
                            <?php
                                  }elseif(!empty($client_status) && $client_status == 'RN'){?>
                                     <blockquote class="blockquote text-center"><small>A match has been <strong>RESERVED</strong> for you. Please pay your $20.00 service fee using <strong>Ecocash Biller code 204320</strong> by <?php echo /*substr($match_exp_date, 0, 10)*/$match_exp_date.". "; ?></small></blockquote><blockquote class="blockquote text-center">Thank you for using our service.</blockquote>
                            <?php
                                  }elseif(!empty($client_status) && $client_status == 'RP'){?>
                                      <blockquote class="blockquote text-center"><small>A match has been <strong>RESERVED</strong> for you. Please await finalization with the other part which we hope to be on/before <?php echo " "./*substr($match_exp_date, 0, 10)*/$match_exp_date.". "; ?> As soon as we finalize with the other part full details of your match will be advised you.</small></blockquote><blockquote class="blockquote text-center">Thank you for using our service.</blockquote>
                            <?php
                                  }elseif(!empty($client_status) && $client_status == 'N'){?>
                                      <blockquote class="blockquote text-center"><strong>CLIENT AWAITING ADMINISTRATOR REVIEW!</strong>.</blockquote>
                            <?php
                                  }elseif(!empty($client_status) && $client_status == 'R'){?>
                                      <blockquote class="blockquote text-center"><strong>APPLICANT REJECTED!</strong>.</blockquote>
                            <?php
                                  }elseif(!empty($client_status) && $client_status == 'BL'){?>
                                     <blockquote class="blockquote text-center"><small>Your account is <strong>BLACKLISTED</strong> due to a breach of our <a href="" style="padding: 0px 1px 0px 1px; width: 23%;">Terms & Conditions</a>.</blockquote><blockquote class="blockquote text-center">Your may reactivate your account <a href="" style="padding: 0px 1px 0px 1px; width: 8%;">HERE</a>.</small></blockquote>
                            <?php }elseif(!empty($client_status) && $client_status == 'MF'){?>
                                     <blockquote class="blockquote text-center"><small>Your match request has been <strong>SUCCESSFULLY FINALIZED</strong> and the account DEACTIVATED. Please REACTIVATE your account if you require to be matched again.</blockquote><blockquote class="blockquote text-center">Thank you for using our service.</small></blockquote>
                            <?php }?>
                    </div>
                          <div class="row my-1">
                              <div class="col-2"></div>
                              <div class="col-8">
                                  <div class="row">
                                      <div class="col" <?php 
                                                        if(!empty($error_message) || empty($_SESSION['logged_in'])){ 
                                                        echo 'style = "display: none;"'; 
                                                        }  ?>>
                                          <!-- <button  id="logOut" class="btn btn-primary btn-lg btn-block" onclick="window.location.href='logout.php'">Log Out</button> -->
                                          <a href="logout.php" id="logOut" class="btn btn-primary btn-lg btn-block" role="button" aria-pressed="true">Log Out</a>
                                      </div>
                                      <div class="col" 
                                                    <?php 
                                                        if(isset($_SESSION['logged_in']) &&  !empty($_SESSION['logged_in'])){ 
                                                        echo 'style = "display: none;"'; } ?>>
                                          <button type="reset" id="reset-button" class="btn btn-primary btn-lg btn-block">Reset</button>
                                      </div>
                                  </div>
                                  </div>
                              <div class="col-2"></div>
                          </div>
                        </fieldset>
                    </form>
                    <?php resume: ?>
                </div>
            </div>
        </div>
    <div class="col-1"></div>
</div>
<?php include("inc/footer.php"); ?>