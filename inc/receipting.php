<?php
    include_once 'functions.php';

      echo 'Now receipting for clients who have paid!'.'<br>';
      
            echo '<pre>';
             print_r($unreceipted);
            echo '</pre>';
       
          //the below loops and updates matched_clients table with receipt numbers for any payments made and notifies the client their receipt number by SMS
          foreach($unreceipted as $key=>$value){
              
              try{$results = $db->query("SELECT MAX(matched_receipt_no) AS last_receipt FROM matched_clients");
              
            	}catch (Exception $e){
            			echo 'Failed to retrieve last receipt number';
            			exit;
            
            	}
            	$last = $results->fetchAll(PDO::FETCH_ASSOC);
            	$last_receipt = $last[0]['last_receipt'];
            	$receipt_no = str_pad($last_receipt + 1, 6, "0", STR_PAD_LEFT);
            	
            $sql = 'UPDATE matched_clients 
                    SET  matched_status = "RP", matched_receipt_no ='."$last_receipt + 1".' 
                    WHERE matched_ec_no = ?';

              try {
                $results = $db->prepare($sql);
                $results->bindValue(1, $value['matched_ec_no'], PDO::PARAM_STR);
                $results->execute();
              } catch (Exception $e) {
                echo "Error!: " . $e->getMessage() . "<br />";
                return false;
              }
              
             $name = $value['client_first_name'];
             $mobile = $value['client_mobile_no'];
             $message = $name.' thank you very much for the payment you made. Your receipt number is SM'.$receipt_no.'.
             Together we will surely get there..';
                  
            $body = urlencode($message);
            $sender = 'SwopMatch';
            $user = "patch";
            $pass = "patchit";
            $url = "http://api.bluedotsms.com/api/mt/SendSMS?user=".$user."&password=".$pass."&senderid=".$sender."&channel=Normal&DCS=0&flashsms=0&number=".$mobile."&text=".$body;
            
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_exec($ch);
                curl_close($ch);
            }
  
      ?>