<?php
include 'connection.php';
include 'functions.php';
 
date_default_timezone_set('Africa/Harare');

class prep_match_data{
 //this class handles the matching process together with updating matched records 
 
  var $db_table;  //database table to be updated
  var $t_status;  //table 'status' column
  var $dup_keys1;  //exclusively holds the 1st ec# column when name for the current preferred option category checking that one 'current category' is matched to one 'preferred category'
  var $dup_keys2;  //exclusively holds the 2nd ec# column when name for the current preferred option category checking that one 'current category' is matched to one 'preferred category'
  var $matched = [];  //holds an array of matched categories cleared of all duplicates
  var $arr_key = [];  //holds an array of combinations between matched current categories and the option number used to determine which tables to update
  var $mdate;
  var $dateTime;
  var $expDate;
   
//the below function ensures that every current category is only matched to a single match  
//the variable $raw_match is the unrefined array of matched combinations from an SQL query in functions.php
 public function get_match(array $raw_match, $dup_keys1, $dup_keys2){
    foreach ($raw_match as $key => $value) {
      if ((isset($matched[$value[$dup_keys1]])) ||
          (isset($matched[$value[$dup_keys2]]))
         )
          continue;
      $matched[$value[$dup_keys1]] = $value;
      
      $this->matched[] = $value;
      
    }
  foreach ($this->matched as $key => $value) {
    //the associative array formed from the above will have both numeric and string keys
    //the below code removes all string keys    
    if (!is_int($key)) {
        unset($this->matched[$key]);
    }
  }
  return count($this->matched);
 }
 
      //the below function is used to deactivate all other categories options once a match has been found
       public function update_tables(){
         include ('connection.php');
         
         $matched_sliced = []; //this will hold an array of matched categories excluding the client ec#
      foreach($this->matched as $key=>$value){
         unset($value['client_ec_no']);
        //the below creates an array with keys used to determine the tables to update
        $this->arr_key = array_keys($value);
        $matched_sliced[] = $value;
      }
      
      echo '<pre>';
      //print_r($this->arr_key);
      echo '</pre>';
      //the below loops through the matched options
      foreach($matched_sliced as $key=>$value){
        /*
(
    [0] => Array
        (
            [mps_client_ec_no] => REG5
            [mcs_client_ec_no] => GL98888
            [tab] => match_pref_towns
            [pref_id] => 238
        )

)
        */
        foreach($value as $key_inner=>$ec_value){
        
       //the $name variable holds the table client ec # column used to determine the table to update 
          $name = $this->arr_key[0];
         
       //the below determines the array of preferred categories options tables containing matched value options to be deactivated
       if(!empty($name)/* = ('mps_client_ec_no'||
                    'mps2_client_ec_no'||
                    'mps3_client_ec_no'||
                    'mps4_client_ec_no'||
                    'mps5_client_ec_no'||
                    'mps6_client_ec_no'||
                    'mps7_client_ec_no'||
                    'mps8_client_ec_no'||
                    'mps9_client_ec_no'||
                    'mps10_client_ec_no'||
                    'mpp_client_ec_no'||
                    'mpp2_client_ec_no'||
                    'mpd_client_ec_no'||
                    'mpd2_client_ec_no'||
                    'mpd3_client_ec_no'||
                    'mpd4_client_ec_no'||
                    'mpt_client_ec_no'||
                    'mpt2_client_ec_no'||
                    'mpt3_client_ec_no'||
                    'mpl_client_ec_no'||
                    'mpl2_client_ec_no'||
                    'mpl3_client_ec_no'||
                    'mpl4_client_ec_no'||
                    'mpl5_client_ec_no')*/){
                   
          //$d_tables holds tables to be marked with "D" meaning 'Deactivated'
             $d_tables = array(array('match_pref_schools', 'mps_status', 'mps_client_ec_no'),
                            array('match_pref_schools2', 'mps2_status', 'mps2_client_ec_no'),
                            array('match_pref_schools3', 'mps3_status', 'mps3_client_ec_no'),
                            array('match_pref_schools4', 'mps4_status', 'mps4_client_ec_no'),
                            array('match_pref_schools5', 'mps5_status', 'mps5_client_ec_no'),
                            array('match_pref_schools6', 'mps6_status', 'mps6_client_ec_no'),
                            array('match_pref_schools7', 'mps7_status', 'mps7_client_ec_no'),
                            array('match_pref_schools8', 'mps8_status', 'mps8_client_ec_no'),
                            array('match_pref_schools9', 'mps9_status', 'mps9_client_ec_no'),
                            array('match_pref_schools10', 'mps10_status', 'mps10_client_ec_no'),
                            array('match_pref_provinces', 'mpp_status', 'mpp_client_ec_no'),
                            array('match_pref_provinces2', 'mpp2_status', 'mpp2_client_ec_no'),
                            array('match_pref_towns', 'mpt_status', 'mpt_client_ec_no'),
                            array('match_pref_towns2', 'mpt2_status', 'mpt2_client_ec_no'),
                            array('match_pref_towns3', 'mpt3_status', 'mpt3_client_ec_no'),
                            array('match_pref_locations', 'mpl_status', 'mpl_client_ec_no'),
                            array('match_pref_locations2', 'mpl2_status', 'mpl2_client_ec_no'),
                            array('match_pref_locations3', 'mpl3_status', 'mpl3_client_ec_no'),
                            array('match_pref_locations4', 'mpl4_status', 'mpl4_client_ec_no'),
                            array('match_pref_locations5', 'mpl5_status', 'mpl5_client_ec_no'),
                            array('match_pref_districts', 'mpd_status', 'mpd_client_ec_no'),
                            array('match_pref_districts2', 'mpd2_status', 'mpd2_client_ec_no'),
                            array('match_pref_districts3', 'mpd3_status', 'mpd3_client_ec_no'),
                            array('match_pref_districts4', 'mpd4_status', 'mpd4_client_ec_no'));
                            
            //$r_tables holds tables to be marked with "RN" meaning 'Reserved'
             $r_tables = array('match_current_schools', 'mcs_status', 'mcs_client_ec_no');
       }
       
          //the below loops and updates table options with values to be deactivated for each match value
          foreach($d_tables as $key=>$d_value_key){
            $sql = 'UPDATE '.$d_value_key[0].'
                          SET '.$d_value_key[1].' = "D"
                          WHERE '.$d_value_key[2].' = ?';

              try {
                $results = $db->prepare($sql);
                $results->bindValue(1, $ec_value, PDO::PARAM_STR);
                $results->execute();
              } catch (Exception $e) {
                echo "Error!: " . $e->getMessage() . "<br />";
                return false;
              }
            }
            
        //the below loops and updates table options with values to be reserved for each match value
           foreach($r_tables as $col_key=>$col_value){
                $sql = 'UPDATE '.$r_tables[0].'
                              SET '.$r_tables[1].' = "RN"
                              WHERE '.$r_tables[2].' = ?';
          } 
            try {
              $results = $db->prepare($sql);
              $results->bindValue(1, $ec_value, PDO::PARAM_STR);
              $results->execute();
            } catch (Exception $e) {
              echo "Error!: " . $e->getMessage() . "<br />";
              return false;
            }
          
        
        //the below updates the clients table for each matched value options and reserves the option
          $sql = 'UPDATE clients
                    SET client_status = "RN"
                    WHERE client_ec_no = ?';

          try {
            $results = $db->prepare($sql);
            $results->bindValue(1, $ec_value, PDO::PARAM_STR);
            $results->execute();
          } catch (Exception $e) {
            echo "Error!: " . $e->getMessage() . "<br />";
            return false;
          }
        }
      }
     }
     
       //the below function is used to insert matched records in the table matched_clients
       public function create_matched_recs(){
         include ('connection.php');
        
      date_default_timezone_set('Africa/Harare');
      
      $mdate = date('d-m-Y H:i:s');

      $dateTime = new DateTime($mdate);
      $dateTime = $dateTime->modify('+7 days');
      $expDate = $dateTime->format("d-m-Y H:i:s");
      
        //remove hours, minutes, and seconds
        $matched_raw_exp_date = substr($expDate, 0, 10);
        $raw_d = date_create_from_format('d-m-Y', $matched_raw_exp_date);
        //make the format more readable
        $MExpDate = date_format($raw_d, 'd-M-Y');
         
         $matched_sliced = []; //this will hold an array of matched categories excluding the client ec#
      foreach($this->matched as $key=>$value){
         unset($value['client_ec_no']);
        //the below creates an array with keys used to determine the tables to update
        $this->arr_key = array_keys($value);
        $matched_sliced[] = $value;
      }
      
      //the below loops through the matched options
      foreach($matched_sliced as $key=>$value){
       $Opt_tab_ec = array_keys($value);
/*
(
    [0] => Array
        (
            [mps_client_ec_no] => REG5
            [mcs_client_ec_no] => GL98888
            [tab] => match_pref_towns
            [pref_id] => 238
        )

)
        */
       //the below loops and inserts a record into the matched_clients table for both clients matched
          $sql = 'INSERT INTO `matched_clients`
                            (`matched_ec_no`,
                            `matched_co_ec_no`,
                            `matched_date`,
                            `matched_res_end_time`,
                            `matched_status`)
                      VALUES (?, ?, ?, ?, ?)';

                try {
                  $results = $db->prepare($sql);
                  $results->bindValue(1, $value[$Opt_tab_ec[0]], PDO::PARAM_STR);/*
                  $results->bindValue(2, $value['mcs_school_id'], PDO::PARAM_INT);*/
                  $results->bindValue(2, $value['mcs_client_ec_no'], PDO::PARAM_STR);
                  $results->bindValue(3, $mdate, PDO::PARAM_STR);
                  $results->bindValue(4, $expDate, PDO::PARAM_STR);
                  $results->bindValue(5, 'RN', PDO::PARAM_STR);
                  $results->execute();
                } catch (Exception $e){
                  echo "Error!: " . $e->getMessage() . "<br />";
                  return false;
                }
                
          $sql = 'INSERT INTO `matched_clients`
                        (`matched_ec_no`,
                        /*`matched_sch_id`,*/
                        `matched_co_ec_no`,
                        `matched_date`,
                        `matched_res_end_time`,
                        `matched_status`)
									VALUES (?, ?, ?, ?, ?)';

            try {
              $results = $db->prepare($sql);
              $results->bindValue(1, $value['mcs_client_ec_no'], PDO::PARAM_STR);/*
              $results->bindValue(2, $value['pref_id'], PDO::PARAM_INT);*/
              $results->bindValue(2, $value[$Opt_tab_ec[0]], PDO::PARAM_STR);
              $results->bindValue(3, $mdate, PDO::PARAM_STR);
              $results->bindValue(4, $expDate, PDO::PARAM_STR);
              $results->bindValue(5, 'RN', PDO::PARAM_STR);
              $results->execute();
            } catch (Exception $e){
              echo "Error!: " . $e->getMessage() . "<br />";
              return false;
            }
            
            //query the data for the client to be send a message
              try{
                $result1 = $db->query("SELECT * FROM clients WHERE client_ec_no = "."'".$value['mcs_client_ec_no']."'");

                }catch (Exception $e){
                  echo 'Failed to retrieve mobile number';
                  exit;

            }

            //client data formatted as an associative array
            $client1 = $result1->fetchAll(PDO::FETCH_ASSOC);

            $clientFirstName1 = $client1[0]['client_first_name'];
            $ecNumber1 = $client1[0]['client_ec_no'];
            
            //check whether the mobile # is a netone number and declare the mobile # variable accordingly
            if(substr($client1[0]['client_mobile_no'], 0, 3) === '071'){
                $mobile1 = '263'.substr($client1[0]['client_mobile_no'], 1);
            }else{
                $mobile1 = $client1[0]['client_mobile_no'];
            }
            
            $sender = 'SwopMatch';
            $body0 = 'Congratulations '.$clientFirstName1.'!! A swop match has been found for you based on your registered preferences! Please pay your service fee of $20.00 using Ecocash Biller Code 204320 and your A/C # before '.$MExpDate.' and full details will be send to you. For any feedback call  +263 8644 085 304 or +263 783 228 462.';
            $body1 = urlencode($body0);
            
            /*
            $params1= ['Username'=>'patch_remote', 'Recipients'=>$mobile1, 'Body'=>$body1];
            $defaults1 = array(
            CURLOPT_URL => 'https://www.txt.co.zw/Remote/SendMessage', 
            CURLOPT_POST => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_POSTFIELDS => http_build_query($params1)
             );
             */
             
            //check whether the mobile # is a netone number and declare the username variable accordingly
            if(substr($client1[0]['client_mobile_no'], 0, 3) === '071'){
                $username = "263775263810";
            }else{
               $username = "patch"; 
            }
            
            $password = "patchit";
            
            //if mobile # is for netone declare url for etxt else declare for bluedots
            if(substr($client1[0]['client_mobile_no'], 0, 3) === '071'){
                $url1 = "http://etext.co.zw/sendsms.php?user=".$username."&password=".$password."&mobile=".$mobile1."&senderid=".$sender."&message=".$body1;
            }else{
                $url1 = "http://api.bluedotsms.com/api/mt/SendSMS?user=".$username."&password=".$password."&senderid=".$sender."&channel=Normal&DCS=0&flashsms=0&number=".$mobile1."&text=".$body1;
            }
            
            if(!empty($mobile1)){
                $ch1 = curl_init();
                //curl_setopt_array($ch1, $defaults1);
                curl_setopt($ch1, CURLOPT_URL, $url1);
                curl_exec($ch1);
                curl_close($ch1);
            }
            
            //query the data for the client to be send a message
              try{
                $result2 = $db->query("SELECT * FROM clients WHERE client_ec_no = "."'".$value[$Opt_tab_ec[0]]."'");

                }catch (Exception $e){
                  echo 'Failed to retrieve mobile number';
                  exit;

            }

            //client data formatted as an associative array
            $client2 = $result2->fetchAll(PDO::FETCH_ASSOC);

            $clientFirstName2 = $client2[0]['client_first_name'];
            $ecNumber2 = $client2[0]['client_ec_no'];
            
            //check whether the mobile # is a netone number and declare the mobile # variable accordingly
            if(substr($client2[0]['client_mobile_no'], 0, 3) === '071'){
                $mobile2 = '263'.substr($client2[0]['client_mobile_no'], 1);
            }else{
                $mobile2 = $client2[0]['client_mobile_no'];
            }
            
            $sender = 'SwopMatch';
            $body3 = 'Congratulations '.$clientFirstName2.'!! A swop match has been found for you based on your registered preferences! Please pay your service fee of $20.00 using Ecocash Biller Code 204320 and your A/C # before '.$MExpDate.' and full details will be send to you. For any feedback call  +263 8644 085 304 or +263 783 228 462.';
            $body2 = urlencode($body3);
            
            /*
            $params2= ['Username'=>'patch_remote', 'Recipients'=>$mobile2, 'Body'=>$body2];
            $defaults2 = array(
            CURLOPT_URL => 'https://www.txt.co.zw/Remote/SendMessage', 
            CURLOPT_POST => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_FRESH_CONNECT => true,
            //CURLOPT_POSTFIELDS => $params,
            CURLOPT_POSTFIELDS => http_build_query($params2)
             );
             */
             
            //check whether the mobile # is a netone number and declare the username variable accordingly
            if(substr($client2[0]['client_mobile_no'], 0, 3) === '071'){
                $username = "263775263810";
            }else{
               $username = "patch"; 
            }
            
            $password = "patchit";
            
            //if mobile # is for netone declare url for etxt else declare for bluedots
            if(substr($client2[0]['client_mobile_no'], 0, 3) === '071'){
                $url2 = "http://etext.co.zw/sendsms.php?user=".$username."&password=".$password."&mobile=".$mobile2."&senderid=".$sender."&message=".$body2;
            }else{
              $url2 = "http://api.bluedotsms.com/api/mt/SendSMS?user=".$username."&password=".$password."&senderid=".$sender."&channel=Normal&DCS=0&flashsms=0&number=".$mobile2."&text=".$body2;
            }
            
            if(!empty($mobile2)){
                $ch2 = curl_init();
                //curl_setopt_array($ch2, $defaults2);
                curl_setopt($ch2, CURLOPT_URL, $url2);
                curl_exec($ch2);
                curl_close($ch2);
            }
        }
      
      
      
      echo '<pre>';
      print_r($matched_sliced);
      echo '</pre>';
      
      echo '<pre>';
      //print_r($mobile1);
      echo '</pre>';
      
      echo '<pre>';
     //print_r($value[0]);
      echo '</pre>';
      
      echo '<pre>';
      //print_r($ec_value);
      echo '</pre>';
     }
    }

?>