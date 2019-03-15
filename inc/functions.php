<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('html_errors', 1);

	include_once 'connection.php';

	//the below extracts all statuses from the statuses database into an array to be used in select menus
  try{$results_statuses = $db->query("SELECT * FROM statuses");

	}catch (Exception $e){
			echo 'Failed to retrieve statuses';
			exit;

	}
	$statuses = $results_statuses->fetchAll(PDO::FETCH_ASSOC);

	//the below extracts ec numbers of all rejected clients from the clients table to be deleted
  try{$results_deleted = $db->query("SELECT client_ec_no FROM clients WHERE client_status = 'R'");

	}catch (Exception $e){
			echo 'Failed to retrieve rejected clients';
			exit;

	}
	
	$rejected = $results_deleted->fetchAll(PDO::FETCH_ASSOC);
	
	//the below extracts all towns from the towns database into an array to beF$ used in select menus
  try{$results_towns = $db->query("SELECT town_id, town_name, town_province_id FROM towns");

	}catch (Exception $e){
			echo 'Failed to retrieve towns';
			exit;

	}
	$towns = $results_towns->fetchAll(PDO::FETCH_ASSOC);


	//the below extracts all provinces from the provinces database into an array to be used in select menus
  try{$results_provinces = $db->query("SELECT * FROM provinces");

	}catch (Exception $e){
			echo 'Failed to retrieve provinces';
			exit;

	}
	$provinces = $results_provinces->fetchAll(PDO::FETCH_ASSOC);


	//the below extracts all districts from the districts database into an array to be used in select menus
  try{$results_districts = $db->query("SELECT * FROM districts ORDER BY distr_name");

	}catch (Exception $e){
			echo 'Failed to retrieve districts';
			exit;

	}
	$districts = $results_districts->fetchAll(PDO::FETCH_ASSOC);


	//the below extracts all locations from the locations database into an array to be used in select menus
  try{$results_locations = $db->query("SELECT loc_id, loc_name FROM locations ORDER BY loc_name");

	}catch (Exception $e){
			echo 'Failed to retrieve locations';
			exit;

	}
	$locations = $results_locations->fetchAll(PDO::FETCH_ASSOC);


	//the below extracts all schools from the schools database into an array to be used in select menus
      try{
        $results_schools = $db->query("SELECT * FROM schools ORDER BY school_name");

      }catch (Exception $e){
          echo 'Failed to retrieve schools';
          exit;

      
    }
    $schools = $results_schools->fetchAll(PDO::FETCH_ASSOC);
    
   //the below extracts client's current details from the 'match_current_schools' database into an array to be used when calling records for update
  try{$results_mcs = $db->query("SELECT mcs_school_id, mcs_province_id, mcs_distr_id, mcs_town_id, mcs_loc_id, mcs_client_ec_no, mcs_sub1_id, mcs_sub2_id FROM match_current_schools ORDER BY mcs_client_ec_no");

	}catch (Exception $e){
			echo 'Failed to retrieve mcs';
			exit;

	}
	$mcs = $results_mcs->fetchAll(PDO::FETCH_ASSOC);

  //this below query extracts subjects details to be used in selecting subjects taught
	try{$results_subjects = $db->query("SELECT sub_id, sub_name FROM subjects ORDER BY sub_name");

	}catch (Exception $e){
			echo 'Failed to retrieve subjects';
			exit;

	}
	$subjects = $results_subjects->fetchAll(PDO::FETCH_ASSOC);
	
	//the below counts the number of ACTIVE clients awaiting to be matched
    try{$results_active = $db->query("SELECT count(*) FROM clients WHERE client_status = 'A'");

	}catch (Exception $e){
			echo 'Failed to count active clients';
			exit;

	}
	$active = $results_active->fetchColumn();
  
  //the below sums the unpaid commission for an individual agent
    function get_unpaid_comm($agent_ac_no){
        
        include ('connection.php');
        
        $sql = 'SELECT count(*) FROM `clients` WHERE `client_agent_comm_status` = "US" AND `client_status` = "A" AND `client_agent_id` = ?';
        try{
            $results_unpaid = $db->prepare($sql);
            $results_unpaid->bindValue(1, $agent_ac_no, PDO::PARAM_INT);
            $results_unpaid->execute();

          }catch (Exception $e){
              echo 'Failed to retrieve unpaid commission';
              exit;

          }
        return $results_unpaid->fetchColumn();
    }
	
	//function to validate date input for reports
	function isDate($string) {
    $matches = array();
    $pattern = '/^([0-9]{1,2})\\/([0-9]{1,2})\\/([0-9]{4})$/';
    if (!preg_match($pattern, $string, $matches)) return false;
    if (!checkdate($matches[2], $matches[1], $matches[3])) return false;
    return true;
}

    //function to validate date for payments
        function validDate($string) {
    $matches = array();
    $pattern = '/^([0-9]{2})\-{1}([0-9]{2})\-{1}([0-9]{4})$/';
    if (!preg_match($pattern, $string, $matches)) return false;
    if (!checkdate($matches[2], $matches[1], $matches[3])) return false;
    return true;
}
    
	
	//the below checks the status of a client who intends to login
    function get_status($ecNumber){
        
        include ('connection.php');
        
        if(!is_int($ecNumber)){
            $sql = "SELECT client_status FROM clients WHERE client_ec_no = ?";
        }else{
            $sql = "SELECT agent_status FROM agents WHERE agent_ac_no = ?";
        }
        try {
                $results = $db->prepare($sql);
                $results->bindValue(1, $ecNumber);
                $results->execute();
    	}catch (Exception $e){
    			echo 'Failed to retrieve account status';
    			exit;
    
    	}
    	return $results->fetchColumn();
        
    }
    
        //function to fetch the school name from the school id for agent report
        function get_school_name($school_id){
            include ('inc/connection.php');
            $sql = "SELECT school_name FROM schools WHERE school_id = ?";
                try {
                        $results = $db->prepare($sql);
                        $results->bindValue(1, $school_id);
                        $results->execute();
            	}catch (Exception $e){
            			echo 'Failed to retrieve school name';
            			exit;
            	}
            	return $results->fetchColumn();
        }


	//the below extracts all matched clients and extracts those that have paid but have not yet been allocated receipt numbers. Client details are taken from the clients table
	
  try{$results_paid = $db->query("SELECT matched_ec_no, client_first_name, client_mobile_no FROM matched_clients 
    INNER JOIN clients
    ON matched_clients.matched_ec_no = clients.client_ec_no
    WHERE (matched_receipt_ref  IS NOT NULL AND matched_receipt_no = 0) ORDER BY matched_paynt_date");

	}catch (Exception $e){
			echo 'Failed to retrieve unreceipted clients';
			exit;

	}
	$unreceipted = $results_paid->fetchAll(PDO::FETCH_ASSOC);
	
	
	//the below extracts all matched clients who have successfully paid and ready to be send full details of their matches
	
  try{$results_final = $db->query("SELECT DISTINCT(matched_ec_no), matched.client_first_name AS first_name, matched.client_mobile_no AS mobile_no, co_match.client_first_name AS co_first_name, co_match.client_last_name AS co_last_name, co_match.client_mobile_no AS co_mobile_no FROM matched_clients
        INNER JOIN clients AS co_match
        ON matched_clients.matched_co_ec_no = co_match.client_ec_no
        INNER JOIN clients AS matched
        ON matched_clients.matched_ec_no = matched.client_ec_no
        WHERE STR_TO_DATE(matched_res_end_time, '%d-%m-%Y %T') <> NOW()
        AND matched_status = 'RP'
        AND matched_co_ec_no IN (
        SELECT matched_ec_no FROM matched_clients
        WHERE STR_TO_DATE(matched_res_end_time, '%d-%m-%Y %T') <> NOW()
        AND matched_status = 'RP'
        )");

	}catch (Exception $e){
			echo 'Failed to retrieve clients to be finalized';
			exit;

	}
	
	$final = $results_final->fetchAll(PDO::FETCH_ASSOC);
	
	
		//the below extracts all matched clients and extracts those that have paid but have not yet been allocated receipt numbers
	
  try{$results_completed = $db->query("SELECT * FROM matched_clients WHERE matched_status = 'RP'");

	}catch (Exception $e){
			echo 'Failed to retrieve Paid Up clients';
			exit;

	}
	$paid_up = $results_completed->fetchAll(PDO::FETCH_ASSOC);
	
	//this function prepares a drop-down of all statuses and checks whether an option has been selected during a session for data persistence
	function pull_status(array $statuses){
	    global $logged_status;

		/*
      Array
        (
            [0] => Array
                (
                    [status_code] => A
                    [status_desc] => Active
                )
        )
    */
    $i = 0;
    foreach ($statuses as $key=>$value){
      
      echo '<option value='.'"'.$value['status_code'].'"';

				global $client_status;
        if(isset($_POST['status_code'])){
					if($_POST['status_code'] == $value['status_code']){
						echo 'selected';
					}
          }elseif($client_status == $value['status_code']){
            echo 'selected';
          }

			echo '>'.$value['status_desc'].'</option>';
            
            if(++$i == 20 && $logged_status != 'SU'){//trim list if user is not super admin
                break;
            }
		}

	echo '</select>';

}

  //this function prepares a drop-down of all provinces for preferred province option 1 and checks whether an option has been selected during a session for data persistence
	function all_provinces(array $provinces){

		/*
      Array
        (
            [0] => Array
                (
                    [province_name] => Bulawayo
                    [province_id] => 2
                )
        )
    */
    foreach ($provinces as $key=>$value){
      
      echo '<option value='.'"'.$value['province_id'].'"';

				global $mpp_province_id;
        if(isset($_POST['preferred_province'])){
					if($_POST['preferred_province'] == $value['province_id']){
						echo 'selected';
					}
          }elseif($mpp_province_id == $value['province_id']){
            echo 'selected';
          }

			echo '>'.$value['province_name'].'</option>';
		}

	echo '</select>';

	}
	
	//this function prepares a drop-down of all provinces for preferred province option 2 and checks whether an option has been selected during a session for data persistence
	function all_provinces2(array $provinces){

		/*
      Array
        (
            [0] => Array
                (
                    [province_name2] => Bulawayo
                    [province_id] => 2
                )
        )
    */
    foreach ($provinces as $key=>$value){
      
      echo '<option value='.'"'.$value['province_id'].'"';

				global $mpp2_province_id;
        if(isset($_POST['preferred_province2'])){
					if($_POST['preferred_province2'] == $value['province_id']){
						echo 'selected';
					}
          }elseif($mpp2_province_id == $value['province_id']){
            echo 'selected';
          }

			echo '>'.$value['province_name'].'</option>';
		}

	echo '</select>';

	}

  //this function prepares a drop-down of all current provinces and checks whether an option has been selected during a session for data persistence
	function all_provinces_curr(array $provinces){
    
    global $curr_province_id;

		/*
      Array
        (
            [0] => Array
                (
                    [province_name] => Bulawayo
                    [province_id] => 2
                )
        )
    */
    foreach ($provinces as $key=>$value){
      echo '<option value='.'"'.$value['province_id'].'"';
      
      global $mcs_client_details;
      if(isset($_POST['current_province'])){
        if($_POST['current_province'] == $value['province_id']){
          echo 'selected';
        }
          }elseif(!empty($_GET['id']) && $curr_province_id == $value['province_id']){
          echo 'selected';
        }
        
      echo '>'.$value['province_name'].'</option>';
    }
	}
	
	//this function prepares a drop-down of all towns for inserting locations
	function all_towns(array $towns){

		/*
      Array
        (
            [0] => Array
                (
                    [town_id] => 33
                    [town_name] => Beitbridge
                )
        )
    */
    foreach ($towns as $key=>$value){
      
      echo '<option value='.'"'.$value['town_id'].'"';

			echo '>'.$value['town_name'].'</option>';
		}

	echo '</select>';

	}
	
	//this function prepares a drop-down of all towns for updating locations
	function all_towns_up(array $towns){

		/*
      Array
        (
            [0] => Array
                (
                    [town_id] => 33
                    [town_name] => Beitbridge
                )
        )
    */
     global $loc_town;
     global $locuptown;
    foreach ($towns as $key=>$value){
      
      echo '<option value='.'"'.$value['town_id'].'"';
      
          if(!empty($_POST['loc_town_nm']) && ($_POST['loc_town_nm'] == $value['town_id'])){
              echo 'selected';
            }elseif(!empty($_GET['loc']) && ($loc_town == $value['town_id'])){
              echo 'selected';
            }

			echo '>'.$value['town_name'].'</option>';
		}

	echo '</select>';

	}
	
	//this function prepares a drop-down of all districts for inserting locations
	function all_territories(array $districts){
    
    global $ex_agent_territory;

		/*
      Array
        (
            [0] => Array
                (
                    [distr_id] => 33
                    [distr_name] => Beitbridge
                )
        )
    */
    foreach ($districts as $key=>$value){
      
      echo '<option value='.'"'.$value['distr_id'].'"';
      
          if(!empty($_POST['agent_territory']) && ($_POST['agent_territory'] == $value['distr_id'])){
              echo 'selected';
            }elseif(!empty($ex_agent_territory) && ($ex_agent_territory == $value['distr_id'])){
              echo 'selected';  
            }

			echo '>'.$value['distr_name'].'</option>';
		}

	echo '</select>';

	}
	
	//this function prepares a drop-down of all districts for inserting locations
	function all_districts(array $districts){

		/*
      Array
        (
            [0] => Array
                (
                    [distr_id] => 33
                    [distr_name] => Beitbridge
                )
        )
    */
     global $loc_distr;
    foreach ($districts as $key=>$value){
      
      echo '<option value='.'"'.$value['distr_id'].'"';

			echo '>'.$value['distr_name'].'</option>';
		}

	echo '</select>';

	}
	
	//this function prepares a drop-down of all districts for updating locations
	function all_districts_up(array $districts){

		/*
      Array
        (
            [0] => Array
                (
                    [distr_id] => 33
                    [distr_name] => Beitbridge
                )
        )
    */
     global $loc_distr;
    foreach ($districts as $key=>$value){
      
      echo '<option value='.'"'.$value['distr_id'].'"';
      
          if(!empty($_POST['loc_distr_nm']) && ($_POST['loc_distr_nm'] == $value['distr_id'])){
              echo 'selected';
            }elseif(!empty($_GET['loc']) && ($loc_distr == $value['distr_id'])){
              echo 'selected';  
            }

			echo '>'.$value['distr_name'].'</option>';
		}

	echo '</select>';

	}
	
	//this function prepares a drop-down of all locations for inserting locations
	function all_locations(array $locations){

		/*
      Array
        (
            [0] => Array
                (
                    [loc_id] => 33
                    [loc_name] => Beitbridge
                )
        )
    */
    global $loc_name;
    foreach ($locations as $key=>$value){
      
      echo '<option value='.'"'.$value['loc_id'].'"';

	    echo '>'.$value['loc_name'].'</option>';
	}

	echo '</select>';

	}
	
	//this function prepares a drop-down of all locations for inserting/updating locations
	function all_locations_up(array $locations){

		/*
      Array
        (
            [0] => Array
                (
                    [loc_id] => 33
                    [loc_name] => Beitbridge
                )
        )
    */
    global $loc_name;
    foreach ($locations as $key=>$value){
      
      echo '<option value='.'"'.$value['loc_id'].'"';
      
      if(!empty($_GET['loc']) && ($loc_name == $value['loc_id'])){
          echo 'selected';
        }

			echo '>'.$value['loc_name'].'</option>';
		}

	echo '</select>';

	}


/*

	//this function prepares a drop-down of all towns for preferred town option and checks whether an option has been selected during a session for data persistence
	function all_towns(array $towns){

		/*
      Array
        (
            [0] => Array
                (
                    [town_id] => 1
                    [town_name] => Beitbridge
                    [town_province_id] => 7
                )
        )
    *//*
    foreach ($towns as $key=>$value){
			//echo '<option value='.'"'.$value['town_name'].' id='.'"'.$value['town_id'].'"';
      echo '<option value='.'"'.$value['town_name'].'"';

      global $mpt_town_id;
				if(isset($_POST['preferred_town'])){
					if($_POST['preferred_town'] == $value['town_name']){
						echo 'selected';
					}

				}elseif($mpt_town_id == $value['town_id']){
            echo 'selected';
          }
			echo '>'.$value['town_name'].'</option>';
		}

	echo '</select>';

	} */
	
	//this function prepares a drop-down of all provinces to use for selection of preferred town option 1 and checks whether an option has been selected during a session for data persistence
        	function all_provs_pref_town1(array $provinces){
        
        		/*
            Array
                (
                    [0] => Array
                        (
                            [province_id] => 1
                            [province_name] => Harare
                        )
                )
            */
            global $mpt_town_province_id;
            foreach ($provinces as $key=>$value){
        
        			echo '<option value='.'"'.$value['province_id'].'"';
              
        				if(isset($_POST['town_name1_province'])){
        					if($_POST['town_name1_province'] == $value['province_id']){
        						echo 'selected';
        						}
                }elseif($mpt_town_province_id == $value['province_id']){
                    echo 'selected';
                }
        			echo '>'.$value['province_name'].'</option>';
            }
          }
          
          //determine whether province has been selected for preferred towns option 1 
              if(!empty($_POST['pref1_town_provinceID'])){ //from AJAX function
                 //if the above condition is met declare variable for province ID
                 $pref1_town_province_id = $_POST['pref1_town_provinceID'];
                 
                //query towns based on province selected above
                $sql = "SELECT * FROM towns
                        WHERE town_province_id = $pref1_town_province_id";

                try {
                    $results_town1 = $db->prepare($sql);
                    $results_town1->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried towns are put in an array
                $towns_pref1 = $results_town1->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select Town</option>';
                //loop through the towns array putting every town in the select menu
                foreach ($towns_pref1 as $key=>$value){
                  echo '<option value='.'"'.$value['town_id'].'"';
                  echo '>'.ucwords(strtolower($value['town_name'])).'</option>';
                }
              }
  
              
              
              function pull_pref_town1(){
                
                global $mpt_town_id;
                global $mpt_town_province_id;
                global $db;
                 
                //query towns based on town selected above
                $sql = "SELECT * FROM towns
                        WHERE town_province_id = $mpt_town_province_id";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried towns are put in an array
                $towns1 = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the towns array putting every town in the select menu
                foreach ($towns1 as $key=>$value){
                  echo '<option value='.'"'.$value['town_id'].'"';
                    if(intval($mpt_town_id) == $value['town_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['town_name'])).'</option>';
                }
              }
              
              //this function prepares a drop-down of all provinces to use for selection of preferred town option 2 and checks whether an option has been selected during a session for data persistence
                	function all_provs_pref_town2(array $provinces){
                
                		/*
                    Array
                        (
                            [0] => Array
                                (
                                    [province_id] => 1
                                    [province_name] => Harare
                                )
                        )
                    */
                    global $mpt2_town_province_id;
                    foreach ($provinces as $key=>$value){
                
                			echo '<option value='.'"'.$value['province_id'].'"';
                      
                				if(isset($_POST['town_name2_province'])){
                					if($_POST['town_name2_province'] == $value['province_id']){
                						echo 'selected';
                						}
                        }elseif($mpt2_town_province_id == $value['province_id']){
                            echo 'selected';
                        }
                			echo '>'.$value['province_name'].'</option>';
                    }
                  }
          
          //determine whether province has been selected for preferred towns option 2 
              if(!empty($_POST['pref2_town_provinceID'])){ //from AJAX function
                 //if the above condition is met declare variable for province ID
                 $pref2_town_province_id = $_POST['pref2_town_provinceID'];
                 
                //query towns based on province selected above
                $sql = "SELECT * FROM towns
                        WHERE town_province_id = $pref2_town_province_id";

                try {
                    $results_town2 = $db->prepare($sql);
                    $results_town2->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried towns are put in an array
                $towns_pref2 = $results_town2->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select Town</option>';
                //loop through the towns array putting every town in the select menu
                foreach ($towns_pref2 as $key=>$value){
                  echo '<option value='.'"'.$value['town_id'].'"';
                  echo '>'.ucwords(strtolower($value['town_name'])).'</option>';
                }
              }
  
              
              
              function pull_pref_town2(){
                
                global $mpt2_town_id;
                global $mpt2_town_province_id;
                global $db;
                 
                //query towns based on town selected above
                $sql = "SELECT * FROM towns
                        WHERE town_province_id = $mpt2_town_province_id";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried towns are put in an array
                $towns2 = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the towns array putting every town in the select menu
                foreach ($towns2 as $key=>$value){
                  echo '<option value='.'"'.$value['town_id'].'"';
                    if(intval($mpt2_town_id) == $value['town_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['town_name'])).'</option>';
                }
              }
              
              //this function prepares a drop-down of all provinces to use for selection of preferred town option 3 and checks whether an option has been selected during a session for data persistence
                	function all_provs_pref_town3(array $provinces){
                
                		/*
                    Array
                        (
                            [0] => Array
                                (
                                    [province_id] => 1
                                    [province_name] => Harare
                                )
                        )
                    */
                    global $mpt3_town_province_id;
                    foreach ($provinces as $key=>$value){
                
                			echo '<option value='.'"'.$value['province_id'].'"';
                      
                				if(isset($_POST['town_name3_province'])){
                					if($_POST['town_name3_province'] == $value['province_id']){
                						echo 'selected';
                						}
                        }elseif($mpt3_town_province_id == $value['province_id']){
                            echo 'selected';
                        }
                			echo '>'.$value['province_name'].'</option>';
                    }
                  }
          
          //determine whether province has been selected for preferred towns option 3 
              if(!empty($_POST['pref3_town_provinceID'])){ //from AJAX function
                 //if the above condition is met declare variable for province ID
                 $pref3_town_province_id = $_POST['pref3_town_provinceID'];
                 
                //query towns based on province selected above
                $sql = "SELECT * FROM towns
                        WHERE town_province_id = $pref3_town_province_id";

                try {
                    $results_town3 = $db->prepare($sql);
                    $results_town3->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried towns are put in an array
                $towns_pref3 = $results_town3->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select Town</option>';
                //loop through the towns array putting every town in the select menu
                foreach ($towns_pref3 as $key=>$value){
                  echo '<option value='.'"'.$value['town_id'].'"';
                  echo '>'.ucwords(strtolower($value['town_name'])).'</option>';
                }
              }
  
              
              
              function pull_pref_town3(){
                
                global $mpt3_town_id;
                global $mpt3_town_province_id;
                global $db;
                 
                //query towns based on town selected above
                $sql = "SELECT * FROM towns
                        WHERE town_province_id = $mpt3_town_province_id";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried towns are put in an array
                $towns3 = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the towns array putting every town in the select menu
                foreach ($towns3 as $key=>$value){
                  echo '<option value='.'"'.$value['town_id'].'"';
                    if(intval($mpt3_town_id) == $value['town_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['town_name'])).'</option>';
                }
              }

	
	//this function prepares a drop-down of all provinces to use for selection of preferred district option 1 and checks whether an option has been selected during a session for data persistence
	function all_provs_pref_distr1(array $provinces){

		/*
    Array
        (
            [0] => Array
                (
                    [province_id] => 1
                    [province_name] => Harare
                )
        )
    */
    global $mpd1_distr_province_id;
    foreach ($provinces as $key=>$value){

			echo '<option value='.'"'.$value['province_id'].'"';
      
				if(isset($_POST['distr_name1_province'])){
					if($_POST['distr_name1_province'] == $value['province_id']){
						echo 'selected';
						}
        }elseif($mpd1_distr_province_id == $value['province_id']){
            echo 'selected';
        }
			echo '>'.$value['province_name'].'</option>';
    }
  }
  
              //determine whether province has been selected for preferred districts option 1 
              if(!empty($_POST['pref1_provinceID'])){ //from AJAX function
                 //if the above condition is met declare variable for province ID
                 $pref1_province_id = $_POST['pref1_provinceID'];
                 
                //query districts based on province selected above
                $sql = "SELECT * FROM districts
                        WHERE distr_province_id = $pref1_province_id";

                try {
                    $results_distr = $db->prepare($sql);
                    $results_distr->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried districts are put in an array
                $districts_pref1 = $results_distr->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select District</option>';
                //loop through the districts array putting every district in the select menu
                foreach ($districts_pref1 as $key=>$value){
                  echo '<option value='.'"'.$value['distr_id'].'"';
                  echo '>'.ucwords(strtolower($value['distr_name'])).'</option>';
                }
              }
              
              function pull_pref_distr1(){
                
                global $mpd_distr_id;
                global $mpd1_distr_province_id;
                global $db;
                 
                //query districts based on province selected above
                $sql = "SELECT * FROM districts
                        WHERE distr_province_id = $mpd1_distr_province_id";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried districts are put in an array
                $districts1 = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the districts array putting every district in the select menu
                foreach ($districts1 as $key=>$value){
                  echo '<option value='.'"'.$value['distr_id'].'"';
                    if(intval($mpd_distr_id) == $value['distr_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['distr_name'])).'</option>';
                }
              }

  	//this function prepares a drop-down of all provinces to use for selection of preferred district option 2 and checks whether an option has been selected during a session for data persistence
        	function all_provs_pref_distr2(array $provinces){
        
        		/*
            Array
                (
                    [0] => Array
                        (
                            [province_id] => 1
                            [province_name] => Harare
                        )
                )
            */
            global $mpd2_distr_province_id;
            foreach ($provinces as $key=>$value){
        
        			echo '<option value='.'"'.$value['province_id'].'"';
              
        				if(isset($_POST['distr_name2_province'])){
        					if($_POST['distr_name2_province'] == $value['province_id']){
        						echo 'selected';
        						}
                }elseif($mpd2_distr_province_id == $value['province_id']){
                    echo 'selected';
                }
        			echo '>'.$value['province_name'].'</option>';
            }
          }
          
          //determine whether province has been selected for preferred districts option 2 
              if(!empty($_POST['pref2_provinceID'])){ //from AJAX function
                 //if the above condition is met declare variable for province ID
                 $pref2_province_id = $_POST['pref2_provinceID'];
                 
                //query districts based on province selected above
                $sql = "SELECT * FROM districts
                        WHERE distr_province_id = $pref2_province_id";

                try {
                    $results_distr = $db->prepare($sql);
                    $results_distr->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried districts are put in an array
                $districts_pref2 = $results_distr->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select District</option>';
                //loop through the districts array putting every district in the select menu
                foreach ($districts_pref2 as $key=>$value){
                  echo '<option value='.'"'.$value['distr_id'].'"';
                  echo '>'.ucwords(strtolower($value['distr_name'])).'</option>';
                }
              }
          
          //this function prepares a drop-down of all provinces to use for selection of preferred district option 3 and checks whether an option has been selected during a session for data persistence
        	function all_provs_pref_distr3(array $provinces){
        
        		/*
            Array
                (
                    [0] => Array
                        (
                            [province_id] => 1
                            [province_name] => Harare
                        )
                )
            */
            global $mpd3_distr_province_id;
            foreach ($provinces as $key=>$value){
        
        			echo '<option value='.'"'.$value['province_id'].'"';
              
        				if(isset($_POST['distr_name3_province'])){
        					if($_POST['distr_name3_province'] == $value['province_id']){
        						echo 'selected';
        						}
                }elseif($mpd3_distr_province_id == $value['province_id']){
                    echo 'selected';
                }
        			echo '>'.$value['province_name'].'</option>';
            }
          }
          
          //determine whether province has been selected for preferred districts option 3 
              if(!empty($_POST['pref3_provinceID'])){ //from AJAX function
                 //if the above condition is met declare variable for province ID
                 $pref3_province_id = $_POST['pref3_provinceID'];
                 
                //query districts based on province selected above
                $sql = "SELECT * FROM districts
                        WHERE distr_province_id = $pref3_province_id";

                try {
                    $results_distr = $db->prepare($sql);
                    $results_distr->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried districts are put in an array
                $districts_pref3 = $results_distr->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select District</option>';
                //loop through the districts array putting every district in the select menu
                foreach ($districts_pref3 as $key=>$value){
                  echo '<option value='.'"'.$value['distr_id'].'"';
                  echo '>'.ucwords(strtolower($value['distr_name'])).'</option>';
                }
              }
          
          //this function prepares a drop-down of all provinces to use for selection of preferred district option 4 and checks whether an option has been selected during a session for data persistence
        	function all_provs_pref_distr4(array $provinces){
        
        		/*
            Array
                (
                    [0] => Array
                        (
                            [province_id] => 1
                            [province_name] => Harare
                        )
                )
            */
            global $mpd4_distr_province_id;
            foreach ($provinces as $key=>$value){
        
        			echo '<option value='.'"'.$value['province_id'].'"';
              
        				if(isset($_POST['distr_name4_province'])){
        					if($_POST['distr_name4_province'] == $value['province_id']){
        						echo 'selected';
        						}
                }elseif($mpd4_distr_province_id == $value['province_id']){
                    echo 'selected';
                }
        			echo '>'.$value['province_name'].'</option>';
            }
          }
          
          //determine whether province has been selected for preferred districts option 4 
              if(!empty($_POST['pref4_provinceID'])){ //from AJAX function
                 //if the above condition is met declare variable for province ID
                 $pref4_province_id = $_POST['pref4_provinceID'];
                 
                //query districts based on province selected above
                $sql = "SELECT * FROM districts
                        WHERE distr_province_id = $pref4_province_id";

                try {
                    $results_distr = $db->prepare($sql);
                    $results_distr->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried districts are put in an array
                $districts_pref4 = $results_distr->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select District</option>';
                //loop through the districts array putting every district in the select menu
                foreach ($districts_pref4 as $key=>$value){
                  echo '<option value='.'"'.$value['distr_id'].'"';
                  echo '>'.ucwords(strtolower($value['distr_name'])).'</option>';
                }
              }
  
              
              
              function pull_pref_distr2(){
                
                global $mpd2_distr_id;
                global $mpd2_distr_province_id;
                global $db;
                 
                //query districts based on province selected above
                $sql = "SELECT * FROM districts
                        WHERE distr_province_id = $mpd2_distr_province_id";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried districts are put in an array
                $districts2 = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the districts array putting every district in the select menu
                foreach ($districts2 as $key=>$value){
                  echo '<option value='.'"'.$value['distr_id'].'"';
                    if(intval($mpd2_distr_id) == $value['distr_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['distr_name'])).'</option>';
                }
              }
              
              function pull_pref_distr3(){
                
                global $mpd3_distr_id;
                global $mpd3_distr_province_id;
                global $db;
                 
                //query districts based on province selected above
                $sql = "SELECT * FROM districts
                        WHERE distr_province_id = $mpd3_distr_province_id";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried districts are put in an array
                $districts3 = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the districts array putting every district in the select menu
                foreach ($districts3 as $key=>$value){
                  echo '<option value='.'"'.$value['distr_id'].'"';
                    if(intval($mpd3_distr_id) == $value['distr_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['distr_name'])).'</option>';
                }
              }
              
              function pull_pref_distr4(){
                
                global $mpd4_distr_id;
                global $mpd4_distr_province_id;
                global $db;
                 
                //query districts based on province selected above
                $sql = "SELECT * FROM districts
                        WHERE distr_province_id = $mpd4_distr_province_id";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried districts are put in an array
                $districts4 = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the districts array putting every district in the select menu
                foreach ($districts4 as $key=>$value){
                  echo '<option value='.'"'.$value['distr_id'].'"';
                    if(intval($mpd4_distr_id) == $value['distr_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['distr_name'])).'</option>';
                }
              }


  //this function prepares a drop-down of all districts to use for selection of preferred school option 1 and checks whether an option has been selected during a session for data persistence
	function all_distr_pref_sch1(array $districts){

		/*
    Array
        (
            [0] => Array
                (
                    [distr_id] => 33
                    [distr_name] => Beitbridge
                )
        )
    */
    global $mps_school_distr_id;
    foreach ($districts as $key=>$value){

			echo '<option value='.'"'.$value['distr_id'].'"';
      
				if(isset($_POST['preferred_schools1_distr'])){
					if($_POST['preferred_schools1_distr'] == $value['distr_id']){
						echo 'selected';
						}
        }elseif($mps_school_distr_id == $value['distr_id']){
            echo 'selected';
        }
			echo '>'.$value['distr_name'].'</option>';
    }
  }
  
  //this function prepares a drop-down of all districts to use for selection of preferred school option 2 and checks whether an option has been selected during a session for data persistence
	function all_distr_pref_sch2(array $districts){

		/*
    Array
        (
            [0] => Array
                (
                    [distr_id] => 33
                    [distr_name] => Beitbridge
                )
        )
    */
    global $mps2_school_distr_id;
    foreach ($districts as $key=>$value){

			echo '<option value='.'"'.$value['distr_id'].'"';
      
				if(isset($_POST['preferred_schools2_distr'])){
					if($_POST['preferred_schools2_distr'] == $value['distr_id']){
						echo 'selected';
						}
        }elseif($mps2_school_distr_id == $value['distr_id']){
            echo 'selected';
        }
			echo '>'.$value['distr_name'].'</option>';
    }
  }
  
  //this function prepares a drop-down of all districts to use for selection of preferred school option 3 and checks whether an option has been selected during a session for data persistence
	function all_distr_pref_sch3(array $districts){

		/*
    Array
        (
            [0] => Array
                (
                    [distr_id] => 33
                    [distr_name] => Beitbridge
                )
        )
    */
    global $mps3_school_distr_id;
    foreach ($districts as $key=>$value){

			echo '<option value='.'"'.$value['distr_id'].'"';
      
				if(isset($_POST['preferred_schools3_distr'])){
					if($_POST['preferred_schools3_distr'] == $value['distr_id']){
						echo 'selected';
						}
        }elseif($mps3_school_distr_id == $value['distr_id']){
            echo 'selected';
        }
			echo '>'.$value['distr_name'].'</option>';
    }
  }
  
  //this function prepares a drop-down of all districts to use for selection of preferred school option 4 and checks whether an option has been selected during a session for data persistence
	function all_distr_pref_sch4(array $districts){

		/*
    Array
        (
            [0] => Array
                (
                    [distr_id] => 33
                    [distr_name] => Beitbridge
                )
        )
    */
    global $mps4_school_distr_id;
    foreach ($districts as $key=>$value){

			echo '<option value='.'"'.$value['distr_id'].'"';
      
				if(isset($_POST['preferred_schools4_distr'])){
					if($_POST['preferred_schools4_distr'] == $value['distr_id']){
						echo 'selected';
						}
        }elseif($mps4_school_distr_id == $value['distr_id']){
            echo 'selected';
        }
			echo '>'.$value['distr_name'].'</option>';
    }
  }
  
  //this function prepares a drop-down of all districts to use for selection of preferred school option 5 and checks whether an option has been selected during a session for data persistence
	function all_distr_pref_sch5(array $districts){

		/*
    Array
        (
            [0] => Array
                (
                    [distr_id] => 33
                    [distr_name] => Beitbridge
                )
        )
    */
    global $mps5_school_distr_id;
    foreach ($districts as $key=>$value){

			echo '<option value='.'"'.$value['distr_id'].'"';
      
				if(isset($_POST['preferred_schools5_distr'])){
					if($_POST['preferred_schools5_distr'] == $value['distr_id']){
						echo 'selected';
						}
        }elseif($mps5_school_distr_id == $value['distr_id']){
            echo 'selected';
        }
			echo '>'.$value['distr_name'].'</option>';
    }
  }
  
  //this function prepares a drop-down of all districts to use for selection of preferred school option 6 and checks whether an option has been selected during a session for data persistence
	function all_distr_pref_sch6(array $districts){

		/*
    Array
        (
            [0] => Array
                (
                    [distr_id] => 33
                    [distr_name] => Beitbridge
                )
        )
    */
    global $mps6_school_distr_id;
    foreach ($districts as $key=>$value){

			echo '<option value='.'"'.$value['distr_id'].'"';
      
				if(isset($_POST['preferred_schools6_distr'])){
					if($_POST['preferred_schools6_distr'] == $value['distr_id']){
						echo 'selected';
						}
        }elseif($mps6_school_distr_id == $value['distr_id']){
            echo 'selected';
        }
			echo '>'.$value['distr_name'].'</option>';
    }
  }
  
  //this function prepares a drop-down of all districts to use for selection of preferred school option 7 and checks whether an option has been selected during a session for data persistence
	function all_distr_pref_sch7(array $districts){

		/*
    Array
        (
            [0] => Array
                (
                    [distr_id] => 33
                    [distr_name] => Beitbridge
                )
        )
    */
    global $mps7_school_distr_id;
    foreach ($districts as $key=>$value){

			echo '<option value='.'"'.$value['distr_id'].'"';
      
				if(isset($_POST['preferred_schools7_distr'])){
					if($_POST['preferred_schools7_distr'] == $value['distr_id']){
						echo 'selected';
						}
        }elseif($mps7_school_distr_id == $value['distr_id']){
            echo 'selected';
        }
			echo '>'.$value['distr_name'].'</option>';
    }
  }
  
  //this function prepares a drop-down of all districts to use for selection of preferred school option 8 and checks whether an option has been selected during a session for data persistence
	function all_distr_pref_sch8(array $districts){

		/*
    Array
        (
            [0] => Array
                (
                    [distr_id] => 33
                    [distr_name] => Beitbridge
                )
        )
    */
    global $mps8_school_distr_id;
    foreach ($districts as $key=>$value){

			echo '<option value='.'"'.$value['distr_id'].'"';
      
				if(isset($_POST['preferred_schools8_distr'])){
					if($_POST['preferred_schools8_distr'] == $value['distr_id']){
						echo 'selected';
						}
        }elseif($mps8_school_distr_id == $value['distr_id']){
            echo 'selected';
        }
			echo '>'.$value['distr_name'].'</option>';
    }
  }
  
  //this function prepares a drop-down of all districts to use for selection of preferred school option 9 and checks whether an option has been selected during a session for data persistence
	function all_distr_pref_sch9(array $districts){

		/*
    Array
        (
            [0] => Array
                (
                    [distr_id] => 33
                    [distr_name] => Beitbridge
                )
        )
    */
    global $mps9_school_distr_id;
    foreach ($districts as $key=>$value){

			echo '<option value='.'"'.$value['distr_id'].'"';
      
				if(isset($_POST['preferred_schools9_distr'])){
					if($_POST['preferred_schools9_distr'] == $value['distr_id']){
						echo 'selected';
						}
        }elseif($mps9_school_distr_id == $value['distr_id']){
            echo 'selected';
        }
			echo '>'.$value['distr_name'].'</option>';
    }
  }
  
  //this function prepares a drop-down of all districts to use for selection of preferred school option 10 and checks whether an option has been selected during a session for data persistence
	function all_distr_pref_sch10(array $districts){

		/*
    Array
        (
            [0] => Array
                (
                    [distr_id] => 33
                    [distr_name] => Beitbridge
                )
        )
    */
    global $mps10_school_distr_id;
    foreach ($districts as $key=>$value){

			echo '<option value='.'"'.$value['distr_id'].'"';
      
				if(isset($_POST['preferred_schools10_distr'])){
					if($_POST['preferred_schools10_distr'] == $value['distr_id']){
						echo 'selected';
						}
        }elseif($mps10_school_distr_id == $value['distr_id']){
            echo 'selected';
        }
			echo '>'.$value['distr_name'].'</option>';
    }
  }
            //checks if a province has been selected
            if(!empty($_POST['distr_province_id'])){ //from AJAX function
              //if a province has been posted the value is put in a variable
              $distr_province_id = $_POST['distr_province_id'];
            
              //a query is made for the districts with the province selected
              $sql = "SELECT * FROM districts
                      WHERE distr_province_id = $distr_province_id";

              try {
                  $results = $db->prepare($sql);
                  $results->execute();
              } catch (Exception $e) {
                  echo "Error!: " . $e->getMessage() . "<br />";
                  return false;
              }
              
              //selected districts are put in an array
              $districts_curr = $results->fetchAll(PDO::FETCH_ASSOC);
              
              //the first option contains no value and is only an instruction
              echo '<option value="">Please Select District</option>';
              //loop through the array of districts and include them in the select menu
              foreach ($districts_curr as $key=>$value){
                echo '<option value='.'"'.$value['distr_id'].'"';
                
                global $mcs_client_details;
                if(isset($_POST['current_district'])){
                  if($_POST['current_district'] == $value['distr_id']){
                    echo 'selected';
                    }
                  }
                echo '>'.$value['distr_name'].'</option>';
              }
            }
            
            //the below function pulls the current district for possible update
              function pull_curr_distr(){
                
                global $curr_distr_id;
                global $curr_province_id;
                global $db;
                 
                //query districts based on province
                $sql = "SELECT * FROM districts
                        WHERE distr_province_id = $curr_province_id";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried districts are put in an array
                $districts_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the districts array putting every district in the select menu
                foreach ($districts_curr as $key=>$value){
                  echo '<option value='.'"'.$value['distr_id'].'"';
                    if(intval($curr_distr_id) == $value['distr_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['distr_name'])).'</option>';
                }
              }
              
            
            //checks if a province has been selected
            if(!empty($_POST['town_province_id'])){ //from AJAX function
              //if a province has been posted the value is put in a variable
              $town_province_id = $_POST['town_province_id'];
              
              //query towns based on province selected above
                $sql = "SELECT * FROM towns
                        WHERE town_province_id = $town_province_id";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried towns are put in an array
                $towns_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select Town</option>';
                //loop through the towns array putting every town in the select menu
                foreach ($towns_curr as $key=>$value){
                  echo '<option value='.'"'.$value['town_id'].'"';
                  echo '>'.ucwords(strtolower($value['town_name'])).'</option>';
                }
            }
            
            
            //checks if a town has been selected and if so locations will be filtered by town
            if(!empty($_POST['loc_town_id'])){ //from AJAX function
              //if a town has been posted the value is put in a variable
              $loc_town_id = $_POST['loc_town_id'];
                
                //query locations based on town selected above
                $sql = "SELECT * FROM locations
                        WHERE loc_town_id = $loc_town_id
                        ORDER BY loc_name";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried locations are put in an array
                $locations_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select Location</option>';
                //loop through the locations array putting every location in the select menu
                foreach ($locations_curr as $key=>$value){
                  echo '<option value='.'"'.$value['loc_id'].'"';
                  echo '>'.ucwords(strtolower($value['loc_name'])).'</option>';
                }
            }
            //checks if a province has been selected and if town is not selected locations will be filtered by province
            elseif(!empty($_POST['loc_province_id'])){ //from AJAX function
              //if a province has been posted the value is put in a variable
              $loc_province_id = $_POST['loc_province_id'];
                
                //query locations based on province selected above
                $sql = "SELECT * FROM locations
                        WHERE loc_province_id = $loc_province_id
                        ORDER BY loc_name";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried locations are put in an array
                $locations_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select Location</option>';
                //loop through the locations array putting every location in the select menu
                foreach ($locations_curr as $key=>$value){
                  echo '<option value='.'"'.$value['loc_id'].'"';
                  echo '>'.ucwords(strtolower($value['loc_name'])).'</option>';
                }
            }
            
             
              
              //for current schools
              //determine whether both district and level taught has been selected  
              if(!empty($_POST['distr_id']) && !empty($_POST['level_taught'])){ //from AJAX function
                 //if the above condition is met declare variables for both district ID and level
                 $school_distr_id = $_POST['distr_id'];
                 $level = $_POST['level_taught'];
              
              if(isset($school_distr_id)){
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $school_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select School</option>';
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
            }
            
            function pull_curr_town(){
                
                global $curr_town_id;
                global $client_level_taught;
                global $curr_province_id;
                global $db;
                 
                //query towns based on province and level selected above
                $sql = "SELECT * FROM towns
                        WHERE town_province_id = $curr_province_id";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried towns are put in an array
                $towns_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the towns array putting every town in the select menu
                foreach ($towns_curr as $key=>$value){
                  echo '<option value='.'"'.$value['town_id'].'"';
                    if(intval($curr_town_id) == $value['town_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['town_name'])).'</option>';
                }
              }
              
              //the below function pulls the current locations for possible update
            function pull_curr_loc(){
                
                global $curr_loc_id;
                global $curr_town_id;
                global $curr_province_id;
                global $db;
               
                //query locations based on province
                if(!empty($curr_town_id)){
                   $sql = "SELECT * FROM locations
                        WHERE loc_town_id = $curr_town_id
                        AND loc_status = 'A'
                        ORDER BY loc_name"; 
                }else{
                    $sql = "SELECT * FROM locations
                        WHERE loc_province_id = $curr_province_id
                        AND loc_status = 'A'
                        ORDER BY loc_name";
                }

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried locations are put in an array
                $locations_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the locations array putting every location in the select menu
                foreach ($locations_curr as $key=>$value){
                  echo '<option value='.'"'.$value['loc_id'].'"';
                    if(intval($curr_loc_id) == $value['loc_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['loc_name'])).'</option>';
                }
              }
              
            
            //pull current school filtered by level and district
            function pull_curr_sch(){
                
                global $curr_school_id;
                global $client_level_taught;
                global $curr_distr_id;
                global $db;
                
                if(!empty($curr_school_id)){
                 //if the above variable is not empty declare variable for level
                 if($client_level_taught == 'PRIMARY - ECD' || 
                    $client_level_taught == 'PRIMARY - GENERAL'){
                    $level = 'Primary';
                    }else{
                      $level = 'Secondary';
                    }
                 
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $curr_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                    if(intval($curr_school_id) == $value['school_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              }
              
              //pull referrer agent filtered by client current district
              if(!empty($_POST['agent_territory'])){ //from AJAX function
                 //if the above condition is met declare variable for territory ID
                 $curr_distr_id = $_POST['agent_territory'];
                 
                //query referrer agent filtered by client current district
                $sql = "SELECT * FROM agents
                        WHERE agent_territory = $curr_distr_id";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried agents are put in an array
                $agents = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value=""></option>';
                //loop through the agents array putting every agent in the select menu
                foreach ($agents as $key=>$value){
                  echo '<option value='.'"'.$value['agent_ac_no'].'"';
                    if(!empty($agent_ac_no) && $agent_ac_no == $value['agent_ac_no']){
                        echo 'selected';
                      }
                  echo '>'.$value['agent_ac_no'].' - '.ucwords(strtolower($value['agent_first_name'])).' '.ucwords(strtolower($value['agent_last_name'])).'</option>';
                }
              }
              
              //pull agents list based on current district
              function pull_agent(){
                global $curr_distr_id;
                global $client_agent_ac_no;
                global $db;
                
                if(!empty($curr_distr_id)){
                //query referrer agent filtered by client current district
                $sql = "SELECT * FROM agents
                        WHERE agent_territory = $curr_distr_id";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried agents are put in an array
                $agents = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the agents array putting every agent in the select menu
                foreach ($agents as $key=>$value){
                  echo '<option value='.'"'.$value['agent_ac_no'].'"';
                    if($client_agent_ac_no == $value['agent_ac_no']){
                        echo 'selected';
                      }
                  echo '>'.$value['agent_ac_no'].' - '.ucwords(strtolower($value['agent_first_name'])).' '.ucwords(strtolower($value['agent_last_name'])).'</option>';
                }
              } 
            }
             
  /*  
  //this function prepares a drop-down of all districts and checks whether an option has been selected during a session for data persistence
	function all_districts_curr(array $districts){
		foreach ($districts as $key=>$value){
			echo '<option value='.'"'.$value['distr_id'].'"';
				global $mcs_client_details;
        if(isset($_POST['current_district'])){
					if($_POST['current_district'] == $value['distr_name']){
						echo 'selected';
						}
					}elseif(!empty($_GET['id']) && ($mcs_client_details[1] == $value['distr_name'])){
            echo 'selected';
          }
			echo '>'.$value['distr_name'].'</option>';
		}
	echo '</select>';
	}

  //this function prepares a drop-down of all districts and checks whether an option has been selected during a session for data persistence
	function all_districts_curr($districts){
      include 'connection.php';
    
    $sql = 'SELECT * FROM districts ORDER BY distr_name';
            

    try {
        $results = $db->prepare($sql);
        //$results->bindValue(1, $distr_province_id, PDO::PARAM_INT);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    
    $districts = $results->fetchAll(PDO::FETCH_ASSOC);
		/*
    Array
        (
            [0] => Array
                (
                    [distr_id] => 33
                    [distr_name] => Beitbridge
                    [distr_province_id] => 7
                )
        )
    *//*
    foreach ($districts as $key=>$value){

			echo '<option value='.'"'.$value['distr_id'].'"';
      //echo '<option value='.'"'.$value['distr_province_id'].'"'.' id='.'"'.intval($value['distr_id']).'"';

				global $mcs_client_details;
        if(isset($_POST['current_district'])){
					if($_POST['current_district'] == $value['distr_name']){
						echo 'selected';
						}
					}elseif(!empty($_GET['id']) && ($mcs_client_details[1] == $value['distr_name'])){
            echo 'selected';
          }

			echo '>'.$value['distr_name'].'</option>';
		}

	echo '</select>';

	}
  */

	 //this function prepares a drop-down of all districts to use for selection of preferred location option 1 and checks whether an option has been selected during a session for data persistence
	function all_distr_pref_loc1(array $districts){

		/*
    Array
        (
            [0] => Array
                (
                    [distr_id] => 33
                    [distr_name] => Beitbridge
                )
        )
    */
    global $mpl_loc_distr_id;
    foreach ($districts as $key=>$value){

			echo '<option value='.'"'.$value['distr_id'].'"';
      
				if(isset($_POST['loc_name1_distr'])){
					if($_POST['loc_name1_distr'] == $value['distr_id']){
						echo 'selected';
						}
        }elseif($mpl_loc_distr_id == $value['distr_id']){
            echo 'selected';
        }
			echo '>'.$value['distr_name'].'</option>';
    }
  }
  
  
	 //this function prepares a drop-down of all districts to use for selection of preferred location option 2 and checks whether an option has been selected during a session for data persistence
        	function all_distr_pref_loc2(array $districts){
        
        		/*
            Array
                (
                    [0] => Array
                        (
                            [distr_id] => 33
                            [distr_name] => Beitbridge
                        )
                )
            */
            global $mpl2_loc_distr_id;
            foreach ($districts as $key=>$value){
        
        			echo '<option value='.'"'.$value['distr_id'].'"';
              
        				if(isset($_POST['loc_name2_distr'])){
        					if($_POST['loc_name2_distr'] == $value['distr_id']){
        						echo 'selected';
        						}
                }elseif($mpl2_loc_distr_id == $value['distr_id']){
                    echo 'selected';
                }
        			echo '>'.$value['distr_name'].'</option>';
            }
          }
      
      	 //this function prepares a drop-down of all districts to use for selection of preferred location option 3 and checks whether an option has been selected during a session for data persistence
        	function all_distr_pref_loc3(array $districts){
        
        		/*
            Array
                (
                    [0] => Array
                        (
                            [distr_id] => 33
                            [distr_name] => Beitbridge
                        )
                )
            */
            global $mpl3_loc_distr_id;
            foreach ($districts as $key=>$value){
        
        			echo '<option value='.'"'.$value['distr_id'].'"';
              
        				if(isset($_POST['loc_name3_distr'])){
        					if($_POST['loc_name3_distr'] == $value['distr_id']){
        						echo 'selected';
        						}
                }elseif($mpl3_loc_distr_id == $value['distr_id']){
                    echo 'selected';
                }
        			echo '>'.$value['distr_name'].'</option>';
            }
          }
          
          	 //this function prepares a drop-down of all districts to use for selection of preferred location option 4 and checks whether an option has been selected during a session for data persistence
            	function all_distr_pref_loc4(array $districts){
            
            		/*
                Array
                    (
                        [0] => Array
                            (
                                [distr_id] => 33
                                [distr_name] => Beitbridge
                            )
                    )
                */
                global $mpl4_loc_distr_id;
                foreach ($districts as $key=>$value){
            
            			echo '<option value='.'"'.$value['distr_id'].'"';
                  
            				if(isset($_POST['loc_name4_distr'])){
            					if($_POST['loc_name4_distr'] == $value['distr_id']){
            						echo 'selected';
            						}
                    }elseif($mpl4_loc_distr_id == $value['distr_id']){
                        echo 'selected';
                    }
            			echo '>'.$value['distr_name'].'</option>';
                }
              }
              
              	 //this function prepares a drop-down of all districts to use for selection of preferred location option 5 and checks whether an option has been selected during a session for data persistence
            	function all_distr_pref_loc5(array $districts){
            
            		/*
                Array
                    (
                        [0] => Array
                            (
                                [distr_id] => 33
                                [distr_name] => Beitbridge
                            )
                    )
                */
                global $mpl5_loc_distr_id;
                foreach ($districts as $key=>$value){
            
            			echo '<option value='.'"'.$value['distr_id'].'"';
                  
            				if(isset($_POST['loc_name5_distr'])){
            					if($_POST['loc_name5_distr'] == $value['distr_id']){
            						echo 'selected';
            						}
                    }elseif($mpl5_loc_distr_id == $value['distr_id']){
                        echo 'selected';
                    }
            			echo '>'.$value['distr_name'].'</option>';
                }
              }
              
  
              //determine whether district has been selected for preferred locations option 1 
              if(!empty($_POST['pref1_distrID'])){ //from AJAX function
                 //if the above condition is met declare variable for district ID
                 $pref1_distr_id = $_POST['pref1_distrID'];
                 
                //query locations based on district selected above
                $sql = "SELECT * FROM locations
                        WHERE loc_distr_id = $pref1_distr_id
                        AND loc_status = 'A'
                        ORDER BY loc_name";

                try {
                    $results_locs = $db->prepare($sql);
                    $results_locs->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried locations are put in an array
                $locations_pref1 = $results_locs->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select Location</option>';
                //loop through the locations array putting every location in the select menu
                foreach ($locations_pref1 as $key=>$value){
                  echo '<option value='.'"'.$value['loc_id'].'"';
                  echo '>'.ucwords(strtolower($value['loc_name'])).'</option>';
                }
              }
              
              //determine whether district has been selected for preferred locations option 2 
              if(!empty($_POST['pref2_distrID'])){ //from AJAX function
                 //if the above condition is met declare variable for district ID
                 $pref2_distr_id = $_POST['pref2_distrID'];
                 
                //query locations based on district selected above
                $sql = "SELECT * FROM locations
                        WHERE loc_distr_id = $pref2_distr_id
                        AND loc_status = 'A'
                        ORDER BY loc_name";

                try {
                    $results_locs2 = $db->prepare($sql);
                    $results_locs2->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried locations are put in an array
                $locations_pref2 = $results_locs2->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select Location</option>';
                //loop through the locations array putting every location in the select menu
                foreach ($locations_pref2 as $key=>$value){
                  echo '<option value='.'"'.$value['loc_id'].'"';
                  echo '>'.ucwords(strtolower($value['loc_name'])).'</option>';
                }
              }
              
              //determine whether district has been selected for preferred locations option 3 
              if(!empty($_POST['pref3_distrID'])){ //from AJAX function
                 //if the above condition is met declare variable for district ID
                 $pref3_distr_id = $_POST['pref3_distrID'];
                 
                //query locations based on district selected above
                $sql = "SELECT * FROM locations
                        WHERE loc_distr_id = $pref3_distr_id
                        AND loc_status = 'A'
                        ORDER BY loc_name";

                try {
                    $results_locs3 = $db->prepare($sql);
                    $results_locs3->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried locations are put in an array
                $locations_pref3 = $results_locs3->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select Location</option>';
                //loop through the locations array putting every location in the select menu
                foreach ($locations_pref3 as $key=>$value){
                  echo '<option value='.'"'.$value['loc_id'].'"';
                  echo '>'.ucwords(strtolower($value['loc_name'])).'</option>';
                }
              }
              
              //determine whether district has been selected for preferred locations option 4 
              if(!empty($_POST['pref4_distrID'])){ //from AJAX function
                 //if the above condition is met declare variable for district ID
                 $pref4_distr_id = $_POST['pref4_distrID'];
                 
                //query locations based on district selected above
                $sql = "SELECT * FROM locations
                        WHERE loc_distr_id = $pref4_distr_id
                        AND loc_status = 'A'
                        ORDER BY loc_name";

                try {
                    $results_locs4 = $db->prepare($sql);
                    $results_locs4->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried locations are put in an array
                $locations_pref4 = $results_locs4->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select Location</option>';
                //loop through the locations array putting every location in the select menu
                foreach ($locations_pref4 as $key=>$value){
                  echo '<option value='.'"'.$value['loc_id'].'"';
                  echo '>'.ucwords(strtolower($value['loc_name'])).'</option>';
                }
              }
              
              //determine whether district has been selected for preferred locations option 5 
              if(!empty($_POST['pref5_distrID'])){ //from AJAX function
                 //if the above condition is met declare variable for district ID
                 $pref5_distr_id = $_POST['pref5_distrID'];
                 
                //query locations based on district selected above
                $sql = "SELECT * FROM locations
                        WHERE loc_distr_id = $pref5_distr_id
                        AND loc_status = 'A'
                        ORDER BY loc_name";

                try {
                    $results_locs5 = $db->prepare($sql);
                    $results_locs5->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried locations are put in an array
                $locations_pref5 = $results_locs5->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select Location</option>';
                //loop through the locations array putting every location in the select menu
                foreach ($locations_pref5 as $key=>$value){
                  echo '<option value='.'"'.$value['loc_id'].'"';
                  echo '>'.ucwords(strtolower($value['loc_name'])).'</option>';
                }
              }
              
              function pull_pref_loc1(){
                
                global $mpl_loc_id;
                global $mpl_loc_distr_id;
                global $pref1_distr_id;
                //global $mpl_loc_distr_id;
                global $db;
                
                //query locations based on district selected above
                $sql = "SELECT * FROM locations
                        WHERE loc_distr_id = $mpl_loc_distr_id
                        AND loc_status = 'A'
                        ORDER BY loc_name";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried locations are put in an array
                $locations1 = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the locations array putting every location in the select menu
                foreach ($locations1 as $key=>$value){
                  echo '<option value='.'"'.$value['loc_id'].'"';
                    if(intval($mpl_loc_id) == $value['loc_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['loc_name'])).'</option>';
                }
              }
      
      function pull_pref_loc2(){
        
        global $mpl2_loc_id;
        global $mpl2_loc_distr_id;
        global $pref2_distr_id;
        //global $mpl2_loc_distr_id;
        global $db;
         
        //query locations based on district selected above
        $sql = "SELECT * FROM locations
                WHERE loc_distr_id = $mpl2_loc_distr_id
                AND loc_status = 'A'
                ORDER BY loc_name";

        try {
            $results = $db->prepare($sql);
            $results->execute();
        } catch (Exception $e) {
            echo "Error!: " . $e->getMessage() . "<br />";
            return false;
        }
        
        //the queried locations are put in an array
        $locations2 = $results->fetchAll(PDO::FETCH_ASSOC);
        
        //loop through the locations array putting every location in the select menu
        foreach ($locations2 as $key=>$value){
          echo '<option value='.'"'.$value['loc_id'].'"';
            if(intval($mpl2_loc_id) == $value['loc_id']){
                echo 'selected';
              }
          echo '>'.ucwords(strtolower($value['loc_name'])).'</option>';
        }
      }
      
      function pull_pref_loc3(){
        
        global $mpl3_loc_id;
        global $mpl3_loc_distr_id;
        global $pref3_distr_id;
        //global $mpl3_loc_distr_id;
        global $db;
         
        //query locations based on district and level selected above
        $sql = "SELECT * FROM locations
                WHERE loc_distr_id = $mpl3_loc_distr_id
                AND loc_status = 'A'
                ORDER BY loc_name";

        try {
            $results = $db->prepare($sql);
            $results->execute();
        } catch (Exception $e) {
            echo "Error!: " . $e->getMessage() . "<br />";
            return false;
        }
        
        //the queried locations are put in an array
        $locations3 = $results->fetchAll(PDO::FETCH_ASSOC);
        
        //loop through the locations array putting every location in the select menu
        foreach ($locations3 as $key=>$value){
          echo '<option value='.'"'.$value['loc_id'].'"';
            if(intval($mpl3_loc_id) == $value['loc_id']){
                echo 'selected';
              }
          echo '>'.ucwords(strtolower($value['loc_name'])).'</option>';
        }
      }
      
      function pull_pref_loc4(){
        
        global $mpl4_loc_id;
        global $mpl4_loc_distr_id;
        global $pref4_distr_id;
        //global $mpl4_loc_distr_id;
        global $db;
         
        //query locations based on district above
        $sql = "SELECT * FROM locations
                WHERE loc_distr_id = $mpl4_loc_distr_id
                AND loc_status = 'A'
                ORDER BY loc_name";

        try {
            $results = $db->prepare($sql);
            $results->execute();
        } catch (Exception $e) {
            echo "Error!: " . $e->getMessage() . "<br />";
            return false;
        }
        
        //the queried locations are put in an array
        $locations4 = $results->fetchAll(PDO::FETCH_ASSOC);
        
        //loop through the locations array putting every location in the select menu
        foreach ($locations4 as $key=>$value){
          echo '<option value='.'"'.$value['loc_id'].'"';
            if(intval($mpl4_loc_id) == $value['loc_id']){
                echo 'selected';
              }
          echo '>'.ucwords(strtolower($value['loc_name'])).'</option>';
        }
      }
      
      function pull_pref_loc5(){
        
        global $mpl5_loc_id;
        global $mpl5_loc_distr_id;
        global $pref5_distr_id;
        //global $mpl5_loc_distr_id;
        global $db;
         
        //query locations based on district and level selected above
        $sql = "SELECT * FROM locations
                WHERE loc_distr_id = $mpl5_loc_distr_id
                AND loc_status = 'A'
                ORDER BY loc_name";

        try {
            $results = $db->prepare($sql);
            $results->execute();
        } catch (Exception $e) {
            echo "Error!: " . $e->getMessage() . "<br />";
            return false;
        }
        
        //the queried locations are put in an array
        $locations5 = $results->fetchAll(PDO::FETCH_ASSOC);
        
        //loop through the locations array putting every location in the select menu
        foreach ($locations5 as $key=>$value){
          echo '<option value='.'"'.$value['loc_id'].'"';
            if(intval($mpl5_loc_id) == $value['loc_id']){
                echo 'selected';
              }
          echo '>'.ucwords(strtolower($value['loc_name'])).'</option>';
        }
      }

/*
  //this function prepares a drop-down of all schools and checks whether an option has been selected during a session for data persistence
  function all_schools_curr(array $schools){

		
    Array
        (
            [0] => Array
                (
                    [school_id] => 1
                    [school_name] => SAKUBVA 1
                    [school_level] => Secondary
                    [school_distr_id] => 59
                    [school_province_id] => 10
                    [school_town_id] => 0
                    [school_loc_id] => 405
                )
        )
    
    foreach ($schools as $key=>$value){
			//echo '<option value='.'"'.intval($value['school_distr_id']).'"'.' id='.'"'.$value['school_level'].'"'.' class='.'"'.$value['school_id'].'"';
      echo '<option value='.'"'.$value['school_distr_id'].'"';
      //echo '<option value='.'"'.strtoupper($value['school_name']).'"'.' class='.'"'.intval($value['school_distr_id']).'"';

				global $mcs_client_details;
        if(isset($_POST['current_school'])){
					if($_POST['current_school'] == $value['school_name']){
						echo 'selected';
					}
				}elseif(!empty($_GET['id']) && ($mcs_client_details[2] == $value['school_name'])){
            echo 'selected';
          }

			echo '>'.strtoupper($value['school_name']).' '.strtoupper($value['school_level']).'</option>';
		}

	echo '</select>';

   
  }
  
*//*

              //determine whether both district and level taught has been selected  
              if(!empty($_POST['pref1_distr_id']) && !empty($_POST['level_taught'])){ //from AJAX function
                 //if the above condition is met declare variables for both district ID and level
                 $sch_distr_id = $_POST['pref1_distr_id'];
                 $level_pref1 = $_POST['level_taught'];
      
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $sch_distr_id
                        AND school_level = '$level_pref1'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_pref1 = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select School</option>';
                //loop through the schools array putting every school in the select menu
                foreach ($schools_pref1 as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                  echo '>'.ucwords($value['school_name']).'</option>';
                }
              }
*/
            
  //this function prepares a drop-down of all schools and checks whether an option has been selected during a session for data persistence
	function all_schools(array $schools){

		/*
    Array
        (
            [0] => Array
                (
                    [school_id] => 1
                    [school_name] => SAKUBVA 1
                    [school_level] => Secondary
                    [school_distr_id] => 59
                    [school_province_id] => 10
                    [school_town_id] => 0
                    [school_loc_id] => 405
                )
        )
    */
    
    foreach ($schools as $key=>$value){
			echo '<option value='.'"'.strtoupper($value['school_name']).' class='.'"'.$value['school_distr_id'].' '.$value['school_level'].'"';
      //echo '<option value='.'"'.strtoupper($value['school_name']).'"';

       global $mps_school_id;
				if(isset($_POST['preferred_schools1'])){
					if($_POST['preferred_schools1'] == $value['school_name']){
						echo 'selected';
					}
				}elseif($mps_school_id == $value['school_id']){
            echo 'selected';
          }


			echo '>'.strtoupper($value['school_name']).' '.strtoupper($value['school_level']).'</option>';
		}

	echo '</select>';

	}

                
              //determine whether both district and level taught has been selected for preferred schools option1 
              if(!empty($_POST['pref1_distr_id']) && !empty($_POST['pref1_level_taught'])){ //from AJAX function
                 //if the above condition is met declare variables for both district ID and level
                 $pref1_distr_id = $_POST['pref1_distr_id'];
                 $level = $_POST['pref1_level_taught'];
                 
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $pref1_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select School</option>';
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              
              function pull_pref_sch1(){
                
                global $mps_school_id;
                global $client_level_taught;
                global $mps_school_distr_id;
                global $db;
                
                if(!empty($mps_school_id)){
                 //if the above variable is not empty declare variable for level
                 if($client_level_taught == 'PRIMARY - ECD' || 
                    $client_level_taught == 'PRIMARY - GENERAL' || 
                    $client_level_taught == 'PRIMARY - Special Needs'){
                    $level = 'Primary';
                    }else{
                      $level = 'Secondary';
                    }
                 
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $mps_school_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                    if(intval($mps_school_id) == $value['school_id']){
                        echo 'selected';
                      }elseif(isset($_POST['preferred_schools1'])){
            					if($_POST['preferred_schools1'] == $value['school_id']){
            						echo 'selected';
            						}
                     }
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              }
              //determine whether both district and level taught has been selected for preferred schools option 2  
              if(!empty($_POST['pref2_distr_id']) && !empty($_POST['pref2_level_taught'])){ //from AJAX function
                 //if the above condition is met declare variables for both district ID and level
                 $pref2_distr_id = $_POST['pref2_distr_id'];
                 $level = $_POST['pref2_level_taught'];
      
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $pref2_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select School</option>';
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              
              function pull_pref_sch2(){
                
                global $mps2_school_id;
                global $client_level_taught;
                global $mps2_school_distr_id;
                global $db;
                
                if(!empty($mps2_school_id)){
                 //if the above variable is not empty declare variable for level
                 if($client_level_taught == 'PRIMARY - ECD' || 
                    $client_level_taught == 'PRIMARY - GENERAL' || 
                    $client_level_taught == 'PRIMARY - Special Needs'){
                    $level = 'Primary';
                    }else{
                      $level = 'Secondary';
                    }
                 
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $mps2_school_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                    if(intval($mps2_school_id) == $value['school_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              }
              
              //determine whether both district and level taught has been selected for preferred schools option 3  
              if(!empty($_POST['pref3_distr_id']) && !empty($_POST['pref3_level_taught'])){ //from AJAX function
                 //if the above condition is met declare variables for both district ID and level
                 $pref3_distr_id = $_POST['pref3_distr_id'];
                 $level = $_POST['pref3_level_taught'];
      
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $pref3_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select School</option>';
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              
              function pull_pref_sch3(){
                
                global $mps3_school_id;
                global $client_level_taught;
                global $mps3_school_distr_id;
                global $db;
                
                if(!empty($mps3_school_id)){
                 //if the above variable is not empty declare variable for level
                 if($client_level_taught == 'PRIMARY - ECD' || 
                    $client_level_taught == 'PRIMARY - GENERAL' || 
                    $client_level_taught == 'PRIMARY - Special Needs'){
                    $level = 'Primary';
                    }else{
                      $level = 'Secondary';
                    }
                 
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $mps3_school_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                    if(intval($mps3_school_id) == $value['school_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              }
              //determine whether both district and level taught has been selected for preferred schools option 4  
              if(!empty($_POST['pref4_distr_id']) && !empty($_POST['pref4_level_taught'])){ //from AJAX function
                 //if the above condition is met declare variables for both district ID and level
                 $pref4_distr_id = $_POST['pref4_distr_id'];
                 $level = $_POST['pref4_level_taught'];
      
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $pref4_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select School</option>';
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              
              function pull_pref_sch4(){
                
                global $mps4_school_id;
                global $client_level_taught;
                global $mps4_school_distr_id;
                global $db;
                
                if(!empty($mps4_school_id)){
                 //if the above variable is not empty declare variable for level
                 if($client_level_taught == 'PRIMARY - ECD' || 
                    $client_level_taught == 'PRIMARY - GENERAL' || 
                    $client_level_taught == 'PRIMARY - Special Needs'){
                    $level = 'Primary';
                    }else{
                      $level = 'Secondary';
                    }
                 
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $mps4_school_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                    if(intval($mps4_school_id) == $value['school_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              }
              //determine whether both district and level taught has been selected for preferred schools option 5  
              if(!empty($_POST['pref5_distr_id']) && !empty($_POST['pref5_level_taught'])){ //from AJAX function
                 //if the above condition is met declare variables for both district ID and level
                 $pref5_distr_id = $_POST['pref5_distr_id'];
                 $level = $_POST['pref5_level_taught'];
      
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $pref5_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select School</option>';
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              
              function pull_pref_sch5(){
                
                global $mps5_school_id;
                global $client_level_taught;
                global $mps5_school_distr_id;
                global $db;
                
                if(!empty($mps5_school_id)){
                 //if the above variable is not empty declare variable for level
                 if($client_level_taught == 'PRIMARY - ECD' || 
                    $client_level_taught == 'PRIMARY - GENERAL' || 
                    $client_level_taught == 'PRIMARY - Special Needs'){
                    $level = 'Primary';
                    }else{
                      $level = 'Secondary';
                    }
                 
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $mps5_school_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                    if(intval($mps5_school_id) == $value['school_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              }
              
              //determine whether both district and level taught has been selected for preferred schools option 6  
              if(!empty($_POST['pref6_distr_id']) && !empty($_POST['pref6_level_taught'])){ //from AJAX function
                 //if the above condition is met declare variables for both district ID and level
                 $pref6_distr_id = $_POST['pref6_distr_id'];
                 $level = $_POST['pref6_level_taught'];
      
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $pref6_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select School</option>';
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              
              function pull_pref_sch6(){
                
                global $mps6_school_id;
                global $client_level_taught;
                global $mps6_school_distr_id;
                global $db;
                
                if(!empty($mps6_school_id)){
                 //if the above variable is not empty declare variable for level
                 if($client_level_taught == 'PRIMARY - ECD' || 
                    $client_level_taught == 'PRIMARY - GENERAL' || 
                    $client_level_taught == 'PRIMARY - Special Needs'){
                    $level = 'Primary';
                    }else{
                      $level = 'Secondary';
                    }
                 
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $mps6_school_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                    if(intval($mps6_school_id) == $value['school_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              }
              //determine whether both district and level taught has been selected for preferred schools option 7  
              if(!empty($_POST['pref7_distr_id']) && !empty($_POST['pref7_level_taught'])){ //from AJAX function
                 //if the above condition is met declare variables for both district ID and level
                 $pref7_distr_id = $_POST['pref7_distr_id'];
                 $level = $_POST['pref7_level_taught'];
      
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $pref7_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select School</option>';
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              
              function pull_pref_sch7(){
                
                global $mps7_school_id;
                global $client_level_taught;
                global $mps7_school_distr_id;
                global $db;
                
                if(!empty($mps7_school_id)){
                 //if the above variable is not empty declare variable for level
                 if($client_level_taught == 'PRIMARY - ECD' || 
                    $client_level_taught == 'PRIMARY - GENERAL' || 
                    $client_level_taught == 'PRIMARY - Special Needs'){
                    $level = 'Primary';
                    }else{
                      $level = 'Secondary';
                    }
                 
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $mps7_school_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                    if(intval($mps7_school_id) == $value['school_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              }
              //determine whether both district and level taught has been selected for preferred schools option 8  
              if(!empty($_POST['pref8_distr_id']) && !empty($_POST['pref8_level_taught'])){ //from AJAX function
                 //if the above condition is met declare variables for both district ID and level
                 $pref8_distr_id = $_POST['pref8_distr_id'];
                 $level = $_POST['pref8_level_taught'];
      
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $pref8_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select School</option>';
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              
              function pull_pref_sch8(){
                
                global $mps8_school_id;
                global $client_level_taught;
                global $mps8_school_distr_id;
                global $db;
                
                if(!empty($mps8_school_id)){
                 //if the above variable is not empty declare variable for level
                 if($client_level_taught == 'PRIMARY - ECD' || 
                    $client_level_taught == 'PRIMARY - GENERAL' || 
                    $client_level_taught == 'PRIMARY - Special Needs'){
                    $level = 'Primary';
                    }else{
                      $level = 'Secondary';
                    }
                 
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $mps8_school_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                    if(intval($mps8_school_id) == $value['school_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              }
              //determine whether both district and level taught has been selected for preferred schools option 9  
              if(!empty($_POST['pref9_distr_id']) && !empty($_POST['pref9_level_taught'])){ //from AJAX function
                 //if the above condition is met declare variables for both district ID and level
                 $pref9_distr_id = $_POST['pref9_distr_id'];
                 $level = $_POST['pref9_level_taught'];
      
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $pref9_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select School</option>';
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              
              function pull_pref_sch9(){
                
                global $mps9_school_id;
                global $client_level_taught;
                global $mps9_school_distr_id;
                global $db;
                
                if(!empty($mps9_school_id)){
                 //if the above variable is not empty declare variable for level
                 if($client_level_taught == 'PRIMARY - ECD' || 
                    $client_level_taught == 'PRIMARY - GENERAL' || 
                    $client_level_taught == 'PRIMARY - Special Needs'){
                    $level = 'Primary';
                    }else{
                      $level = 'Secondary';
                    }
                 
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $mps9_school_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                    if(intval($mps9_school_id) == $value['school_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              }
              //determine whether both district and level taught has been selected for preferred schools option 10  
              if(!empty($_POST['pref10_distr_id']) && !empty($_POST['pref10_level_taught'])){ //from AJAX function
                 //if the above condition is met declare variables for both district ID and level
                 $pref10_distr_id = $_POST['pref10_distr_id'];
                 $level = $_POST['pref10_level_taught'];
      
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $pref10_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //the first option is an instruction
                echo '<option value="">Please Select School</option>';
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              
              function pull_pref_sch10(){
                
                global $mps10_school_id;
                global $client_level_taught;
                global $mps10_school_distr_id;
                global $db;
                
                if(!empty($mps10_school_id)){
                 //if the above variable is not empty declare variable for level
                 if($client_level_taught == 'PRIMARY - ECD' || 
                    $client_level_taught == 'PRIMARY - GENERAL' || 
                    $client_level_taught == 'PRIMARY - Special Needs'){
                    $level = 'Primary';
                    }else{
                      $level = 'Secondary';
                    }
                 
                //query schools based on district and level selected above
                $sql = "SELECT * FROM schools
                        WHERE school_distr_id = $mps10_school_distr_id
                        AND school_level = '$level'";

                try {
                    $results = $db->prepare($sql);
                    $results->execute();
                } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                }
                
                //the queried schools are put in an array
                $schools_curr = $results->fetchAll(PDO::FETCH_ASSOC);
                
                //loop through the schools array putting every school in the select menu
                foreach ($schools_curr as $key=>$value){
                  echo '<option value='.'"'.$value['school_id'].'"';
                    if(intval($mps10_school_id) == $value['school_id']){
                        echo 'selected';
                      }
                  echo '>'.ucwords(strtolower($value['school_name'])).'</option>';
                }
              }
              }

	//this function prepares all subjects and checks whether an option has been selected during a session for data persistence
	function all_subjects(array $subjects){

	echo "<table>";
		foreach ($subjects as $key=>$value){
			echo '<tr><td>'.'<input type=checkbox" id="subject" name="subject" value='.'"'.$value[sub_name].'"'.'><label for="subject" name="subject" class="light">'.$value['sub_name'].'</label></td></tr>';
		}

	echo "<table>";

	}
	
//this function creates a new location record in the 'locations' database if the location name is not already in the system
		function loc_create($locnewtown, $locnewdistr, $locnewname, $locnewstatus, $locnewprov){
					
					include ('connection.php');
					$sql = 'INSERT INTO locations									(loc_town_id,
							 loc_distr_id,
							 loc_name,
                             loc_status,
                             loc_province_id)
							VALUES (:loc_town_id, :loc_distr_id, :loc_name, :loc_status, :loc_province_id)';
									
					try {
					  $results = $db->prepare($sql);
                      $results->bindValue(':loc_town_id', $locnewtown, PDO::PARAM_INT);
                      $results->bindValue(':loc_distr_id', $locnewdistr, PDO::PARAM_INT);
                      $results->bindValue(':loc_name', $locnewname, PDO::PARAM_STR);
                      $results->bindValue(':loc_status', $locnewstatus, PDO::PARAM_STR);
                      $results->bindValue(':loc_province_id', $locnewprov, PDO::PARAM_INT);
					  $results->execute();
					} catch (Exception $e) {
						echo "Error!: " . $e->getMessage() . "<br />";
						return false;
					}
					return true;
			
		}
	
//this function updates a location in the 'locations' database if the location name is already in the system
function loc_update($locuptown, $locupdistr, $locupname, $locupstatus, $locupprov, $loc_id){
			
			include ('connection.php');
			$sql = 'UPDATE `locations` SET									         `loc_town_id` = :loc_town_id,
					 `loc_distr_id` = :loc_distr_id,
					 `loc_name` = :loc_name,
                     `loc_status` = :loc_status,
                     `loc_province_id` = :loc_province_id
                     WHERE `loc_id` = :loc_id';
							
			try {
			  $results = $db->prepare($sql);
              $results->bindValue(':loc_town_id', $locuptown, PDO::PARAM_INT);
              $results->bindValue(':loc_distr_id', $locupdistr, PDO::PARAM_INT);
              $results->bindValue(':loc_name', $locupname, PDO::PARAM_STR);
              $results->bindValue(':loc_status', $locupstatus, PDO::PARAM_STR);
              $results->bindValue(':loc_province_id', $locupprov, PDO::PARAM_INT);
              $results->bindValue(':loc_id', $loc_id, PDO::PARAM_INT);
			  $results->execute();
			} catch (Exception $e) {
				echo "Error!: " . $e->getMessage() . "<br />";
				return false;
			}
			return true;
	
}

//this function updates a client payment in the 'matched_clients' or 'other tables for registration fee'
function matched_client_update($pay_ref, $pay_amnt, $pay_date, $client_ec_no){
			
			include ('connection.php');
      
      global $purpose;
			
			if(!empty($purpose) && $purpose == 'match_fee'){
    			$sql = 'UPDATE `matched_clients` SET `matched_receipt_ref` = :matched_receipt_ref,
    					 `matched_amnt_paid` = :matched_amnt_paid,
    					 `matched_status` = "RP",
    					 `matched_paynt_date` = :matched_paynt_date
                WHERE `matched_ec_no` = :matched_ec_no';
                    
    			$sql2 = 'UPDATE `match_current_schools` SET `mcs_status` = "RP" WHERE `mcs_client_ec_no` = :matched_ec_no';
                    
    			$sql3 = 'UPDATE `clients` SET `client_status` = "RP" WHERE `client_ec_no` = :matched_ec_no';
			}elseif(!empty($purpose) && $purpose == 'reg_fee'){
    			
    			//$a_tables holds tables to be marked with "A" meaning 'Activated' 
             $a_tables = [['match_current_schools', 'mcs_status', 'mcs_client_ec_no'],
                            ['match_pref_schools', 'mps_status', 'mps_client_ec_no'],
                            ['match_pref_schools2', 'mps2_status', 'mps2_client_ec_no'],
                            ['match_pref_schools3', 'mps3_status', 'mps3_client_ec_no'],
                            ['match_pref_schools4', 'mps4_status', 'mps4_client_ec_no'],
                            ['match_pref_schools5', 'mps5_status', 'mps5_client_ec_no'],
                            ['match_pref_schools6', 'mps6_status', 'mps6_client_ec_no'],
                            ['match_pref_schools7', 'mps7_status', 'mps7_client_ec_no'],
                            ['match_pref_schools8', 'mps8_status', 'mps8_client_ec_no'],
                            ['match_pref_schools9', 'mps9_status', 'mps9_client_ec_no'],
                            ['match_pref_schools10', 'mps10_status', 'mps10_client_ec_no'],
                            ['match_pref_provinces', 'mpp_status', 'mpp_client_ec_no'],
                            ['match_pref_provinces2', 'mpp2_status', 'mpp2_client_ec_no'],
                            ['match_pref_towns', 'mpt_status', 'mpt_client_ec_no'],
                            ['match_pref_towns2', 'mpt2_status', 'mpt2_client_ec_no'],
                            ['match_pref_towns3', 'mpt3_status', 'mpt3_client_ec_no'],
                            ['match_pref_locations', 'mpl_status', 'mpl_client_ec_no'],
                            ['match_pref_locations2', 'mpl2_status', 'mpl2_client_ec_no'],
                            ['match_pref_locations3', 'mpl3_status', 'mpl3_client_ec_no'],
                            ['match_pref_locations4', 'mpl4_status', 'mpl4_client_ec_no'],
                            ['match_pref_locations5', 'mpl5_status', 'mpl5_client_ec_no'],
                            ['match_pref_districts', 'mpd_status', 'mpd_client_ec_no'],
                            ['match_pref_districts2', 'mpd2_status', 'mpd2_client_ec_no'],
                            ['match_pref_districts3', 'mpd3_status', 'mpd3_client_ec_no'],
                            ['match_pref_districts4', 'mpd4_status', 'mpd4_client_ec_no']];
                         
    			 //update clients table with registration payment details
        			$sql = 'UPDATE `clients` SET `client_receipt_ref` = :matched_receipt_ref, `client_reg_fee` = :matched_amnt_paid,
                  `client_reg_pay_date` = :matched_paynt_date, `client_status` = "A"
                   WHERE `client_ec_no` = :matched_ec_no';
                            
                //the below loops and updates table options with values to be activated for each client who paid reg fee
                  foreach($a_tables as $key=>$a_value_key){
                      
                    $sql2 = 'UPDATE '.$a_value_key[0].'
                                  SET '.$a_value_key[1].' = "A"
                                  WHERE '.$a_value_key[2].' = :matched_ec_no';

                  try {
                    $results2 = $db->prepare($sql2);
                    $results2->bindValue(':matched_ec_no', $client_ec_no, PDO::PARAM_STR);
                    $results2->execute();
                  } catch (Exception $e) {
                    echo "Error!: " . $e->getMessage() . "<br />";
                    return false;
                  }
			    }
			}
							
			try {
			  $results = $db->prepare($sql);
              $results->bindValue(':matched_receipt_ref', $pay_ref, PDO::PARAM_STR);
              $results->bindValue(':matched_amnt_paid', $pay_amnt, PDO::PARAM_INT);
              $results->bindValue(':matched_paynt_date', $pay_date, PDO::PARAM_STR);
              $results->bindValue(':matched_ec_no', $client_ec_no, PDO::PARAM_STR);
			  $results->execute();
			  
              $results2 = $db->prepare($sql2);
              $results2->bindValue(':matched_ec_no', $client_ec_no, PDO::PARAM_STR);
                $results2->execute();
                
			  if(!empty($purpose) && $purpose == 'match_fee'){
    			  $results3 = $db->prepare($sql3);
                  $results3->bindValue(':matched_ec_no', $client_ec_no, PDO::PARAM_STR);
			      $results3->execute();    
			  }
			} catch (Exception $e) {
				echo "Error!: " . $e->getMessage() . "<br />";
				return false;
			}
			return true;
	
}


if ($_SERVER['REQUEST_METHOD'] == "POST"){
		//header(Location:'payreg.php');
		//on confirmation of registration payment

    //agent creation function
    function create_agent($agent_ac_no,
                          $agent_first_name, 
                          $agent_last_name, 
                          $agent_reg_id,  
                          $agent_sex, 
                          $agent_mobile_no, 
                          $agent_email, 
                          $agent_password, 
                          $agent_status,
                          $agent_territory, 
                          $agent_date_created){
		include ('connection.php');
    
    global $error_message;
    global $logged_status;
    global $logged_in;
    
    //if update is by an administrator the password is not updated
    if(!empty($agent_ac_no) && ($logged_status == 'SU' || $logged_status == 'AD')){
      $sql_agent = 'UPDATE `agents` SET `agent_first_name` = :agent_first_name, 
                                           `agent_last_name` = :agent_last_name, 
                                           `agent_sex` = :agent_sex, 
                                           `agent_mobile_no` = :agent_mobile_no, 
                                           `agent_email` = :agent_email,
                                           `agent_reg_id` = :agent_reg_id,
                                           `agent_status` = :agent_status,
                                           `agent_territory` = :agent_territory
                                      WHERE `agent_ac_no` = :agent_ac_no';
      
    }elseif(!empty($agent_ac_no)){ //determines whether an update is to be done by the agent
      $sql_agent = 'UPDATE `agents` SET `agent_mobile_no` = :agent_mobile_no, 
                                           `agent_email` = :agent_email, 
                                           `agent_password` = :agent_password,
                                           `agent_status` = :agent_status
                                      WHERE `agent_ac_no` = :agent_ac_no';
      
    }elseif($logged_status == 'SU' || $logged_status == 'AD'){
		$sql_agent = 'INSERT INTO `agents` (`agent_first_name`, 
                                          `agent_last_name`, 
                                          `agent_sex`, 
                                          `agent_mobile_no`, 
                                          `agent_email`, 
                                          `agent_reg_id`, 
                                          `agent_password`, 
                                          `agent_status`,
                                          `agent_territory`,
                                          `agent_date_created`)
                                      VALUES (:agent_first_name, :agent_last_name, :agent_sex, :agent_mobile_no, :agent_email, :agent_reg_id, :agent_password, :agent_status, :agent_territory, :agent_date_created)';
    }
    try { 
        $results_agent = $db->prepare($sql_agent); 
        if(empty($logged_in)){
        $results_agent->bindValue(':agent_first_name', $agent_first_name, PDO::PARAM_STR);
        $results_agent->bindValue(':agent_last_name', $agent_last_name, PDO::PARAM_STR);
        $results_agent->bindValue(':agent_sex', $agent_sex, PDO::PARAM_STR);
        $results_agent->bindValue(':agent_territory', $agent_territory, PDO::PARAM_STR);
        $results_agent->bindValue(':agent_reg_id', $agent_reg_id, PDO::PARAM_STR);
        }
        $results_agent->bindValue(':agent_mobile_no', $agent_mobile_no, PDO::PARAM_STR);
        $results_agent->bindValue(':agent_email', $agent_email, PDO::PARAM_STR);
        
      //password only managed during inserting a new record or by a user without administrative privileges
      if(!empty($agent_ac_no) && !empty($logged_in)){
        $results_agent->bindValue(':agent_ac_no', $agent_ac_no, PDO::PARAM_INT);
        $results_agent->bindValue(':agent_status', $agent_status, PDO::PARAM_STR);
        $results_agent->bindValue(':agent_password', $agent_password);
      }
      
      //the below fields only set when inserting a new record
      if(empty($agent_ac_no)){
        $results_agent->bindValue(':agent_date_created', $agent_date_created);
      }
      
      //status set during inserting a new record or by the super admin
      if(!empty($logged_status) && ($logged_status == 'SU' || $logged_status == 'AD')){
        $results_agent->bindValue(':agent_status', $agent_status, PDO::PARAM_STR);
      }
      
      //a default password set by the admin during agent creation and not on updating
      if(empty($agent_ac_no) && !empty($logged_status) && ($logged_status == 'SU' || $logged_status == 'AD')){
        $results_agent->bindValue(':agent_password', $agent_password);
      }
      
      //status set by agent
      if(!empty($agent_ac_no)){
        $results_agent->bindValue(':agent_ac_no', $agent_ac_no, PDO::PARAM_INT);
        $results_agent->bindValue(':agent_status', $agent_status, PDO::PARAM_STR);
      }
        $results_agent->execute();
        
      }catch(Exception $e){
        echo "Error!: " . $e->getMessage() . "<br />"; /*
        if(empty($agent_ac_no) && preg_match("/Duplicate entry/i", $e->getMessage())){
        $error_message = "Whoa! The A/C number ".$agent_ac_no." is already registered in the system.";
      } */
      return false;
      }
			return true;
    }
 
    
    
//this function inserts a new client if the EC # is not already in the system. Otherwise it updates details on an existing EC #.
function create_client($ecNumber,
                        $client_id,
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
						$dateMatched = NULL){
		include ('connection.php');
    
    global $error_message;
    global $logged_status;
    
    //if the EC number has been registered before the record won't post
    
     //determine whether an update is to be done otherwise a new record will be created
    //if update is by an administrator the password is not updated
    if($client_id && $logged_status == 'SU'){
      $sql_client = 'UPDATE `clients` SET `client_first_name` = :client_first_name, 
                                           `client_last_name` = :client_last_name, 
                                           `client_sex` = :client_sex, 
                                           `client_mobile_no` = :client_mobile_no, 
                                           `client_email` = :client_email,
                                           `client_level_taught` = :client_level_taught,
                                           `client_status` = :client_status,
                                           `client_ranum` = :client_ranum
                                      WHERE `client_ec_no` = :client_ec_no';
      
    }elseif($client_id){ //determines whether an update is to be done otherwise a new record will be created
      $sql_client = 'UPDATE `clients` SET `client_first_name` = :client_first_name, 
                                           `client_last_name` = :client_last_name, 
                                           `client_sex` = :client_sex, 
                                           `client_mobile_no` = :client_mobile_no, 
                                           `client_email` = :client_email, 
                                           `client_password` = :client_password, 
                                           `client_level_taught` = :client_level_taught,
                                           `client_ranum` = :client_ranum
                                      WHERE `client_ec_no` = :client_ec_no';
      
    }else{
		$sql_client = 'INSERT INTO `clients` (`client_ec_no`, 
                                          `client_first_name`, 
                                          `client_last_name`, 
                                          `client_sex`, 
                                          `client_mobile_no`, 
                                          `client_email`, 
                                          `client_password`, 
                                          `client_level_taught`,
                                          `client_agent_id`,
                                          `client_ranum`,
                                          `client_date_created`, 
                                          `client_status`, 
                                          `client_date_matched`)
                                      VALUES (:client_ec_no, :client_first_name, :client_last_name, :client_sex, :client_mobile_no, :client_email, :client_password, :client_level_taught, :client_agent_id, :client_ranum, :client_date_created, :client_status, :client_date_matched)';
    }
    try {
			    
        $results_client = $db->prepare($sql_client);
        $results_client->bindValue(':client_ec_no', $ecNumber, PDO::PARAM_STR); 
        $results_client->bindValue(':client_first_name', $userFirstName, PDO::PARAM_STR);
        $results_client->bindValue(':client_last_name', $userLastName, PDO::PARAM_STR);
        $results_client->bindValue(':client_sex', $gender, PDO::PARAM_STR);
        $results_client->bindValue(':client_mobile_no', $mobileNumber);
        $results_client->bindValue(':client_email', $userEmail, PDO::PARAM_STR);
      //password only managed during inserting a new record or by a user without administrative privileges
      if(!$client_id || (!empty($logged_status) && $logged_status != 'SU' && $logged_status != 'AD')){
        $results_client->bindValue(':client_password', $userPassword);
      }
        $results_client->bindValue(':client_level_taught', $levelTaught, PDO::PARAM_STR);
        $results_client->bindValue(':client_ranum', $clientRanum, PDO::PARAM_INT);
      //the below fields only set when inserting a new record
      if(!$client_id){
        $results_client->bindValue(':client_agent_id', $clientAgent);
        $results_client->bindValue(':client_date_created', $dateCreated);
        $results_client->bindValue(':client_date_matched', $dateMatched);
      }
      //status only set during inserting a new record or by the super admin
      if(!$client_id || (!empty($logged_status) && $logged_status == 'SU')){
        $results_client->bindValue(':client_status', $status, PDO::PARAM_STR);
      }
        $results_client->execute();
      }catch (Exception $e) {
        //echo "Error!: " . $e->getMessage() . "<br />";
        if(empty($client_id) && preg_match("/Duplicate entry/i", $e->getMessage())){
        $error_message = "Whoa! The A/C number ".$ecNumber." is already registered in our system. If you have registered before please Login or else contact our office.";
      }
      return false;
			}
			return true;
   }  
       
    //this function creates a record in the 'match_pref_provinces' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_prov($prefProv_id, $ecNumber, $levelTaught,$currProv_id,$currSch_id, $optional, $client_id){
		include ('connection.php');
       
       if(!empty($client_id)){ //determines whether deletion has to be done from the other tables before updating
         $del = array( //this is an array of all the other tables from where the record has to be deleted upon change of preference
                          "match_pref_provinces2"=>"mpp2_client_ec_no",
                          "match_pref_towns"=>"mpt_client_ec_no",
                          "match_pref_towns2"=>"mpt2_client_ec_no",
                          "match_pref_towns3"=>"mpt3_client_ec_no",
                          "match_pref_locations"=>"mpl_client_ec_no",
                          "match_pref_locations2"=>"mpl2_client_ec_no",
                          "match_pref_locations3"=>"mpl3_client_ec_no",
                          "match_pref_locations4"=>"mpl4_client_ec_no",
                          "match_pref_locations5"=>"mpl5_client_ec_no",
                          "match_pref_districts"=>"mpd_client_ec_no",
                          "match_pref_districts2"=>"mpd2_client_ec_no",
                          "match_pref_districts3"=>"mpd3_client_ec_no",
                          "match_pref_districts4"=>"mpd4_client_ec_no",
                          "match_pref_schools"=>"mps_client_ec_no",
                          "match_pref_schools2"=>"mps2_client_ec_no",
                          "match_pref_schools3"=>"mps3_client_ec_no",
                          "match_pref_schools4"=>"mps4_client_ec_no",
                          "match_pref_schools5"=>"mps5_client_ec_no",
                          "match_pref_schools6"=>"mps6_client_ec_no",
                          "match_pref_schools7"=>"mps7_client_ec_no",
                          "match_pref_schools8"=>"mps8_client_ec_no",
                          "match_pref_schools9"=>"mps9_client_ec_no",
                          "match_pref_schools10"=>"mps10_client_ec_no"
                        );
            //the below code deletes previously saved preference options upon switching to selecting by preferred province
            foreach($del as $database=>$column){
              
               $sql_del = 'DELETE FROM '.$database.' 
                      WHERE '.$column.' = ?';

              try {
                $results_del_sch1 = $db->prepare($sql_del);
                $results_del_sch1->bindValue(1, $ecNumber, PDO::PARAM_STR);
                $results_del_sch1->execute();
              } catch (Exception $e) {
                echo "Error!: " . $e->getMessage() . "<br />";
                return false;
              }
            }
          } 
          
       $sql_province = 'INSERT INTO `match_pref_provinces`
                        (`mpp_province_id`,
                        `mpp_client_ec_no`,
                        `mpp_curr_province_id`,
                        `mpp_client_level_taught`,
                        `mpp_curr_school_id`,
                        `mpp_sub1_id`,
                        `mpp_sub2_id`)
									VALUES (?, ?, ?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE `mpp_province_id` = VALUES(`mpp_province_id`),
                  `mpp_curr_province_id` = VALUES(`mpp_curr_province_id`),
                                          `mpp_client_level_taught` = VALUES(`mpp_client_level_taught`),
                                         `mpp_curr_school_id` = VALUES(`mpp_curr_school_id`),
                                          `mpp_sub1_id` = VALUES(`mpp_sub1_id`), 
                                          `mpp_sub2_id` = VALUES(`mpp_sub2_id`)';
                
                            try {
								$results_province = $db->prepare($sql_province);
								$results_province->bindValue(1, $prefProv_id, PDO::PARAM_INT);
								$results_province->bindValue(2, $ecNumber, PDO::PARAM_STR);
								$results_province->bindValue(3, $currProv_id, PDO::PARAM_INT);
                $results_province->bindValue(4, $levelTaught, PDO::PARAM_STR);
                $results_province->bindValue(5, $currSch_id, PDO::PARAM_INT);
                $results_province->bindValue(6, $optional['mpp_sub1_id'], PDO::PARAM_INT);
                $results_province->bindValue(7, $optional['mpp_sub2_id'], PDO::PARAM_INT);
								
								$results_province->execute();
							} catch (Exception $e) {
								echo "Error!: " . $e->getMessage() . "<br />";
								return false;
							}
							return true;
	}
	
	//this function creates a record in the 'match_pref_provinces2' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_prov2($prefProv2_id, $ecNumber, $levelTaught,$currProv_id,$currSch_id, $optional, $client_id){
		include ('connection.php');
          
       $sql_province2 = 'INSERT INTO `match_pref_provinces2`
                        (`mpp2_province_id`,
                        `mpp2_client_ec_no`,
                        `mpp2_curr_province_id`,
                        `mpp2_client_level_taught`,
                        `mpp2_curr_school_id`,
                        `mpp2_sub1_id`,
                        `mpp2_sub2_id`)
									VALUES (?, ?, ?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE `mpp2_province_id` = VALUES(`mpp2_province_id`),
                  `mpp2_curr_province_id` = VALUES(`mpp2_curr_province_id`),
                                          `mpp2_client_level_taught` = VALUES(`mpp2_client_level_taught`),
                                         `mpp2_curr_school_id` = VALUES(`mpp2_curr_school_id`),
                                          `mpp2_sub1_id` = VALUES(`mpp2_sub1_id`), 
                                          `mpp2_sub2_id` = VALUES(`mpp2_sub2_id`)';
                
							try {
								$results_province2 = $db->prepare($sql_province2);
								$results_province2->bindValue(1, $prefProv2_id, PDO::PARAM_INT);
								$results_province2->bindValue(2, $ecNumber, PDO::PARAM_STR);
								$results_province2->bindValue(3, $currProv_id, PDO::PARAM_INT);
                $results_province2->bindValue(4, $levelTaught, PDO::PARAM_STR);
                $results_province->bindValue(5, $currSch_id, PDO::PARAM_INT);
                $results_province2->bindValue(6, $optional['mpp2_sub1_id'], PDO::PARAM_INT);
                $results_province2->bindValue(7, $optional['mpp2_sub2_id'], PDO::PARAM_INT);
								$results_province2->execute();
							} catch (Exception $e) {
								echo "Error!: " . $e->getMessage() . "<br />";
								return false;
							}
							return true;
	}

		//this function creates a record in the 'match_pref_towns' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_town($prefTown_id, $ecNumber,$currLoc_id,$currTown_id,$currDistr_id,$currProv_id, $pref_town_province_id, $levelTaught, /*$currSch_id,*/ $optional, $client_id){
			include ('connection.php');
          
          if(!empty($client_id)){ //determines whether deletion has to be done from the other tables before updating 
          $del = array( //this is an array of all the other tables from where the record has to be deleted upon change of preference
                        "match_pref_provinces"=>"mpp_client_ec_no",
                        "match_pref_provinces2"=>"mpp2_client_ec_no",
                        "match_pref_towns2"=>"mpt2_client_ec_no",
                        "match_pref_towns3"=>"mpt3_client_ec_no",
                        "match_pref_locations"=>"mpl_client_ec_no",
                        "match_pref_locations2"=>"mpl2_client_ec_no",
                        "match_pref_locations3"=>"mpl3_client_ec_no",
                        "match_pref_locations4"=>"mpl4_client_ec_no",
                        "match_pref_locations5"=>"mpl5_client_ec_no",
                        "match_pref_districts"=>"mpd_client_ec_no",
                        "match_pref_districts2"=>"mpd2_client_ec_no",
                        "match_pref_districts3"=>"mpd3_client_ec_no",
                        "match_pref_districts4"=>"mpd4_client_ec_no",
                        "match_pref_schools"=>"mps_client_ec_no",
                        "match_pref_schools2"=>"mps2_client_ec_no",
                        "match_pref_schools3"=>"mps3_client_ec_no",
                        "match_pref_schools4"=>"mps4_client_ec_no",
                        "match_pref_schools5"=>"mps5_client_ec_no",
                        "match_pref_schools6"=>"mps6_client_ec_no",
                        "match_pref_schools7"=>"mps7_client_ec_no",
                        "match_pref_schools8"=>"mps8_client_ec_no",
                        "match_pref_schools9"=>"mps9_client_ec_no",
                        "match_pref_schools10"=>"mps10_client_ec_no"
                      );
          //the below code deletes previously saved preference options upon switching to selecting by preferred town
          foreach($del as $database=>$column){
            
             $sql_del = 'DELETE FROM '.$database.' 
                    WHERE '.$column.' = ?';

           try {
              $results_del_sch1 = $db->prepare($sql_del);
              $results_del_sch1->bindValue(1, $ecNumber, PDO::PARAM_STR);
              $results_del_sch1->execute();
            } catch (Exception $e) {
              echo "Error!: " . $e->getMessage() . "<br />";
              return false;
            }
          }
        }
        
        $sql_town = 'INSERT INTO match_pref_towns
                       (`mpt_town_id`,
                        `mpt_client_ec_no`,
                        `mpt_town_province_id`,
                        `mpt_curr_loc_id`,
                        `mpt_curr_town_id`,
                        `mpt_curr_distr_id`,
                        `mpt_curr_province_id`,
                        `mpt_client_level_taught`,
                        /*`mpt_curr_school_id`,*/
                        `mpt_sub1_id`,
                        `mpt_sub2_id`)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE `mpt_town_id` = VALUES(`mpt_town_id`),
                                          `mpt_town_province_id` = VALUES(`mpt_town_province_id`),
                                          `mpt_curr_loc_id` = VALUES(`mpt_curr_loc_id`),
                                          `mpt_curr_town_id` = VALUES(`mpt_curr_town_id`),
                                          `mpt_curr_distr_id` = VALUES(`mpt_curr_distr_id`),
                                          `mpt_curr_province_id` = VALUES(`mpt_curr_province_id`),
                                          `mpt_client_level_taught` = VALUES(`mpt_client_level_taught`),
                                          /*`mpt_curr_school_id` = VALUES(`mpt_curr_school_id`),*/
                                          `mpt_sub1_id` = VALUES(`mpt_sub1_id`), 
                                          `mpt_sub2_id` = VALUES(`mpt_sub2_id`)';
      
							try {
								$results_town = $db->prepare($sql_town);
								$results_town->bindValue(1, $prefTown_id, PDO::PARAM_INT);
								$results_town->bindValue(2, $ecNumber, PDO::PARAM_STR);
                $results_town->bindValue(3, $pref_town_province_id, PDO::PARAM_INT);
                $results_town->bindValue(4, $currLoc_id, PDO::PARAM_INT);
                $results_town->bindValue(5, $currTown_id, PDO::PARAM_INT);
                $results_town->bindValue(6, $currDistr_id, PDO::PARAM_INT);
                $results_town->bindValue(7, $currProv_id, PDO::PARAM_INT);
                $results_town->bindValue(8, $levelTaught, PDO::PARAM_STR);
                //$results_town->bindValue(4, $currSch_id, PDO::PARAM_INT);
                $results_town->bindValue(9, $optional['mpt_sub1_id'], PDO::PARAM_INT);
                $results_town->bindValue(10, $optional['mpt_sub2_id'], PDO::PARAM_INT);
								$results_town->execute();
							} catch (Exception $e) {
								echo "Error!: " . $e->getMessage() . "<br />";
								return false;
							}
							return true;
      
    }
    
    //this function creates a record in the 'match_pref_towns2' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_town2($prefTown2_id, $ecNumber,$currLoc_id,$currTown_id,$currDistr_id,$currProv_id, $pref2_town_province_id, $levelTaught, /*$currSch_id,*/ $optional, $client_id){
			include ('connection.php');
        
        $sql_town2 = 'INSERT INTO match_pref_towns2
                       (`mpt2_town_id`,
                        `mpt2_client_ec_no`,
                        `mpt2_town_province_id`,
                        `mpt2_curr_loc_id`,
                        `mpt2_curr_town_id`,
                        `mpt2_curr_distr_id`,
                        `mpt2_curr_province_id`,
                        `mpt2_client_level_taught`,
                        /*`mpt2_curr_school_id`,*/
                        `mpt2_sub1_id`,
                        `mpt2_sub2_id`)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE `mpt2_town_id` = VALUES(`mpt2_town_id`),
                                        `mpt2_town_province_id` = VALUES(`mpt2_town_province_id`),
                                          `mpt2_curr_loc_id` = VALUES(`mpt2_curr_loc_id`),
                                          `mpt2_curr_town_id` = VALUES(`mpt2_curr_town_id`),
                                          `mpt2_curr_distr_id` = VALUES(`mpt2_curr_distr_id`),
                                          `mpt2_curr_province_id` = VALUES(`mpt2_curr_province_id`),
                                          `mpt2_client_level_taught` = VALUES(`mpt2_client_level_taught`),
                                          /*`mpt2_curr_school_id` = VALUES(`mpt2_curr_school_id`),*/
                                          `mpt2_sub1_id` = VALUES(`mpt2_sub1_id`), 
                                          `mpt2_sub2_id` = VALUES(`mpt2_sub2_id`)';
      
							try {
								$results_town2 = $db->prepare($sql_town2);
								$results_town2->bindValue(1, $prefTown2_id, PDO::PARAM_INT);
								$results_town2->bindValue(2, $ecNumber, PDO::PARAM_STR);
				$results_town2->bindValue(3, $pref2_town_province_id, PDO::PARAM_INT);
                $results_town2->bindValue(4, $currLoc_id, PDO::PARAM_INT);
                $results_town2->bindValue(5, $currTown_id, PDO::PARAM_INT);
                $results_town2->bindValue(6, $currDistr_id, PDO::PARAM_INT);
                $results_town2->bindValue(7, $currProv_id, PDO::PARAM_INT);
                $results_town2->bindValue(8, $levelTaught, PDO::PARAM_STR);
                //$results_town2->bindValue(4, $currSch_id, PDO::PARAM_INT);
                $results_town2->bindValue(9, $optional['mpt2_sub1_id'], PDO::PARAM_INT);
                $results_town2->bindValue(10, $optional['mpt2_sub2_id'], PDO::PARAM_INT);
								$results_town2->execute();
							} catch (Exception $e) {
								echo "Error!: " . $e->getMessage() . "<br />";
								return false;
							}
							return true;
      
    }
    
    //this function creates a record in the 'match_pref_towns3' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_town3($prefTown3_id, $ecNumber,$currLoc_id,$currTown_id,$currDistr_id,$currProv_id, $pref3_town_province_id, $levelTaught, /*$currSch_id,*/ $optional, $client_id){
			include ('connection.php');
        
        $sql_town3 = 'INSERT INTO match_pref_towns3
                       (`mpt3_town_id`,
                        `mpt3_client_ec_no`,
                        `mpt3_town_province_id`,
                        `mpt3_curr_loc_id`,
                        `mpt3_curr_town_id`,
                        `mpt3_curr_distr_id`,
                        `mpt3_curr_province_id`,
                        `mpt3_client_level_taught`,
                        /*`mpt3_curr_school_id`,*/
                        `mpt3_sub1_id`,
                        `mpt3_sub2_id`)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE `mpt3_town_id` = VALUES(`mpt3_town_id`),
                                         `mpt3_town_province_id` = VALUES(`mpt3_town_province_id`),
                                          `mpt3_curr_loc_id` = VALUES(`mpt3_curr_loc_id`),
                                          `mpt3_curr_town_id` = VALUES(`mpt3_curr_town_id`),
                                          `mpt3_curr_distr_id` = VALUES(`mpt3_curr_distr_id`),
                                          `mpt3_curr_province_id` = VALUES(`mpt3_curr_province_id`),
                                          `mpt3_client_level_taught` = VALUES(`mpt3_client_level_taught`),
                                          /*`mpt3_curr_school_id` = VALUES(`mpt3_curr_school_id`),*/
                                          `mpt3_sub1_id` = VALUES(`mpt3_sub1_id`), 
                                          `mpt3_sub2_id` = VALUES(`mpt3_sub2_id`)';
      
							try {
								$results_town3 = $db->prepare($sql_town3);
								$results_town3->bindValue(1, $prefTown3_id, PDO::PARAM_INT);
								$results_town3->bindValue(2, $ecNumber, PDO::PARAM_STR);
				$results_town3->bindValue(3, $pref3_town_province_id, PDO::PARAM_INT);
                $results_town3->bindValue(4, $currLoc_id, PDO::PARAM_INT);
                $results_town3->bindValue(5, $currTown_id, PDO::PARAM_INT);
                $results_town3->bindValue(6, $currDistr_id, PDO::PARAM_INT);
                $results_town3->bindValue(7, $currProv_id, PDO::PARAM_INT);
                $results_town3->bindValue(8, $levelTaught, PDO::PARAM_STR);
                //$results_town3->bindValue(4, $currSch_id, PDO::PARAM_INT);
                $results_town3->bindValue(9, $optional['mpt3_sub1_id'], PDO::PARAM_INT);
                $results_town3->bindValue(10, $optional['mpt3_sub2_id'], PDO::PARAM_INT);
								$results_town3->execute();
							} catch (Exception $e) {
								echo "Error!: " . $e->getMessage() . "<br />";
								return false;
							}
							return true;
      
    }    
    
  //this function creates a record in the 'match_pref_districts' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_distr1($prefDistr1_id, $ecNumber, $levelTaught, $pref1_province_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id, /*$currSch_id,*/ $optional, $client_id){

		include ('connection.php');
          
          if(!empty($client_id)){ //determines whether deletion has to be done from the other tables before updating 
          $del = array( //this is an array of all the other tables from where the record has to be deleted upon change of preference
                        "match_pref_provinces"=>"mpp_client_ec_no",
                        "match_pref_provinces2"=>"mpp2_client_ec_no",
                        "match_pref_districts2"=>"mpd2_client_ec_no",
                        "match_pref_districts3"=>"mpd3_client_ec_no",
                        "match_pref_districts4"=>"mpd4_client_ec_no",
                        "match_pref_locations"=>"mpl_client_ec_no",
                        "match_pref_locations2"=>"mpl2_client_ec_no",
                        "match_pref_locations3"=>"mpl3_client_ec_no",
                        "match_pref_locations4"=>"mpl4_client_ec_no",
                        "match_pref_locations5"=>"mpl5_client_ec_no",
                        "match_pref_towns"=>"mpt_client_ec_no",
                        "match_pref_towns2"=>"mpt2_client_ec_no",
                        "match_pref_schools"=>"mps_client_ec_no",
                        "match_pref_schools2"=>"mps2_client_ec_no",
                        "match_pref_schools3"=>"mps3_client_ec_no",
                        "match_pref_schools4"=>"mps4_client_ec_no",
                        "match_pref_schools5"=>"mps5_client_ec_no",
                        "match_pref_schools6"=>"mps6_client_ec_no",
                        "match_pref_schools7"=>"mps7_client_ec_no",
                        "match_pref_schools8"=>"mps8_client_ec_no",
                        "match_pref_schools9"=>"mps9_client_ec_no",
                        "match_pref_schools10"=>"mps10_client_ec_no"
                      );
          //the below code deletes previously saved provinces option upon switching to selecting by preferred districts
          foreach($del as $database=>$column){
            
             $sql_del = 'DELETE FROM '.$database.' 
                    WHERE '.$column.' = ?';

            try {
              $results_del_sch1 = $db->prepare($sql_del);
              $results_del_sch1->bindValue(1, $ecNumber, PDO::PARAM_STR);
              $results_del_sch1->execute();
            } catch (Exception $e) {
              echo "Error!: " . $e->getMessage() . "<br />";
              return false;
            } 
          }
        }
        
        $sql_districts1 = 'INSERT INTO match_pref_districts
                           (`mpd_distr_id`,
                            `mpd_client_ec_no`,
                            `mpd_client_level_taught`,
                            `mpd_distr_province_id`,
                            `mpd_curr_province_id`,
                            `mpd_curr_distr_id`,
                            `mpd_curr_town_id`,
                            `mpd_curr_loc_id`,
                            /*`mpd_curr_school_id`,*/
                            `mpd_sub1_id`,
                            `mpd_sub2_id`)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                          ON DUPLICATE KEY UPDATE `mpd_distr_id` = VALUES(`mpd_distr_id`),
                                                  `mpd_client_level_taught` = VALUES(`mpd_client_level_taught`),
                                                  `mpd_distr_province_id` = VALUES(`mpd_distr_province_id`),
                                                  `mpd_curr_province_id` = VALUES(`mpd_curr_province_id`),
                                                  `mpd_curr_distr_id` = VALUES(`mpd_curr_distr_id`),
                                                  `mpd_curr_town_id` = VALUES(`mpd_curr_town_id`),
                                                  `mpd_curr_loc_id` = VALUES(`mpd_curr_loc_id`),
                                                  /*`mpd_curr_school_id` = VALUES(`mpd_curr_school_id`),*/
                                                  `mpd_sub1_id` = VALUES(`mpd_sub1_id`), 
                                                  `mpd_sub2_id` = VALUES(`mpd_sub2_id`)';
    
				try {
					$results_districts1 = $db->prepare($sql_districts1);
          $results_districts1->bindValue(1, $prefDistr1_id, PDO::PARAM_INT);
          $results_districts1->bindValue(2, $ecNumber, PDO::PARAM_STR);
          $results_districts1->bindValue(3, $levelTaught, PDO::PARAM_STR);
          $results_districts1->bindValue(4, $pref1_province_id, PDO::PARAM_INT);
          $results_districts1->bindValue(5, $currProv_id, PDO::PARAM_INT);
          $results_districts1->bindValue(6, $currDistr_id, PDO::PARAM_INT);
          $results_districts1->bindValue(7, $currTown_id, PDO::PARAM_INT);
          $results_districts1->bindValue(8, $currLoc_id, PDO::PARAM_INT);
          //$results_districts1->bindValue(5, $currSch_id, PDO::PARAM_INT);
          $results_districts1->bindValue(9, $optional['mpd_sub1_id'], PDO::PARAM_INT);
          $results_districts1->bindValue(10, $optional['mpd_sub2_id'], PDO::PARAM_INT);
          $results_districts1->execute();
				} catch (Exception $e) {
					echo "Error!: " . $e->getMessage() . "<br />";
					return false;
				}
				return true;
       
  }
  
  //this function creates a record in the 'match_pref_districts2' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_distr2($prefDistr2_id, $ecNumber, $levelTaught, $pref2_province_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id, /*$currSch_id,*/ $optional){

		include ('connection.php');
    
        $sql_districts2 = 'INSERT INTO match_pref_districts2
                           (`mpd2_distr_id`,
                            `mpd2_client_ec_no`,
                            `mpd2_client_level_taught`,
                            `mpd2_distr_province_id`,
                            `mpd2_curr_province_id`,
                            `mpd2_curr_distr_id`,
                            `mpd2_curr_town_id`,
                            `mpd2_curr_loc_id`,
                            /*`mpd2_curr_school_id`,*/
                            `mpd2_sub1_id`,
                            `mpd2_sub2_id`)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                          ON DUPLICATE KEY UPDATE `mpd2_distr_id` = VALUES(`mpd2_distr_id`),
                                                  `mpd2_client_level_taught` = VALUES(`mpd2_client_level_taught`),
                                                  `mpd2_distr_province_id` = VALUES(`mpd2_distr_province_id`),
                                                  `mpd2_curr_province_id` = VALUES(`mpd2_curr_province_id`),
                                                  `mpd2_curr_distr_id` = VALUES(`mpd2_curr_distr_id`),
                                                  `mpd2_curr_town_id` = VALUES(`mpd2_curr_town_id`),
                                                  `mpd2_curr_loc_id` = VALUES(`mpd2_curr_loc_id`),
                                                  /*`mpd2_curr_school_id` = VALUES(`mpd2_curr_school_id`),*/
                                                  `mpd2_sub1_id` = VALUES(`mpd2_sub1_id`), 
                                                  `mpd2_sub2_id` = VALUES(`mpd2_sub2_id`)';
    
				try {
					$results_districts2 = $db->prepare($sql_districts2);
          $results_districts2->bindValue(1, $prefDistr2_id, PDO::PARAM_INT);
          $results_districts2->bindValue(2, $ecNumber, PDO::PARAM_STR);
          $results_districts2->bindValue(3, $levelTaught, PDO::PARAM_STR);
          $results_districts2->bindValue(4, $pref2_province_id, PDO::PARAM_INT);
          $results_districts2->bindValue(5, $currProv_id, PDO::PARAM_INT);
          $results_districts2->bindValue(6, $currDistr_id, PDO::PARAM_INT);
          $results_districts2->bindValue(7, $currTown_id, PDO::PARAM_INT);
          $results_districts2->bindValue(8, $currLoc_id, PDO::PARAM_INT);
          //$results_districts2->bindValue(5, $currSch_id, PDO::PARAM_INT);
          $results_districts2->bindValue(9, $optional['mpd2_sub1_id'], PDO::PARAM_INT);
          $results_districts2->bindValue(10, $optional['mpd2_sub2_id'], PDO::PARAM_INT);
          $results_districts2->execute();
				} catch (Exception $e) {
					echo "Error!: " . $e->getMessage() . "<br />";
					return false;
				}
				return true;
       
  }
  
  //this function creates a record in the 'match_pref_districts3' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_distr3($prefDistr3_id, $ecNumber, $levelTaught, $pref3_province_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id, /*$currSch_id,*/ $optional){

		include ('connection.php');
    
        $sql_districts3 = 'INSERT INTO match_pref_districts3
                           (`mpd3_distr_id`,
                            `mpd3_client_ec_no`,
                            `mpd3_client_level_taught`,
                            `mpd3_distr_province_id`,
                            `mpd3_curr_province_id`,
                            `mpd3_curr_distr_id`,
                            `mpd3_curr_town_id`,
                            `mpd3_curr_loc_id`,
                            /*`mpd3_curr_school_id`,*/
                            `mpd3_sub1_id`,
                            `mpd3_sub2_id`)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                          ON DUPLICATE KEY UPDATE `mpd3_distr_id` = VALUES(`mpd3_distr_id`),
                                                  `mpd3_client_level_taught` = VALUES(`mpd3_client_level_taught`),
                                                  `mpd3_distr_province_id` = VALUES(`mpd3_distr_province_id`),
                                                  `mpd3_curr_province_id` = VALUES(`mpd3_curr_province_id`),
                                                  `mpd3_curr_distr_id` = VALUES(`mpd3_curr_distr_id`),
                                                  `mpd3_curr_town_id` = VALUES(`mpd3_curr_town_id`),
                                                  `mpd3_curr_loc_id` = VALUES(`mpd3_curr_loc_id`),
                                                  /*`mpd3_curr_school_id` = VALUES(`mpd3_curr_school_id`),*/
                                                  `mpd3_sub1_id` = VALUES(`mpd3_sub1_id`), 
                                                  `mpd3_sub2_id` = VALUES(`mpd3_sub2_id`)';
    
				try {
					$results_districts3 = $db->prepare($sql_districts3);
          $results_districts3->bindValue(1, $prefDistr3_id, PDO::PARAM_INT);
          $results_districts3->bindValue(2, $ecNumber, PDO::PARAM_STR);
          $results_districts3->bindValue(3, $levelTaught, PDO::PARAM_STR);
          $results_districts3->bindValue(4, $pref3_province_id, PDO::PARAM_INT);
          $results_districts3->bindValue(5, $currProv_id, PDO::PARAM_INT);
          $results_districts3->bindValue(6, $currDistr_id, PDO::PARAM_INT);
          $results_districts3->bindValue(7, $currTown_id, PDO::PARAM_INT);
          $results_districts3->bindValue(8, $currLoc_id, PDO::PARAM_INT);
          //$results_districts3->bindValue(5, $currSch_id, PDO::PARAM_INT);
          $results_districts3->bindValue(9, $optional['mpd3_sub1_id'], PDO::PARAM_INT);
          $results_districts3->bindValue(10, $optional['mpd3_sub2_id'], PDO::PARAM_INT);
          $results_districts3->execute();
				} catch (Exception $e) {
					echo "Error!: " . $e->getMessage() . "<br />";
					return false;
				}
				return true;
       
  }
  
  //this function creates a record in the 'match_pref_districts4' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_distr4($prefDistr4_id, $ecNumber, $levelTaught, $pref4_province_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id, /*$currSch_id,*/ $optional){

		include ('connection.php');
    
        $sql_districts4 = 'INSERT INTO match_pref_districts4
                           (`mpd4_distr_id`,
                            `mpd4_client_ec_no`,
                            `mpd4_client_level_taught`,
                            `mpd4_distr_province_id`,
                            `mpd4_curr_province_id`,
                            `mpd4_curr_distr_id`,
                            `mpd4_curr_town_id`,
                            `mpd4_curr_loc_id`,
                            /*`mpd4_curr_school_id`,*/
                            `mpd4_sub1_id`,
                            `mpd4_sub2_id`)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                          ON DUPLICATE KEY UPDATE `mpd4_distr_id` = VALUES(`mpd4_distr_id`),
                                                  `mpd4_client_level_taught` = VALUES(`mpd4_client_level_taught`),
                                                  `mpd4_distr_province_id` = VALUES(`mpd4_distr_province_id`),
                                                  `mpd4_curr_province_id` = VALUES(`mpd4_curr_province_id`),
                                                  `mpd4_curr_distr_id` = VALUES(`mpd4_curr_distr_id`),
                                                  `mpd4_curr_town_id` = VALUES(`mpd4_curr_town_id`),
                                                  `mpd4_curr_loc_id` = VALUES(`mpd4_curr_loc_id`),
                                                  /*`mpd4_curr_school_id` = VALUES(`mpd4_curr_school_id`),*/
                                                  `mpd4_sub1_id` = VALUES(`mpd4_sub1_id`), 
                                                  `mpd4_sub2_id` = VALUES(`mpd4_sub2_id`)';
    
				try {
					$results_districts4 = $db->prepare($sql_districts4);
          $results_districts4->bindValue(1, $prefDistr4_id, PDO::PARAM_INT);
          $results_districts4->bindValue(2, $ecNumber, PDO::PARAM_STR);
          $results_districts4->bindValue(3, $levelTaught, PDO::PARAM_STR);
          $results_districts4->bindValue(4, $pref4_province_id, PDO::PARAM_INT);
          $results_districts4->bindValue(5, $currProv_id, PDO::PARAM_INT);
          $results_districts4->bindValue(6, $currDistr_id, PDO::PARAM_INT);
          $results_districts4->bindValue(7, $currTown_id, PDO::PARAM_INT);
          $results_districts4->bindValue(8, $currLoc_id, PDO::PARAM_INT);
          //$results_districts4->bindValue(5, $currSch_id, PDO::PARAM_INT);
          $results_districts4->bindValue(9, $optional['mpd4_sub1_id'], PDO::PARAM_INT);
          $results_districts4->bindValue(10, $optional['mpd4_sub2_id'], PDO::PARAM_INT);
          $results_districts4->execute();
				} catch (Exception $e) {
					echo "Error!: " . $e->getMessage() . "<br />";
					return false;
				}
				return true;
       
  }
    //this function creates a record in the 'match_pref_schools' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_sch1($prefSchool1_id, $ecNumber, $levelTaught, $pref1_distr_id, $currSch_id, $optional, $client_id){

					include ('connection.php');
          
          if(!empty($client_id)){ //determines whether deletion has to be done from the other tables before updating 
          $del = array( //this is an array of all the other tables where the record has to be deleted upon change of preference
                        "match_pref_provinces"=>"mpp_client_ec_no", 
                        "match_pref_districts"=>"mpd_client_ec_no", 
                        "match_pref_districts2"=>"mpd2_client_ec_no",
                        "match_pref_towns"=>"mpt_client_ec_no",
                        "match_pref_locations"=>"mpl_client_ec_no",
                        "match_pref_locations2"=>"mpl2_client_ec_no",
                        "match_pref_locations3"=>"mpl3_client_ec_no",
                        "match_pref_schools"=>"mps_client_ec_no",
                        "match_pref_schools2"=>"mps2_client_ec_no",
                        "match_pref_schools3"=>"mps3_client_ec_no",
                        "match_pref_schools4"=>"mps4_client_ec_no",
                        "match_pref_schools5"=>"mps5_client_ec_no",
                        "match_pref_schools6"=>"mps6_client_ec_no",
                        "match_pref_schools7"=>"mps7_client_ec_no",
                        "match_pref_schools8"=>"mps8_client_ec_no",
                        "match_pref_schools9"=>"mps9_client_ec_no",
                        "match_pref_schools10"=>"mps10_client_ec_no"
                      );
          //the below code deletes previously saved provinces option upon switching to selecting by preferred schools
          foreach($del as $database=>$column){
            
             $sql_del = 'DELETE FROM '.$database.' 
                    WHERE '.$column.' = ?';

            try {
              $results_del_sch1 = $db->prepare($sql_del);
              $results_del_sch1->bindValue(1, $ecNumber, PDO::PARAM_STR);
              $results_del_sch1->execute();
            } catch (Exception $e) {
              echo "Error!: " . $e->getMessage() . "<br />";
              return false;
            } 
          }
        }          
          //insertion or updating of records is handled by the below code
          $sql_school1 = 'INSERT INTO `match_pref_schools`
                       (`mps_school_id`,
                        `mps_client_ec_no`,
                        `mps_client_level_taught`,
                        `mps_school_distr_id`,
                        `mps_curr_school_id`,
                        `mps_sub1_id`,
                        `mps_sub2_id`)
									VALUES (?, ?, ?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE `mps_school_id` = VALUES(`mps_school_id`),
                                          `mps_client_level_taught` = VALUES(`mps_client_level_taught`),
                                          `mps_school_distr_id` = VALUES(`mps_school_distr_id`),
                                          `mps_curr_school_id` = VALUES(`mps_curr_school_id`),
                                           `mps_sub1_id` = VALUES(`mps_sub1_id`), 
                                           `mps_sub2_id` = VALUES(`mps_sub2_id`)';

									try {
										$results_school1 = $db->prepare($sql_school1);
										$results_school1->bindValue(1, $prefSchool1_id, PDO::PARAM_INT);
										$results_school1->bindValue(2, $ecNumber, PDO::PARAM_STR);
                    $results_school1->bindValue(3, $levelTaught, PDO::PARAM_STR);
                    $results_school1->bindValue(4, $pref1_distr_id, PDO::PARAM_INT);
                    $results_school1->bindValue(5, $currSch_id, PDO::PARAM_INT);
                    $results_school1->bindValue(6, $optional['mps_sub1_id'], PDO::PARAM_INT);
                    $results_school1->bindValue(7, $optional['mps_sub2_id'], PDO::PARAM_INT);
										$results_school1->execute();
									} catch (Exception $e) {
										echo "Error!: " . $e->getMessage() . "<br />";
										return false;
									}
									return true;
          
    }
    
    //this function creates a record in the 'match_pref_schools2' database if the EC number is not already in the system otherwise it updates the record with that EC #
    function client_pref_sch2($prefSchool2_id, $ecNumber, $levelTaught, $pref2_distr_id, $currSch_id, $optional){

              include ('connection.php');

              $sql_school2 = 'INSERT INTO `match_pref_schools2`
                           (`mps2_school_id`,
                            `mps2_client_ec_no`,
                            `mps2_client_level_taught`,
                            `mps2_school_distr_id`,
                            `mps2_curr_school_id`,
                            `mps2_sub1_id`,
                            `mps2_sub2_id`)
                      VALUES (?, ?, ?, ?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE `mps2_school_id` = VALUES(`mps2_school_id`),
                                              `mps2_client_level_taught` = VALUES(`mps2_client_level_taught`),
                                              `mps2_school_distr_id` = VALUES(`mps2_school_distr_id`), 
                                              `mps2_curr_school_id` = VALUES(`mps2_curr_school_id`),
                                               `mps2_sub1_id` = VALUES(`mps2_sub1_id`), 
                                               `mps2_sub2_id` = VALUES(`mps2_sub2_id`)';

                      try {
                        $results_school2 = $db->prepare($sql_school2);
                        $results_school2->bindValue(1, $prefSchool2_id, PDO::PARAM_INT);
                        $results_school2->bindValue(2, $ecNumber, PDO::PARAM_STR);
                        $results_school2->bindValue(3, $levelTaught, PDO::PARAM_STR);
                        $results_school2->bindValue(4, $pref2_distr_id, PDO::PARAM_INT);
                        $results_school2->bindValue(5, $currSch_id, PDO::PARAM_INT);
                        $results_school2->bindValue(6, $optional['mps2_sub1_id'], PDO::PARAM_INT);
                        $results_school2->bindValue(7, $optional['mps2_sub2_id'], PDO::PARAM_INT);
                        $results_school2->execute();
                      } catch (Exception $e) {
                        echo "Error!: " . $e->getMessage() . "<br />";
                        return false;
                      }
                      return true;

                }
      
      //this function creates a record in the 'match_pref_schools3' database if the EC number is not already in the system otherwise it updates the record with that EC #
			function client_pref_sch3($prefSchool3_id, $ecNumber, $levelTaught, $pref3_distr_id, $currSch_id, $optional){

              include ('connection.php');

              $sql_school3 = 'INSERT INTO `match_pref_schools3`
                           (`mps3_school_id`,
                            `mps3_client_ec_no`,
                            `mps3_client_level_taught`,
                            `mps3_school_distr_id`,
                            `mps3_curr_school_id`,
                            `mps3_sub1_id`,
                            `mps3_sub2_id`)
                      VALUES (?, ?, ?, ?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE `mps3_school_id` = VALUES(`mps3_school_id`),
                                              `mps3_client_level_taught` = VALUES(`mps3_client_level_taught`),
                                              `mps3_school_distr_id` = VALUES(`mps3_school_distr_id`),
                                               `mps3_curr_school_id` = VALUES(`mps3_curr_school_id`),
                                               `mps3_sub1_id` = VALUES(`mps3_sub1_id`), 
                                               `mps3_sub2_id` = VALUES(`mps3_sub2_id`)';

                      try {
                        $results_school3 = $db->prepare($sql_school3);
                        $results_school3->bindValue(1, $prefSchool3_id, PDO::PARAM_INT);
                        $results_school3->bindValue(2, $ecNumber, PDO::PARAM_STR);
                        $results_school3->bindValue(3, $levelTaught, PDO::PARAM_STR);
                        $results_school3->bindValue(4, $pref3_distr_id, PDO::PARAM_INT);
                        $results_school3->bindValue(5, $currSch_id, PDO::PARAM_INT);
                        $results_school3->bindValue(6, $optional['mps3_sub1_id'], PDO::PARAM_INT);
                        $results_school3->bindValue(7, $optional['mps3_sub2_id'], PDO::PARAM_INT);
                        $results_school3->execute();
                      } catch (Exception $e) {
                        echo "Error!: " . $e->getMessage() . "<br />";
                        return false;
                      }
                      return true;

                }
    
      //this function creates a record in the 'match_pref_schools4' database if the EC number is not already in the system otherwise it updates the record with that EC #
			function client_pref_sch4($prefSchool4_id, $ecNumber, $levelTaught, $pref4_distr_id, $currSch_id, $optional){

              include ('connection.php');

              $sql_school4 = 'INSERT INTO `match_pref_schools4`
                           (`mps4_school_id`,
                            `mps4_client_ec_no`,
                            `mps4_client_level_taught`,
                            `mps4_school_distr_id`,
                            `mps4_curr_school_id`,
                            `mps4_sub1_id`,
                            `mps4_sub2_id`)
                      VALUES (?, ?, ?, ?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE `mps4_school_id` = VALUES(`mps4_school_id`),
                                              `mps4_client_level_taught` = VALUES(`mps4_client_level_taught`),
                                              `mps4_school_distr_id` = VALUES(`mps4_school_distr_id`), 
                                              `mps4_curr_school_id` = VALUES(`mps4_curr_school_id`),
                                               `mps4_sub1_id` = VALUES(`mps4_sub1_id`), 
                                               `mps4_sub2_id` = VALUES(`mps4_sub2_id`)';

                      try {
                        $results_school4 = $db->prepare($sql_school4);
                        $results_school4->bindValue(1, $prefSchool4_id, PDO::PARAM_INT);
                        $results_school4->bindValue(2, $ecNumber, PDO::PARAM_STR);
                        $results_school4->bindValue(3, $levelTaught, PDO::PARAM_STR);
                        $results_school4->bindValue(4, $pref4_distr_id, PDO::PARAM_INT);
                        $results_school4->bindValue(5, $currSch_id, PDO::PARAM_INT);
                        $results_school4->bindValue(6, $optional['mps4_sub1_id'], PDO::PARAM_INT);
                        $results_school4->bindValue(7, $optional['mps4_sub2_id'], PDO::PARAM_INT);
                        $results_school4->execute();
                      } catch (Exception $e) {
                        echo "Error!: " . $e->getMessage() . "<br />";
                        return false;
                      }
                      return true;

                }
      
      //this function creates a record in the 'match_pref_schools5' database if the EC number is not already in the system otherwise it updates the record with that EC #
			function client_pref_sch5($prefSchool5_id, $ecNumber, $levelTaught, $pref5_distr_id, $currSch_id, $optional){

              include ('connection.php');

              $sql_school5 = 'INSERT INTO `match_pref_schools5`
                           (`mps5_school_id`,
                            `mps5_client_ec_no`,
                            `mps5_client_level_taught`,
                            `mps5_school_distr_id`,
                            `mps5_curr_school_id`,
                            `mps5_sub1_id`,
                            `mps5_sub2_id`)
                      VALUES (?, ?, ?, ?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE `mps5_school_id` = VALUES(`mps5_school_id`),
                                              `mps5_client_level_taught` = VALUES(`mps5_client_level_taught`),
                                              `mps5_school_distr_id` = VALUES(`mps5_school_distr_id`), 
                                              `mps5_curr_school_id` = VALUES(`mps5_curr_school_id`),
                                               `mps5_sub1_id` = VALUES(`mps5_sub1_id`), 
                                               `mps5_sub2_id` = VALUES(`mps5_sub2_id`)';

                      try {
                        $results_school5 = $db->prepare($sql_school5);
                        $results_school5->bindValue(1, $prefSchool5_id, PDO::PARAM_INT);
                        $results_school5->bindValue(2, $ecNumber, PDO::PARAM_STR);
                        $results_school5->bindValue(3, $levelTaught, PDO::PARAM_STR);
                        $results_school5->bindValue(4, $pref5_distr_id, PDO::PARAM_INT);
                        $results_school5->bindValue(5, $currSch_id, PDO::PARAM_INT);
                        $results_school5->bindValue(6, $optional['mps5_sub1_id'], PDO::PARAM_INT);
                        $results_school5->bindValue(7, $optional['mps5_sub2_id'], PDO::PARAM_INT);
                        $results_school5->execute();
                      } catch (Exception $e) {
                        echo "Error!: " . $e->getMessage() . "<br />";
                        return false;
                      }
                      return true;

                }
      

			//this function creates a record in the 'match_pref_schools6' database if the EC number is not already in the system otherwise it updates the record with that EC #
      function client_pref_sch6($prefSchool6_id, $ecNumber, $levelTaught, $pref6_distr_id, $currSch_id, $optional){

              include ('connection.php');

              $sql_school6 = 'INSERT INTO `match_pref_schools6`
                           (`mps6_school_id`,
                            `mps6_client_ec_no`,
                            `mps6_client_level_taught`,
                            `mps6_school_distr_id`,
                            `mps6_curr_school_id`,
                            `mps6_sub1_id`,
                            `mps6_sub2_id`)
                      VALUES (?, ?, ?, ?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE `mps6_school_id` = VALUES(`mps6_school_id`),
                                              `mps6_client_level_taught` = VALUES(`mps6_client_level_taught`),
                                              `mps6_school_distr_id` = VALUES(`mps6_school_distr_id`), 
                                              `mps6_curr_school_id` = VALUES(`mps6_curr_school_id`),
                                               `mps6_sub1_id` = VALUES(`mps6_sub1_id`), 
                                               `mps6_sub2_id` = VALUES(`mps6_sub2_id`)';

                      try {
                        $results_school6 = $db->prepare($sql_school6);
                        $results_school6->bindValue(1, $prefSchool6_id, PDO::PARAM_INT);
                        $results_school6->bindValue(2, $ecNumber, PDO::PARAM_STR);
                        $results_school6->bindValue(3, $levelTaught, PDO::PARAM_STR);
                        $results_school6->bindValue(4, $pref6_distr_id, PDO::PARAM_INT);
                        $results_school6->bindValue(5, $currSch_id, PDO::PARAM_INT);
                        $results_school6->bindValue(6, $optional['mps6_sub1_id'], PDO::PARAM_INT);
                        $results_school6->bindValue(7, $optional['mps6_sub2_id'], PDO::PARAM_INT);
                        $results_school6->execute();
                      } catch (Exception $e) {
                        echo "Error!: " . $e->getMessage() . "<br />";
                        return false;
                      }
                      return true;

                }
      

    //this function creates a record in the 'match_pref_schools7' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_sch7($prefSchool7_id, $ecNumber, $levelTaught, $pref7_distr_id, $currSch_id, $optional){

              include ('connection.php');

              $sql_school7 = 'INSERT INTO `match_pref_schools7`
                           (`mps7_school_id`,
                            `mps7_client_ec_no`,
                            `mps7_client_level_taught`,
                            `mps7_school_distr_id`,
                            `mps7_curr_school_id`,
                            `mps7_sub1_id`,
                            `mps7_sub2_id`)
                      VALUES (?, ?, ?, ?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE `mps7_school_id` = VALUES(`mps7_school_id`),
                                              `mps7_client_level_taught` = VALUES(`mps7_client_level_taught`),
                                              `mps7_school_distr_id` = VALUES(`mps7_school_distr_id`), 
                                              `mps7_curr_school_id` = VALUES(`mps7_curr_school_id`),
                                               `mps7_sub1_id` = VALUES(`mps7_sub1_id`), 
                                               `mps7_sub2_id` = VALUES(`mps7_sub2_id`)';

                      try {
                        $results_school7 = $db->prepare($sql_school7);
                        $results_school7->bindValue(1, $prefSchool7_id, PDO::PARAM_INT);
                        $results_school7->bindValue(2, $ecNumber, PDO::PARAM_STR);
                        $results_school7->bindValue(3, $levelTaught, PDO::PARAM_STR);
                        $results_school7->bindValue(4, $pref7_distr_id, PDO::PARAM_INT);
                        $results_school7->bindValue(5, $currSch_id, PDO::PARAM_INT);
                        $results_school7->bindValue(6, $optional['mps7_sub1_id'], PDO::PARAM_INT);
                        $results_school7->bindValue(7, $optional['mps7_sub2_id'], PDO::PARAM_INT);
                        $results_school7->execute();
                      } catch (Exception $e) {
                        echo "Error!: " . $e->getMessage() . "<br />";
                        return false;
                      }
                      return true;

                }
    
      //this function creates a record in the 'match_pref_schools8' database if the EC number is not already in the system otherwise it updates the record with that EC #
			function client_pref_sch8($prefSchool8_id, $ecNumber, $levelTaught, $pref8_distr_id, $currSch_id, $optional){

              include ('connection.php');

              $sql_school8 = 'INSERT INTO `match_pref_schools8`
                           (`mps8_school_id`,
                            `mps8_client_ec_no`,
                            `mps8_client_level_taught`,
                            `mps8_school_distr_id`,
                            `mps8_curr_school_id`,
                            `mps8_sub1_id`,
                            `mps8_sub2_id`)
                      VALUES (?, ?, ?, ?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE `mps8_school_id` = VALUES(`mps8_school_id`),
                                              `mps8_client_level_taught` = VALUES(`mps8_client_level_taught`),
                                              `mps8_school_distr_id` = VALUES(`mps8_school_distr_id`),
                                              `mps8_curr_school_id` = VALUES(`mps8_curr_school_id`),
                                               `mps8_sub1_id` = VALUES(`mps8_sub1_id`), 
                                               `mps8_sub2_id` = VALUES(`mps8_sub2_id`)';

                      try {
                        $results_school8 = $db->prepare($sql_school8);
                        $results_school8->bindValue(1, $prefSchool8_id, PDO::PARAM_INT);
                        $results_school8->bindValue(2, $ecNumber, PDO::PARAM_STR);
                        $results_school8->bindValue(3, $levelTaught, PDO::PARAM_STR);
                        $results_school8->bindValue(4, $pref8_distr_id, PDO::PARAM_INT);
                        $results_school8->bindValue(5, $currSch_id, PDO::PARAM_INT);
                        $results_school8->bindValue(6, $optional['mps8_sub1_id'], PDO::PARAM_INT);
                        $results_school8->bindValue(7, $optional['mps8_sub2_id'], PDO::PARAM_INT);
                        $results_school8->execute();
                      } catch (Exception $e) {
                        echo "Error!: " . $e->getMessage() . "<br />";
                        return false;
                      }
                      return true;

                }
      
    //this function creates a record in the 'match_pref_schools9' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_sch9($prefSchool9_id, $ecNumber, $levelTaught, $pref9_distr_id, $currSch_id, $optional){

              include ('connection.php');

              $sql_school9 = 'INSERT INTO `match_pref_schools9`
                           (`mps9_school_id`,
                            `mps9_client_ec_no`,
                            `mps9_client_level_taught`,
                            `mps9_school_distr_id`,
                            `mps9_curr_school_id`,
                            `mps9_sub1_id`,
                            `mps9_sub2_id`)
                      VALUES (?, ?, ?, ?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE `mps9_school_id` = VALUES(`mps9_school_id`),
                                              `mps9_client_level_taught` = VALUES(`mps9_client_level_taught`),
                                              `mps9_school_distr_id` = VALUES(`mps9_school_distr_id`),
                                               `mps9_curr_school_id` = VALUES(`mps9_curr_school_id`),
                                               `mps9_sub1_id` = VALUES(`mps9_sub1_id`), 
                                               `mps9_sub2_id` = VALUES(`mps9_sub2_id`)';

                      try {
                        $results_school9 = $db->prepare($sql_school9);
                        $results_school9->bindValue(1, $prefSchool9_id, PDO::PARAM_INT);
                        $results_school9->bindValue(2, $ecNumber, PDO::PARAM_STR);
                        $results_school9->bindValue(3, $levelTaught, PDO::PARAM_STR);
                        $results_school9->bindValue(4, $pref9_distr_id, PDO::PARAM_INT);
                        $results_school9->bindValue(5, $currSch_id, PDO::PARAM_INT);
                        $results_school9->bindValue(6, $optional['mps9_sub1_id'], PDO::PARAM_INT);
                        $results_school9->bindValue(7, $optional['mps9_sub2_id'], PDO::PARAM_INT);
                        $results_school9->execute();
                      } catch (Exception $e) {
                        echo "Error!: " . $e->getMessage() . "<br />";
                        return false;
                      }
                      return true;

                }
		
    //this function creates a record in the 'match_pref_schools10' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_sch10($prefSchool10_id, $ecNumber, $levelTaught, $pref10_distr_id, $currSch_id, $optional){

              include ('connection.php');

              $sql_school10 = 'INSERT INTO `match_pref_schools10`
                           (`mps10_school_id`,
                            `mps10_client_ec_no`,
                            `mps10_client_level_taught`,
                            `mps10_school_distr_id`,
                            `mps10_curr_school_id`,
                            `mps10_sub1_id`,
                            `mps10_sub2_id`)
                      VALUES (?, ?, ?, ?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE `mps10_school_id` = VALUES(`mps10_school_id`),
                                              `mps10_client_level_taught` = VALUES(`mps10_client_level_taught`),
                                              `mps10_school_distr_id` = VALUES(`mps10_school_distr_id`),
                                               `mps10_curr_school_id` = VALUES(`mps10_curr_school_id`),
                                               `mps10_sub1_id` = VALUES(`mps10_sub1_id`), 
                                               `mps10_sub2_id` = VALUES(`mps10_sub2_id`)';

                      try {
                        $results_school10 = $db->prepare($sql_school10);
                        $results_school10->bindValue(1, $prefSchool10_id, PDO::PARAM_INT);
                        $results_school10->bindValue(2, $ecNumber, PDO::PARAM_STR);
                        $results_school10->bindValue(3, $levelTaught, PDO::PARAM_STR);
                        $results_school10->bindValue(4, $pref10_distr_id, PDO::PARAM_INT);
                        $results_school10->bindValue(5, $currSch_id, PDO::PARAM_INT);
                        $results_school10->bindValue(6, $optional['mps10_sub1_id'], PDO::PARAM_INT);
                        $results_school10->bindValue(7, $optional['mps10_sub2_id'], PDO::PARAM_INT);
                        $results_school10->execute();
                      } catch (Exception $e) {
                        echo "Error!: " . $e->getMessage() . "<br />";
                        return false;
                      }
                      return true;

                }
		

		//this function creates a record in the 'match_pref_locations' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_loc1($prefLoc1_id, $ecNumber, $levelTaught, $pref1_loc_province_id, $pref1_distr_id, $pref1_town_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id,/*$currSch_id,*/ $optional, $client_id){

				include ('connection.php');
          
          if(!empty($client_id)){ //determines whether deletion has to be done from the other tables before updating 
          $del = array( //this is an array of all the other tables from where the record has to be deleted upon change of preference
                        "match_pref_provinces"=>"mpp_client_ec_no",
                        "match_pref_provinces2"=>"mpp2_client_ec_no",
                        "match_pref_districts"=>"mpd_client_ec_no", 
                        "match_pref_districts2"=>"mpd2_client_ec_no",
                        "match_pref_districts3"=>"mpd3_client_ec_no",
                        "match_pref_districts4"=>"mpd4_client_ec_no",
                        "match_pref_locations"=>"mpl_client_ec_no",
                        "match_pref_locations2"=>"mpl2_client_ec_no",
                        "match_pref_locations3"=>"mpl3_client_ec_no",
                        "match_pref_locations4"=>"mpl4_client_ec_no",
                        "match_pref_locations5"=>"mpl5_client_ec_no",
                        "match_pref_towns"=>"mpt_client_ec_no",
                        "match_pref_towns2"=>"mpt2_client_ec_no",
                        "match_pref_towns3"=>"mpt3_client_ec_no",
                        "match_pref_schools"=>"mps_client_ec_no",
                        "match_pref_schools2"=>"mps2_client_ec_no",
                        "match_pref_schools3"=>"mps3_client_ec_no",
                        "match_pref_schools4"=>"mps4_client_ec_no",
                        "match_pref_schools5"=>"mps5_client_ec_no",
                        "match_pref_schools6"=>"mps6_client_ec_no",
                        "match_pref_schools7"=>"mps7_client_ec_no",
                        "match_pref_schools8"=>"mps8_client_ec_no",
                        "match_pref_schools9"=>"mps9_client_ec_no",
                        "match_pref_schools10"=>"mps10_client_ec_no"
                      );
          //the below code deletes previously saved provinces option upon switching to selecting by preferred locations
          foreach($del as $database=>$column){
            
             $sql_del = 'DELETE FROM '.$database.' 
                    WHERE '.$column.' = ?';

            try {
              $results_del = $db->prepare($sql_del);
              $results_del->bindValue(1, $ecNumber, PDO::PARAM_STR);
              $results_del->execute();
            } catch (Exception $e) {
              echo "Error!: " . $e->getMessage() . "<br />";
              return false;
            } 
          }
        }
        
				$sql_location1 = 'INSERT INTO match_pref_locations
                           (`mpl_loc_id`,
                            `mpl_client_ec_no`,
                            `mpl_client_level_taught`,
                            `mpl_loc_province_id`,
                            `mpl_loc_distr_id`,
                            `mpl_loc_town_id`,
                            `mpl_curr_province_id`,
                            `mpl_curr_distr_id`,
                            `mpl_curr_town_id`,
                            `mpl_curr_loc_id`,
                            /*`mpl_curr_school_id`,*/
                            `mpl_sub1_id`,
                            `mpl_sub2_id`)
										VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE `mpl_loc_id` = VALUES(`mpl_loc_id`),
                                            `mpl_client_level_taught` = VALUES(`mpl_client_level_taught`),
                                            `mpl_loc_province_id` = VALUES(`mpl_loc_province_id`),
                                            `mpl_loc_distr_id` = VALUES(`mpl_loc_distr_id`),
                                            `mpl_loc_town_id` = VALUES(`mpl_loc_town_id`),
                                            `mpl_curr_province_id` = VALUES(`mpl_curr_province_id`),
                                            `mpl_curr_distr_id` = VALUES(`mpl_curr_distr_id`),
                                            `mpl_curr_town_id` = VALUES(`mpl_curr_town_id`),
                                            `mpl_curr_loc_id` = VALUES(`mpl_curr_loc_id`),/*
                                             `mpl_curr_school_id` = VALUES(`mpl_curr_school_id`),*/
                                             `mpl_sub1_id` = VALUES(`mpl_sub1_id`), 
                                             `mpl_sub2_id` = VALUES(`mpl_sub2_id`)';

										try {
										$results_location1 = $db->prepare($sql_location1);
										$results_location1->bindValue(1, $prefLoc1_id, PDO::PARAM_INT);
										$results_location1->bindValue(2, $ecNumber, PDO::PARAM_STR);
                    $results_location1->bindValue(3, $levelTaught, PDO::PARAM_STR);
                    $results_location1->bindValue(4, $pref1_loc_province_id, PDO::PARAM_INT);
                    $results_location1->bindValue(5, $pref1_distr_id, PDO::PARAM_INT);
                    $results_location1->bindValue(6, $pref1_town_id, PDO::PARAM_INT);
                    $results_location1->bindValue(7, $currProv_id, PDO::PARAM_INT);
                    $results_location1->bindValue(8, $currDistr_id, PDO::PARAM_INT);
                    $results_location1->bindValue(9, $currTown_id, PDO::PARAM_INT);
                    $results_location1->bindValue(10, $currLoc_id, PDO::PARAM_INT);
                    //$results_location1->bindValue(5, $pref_town_id, PDO::PARAM_INT);
                    //$results_location1->bindValue(5, $currSch_id, PDO::PARAM_INT);
                    $results_location1->bindValue(11, $optional['mpl_sub1_id'], PDO::PARAM_INT);
                    $results_location1->bindValue(12, $optional['mpl_sub2_id'], PDO::PARAM_INT);
										$results_location1->execute();
									} catch (Exception $e) {
										echo "Error!: " . $e->getMessage() . "<br />";
										return false;
									}
									return true;

        }
    

		//this function creates a record in the 'match_pref_locations2' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_loc2($prefLoc2_id, $ecNumber, $levelTaught, $pref2_loc_province_id, $pref2_distr_id, $pref2_town_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id,/*$currSch_id,*/ $optional){

				include ('connection.php');

				$sql_location2 = 'INSERT INTO match_pref_locations2
                           (`mpl2_loc_id`,
                            `mpl2_client_ec_no`,
                            `mpl2_client_level_taught`,
                            `mpl2_loc_province_id`,
                            `mpl2_loc_distr_id`,
                            `mpl2_loc_town_id`,
                            `mpl2_curr_province_id`,
                            `mpl2_curr_distr_id`,
                            `mpl2_curr_town_id`,
                            `mpl2_curr_loc_id`,
                            /*`mpl2_curr_school_id`,*/
                            `mpl2_sub1_id`,
                            `mpl2_sub2_id`)
										VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE `mpl2_loc_id` = VALUES(`mpl2_loc_id`),
                                            `mpl2_client_level_taught` = VALUES(`mpl2_client_level_taught`),
                                            `mpl2_loc_province_id` = VALUES(`mpl2_loc_province_id`),
                                            `mpl2_loc_distr_id` = VALUES(`mpl2_loc_distr_id`),
                                            `mpl2_loc_town_id` = VALUES(`mpl2_loc_town_id`),
                                            `mpl2_curr_province_id` = VALUES(`mpl2_curr_province_id`),
                                            `mpl2_curr_distr_id` = VALUES(`mpl2_curr_distr_id`),
                                            `mpl2_curr_town_id` = VALUES(`mpl2_curr_town_id`),
                                            `mpl2_curr_loc_id` = VALUES(`mpl2_curr_loc_id`),/*
                                             `mpl2_curr_school_id` = VALUES(`mpl2_curr_school_id`),*/
                                             `mpl2_sub1_id` = VALUES(`mpl2_sub1_id`), 
                                             `mpl2_sub2_id` = VALUES(`mpl2_sub2_id`)';

										try {
										$results_location2 = $db->prepare($sql_location2);
										$results_location2->bindValue(1, $prefLoc2_id, PDO::PARAM_INT);
										$results_location2->bindValue(2, $ecNumber, PDO::PARAM_STR);
                    $results_location2->bindValue(3, $levelTaught, PDO::PARAM_STR);
                    $results_location2->bindValue(4, $pref2_loc_province_id, PDO::PARAM_INT);
                    $results_location2->bindValue(5, $pref2_distr_id, PDO::PARAM_INT);
                    $results_location2->bindValue(6, $pref2_town_id, PDO::PARAM_INT);
                    $results_location2->bindValue(7, $currProv_id, PDO::PARAM_INT);
                    $results_location2->bindValue(8, $currDistr_id, PDO::PARAM_INT);
                    $results_location2->bindValue(9, $currTown_id, PDO::PARAM_INT);
                    $results_location2->bindValue(10, $currLoc_id, PDO::PARAM_INT);
                    //$results_location2->bindValue(5, $pref2_town_id, PDO::PARAM_INT);
                    //$results_location2->bindValue(5, $currSch_id, PDO::PARAM_INT);
                    $results_location2->bindValue(11, $optional['mpl2_sub1_id'], PDO::PARAM_INT);
                    $results_location2->bindValue(12, $optional['mpl2_sub2_id'], PDO::PARAM_INT);
										$results_location2->execute();
									} catch (Exception $e) {
										echo "Error!: " . $e->getMessage() . "<br />";
										return false;
									}
									return true;

          }
    

		//this function creates a record in the 'match_pref_locations3' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_loc3($prefLoc3_id, $ecNumber, $levelTaught, $pref3_loc_province_id, $pref3_distr_id, $pref3_town_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id,/*$currSch_id,*/ $optional){

				include ('connection.php');

				$sql_location3 = 'INSERT INTO match_pref_locations3
                           (`mpl3_loc_id`,
                            `mpl3_client_ec_no`,
                            `mpl3_client_level_taught`,
                            `mpl3_loc_province_id`,
                            `mpl3_loc_distr_id`,
                            `mpl3_loc_town_id`,
                            `mpl3_curr_province_id`,
                            `mpl3_curr_distr_id`,
                            `mpl3_curr_town_id`,
                            `mpl3_curr_loc_id`,
                            /*`mpl3_curr_school_id`,*/
                            `mpl3_sub1_id`,
                            `mpl3_sub2_id`)
										VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE `mpl3_loc_id` = VALUES(`mpl3_loc_id`),
                                            `mpl3_client_level_taught` = VALUES(`mpl3_client_level_taught`),
                                            `mpl3_loc_province_id` = VALUES(`mpl3_loc_province_id`),
                                            `mpl3_loc_distr_id` = VALUES(`mpl3_loc_distr_id`),
                                            `mpl3_loc_town_id` = VALUES(`mpl3_loc_town_id`),
                                            `mpl3_curr_province_id` = VALUES(`mpl3_curr_province_id`),
                                            `mpl3_curr_distr_id` = VALUES(`mpl3_curr_distr_id`),
                                            `mpl3_curr_town_id` = VALUES(`mpl3_curr_town_id`),
                                            `mpl3_curr_loc_id` = VALUES(`mpl3_curr_loc_id`),/*
                                             `mpl3_curr_school_id` = VALUES(`mpl3_curr_school_id`),
                                             /*`mpl3_curr_school_id` = VALUES(`mpl3_curr_school_id`),*/
                                             `mpl3_sub1_id` = VALUES(`mpl3_sub1_id`), 
                                             `mpl3_sub2_id` = VALUES(`mpl3_sub2_id`)';

										try {
										$results_location3 = $db->prepare($sql_location3);
										$results_location3->bindValue(1, $prefLoc3_id, PDO::PARAM_INT);
										$results_location3->bindValue(2, $ecNumber, PDO::PARAM_STR);
                    $results_location3->bindValue(3, $levelTaught, PDO::PARAM_STR);
                    $results_location3->bindValue(4, $pref3_loc_province_id, PDO::PARAM_INT);
                    $results_location3->bindValue(5, $pref3_distr_id, PDO::PARAM_INT);
                    $results_location3->bindValue(6, $pref3_town_id, PDO::PARAM_INT);
                    $results_location3->bindValue(7, $currProv_id, PDO::PARAM_INT);
                    $results_location3->bindValue(8, $currDistr_id, PDO::PARAM_INT);
                    $results_location3->bindValue(9, $currTown_id, PDO::PARAM_INT);
                    $results_location3->bindValue(10, $currLoc_id, PDO::PARAM_INT);
                    //$results_location3->bindValue(5, $pref3_town_id, PDO::PARAM_INT);
                    //$results_location3->bindValue(5, $currSch_id, PDO::PARAM_INT);
                    $results_location3->bindValue(11, $optional['mpl3_sub1_id'], PDO::PARAM_INT);
                    $results_location3->bindValue(12, $optional['mpl3_sub2_id'], PDO::PARAM_INT);
										$results_location3->execute();
									} catch (Exception $e) {
										echo "Error!: " . $e->getMessage() . "<br />";
										return false;
									}
									return true;

      }
      
      //this function creates a record in the 'match_pref_locations4' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_loc4($prefLoc4_id, $ecNumber, $levelTaught, $pref4_loc_province_id, $pref4_distr_id, $pref4_town_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id,/*$currSch_id,*/ $optional){

				include ('connection.php');

				$sql_location4 = 'INSERT INTO match_pref_locations4
                           (`mpl4_loc_id`,
                            `mpl4_client_ec_no`,
                            `mpl4_client_level_taught`,
                            `mpl4_loc_province_id`,
                            `mpl4_loc_distr_id`,
                            `mpl4_loc_town_id`,
                            `mpl4_curr_province_id`,
                            `mpl4_curr_distr_id`,
                            `mpl4_curr_town_id`,
                            `mpl4_curr_loc_id`,
                            /*`mpl4_curr_school_id`,*/
                            `mpl4_sub1_id`,
                            `mpl4_sub2_id`)
										VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE `mpl4_loc_id` = VALUES(`mpl4_loc_id`),
                                            `mpl4_client_level_taught` = VALUES(`mpl4_client_level_taught`),
                                            `mpl4_loc_province_id` = VALUES(`mpl4_loc_province_id`),
                                            `mpl4_loc_distr_id` = VALUES(`mpl4_loc_distr_id`),
                                            `mpl4_loc_town_id` = VALUES(`mpl4_loc_town_id`),
                                            `mpl4_curr_province_id` = VALUES(`mpl4_curr_province_id`),
                                            `mpl4_curr_distr_id` = VALUES(`mpl4_curr_distr_id`),
                                            `mpl4_curr_town_id` = VALUES(`mpl4_curr_town_id`),/*
                                             `mpl4_curr_school_id` = VALUES(`mpl4_curr_school_id`),
                                            /* `mpl4_curr_school_id` = VALUES(`mpl4_curr_school_id`),
                                            `mpl4_curr_loc_id` = VALUES(`mpl4_curr_loc_id`),*/
                                             `mpl4_sub1_id` = VALUES(`mpl4_sub1_id`), 
                                             `mpl4_sub2_id` = VALUES(`mpl4_sub2_id`)';

										try {
										$results_location4 = $db->prepare($sql_location4);
										$results_location4->bindValue(1, $prefLoc4_id, PDO::PARAM_INT);
										$results_location4->bindValue(2, $ecNumber, PDO::PARAM_STR);
                    $results_location4->bindValue(3, $levelTaught, PDO::PARAM_STR);
                    $results_location4->bindValue(4, $pref4_loc_province_id, PDO::PARAM_INT);
                    $results_location4->bindValue(5, $pref4_distr_id, PDO::PARAM_INT);
                    $results_location4->bindValue(6, $pref4_town_id, PDO::PARAM_INT);
                    $results_location4->bindValue(7, $currProv_id, PDO::PARAM_INT);
                    $results_location4->bindValue(8, $currDistr_id, PDO::PARAM_INT);
                    $results_location4->bindValue(9, $currTown_id, PDO::PARAM_INT);
                    $results_location4->bindValue(10, $currLoc_id, PDO::PARAM_INT);
                    //$results_location4->bindValue(5, $pref4_town_id, PDO::PARAM_INT);
                    //$results_location4->bindValue(5, $currSch_id, PDO::PARAM_INT);
                    $results_location4->bindValue(11, $optional['mpl4_sub1_id'], PDO::PARAM_INT);
                    $results_location4->bindValue(12, $optional['mpl4_sub2_id'], PDO::PARAM_INT);
										$results_location4->execute();
									} catch (Exception $e) {
										echo "Error!: " . $e->getMessage() . "<br />";
										return false;
									}
									return true;

      }
      
      //this function creates a record in the 'match_pref_locations5' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_pref_loc5($prefLoc5_id, $ecNumber, $levelTaught, $pref5_loc_province_id, $pref5_distr_id, $pref5_town_id, $currProv_id, $currDistr_id, $currTown_id, $currLoc_id,/*$currSch_id,*/ $optional){

				include ('connection.php');

				$sql_location5 = 'INSERT INTO match_pref_locations5
                           (`mpl5_loc_id`,
                            `mpl5_client_ec_no`,
                            `mpl5_client_level_taught`,
                            `mpl5_loc_province_id`,
                            `mpl5_loc_distr_id`,
                            `mpl5_loc_town_id`,
                            `mpl5_curr_province_id`,
                            `mpl5_curr_distr_id`,
                            `mpl5_curr_town_id`,
                            `mpl5_curr_loc_id`,
                            /*`mpl5_curr_school_id`,*/
                            `mpl5_sub1_id`,
                            `mpl5_sub2_id`)
										VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE `mpl5_loc_id` = VALUES(`mpl5_loc_id`),
                                            `mpl5_client_level_taught` = VALUES(`mpl5_client_level_taught`),
                                            `mpl5_loc_province_id` = VALUES(`mpl5_loc_province_id`),
                                            `mpl5_loc_distr_id` = VALUES(`mpl5_loc_distr_id`),
                                            `mpl5_loc_town_id` = VALUES(`mpl5_loc_town_id`),
                                            `mpl5_curr_province_id` = VALUES(`mpl5_curr_province_id`),
                                            `mpl5_curr_distr_id` = VALUES(`mpl5_curr_distr_id`),
                                            `mpl5_curr_town_id` = VALUES(`mpl5_curr_town_id`),/*
                                             `mpl5_curr_school_id` = VALUES(`mpl5_curr_school_id`),
                                             /*`mpl5_curr_school_id` = VALUES(`mpl5_curr_school_id`),
                                            `mpl5_curr_loc_id` = VALUES(`mpl5_curr_loc_id`),*/
                                             `mpl5_sub1_id` = VALUES(`mpl5_sub1_id`), 
                                             `mpl5_sub2_id` = VALUES(`mpl5_sub2_id`)';

										try {
										$results_location5 = $db->prepare($sql_location5);
										$results_location5->bindValue(1, $prefLoc5_id, PDO::PARAM_INT);
										$results_location5->bindValue(2, $ecNumber, PDO::PARAM_STR);
                    $results_location5->bindValue(3, $levelTaught, PDO::PARAM_STR);
                    $results_location5->bindValue(4, $pref5_loc_province_id, PDO::PARAM_INT);
                    $results_location5->bindValue(5, $pref5_distr_id, PDO::PARAM_INT);
                    $results_location5->bindValue(6, $pref5_town_id, PDO::PARAM_INT);
                    $results_location5->bindValue(7, $currProv_id, PDO::PARAM_INT);
                    $results_location5->bindValue(8, $currDistr_id, PDO::PARAM_INT);
                    $results_location5->bindValue(9, $currTown_id, PDO::PARAM_INT);
                    $results_location5->bindValue(10, $currLoc_id, PDO::PARAM_INT);
                    //$results_location5->bindValue(5, $pref5_town_id, PDO::PARAM_INT);
                    //$results_location5->bindValue(5, $currSch_id, PDO::PARAM_INT);
                    $results_location5->bindValue(11, $optional['mpl5_sub1_id'], PDO::PARAM_INT);
                    $results_location5->bindValue(12, $optional['mpl5_sub2_id'], PDO::PARAM_INT);
										$results_location5->execute();
									} catch (Exception $e) {
										echo "Error!: " . $e->getMessage() . "<br />";
										return false;
									}
									return true;

      }
    
    
    //this function creates a record in the 'match_current_schools' database if the EC number is not already in the system otherwise it updates the record with that EC #
		function client_curr_sch($ecNumber, 
                              $currSch_id, 
                              $currDistr_id, 
                              $currProv_id, 
                              $currTown_id, 
                              $currLoc_id, 
                              $levelTaught, 
                              $optional, 
                              $prefSchool1_id, 
                              $prefSchool2_id, 
                              $prefSchool3_id, 
                              $prefSchool4_id, 
                              $prefSchool5_id, 
                              $prefSchool6_id, 
                              $prefSchool7_id, 
                              $prefSchool8_id, 
                              $prefSchool9_id, 
                              $prefSchool10_id, 
                              $prefLoc1_id, 
                              $prefLoc2_id, 
                              $prefLoc3_id, 
                              $prefLoc4_id, 
                              $prefLoc5_id, 
                              $prefTown_id, 
                              $prefTown2_id, 
                              $prefTown3_id, 
                              $prefDistr1_id, 
                              $prefDistr2_id, 
                              $prefDistr3_id, 
                              $prefDistr4_id, 
                              $prefProv_id, 
                              $prefProv2_id){
					
					include ('connection.php');
					$sql_current_sch = 'INSERT INTO match_current_schools 
											(mcs_client_ec_no,
											mcs_distr_id,
											mcs_province_id,
                      mcs_town_id,
                      mcs_loc_id,
                      mcs_school_id,
											mcs_client_level_taught,
                      mcs_sub1_id,
                      mcs_sub2_id,
                      mcs_pref_school_id,
                      mcs_pref_school2_id,
                      mcs_pref_school3_id,
                      mcs_pref_school4_id,
                      mcs_pref_school5_id,
                      mcs_pref_school6_id,
                      mcs_pref_school7_id,
                      mcs_pref_school8_id,
                      mcs_pref_school9_id,
                      mcs_pref_school10_id,
                      mcs_pref_loc_id,
                      mcs_pref_loc2_id,
                      mcs_pref_loc3_id,
                      mcs_pref_loc4_id,
                      mcs_pref_loc5_id,
                      mcs_pref_town_id,
                      mcs_pref_town2_id,
                      mcs_pref_town3_id,
                      mcs_pref_distr_id,
                      mcs_pref_distr2_id,
                      mcs_pref_distr3_id,
                      mcs_pref_distr4_id,
                      mcs_pref_province_id,
                      mcs_pref_province2_id)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE `mcs_distr_id` = VALUES(`mcs_distr_id`),
                                          `mcs_province_id` = VALUES(`mcs_province_id`),
                                          `mcs_town_id` = VALUES(`mcs_town_id`),
                                          `mcs_loc_id` = VALUES(`mcs_loc_id`),
                                          `mcs_school_id` = VALUES(`mcs_school_id`),
                                          `mcs_client_level_taught` = VALUES    (`mcs_client_level_taught`),
                                          `mcs_sub1_id` = VALUES(`mcs_sub1_id`),
                                          `mcs_sub2_id` = VALUES(`mcs_sub2_id`),
                                          `mcs_pref_school_id` = VALUES(`mcs_pref_school_id`),
                                          `mcs_pref_school2_id` = VALUES(`mcs_pref_school2_id`),
                                          `mcs_pref_school3_id` = VALUES(`mcs_pref_school3_id`),
                                          `mcs_pref_school4_id` = VALUES(`mcs_pref_school4_id`),
                                          `mcs_pref_school5_id` = VALUES(`mcs_pref_school5_id`),
                                          `mcs_pref_school6_id` = VALUES(`mcs_pref_school6_id`),
                                          `mcs_pref_school7_id` = VALUES(`mcs_pref_school7_id`),
                                          `mcs_pref_school8_id` = VALUES(`mcs_pref_school8_id`),
                                          `mcs_pref_school9_id` = VALUES(`mcs_pref_school9_id`),
                                          `mcs_pref_school10_id` = VALUES(`mcs_pref_school10_id`),
                                          `mcs_pref_loc_id` = VALUES(`mcs_pref_loc_id`),
                                          `mcs_pref_loc2_id` = VALUES(`mcs_pref_loc2_id`),
                                          `mcs_pref_loc3_id` = VALUES(`mcs_pref_loc3_id`),
                                          `mcs_pref_loc4_id` = VALUES(`mcs_pref_loc4_id`),
                                          `mcs_pref_loc5_id` = VALUES(`mcs_pref_loc5_id`),
                                          `mcs_pref_town_id` = VALUES(`mcs_pref_town_id`),
                                          `mcs_pref_town2_id` = VALUES(`mcs_pref_town2_id`),
                                          `mcs_pref_town3_id` = VALUES(`mcs_pref_town3_id`),
                                          `mcs_pref_distr_id` = VALUES(`mcs_pref_distr_id`),
                                          `mcs_pref_distr2_id` = VALUES(`mcs_pref_distr2_id`),
                                          `mcs_pref_distr3_id` = VALUES(`mcs_pref_distr3_id`),
                                          `mcs_pref_distr4_id` = VALUES(`mcs_pref_distr4_id`),
                                          `mcs_pref_province_id` = VALUES(`mcs_pref_province_id`),
                                          `mcs_pref_province2_id` = VALUES(`mcs_pref_province2_id`)';
									
									try {
										$results_current_sch = $db->prepare($sql_current_sch);
                      $results_current_sch->bindValue(1, $ecNumber, PDO::PARAM_STR);
                      $results_current_sch->bindValue(2, $currDistr_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(3, $currProv_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(4, $currTown_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(5, intval($currLoc_id), PDO::PARAM_INT);
                      $results_current_sch->bindValue(6, $currSch_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(7, $levelTaught, PDO::PARAM_STR);
                      $results_current_sch->bindValue(8, $optional['mcs_sub1_id'], PDO::PARAM_INT);
                      $results_current_sch->bindValue(9, $optional['mcs_sub2_id'], PDO::PARAM_INT);
                      $results_current_sch->bindValue(10, $prefSchool1_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(11, $prefSchool2_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(12, $prefSchool3_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(13, $prefSchool4_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(14, $prefSchool5_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(15, $prefSchool6_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(16, $prefSchool7_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(17, $prefSchool8_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(18, $prefSchool9_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(19, $prefSchool10_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(20, $prefLoc1_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(21, $prefLoc2_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(22, $prefLoc3_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(23, $prefLoc4_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(24, $prefLoc5_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(25, $prefTown_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(26, $prefTown2_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(27, $prefTown3_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(28, $prefDistr1_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(29, $prefDistr2_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(30, $prefDistr3_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(31, $prefDistr4_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(32, $prefProv_id, PDO::PARAM_INT);
                      $results_current_sch->bindValue(33, $prefProv2_id, PDO::PARAM_INT);
										$results_current_sch->execute();
									} catch (Exception $e) {
										echo "Error!: " . $e->getMessage() . "<br />";
										return false;
									}
									return true;
			
		}
}

//this function deletes clients whose registration was rejected and did not object within a month
		function delete_rejected($ec_num){

		include ('connection.php');
		
          $delete = array( //this is an array of all the tables from where the record of a rejected client must be deleted
                        "clients"=>"client_ec_no",
                        "match_current_schools"=>"mcs_client_ec_no",
                        "match_pref_provinces"=>"mpp_client_ec_no",
                        "match_pref_provinces2"=>"mpp2_client_ec_no",
                        "match_pref_districts"=>"mpd_client_ec_no",
                        "match_pref_districts2"=>"mpd2_client_ec_no",
                        "match_pref_districts3"=>"mpd3_client_ec_no",
                        "match_pref_districts4"=>"mpd4_client_ec_no",
                        "match_pref_locations"=>"mpl_client_ec_no",
                        "match_pref_locations2"=>"mpl2_client_ec_no",
                        "match_pref_locations3"=>"mpl3_client_ec_no",
                        "match_pref_locations4"=>"mpl4_client_ec_no",
                        "match_pref_locations5"=>"mpl5_client_ec_no",
                        "match_pref_towns"=>"mpt_client_ec_no",
                        "match_pref_towns2"=>"mpt2_client_ec_no",
                        "match_pref_schools"=>"mps_client_ec_no",
                        "match_pref_schools2"=>"mps2_client_ec_no",
                        "match_pref_schools3"=>"mps3_client_ec_no",
                        "match_pref_schools4"=>"mps4_client_ec_no",
                        "match_pref_schools5"=>"mps5_client_ec_no",
                        "match_pref_schools6"=>"mps6_client_ec_no",
                        "match_pref_schools7"=>"mps7_client_ec_no",
                        "match_pref_schools8"=>"mps8_client_ec_no",
                        "match_pref_schools9"=>"mps9_client_ec_no",
                        "match_pref_schools10"=>"mps10_client_ec_no"
                      );
          //the below code executes deletion from all tables where a rejected client has been included
          
          foreach($delete as $table=>$col){
            
             $sql_delete = 'DELETE FROM '.$table.' 
                    WHERE '.$col.' = ?';

            try {
              $results_delete = $db->prepare($sql_delete);
              $results_delete->bindValue(1, $ec_num, PDO::PARAM_STR);
              $results_delete->execute();
            } catch (Exception $e) {
              echo "Error!: " . $e->getMessage() . "<br />";
              return false;
            } 
          } 
		}


	function get_clients_list($SELECT, $CONDITION, $ORDER){ //extracts all clients for the clients report

		include ('connection.php');

		try{
			return $db->query($SELECT.$CONDITION.$ORDER);
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}
	
	function get_new_regs_list(){ //extracts all newly registered clients

		include ('connection.php');

		try{
			return $db->query('SELECT * FROM clients WHERE client_status = "N" ORDER BY client_id ASC');
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}
/*
 //this function extracts details used to determine whether a record should be updated or inserted
 function get_client_d ($client_ec_no){
    include 'connection.php';

    $sql = 'SELECT client_id,
                    client_ec_no
            FROM clients
            WHERE client_ec_no = ?';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $client_ec_no, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return $results->fetch();
}
*/

//extracts details of a client for viewing and possible profile update
function get_client ($client_ec_no){
    include 'connection.php';

    $sql = 'SELECT client_id,
                    client_ec_no,
                    client_first_name,
                    client_last_name,
                    client_sex,
                    client_mobile_no,
                    client_email,
                    client_status,
                    client_level_taught,
                    client_agent_id,
                    matched_res_end_time,
                    mpp_id,
                    mpp_province_id,
                    mpp2_id,
                    mpp2_province_id,
                    mpd_id,
                    mpd_distr_id,
                    mpd_distr_province_id,
                    mpd2_id,
                    mpd2_distr_id,
                    mpd2_distr_province_id,
                    mpd3_id,
                    mpd3_distr_id,
                    mpd3_distr_province_id,
                    mpd4_id,
                    mpd4_distr_id,
                    mpd4_distr_province_id,
                    mpt_id,
                    mpt_town_id,
                    mpt_town_province_id,
                    mpt2_id,
                    mpt2_town_id,
                    mpt2_town_province_id,
                    mpt3_id,
                    mpt3_town_id,
                    mpt3_town_province_id,
                    mpl_id,
                    mpl_loc_id,
                    mpl_loc_distr_id,
                    mpl2_id,
                    mpl2_loc_id,
                    mpl2_loc_distr_id,
                    mpl3_id,
                    mpl3_loc_id,
                    mpl3_loc_distr_id,
                    mpl4_id,
                    mpl4_loc_id,
                    mpl4_loc_distr_id,
                    mpl5_id,
                    mpl5_loc_id,
                    mpl5_loc_distr_id,
                    mps_id,
                    mps_school_id,
                    mps_school_distr_id,
                    mps2_id,
                    mps2_school_id,
                    mps2_school_distr_id,
                    mps3_id,
                    mps3_school_id,
                    mps3_school_distr_id,
                    mps4_id,
                    mps4_school_id,
                    mps4_school_distr_id,
                    mps5_id,
                    mps5_school_id,
                    mps5_school_distr_id,
                    mps6_id,
                    mps6_school_id,
                    mps6_school_distr_id,
                    mps7_id,
                    mps7_school_id,
                    mps7_school_distr_id,
                    mps8_id,
                    mps8_school_id,
                    mps8_school_distr_id,
                    mps9_id,
                    mps9_school_id,
                    mps9_school_distr_id,
                    mps10_id,
                    mps10_school_id,
                    mps10_school_distr_id
            FROM clients
            LEFT JOIN matched_clients ON clients.client_ec_no = matched_clients.matched_ec_no
            LEFT JOIN match_pref_provinces ON clients.client_ec_no = match_pref_provinces.mpp_client_ec_no
            LEFT JOIN match_pref_provinces2 ON clients.client_ec_no = match_pref_provinces2.mpp2_client_ec_no
            LEFT JOIN match_pref_districts ON clients.client_ec_no = match_pref_districts.mpd_client_ec_no
            LEFT JOIN match_pref_districts2 ON clients.client_ec_no = match_pref_districts2.mpd2_client_ec_no
            LEFT JOIN match_pref_districts3 ON clients.client_ec_no = match_pref_districts3.mpd3_client_ec_no
            LEFT JOIN match_pref_districts4 ON clients.client_ec_no = match_pref_districts4.mpd4_client_ec_no
            LEFT JOIN match_pref_towns ON clients.client_ec_no = match_pref_towns.mpt_client_ec_no
            LEFT JOIN match_pref_towns2 ON clients.client_ec_no = match_pref_towns2.mpt2_client_ec_no
            LEFT JOIN match_pref_towns3 ON clients.client_ec_no = match_pref_towns3.mpt3_client_ec_no
            LEFT JOIN match_pref_locations ON clients.client_ec_no = match_pref_locations.mpl_client_ec_no
            LEFT JOIN match_pref_locations2 ON clients.client_ec_no = match_pref_locations2.mpl2_client_ec_no
            LEFT JOIN match_pref_locations3 ON clients.client_ec_no = match_pref_locations3.mpl3_client_ec_no
            LEFT JOIN match_pref_locations4 ON clients.client_ec_no = match_pref_locations4.mpl4_client_ec_no
            LEFT JOIN match_pref_locations5 ON clients.client_ec_no = match_pref_locations5.mpl5_client_ec_no
            LEFT JOIN match_pref_schools ON clients.client_ec_no = match_pref_schools.mps_client_ec_no
            LEFT JOIN match_pref_schools2 ON clients.client_ec_no = match_pref_schools2.mps2_client_ec_no
            LEFT JOIN match_pref_schools3 ON clients.client_ec_no = match_pref_schools3.mps3_client_ec_no
            LEFT JOIN match_pref_schools4 ON clients.client_ec_no = match_pref_schools4.mps4_client_ec_no
            LEFT JOIN match_pref_schools5 ON clients.client_ec_no = match_pref_schools5.mps5_client_ec_no
            LEFT JOIN match_pref_schools6 ON clients.client_ec_no = match_pref_schools6.mps6_client_ec_no
            LEFT JOIN match_pref_schools7 ON clients.client_ec_no = match_pref_schools7.mps7_client_ec_no
            LEFT JOIN match_pref_schools8 ON clients.client_ec_no = match_pref_schools8.mps8_client_ec_no
            LEFT JOIN match_pref_schools9 ON clients.client_ec_no = match_pref_schools9.mps9_client_ec_no
            LEFT JOIN match_pref_schools10 ON clients.client_ec_no = match_pref_schools10.mps10_client_ec_no
            WHERE client_ec_no = ?';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $client_ec_no, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return $results->fetch();
}

//extracts details of paid client for entering a payment
function get_paid_client ($client_ec_no){
    include 'connection.php';

    $sql = 'SELECT  client_ec_no,
                    client_first_name,
                    client_last_name,
                    client_sex,
                    client_mobile_no,
                    client_email,
                    client_status,
                    matched_receipt_ref,
                    matched_amnt_paid
            FROM clients
            LEFT JOIN matched_clients ON clients.client_ec_no = matched_clients.matched_ec_no
            WHERE client_ec_no = ?';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $client_ec_no, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return $results->fetch();
}

//extracts details of registered agent
function get_agent($agent_ac_no){
    include 'connection.php';

    $sql = 'SELECT  agent_ac_no,
                    agent_first_name,
                    agent_last_name,
                    agent_reg_id,
                    agent_sex,
                    agent_mobile_no,
                    agent_email,
                    agent_status,
                    agent_territory,
                    agent_date_created
            FROM agents
            WHERE agent_ac_no = ?';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $agent_ac_no, PDO::PARAM_INT);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return $results->fetch();
}


//extracts location details for possible update
function get_loc ($locname){
    include 'connection.php';

    $sql = 'SELECT loc_name,
                    loc_distr_id,
                    loc_town_id,
                    loc_status,
                    loc_id
            FROM locations
            WHERE loc_id = ?
            ORDER BY loc_name';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $locname, PDO::PARAM_INT);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return $results->fetch();
}

    
/*
try{$results_mps = $db->query('SELECT mps_id,
                                  mps_school_id,
                                  mps_client_ec_no
                                FROM match_pref_schools
                                ORDER BY mps_id');

	}catch (Exception $e){
			echo 'Failed to retrieve mps';
			exit;

	}
	$mps1 = $results_mps->fetchAll(PDO::FETCH_ASSOC);


 function get_pref_schools($client_ec_no){
    include 'connection.php';

    global $mps;
    $sql = 'SELECT mps_id,
                    mps_school_id
            FROM match_pref_schools
            WHERE mps_client_ec_no = ?
            ORDER BY mps_id';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $client_ec_no, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return $results->fetchAll(PDO::FETCH_ASSOC);

}

function get_pref_locations($client_ec_no){
    include 'connection.php';

    global $mpl;
    $sql = 'SELECT mpl_id,
                    mpl_loc_id
            FROM match_pref_locations
            WHERE mpl_client_ec_no = ?
            ORDER BY mpl_id';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $client_ec_no, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return $results->fetchAll(PDO::FETCH_ASSOC);

}

function get_pref_districts($client_ec_no){
    include 'connection.php';

    //global $mpd;
    $sql = 'SELECT mpd_id,
                    mpd_distr_id
            FROM match_pref_districts
            WHERE mpd_client_ec_no = ?
            ORDER BY mpd_id';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $client_ec_no, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return $results->fetchAll(PDO::FETCH_ASSOC);

}
*/

function delete_client ($client_ec_no){ //deletes client from the database through the clients report
    include 'connection.php';

    $sql = 'DELETE
            FROM clients
            WHERE client_ec_no = ?';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $client_ec_no, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return true;
}

function delete_current_school ($mcs_id){ //deletes a record from the current schools database through the current schools report
    include 'connection.php';

    $sql = 'DELETE
            FROM match_current_schools
            WHERE mcs_id = ?';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $mcs_id, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return true;
}



function delete_pref_school ($mps_id){ //deletes a record from the preferred schools option 1 database through the preferred schools report
    include 'connection.php';

    $sql = 'DELETE
            FROM match_pref_schools
            WHERE mps_id = ?';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $mps_id, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return true;
}

function delete_pref_province ($mpp_id){ //deletes a record from the preferred province database through the preferred province report
    include 'connection.php';

    $sql = 'DELETE
            FROM match_pref_provinces
            WHERE mpp_id = ?';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $mpp_id, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return true;
}

function delete_pref_district ($mpd_id){ //deletes a record from the preferred district database through the preferred district report
    include 'connection.php';

    $sql = 'DELETE
            FROM match_pref_districts
            WHERE mpd_id = ?';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $mpd_id, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return true;
}

function delete_pref_town ($mpt_id){ //deletes a record from the preferred town database through the preferred town report
    include 'connection.php';

    $sql = 'DELETE
            FROM match_pref_towns
            WHERE mpt_id = ?';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $mpt_client_ec_no, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return true;
}

function delete_pref_location ($mpl_id){ //deletes a record from the preferred locations database through the preferred locations report
    include 'connection.php';

    $sql = 'DELETE
            FROM match_pref_locations
            WHERE mpl_id = ?';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $mpl_id, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    return true;
}

 function get_reg_distr($mpd_match_ec_no){
    include 'connection.php';

    $sql = 'SELECT mpd_id,mpd_distr_id FROM match_pref_districts
            WHERE mpd_client_ec_no = ?';

    try {
        $results_mpd = $db->prepare($sql);
        $results_mpd->bindValue(1, $mpd_match_ec_no, PDO::PARAM_STR);
        $results_mpd->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    $results_mpd->fetchAll(PDO::FETCH_ASSOC);
    //$get_reg_distr = $results->fetchAll();
}

/*function get_reg_loc($match_ec_no){
    include 'connection.php';

    $sql = 'SELECT mpl_distr_id, mpl_sub1_id, mpl_sub2_id FROM match_pref_locations
            WHERE mpl_client_ec_no = ?';

    try {
        $results = $db->prepare($sql);
        $results->bindValue(1, $match_ec_no, PDO::PARAM_STR);
        $results->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    $results->fetch();
 } */
// $match_ec_no = 'TIN0125';
 function get_reg_schs($mps_match_ec_no){
    include 'connection.php';

    $sql = 'SELECT mps.mps_client_ec_no, schs.school_name
             FROM match_pref_schools AS mps
             INNER JOIN schools AS schs ON mps.mps_school_id = schs.school_id
             WHERE mps.mps_client_ec_no = ?';

    try {
        $results_schs = $db->prepare($sql);
        $results_schs->bindValue(1, $mps_match_ec_no, PDO::PARAM_STR);
        $results_schs->execute();
    } catch (Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        return false;
    }
    for($i=0; $row = $results_schs->fetch(); $i++){
         echo $i." - ".$row['schs.school_name']."<br/>";
       }
 }


	function get_current_schools_list(){

		include ('connection.php');
    
    //global $sub_ec_no;
    //$sub_ec_no = $item['mcs_client_ec_no'];
    
		try{
			return $db->query('SELECT match_current_schools.mcs_id,
										match_current_schools.mcs_client_ec_no,
										schools.school_name,
										districts.distr_name,
										provinces.province_name,
										match_current_schools.mcs_client_level_taught,
                    (SELECT subjects.sub_name FROM subjects, match_current_schools AS mcs WHERE mcs.mcs_sub1_id = subjects.sub_id LIMIT 1) AS subject1,
										(SELECT subjects.sub_name FROM subjects, match_current_schools AS mcs WHERE mcs.mcs_sub2_id = subjects.sub_id LIMIT 1) AS subject2
										FROM match_current_schools
										INNER JOIN schools ON match_current_schools.mcs_school_id = schools.school_id
										INNER JOIN districts ON match_current_schools.mcs_distr_id = districts.distr_id
										INNER JOIN provinces ON match_current_schools.mcs_province_id = provinces.province_id');
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}

//the below two functions pulls data for the preferred districts reports
	function get_pref_districts_list(){

		include ('connection.php');

		try{
			return $db->query('SELECT mpd.mpd_id,
										mpd.mpd_client_ec_no,
										districts.distr_name
										FROM match_pref_districts AS mpd
										INNER JOIN districts ON mpd.mpd_distr_id = districts.distr_id');
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}
  
  function get_pref_districts2_list(){

		include ('connection.php');

		try{
			return $db->query('SELECT mpd2.mpd2_id,
										mpd2.mpd2_client_ec_no,
										districts.distr_name
										FROM match_pref_districts2 AS mpd2
										INNER JOIN districts ON mpd2.mpd2_distr_id = districts.distr_id');
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}

	//the below function pulls data for the preferred provinces reports
  function get_pref_provinces_list(){

		include ('connection.php');

		try{
			return $db->query('SELECT mpp.mpp_id,
										mpp.mpp_client_ec_no,
										provinces.province_name
										FROM match_pref_provinces AS mpp
										INNER JOIN provinces ON mpp.mpp_province_id = provinces.province_id');
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}

  //the below three functions pulls data for the preferred locations reports
	function get_pref_locations_list(){

		include ('connection.php');

		try{
			return $db->query('SELECT mpl.mpl_id,
										mpl.mpl_client_ec_no,
										locations.loc_name
										FROM match_pref_locations AS mpl
										INNER JOIN locations ON mpl.mpl_loc_id = locations.loc_id');
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}
  
  function get_pref_locations2_list(){

		include ('connection.php');

		try{
			return $db->query('SELECT mpl2.mpl2_id,
										mpl2.mpl2_client_ec_no,
										locations.loc_name
										FROM match_pref_locations2 AS mpl2
										INNER JOIN locations ON mpl2.mpl2_loc_id = locations.loc_id');
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}
  
  function get_pref_locations3_list(){

		include ('connection.php');

		try{
			return $db->query('SELECT mpl3.mpl3_id,
										mpl3.mpl3_client_ec_no,
										locations.loc_name
										FROM match_pref_locations3 AS mpl3
										INNER JOIN locations ON mpl3.mpl3_loc_id = locations.loc_id');
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}

	//the below function pulls data for the preferred towns report
  function get_pref_towns_list(){

		include ('connection.php');

		try{
			return $db->query('SELECT mpt.mpt_id,
										mpt.mpt_client_ec_no,
										towns.town_name
										FROM match_pref_towns AS mpt
										INNER JOIN towns ON mpt.mpt_town_id = towns.town_id');
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}

	// the below ten functions pulls data for the preferred schools reports
  function get_pref_schools_list(){


		include ('connection.php');

		try{
			return $db->query("SELECT mps.mps_id,
										mps.mps_client_ec_no,
										schools.school_name
										FROM match_pref_schools AS mps
										INNER JOIN schools ON mps.mps_school_id = schools.school_id");
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}
  
  function get_pref_schools2_list(){


		include ('connection.php');

		try{
			return $db->query("SELECT mps2.mps2_id,
										mps2.mps2_client_ec_no,
										schools.school_name
										FROM match_pref_schools2 AS mps2
										INNER JOIN schools ON mps2.mps2_school_id = schools.school_id");
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}
  
  function get_pref_schools3_list(){


		include ('connection.php');

		try{
			return $db->query("SELECT mps3.mps3_id,
										mps3.mps3_client_ec_no,
										schools.school_name
										FROM match_pref_schools3 AS mps3
										INNER JOIN schools ON mps3.mps3_school_id = schools.school_id");
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}
  
  function get_pref_schools4_list(){


		include ('connection.php');

		try{
			return $db->query("SELECT mps4.mps4_id,
										mps4.mps4_client_ec_no,
										schools.school_name
										FROM match_pref_schools4 AS mps4
										INNER JOIN schools ON mps4.mps4_school_id = schools.school_id");
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}
  
  function get_pref_schools5_list(){


		include ('connection.php');

		try{
			return $db->query("SELECT mps5.mps5_id,
										mps5.mps5_client_ec_no,
										schools.school_name
										FROM match_pref_schools5 AS mps5
										INNER JOIN schools ON mps5.mps5_school_id = schools.school_id");
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}
  
  function get_pref_schools6_list(){


		include ('connection.php');

		try{
			return $db->query("SELECT mps6.mps6_id,
										mps6.mps6_client_ec_no,
										schools.school_name
										FROM match_pref_schools6 AS mps6
										INNER JOIN schools ON mps6.mps6_school_id = schools.school_id");
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}
  
  function get_pref_schools7_list(){


		include ('connection.php');

		try{
			return $db->query("SELECT mps7.mps7_id,
										mps7.mps7_client_ec_no,
										schools.school_name
										FROM match_pref_schools7 AS mps7
										INNER JOIN schools ON mps7.mps7_school_id = schools.school_id");
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}
  
  function get_pref_schools8_list(){


		include ('connection.php');

		try{
			return $db->query("SELECT mps8.mps8_id,
										mps8.mps8_client_ec_no,
										schools.school_name
										FROM match_pref_schools8 AS mps8
										INNER JOIN schools ON mps8.mps8_school_id = schools.school_id");
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}
  
  function get_pref_schools9_list(){


		include ('connection.php');

		try{
			return $db->query("SELECT mps9.mps9_id,
										mps9.mps9_client_ec_no,
										schools.school_name
										FROM match_pref_schools9 AS mps9
										INNER JOIN schools ON mps9.mps9_school_id = schools.school_id");
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}
  
  function get_pref_schools10_list(){


		include ('connection.php');

		try{
			return $db->query("SELECT mps10.mps10_id,
										mps10.mps10_client_ec_no,
										schools.school_name
										FROM match_pref_schools10 AS mps10
										INNER JOIN schools ON mps10.mps10_school_id = schools.school_id");
		}catch (Exception $e){

			echo 'Error!: '.$e->getMessage(). '<br>';
			return array();
		}
	}

//the below function queries all matching records
  function matched_records($conditions){
    include 'connection.php';
    
    try{
      $results = $db->query("SELECT
      co_prefs.EC_NO,
      mcs.mcs_client_ec_no,
      co_prefs.tab
          FROM
              match_current_schools AS mcs
          INNER JOIN
          (
              SELECT
                  'match_pref_locations' AS tab,
                  mpl_client_ec_no AS EC_NO,
                  mpl_loc_province_id AS province_id,
                  mpl_loc_distr_id AS distr_id,
                  mpl_loc_town_id AS town_id,
                  mpl_loc_id AS loc_id,
                  mpl_curr_province_id AS curr_province_id,
                  mpl_curr_distr_id AS curr_distr_id,
                  mpl_curr_town_id AS curr_town_id,
                  mpl_curr_loc_id AS curr_loc_id,
                  mpl_client_level_taught AS level_taught,
                  mpl_sub1_id AS sub1_id,
                  mpl_sub2_id AS sub2_id,
                  mpl_status AS status
              FROM
                  match_pref_locations
              UNION ALL
          SELECT
              'match_pref_locations2' AS tab,
              mpl2_client_ec_no AS EC_NO,
              mpl2_loc_province_id AS province_id,
              mpl2_loc_distr_id AS distr_id,
              mpl2_loc_town_id AS town_id,
              mpl2_loc_id AS loc_id,
              mpl2_curr_province_id AS curr_province_id,
              mpl2_curr_distr_id AS curr_distr_id,
              mpl2_curr_town_id AS curr_town_id,
              mpl2_curr_loc_id AS curr_loc_id,
              mpl2_client_level_taught AS level_taught,
              mpl2_sub1_id AS sub1_id,
              mpl2_sub2_id AS sub2_id,
              mpl2_status AS status
          FROM
              match_pref_locations2
          UNION ALL
          SELECT
              'match_pref_locations3' AS tab,
              mpl3_client_ec_no AS EC_NO,
              mpl3_loc_province_id AS province_id,
              mpl3_loc_distr_id AS distr_id,
              mpl3_loc_town_id AS town_id,
              mpl3_loc_id AS loc_id,
              mpl3_curr_province_id AS curr_province_id,
              mpl3_curr_distr_id AS curr_distr_id,
              mpl3_curr_town_id AS curr_town_id,
              mpl3_curr_loc_id AS curr_loc_id,
              mpl3_client_level_taught AS level_taught,
              mpl3_sub1_id AS sub1_id,
              mpl3_sub2_id AS sub2_id,
              mpl3_status AS status
          FROM
              match_pref_locations3
          UNION ALL
          SELECT
              'match_pref_locations4' AS tab,
              mpl4_client_ec_no AS EC_NO,
              mpl4_loc_province_id AS province_id,
              mpl4_loc_distr_id AS distr_id,
              mpl4_loc_town_id AS town_id,
              mpl4_loc_id AS loc_id,
              mpl4_curr_province_id AS curr_province_id,
              mpl4_curr_distr_id AS curr_distr_id,
              mpl4_curr_town_id AS curr_town_id,
              mpl4_curr_loc_id AS curr_loc_id,
              mpl4_client_level_taught AS level_taught,
              mpl4_sub1_id AS sub1_id,
              mpl4_sub2_id AS sub2_id,
              mpl4_status AS status
          FROM
              match_pref_locations4
          UNION ALL
          SELECT
              'match_pref_locations5' AS tab,
              mpl5_client_ec_no AS EC_NO,
              mpl5_loc_province_id AS province_id,
              mpl5_loc_distr_id AS distr_id,
              mpl5_loc_town_id AS town_id,
              mpl5_loc_id AS loc_id,
              mpl5_curr_province_id AS curr_province_id,
              mpl5_curr_distr_id AS curr_distr_id,
              mpl5_curr_town_id AS curr_town_id,
              mpl5_curr_loc_id AS curr_loc_id,
              mpl5_client_level_taught AS level_taught,
              mpl5_sub1_id AS sub1_id,
              mpl5_sub2_id AS sub2_id,
              mpl5_status AS status
          FROM
              match_pref_locations5
          UNION ALL
          SELECT
              'match_pref_towns' AS tab,
              mpt_client_ec_no AS EC_NO,
              mpt_town_province_id AS province_id,
              mpt_town_distr_id AS distr_id,
              mpt_town_id AS town_id,
              0 AS loc_id,
              mpt_curr_province_id AS curr_province_id,
              mpt_curr_distr_id AS curr_distr_id,
              mpt_curr_town_id AS curr_town_id,
              mpt_curr_loc_id AS curr_loc_id,
              mpt_client_level_taught AS level_taught,
              mpt_sub1_id AS sub1_id,
              mpt_sub2_id AS sub2_id,
              mpt_status AS status
          FROM
              match_pref_towns
          UNION ALL
          SELECT
              'match_pref_towns2' AS tab,
              mpt2_client_ec_no AS EC_NO,
              mpt2_town_province_id AS province_id,
              mpt2_town_distr_id AS distr_id,
              mpt2_town_id AS town_id,
              0 AS loc_id,
              mpt2_curr_province_id AS curr_province_id,
              mpt2_curr_distr_id AS curr_distr_id,
              mpt2_curr_town_id AS curr_town_id,
              mpt2_curr_loc_id AS curr_loc_id,
              mpt2_client_level_taught AS level_taught,
              mpt2_sub1_id AS sub1_id,
              mpt2_sub2_id AS sub2_id,
              mpt2_status AS status
          FROM
              match_pref_towns2
          UNION ALL
          SELECT
              'match_pref_towns3' AS tab,
              mpt3_client_ec_no AS EC_NO,
              mpt3_town_province_id AS province_id,
              mpt3_town_distr_id AS distr_id,
              mpt3_town_id AS town_id,
              0 AS loc_id,
              mpt3_curr_province_id AS curr_province_id,
              mpt3_curr_distr_id AS curr_distr_id,
              mpt3_curr_town_id AS curr_town_id,
              mpt3_curr_loc_id AS curr_loc_id,
              mpt3_client_level_taught AS level_taught,
              mpt3_sub1_id AS sub1_id,
              mpt3_sub2_id AS sub2_id,
              mpt3_status AS status
          FROM
              match_pref_towns3
          UNION ALL
          SELECT
              'match_pref_districts' AS tab,
              mpd_client_ec_no AS EC_NO,
              mpd_distr_province_id AS province_id,
              mpd_distr_id AS distr_id,
              mpd_distr_town_id AS town_id,
              0 AS loc_id,
              mpd_curr_province_id AS curr_province_id,
              mpd_curr_distr_id AS curr_distr_id,
              mpd_curr_town_id AS curr_town_id,
              mpd_curr_loc_id AS curr_loc_id,
              mpd_client_level_taught AS level_taught,
              mpd_sub1_id AS sub1_id,
              mpd_sub2_id AS sub2_id,
              mpd_status AS status
          FROM
              match_pref_districts
          UNION ALL
          SELECT
              'match_pref_districts2' AS tab,
              mpd2_client_ec_no AS EC_NO,
              mpd2_distr_province_id AS province_id,
              mpd2_distr_id AS distr_id,
              mpd2_distr_town_id AS town_id,
              0 AS loc_id,
              mpd2_curr_province_id AS curr_province_id,
              mpd2_curr_distr_id AS curr_distr_id,
              mpd2_curr_town_id AS curr_town_id,
              mpd2_curr_loc_id AS curr_loc_id,
              mpd2_client_level_taught AS level_taught,
              mpd2_sub1_id AS sub1_id,
              mpd2_sub2_id AS sub2_id,
              mpd2_status AS status
          FROM
              match_pref_districts2
          UNION ALL
          SELECT
              'match_pref_districts3' AS tab,
              mpd3_client_ec_no AS EC_NO,
              mpd3_distr_province_id AS province_id,
              mpd3_distr_id AS distr_id,
              mpd3_distr_town_id AS town_id,
              0 AS loc_id,
              mpd3_curr_province_id AS curr_province_id,
              mpd3_curr_distr_id AS curr_distr_id,
              mpd3_curr_town_id AS curr_town_id,
              mpd3_curr_loc_id AS curr_loc_id,
              mpd3_client_level_taught AS level_taught,
              mpd3_sub1_id AS sub1_id,
              mpd3_sub2_id AS sub2_id,
              mpd3_status AS status
          FROM
              match_pref_districts3
          UNION ALL
          SELECT
              'match_pref_districts4' AS tab,
              mpd4_client_ec_no AS EC_NO,
              mpd4_distr_province_id AS province_id,
              mpd4_distr_id AS distr_id,
              mpd4_distr_town_id AS town_id,
              0 AS loc_id,
              mpd4_curr_province_id AS curr_province_id,
              mpd4_curr_distr_id AS curr_distr_id,
              mpd4_curr_town_id AS curr_town_id,
              mpd4_curr_loc_id AS curr_loc_id,
              mpd4_client_level_taught AS level_taught,
              mpd4_sub1_id AS sub1_id,
              mpd4_sub2_id AS sub2_id,
              mpd4_status AS status
          FROM
              match_pref_districts4
          UNION ALL
          SELECT
              'match_pref_provinces' AS tab,
              mpp_client_ec_no AS EC_NO,
              mpp_province_id AS province_id,
              0 AS distr_id,
              0 AS town_id,
              0 AS loc_id,
              mpp_curr_province_id AS curr_province_id,
              0 AS curr_distr_id,
              0 AS curr_town_id,
              0 AS curr_loc_id,
              mpp_client_level_taught AS level_taught,
              mpp_sub1_id AS sub1_id,
              mpp_sub2_id AS sub2_id,
              mpp_status AS status
          FROM
              match_pref_provinces
          UNION ALL
          SELECT
              'match_pref_provinces2' AS tab,
              mpp2_client_ec_no AS EC_NO,
              mpp2_province_id AS province_id,
              0 AS distr_id,
              0 AS town_id,
              0 AS loc_id,
              mpp2_curr_province_id AS curr_province_id,
              0 AS curr_distr_id,
              0 AS curr_town_id,
              0 AS curr_loc_id,
              mpp2_client_level_taught AS level_taught,
              mpp2_sub1_id AS sub1_id,
              mpp2_sub2_id AS sub2_id,
              mpp2_status AS status
          FROM
              match_pref_provinces2
          ) AS co_prefs ".$conditions);
          
            }catch (Exception $e){
                echo 'Failed to retrieve matched records';
                exit;
          
            }
    
    return $results->fetchAll(PDO::FETCH_ASSOC);
  }
  
/*
//the below function queries all matching records
  function matched_records($conditions){
    include 'connection.php';
    
    try{
      $results = $db->query("SELECT
      co_prefs.EC_NO,
      mcs.mcs_client_ec_no,
      co_prefs.tab
          FROM
              match_current_schools AS mcs
          INNER JOIN
          (
              SELECT
                  'match_pref_locations' AS tab,
                  mpl_client_ec_no AS EC_NO,
                  mpl_loc_province_id AS province_id,
                  mpl_loc_distr_id AS distr_id,
                  mpl_loc_town_id AS town_id,
                  mpl_loc_id AS loc_id,
                  mpl_curr_province_id AS curr_province_id,
                  mpl_curr_distr_id AS curr_distr_id,
                  mpl_curr_town_id AS curr_town_id,
                  mpl_curr_loc_id AS curr_loc_id,
                  mpl_client_level_taught AS level_taught,
                  mpl_sub1_id AS sub1_id,
                  mpl_sub2_id AS sub2_id,
                  mpl_status AS status
              FROM
                  match_pref_locations
              UNION ALL
          SELECT
              'match_pref_locations2' AS tab,
              mpl2_client_ec_no AS EC_NO,
              mpl2_loc_province_id AS province_id,
              mpl2_loc_distr_id AS distr_id,
              mpl2_loc_town_id AS town_id,
              mpl2_loc_id AS loc_id,
              mpl2_curr_province_id AS curr_province_id,
              mpl2_curr_distr_id AS curr_distr_id,
              mpl2_curr_town_id AS curr_town_id,
              mpl2_curr_loc_id AS curr_loc_id,
              mpl2_client_level_taught AS level_taught,
              mpl2_sub1_id AS sub1_id,
              mpl2_sub2_id AS sub2_id,
              mpl2_status AS status
          FROM
              match_pref_locations2
          UNION ALL
          SELECT
              'match_pref_locations3' AS tab,
              mpl3_client_ec_no AS EC_NO,
              mpl3_loc_province_id AS province_id,
              mpl3_loc_distr_id AS distr_id,
              mpl3_loc_town_id AS town_id,
              mpl3_loc_id AS loc_id,
              mpl3_curr_province_id AS curr_province_id,
              mpl3_curr_distr_id AS curr_distr_id,
              mpl3_curr_town_id AS curr_town_id,
              mpl3_curr_loc_id AS curr_loc_id,
              mpl3_client_level_taught AS level_taught,
              mpl3_sub1_id AS sub1_id,
              mpl3_sub2_id AS sub2_id,
              mpl3_status AS status
          FROM
              match_pref_locations3
          UNION ALL
          SELECT
              'match_pref_locations4' AS tab,
              mpl4_client_ec_no AS EC_NO,
              mpl4_loc_province_id AS province_id,
              mpl4_loc_distr_id AS distr_id,
              mpl4_loc_town_id AS town_id,
              mpl4_loc_id AS loc_id,
              mpl4_curr_province_id AS curr_province_id,
              mpl4_curr_distr_id AS curr_distr_id,
              mpl4_curr_town_id AS curr_town_id,
              mpl4_curr_loc_id AS curr_loc_id,
              mpl4_client_level_taught AS level_taught,
              mpl4_sub1_id AS sub1_id,
              mpl4_sub2_id AS sub2_id,
              mpl4_status AS status
          FROM
              match_pref_locations4
          UNION ALL
          SELECT
              'match_pref_locations5' AS tab,
              mpl5_client_ec_no AS EC_NO,
              mpl5_loc_province_id AS province_id,
              mpl5_loc_distr_id AS distr_id,
              mpl5_loc_town_id AS town_id,
              mpl5_loc_id AS loc_id,
              mpl5_curr_province_id AS curr_province_id,
              mpl5_curr_distr_id AS curr_distr_id,
              mpl5_curr_town_id AS curr_town_id,
              mpl5_curr_loc_id AS curr_loc_id,
              mpl5_client_level_taught AS level_taught,
              mpl5_sub1_id AS sub1_id,
              mpl5_sub2_id AS sub2_id,
              mpl5_status AS status
          FROM
              match_pref_locations5
          UNION ALL
          SELECT
              'match_pref_towns' AS tab,
              mpt_client_ec_no AS EC_NO,
              mpt_town_province_id AS province_id,
              mpt_town_distr_id AS distr_id,
              mpt_town_id AS town_id,
              0 AS loc_id,
              mpt_curr_province_id AS curr_province_id,
              mpt_curr_distr_id AS curr_distr_id,
              mpt_curr_town_id AS curr_town_id,
              mpt_curr_loc_id AS curr_loc_id,
              mpt_client_level_taught AS level_taught,
              mpt_sub1_id AS sub1_id,
              mpt_sub2_id AS sub2_id,
              mpt_status AS status
          FROM
              match_pref_towns
          UNION ALL
          SELECT
              'match_pref_towns2' AS tab,
              mpt2_client_ec_no AS EC_NO,
              mpt2_town_province_id AS province_id,
              mpt2_town_distr_id AS distr_id,
              mpt2_town_id AS town_id,
              0 AS loc_id,
              mpt2_curr_province_id AS curr_province_id,
              mpt2_curr_distr_id AS curr_distr_id,
              mpt2_curr_town_id AS curr_town_id,
              mpt2_curr_loc_id AS curr_loc_id,
              mpt2_client_level_taught AS level_taught,
              mpt2_sub1_id AS sub1_id,
              mpt2_sub2_id AS sub2_id,
              mpt2_status AS status
          FROM
              match_pref_towns2
          UNION ALL
          SELECT
              'match_pref_towns3' AS tab,
              mpt3_client_ec_no AS EC_NO,
              mpt3_town_province_id AS province_id,
              mpt3_town_distr_id AS distr_id,
              mpt3_town_id AS town_id,
              0 AS loc_id,
              mpt3_curr_province_id AS curr_province_id,
              mpt3_curr_distr_id AS curr_distr_id,
              mpt3_curr_town_id AS curr_town_id,
              mpt3_curr_loc_id AS curr_loc_id,
              mpt3_client_level_taught AS level_taught,
              mpt3_sub1_id AS sub1_id,
              mpt3_sub2_id AS sub2_id,
              mpt3_status AS status
          FROM
              match_pref_towns3
          UNION ALL
          SELECT
              'match_pref_districts' AS tab,
              mpd_client_ec_no AS EC_NO,
              mpd_distr_province_id AS province_id,
              mpd_distr_id AS distr_id,
              mpd_distr_town_id AS town_id,
              0 AS loc_id,
              mpd_curr_province_id AS curr_province_id,
              mpd_curr_distr_id AS curr_distr_id,
              mpd_curr_town_id AS curr_town_id,
              mpd_curr_loc_id AS curr_loc_id,
              mpd_client_level_taught AS level_taught,
              mpd_sub1_id AS sub1_id,
              mpd_sub2_id AS sub2_id,
              mpd_status AS status
          FROM
              match_pref_districts
          UNION ALL
          SELECT
              'match_pref_districts2' AS tab,
              mpd2_client_ec_no AS EC_NO,
              mpd2_distr_province_id AS province_id,
              mpd2_distr_id AS distr_id,
              mpd2_distr_town_id AS town_id,
              0 AS loc_id,
              mpd2_curr_province_id AS curr_province_id,
              mpd2_curr_distr_id AS curr_distr_id,
              mpd2_curr_town_id AS curr_town_id,
              mpd2_curr_loc_id AS curr_loc_id,
              mpd2_client_level_taught AS level_taught,
              mpd2_sub1_id AS sub1_id,
              mpd2_sub2_id AS sub2_id,
              mpd2_status AS status
          FROM
              match_pref_districts2
          UNION ALL
          SELECT
              'match_pref_districts3' AS tab,
              mpd3_client_ec_no AS EC_NO,
              mpd3_distr_province_id AS province_id,
              mpd3_distr_id AS distr_id,
              mpd3_distr_town_id AS town_id,
              0 AS loc_id,
              mpd3_curr_province_id AS curr_province_id,
              mpd3_curr_distr_id AS curr_distr_id,
              mpd3_curr_town_id AS curr_town_id,
              mpd3_curr_loc_id AS curr_loc_id,
              mpd3_client_level_taught AS level_taught,
              mpd3_sub1_id AS sub1_id,
              mpd3_sub2_id AS sub2_id,
              mpd3_status AS status
          FROM
              match_pref_districts3
          UNION ALL
          SELECT
              'match_pref_districts4' AS tab,
              mpd4_client_ec_no AS EC_NO,
              mpd4_distr_province_id AS province_id,
              mpd4_distr_id AS distr_id,
              mpd4_distr_town_id AS town_id,
              0 AS loc_id,
              mpd4_curr_province_id AS curr_province_id,
              mpd4_curr_distr_id AS curr_distr_id,
              mpd4_curr_town_id AS curr_town_id,
              mpd4_curr_loc_id AS curr_loc_id,
              mpd4_client_level_taught AS level_taught,
              mpd4_sub1_id AS sub1_id,
              mpd4_sub2_id AS sub2_id,
              mpd4_status AS status
          FROM
              match_pref_districts4
          UNION ALL
          SELECT
              'match_pref_provinces' AS tab,
              mpp_client_ec_no AS EC_NO,
              mpp_province_id AS province_id,
              0 AS distr_id,
              0 AS town_id,
              0 AS loc_id,
              mpp_curr_province_id AS curr_province_id,
              0 AS curr_distr_id,
              0 AS curr_town_id,
              0 AS curr_loc_id,
              mpp_client_level_taught AS level_taught,
              mpp_sub1_id AS sub1_id,
              mpp_sub2_id AS sub2_id,
              mpp_status AS status
          FROM
              match_pref_provinces
          UNION ALL
          SELECT
              'match_pref_provinces2' AS tab,
              mpp2_client_ec_no AS EC_NO,
              mpp2_province_id AS province_id,
              0 AS distr_id,
              0 AS town_id,
              0 AS loc_id,
              mpp2_curr_province_id AS curr_province_id,
              0 AS curr_distr_id,
              0 AS curr_town_id,
              0 AS curr_loc_id,
              mpp2_client_level_taught AS level_taught,
              mpp2_sub1_id AS sub1_id,
              mpp2_sub2_id AS sub2_id,
              mpp2_status AS status
          FROM
              match_pref_provinces2
          ) AS co_prefs ".$conditions);
          
            }catch (Exception $e){
                echo 'Failed to retrieve matched records';
                exit;
          
            }
    
    return $results->fetchAll(PDO::FETCH_ASSOC);
  } */
/**/
//the below functions prepares matches starting with first preferred schools options followed by preferred locations, preferred towns, preferred districts and finally preferred provinces

try{$results_curr_school = $db->query('SELECT mcs_school_id, mcs_client_ec_no
                                        FROM match_current_schools
                                        ORDER BY mcs_id');

	}catch (Exception $e){
			echo 'Failed to retrieve matched current school';
			exit;

	}
	$matched_curr_schools = $results_curr_school->fetchAll(PDO::FETCH_ASSOC); 
 
  //the below functions extract matching records from the databases. The queries will be further refined by a PHP script before a database update.
  //only the first query is commented in detail and the others work in a similar manner
  try{
    $results_pref_schools = $db->query(
                                     "SELECT co_prefs.EC_NO,mcs.mcs_client_ec_no, mcs_school_id, co_prefs.tab, co_prefs.pref_id
                                     /* the EC numbers of the matched records will be shown together with the table names 
                                      */
                                      FROM match_current_schools AS mcs
                                      INNER JOIN 
                                          /* the below statements creates a subquery table from all the preferred category options that will be used to search for matching records 
                                          */
                                            ( SELECT 'match_pref_schools' AS tab, mps_client_ec_no AS EC_NO, mps_school_id AS pref_id, mps_curr_school_id AS curr_id, mps_sub1_id AS pref_sub1_id, mps_sub2_id AS pref_sub2_id, mps_client_level_taught AS pref_client_level_taught, mps_status AS pref_status
                                              FROM match_pref_schools
                                              UNION ALL
                                              SELECT 'match_pref_schools2' AS tab, mps2_client_ec_no AS EC_NO, mps2_school_id AS pref_id, mps2_curr_school_id AS curr_id, mps2_sub1_id AS pref_sub1_id, mps2_sub2_id AS pref_sub2_id, mps2_client_level_taught AS pref_client_level_taught, mps2_status AS pref_status
                                              FROM match_pref_schools2
                                              UNION ALL
                                              SELECT 'match_pref_schools3' AS tab, mps3_client_ec_no AS EC_NO, mps3_school_id AS pref_id, mps3_curr_school_id AS curr_id, mps3_sub1_id AS pref_sub1_id, mps3_sub2_id AS pref_sub2_id, mps3_client_level_taught AS pref_client_level_taught, mps3_status AS pref_status
                                              FROM match_pref_schools3
                                              UNION ALL
                                              SELECT 'match_pref_schools4' AS tab, mps4_client_ec_no AS EC_NO, mps4_school_id AS pref_id, mps4_curr_school_id AS curr_id, mps4_sub1_id AS pref_sub1_id, mps4_sub2_id AS pref_sub2_id, mps4_client_level_taught AS pref_client_level_taught, mps4_status AS pref_status
                                              FROM match_pref_schools4
                                              UNION ALL
                                              SELECT 'match_pref_schools5' AS tab, mps5_client_ec_no AS EC_NO, mps5_school_id AS pref_id, mps5_curr_school_id AS curr_id, mps5_sub1_id AS pref_sub1_id, mps5_sub2_id AS pref_sub2_id, mps5_client_level_taught AS pref_client_level_taught, mps5_status AS pref_status
                                              FROM match_pref_schools5
                                              UNION ALL
                                              SELECT 'match_pref_schools6' AS tab, mps6_client_ec_no AS EC_NO, mps6_school_id AS pref_id, mps6_curr_school_id AS curr_id, mps6_sub1_id AS pref_sub1_id, mps6_sub2_id AS pref_sub2_id, mps6_client_level_taught AS pref_client_level_taught, mps6_status AS pref_status
                                              FROM match_pref_schools6
                                              UNION ALL
                                              SELECT 'match_pref_schools7' AS tab, mps7_client_ec_no AS EC_NO, mps7_school_id AS pref_id, mps7_curr_school_id AS curr_id, mps7_sub1_id AS pref_sub1_id, mps7_sub2_id AS pref_sub2_id, mps7_client_level_taught AS pref_client_level_taught, mps7_status AS pref_status
                                              FROM match_pref_schools7
                                              UNION ALL
                                              SELECT 'match_pref_schools8' AS tab, mps8_client_ec_no AS EC_NO, mps8_school_id AS pref_id, mps8_curr_school_id AS curr_id, mps8_sub1_id AS pref_sub1_id, mps8_sub2_id AS pref_sub2_id, mps8_client_level_taught AS pref_client_level_taught, mps8_status AS pref_status
                                              FROM match_pref_schools8
                                              UNION ALL
                                              SELECT 'match_pref_schools9' AS tab, mps9_client_ec_no AS EC_NO, mps9_school_id AS pref_id, mps9_curr_school_id AS curr_id, mps9_sub1_id AS pref_sub1_id, mps9_sub2_id AS pref_sub2_id, mps9_client_level_taught AS pref_client_level_taught, mps9_status AS pref_status
                                              FROM match_pref_schools9
                                              UNION ALL
                                              SELECT 'match_pref_schools10' AS tab, mps10_client_ec_no AS EC_NO, mps10_school_id AS pref_id, mps10_curr_school_id AS curr_id, mps10_sub1_id AS pref_sub1_id, mps10_sub2_id AS pref_sub2_id, mps10_client_level_taught AS pref_client_level_taught, mps10_status AS pref_status
                                              FROM match_pref_schools10
                                             
                                              ) AS co_prefs
                                          /* the first condition to be met is that the required schools should be available 
                                          */  
                                          ON mcs.mcs_school_id = co_prefs.pref_id
                                          /* the second condition to be met is that the level taught has to be the same for both clients 
                                          */ 
                                          AND  mcs.mcs_client_level_taught = co_prefs.pref_client_level_taught
                                          /* the third condition to be met is that the (for high school clients) at least one subject taught should match 
                                          */ 
                                          AND ((mcs.mcs_sub1_id = co_prefs.pref_sub1_id) 
                                          OR (mcs.mcs_sub1_id = co_prefs.pref_sub2_id)
                                          OR (mcs.mcs_sub2_id = co_prefs.pref_sub1_id)
                                          OR (mcs.mcs_sub2_id = co_prefs.pref_sub2_id))
                                          /* the fourth condition to be met is that both clients' records should be have an active status of Active ('A') 
                                          */
                                          AND co_prefs.pref_status = 'A' 
                                          AND mcs.mcs_status = 'A'
                                          /* the fifth condition to be met is for mutual school preferences to be satisfied 
                                          */
                                          AND (
                                                (mcs.mcs_pref_school_id = co_prefs.curr_id) OR 
                                                (mcs.mcs_pref_school2_id = co_prefs.curr_id) OR
                                                (mcs.mcs_pref_school3_id = co_prefs.curr_id) OR
                                                (mcs.mcs_pref_school4_id = co_prefs.curr_id) OR
                                                (mcs.mcs_pref_school5_id = co_prefs.curr_id) OR 
                                                (mcs.mcs_pref_school6_id = co_prefs.curr_id) OR 
                                                (mcs.mcs_pref_school7_id = co_prefs.curr_id) OR 
                                                (mcs.mcs_pref_school8_id = co_prefs.curr_id) OR 
                                                (mcs.mcs_pref_school9_id = co_prefs.curr_id) OR 
                                                (mcs.mcs_pref_school10_id = co_prefs.curr_id) 
                                              )
                                      WHERE mcs.mcs_id IN (
                                          SELECT MIN(mcs.mcs_id) 
                                          FROM match_current_schools AS mcs
                                          GROUP BY mcs.mcs_school_id)
                                          ORDER BY mcs.mcs_id");
	}catch (Exception $e){
			echo 'Failed to retrieve matched preferred schools';
			exit;

	}
  
	$matched_schools = $results_pref_schools->fetchAll(PDO::FETCH_ASSOC);
 /*  
 try{
   $results_pref_school2 = $db->query("SELECT mps2.mps2_client_ec_no, mcs.mcs_client_ec_no, mcs_school_id, co_prefs2.tab, co_prefs2.pref_id 
                                          FROM match_current_schools AS mcs  
                                          INNER JOIN match_pref_schools2 AS mps2 
                                            ON mps2.mps2_school_id = mcs.mcs_school_id
                                            AND mps2.mps2_client_level_taught = mcs.mcs_client_level_taught
                                            AND ((mps2.mps2_sub1_id = mcs.mcs_sub1_id) 
                                            OR (mps2.mps2_sub1_id = mcs.mcs_sub2_id)
                                            OR (mps2.mps2_sub2_id = mcs.mcs_sub1_id)
                                            OR (mps2.mps2_sub2_id = mcs.mcs_sub2_id))
                                            AND mps2.mps2_status = 'A' 
                                            AND mcs.mcs_status = 'A'
                                      INNER JOIN
                                            ( SELECT 'match_pref_schools' AS tab, mps_client_ec_no AS EC_NO, mps_school_id AS pref_id
                                              FROM match_pref_schools
                                              UNION ALL
                                              SELECT 'match_pref_schools2', mps2_client_ec_no AS EC_NO, mps2_school_id
                                              FROM match_pref_schools2
                                              UNION ALL
                                              SELECT 'match_pref_schools3', mps3_client_ec_no AS EC_NO, mps3_school_id
                                              FROM match_pref_schools3
                                              UNION ALL
                                              SELECT 'match_pref_schools4', mps4_client_ec_no AS EC_NO, mps4_school_id
                                              FROM match_pref_schools4
                                              UNION ALL
                                              SELECT 'match_pref_schools5', mps5_client_ec_no AS EC_NO, mps5_school_id
                                              FROM match_pref_schools5
                                              UNION ALL
                                              SELECT 'match_pref_schools6', mps6_client_ec_no AS EC_NO, mps6_school_id
                                              FROM match_pref_schools6
                                              UNION ALL
                                              SELECT 'match_pref_schools7', mps7_client_ec_no AS EC_NO, mps7_school_id
                                              FROM match_pref_schools7
                                              UNION ALL
                                              SELECT 'match_pref_schools8', mps8_client_ec_no AS EC_NO, mps8_school_id
                                              FROM match_pref_schools8
                                              UNION ALL
                                              SELECT 'match_pref_schools9', mps9_client_ec_no AS EC_NO, mps9_school_id
                                              FROM match_pref_schools9
                                              UNION ALL
                                              SELECT 'match_pref_schools10', mps10_client_ec_no AS EC_NO, mps10_school_id
                                              FROM match_pref_schools10
                                              UNION ALL
                                              SELECT 'match_pref_locations', mpl_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations, schools
                                              WHERE mpl_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations2', mpl2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations2, schools
                                              WHERE mpl2_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations3', mpl3_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations3, schools
                                              WHERE mpl3_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_towns', mpt_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_towns, schools
                                              WHERE mpt_town_id = school_town_id
                                              UNION ALL
                                              SELECT 'match_pref_districts', mpd_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts, schools
                                              WHERE mpd_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_districts2', mpd2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts2, schools
                                              WHERE mpd2_distr_id =school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_provinces', mpp_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_provinces, schools
                                              WHERE mpp_province_id = school_province_id
                                              ) AS co_prefs2
                                              ON mcs.mcs_client_ec_no = co_prefs2.EC_NO
                                              AND mps2.mps2_curr_school_id = co_prefs2.pref_id
                                          WHERE mcs.mcs_id IN (SELECT MIN(mcs.mcs_id) 
                                            FROM match_current_schools AS mcs 
                                            GROUP BY mcs.mcs_school_id)
                                          ORDER BY mcs.mcs_id");

	}catch (Exception $e){
			echo 'Failed to retrieve matched preferred school2';
			exit;

	}
	$matched_schools2 = $results_pref_school2->fetchAll(PDO::FETCH_ASSOC); 

 try{$results_pref_school3 = $db->query("SELECT mps3.mps3_client_ec_no, mcs.mcs_client_ec_no, mcs_school_id, co_prefs3.tab, co_prefs3.pref_id 
                                          FROM match_current_schools AS mcs  
                                          INNER JOIN match_pref_schools3 AS mps3 
                                            ON mps3.mps3_school_id = mcs.mcs_school_id
                                            AND mps3.mps3_client_level_taught = mcs.mcs_client_level_taught
                                            AND ((mps3.mps3_sub1_id = mcs.mcs_sub1_id) 
                                            OR (mps3.mps3_sub1_id = mcs.mcs_sub2_id)
                                            OR (mps3.mps3_sub2_id = mcs.mcs_sub1_id)
                                            OR (mps3.mps3_sub2_id = mcs.mcs_sub2_id))
                                            AND mps3.mps3_status = 'A' 
                                            AND mcs.mcs_status = 'A'
                                      INNER JOIN
                                            ( SELECT 'match_pref_schools' AS tab, mps_client_ec_no AS EC_NO, mps_school_id AS pref_id
                                              FROM match_pref_schools
                                              UNION ALL
                                              SELECT 'match_pref_schools2', mps2_client_ec_no AS EC_NO, mps2_school_id
                                              FROM match_pref_schools2
                                              UNION ALL
                                              SELECT 'match_pref_schools3', mps3_client_ec_no AS EC_NO, mps3_school_id
                                              FROM match_pref_schools3
                                              UNION ALL
                                              SELECT 'match_pref_schools4', mps4_client_ec_no AS EC_NO, mps4_school_id
                                              FROM match_pref_schools4
                                              UNION ALL
                                              SELECT 'match_pref_schools5', mps5_client_ec_no AS EC_NO, mps5_school_id
                                              FROM match_pref_schools5
                                              UNION ALL
                                              SELECT 'match_pref_schools6', mps6_client_ec_no AS EC_NO, mps6_school_id
                                              FROM match_pref_schools6
                                              UNION ALL
                                              SELECT 'match_pref_schools7', mps7_client_ec_no AS EC_NO, mps7_school_id
                                              FROM match_pref_schools7
                                              UNION ALL
                                              SELECT 'match_pref_schools8', mps8_client_ec_no AS EC_NO, mps8_school_id
                                              FROM match_pref_schools8
                                              UNION ALL
                                              SELECT 'match_pref_schools9', mps9_client_ec_no AS EC_NO, mps9_school_id
                                              FROM match_pref_schools9
                                              UNION ALL
                                              SELECT 'match_pref_schools10', mps10_client_ec_no AS EC_NO, mps10_school_id
                                              FROM match_pref_schools10
                                              UNION ALL
                                              SELECT 'match_pref_locations', mpl_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations, schools
                                              WHERE mpl_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations2', mpl2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations2, schools
                                              WHERE mpl2_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations3', mpl3_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations3, schools
                                              WHERE mpl3_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_towns', mpt_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_towns, schools
                                              WHERE mpt_town_id = school_town_id
                                              UNION ALL
                                              SELECT 'match_pref_districts', mpd_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts, schools
                                              WHERE mpd_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_districts2', mpd2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts2, schools
                                              WHERE mpd2_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_provinces', mpp_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_provinces, schools
                                              WHERE mpp_province_id = school_province_id
                                              ) AS co_prefs3
                                              ON mcs.mcs_client_ec_no = co_prefs3.EC_NO
                                              AND mps3.mps3_curr_school_id = co_prefs3.pref_id
                                          WHERE mcs.mcs_id IN (SELECT MIN(mcs.mcs_id) 
                                            FROM match_current_schools AS mcs 
                                            GROUP BY mcs.mcs_school_id)
                                          ORDER BY mcs.mcs_id");

	}catch (Exception $e){
			echo 'Failed to retrieve matched preferred school3';
			exit;

	}
	$matched_schools3 = $results_pref_school3->fetchAll(PDO::FETCH_ASSOC);  
  
  
  try{
    $results_pref_school4 = $db->query("SELECT mps4.mps4_client_ec_no, mcs.mcs_client_ec_no, mcs_school_id, co_prefs4.tab, co_prefs4.pref_id 
                                          FROM match_current_schools AS mcs  
                                          INNER JOIN match_pref_schools4 AS mps4 
                                            ON mps4.mps4_school_id = mcs.mcs_school_id
                                            AND mps4.mps4_client_level_taught = mcs.mcs_client_level_taught
                                            AND ((mps4.mps4_sub1_id = mcs.mcs_sub1_id) 
                                            OR (mps4.mps4_sub1_id = mcs.mcs_sub2_id)
                                            OR (mps4.mps4_sub2_id = mcs.mcs_sub1_id)
                                            OR (mps4.mps4_sub2_id = mcs.mcs_sub2_id))
                                            AND mps4.mps4_status = 'A' 
                                            AND mcs.mcs_status = 'A'
                                      INNER JOIN
                                            ( SELECT 'match_pref_schools' AS tab, mps_client_ec_no AS EC_NO, mps_school_id AS pref_id
                                              FROM match_pref_schools
                                              UNION ALL
                                              SELECT 'match_pref_schools2', mps2_client_ec_no AS EC_NO, mps2_school_id
                                              FROM match_pref_schools2
                                              UNION ALL
                                              SELECT 'match_pref_schools3', mps3_client_ec_no AS EC_NO, mps3_school_id
                                              FROM match_pref_schools3
                                              UNION ALL
                                              SELECT 'match_pref_schools4', mps4_client_ec_no AS EC_NO, mps4_school_id
                                              FROM match_pref_schools4
                                              UNION ALL
                                              SELECT 'match_pref_schools5', mps5_client_ec_no AS EC_NO, mps5_school_id
                                              FROM match_pref_schools5
                                              UNION ALL
                                              SELECT 'match_pref_schools6', mps6_client_ec_no AS EC_NO, mps6_school_id
                                              FROM match_pref_schools6
                                              UNION ALL
                                              SELECT 'match_pref_schools7', mps7_client_ec_no AS EC_NO, mps7_school_id
                                              FROM match_pref_schools7
                                              UNION ALL
                                              SELECT 'match_pref_schools8', mps8_client_ec_no AS EC_NO, mps8_school_id
                                              FROM match_pref_schools8
                                              UNION ALL
                                              SELECT 'match_pref_schools9', mps9_client_ec_no AS EC_NO, mps9_school_id
                                              FROM match_pref_schools9
                                              UNION ALL
                                              SELECT 'match_pref_schools10', mps10_client_ec_no AS EC_NO, mps10_school_id
                                              FROM match_pref_schools10
                                              UNION ALL
                                              SELECT 'match_pref_locations', mpl_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations, schools
                                              WHERE mpl_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations2', mpl2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations2, schools
                                              WHERE mpl2_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations3', mpl3_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations3, schools
                                              WHERE mpl3_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_towns', mpt_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_towns, schools
                                              WHERE mpt_town_id = school_town_id
                                              UNION ALL
                                              SELECT 'match_pref_districts', mpd_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts, schools
                                              WHERE mpd_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_districts2', mpd2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts2, schools
                                              WHERE mpd2_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_provinces', mpp_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_provinces, schools
                                              WHERE mpp_province_id = school_province_id
                                              ) AS co_prefs4
                                              ON mcs.mcs_client_ec_no = co_prefs4.EC_NO
                                              AND mps4.mps4_curr_school_id = co_prefs4.pref_id
                                          WHERE mcs.mcs_id IN (SELECT MIN(mcs.mcs_id) 
                                            FROM match_current_schools AS mcs 
                                            GROUP BY mcs.mcs_school_id)
                                          ORDER BY mcs.mcs_id");

	}catch (Exception $e){
			echo 'Failed to retrieve matched preferred school4';
			exit;

	}
	$matched_schools4 = $results_pref_school4->fetchAll(PDO::FETCH_ASSOC);  
 
 
 try{$results_pref_school5 = $db->query("SELECT mps5.mps5_client_ec_no, mcs.mcs_client_ec_no, mcs_school_id, co_prefs5.tab, co_prefs5.pref_id 
                                          FROM match_current_schools AS mcs  
                                          INNER JOIN match_pref_schools5 AS mps5 
                                            ON mps5.mps5_school_id = mcs.mcs_school_id
                                            AND mps5.mps5_client_level_taught = mcs.mcs_client_level_taught
                                            AND ((mps5.mps5_sub1_id = mcs.mcs_sub1_id) 
                                            OR (mps5.mps5_sub1_id = mcs.mcs_sub2_id)
                                            OR (mps5.mps5_sub2_id = mcs.mcs_sub1_id)
                                            OR (mps5.mps5_sub2_id = mcs.mcs_sub2_id))
                                            AND mps5.mps5_status = 'A' 
                                            AND mcs.mcs_status = 'A'
                                      INNER JOIN
                                            ( SELECT 'match_pref_schools' AS tab, mps_client_ec_no AS EC_NO, mps_school_id AS pref_id
                                              FROM match_pref_schools
                                              UNION ALL
                                              SELECT 'match_pref_schools2', mps2_client_ec_no AS EC_NO, mps2_school_id
                                              FROM match_pref_schools2
                                              UNION ALL
                                              SELECT 'match_pref_schools3', mps3_client_ec_no AS EC_NO, mps3_school_id
                                              FROM match_pref_schools3
                                              UNION ALL
                                              SELECT 'match_pref_schools4', mps4_client_ec_no AS EC_NO, mps4_school_id
                                              FROM match_pref_schools4
                                              UNION ALL
                                              SELECT 'match_pref_schools5', mps5_client_ec_no AS EC_NO, mps5_school_id
                                              FROM match_pref_schools5
                                              UNION ALL
                                              SELECT 'match_pref_schools6', mps6_client_ec_no AS EC_NO, mps6_school_id
                                              FROM match_pref_schools6
                                              UNION ALL
                                              SELECT 'match_pref_schools7', mps7_client_ec_no AS EC_NO, mps7_school_id
                                              FROM match_pref_schools7
                                              UNION ALL
                                              SELECT 'match_pref_schools8', mps8_client_ec_no AS EC_NO, mps8_school_id
                                              FROM match_pref_schools8
                                              UNION ALL
                                              SELECT 'match_pref_schools9', mps9_client_ec_no AS EC_NO, mps9_school_id
                                              FROM match_pref_schools9
                                              UNION ALL
                                              SELECT 'match_pref_schools10', mps10_client_ec_no AS EC_NO, mps10_school_id
                                              FROM match_pref_schools10
                                              UNION ALL
                                              SELECT 'match_pref_locations', mpl_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations, schools
                                              WHERE mpl_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations2', mpl2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations2, schools
                                              WHERE mpl2_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations3', mpl3_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations3, schools
                                              WHERE mpl3_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_towns', mpt_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_towns, schools
                                              WHERE mpt_town_id = school_town_id
                                              UNION ALL
                                              SELECT 'match_pref_districts', mpd_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts, schools
                                              WHERE mpd_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_districts2', mpd2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts2, schools
                                              WHERE mpd2_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_provinces', mpp_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_provinces, schools
                                              WHERE mpp_province_id = school_province_id
                                              ) AS co_prefs5
                                              ON mcs.mcs_client_ec_no = co_prefs5.EC_NO
                                              AND mps5.mps5_curr_school_id = co_prefs5.pref_id
                                          WHERE mcs.mcs_id IN (SELECT MIN(mcs.mcs_id) 
                                            FROM match_current_schools AS mcs 
                                            GROUP BY mcs.mcs_school_id)
                                          ORDER BY mcs.mcs_id");

	}catch (Exception $e){
			echo 'Failed to retrieve matched preferred school5';
			exit;

	}
	$matched_schools5 = $results_pref_school5->fetchAll(PDO::FETCH_ASSOC); 
 
 
 try{$results_pref_school6 = $db->query("SELECT mps6.mps6_client_ec_no, mcs.mcs_client_ec_no, mcs_school_id, co_prefs6.tab, co_prefs6.pref_id  
                                          FROM match_current_schools AS mcs  
                                          INNER JOIN match_pref_schools6 AS mps6 
                                            ON mps6.mps6_school_id = mcs.mcs_school_id
                                            AND mps6.mps6_client_level_taught = mcs.mcs_client_level_taught
                                            AND ((mps6.mps6_sub1_id = mcs.mcs_sub1_id) 
                                            OR (mps6.mps6_sub1_id = mcs.mcs_sub2_id)
                                            OR (mps6.mps6_sub2_id = mcs.mcs_sub1_id)
                                            OR (mps6.mps6_sub2_id = mcs.mcs_sub2_id))
                                            AND mps6.mps6_status = 'A'
                                            AND mcs.mcs_status = 'A'
                                      INNER JOIN
                                            ( SELECT 'match_pref_schools' AS tab, mps_client_ec_no AS EC_NO, mps_school_id AS pref_id
                                              FROM match_pref_schools
                                              UNION ALL
                                              SELECT 'match_pref_schools2', mps2_client_ec_no AS EC_NO, mps2_school_id
                                              FROM match_pref_schools2
                                              UNION ALL
                                              SELECT 'match_pref_schools3', mps3_client_ec_no AS EC_NO, mps3_school_id
                                              FROM match_pref_schools3
                                              UNION ALL
                                              SELECT 'match_pref_schools4', mps4_client_ec_no AS EC_NO, mps4_school_id
                                              FROM match_pref_schools4
                                              UNION ALL
                                              SELECT 'match_pref_schools5', mps5_client_ec_no AS EC_NO, mps5_school_id
                                              FROM match_pref_schools5
                                              UNION ALL
                                              SELECT 'match_pref_schools6', mps6_client_ec_no AS EC_NO, mps6_school_id
                                              FROM match_pref_schools6
                                              UNION ALL
                                              SELECT 'match_pref_schools7', mps7_client_ec_no AS EC_NO, mps7_school_id
                                              FROM match_pref_schools7
                                              UNION ALL
                                              SELECT 'match_pref_schools8', mps8_client_ec_no AS EC_NO, mps8_school_id
                                              FROM match_pref_schools8
                                              UNION ALL
                                              SELECT 'match_pref_schools9', mps9_client_ec_no AS EC_NO, mps9_school_id
                                              FROM match_pref_schools9
                                              UNION ALL
                                              SELECT 'match_pref_schools10', mps10_client_ec_no AS EC_NO, mps10_school_id
                                              FROM match_pref_schools10
                                              UNION ALL
                                              SELECT 'match_pref_locations', mpl_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations, schools
                                              WHERE mpl_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations2', mpl2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations2, schools
                                              WHERE mpl2_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations3', mpl3_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations3, schools
                                              WHERE mpl3_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_towns', mpt_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_towns, schools
                                              WHERE mpt_town_id = school_town_id
                                              UNION ALL
                                              SELECT 'match_pref_districts', mpd_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts, schools
                                              WHERE mpd_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_districts2', mpd2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts2, schools
                                              WHERE mpd2_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_provinces', mpp_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_provinces, schools
                                              WHERE mpp_province_id = school_province_id
                                              ) AS co_prefs6
                                              ON mcs.mcs_client_ec_no = co_prefs6.EC_NO
                                              AND mps6.mps6_curr_school_id = co_prefs6.pref_id
                                          WHERE mcs.mcs_id IN (SELECT MIN(mcs.mcs_id) 
                                            FROM match_current_schools AS mcs 
                                            GROUP BY mcs.mcs_school_id)
                                          ORDER BY mcs.mcs_id");

	}catch (Exception $e){
			echo 'Failed to retrieve matched preferred school6';
			exit;

	}
	$matched_schools6 = $results_pref_school6->fetchAll(PDO::FETCH_ASSOC); 
 
 
 try{$results_pref_school7 = $db->query("SELECT mps7.mps7_client_ec_no, mcs.mcs_client_ec_no, mcs_school_id, co_prefs7.tab, co_prefs7.pref_id 
                                          FROM match_current_schools AS mcs  
                                          INNER JOIN match_pref_schools7 AS mps7 
                                            ON mps7.mps7_school_id = mcs.mcs_school_id
                                            AND mps7.mps7_client_level_taught = mcs.mcs_client_level_taught
                                            AND ((mps7.mps7_sub1_id = mcs.mcs_sub1_id) 
                                            OR (mps7.mps7_sub1_id = mcs.mcs_sub2_id)
                                            OR (mps7.mps7_sub2_id = mcs.mcs_sub1_id)
                                            OR (mps7.mps7_sub2_id = mcs.mcs_sub2_id))
                                            AND mps7.mps7_status = 'A' 
                                            AND mcs.mcs_status = 'A'
                                      INNER JOIN
                                            ( SELECT 'match_pref_schools' AS tab, mps_client_ec_no AS EC_NO, mps_school_id AS pref_id
                                              FROM match_pref_schools
                                              UNION ALL
                                              SELECT 'match_pref_schools2', mps2_client_ec_no AS EC_NO, mps2_school_id
                                              FROM match_pref_schools2
                                              UNION ALL
                                              SELECT 'match_pref_schools3', mps3_client_ec_no AS EC_NO, mps3_school_id
                                              FROM match_pref_schools3
                                              UNION ALL
                                              SELECT 'match_pref_schools4', mps4_client_ec_no AS EC_NO, mps4_school_id
                                              FROM match_pref_schools4
                                              UNION ALL
                                              SELECT 'match_pref_schools5', mps5_client_ec_no AS EC_NO, mps5_school_id
                                              FROM match_pref_schools5
                                              UNION ALL
                                              SELECT 'match_pref_schools6', mps6_client_ec_no AS EC_NO, mps6_school_id
                                              FROM match_pref_schools6
                                              UNION ALL
                                              SELECT 'match_pref_schools7', mps7_client_ec_no AS EC_NO, mps7_school_id
                                              FROM match_pref_schools7
                                              UNION ALL
                                              SELECT 'match_pref_schools8', mps8_client_ec_no AS EC_NO, mps8_school_id
                                              FROM match_pref_schools8
                                              UNION ALL
                                              SELECT 'match_pref_schools9', mps9_client_ec_no AS EC_NO, mps9_school_id
                                              FROM match_pref_schools9
                                              UNION ALL
                                              SELECT 'match_pref_schools10', mps10_client_ec_no AS EC_NO, mps10_school_id
                                              FROM match_pref_schools10
                                              UNION ALL
                                              SELECT 'match_pref_locations', mpl_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations, schools
                                              WHERE mpl_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations2', mpl2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations2, schools
                                              WHERE mpl2_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations3', mpl3_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations3, schools
                                              WHERE mpl3_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_towns', mpt_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_towns, schools
                                              WHERE mpt_town_id = school_town_id
                                              UNION ALL
                                              SELECT 'match_pref_districts', mpd_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts, schools
                                              WHERE mpd_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_districts2', mpd2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts2, schools
                                              WHERE mpd2_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_provinces', mpp_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_provinces, schools
                                              WHERE mpp_province_id = school_province_id
                                              ) AS co_prefs7
                                              ON mcs.mcs_client_ec_no = co_prefs7.EC_NO
                                              AND mps7.mps7_curr_school_id = co_prefs7.pref_id
                                          WHERE mcs.mcs_id IN (SELECT MIN(mcs.mcs_id) 
                                            FROM match_current_schools AS mcs 
                                            GROUP BY mcs.mcs_school_id)
                                          ORDER BY mcs.mcs_id");

	}catch (Exception $e){
			echo 'Failed to retrieve matched preferred school7';
			exit;

	}
	$matched_schools7 = $results_pref_school7->fetchAll(PDO::FETCH_ASSOC); 
 
 
 try{$results_pref_school8 = $db->query("SELECT mps8.mps8_client_ec_no, mcs.mcs_client_ec_no, mcs_school_id, co_prefs8.tab, co_prefs8.pref_id  
                                          FROM match_current_schools AS mcs  
                                          INNER JOIN match_pref_schools8 AS mps8 
                                            ON mps8.mps8_school_id = mcs.mcs_school_id
                                            AND mps8.mps8_client_level_taught = mcs.mcs_client_level_taught
                                            AND ((mps8.mps8_sub1_id = mcs.mcs_sub1_id) 
                                            OR (mps8.mps8_sub1_id = mcs.mcs_sub2_id)
                                            OR (mps8.mps8_sub2_id = mcs.mcs_sub1_id)
                                            OR (mps8.mps8_sub2_id = mcs.mcs_sub2_id))
                                            AND mps8.mps8_status = 'A'
                                            AND mcs.mcs_status = 'A'
                                      INNER JOIN
                                            ( SELECT 'match_pref_schools' AS tab, mps_client_ec_no AS EC_NO, mps_school_id AS pref_id
                                              FROM match_pref_schools
                                              UNION ALL
                                              SELECT 'match_pref_schools2', mps2_client_ec_no AS EC_NO, mps2_school_id
                                              FROM match_pref_schools2
                                              UNION ALL
                                              SELECT 'match_pref_schools3', mps3_client_ec_no AS EC_NO, mps3_school_id
                                              FROM match_pref_schools3
                                              UNION ALL
                                              SELECT 'match_pref_schools4', mps4_client_ec_no AS EC_NO, mps4_school_id
                                              FROM match_pref_schools4
                                              UNION ALL
                                              SELECT 'match_pref_schools5', mps5_client_ec_no AS EC_NO, mps5_school_id
                                              FROM match_pref_schools5
                                              UNION ALL
                                              SELECT 'match_pref_schools6', mps6_client_ec_no AS EC_NO, mps6_school_id
                                              FROM match_pref_schools6
                                              UNION ALL
                                              SELECT 'match_pref_schools7', mps7_client_ec_no AS EC_NO, mps7_school_id
                                              FROM match_pref_schools7
                                              UNION ALL
                                              SELECT 'match_pref_schools8', mps8_client_ec_no AS EC_NO, mps8_school_id
                                              FROM match_pref_schools8
                                              UNION ALL
                                              SELECT 'match_pref_schools9', mps9_client_ec_no AS EC_NO, mps9_school_id
                                              FROM match_pref_schools9
                                              UNION ALL
                                              SELECT 'match_pref_schools10', mps10_client_ec_no AS EC_NO, mps10_school_id
                                              FROM match_pref_schools10
                                              UNION ALL
                                              SELECT 'match_pref_locations', mpl_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations, schools
                                              WHERE mpl_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations2', mpl2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations2, schools
                                              WHERE mpl2_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations3', mpl3_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations3, schools
                                              WHERE mpl3_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_towns', mpt_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_towns, schools
                                              WHERE mpt_town_id = school_town_id
                                              UNION ALL
                                              SELECT 'match_pref_districts', mpd_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts, schools
                                              WHERE mpd_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_districts2', mpd2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts2, schools
                                              WHERE mpd2_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_provinces', mpp_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_provinces, schools
                                              WHERE mpp_province_id = school_province_id
                                              ) AS co_prefs8
                                              ON mcs.mcs_client_ec_no = co_prefs8.EC_NO
                                              AND mps8.mps8_curr_school_id = co_prefs8.pref_id
                                          WHERE mcs.mcs_id IN (SELECT MIN(mcs.mcs_id) 
                                            FROM match_current_schools AS mcs 
                                            GROUP BY mcs.mcs_school_id)
                                          ORDER BY mcs.mcs_id");

	}catch (Exception $e){
			echo 'Failed to retrieve matched preferred school8';
			exit;

	}
	$matched_schools8 = $results_pref_school8->fetchAll(PDO::FETCH_ASSOC); 
 
 
 try{$results_pref_school9 = $db->query("SELECT mps9.mps9_client_ec_no, mcs.mcs_client_ec_no, mcs_school_id, co_prefs9.tab, co_prefs9.pref_id  
                                          FROM match_current_schools AS mcs  
                                          INNER JOIN match_pref_schools9 AS mps9 
                                            ON mps9.mps9_school_id = mcs.mcs_school_id
                                            AND mps9.mps9_client_level_taught = mcs.mcs_client_level_taught
                                            AND ((mps9.mps9_sub1_id = mcs.mcs_sub1_id) 
                                            OR (mps9.mps9_sub1_id = mcs.mcs_sub2_id)
                                            OR (mps9.mps9_sub2_id = mcs.mcs_sub1_id)
                                            OR (mps9.mps9_sub2_id = mcs.mcs_sub2_id))
                                            AND mps9.mps9_status = 'A' 
                                            AND mcs.mcs_status = 'A'
                                      INNER JOIN
                                            ( SELECT 'match_pref_schools' AS tab, mps_client_ec_no AS EC_NO, mps_school_id AS pref_id
                                              FROM match_pref_schools
                                              UNION ALL
                                              SELECT 'match_pref_schools2', mps2_client_ec_no AS EC_NO, mps2_school_id
                                              FROM match_pref_schools2
                                              UNION ALL
                                              SELECT 'match_pref_schools3', mps3_client_ec_no AS EC_NO, mps3_school_id
                                              FROM match_pref_schools3
                                              UNION ALL
                                              SELECT 'match_pref_schools4', mps4_client_ec_no AS EC_NO, mps4_school_id
                                              FROM match_pref_schools4
                                              UNION ALL
                                              SELECT 'match_pref_schools5', mps5_client_ec_no AS EC_NO, mps5_school_id
                                              FROM match_pref_schools5
                                              UNION ALL
                                              SELECT 'match_pref_schools6', mps6_client_ec_no AS EC_NO, mps6_school_id
                                              FROM match_pref_schools6
                                              UNION ALL
                                              SELECT 'match_pref_schools7', mps7_client_ec_no AS EC_NO, mps7_school_id
                                              FROM match_pref_schools7
                                              UNION ALL
                                              SELECT 'match_pref_schools8', mps8_client_ec_no AS EC_NO, mps8_school_id
                                              FROM match_pref_schools8
                                              UNION ALL
                                              SELECT 'match_pref_schools9', mps9_client_ec_no AS EC_NO, mps9_school_id
                                              FROM match_pref_schools9
                                              UNION ALL
                                              SELECT 'match_pref_schools10', mps10_client_ec_no AS EC_NO, mps10_school_id
                                              FROM match_pref_schools10
                                              UNION ALL
                                              SELECT 'match_pref_locations', mpl_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations, schools
                                              WHERE mpl_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations2', mpl2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations2, schools
                                              WHERE mpl2_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations3', mpl3_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations3, schools
                                              WHERE mpl3_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_towns', mpt_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_towns, schools
                                              WHERE mpt_town_id = school_town_id
                                              UNION ALL
                                              SELECT 'match_pref_districts', mpd_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts, schools
                                              WHERE mpd_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_districts2', mpd2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts2, schools
                                              WHERE mpd2_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_provinces', mpp_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_provinces, schools
                                              WHERE mpp_province_id = school_province_id
                                              ) AS co_prefs9
                                              ON mcs.mcs_client_ec_no = co_prefs9.EC_NO
                                              AND mps9.mps9_curr_school_id = co_prefs9.pref_id
                                          WHERE mcs.mcs_id IN (SELECT MIN(mcs.mcs_id) 
                                            FROM match_current_schools AS mcs 
                                            GROUP BY mcs.mcs_school_id)
                                          ORDER BY mcs.mcs_id");

	}catch (Exception $e){
			echo 'Failed to retrieve matched preferred school9';
			exit;

	}
	$matched_schools9 = $results_pref_school9->fetchAll(PDO::FETCH_ASSOC);  
  
  try{$results_pref_school10 = $db->query("SELECT mps10.mps10_client_ec_no, mcs.mcs_client_ec_no, mcs_school_id, co_prefs10.tab, co_prefs10.pref_id 
                                          FROM match_current_schools AS mcs  
                                          INNER JOIN match_pref_schools10 AS mps10 
                                            ON mps10.mps10_school_id = mcs.mcs_school_id
                                            AND mps10.mps10_client_level_taught = mcs.mcs_client_level_taught
                                            AND ((mps10.mps10_sub1_id = mcs.mcs_sub1_id) 
                                            OR (mps10.mps10_sub1_id = mcs.mcs_sub2_id)
                                            OR (mps10.mps10_sub2_id = mcs.mcs_sub1_id)
                                            OR (mps10.mps10_sub2_id = mcs.mcs_sub2_id))
                                            AND mps10.mps10_status = 'A' 
                                            AND mcs.mcs_status = 'A'
                                      INNER JOIN
                                            ( SELECT 'match_pref_schools' AS tab, mps_client_ec_no AS EC_NO, mps_school_id AS pref_id
                                              FROM match_pref_schools
                                              UNION ALL
                                              SELECT 'match_pref_schools2', mps2_client_ec_no AS EC_NO, mps2_school_id
                                              FROM match_pref_schools2
                                              UNION ALL
                                              SELECT 'match_pref_schools3', mps3_client_ec_no AS EC_NO, mps3_school_id
                                              FROM match_pref_schools3
                                              UNION ALL
                                              SELECT 'match_pref_schools4', mps4_client_ec_no AS EC_NO, mps4_school_id
                                              FROM match_pref_schools4
                                              UNION ALL
                                              SELECT 'match_pref_schools5', mps5_client_ec_no AS EC_NO, mps5_school_id
                                              FROM match_pref_schools5
                                              UNION ALL
                                              SELECT 'match_pref_schools6', mps6_client_ec_no AS EC_NO, mps6_school_id
                                              FROM match_pref_schools6
                                              UNION ALL
                                              SELECT 'match_pref_schools7', mps7_client_ec_no AS EC_NO, mps7_school_id
                                              FROM match_pref_schools7
                                              UNION ALL
                                              SELECT 'match_pref_schools8', mps8_client_ec_no AS EC_NO, mps8_school_id
                                              FROM match_pref_schools8
                                              UNION ALL
                                              SELECT 'match_pref_schools9', mps9_client_ec_no AS EC_NO, mps9_school_id
                                              FROM match_pref_schools9
                                              UNION ALL
                                              SELECT 'match_pref_schools10', mps10_client_ec_no AS EC_NO, mps10_school_id
                                              FROM match_pref_schools10
                                              UNION ALL
                                              SELECT 'match_pref_locations', mpl_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations, schools
                                              WHERE mpl_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations2', mpl2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations2, schools
                                              WHERE mpl2_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_locations3', mpl3_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_locations3, schools
                                              WHERE mpl3_loc_id = school_loc_id
                                              UNION ALL
                                              SELECT 'match_pref_towns', mpt_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_towns, schools
                                              WHERE mpt_town_id = school_town_id
                                              UNION ALL
                                              SELECT 'match_pref_districts', mpd_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts, schools
                                              WHERE mpd_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_districts2', mpd2_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_districts2, schools
                                              WHERE mpd2_distr_id = school_distr_id
                                              UNION ALL
                                              SELECT 'match_pref_provinces', mpp_client_ec_no AS EC_NO, schools.school_id
                                              FROM match_pref_provinces, schools
                                              WHERE mpp_province_id = school_province_id
                                              ) AS co_prefs10
                                              ON mcs.mcs_client_ec_no = co_prefs10.EC_NO
                                              AND mps10.mps10_curr_school_id = co_prefs10.pref_id
                                          WHERE mcs.mcs_id IN (SELECT MIN(mcs.mcs_id) 
                                            FROM match_current_schools AS mcs 
                                            GROUP BY mcs.mcs_school_id)
                                          ORDER BY mcs.mcs_id");

	}catch (Exception $e){
			echo 'Failed to retrieve matched preferred school10';
			exit;

	}
	$matched_schools10 = $results_pref_school10->fetchAll(PDO::FETCH_ASSOC); 
  *//*
  try{
    $results_pref_locations1 = $db->query("SELECT mpl.mpl_client_ec_no, mcs.mcs_client_ec_no, co_prefs.tab, co_prefs.loc_id 
                                          FROM match_current_schools AS mcs  
                                          INNER JOIN match_pref_locations AS mpl 
                                            ON mpl.mpl_loc_id = mcs.mcs_loc_id
                                            AND mpl.mpl_client_level_taught = mcs.mcs_client_level_taught
                                            AND ((mpl.mpl_sub1_id = mcs.mcs_sub1_id) 
                                            OR (mpl.mpl_sub1_id = mcs.mcs_sub2_id)
                                            OR (mpl.mpl_sub2_id = mcs.mcs_sub1_id)
                                            OR (mpl.mpl_sub2_id = mcs.mcs_sub2_id))
                                            AND mpl.mpl_status = 'A' 
                                            AND mcs.mcs_status = 'A'
                                      INNER JOIN
                                            ( SELECT 'match_pref_schools' AS tab, mps_client_ec_no AS EC_NO, (SELECT school_loc_id
                                                                                                              FROM schools
                                                                                                              WHERE school_id = match_pref_schools.mps_curr_school_id) AS loc_id
                                              FROM match_pref_schools
                                              UNION ALL
                                              SELECT 'match_pref_schools2', mps2_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools2.mps2_curr_school_id) AS loc_id
                                              FROM match_pref_schools2
                                              UNION ALL
                                              SELECT 'match_pref_schools3', mps3_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools3.mps3_curr_school_id) AS loc_id
                                              FROM match_pref_schools3
                                              UNION ALL
                                              SELECT 'match_pref_schools4', mps4_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools4.mps4_curr_school_id) AS loc_id
                                              FROM match_pref_schools4
                                              UNION ALL
                                              SELECT 'match_pref_schools5', mps5_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools5.mps5_curr_school_id) AS loc_id
                                              FROM match_pref_schools5
                                              UNION ALL
                                              SELECT 'match_pref_schools6', mps6_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools6.mps6_curr_school_id) AS loc_id
                                              FROM match_pref_schools6
                                              UNION ALL
                                              SELECT 'match_pref_schools7', mps7_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools7.mps7_curr_school_id) AS loc_id
                                              FROM match_pref_schools7
                                              UNION ALL
                                              SELECT 'match_pref_schools8', mps8_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools8.mps8_curr_school_id) AS loc_id
                                              FROM match_pref_schools8
                                              
                                              UNION ALL
                                              SELECT 'match_pref_schools9', mps9_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools9.mps9_curr_school_id) AS loc_id
                                              FROM match_pref_schools9
                                              
                                              UNION ALL
                                              SELECT 'match_pref_schools10', mps10_client_ec_no, (SELECT school_loc_id
                                                                                                  FROM schools
                                                                                                  WHERE school_id = match_pref_schools10.mps10_curr_school_id) AS loc_id
                                              FROM match_pref_schools10
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations' AS tab, mpl_client_ec_no AS EC_NO, (SELECT school_loc_id
                                                                                                  FROM schools
                                                                                                  WHERE school_id = match_pref_locations.mpl_curr_school_id) AS loc_id
                                              FROM match_pref_locations
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations2' AS tab, mpl2_client_ec_no AS EC_NO, (SELECT school_loc_id
                                                                                                  FROM schools
                                                                                                  WHERE school_id = match_pref_locations2.mpl2_curr_school_id) AS loc_id
                                              FROM match_pref_locations2
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations3' AS tab, mpl3_client_ec_no AS EC_NO, (SELECT school_loc_id
                                                                                                  FROM schools
                                                                                                  WHERE school_id = match_pref_locations3.mpl3_curr_school_id) AS loc_id
                                              FROM match_pref_locations3
                                              
                                              UNION ALL
                                              SELECT 'match_pref_towns', mpt_client_ec_no AS EC_NO, ( SELECT school_loc_id
                                                                                                      FROM schools
                                                                                                      WHERE school_id = match_pref_towns.mpt_curr_school_id) AS loc_id
                                              FROM match_pref_towns
                                              
                                              UNION ALL
                                              SELECT 'match_pref_districts', mpd_client_ec_no AS EC_NO, ( SELECT school_loc_id
                                                                                                          FROM schools
                                                                                                          WHERE school_id = match_pref_districts.mpd_curr_school_id) AS loc_id
                                              FROM match_pref_districts
                                              
                                              UNION ALL
                                              SELECT 'match_pref_districts2', mpd2_client_ec_no AS EC_NO, ( SELECT school_loc_id
                                                                                                            FROM schools
                                                                                                            WHERE school_id = match_pref_districts2.mpd2_curr_school_id) AS loc_id
                                              FROM match_pref_districts2
                                              
                                              UNION ALL
                                              SELECT 'match_pref_provinces', mpp_client_ec_no AS EC_NO, ( SELECT school_loc_id
                                                                                                          FROM schools
                                                                                                          WHERE school_id = match_pref_provinces.mpp_curr_school_id) AS loc_id
                                              FROM match_pref_provinces
                                              ) AS co_prefs
                                              ON mcs.mcs_client_ec_no = co_prefs.EC_NO
                                              AND co_prefs.loc_id = (SELECT school_loc_id
                                                                          FROM schools
                                                                          WHERE school_id = mpl.mpl_curr_school_id)
                                          WHERE mcs.mcs_id IN (SELECT MIN(mcs.mcs_id) 
                                            FROM match_current_schools AS mcs 
                                            GROUP BY mcs.mcs_loc_id)
                                            
                                          ORDER BY mcs.mcs_id");

	}catch (Exception $e){
			echo 'Failed to retrieve matched locations1';
			exit;

	}
	$matched_locations1 = $results_pref_locations1->fetchAll(PDO::FETCH_ASSOC); 
  
  try{$results_pref_locations2 = $db->query("SELECT mpl2.mpl2_client_ec_no, mcs.mcs_client_ec_no, co_prefs.tab, co_prefs.loc_id  
                                          FROM match_current_schools AS mcs  
                                          INNER JOIN match_pref_locations2 AS mpl2 
                                            ON mpl2.mpl2_loc_id = mcs.mcs_loc_id
                                             AND mpl2.mpl2_client_level_taught = mcs.mcs_client_level_taught
                                            AND ((mpl2.mpl2_sub1_id = mcs.mcs_sub1_id) 
                                            OR (mpl2.mpl2_sub1_id = mcs.mcs_sub2_id)
                                            OR (mpl2.mpl2_sub2_id = mcs.mcs_sub1_id)
                                            OR (mpl2.mpl2_sub2_id = mcs.mcs_sub2_id))
                                            AND mpl2.mpl2_status = 'A' 
                                            AND mcs.mcs_status = 'A'
                                      INNER JOIN
                                            ( SELECT 'match_pref_schools' AS tab, mps_client_ec_no AS EC_NO, (SELECT school_loc_id
                                                                                                              FROM schools
                                                                                                              WHERE school_id = match_pref_schools.mps_curr_school_id) AS loc_id
                                              FROM match_pref_schools
                                              UNION ALL
                                              SELECT 'match_pref_schools2', mps2_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools2.mps2_curr_school_id) AS loc_id
                                              FROM match_pref_schools2
                                              UNION ALL
                                              SELECT 'match_pref_schools3', mps3_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools3.mps3_curr_school_id) AS loc_id
                                              FROM match_pref_schools3
                                              UNION ALL
                                              SELECT 'match_pref_schools4', mps4_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools4.mps4_curr_school_id) AS loc_id
                                              FROM match_pref_schools4
                                              UNION ALL
                                              SELECT 'match_pref_schools5', mps5_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools5.mps5_curr_school_id) AS loc_id
                                              FROM match_pref_schools5
                                              UNION ALL
                                              SELECT 'match_pref_schools6', mps6_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools6.mps6_curr_school_id) AS loc_id
                                              FROM match_pref_schools6
                                              UNION ALL
                                              SELECT 'match_pref_schools7', mps7_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools7.mps7_curr_school_id) AS loc_id
                                              FROM match_pref_schools7
                                              UNION ALL
                                              SELECT 'match_pref_schools8', mps8_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools8.mps8_curr_school_id) AS loc_id
                                              FROM match_pref_schools8
                                              
                                              UNION ALL
                                              SELECT 'match_pref_schools9', mps9_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools9.mps9_curr_school_id) AS loc_id
                                              FROM match_pref_schools9
                                              
                                              UNION ALL
                                              SELECT 'match_pref_schools10', mps10_client_ec_no, (SELECT school_loc_id
                                                                                                  FROM schools
                                                                                                  WHERE school_id = match_pref_schools10.mps10_curr_school_id) AS loc_id
                                              FROM match_pref_schools10
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations' AS tab, mpl_client_ec_no AS EC_NO, (SELECT school_loc_id
                                                                                                  FROM schools
                                                                                                  WHERE school_id = match_pref_locations.mpl_curr_school_id) AS loc_id
                                              FROM match_pref_locations
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations2' AS tab, mpl2_client_ec_no AS EC_NO, (SELECT school_loc_id
                                                                                                  FROM schools
                                                                                                  WHERE school_id = match_pref_locations2.mpl2_curr_school_id) AS loc_id
                                              FROM match_pref_locations2
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations3' AS tab, mpl3_client_ec_no AS EC_NO, (SELECT school_loc_id
                                                                                                  FROM schools
                                                                                                  WHERE school_id = match_pref_locations3.mpl3_curr_school_id) AS loc_id
                                              FROM match_pref_locations3
                                              
                                              UNION ALL
                                              SELECT 'match_pref_towns', mpt_client_ec_no AS EC_NO, ( SELECT school_loc_id
                                                                                                      FROM schools
                                                                                                      WHERE school_id = match_pref_towns.mpt_curr_school_id) AS loc_id
                                              FROM match_pref_towns
                                              
                                              UNION ALL
                                              SELECT 'match_pref_districts', mpd_client_ec_no AS EC_NO, ( SELECT school_loc_id
                                                                                                          FROM schools
                                                                                                          WHERE school_id = match_pref_districts.mpd_curr_school_id) AS loc_id
                                              FROM match_pref_districts
                                              
                                              UNION ALL
                                              SELECT 'match_pref_districts2', mpd2_client_ec_no AS EC_NO, ( SELECT school_loc_id
                                                                                                            FROM schools
                                                                                                            WHERE school_id = match_pref_districts2.mpd2_curr_school_id) AS loc_id
                                              FROM match_pref_districts2
                                              
                                              UNION ALL
                                              SELECT 'match_pref_provinces', mpp_client_ec_no AS EC_NO, ( SELECT school_loc_id
                                                                                                          FROM schools
                                                                                                          WHERE school_id = match_pref_provinces.mpp_curr_school_id) AS loc_id
                                              FROM match_pref_provinces
                                              ) AS co_prefs
                                              ON mcs.mcs_client_ec_no = co_prefs.EC_NO
                                              AND co_prefs.loc_id = (SELECT school_loc_id
                                                                          FROM schools
                                                                          WHERE school_id = mpl2.mpl2_curr_school_id)
                                          WHERE mcs.mcs_id IN (SELECT MIN(mcs.mcs_id) 
                                            FROM match_current_schools AS mcs 
                                            GROUP BY mcs.mcs_loc_id)
                                            
                                          ORDER BY mcs.mcs_id");

	}catch (Exception $e){
			echo 'Failed to retrieve matched locations2';
			exit;

	}
	$matched_locations2 = $results_pref_locations2->fetchAll(PDO::FETCH_ASSOC); 
  
  try{$results_pref_locations3 = $db->query("SELECT mpl3.mpl3_client_ec_no, mcs.mcs_client_ec_no, co_prefs.tab, co_prefs.loc_id  
                                          FROM match_current_schools AS mcs  
                                          INNER JOIN match_pref_locations3 AS mpl3 
                                            ON mpl3.mpl3_loc_id = mcs.mcs_loc_id
                                            AND mpl3.mpl3_client_level_taught = mcs.mcs_client_level_taught
                                            AND ((mpl3.mpl3_sub1_id = mcs.mcs_sub1_id) 
                                            OR (mpl3.mpl3_sub1_id = mcs.mcs_sub2_id)
                                            OR (mpl3.mpl3_sub2_id = mcs.mcs_sub1_id)
                                            OR (mpl3.mpl3_sub2_id = mcs.mcs_sub2_id))
                                            AND mpl3.mpl3_status = 'A' 
                                            AND mcs.mcs_status = 'A'
                                      INNER JOIN
                                            ( SELECT 'match_pref_schools' AS tab, mps_client_ec_no AS EC_NO, (SELECT school_loc_id
                                                                                                              FROM schools
                                                                                                              WHERE school_id = match_pref_schools.mps_curr_school_id) AS loc_id
                                              FROM match_pref_schools
                                              UNION ALL
                                              SELECT 'match_pref_schools2', mps2_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools2.mps2_curr_school_id) AS loc_id
                                              FROM match_pref_schools2
                                              UNION ALL
                                              SELECT 'match_pref_schools3', mps3_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools3.mps3_curr_school_id) AS loc_id
                                              FROM match_pref_schools3
                                              UNION ALL
                                              SELECT 'match_pref_schools4', mps4_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools4.mps4_curr_school_id) AS loc_id
                                              FROM match_pref_schools4
                                              UNION ALL
                                              SELECT 'match_pref_schools5', mps5_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools5.mps5_curr_school_id) AS loc_id
                                              FROM match_pref_schools5
                                              UNION ALL
                                              SELECT 'match_pref_schools6', mps6_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools6.mps6_curr_school_id) AS loc_id
                                              FROM match_pref_schools6
                                              UNION ALL
                                              SELECT 'match_pref_schools7', mps7_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools7.mps7_curr_school_id) AS loc_id
                                              FROM match_pref_schools7
                                              UNION ALL
                                              SELECT 'match_pref_schools8', mps8_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools8.mps8_curr_school_id) AS loc_id
                                              FROM match_pref_schools8
                                              
                                              UNION ALL
                                              SELECT 'match_pref_schools9', mps9_client_ec_no, (SELECT school_loc_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools9.mps9_curr_school_id) AS loc_id
                                              FROM match_pref_schools9
                                              
                                              UNION ALL
                                              SELECT 'match_pref_schools10', mps10_client_ec_no, (SELECT school_loc_id
                                                                                                  FROM schools
                                                                                                  WHERE school_id = match_pref_schools10.mps10_curr_school_id) AS loc_id
                                              FROM match_pref_schools10
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations' AS tab, mpl_client_ec_no AS EC_NO, (SELECT school_loc_id
                                                                                                  FROM schools
                                                                                                  WHERE school_id = match_pref_locations.mpl_curr_school_id) AS loc_id
                                              FROM match_pref_locations
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations2' AS tab, mpl2_client_ec_no AS EC_NO, (SELECT school_loc_id
                                                                                                  FROM schools
                                                                                                  WHERE school_id = match_pref_locations2.mpl2_curr_school_id) AS loc_id
                                              FROM match_pref_locations2
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations3' AS tab, mpl3_client_ec_no AS EC_NO, (SELECT school_loc_id
                                                                                                  FROM schools
                                                                                                  WHERE school_id = match_pref_locations3.mpl3_curr_school_id) AS loc_id
                                              FROM match_pref_locations3
                                              
                                              UNION ALL
                                              SELECT 'match_pref_towns', mpt_client_ec_no AS EC_NO, ( SELECT school_loc_id
                                                                                                      FROM schools
                                                                                                      WHERE school_id = match_pref_towns.mpt_curr_school_id) AS loc_id
                                              FROM match_pref_towns
                                              
                                              UNION ALL
                                              SELECT 'match_pref_districts', mpd_client_ec_no AS EC_NO, ( SELECT school_loc_id
                                                                                                          FROM schools
                                                                                                          WHERE school_id = match_pref_districts.mpd_curr_school_id) AS loc_id
                                              FROM match_pref_districts
                                              
                                              UNION ALL
                                              SELECT 'match_pref_districts2', mpd2_client_ec_no AS EC_NO, ( SELECT school_loc_id
                                                                                                            FROM schools
                                                                                                            WHERE school_id = match_pref_districts2.mpd2_curr_school_id) AS loc_id
                                              FROM match_pref_districts2
                                              
                                              UNION ALL
                                              SELECT 'match_pref_provinces', mpp_client_ec_no AS EC_NO, ( SELECT school_loc_id
                                                                                                          FROM schools
                                                                                                          WHERE school_id = match_pref_provinces.mpp_curr_school_id) AS loc_id
                                              FROM match_pref_provinces
                                              ) AS co_prefs
                                              ON mcs.mcs_client_ec_no = co_prefs.EC_NO
                                              AND co_prefs.loc_id = (SELECT school_loc_id
                                                                          FROM schools
                                                                          WHERE school_id = mpl3.mpl3_curr_school_id)
                                          WHERE mcs.mcs_id IN (SELECT MIN(mcs.mcs_id) 
                                            FROM match_current_schools AS mcs 
                                            GROUP BY mcs.mcs_loc_id)
                                            
                                          ORDER BY mcs.mcs_id");

	}catch (Exception $e){
			echo 'Failed to retrieve matched locations3';
			exit;

	}
	$matched_locations3 = $results_pref_locations3->fetchAll(PDO::FETCH_ASSOC); 
  
  try{$results_pref_districts1 = $db->query("SELECT mpd.mpd_client_ec_no, mcs.mcs_client_ec_no, co_prefs.tab, co_prefs.distr_id 
                                          FROM match_current_schools AS mcs  
                                          INNER JOIN match_pref_districts AS mpd 
                                            ON mpd.mpd_distr_id = mcs.mcs_distr_id
                                            AND mpd.mpd_client_level_taught = mcs.mcs_client_level_taught
                                            AND ((mpd.mpd_sub1_id = mcs.mcs_sub1_id) 
                                            OR (mpd.mpd_sub1_id = mcs.mcs_sub2_id)
                                            OR (mpd.mpd_sub2_id = mcs.mcs_sub1_id)
                                            OR (mpd.mpd_sub2_id = mcs.mcs_sub2_id))
                                            AND mpd.mpd_status = 'A' 
                                            AND mcs.mcs_status = 'A'
                                      INNER JOIN
                                            ( SELECT 'match_pref_schools' AS tab, mps_client_ec_no AS EC_NO, (SELECT school_distr_id
                                                                                                              FROM schools
                                                                                                              WHERE school_id = match_pref_schools.mps_curr_school_id) AS distr_id
                                              FROM match_pref_schools
                                              UNION ALL
                                              SELECT 'match_pref_schools2', mps2_client_ec_no, (SELECT school_distr_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools2.mps2_curr_school_id) AS distr_id
                                              FROM match_pref_schools2
                                              UNION ALL
                                              SELECT 'match_pref_schools3', mps3_client_ec_no, (SELECT school_distr_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools3.mps3_curr_school_id) AS distr_id
                                              FROM match_pref_schools3
                                              UNION ALL
                                              SELECT 'match_pref_schools4', mps4_client_ec_no, (SELECT school_distr_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools4.mps4_curr_school_id) AS distr_id
                                              FROM match_pref_schools4
                                              UNION ALL
                                              SELECT 'match_pref_schools5', mps5_client_ec_no, (SELECT school_distr_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools5.mps5_curr_school_id) AS distr_id
                                              FROM match_pref_schools5
                                              UNION ALL
                                              SELECT 'match_pref_schools6', mps6_client_ec_no, (SELECT school_distr_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools6.mps6_curr_school_id) AS distr_id
                                              FROM match_pref_schools6
                                              UNION ALL
                                              SELECT 'match_pref_schools7', mps7_client_ec_no, (SELECT school_distr_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools7.mps7_curr_school_id) AS distr_id
                                              FROM match_pref_schools7
                                              UNION ALL
                                              SELECT 'match_pref_schools8', mps8_client_ec_no, (SELECT school_distr_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools8.mps8_curr_school_id) AS distr_id
                                              FROM match_pref_schools8
                                              
                                              UNION ALL
                                              SELECT 'match_pref_schools9', mps9_client_ec_no, (SELECT school_distr_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools9.mps9_curr_school_id) AS distr_id
                                              FROM match_pref_schools9
                                              
                                              UNION ALL
                                              SELECT 'match_pref_schools10', mps10_client_ec_no, (SELECT school_distr_id
                                                                                                  FROM schools
                                                                                                  WHERE school_id = match_pref_schools10.mps10_curr_school_id) AS distr_id
                                              FROM match_pref_schools10
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations' AS tab, mpl_client_ec_no AS EC_NO, (SELECT school_distr_id
                                                                                                                FROM schools
                                                                                                                WHERE school_id = match_pref_locations.mpl_curr_school_id) AS distr_id
                                              FROM match_pref_locations
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations2' AS tab, mpl2_client_ec_no AS EC_NO, (SELECT school_distr_id
                                                                                                                  FROM schools
                                                                                                                  WHERE school_id = match_pref_locations2.mpl2_curr_school_id) AS distr_id
                                              FROM match_pref_locations2
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations3' AS tab, mpl3_client_ec_no AS EC_NO, (SELECT school_distr_id
                                                                                                                  FROM schools
                                                                                                                  WHERE school_id = match_pref_locations3.mpl3_curr_school_id) AS distr_id
                                              FROM match_pref_locations3
                                              
                                              UNION ALL
                                              SELECT 'match_pref_towns', mpt_client_ec_no AS EC_NO, ( SELECT school_distr_id
                                                                                                      FROM schools
                                                                                                      WHERE school_id = match_pref_towns.mpt_curr_school_id) AS distr_id
                                              FROM match_pref_towns
                                              
                                              UNION ALL
                                              SELECT 'match_pref_districts', mpd_client_ec_no AS EC_NO, ( SELECT school_distr_id
                                                                                                          FROM schools
                                                                                                          WHERE school_id = match_pref_districts.mpd_curr_school_id) AS distr_id
                                              FROM match_pref_districts
                                              
                                              UNION ALL
                                              SELECT 'match_pref_districts2', mpd2_client_ec_no AS EC_NO, ( SELECT school_distr_id
                                                                                                            FROM schools
                                                                                                            WHERE school_id = match_pref_districts2.mpd2_curr_school_id) AS distr_id
                                              FROM match_pref_districts2
                                              
                                              UNION ALL
                                              SELECT 'match_pref_provinces', mpp_client_ec_no AS EC_NO, ( SELECT school_distr_id
                                                                                                          FROM schools
                                                                                                          WHERE school_id = match_pref_provinces.mpp_curr_school_id) AS distr_id
                                              FROM match_pref_provinces
                                              ) AS co_prefs
                                              ON mcs.mcs_client_ec_no = co_prefs.EC_NO
                                              AND co_prefs.distr_id = (SELECT school_distr_id
                                                                          FROM schools
                                                                          WHERE school_id = mpd.mpd_curr_school_id)
                                          WHERE mcs.mcs_id IN (SELECT MIN(mcs.mcs_id) 
                                            FROM match_current_schools AS mcs 
                                            GROUP BY mcs.mcs_distr_id)
                                            
                                          ORDER BY mcs.mcs_id");

	}catch (Exception $e){
			echo 'Failed to retrieve matched districts1';
			exit;

	}
	$matched_districts1 = $results_pref_districts1->fetchAll(PDO::FETCH_ASSOC);
  
  try{$results_pref_districts2 = $db->query("SELECT mpd2.mpd2_client_ec_no, mcs.mcs_client_ec_no, co_prefs.tab, co_prefs.distr_id  
                                          FROM match_current_schools AS mcs  
                                          INNER JOIN match_pref_districts2 AS mpd2 
                                            ON mpd2.mpd2_distr_id = mcs.mcs_distr_id
                                            AND mpd2.mpd2_client_level_taught = mcs.mcs_client_level_taught
                                            AND ((mpd2.mpd2_sub1_id = mcs.mcs_sub1_id) 
                                            OR (mpd2.mpd2_sub1_id = mcs.mcs_sub2_id)
                                            OR (mpd2.mpd2_sub2_id = mcs.mcs_sub1_id)
                                            OR (mpd2.mpd2_sub2_id = mcs.mcs_sub2_id))
                                            AND mpd2.mpd2_status = 'A' 
                                            AND mcs.mcs_status = 'A'
                                      INNER JOIN
                                            ( SELECT 'match_pref_schools' AS tab, mps_client_ec_no AS EC_NO, (SELECT school_distr_id
                                                                                                              FROM schools
                                                                                                              WHERE school_id = match_pref_schools.mps_curr_school_id) AS distr_id
                                              FROM match_pref_schools
                                              UNION ALL
                                              SELECT 'match_pref_schools2', mps2_client_ec_no, (SELECT school_distr_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools2.mps2_curr_school_id) AS distr_id
                                              FROM match_pref_schools2
                                              UNION ALL
                                              SELECT 'match_pref_schools3', mps3_client_ec_no, (SELECT school_distr_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools3.mps3_curr_school_id) AS distr_id
                                              FROM match_pref_schools3
                                              UNION ALL
                                              SELECT 'match_pref_schools4', mps4_client_ec_no, (SELECT school_distr_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools4.mps4_curr_school_id) AS distr_id
                                              FROM match_pref_schools4
                                              UNION ALL
                                              SELECT 'match_pref_schools5', mps5_client_ec_no, (SELECT school_distr_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools5.mps5_curr_school_id) AS distr_id
                                              FROM match_pref_schools5
                                              UNION ALL
                                              SELECT 'match_pref_schools6', mps6_client_ec_no, (SELECT school_distr_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools6.mps6_curr_school_id) AS distr_id
                                              FROM match_pref_schools6
                                              UNION ALL
                                              SELECT 'match_pref_schools7', mps7_client_ec_no, (SELECT school_distr_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools7.mps7_curr_school_id) AS distr_id
                                              FROM match_pref_schools7
                                              UNION ALL
                                              SELECT 'match_pref_schools8', mps8_client_ec_no, (SELECT school_distr_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools8.mps8_curr_school_id) AS distr_id
                                              FROM match_pref_schools8
                                              
                                              UNION ALL
                                              SELECT 'match_pref_schools9', mps9_client_ec_no, (SELECT school_distr_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools9.mps9_curr_school_id) AS distr_id
                                              FROM match_pref_schools9
                                              
                                              UNION ALL
                                              SELECT 'match_pref_schools10', mps10_client_ec_no, (SELECT school_distr_id
                                                                                                  FROM schools
                                                                                                  WHERE school_id = match_pref_schools10.mps10_curr_school_id) AS distr_id
                                              FROM match_pref_schools10
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations' AS tab, mpl_client_ec_no AS EC_NO, (SELECT school_distr_id
                                                                                                                FROM schools
                                                                                                                WHERE school_id = match_pref_locations.mpl_curr_school_id) AS distr_id
                                              FROM match_pref_locations
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations2' AS tab, mpl2_client_ec_no AS EC_NO, (SELECT school_distr_id
                                                                                                                  FROM schools
                                                                                                                  WHERE school_id = match_pref_locations2.mpl2_curr_school_id) AS distr_id
                                              FROM match_pref_locations2
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations3' AS tab, mpl3_client_ec_no AS EC_NO, (SELECT school_distr_id
                                                                                                                  FROM schools
                                                                                                                  WHERE school_id = match_pref_locations3.mpl3_curr_school_id) AS distr_id
                                              FROM match_pref_locations3
                                              
                                              UNION ALL
                                              SELECT 'match_pref_towns', mpt_client_ec_no AS EC_NO, ( SELECT school_distr_id
                                                                                                      FROM schools
                                                                                                      WHERE school_id = match_pref_towns.mpt_curr_school_id) AS distr_id
                                              FROM match_pref_towns
                                              
                                              UNION ALL
                                              SELECT 'match_pref_districts', mpd_client_ec_no AS EC_NO, ( SELECT school_distr_id
                                                                                                          FROM schools
                                                                                                          WHERE school_id = match_pref_districts.mpd_curr_school_id) AS distr_id
                                              FROM match_pref_districts
                                              
                                              UNION ALL
                                              SELECT 'match_pref_districts2', mpd2_client_ec_no AS EC_NO, ( SELECT school_distr_id
                                                                                                            FROM schools
                                                                                                            WHERE school_id = match_pref_districts2.mpd2_curr_school_id) AS distr_id
                                              FROM match_pref_districts2
                                              
                                              UNION ALL
                                              SELECT 'match_pref_provinces', mpp_client_ec_no AS EC_NO, ( SELECT school_distr_id
                                                                                                          FROM schools
                                                                                                          WHERE school_id = match_pref_provinces.mpp_curr_school_id) AS distr_id
                                              FROM match_pref_provinces
                                              ) AS co_prefs
                                              ON mcs.mcs_client_ec_no = co_prefs.EC_NO
                                              AND co_prefs.distr_id = (SELECT school_distr_id
                                                                          FROM schools
                                                                          WHERE school_id = mpd2.mpd2_curr_school_id)
                                          WHERE mcs.mcs_id IN (SELECT MIN(mcs.mcs_id) 
                                            FROM match_current_schools AS mcs 
                                            GROUP BY mcs.mcs_distr_id)
                                            
                                          ORDER BY mcs.mcs_id");

	}catch (Exception $e){
			echo 'Failed to retrieve matched districts2';
			exit;

	}
	$matched_districts2 = $results_pref_districts2->fetchAll(PDO::FETCH_ASSOC);
  
  try{
        $results_pref_towns = $db->query("SELECT mpt.mpt_client_ec_no, mcs.mcs_client_ec_no, co_prefs.tab, co_prefs.town_id 
                                          FROM match_current_schools AS mcs  
                                          INNER JOIN match_pref_towns AS mpt 
                                            ON mpt.mpt_town_id = mcs.mcs_town_id
                                            AND mpt.mpt_client_level_taught = mcs.mcs_client_level_taught
                                            AND ((mpt.mpt_sub1_id = mcs.mcs_sub1_id) 
                                            OR (mpt.mpt_sub1_id = mcs.mcs_sub2_id)
                                            OR (mpt.mpt_sub2_id = mcs.mcs_sub1_id)
                                            OR (mpt.mpt_sub2_id = mcs.mcs_sub2_id))
                                            AND mpt.mpt_status = 'A' 
                                            AND mcs.mcs_status = 'A'
                                      INNER JOIN
                                            ( SELECT 'match_pref_schools' AS tab, mps_client_ec_no AS EC_NO, (SELECT school_town_id
                                                                                                              FROM schools
                                                                                                              WHERE school_id = match_pref_schools.mps_curr_school_id) AS town_id
                                              FROM match_pref_schools
                                              UNION ALL
                                              SELECT 'match_pref_schools2', mps2_client_ec_no, (SELECT school_town_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools2.mps2_curr_school_id) AS town_id
                                              FROM match_pref_schools2
                                              UNION ALL
                                              SELECT 'match_pref_schools3', mps3_client_ec_no, (SELECT school_town_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools3.mps3_curr_school_id) AS town_id
                                              FROM match_pref_schools3
                                              UNION ALL
                                              SELECT 'match_pref_schools4', mps4_client_ec_no, (SELECT school_town_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools4.mps4_curr_school_id) AS town_id
                                              FROM match_pref_schools4
                                              UNION ALL
                                              SELECT 'match_pref_schools5', mps5_client_ec_no, (SELECT school_town_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools5.mps5_curr_school_id) AS town_id
                                              FROM match_pref_schools5
                                              UNION ALL
                                              SELECT 'match_pref_schools6', mps6_client_ec_no, (SELECT school_town_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools6.mps6_curr_school_id) AS town_id
                                              FROM match_pref_schools6
                                              UNION ALL
                                              SELECT 'match_pref_schools7', mps7_client_ec_no, (SELECT school_town_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools7.mps7_curr_school_id) AS town_id
                                              FROM match_pref_schools7
                                              UNION ALL
                                              SELECT 'match_pref_schools8', mps8_client_ec_no, (SELECT school_town_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools8.mps8_curr_school_id) AS town_id
                                              FROM match_pref_schools8
                                              
                                              UNION ALL
                                              SELECT 'match_pref_schools9', mps9_client_ec_no, (SELECT school_town_id
                                                                                                FROM schools
                                                                                                WHERE school_id = match_pref_schools9.mps9_curr_school_id) AS town_id
                                              FROM match_pref_schools9
                                              
                                              UNION ALL
                                              SELECT 'match_pref_schools10', mps10_client_ec_no, (SELECT school_town_id
                                                                                                  FROM schools
                                                                                                  WHERE school_id = match_pref_schools10.mps10_curr_school_id) AS town_id
                                              FROM match_pref_schools10
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations' AS tab, mpl_client_ec_no AS EC_NO, (SELECT school_town_id
                                                                                                                FROM schools
                                                                                                                WHERE school_id = match_pref_locations.mpl_curr_school_id) AS town_id
                                              FROM match_pref_locations
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations2' AS tab, mpl2_client_ec_no AS EC_NO, (SELECT school_town_id
                                                                                                                  FROM schools
                                                                                                                  WHERE school_id = match_pref_locations2.mpl2_curr_school_id) AS town_id
                                              FROM match_pref_locations2
                                              
                                              UNION ALL
                                              SELECT 'match_pref_locations3' AS tab, mpl3_client_ec_no AS EC_NO, (SELECT school_town_id
                                                                                                                  FROM schools
                                                                                                                  WHERE school_id = match_pref_locations3.mpl3_curr_school_id) AS town_id
                                              FROM match_pref_locations3
                                              
                                              UNION ALL
                                              SELECT 'match_pref_towns', mpt_client_ec_no AS EC_NO, ( SELECT school_town_id
                                                                                                      FROM schools
                                                                                                      WHERE school_id = match_pref_towns.mpt_curr_school_id) AS town_id
                                              FROM match_pref_towns
                                              
                                              UNION ALL
                                              SELECT 'match_pref_districts', mpd_client_ec_no AS EC_NO, ( SELECT school_town_id
                                                                                                          FROM schools
                                                                                                          WHERE school_id = match_pref_districts.mpd_curr_school_id) AS town_id
                                              FROM match_pref_districts
                                              
                                              UNION ALL
                                              SELECT 'match_pref_districts2', mpd2_client_ec_no AS EC_NO, ( SELECT school_town_id
                                                                                                            FROM schools
                                                                                                            WHERE school_id = match_pref_districts2.mpd2_curr_school_id) AS town_id
                                              FROM match_pref_districts2
                                              
                                              UNION ALL
                                              SELECT 'match_pref_provinces', mpp_client_ec_no AS EC_NO, ( SELECT school_town_id
                                                                                                          FROM schools
                                                                                                          WHERE school_id = match_pref_provinces.mpp_curr_school_id) AS town_id
                                              FROM match_pref_provinces
                                              ) AS co_prefs
                                              ON mcs.mcs_client_ec_no = co_prefs.EC_NO
                                              AND co_prefs.town_id = (SELECT school_town_id
                                                                          FROM schools
                                                                          WHERE school_id = mpt.mpt_curr_school_id)
                                          WHERE mcs.mcs_id IN (SELECT MIN(mcs.mcs_id) 
                                            FROM match_current_schools AS mcs 
                                            GROUP BY mcs.mcs_town_id)
                                            
                                          ORDER BY mcs.mcs_id");

	}catch (Exception $e){
			echo 'Failed to retrieve matched towns';
			exit;

	}
	$matched_towns = $results_pref_towns->fetchAll(PDO::FETCH_ASSOC);

	
	try{
	$results_pref_provinces1 = $db->query("SELECT
    mpp.mpp_client_ec_no,
    mcs.mcs_client_ec_no,
    co_prefs.tab,
    co_prefs.province_id
        FROM
            match_current_schools AS mcs
        INNER JOIN match_pref_provinces AS mpp
        ON
            mcs.mcs_province_id = mpp.mpp_province_id AND mpp.mpp_client_level_taught = mcs.mcs_client_level_taught AND(
                (
                    mpp.mpp_sub1_id = mcs.mcs_sub1_id
                ) OR(
                    mpp.mpp_sub1_id = mcs.mcs_sub2_id
                ) OR(
                    mpp.mpp_sub2_id = mcs.mcs_sub1_id
                ) OR(
                    mpp.mpp_sub2_id = mcs.mcs_sub2_id
                )
            ) AND mpp.mpp_status = 'A' AND mcs.mcs_status = 'A'
        INNER JOIN
        (
            SELECT
                'match_pref_locations' AS tab,
                mpl_client_ec_no AS EC_NO,
                mpl_loc_province_id AS province_id
            FROM
                match_pref_locations
            UNION ALL
        SELECT
            'match_pref_locations2' AS tab,
            mpl2_client_ec_no AS EC_NO,
            mpl2_loc_province_id AS province_id
        FROM
            match_pref_locations2
        UNION ALL
        SELECT
            'match_pref_locations3' AS tab,
            mpl3_client_ec_no AS EC_NO,
            mpl3_loc_province_id AS province_id
        FROM
            match_pref_locations3
        UNION ALL
        SELECT
            'match_pref_locations4' AS tab,
            mpl4_client_ec_no AS EC_NO,
            mpl4_loc_province_id AS province_id
        FROM
            match_pref_locations4
        UNION ALL
        SELECT
            'match_pref_locations5' AS tab,
            mpl5_client_ec_no AS EC_NO,
            mpl5_loc_province_id AS province_id
        FROM
            match_pref_locations5
        UNION ALL
        SELECT
            'match_pref_towns',
            mpt_client_ec_no AS EC_NO,
            mpt_town_province_id AS province_id
        FROM
            match_pref_towns
        UNION ALL
        SELECT
            'match_pref_towns2',
            mpt2_client_ec_no AS EC_NO,
            mpt2_town_province_id AS province_id
        FROM
            match_pref_towns2
        UNION ALL
        SELECT
            'match_pref_towns3',
            mpt3_client_ec_no AS EC_NO,
            mpt3_town_province_id AS province_id
        FROM
            match_pref_towns3
        UNION ALL
        SELECT
            'match_pref_districts',
            mpd_client_ec_no AS EC_NO,
            mpd_distr_province_id AS province_id
        FROM
            match_pref_districts
        UNION ALL
        SELECT
            'match_pref_districts2',
            mpd2_client_ec_no AS EC_NO,
            mpd2_distr_province_id AS province_id
        FROM
            match_pref_districts2
        UNION ALL
        SELECT
            'match_pref_districts3',
            mpd3_client_ec_no AS EC_NO,
            mpd3_distr_province_id AS province_id
        FROM
            match_pref_districts3
        UNION ALL
        SELECT
            'match_pref_districts4',
            mpd4_client_ec_no AS EC_NO,
            mpd4_distr_province_id AS province_id
        FROM
            match_pref_districts4
        UNION ALL
        SELECT
            'match_pref_provinces',
            mpp_client_ec_no AS EC_NO,
            mpp_province_id AS province_id
        FROM
            match_pref_provinces
        ) AS co_prefs
        ON
            mcs.mcs_client_ec_no = co_prefs.EC_NO AND co_prefs.province_id = mpp.mpp_curr_province_id /*
        WHERE
            mcs.mcs_id IN(
            SELECT
                MIN(mcs.mcs_id)
            FROM
                match_current_schools AS mcs
            GROUP BY
                mcs.mcs_province_id
        )*//*
        ORDER BY
            mcs.mcs_id");
        
        	}catch (Exception $e){
        			echo 'Failed to retrieve matched provinces';
        			exit;
        
        	}
	
	$matched_provinces1 = $results_pref_provinces1->fetchAll(PDO::FETCH_ASSOC);
	
	try{$results_pref_provinces2 = $db->query("SELECT
    mpp2.mpp2_client_ec_no,
    mcs.mcs_client_ec_no,
    co_prefs.tab,
    co_prefs.province_id
        FROM
            match_current_schools AS mcs
        INNER JOIN match_pref_provinces2 AS mpp2
        ON
            mcs.mcs_province_id = mpp2.mpp2_province_id AND mpp2.mpp2_client_level_taught = mcs.mcs_client_level_taught AND(
                (
                    mpp2.mpp2_sub1_id = mcs.mcs_sub1_id
                ) OR(
                    mpp2.mpp2_sub1_id = mcs.mcs_sub2_id
                ) OR(
                    mpp2.mpp2_sub2_id = mcs.mcs_sub1_id
                ) OR(
                    mpp2.mpp2_sub2_id = mcs.mcs_sub2_id
                )
            ) AND mpp2.mpp2_status = 'A' AND mcs.mcs_status = 'A'
        INNER JOIN
        (
            SELECT
                'match_pref_locations' AS tab,
                mpl_client_ec_no AS EC_NO,
                mpl_loc_province_id AS province_id
            FROM
                match_pref_locations
            UNION ALL
        SELECT
            'match_pref_locations2' AS tab,
            mpl2_client_ec_no AS EC_NO,
            mpl2_loc_province_id AS province_id
        FROM
            match_pref_locations2
        UNION ALL
        SELECT
            'match_pref_locations3' AS tab,
            mpl3_client_ec_no AS EC_NO,
            mpl3_loc_province_id AS province_id
        FROM
            match_pref_locations3
        UNION ALL
        SELECT
            'match_pref_locations4' AS tab,
            mpl4_client_ec_no AS EC_NO,
            mpl4_loc_province_id AS province_id
        FROM
            match_pref_locations4
        UNION ALL
        SELECT
            'match_pref_locations5' AS tab,
            mpl5_client_ec_no AS EC_NO,
            mpl5_loc_province_id AS province_id
        FROM
            match_pref_locations5
        UNION ALL
        SELECT
            'match_pref_towns',
            mpt_client_ec_no AS EC_NO,
            mpt_town_province_id AS province_id
        FROM
            match_pref_towns
        UNION ALL
        SELECT
            'match_pref_towns2',
            mpt2_client_ec_no AS EC_NO,
            mpt2_town_province_id AS province_id
        FROM
            match_pref_towns2
        UNION ALL
        SELECT
            'match_pref_towns3',
            mpt3_client_ec_no AS EC_NO,
            mpt3_town_province_id AS province_id
        FROM
            match_pref_towns3
        UNION ALL
        SELECT
            'match_pref_districts',
            mpd_client_ec_no AS EC_NO,
            mpd_distr_province_id AS province_id
        FROM
            match_pref_districts
        UNION ALL
        SELECT
            'match_pref_districts2',
            mpd2_client_ec_no AS EC_NO,
            mpd2_distr_province_id AS province_id
        FROM
            match_pref_districts2
        UNION ALL
        SELECT
            'match_pref_districts3',
            mpd3_client_ec_no AS EC_NO,
            mpd3_distr_province_id AS province_id
        FROM
            match_pref_districts3
        UNION ALL
        SELECT
            'match_pref_districts4',
            mpd4_client_ec_no AS EC_NO,
            mpd4_distr_province_id AS province_id
        FROM
            match_pref_districts4
        UNION ALL
        SELECT
            'match_pref_provinces2',
            mpp2_client_ec_no AS EC_NO,
            mpp2_province_id AS province_id
        FROM
            match_pref_provinces2
        UNION ALL
        SELECT
            'match_pref_provinces',
            mpp_client_ec_no AS EC_NO,
            mpp_province_id AS province_id
        FROM
            match_pref_provinces
        ) AS co_prefs
        ON
            mcs.mcs_client_ec_no = co_prefs.EC_NO AND co_prefs.province_id = mpp2.mpp2_curr_province_id
        ORDER BY
            mcs.mcs_id");
        
        	}catch (Exception $e){
        			echo 'Failed to retrieve matched provinces2';
        			exit;
        
        	}
	
	$matched_provinces2 = $results_pref_provinces2->fetchAll(PDO::FETCH_ASSOC);
	
	try{
	$results_pref_towns1 = $db->query("SELECT
    mpt.mpt_client_ec_no,
    mcs.mcs_client_ec_no,
    co_prefs.tab,
    co_prefs.town_id
        FROM
            match_current_schools AS mcs
        INNER JOIN match_pref_towns AS mpt
        ON
            mcs.mcs_town_id = mpt.mpt_town_id AND mpt.mpt_client_level_taught = mcs.mcs_client_level_taught AND(
                (
                    mpt.mpt_sub1_id = mcs.mcs_sub1_id
                ) OR(
                    mpt.mpt_sub1_id = mcs.mcs_sub2_id
                ) OR(
                    mpt.mpt_sub2_id = mcs.mcs_sub1_id
                ) OR(
                    mpt.mpt_sub2_id = mcs.mcs_sub2_id
                )
            ) AND mpt.mpt_status = 'A' AND mcs.mcs_status = 'A'
        INNER JOIN
        (
            SELECT
                'match_pref_locations' AS tab,
                mpl_client_ec_no AS EC_NO,
                mpl_loc_town_id AS town_id
            FROM
                match_pref_locations
            UNION ALL
        SELECT
            'match_pref_locations2' AS tab,
            mpl2_client_ec_no AS EC_NO,
            mpl2_loc_town_id AS town_id
        FROM
            match_pref_locations2
        UNION ALL
        SELECT
            'match_pref_locations3' AS tab,
            mpl3_client_ec_no AS EC_NO,
            mpl3_loc_town_id AS town_id
        FROM
            match_pref_locations3
        UNION ALL
        SELECT
            'match_pref_locations4' AS tab,
            mpl4_client_ec_no AS EC_NO,
            mpl4_loc_town_id AS town_id
        FROM
            match_pref_locations4
        UNION ALL
        SELECT
            'match_pref_locations5' AS tab,
            mpl5_client_ec_no AS EC_NO,
            mpl5_loc_town_id AS town_id
        FROM
            match_pref_locations5
        UNION ALL
        SELECT
            'match_pref_towns',
            mpt_client_ec_no AS EC_NO,
            mpt_town_id AS town_id
        FROM
            match_pref_towns
        UNION ALL
        SELECT
            'match_pref_towns2',
            mpt2_client_ec_no AS EC_NO,
            mpt2_town_id AS town_id
        FROM
            match_pref_towns2
        UNION ALL
        SELECT
            'match_pref_towns3',
            mpt3_client_ec_no AS EC_NO,
            mpt3_town_id AS town_id
        FROM
            match_pref_towns3
        UNION ALL
        SELECT
            'match_pref_districts',
            mpd_client_ec_no AS EC_NO,
            mpd_distr_town_id AS town_id
        FROM
            match_pref_districts
        UNION ALL
        SELECT
            'match_pref_districts2',
            mpd2_client_ec_no AS EC_NO,
            mpd2_distr_town_id AS town_id
        FROM
            match_pref_districts2
        UNION ALL
        SELECT
            'match_pref_districts3',
            mpd3_client_ec_no AS EC_NO,
            mpd3_distr_town_id AS town_id
        FROM
            match_pref_districts3
        UNION ALL
        SELECT
            'match_pref_districts4',
            mpd4_client_ec_no AS EC_NO,
            mpd4_distr_town_id AS town_id
        FROM
            match_pref_districts4) AS co_prefs
        ON
            mcs.mcs_client_ec_no = co_prefs.EC_NO AND co_prefs.town_id = mpt.mpt_curr_town_id
        ORDER BY
            mcs.mcs_id");
        
        	}catch (Exception $e){
        			echo 'Failed to retrieve matched towns';
        			exit;
        
        	}
	
	$matched_towns1 = $results_pref_towns1->fetchAll(PDO::FETCH_ASSOC);
	
	try{
	$results_pref_towns2 = $db->query("SELECT
    mpt2.mpt2_client_ec_no,
    mcs.mcs_client_ec_no,
    co_prefs.tab,
    co_prefs.town_id
        FROM
            match_current_schools AS mcs
        INNER JOIN match_pref_towns2 AS mpt2
        ON
            mcs.mcs_town_id = mpt2.mpt2_town_id AND mpt2.mpt2_client_level_taught = mcs.mcs_client_level_taught AND(
                (
                    mpt2.mpt2_sub1_id = mcs.mcs_sub1_id
                ) OR(
                    mpt2.mpt2_sub1_id = mcs.mcs_sub2_id
                ) OR(
                    mpt2.mpt2_sub2_id = mcs.mcs_sub1_id
                ) OR(
                    mpt2.mpt2_sub2_id = mcs.mcs_sub2_id
                )
            ) AND mpt2.mpt2_status = 'A' AND mcs.mcs_status = 'A'
        INNER JOIN
        (
            SELECT
                'match_pref_locations' AS tab,
                mpl_client_ec_no AS EC_NO,
                mpl_loc_town_id AS town_id
            FROM
                match_pref_locations
            UNION ALL
        SELECT
            'match_pref_locations2' AS tab,
            mpl2_client_ec_no AS EC_NO,
            mpl2_loc_town_id AS town_id
        FROM
            match_pref_locations2
        UNION ALL
        SELECT
            'match_pref_locations3' AS tab,
            mpl3_client_ec_no AS EC_NO,
            mpl3_loc_town_id AS town_id
        FROM
            match_pref_locations3
        UNION ALL
        SELECT
            'match_pref_locations4' AS tab,
            mpl4_client_ec_no AS EC_NO,
            mpl4_loc_town_id AS town_id
        FROM
            match_pref_locations4
        UNION ALL
        SELECT
            'match_pref_locations5' AS tab,
            mpl5_client_ec_no AS EC_NO,
            mpl5_loc_town_id AS town_id
        FROM
            match_pref_locations5
        UNION ALL
        SELECT
            'match_pref_towns',
            mpt_client_ec_no AS EC_NO,
            mpt_town_id AS town_id
        FROM
            match_pref_towns
        UNION ALL
        SELECT
            'match_pref_towns2',
            mpt2_client_ec_no AS EC_NO,
            mpt2_town_id AS town_id
        FROM
            match_pref_towns2
        UNION ALL
        SELECT
            'match_pref_towns3',
            mpt3_client_ec_no AS EC_NO,
            mpt3_town_id AS town_id
        FROM
            match_pref_towns3
        UNION ALL
        SELECT
            'match_pref_districts',
            mpd_client_ec_no AS EC_NO,
            mpd_distr_town_id AS town_id
        FROM
            match_pref_districts
        UNION ALL
        SELECT
            'match_pref_districts2',
            mpd2_client_ec_no AS EC_NO,
            mpd2_distr_town_id AS town_id
        FROM
            match_pref_districts2
        UNION ALL
        SELECT
            'match_pref_districts3',
            mpd3_client_ec_no AS EC_NO,
            mpd3_distr_town_id AS town_id
        FROM
            match_pref_districts3
        UNION ALL
        SELECT
            'match_pref_districts4',
            mpd4_client_ec_no AS EC_NO,
            mpd4_distr_town_id AS town_id
        FROM
            match_pref_districts4) AS co_prefs
        ON
            mcs.mcs_client_ec_no = co_prefs.EC_NO AND co_prefs.town_id = mpt2.mpt2_curr_town_id
        ORDER BY
            mcs.mcs_id");
        
        	}catch (Exception $e){
        			echo 'Failed to retrieve matched towns2';
        			exit;
        
        	}
	
	$matched_towns2 = $results_pref_towns2->fetchAll(PDO::FETCH_ASSOC);
	
	try{
	$results_pref_towns3 = $db->query("SELECT
    mpt3.mpt3_client_ec_no,
    mcs.mcs_client_ec_no,
    co_prefs.tab,
    co_prefs.town_id
        FROM
            match_current_schools AS mcs
        INNER JOIN match_pref_towns3 AS mpt3
        ON
            mcs.mcs_town_id = mpt3.mpt3_town_id AND mpt3.mpt3_client_level_taught = mcs.mcs_client_level_taught AND(
                (
                    mpt3.mpt3_sub1_id = mcs.mcs_sub1_id
                ) OR(
                    mpt3.mpt3_sub1_id = mcs.mcs_sub2_id
                ) OR(
                    mpt3.mpt3_sub2_id = mcs.mcs_sub1_id
                ) OR(
                    mpt3.mpt3_sub2_id = mcs.mcs_sub2_id
                )
            ) AND mpt3.mpt3_status = 'A' AND mcs.mcs_status = 'A'
        INNER JOIN
        (
            SELECT
                'match_pref_locations' AS tab,
                mpl_client_ec_no AS EC_NO,
                mpl_loc_town_id AS town_id
            FROM
                match_pref_locations
            UNION ALL
        SELECT
            'match_pref_locations2' AS tab,
            mpl2_client_ec_no AS EC_NO,
            mpl2_loc_town_id AS town_id
        FROM
            match_pref_locations2
        UNION ALL
        SELECT
            'match_pref_locations3' AS tab,
            mpl3_client_ec_no AS EC_NO,
            mpl3_loc_town_id AS town_id
        FROM
            match_pref_locations3
        UNION ALL
        SELECT
            'match_pref_locations4' AS tab,
            mpl4_client_ec_no AS EC_NO,
            mpl4_loc_town_id AS town_id
        FROM
            match_pref_locations4
        UNION ALL
        SELECT
            'match_pref_locations5' AS tab,
            mpl5_client_ec_no AS EC_NO,
            mpl5_loc_town_id AS town_id
        FROM
            match_pref_locations5
        UNION ALL
        SELECT
            'match_pref_towns',
            mpt_client_ec_no AS EC_NO,
            mpt_town_id AS town_id
        FROM
            match_pref_towns
        UNION ALL
        SELECT
            'match_pref_towns2',
            mpt2_client_ec_no AS EC_NO,
            mpt2_town_id AS town_id
        FROM
            match_pref_towns2
        UNION ALL
        SELECT
            'match_pref_towns3',
            mpt3_client_ec_no AS EC_NO,
            mpt3_town_id AS town_id
        FROM
            match_pref_towns3
        UNION ALL
        SELECT
            'match_pref_districts',
            mpd_client_ec_no AS EC_NO,
            mpd_distr_town_id AS town_id
        FROM
            match_pref_districts
        UNION ALL
        SELECT
            'match_pref_districts2',
            mpd2_client_ec_no AS EC_NO,
            mpd2_distr_town_id AS town_id
        FROM
            match_pref_districts2
        UNION ALL
        SELECT
            'match_pref_districts3',
            mpd3_client_ec_no AS EC_NO,
            mpd3_distr_town_id AS town_id
        FROM
            match_pref_districts3
        UNION ALL
        SELECT
            'match_pref_districts4',
            mpd4_client_ec_no AS EC_NO,
            mpd4_distr_town_id AS town_id
        FROM
            match_pref_districts4) AS co_prefs
        ON
            mcs.mcs_client_ec_no = co_prefs.EC_NO AND co_prefs.town_id = mpt3.mpt3_curr_town_id
        ORDER BY
            mcs.mcs_id");
        
        	}catch (Exception $e){
        			echo 'Failed to retrieve matched towns3';
        			exit;
        
        	}
	
	$matched_towns3 = $results_pref_towns3->fetchAll(PDO::FETCH_ASSOC);
	
	try{
	$results_pref_districts1 = $db->query("SELECT
    mpd.mpd_client_ec_no,
    mcs.mcs_client_ec_no,
    co_prefs.tab,
    co_prefs.distr_id
        FROM
            match_current_schools AS mcs
        INNER JOIN match_pref_districts AS mpd
        ON
            mcs.mcs_distr_id = mpd.mpd_distr_id AND mpd.mpd_client_level_taught = mcs.mcs_client_level_taught AND(
                (
                    mpd.mpd_sub1_id = mcs.mcs_sub1_id
                ) OR(
                    mpd.mpd_sub1_id = mcs.mcs_sub2_id
                ) OR(
                    mpd.mpd_sub2_id = mcs.mcs_sub1_id
                ) OR(
                    mpd.mpd_sub2_id = mcs.mcs_sub2_id
                )
            ) AND mpd.mpd_status = 'A' AND mcs.mcs_status = 'A'
        INNER JOIN
        (
            SELECT
                'match_pref_locations' AS tab,
                mpl_client_ec_no AS EC_NO,
                mpl_loc_distr_id AS distr_id
            FROM
                match_pref_locations
            UNION ALL
        SELECT
            'match_pref_locations2' AS tab,
            mpl2_client_ec_no AS EC_NO,
            mpl2_loc_distr_id AS distr_id
        FROM
            match_pref_locations2
        UNION ALL
        SELECT
            'match_pref_locations3' AS tab,
            mpl3_client_ec_no AS EC_NO,
            mpl3_loc_distr_id AS distr_id
        FROM
            match_pref_locations3
        UNION ALL
        SELECT
            'match_pref_locations4' AS tab,
            mpl4_client_ec_no AS EC_NO,
            mpl4_loc_distr_id AS distr_id
        FROM
            match_pref_locations4
        UNION ALL
        SELECT
            'match_pref_locations5' AS tab,
            mpl5_client_ec_no AS EC_NO,
            mpl5_loc_distr_id AS distr_id
        FROM
            match_pref_locations5
        UNION ALL
        SELECT
            'match_pref_towns',
            mpt_client_ec_no AS EC_NO,
            mpt_town_distr_id AS distr_id
        FROM
            match_pref_towns
        UNION ALL
        SELECT
            'match_pref_towns2',
            mpt2_client_ec_no AS EC_NO,
            mpt2_town_distr_id AS distr_id
        FROM
            match_pref_towns2
        UNION ALL
        SELECT
            'match_pref_towns3',
            mpt3_client_ec_no AS EC_NO,
            mpt3_town_distr_id AS distr_id
        FROM
            match_pref_towns3
        UNION ALL
        SELECT
            'match_pref_districts',
            mpd_client_ec_no AS EC_NO,
            mpd_distr_id AS distr_id
        FROM
            match_pref_districts
        UNION ALL
        SELECT
            'match_pref_districts2',
            mpd2_client_ec_no AS EC_NO,
            mpd2_distr_id AS distr_id
        FROM
            match_pref_districts2
        UNION ALL
        SELECT
            'match_pref_districts3',
            mpd3_client_ec_no AS EC_NO,
            mpd3_distr_id AS distr_id
        FROM
            match_pref_districts3
        UNION ALL
        SELECT
            'match_pref_districts4',
            mpd4_client_ec_no AS EC_NO,
            mpd4_distr_id AS distr_id
        FROM
            match_pref_districts4) AS co_prefs
        ON
            mcs.mcs_client_ec_no = co_prefs.EC_NO AND co_prefs.distr_id = mpd.mpd_curr_distr_id
        ORDER BY
            mcs.mcs_id");
        
        	}catch (Exception $e){
        			echo 'Failed to retrieve matched districts1';
        			exit;
        
        	}
	
	$matched_districts1 = $results_pref_districts1->fetchAll(PDO::FETCH_ASSOC);
	
	try{
	$results_pref_districts2 = $db->query("SELECT
    mpd2.mpd2_client_ec_no,
    mcs.mcs_client_ec_no,
    co_prefs.tab,
    co_prefs.distr_id
        FROM
            match_current_schools AS mcs
        INNER JOIN match_pref_districts2 AS mpd2
        ON
            mcs.mcs_distr_id = mpd2.mpd2_distr_id AND mpd2.mpd2_client_level_taught = mcs.mcs_client_level_taught AND(
                (
                    mpd2.mpd2_sub1_id = mcs.mcs_sub1_id
                ) OR(
                    mpd2.mpd2_sub1_id = mcs.mcs_sub2_id
                ) OR(
                    mpd2.mpd2_sub2_id = mcs.mcs_sub1_id
                ) OR(
                    mpd2.mpd2_sub2_id = mcs.mcs_sub2_id
                )
            ) AND mpd2.mpd2_status = 'A' AND mcs.mcs_status = 'A'
        INNER JOIN
        (
            SELECT
                'match_pref_locations' AS tab,
                mpl_client_ec_no AS EC_NO,
                mpl_loc_distr_id AS distr_id
            FROM
                match_pref_locations
            UNION ALL
        SELECT
            'match_pref_locations2' AS tab,
            mpl2_client_ec_no AS EC_NO,
            mpl2_loc_distr_id AS distr_id
        FROM
            match_pref_locations2
        UNION ALL
        SELECT
            'match_pref_locations3' AS tab,
            mpl3_client_ec_no AS EC_NO,
            mpl3_loc_distr_id AS distr_id
        FROM
            match_pref_locations3
        UNION ALL
        SELECT
            'match_pref_locations4' AS tab,
            mpl4_client_ec_no AS EC_NO,
            mpl4_loc_distr_id AS distr_id
        FROM
            match_pref_locations4
        UNION ALL
        SELECT
            'match_pref_locations5' AS tab,
            mpl5_client_ec_no AS EC_NO,
            mpl5_loc_distr_id AS distr_id
        FROM
            match_pref_locations5
        UNION ALL
        SELECT
            'match_pref_towns',
            mpt_client_ec_no AS EC_NO,
            mpt_town_id AS town_id
        FROM
            match_pref_towns
        UNION ALL
        SELECT
            'match_pref_towns2',
            mpt2_client_ec_no AS EC_NO,
            mpt2_town_distr_id AS distr_id
        FROM
            match_pref_towns2
        UNION ALL
        SELECT
            'match_pref_towns3',
            mpt3_client_ec_no AS EC_NO,
            mpt3_town_distr_id AS distr_id
        FROM
            match_pref_towns3
        UNION ALL
        SELECT
            'match_pref_districts',
            mpd_client_ec_no AS EC_NO,
            mpd_distr_id AS distr_id
        FROM
            match_pref_districts
        UNION ALL
        SELECT
            'match_pref_districts2',
            mpd2_client_ec_no AS EC_NO,
            mpd2_distr_id AS distr_id
        FROM
            match_pref_districts2
        UNION ALL
        SELECT
            'match_pref_districts3',
            mpd3_client_ec_no AS EC_NO,
            mpd3_distr_id AS distr_id
        FROM
            match_pref_districts3
        UNION ALL
        SELECT
            'match_pref_districts4',
            mpd4_client_ec_no AS EC_NO,
            mpd4_distr_id AS distr_id
        FROM
            match_pref_districts4) AS co_prefs
        ON
            mcs.mcs_client_ec_no = co_prefs.EC_NO AND co_prefs.distr_id = mpd2.mpd2_curr_distr_id
        ORDER BY
            mcs.mcs_id");
        
        	}catch (Exception $e){
        			echo 'Failed to retrieve matched districts2';
        			exit;
        
        	}
	
	$matched_districts2 = $results_pref_districts2->fetchAll(PDO::FETCH_ASSOC);
	
	try{
	$results_pref_districts3 = $db->query("SELECT
    mpd3.mpd3_client_ec_no,
    mcs.mcs_client_ec_no,
    co_prefs.tab,
    co_prefs.distr_id
        FROM
            match_current_schools AS mcs
        INNER JOIN match_pref_districts3 AS mpd3
        ON
            mcs.mcs_distr_id = mpd3.mpd3_distr_id AND mpd3.mpd3_client_level_taught = mcs.mcs_client_level_taught AND(
                (
                    mpd3.mpd3_sub1_id = mcs.mcs_sub1_id
                ) OR(
                    mpd3.mpd3_sub1_id = mcs.mcs_sub2_id
                ) OR(
                    mpd3.mpd3_sub2_id = mcs.mcs_sub1_id
                ) OR(
                    mpd3.mpd3_sub2_id = mcs.mcs_sub2_id
                )
            ) AND mpd3.mpd3_status = 'A' AND mcs.mcs_status = 'A'
        INNER JOIN
        (
            SELECT
                'match_pref_locations' AS tab,
                mpl_client_ec_no AS EC_NO,
                mpl_loc_distr_id AS distr_id
            FROM
                match_pref_locations
            UNION ALL
        SELECT
            'match_pref_locations2' AS tab,
            mpl2_client_ec_no AS EC_NO,
            mpl2_loc_distr_id AS distr_id
        FROM
            match_pref_locations2
        UNION ALL
        SELECT
            'match_pref_locations3' AS tab,
            mpl3_client_ec_no AS EC_NO,
            mpl3_loc_distr_id AS distr_id
        FROM
            match_pref_locations3
        UNION ALL
        SELECT
            'match_pref_locations4' AS tab,
            mpl4_client_ec_no AS EC_NO,
            mpl4_loc_distr_id AS distr_id
        FROM
            match_pref_locations4
        UNION ALL
        SELECT
            'match_pref_locations5' AS tab,
            mpl5_client_ec_no AS EC_NO,
            mpl5_loc_distr_id AS distr_id
        FROM
            match_pref_locations5
        UNION ALL
        SELECT
            'match_pref_towns',
            mpt_client_ec_no AS EC_NO,
            mpt_town_id AS town_id
        FROM
            match_pref_towns
        UNION ALL
        SELECT
            'match_pref_towns2',
            mpt2_client_ec_no AS EC_NO,
            mpt2_town_distr_id AS distr_id
        FROM
            match_pref_towns2
        UNION ALL
        SELECT
            'match_pref_towns3',
            mpt3_client_ec_no AS EC_NO,
            mpt3_town_distr_id AS distr_id
        FROM
            match_pref_towns3
        UNION ALL
        SELECT
            'match_pref_districts',
            mpd_client_ec_no AS EC_NO,
            mpd_distr_id AS distr_id
        FROM
            match_pref_districts
        UNION ALL
        SELECT
            'match_pref_districts2',
            mpd2_client_ec_no AS EC_NO,
            mpd2_distr_id AS distr_id
        FROM
            match_pref_districts2
        UNION ALL
        SELECT
            'match_pref_districts3',
            mpd3_client_ec_no AS EC_NO,
            mpd3_distr_id AS distr_id
        FROM
            match_pref_districts3
        UNION ALL
        SELECT
            'match_pref_districts4',
            mpd4_client_ec_no AS EC_NO,
            mpd4_distr_id AS distr_id
        FROM
            match_pref_districts4) AS co_prefs
        ON
            mcs.mcs_client_ec_no = co_prefs.EC_NO AND co_prefs.distr_id = mpd3.mpd3_curr_distr_id
        ORDER BY
            mcs.mcs_id");
        
        	}catch (Exception $e){
        			echo 'Failed to retrieve matched districts3';
        			exit;
        
        	}
	
	$matched_districts3 = $results_pref_districts3->fetchAll(PDO::FETCH_ASSOC);
	
	try{
	$results_pref_districts4 = $db->query("SELECT
    mpd4.mpd4_client_ec_no,
    mcs.mcs_client_ec_no,
    co_prefs.tab,
    co_prefs.distr_id
        FROM
            match_current_schools AS mcs
        INNER JOIN match_pref_districts4 AS mpd4
        ON
            mcs.mcs_distr_id = mpd4.mpd4_distr_id AND mpd4.mpd4_client_level_taught = mcs.mcs_client_level_taught AND(
                (
                    mpd4.mpd4_sub1_id = mcs.mcs_sub1_id
                ) OR(
                    mpd4.mpd4_sub1_id = mcs.mcs_sub2_id
                ) OR(
                    mpd4.mpd4_sub2_id = mcs.mcs_sub1_id
                ) OR(
                    mpd4.mpd4_sub2_id = mcs.mcs_sub2_id
                )
            ) AND mpd4.mpd4_status = 'A' AND mcs.mcs_status = 'A'
        INNER JOIN
        (
            SELECT
                'match_pref_locations' AS tab,
                mpl_client_ec_no AS EC_NO,
                mpl_loc_distr_id AS distr_id
            FROM
                match_pref_locations
            UNION ALL
        SELECT
            'match_pref_locations2' AS tab,
            mpl2_client_ec_no AS EC_NO,
            mpl2_loc_distr_id AS distr_id
        FROM
            match_pref_locations2
        UNION ALL
        SELECT
            'match_pref_locations3' AS tab,
            mpl3_client_ec_no AS EC_NO,
            mpl3_loc_distr_id AS distr_id
        FROM
            match_pref_locations3
        UNION ALL
        SELECT
            'match_pref_locations4' AS tab,
            mpl4_client_ec_no AS EC_NO,
            mpl4_loc_distr_id AS distr_id
        FROM
            match_pref_locations4
        UNION ALL
        SELECT
            'match_pref_locations5' AS tab,
            mpl5_client_ec_no AS EC_NO,
            mpl5_loc_distr_id AS distr_id
        FROM
            match_pref_locations5
        UNION ALL
        SELECT
            'match_pref_towns',
            mpt_client_ec_no AS EC_NO,
            mpt_town_id AS town_id
        FROM
            match_pref_towns
        UNION ALL
        SELECT
            'match_pref_towns2',
            mpt2_client_ec_no AS EC_NO,
            mpt2_town_distr_id AS distr_id
        FROM
            match_pref_towns2
        UNION ALL
        SELECT
            'match_pref_towns3',
            mpt3_client_ec_no AS EC_NO,
            mpt3_town_distr_id AS distr_id
        FROM
            match_pref_towns3
        UNION ALL
        SELECT
            'match_pref_districts',
            mpd_client_ec_no AS EC_NO,
            mpd_distr_id AS distr_id
        FROM
            match_pref_districts
        UNION ALL
        SELECT
            'match_pref_districts2',
            mpd2_client_ec_no AS EC_NO,
            mpd2_distr_id AS distr_id
        FROM
            match_pref_districts2
        UNION ALL
        SELECT
            'match_pref_districts3',
            mpd3_client_ec_no AS EC_NO,
            mpd3_distr_id AS distr_id
        FROM
            match_pref_districts3
        UNION ALL
        SELECT
            'match_pref_districts4',
            mpd4_client_ec_no AS EC_NO,
            mpd4_distr_id AS distr_id
        FROM
            match_pref_districts4) AS co_prefs
        ON
            mcs.mcs_client_ec_no = co_prefs.EC_NO AND co_prefs.distr_id = mpd4.mpd4_curr_distr_id
        ORDER BY
            mcs.mcs_id");
        
        	}catch (Exception $e){
        			echo 'Failed to retrieve matched districts4';
        			exit;
        
        	}
	
	$matched_districts4 = $results_pref_districts4->fetchAll(PDO::FETCH_ASSOC);
	
	try{
	$results_pref_locations1 = $db->query("SELECT
    mpl.mpl_client_ec_no,
    mcs.mcs_client_ec_no,
    co_prefs.tab,
    co_prefs.loc_id
        FROM
            match_current_schools AS mcs
        INNER JOIN match_pref_locations AS mpl
        ON
            mcs.mcs_loc_id = mpl.mpl_loc_id AND mpl.mpl_client_level_taught = mcs.mcs_client_level_taught AND(
                (
                    mpl.mpl_sub1_id = mcs.mcs_sub1_id
                ) OR(
                    mpl.mpl_sub1_id = mcs.mcs_sub2_id
                ) OR(
                    mpl.mpl_sub2_id = mcs.mcs_sub1_id
                ) OR(
                    mpl.mpl_sub2_id = mcs.mcs_sub2_id
                )
            ) AND mpl.mpl_status = 'A' AND mcs.mcs_status = 'A'
        INNER JOIN
        (
            SELECT
                'match_pref_locations' AS tab,
                mpl_client_ec_no AS EC_NO,
                mpl_loc_id AS loc_id
            FROM
                match_pref_locations
            UNION ALL
            SELECT
                'match_pref_locations2' AS tab,
                mpl2_client_ec_no AS EC_NO,
                mpl2_loc_id AS loc_id
            FROM
                match_pref_locations2
            UNION ALL
            SELECT
                'match_pref_locations3' AS tab,
                mpl3_client_ec_no AS EC_NO,
                mpl3_loc_id AS loc_id
            FROM
                match_pref_locations3
            UNION ALL
            SELECT
                'match_pref_locations4' AS tab,
                mpl4_client_ec_no AS EC_NO,
                mpl4_loc_id AS loc_id
            FROM
                match_pref_locations4
            UNION ALL
            SELECT
                'match_pref_locations5' AS tab,
                mpl5_client_ec_no AS EC_NO,
                mpl5_loc_id AS loc_id
            FROM
                match_pref_locations5) AS co_prefs
            ON
                mcs.mcs_client_ec_no = co_prefs.EC_NO AND co_prefs.loc_id = mpl.mpl_curr_loc_id
            ORDER BY
                mcs.mcs_id");
            
            	}catch (Exception $e){
            			echo 'Failed to retrieve matched locations1';
            			exit;
        
        	}
	
	$matched_locations1 = $results_pref_locations1->fetchAll(PDO::FETCH_ASSOC);
	
	try{
	$results_pref_locations2 = $db->query("SELECT
    mpl2.mpl2_client_ec_no,
    mcs.mcs_client_ec_no,
    co_prefs.tab,
    co_prefs.loc_id
        FROM
            match_current_schools AS mcs
        INNER JOIN match_pref_locations2 AS mpl2
        ON
            mcs.mcs_loc_id = mpl2.mpl2_loc_id AND mpl2.mpl2_client_level_taught = mcs.mcs_client_level_taught AND(
                (
                    mpl2.mpl2_sub1_id = mcs.mcs_sub1_id
                ) OR(
                    mpl2.mpl2_sub1_id = mcs.mcs_sub2_id
                ) OR(
                    mpl2.mpl2_sub2_id = mcs.mcs_sub1_id
                ) OR(
                    mpl2.mpl2_sub2_id = mcs.mcs_sub2_id
                )
            ) AND mpl2.mpl2_status = 'A' AND mcs.mcs_status = 'A'
        INNER JOIN
        (
            SELECT
                'match_pref_locations' AS tab,
                mpl_client_ec_no AS EC_NO,
                mpl_loc_id AS loc_id
            FROM
                match_pref_locations
            UNION ALL
        SELECT
            'match_pref_locations2' AS tab,
            mpl2_client_ec_no AS EC_NO,
            mpl2_loc_id AS loc_id
        FROM
            match_pref_locations2
        UNION ALL
        SELECT
            'match_pref_locations3' AS tab,
            mpl3_client_ec_no AS EC_NO,
            mpl3_loc_id AS loc_id
        FROM
            match_pref_locations3
        UNION ALL
        SELECT
            'match_pref_locations4' AS tab,
            mpl4_client_ec_no AS EC_NO,
            mpl4_loc_id AS loc_id
        FROM
            match_pref_locations4
        UNION ALL
        SELECT
            'match_pref_locations5' AS tab,
            mpl5_client_ec_no AS EC_NO,
            mpl5_loc_id AS loc_id
        FROM
            match_pref_locations5) AS co_prefs
        ON
            mcs.mcs_client_ec_no = co_prefs.EC_NO AND co_prefs.loc_id = mpl2.mpl2_curr_loc_id
        ORDER BY
            mcs.mcs_id");
        
        	}catch (Exception $e){
        			echo 'Failed to retrieve matched locations2';
        			exit;
        
        	}
	
	$matched_locations2 = $results_pref_locations2->fetchAll(PDO::FETCH_ASSOC);
	
	try{
	$results_pref_locations3 = $db->query("SELECT
    mpl3.mpl3_client_ec_no,
    mcs.mcs_client_ec_no,
    co_prefs.tab,
    co_prefs.loc_id
        FROM
            match_current_schools AS mcs
        INNER JOIN match_pref_locations3 AS mpl3
        ON
            mcs.mcs_loc_id = mpl3.mpl3_loc_id AND mpl3.mpl3_client_level_taught = mcs.mcs_client_level_taught AND(
                (
                    mpl3.mpl3_sub1_id = mcs.mcs_sub1_id
                ) OR(
                    mpl3.mpl3_sub1_id = mcs.mcs_sub2_id
                ) OR(
                    mpl3.mpl3_sub2_id = mcs.mcs_sub1_id
                ) OR(
                    mpl3.mpl3_sub2_id = mcs.mcs_sub2_id
                )
            ) AND mpl3.mpl3_status = 'A' AND mcs.mcs_status = 'A'
        INNER JOIN
        (
            SELECT
                'match_pref_locations' AS tab,
                mpl_client_ec_no AS EC_NO,
                mpl_loc_id AS loc_id
            FROM
                match_pref_locations
            UNION ALL
        SELECT
            'match_pref_locations2' AS tab,
            mpl2_client_ec_no AS EC_NO,
            mpl2_loc_id AS loc_id
        FROM
            match_pref_locations2
        UNION ALL
        SELECT
            'match_pref_locations3' AS tab,
            mpl3_client_ec_no AS EC_NO,
            mpl3_loc_id AS loc_id
        FROM
            match_pref_locations3
        UNION ALL
        SELECT
            'match_pref_locations4' AS tab,
            mpl4_client_ec_no AS EC_NO,
            mpl4_loc_id AS loc_id
        FROM
            match_pref_locations4
        UNION ALL
        SELECT
            'match_pref_locations5' AS tab,
            mpl5_client_ec_no AS EC_NO,
            mpl5_loc_id AS loc_id
        FROM
            match_pref_locations5) AS co_prefs
        ON
            mcs.mcs_client_ec_no = co_prefs.EC_NO AND co_prefs.loc_id = mpl3.mpl3_curr_loc_id
        ORDER BY
            mcs.mcs_id");
        
        	}catch (Exception $e){
        			echo 'Failed to retrieve matched locations3';
        			exit;
        
        	}
	
	$matched_locations3 = $results_pref_locations3->fetchAll(PDO::FETCH_ASSOC);
	
	try{
	$results_pref_locations4 = $db->query("SELECT
    mpl4.mpl4_client_ec_no,
    mcs.mcs_client_ec_no,
    co_prefs.tab,
    co_prefs.loc_id
        FROM
            match_current_schools AS mcs
        INNER JOIN match_pref_locations4 AS mpl4
        ON
            mcs.mcs_loc_id = mpl4.mpl4_loc_id AND mpl4.mpl4_client_level_taught = mcs.mcs_client_level_taught AND(
                (
                    mpl4.mpl4_sub1_id = mcs.mcs_sub1_id
                ) OR(
                    mpl4.mpl4_sub1_id = mcs.mcs_sub2_id
                ) OR(
                    mpl4.mpl4_sub2_id = mcs.mcs_sub1_id
                ) OR(
                    mpl4.mpl4_sub2_id = mcs.mcs_sub2_id
                )
            ) AND mpl4.mpl4_status = 'A' AND mcs.mcs_status = 'A'
        INNER JOIN
        (
            SELECT
                'match_pref_locations' AS tab,
                mpl_client_ec_no AS EC_NO,
                mpl_loc_id AS loc_id
            FROM
                match_pref_locations
            UNION ALL
        SELECT
            'match_pref_locations2' AS tab,
            mpl2_client_ec_no AS EC_NO,
            mpl2_loc_id AS loc_id
        FROM
            match_pref_locations2
        UNION ALL
        SELECT
            'match_pref_locations3' AS tab,
            mpl3_client_ec_no AS EC_NO,
            mpl3_loc_id AS loc_id
        FROM
            match_pref_locations3
        UNION ALL
        SELECT
            'match_pref_locations4' AS tab,
            mpl4_client_ec_no AS EC_NO,
            mpl4_loc_id AS loc_id
        FROM
            match_pref_locations4
        UNION ALL
        SELECT
            'match_pref_locations5' AS tab,
            mpl5_client_ec_no AS EC_NO,
            mpl5_loc_id AS loc_id
        FROM
            match_pref_locations5) AS co_prefs
        ON
            mcs.mcs_client_ec_no = co_prefs.EC_NO AND co_prefs.loc_id = mpl4.mpl4_curr_loc_id
        ORDER BY
            mcs.mcs_id");
        
        	}catch (Exception $e){
        			echo 'Failed to retrieve matched locations4';
        			exit;
        
        	}
	
	$matched_locations4 = $results_pref_locations4->fetchAll(PDO::FETCH_ASSOC);
	
	try{
	$results_pref_locations5 = $db->query("SELECT
    mpl5.mpl5_client_ec_no,
    mcs.mcs_client_ec_no,
    co_prefs.tab,
    co_prefs.loc_id
        FROM
            match_current_schools AS mcs
        INNER JOIN match_pref_locations5 AS mpl5
        ON
            mcs.mcs_loc_id = mpl5.mpl5_loc_id AND mpl5.mpl5_client_level_taught = mcs.mcs_client_level_taught AND(
                (
                    mpl5.mpl5_sub1_id = mcs.mcs_sub1_id
                ) OR(
                    mpl5.mpl5_sub1_id = mcs.mcs_sub2_id
                ) OR(
                    mpl5.mpl5_sub2_id = mcs.mcs_sub1_id
                ) OR(
                    mpl5.mpl5_sub2_id = mcs.mcs_sub2_id
                )
            ) AND mpl5.mpl5_status = 'A' AND mcs.mcs_status = 'A'
        INNER JOIN
        (
            SELECT
                'match_pref_locations' AS tab,
                mpl_client_ec_no AS EC_NO,
                mpl_loc_id AS loc_id
            FROM
                match_pref_locations
            UNION ALL
        SELECT
            'match_pref_locations2' AS tab,
            mpl2_client_ec_no AS EC_NO,
            mpl2_loc_id AS loc_id
        FROM
            match_pref_locations2
        UNION ALL
        SELECT
            'match_pref_locations3' AS tab,
            mpl3_client_ec_no AS EC_NO,
            mpl3_loc_id AS loc_id
        FROM
            match_pref_locations3
        UNION ALL
        SELECT
            'match_pref_locations4' AS tab,
            mpl4_client_ec_no AS EC_NO,
            mpl4_loc_id AS loc_id
        FROM
            match_pref_locations4
        UNION ALL
        SELECT
            'match_pref_locations5' AS tab,
            mpl5_client_ec_no AS EC_NO,
            mpl5_loc_id AS loc_id
        FROM
            match_pref_locations5) AS co_prefs
        ON
            mcs.mcs_client_ec_no = co_prefs.EC_NO AND co_prefs.loc_id = mpl5.mpl5_curr_loc_id
        ORDER BY
            mcs.mcs_id");
        
        	}catch (Exception $e){
        			echo 'Failed to retrieve matched locations5';
        			exit;
        
        	}
	
	$matched_locations5 = $results_pref_locations5->fetchAll(PDO::FETCH_ASSOC);
	
	*/
	//below query extracts lonely dependants from all dependant client tables
	try{
	$results_lonelies = $db->query("SELECT
                                        mcs_client_ec_no
                                    FROM
                                        match_current_schools
                                    WHERE
                                        mcs_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mpd_client_ec_no
                                    FROM
                                        match_pref_districts
                                    WHERE
                                        mpd_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mpd2_client_ec_no
                                    FROM
                                        match_pref_districts2
                                    WHERE
                                        mpd2_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mpd3_client_ec_no
                                    FROM
                                        match_pref_districts3
                                    WHERE
                                        mpd3_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mpd4_client_ec_no
                                    FROM
                                        match_pref_districts4
                                    WHERE
                                        mpd4_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mpp_client_ec_no
                                    FROM
                                        match_pref_provinces
                                    WHERE
                                        mpp_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mpp2_client_ec_no
                                    FROM
                                        match_pref_provinces2
                                    WHERE
                                        mpp2_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mpt_client_ec_no
                                    FROM
                                        match_pref_towns
                                    WHERE
                                        mpt_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mpt2_client_ec_no
                                    FROM
                                        match_pref_towns2
                                    WHERE
                                        mpt2_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mpt3_client_ec_no
                                    FROM
                                        match_pref_towns3
                                    WHERE
                                        mpt3_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mpl_client_ec_no
                                    FROM
                                        match_pref_locations
                                    WHERE
                                        mpl_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mpl2_client_ec_no
                                    FROM
                                        match_pref_locations2
                                    WHERE
                                        mpl2_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mpl3_client_ec_no
                                    FROM
                                        match_pref_locations3
                                    WHERE
                                        mpl3_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mpl4_client_ec_no
                                    FROM
                                        match_pref_locations4
                                    WHERE
                                        mpl4_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mpl5_client_ec_no
                                    FROM
                                        match_pref_locations5
                                    WHERE
                                        mpl5_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mps_client_ec_no
                                    FROM
                                        match_pref_schools
                                    WHERE
                                        mps_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mps2_client_ec_no
                                    FROM
                                        match_pref_schools2
                                    WHERE
                                        mps2_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mps3_client_ec_no
                                    FROM
                                        match_pref_schools3
                                    WHERE
                                        mps3_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mps4_client_ec_no
                                    FROM
                                        match_pref_schools4
                                    WHERE
                                        mps4_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mps5_client_ec_no
                                    FROM
                                        match_pref_schools5
                                    WHERE
                                        mps5_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mps6_client_ec_no
                                    FROM
                                        match_pref_schools6
                                    WHERE
                                        mps6_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mps7_client_ec_no
                                    FROM
                                        match_pref_schools7
                                    WHERE
                                        mps7_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mps8_client_ec_no
                                    FROM
                                        match_pref_schools8
                                    WHERE
                                        mps8_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mps9_client_ec_no
                                    FROM
                                        match_pref_schools9
                                    WHERE
                                        mps9_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )
                                    UNION
                                    SELECT
                                        mps10_client_ec_no
                                    FROM
                                        match_pref_schools10
                                    WHERE
                                        mps10_client_ec_no NOT IN(
                                        SELECT
                                            client_ec_no
                                        FROM
                                            clients
                                    )");
        
	}catch (Exception $e){
			echo 'Failed to retrieve lonelies';
			exit;

	}
	
	$lonelies = $results_lonelies->fetchAll(PDO::FETCH_ASSOC);
	
?>
