<?php
ob_end_flush();
ob_start();
session_start();

include 'inc/functions.php';
   
if(empty($_SESSION['logged_status']) ||
    ($_SESSION['logged_status'] != 'SU' &&
    $_SESSION['logged_status'] != 'AD')){ //checks if the administrator is logged in  

    $error_message = "Whoa! You do not have rights to enter a payment!";
}

    //query the data for the client to be send a message
    $ecNumber = $_SESSION['ec_number'];
    try{
      $result = $db->query("SELECT * FROM clients WHERE client_ec_no = '$ecNumber'");

      }catch (Exception $e){
          echo 'Failed to retrieve OTP';
          exit;

      }
      
    //client data formatted as an associative array
    $client = $result->fetchAll(PDO::FETCH_ASSOC);
    
    //if client is already logged in, sent OTP
    $mobile = $client[0]['client_mobile_no'];
    //$mobile = '263'.substr($client[0]['client_mobile_no'], 1);
    $OTP = $client[0]['client_ranum'];
    $sender = 'SwopMatch';
    $body1 = 'Here is your One Time Password (OTP). Please use the number '.$OTP;
    
    $body = urlencode($body1);
    
        $username = "patch";
        $password = "patchit";
        //$url = "http://etext.co.zw/sendsms.php?user=".$username."&password=".$password."&mobile=".$mobile."&senderid=".$sender."&message=".$body;
        $url = "http://api.bluedotsms.com/api/mt/SendSMS?user=".$username."&password=".$password."&senderid=".$sender."&channel=Normal&DCS=0&flashsms=0&number=".$mobile."&text=".$body;
        
        if(!empty($mobile)){
            $ch = curl_init();
            //curl_setopt_array($ch, $defaults);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_exec($ch);
            curl_close($ch);
    } 
                

// Make sure the form is being submitted with method="post"
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

    $ecnum = filter_input(INPUT_POST, 'ecnum', FILTER_SANITIZE_STRING);
    $new_password = password_hash($_POST['newpass'], PASSWORD_BCRYPT);
    $clientRanum = mt_rand(11111, 99999); //random number
    
        //check if OTP matches
        if($_POST['otp'] != $_POST['prev_otp']){
           $error_message = "You have input a wrong OTP!"; 
        }
        
        //proceed with updating if there is no error
        if(empty($error_message)){
            $sql = "UPDATE clients SET client_password='$new_password', client_ranum = '$clientRanum' WHERE client_ec_no='$ecnum'";
            
            //redirect to client area
            if ( $db->query($sql) ) {
                header("location: Account_manage.php?id=$ecNumber");
            }else{
                $error_message = "Your password has been reset successfully!";
            }
        }
        
	    //redirects to the admin client area
	    if(empty($error_message)){
            header("location: Account_manage.php?id=$ecNumber");
	    }
    }

$pageTitle = 'SwopMatch Handler | Change Admin Password';

include 'inc/header.php';


        echo '<pre>';
        print_r($client);
        echo '</pre>';
        
        echo '<pre>';
        print_r($ecNumber);
        echo '</pre>';

?>

    <div class="form">
  <?php if(!empty($error_message)){
            echo '<div class = "hidden message">';
            echo '<span class = "err">'.$error_message.'</span>';
            echo '</div>';
    	} ?>

          <h1>Submit Your OTP</h1>
          
          <form action="reset_confirm.php" method="post">
              
          <div class="input-w">
            <input type="hidden"required name="newpassword"/>
          </div>
              
          <div class="input-w">
            <label style = "width: 172px; font-weight: lighter";>
              One Time Pin(OTP)<span class="req">*</span>
            </label>
            <input type="number"required name="otp" autocomplete="off" style = "width: 50%"/>
          </div>
              
          <div class="input-w">
            <input type="hidden" name="prev_otp" value = "<?php echo $OTP; ?>" autocomplete="off" style = "width: 50%"/>
          </div>
          
          <input type="hidden" name="ecnum" value="<?php echo $_SESSION['ec_number']; ?>"/>
          
          <input type="hidden" name="newpass" value="<?php echo $_SESSION['newpassword']; ?>"/>
              
          <button class="sub-reset"/>Apply</button>
          
          </form>

    </div>

<?php include("inc/footer.php"); ?>