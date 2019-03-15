<?php
ob_end_flush();
ob_start();
session_start();


error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('html_errors', 1);

include 'inc/functions.php';

   unset($error_message);
   
    if(empty($_SESSION['logged_status']) ||
        ($_SESSION['logged_status'] != 'SU' &&
        $_SESSION['logged_status'] != 'AD')){ //checks if the administrator is logged in  
    
        $error_message = "Whoa! You do not have rights to enter a payment!";
    }elseif(isset($_GET['id'])){ //checks if an update is intended and creates variables containing details of a client called for possible updating
    list( $client_ec_no,
          $client_first_name,
          $client_last_name,
          $client_sex,
          $client_mobile_no,
          $client_email,
          $client_status,
          $client_paid_ref,
          $client_paid_amnt) = get_paid_client(filter_input(INPUT_GET,'id', FILTER_SANITIZE_STRING));
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        //declare a variable for account number
        $client_ec_no = filter_input(INPUT_POST, 'account_no', FILTER_SANITIZE_STRING);
        
        //declare a variable for client name
        $client_first_name = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
        
        //declare a variable for account number
        $client_mobile_no = filter_input(INPUT_POST, 'cell', FILTER_SANITIZE_STRING);
	 
	    //check whether the mobile # is a netone number and declare the mobile # variable accordingly
        if(substr($client_mobile_no, 0, 3) === '071'){
    	    $mobile = '263'.substr($client_mobile_no, 1); //for use in sending sms
    	}else{
    	    $mobile = $client_mobile_no;
    	}
        
        //declare a variable for payment amount
        $pay_amnt = trim(filter_input(INPUT_POST, 'pay_amount', FILTER_SANITIZE_NUMBER_INT));
        
        //declare a variable for payment reference
        if(!empty($_POST['pay_ref'])){
        $pay_ref = trim(filter_input(INPUT_POST, 'pay_ref', FILTER_SANITIZE_STRING));
        }else{
            $error_message = 'Whoa! Payment reference cannot be empty!';
        }
        
        //declare a variable for payment reason
        $purpose = filter_input(INPUT_POST, 'pay_purpose', FILTER_SANITIZE_STRING);
        
        //declare a variable for payment date and time
        if(!empty($pay_ref)){
            //extract date portions from payment reference
            $raw_date = str_split(substr($pay_ref, 2, 6), 2);
            
            //format year
            $raw_year = date_create_from_format('y', $raw_date[0]);
            $year = date_format($raw_year, 'Y');
            
            //declare a date array
            $date_arr = array($raw_date[2], $raw_date[1], $year, substr($pay_ref, 9, 2).':'.substr($pay_ref, 11, 2).':00');
            
            //declare date and time variable from $date array
            $pay_date_in = $date_arr[0].'-'.$date_arr[1].'-'.$date_arr[2].' '.$date_arr[3];
            
            //validate date for payments
            if (validDate(substr($pay_date_in, 0, 10))) {
                $pay_date = substr($pay_date_in, 0, 10);
            }else{
                $error_message = 'Whoa! Invalid Payment Reference!';
            }
        }else{
            $error_message = 'Whoa! Payment reference cannot be empty!';
        }
    
        //if all conditions are met, update either the clients table or the matched_clients table
        if(empty($error_message)){
        matched_client_update($pay_ref, $pay_amnt, $pay_date, $client_ec_no);
        
        //send a congratulatory message to the client and redirect to the pay home page
            
        $sender = 'SwopMatch';
        
        //for bulkSMSweb only
        $webtoken = '7e51fe1d78da5158df9aeeb5b029443a';
        
        if($purpose == 'reg_fee'){
            $body0 = 'Congratulations '.$client_first_name.'!! Your registration has been approved and activated! Please always check for SMSs from SwopMatch for updates on our journey together. Where you are required to act; please act within the time limits given. Together we will surely get there..';
            
        }elseif($purpose == 'match_fee'){
            $body0 = 'Thank you '.$client_first_name.'!! Your match fee has been received. As soon as we finalize with the other part (who had the same payment deadline as you), full details of your match will be advised you. Together we will surely get there..';
        }
        
        $body = urlencode($body0);
         
        //check whether the mobile # is a netone number and declare the username variable accordingly
        if(substr($client_mobile_no, 0, 3) === '071'){
            $user = "263775263810";
        }else{
            $user = "patch";
        }
        
        $pass = "patchit";
        
        //bulkSMSweb service
        //$url  = "http://portal.bulksmsweb.com/index.php?app=ws&u=".$user."&h=".$webtoken."&op=pv&to=".$mobile."&msg=".$body;
        
        //if mobile # is for netone declare url for etxt else declare for bluedots
        if(substr($client_mobile_no, 0, 3) === '071'){
             $url = "http://etext.co.zw/sendsms.php?user=".$user."&password=".$pass."&mobile=".$mobile."&senderid=".$sender."&message=".$body;
        }else{
          $url = "http://api.bluedotsms.com/api/mt/SendSMS?user=".$user."&password=".$pass."&senderid=".$sender."&channel=Normal&DCS=0&flashsms=0&number=".$mobile."&text=".$body;
        }
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_exec($ch);
            curl_close($ch);
            
            header("location: pay.php");
        }
    }

        //set the page title  
        $pageTitle = 'SwopMatch Handler | Update Client Payment';
        		
include 'inc/header.php';

?>

<div class="row">
    <div class="col-1"></div>
        <div class="col-10">
            <div class="container">
                <div id="pay_update" class="jumbotron my-1 py-3">
                  <?php if(!empty($error_message)){
                            echo '<div class = "alert text-center">';
                            echo '<span class = "err">'.$error_message.'</span>';
                            echo '</div>';
                    	} 
                    	
                    if(!empty($_SESSION['logged_status']) ||
                        (isset($_SESSION['logged_status']) && $_SESSION['logged_status'] == 'SU') || (isset($_SESSION['logged_status']) && $_SESSION['logged_status'] == 'AD')){ ?>
                    <form action = "payments.php" method = "post">
	                    <h1 class="text-center display-5"><b>Enter Payment</b></h1>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Paynt Purpose:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <select id = "pay_purpose" name = "pay_purpose" class="form-control">
                                                    <option value="">Select One</option>
                                                    <option value="reg_fee" id="reg_fee">Registration</option>
                                                    <option value="match_fee" id="match_fee">Match Fee</option>
                                                </select>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Account No:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type="text" id="account_no" name = "account_no" value = "<?php if(!empty($client_ec_no)){//retrieve client account number
                                                                                      echo $client_ec_no;
                                                                                      } ?>" class="form-control" readonly />
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Payment Ref:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type="text" id="pay_ref" name = "pay_ref" value = "<?php if(!empty($client_paid_ref)){//retrieve client previous payment reference
                                                                                      echo $client_paid_ref;
                                                                                      }else{ echo '"placeholder = "Full Payment Reference"';} ?>" class="form-control"/>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Paid Amnt:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type = "number" id="pay_amount" name = "pay_amount"value = "<?php if(!empty($client_paid_amnt)){//retrieve client previous payment reference
                                                                                      echo $client_paid_amnt;
                                                                                      }else{ echo '"placeholder = "Full Payment Only!"';} ?>" class="form-control"/>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>First Name:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type="text" id="fname" name = "fname" value = "<?php if(!empty($client_first_name)){//retrieve client first name
                                                                                      echo $client_first_name;
                                                                                      } ?>" class="form-control" readonly/>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Surname:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type="text" id="lname" name = "lname" value = "<?php if(!empty($client_last_name)){//retrieve client last name
                                                                                      echo $client_last_name;
                                                                                      } ?>" class="form-control" readonly/>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Gender:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type="text" id="sex" name = "sex" value = "<?php if(!empty($client_sex)){//retrieve client gender
                                                                                      echo $client_sex;
                                                                                      } ?>" class="form-control" readonly/>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Mobile #:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type = "text" id="cell" name = "cell" value = "<?php if(!empty($client_mobile_no)){//retrieve client mobile number
                                                                                      echo $client_mobile_no;
                                                                                      } ?>" class="form-control" readonly/>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Email:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type = "text" id="email" name = "email" value = "<?php if(!empty($client_email)){//retrieve client email
                                                                                      echo $client_email;
                                                                                      } ?>" class="form-control" readonly/>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Status:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type = "text" id="status" name = "status" value = "<?php if(!empty($client_status)){//retrieve client status
                                                                                      echo $client_status;
                                                                                      } ?>" class="form-control" readonly/>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-4"></div>
                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="form-group col-md-6 pr-md-1">
                                                <button type = "submit" id="pay-submit-update" class="btn btn-primary btn-lg btn-block">Submit</button>
                                            </div>
                                            <div class="form-group col-md-6 pl-md-1">
                                                <button type="reset" id="pay-reset-update" class="btn btn-primary btn-lg btn-block">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-1"></div>
                                  </div>
                                </div>
                            <div class="col-1"></div>
                        </div>
                    </form>
                    <?php } ?>
                	</div>
                </div>
            </div>
        <div class="col-1"></div>
<?php include("inc/footer.php"); ?>