<?php
session_start(); 
  if(isset($_SESSION['message'])){
     unset($_SESSION['message']);
  }
/* Retrieves the random number from the user profile and SMSs for login */
require 'inc/connection.php';
 
  if($_SERVER['REQUEST_METHOD'] == 'POST') 
  {
    $ecNumber = $_POST['ec_number'];
    //query the data for the client to be send a message
        try{
          $result = $db->query("SELECT * FROM clients WHERE client_ec_no = '$ecNumber'");

      }catch (Exception $e){
          echo 'Failed to retrieve mobile number';
          exit;

      }

      //client data formatted as an associative array
      $client = $result->fetchAll(PDO::FETCH_ASSOC);

        if(count($client) == 0 ) // User doesn't exist
           { 
            $_SESSION['message'] = "Client with EC Number '$ecNumber' doesn't exist!";
           }
            else { // User exists (num_rows != 0)

            $ecNumber = $client[0]['client_ec_no'];
            $mobile = $client[0]['client_mobile_no'];
            //$mobile = '263'.substr($client[0]['client_mobile_no'], 1);
            $OTP = $client[0]['client_ranum'];
            $sender = 'SwopMatch';
            $body1 = 'Here is your One Time Password (OTP). Please use the number '.$OTP.' as your password and type in a NEW password on your profile password field and submit!';
            $body = urlencode($body1);

            // Session message to display on success
            $_SESSION['message'] = "Please check an SMS on the number you registered with us for a One Time Password (OTP). Login with that OTP (as your password) and type in a new password in the password field and save your profile!";
            /*
            $params= ['Username'=>'patch_remote', 'Recipients'=>$mobile, 'Body'=>$body];
            
            $defaults = array(
            CURLOPT_URL => 'https://www.txt.co.zw/Remote/SendMessage',
            CURLOPT_POST => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_POSTFIELDS => http_build_query($params)
             );
            */
                $username = "patch";
                $password = "patchit";
                //$url = "http://etext.co.zw/sendsms.php?user=".$username."&password=".$password."&mobile=".$mobile."&senderid=".$sender."&message=".$body;
                $url = "http://api.bluedotsms.com/api/mt/SendSMS?user=".$username."&password=".$password."&senderid=".$sender."&channel=Normal&DCS=0&flashsms=0&number=".$mobile."&text=".$body;
                //$url = "https://www.txt.co.zw/Remote/SendMessage?username=patch_remote&Recipients=".$mobile."&body=".$body;
             
      }    
    }

$pageTitle = 'SwopMatch Handler | Password Reset';
include("inc/header.php");
?>
<div class="row">
    <div class="col-1">
    </div>
        <div class="col-10">
            <div class="container">
                <div class="jumbotron my-1 py-3">
                    <form action="forgot.php" method="post">
	                    <h1 class="text-center display-5"><b>Reset Your Password</b></h1>
                        <div class="row">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-3 pr-0">
                                <h5><em>Client A/C #:</em></h5>
                                </div>
                                    <div class="form-group col-md-7">
                                        <input required autocomplete="off" name="ec_number" placeholder= "Your EC Number" class="form-control"/>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                            <?php if(!empty($mobile)){
                                $ch = curl_init();
                                //curl_setopt_array($ch, $defaults);
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_exec($ch);
                                
                                /*
                                $response = curl_exec($ch);
                                $err = curl_error($ch);
                                
                                if ($err) {
                                    echo $err;
                                } else {
                                    echo $response;
                                }*/
                                curl_close($ch);
                            } ?>     
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="form-group col-md-6 pr-md-1">
                                            <a href="index.php" id="pwd-reset" class="btn btn-primary btn-lg btn-block" role="button" aria-pressed="true">Back To Login</a>
                                        </div>
                                        <div class="form-group col-md-6 pl-md-1">
                                            <button type="submit" id="pwd-sub" class="btn btn-primary btn-lg btn-block">Reset</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1"></div>
                              </div>
                    </form>
                </div>
            </div>
        </div>
    <div class="col-1"></div>
</div>

<?php include("inc/footer.php"); ?>