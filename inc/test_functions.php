<?php
function create_client(){
		
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		//header(Location:'payreg.php');
		//on confirmation of registration payment
		
		
			
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
				
				include ('selected_subs.php');
			
		INSERT INTO match_pref_provinces 
			(mpp_province_id, mpp_client_ec_no, mpp_client_level_taught, mpp_sub1_id, mpp_sub2_id)
				VALUES ($prefProv_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);
				
		}else if (isset($_POST["preferred_town"])){
			$prefTown = $_POST["preferred_town"];
			
				foreach($towns as $key=>$value){
					if(in_array($prefTown,$value)){
						  $prefTown_id = $value['town_id'];
					}
				}	
			include ('selected_subs.php');
			
		INSERT INTO match_pref_towns 
			(mpt_town_id, mpt_client_ec_no, mpt_client_level_taught, mpt_sub1_id, mpt_sub2_id)
				VALUES ($prefTown_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);
				
		}else if (isset($_POST["preferred_district1"])){
			$prefDistrict1 = $_POST["preferred_district1"];
			
			foreach($districts as $key=>$value){
					if(in_array($prefDistrict1,$value)){
						  $prefDistrict1_id = $value['distr_id'];
					}
				}
			include ('selected_subs.php');
			
			INSERT INTO match_pref_districts 
			(mpd_distr_id, mpd_client_ec_no, mpd_client_level_taught, mpd_sub1_id, mpd_sub2_id)
				VALUES ($prefDistr1_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);
				
		}else if (isset($_POST["preferred_district2"])){
			$prefDistrict2 = $_POST["preferred_district2"];
			
			foreach($districts as $key=>$value){
					if(in_array($prefDistrict2,$value)){
						  $prefDistrict2_id = $value['distr_id'];
					}
				}
			
			include ('selected_subs.php');
			
			INSERT INTO match_pref_districts 
			(mpd_distr_id, mpd_client_ec_no, mpd_client_level_taught, mpd_sub1_id, mpd_sub2_id)
				VALUES ($prefDistr2_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);
				
		}else if (isset($_POST["preferred_schools1"])){
			$prefSchools1 = $_POST["preferred_schools1"];
			
			foreach($schools as $key=>$value){
					if(in_array($prefSchools1,$value)){
						  $prefSchools1_id = $value['school_id'];
					}
				}
			
				if (isset($_POST["preferred_schools2"])){
					$prefSchools2 = $_POST["preferred_schools2"];
					
					foreach($schools as $key=>$value){
						if(in_array($prefSchools2,$value)){
							  $prefSchools2_id = $value['school_id'];
						}
					}
				}if (isset($_POST["preferred_schools3"])){
					$prefSchools3 = $_POST["preferred_schools3"];
					
					foreach($schools as $key=>$value){
						if(in_array($prefSchools3,$value)){
							  $prefSchools3_id = $value['school_id'];
						}
					}
				}
				
				if (isset($_POST["preferred_schools4"])){
					$prefSchools4 = $_POST["preferred_schools4"];
					
					foreach($schools as $key=>$value){
						if(in_array($prefSchools4,$value)){
							  $prefSchools4_id = $value['school_id'];
						}
					}
				}
				
				if (isset($_POST["preferred_schools5"])){
					$prefSchools5 = $_POST["preferred_schools5"];
					
					foreach($schools as $key=>$value){
						if(in_array($prefSchools5,$value)){
							  $prefSchools5_id = $value['school_id'];
						}
					}
				}
				
				if (isset($_POST["preferred_schools6"])){
					$prefSchools6 = $_POST["preferred_schools6"];
					
					foreach($schools as $key=>$value){
						if(in_array($prefSchools6,$value)){
							  $prefSchools6_id = $value['school_id'];
						}
					}
				}
				
				if (isset($_POST["preferred_schools7"])){
					$prefSchools7 = $_POST["preferred_schools7"];
					
					foreach($schools as $key=>$value){
						if(in_array($prefSchools7,$value)){
							  $prefSchools7_id = $value['school_id'];
						}
					}
				}
				
				if (isset($_POST["preferred_schools8"])){
					$prefSchools8 = $_POST["preferred_schools8"];
					
					foreach($schools as $key=>$value){
						if(in_array($prefSchools8,$value)){
							  $prefSchools8_id = $value['school_id'];
						}
					}
				}
				
				if (isset($_POST["preferred_schools9"])){
					$prefSchools9 = $_POST["preferred_schools9"];
					
					foreach($schools as $key=>$value){
						if(in_array($prefSchools9,$value)){
							  $prefSchools9_id = $value['school_id'];
						}
					}
				}
				
				if (isset($_POST["preferred_schools10"])){
					$prefSchools10 = $_POST["preferred_schools10"];
					
					foreach($schools as $key=>$value){
						if(in_array($prefSchools10,$value)){
							  $prefSchools10_id = $value['school_id'];
						}
					}
				}
			
			
			include ('selected_subs.php');
			
		if (isset($prefSchool1_id)){
			INSERT INTO match_pref_schools 
			(mps_school_id, mps_client_ec_no, mps_client_level_taught, mps_sub1_id, mps_sub2_id)
				VALUES ($prefSchool1_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);	
		}
		if (isset($prefSchool2_id)){
			INSERT INTO match_pref_schools 
			(mps_school_id, mps_client_ec_no, mps_client_level_taught, mps_sub1_id, mps_sub2_id)
				VALUES ($prefSchool2_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);	
		}
		if (isset($prefSchool3_id)){
			INSERT INTO match_pref_schools 
			(mps_school_id, mps_client_ec_no, mps_client_level_taught, mps_sub1_id, mps_sub2_id)
				VALUES ($prefSchool3_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);	
		}
		if (isset($prefSchool4_id)){
			INSERT INTO match_pref_schools 
			(mps_school_id, mps_client_ec_no, mps_client_level_taught, mps_sub1_id, mps_sub2_id)
				VALUES ($prefSchool4_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);	
		}
		if (isset($prefSchool5_id)){
			INSERT INTO match_pref_schools 
			(mps_school_id, mps_client_ec_no, mps_client_level_taught, mps_sub1_id, mps_sub2_id)
				VALUES ($prefSchool5_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);	
		}
		if (isset($prefSchool6_id)){
			INSERT INTO match_pref_schools 
			(mps_school_id, mps_client_ec_no, mps_client_level_taught, mps_sub1_id, mps_sub2_id)
				VALUES ($prefSchool6_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);	
		}
		if (isset($prefSchool7_id)){
			INSERT INTO match_pref_schools 
			(mps_school_id, mps_client_ec_no, mps_client_level_taught, mps_sub1_id, mps_sub2_id)
				VALUES ($prefSchool7_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);	
		}
		if (isset($prefSchool8_id)){
			INSERT INTO match_pref_schools 
			(mps_school_id, mps_client_ec_no, mps_client_level_taught, mps_sub1_id, mps_sub2_id)
				VALUES ($prefSchool8_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);	
		}
		if (isset($prefSchool9_id)){
			INSERT INTO match_pref_schools 
			(mps_school_id, mps_client_ec_no, mps_client_level_taught, mps_sub1_id, mps_sub2_id)
				VALUES ($prefSchool9_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);	
		}
		if (isset($prefSchool10_id)){
			INSERT INTO match_pref_schools 
			(mps_school_id, mps_client_ec_no, mps_client_level_taught, mps_sub1_id, mps_sub2_id)
				VALUES ($prefSchool10_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);	
		}
		
			
	}else if (isset($_POST["preferred_location1"])){
			$prefLocations1 = $_POST["preferred_location1"];
			
			foreach($locations as $key=>$value){
					if(in_array($prefLocations1,$value)){
						  $prefLocations1_id = $value['loc_id'];
					}
				}
			
				if (isset($_POST["preferred_location2"])){
					$prefLocations2 = $_POST["preferred_location2"];
			
				foreach($locations as $key=>$value){
					if(in_array($prefLocations2,$value)){
						  $prefLocations2_id = $value['loc_id'];
						}
					}
				}if (isset($_POST["preferred_location3"])){
					$prefLocations3 = $_POST["preferred_location3"];
			
				foreach($locations as $key=>$value){
					if(in_array($prefLocations3,$value)){
						  $prefLocations3_id = $value['loc_id'];
						}
					}
				}		
			
			include ('selected_subs.php');
			
		if (isset($prefLocation1_id)){
			INSERT INTO match_pref_locations
			(mpl_loc_id, mpl_client_ec_no, mpl_client_level_taught, mpl_sub1_id, mpl_sub2_id)
				VALUES ($prefLocation1_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);	
		}
		if (isset($prefLocation2_id)){
			INSERT INTO match_pref_locations
			(mpl_school_id, mpl_client_ec_no, mpl_client_level_taught, mpl_sub1_id, mpl_sub2_id)
				VALUES ($prefLocation2_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);		
		}
		if (isset($prefLocation3_id)){
			INSERT INTO match_pref_locations
			(mpl_school_id, mpl_client_ec_no, mpl_client_level_taught, mpl_sub1_id, mpl_sub2_id)
				VALUES ($prefLocation3_id, $ecNumber, $levelTaught, $sub1ID, $sub2ID);		
		}
		
			
	}
}
}
	/* $sql = 'SELECT match_client_ec_no FROM matches WHERE match_province_id = $prefProv_id AND match_level_taught = $levelTaught ORDER BY match_id ASC LIMIT 1';
	
	if (!empty($sql)){
		
			UPDATE matches SET  $prefProv_id';
			echo 'Congratulations!! A match has been found.';
			echo 'You have up to 7 days to pay the Match fee of '.$matchfee.' before the reservation of the match expires.';
			echo 'If you want to pay now, please click the Pay button else click the Pay Later button'
			echo 'Thank you';
		}else{
			
			echo 'Thank you for registering with us. We will contact you as soon as a match is found.';
			header(Location:'index.php'); */
			
			
		//var_dump($prefSchools);
?>