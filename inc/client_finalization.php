<?php
    include_once 'functions.php';

      echo 'Now processing and sending final details to the client!'.'<br>';
      
      
        //list or clients finalized
        
            echo '<pre>';
             print_r($final);
            echo '</pre>';
          
          //the below loops through all successfully matched clients and sends them final details of their matches
          foreach($final as $final){
                  
            $name = $final['first_name'];
              
            //check whether the mobile # is a netone number and declare the mobile # variable accordingly
            if(substr($final['mobile_no'], 0, 3) === '071'){
                $mobile = '263'.substr($final['mobile_no'], 1);
            }else{
                $mobile = $final['mobile_no'];
            }
            
              $co_first_name = $final['co_first_name'];
              $co_last_name = $final['co_last_name'];
              $co_mobile_no = $final['co_mobile_no'];
              $message = $name.' it has been our pleasure walking the journey together! The details of your match are: Name is '.$co_first_name.' '.$co_last_name.' & Mobile # is '.$co_mobile_no.'. Your details have also been send to them and we trust that you will successfully finalize the other relevant procedures.
              Grace abound.';
                  
            $body = urlencode($message);
            $sender = 'SwopMatch';
            
            //check whether the mobile # is a netone number and declare the username variable accordingly
            if(substr($final['mobile_no'], 0, 3) === '071'){
                $user = "263775263810";
            }else{
                $user = "patch";
            }
            
            $pass = "patchit";
            
            //if mobile # is for netone declare url for etxt else declare for bluedots
            if(substr($final['mobile_no'], 0, 3) === '071'){
                $url = "http://etext.co.zw/sendsms.php?user=".$user."&password=".$pass."&mobile=".$mobile."&senderid=".$sender."&message=".$body;
            }else{
                $url = "http://api.bluedotsms.com/api/mt/SendSMS?user=".$user."&password=".$pass."&senderid=".$sender."&channel=Normal&DCS=0&flashsms=0&number=".$mobile."&text=".$body;
            }
            
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_exec($ch);
                curl_close($ch);
                
            //the below updates the clients table for each finalized client and sets client_status to 'Matched and Finalized (MF)'
              $sql = 'UPDATE clients
                        SET client_status = "MF"
                        WHERE client_ec_no = ?';

                      try {
                        $results = $db->prepare($sql);
                        $results->bindValue(1, $final['matched_ec_no'], PDO::PARAM_STR);
                        $results->execute();
                      } catch (Exception $e) {
                        echo "Error!: " . $e->getMessage() . "<br />";
                        return false;
                      }
                      
            //the below updates the match_current_schools table for each finalized client and sets mcs_status to 'Matched and Finalized (MF)'
              $sql = 'UPDATE match_current_schools
                        SET mcs_status = "MF"
                        WHERE mcs_client_ec_no = ?';

                      try {
                        $results = $db->prepare($sql);
                        $results->bindValue(1, $final['matched_ec_no'], PDO::PARAM_STR);
                        $results->execute();
                      } catch (Exception $e) {
                        echo "Error!: " . $e->getMessage() . "<br />";
                        return false;
                      }
                      
            //the below updates the match_clients table for each finalized client and sets matched_status to 'Matched and Finalized (MF)'
              $sql = 'UPDATE matched_clients
                        SET matched_status = "MF"
                        WHERE matched_ec_no = ?';

                      try {
                        $results = $db->prepare($sql);
                        $results->bindValue(1, $final['matched_ec_no'], PDO::PARAM_STR);
                        $results->execute();
                      } catch (Exception $e) {
                        echo "Error!: " . $e->getMessage() . "<br />";
                        return false;
                      }
                
            }
  
      ?>