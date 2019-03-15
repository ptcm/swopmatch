<?php
ob_end_flush();
ob_start();
session_start();


error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('html_errors', 1);

include 'inc/functions.php';

$agent_password = '';
$agent_ac_no = '';

   unset($error_message);
   
   //declare a variable for logged in user status
   if(!empty($_SESSION['logged_status'])){
     $logged_status = $_SESSION['logged_status'];
   }
   
   //declare a variable for logged in agent account
   if(!empty($_SESSION['logged_in']) && strlen($_SESSION['logged_in']) < 5){
     $logged_in = intval($_SESSION['logged_in']);
   }
   
    if((empty($logged_status) ||($logged_status != 'SU' && $logged_status != 'AD')) && empty($logged_in)){ //checks if the administrator is logged in  
        $error_message = "Whoa! Access restricted!";
    }elseif(isset($_GET['agent'])){ //checks if an update is intended and creates variables containing details of a agent called for possible updating
    list( $agent_ac_no,
          $ex_agent_first_name,
          $ex_agent_last_name,
          $ex_agent_reg_id,
          $ex_agent_sex,
          $ex_agent_mobile_no,
          $ex_agent_email,
          $ex_agent_status,
          $ex_agent_territory,
          $ex_agent_date_created) = get_agent(filter_input(INPUT_GET,'agent', FILTER_SANITIZE_NUMBER_INT));
    }
   
    //get the NUMBER of unpaid clients for the logged agent
    if(!empty($agent_ac_no)){
        $agent_ac_no = intval($agent_ac_no);
        $comm_owed = get_unpaid_comm($agent_ac_no);
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        //declare a variable for agent a/c #
        if(!empty($_POST['agent_ac_no'])){
            $agent_ac_no = filter_input(INPUT_POST, 'agent_ac_no', FILTER_SANITIZE_NUMBER_INT);
        }
        
        //declare a variable for agent first name
        $agent_first_name = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
        
        //declare a variable for agent last name
        $agent_last_name = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
        
        //declare a variable for agent National Reg ID
        $agent_reg_id = filter_input(INPUT_POST, 'reg_id', FILTER_SANITIZE_STRING);
        
        //declare a variable for mobile number
        $agent_mobile_no = filter_input(INPUT_POST, 'cell', FILTER_SANITIZE_STRING);
	 
       //check whether the mobile # is a netone number and declare the mobile # variable accordingly
        if(substr($agent_mobile_no, 0, 3) === '071'){
          $mobile = '263'.substr($agent_mobile_no, 1); //for use in sending sms
        }else{
            $mobile = $agent_mobile_no;
        }
        
        //declare a variable for gender
        $agent_sex = filter_input(INPUT_POST, 'agent_sex', FILTER_SANITIZE_STRING);
        
        //declare a variable for territory
        $agent_territory = filter_input(INPUT_POST, 'agent_territory', FILTER_SANITIZE_NUMBER_INT);
        
        //declare a variable for email
        $agent_email = trim(filter_input(INPUT_POST, 'agent_email', FILTER_SANITIZE_EMAIL));
        
        //declare a variable for password
        $agent_password = trim(password_hash(filter_input(INPUT_POST, 'agent_password', FILTER_SANITIZE_STRING), PASSWORD_BCRYPT));
        
        //declare a variable for status
        $agent_status = filter_input(INPUT_POST, 'agent_status', FILTER_SANITIZE_STRING);
        
        //declare a variable for date created time stamp
        $agent_date_created = filter_input(INPUT_POST, 'agent_date_created', FILTER_SANITIZE_STRING);
        
        //if all conditions are met, update either the agents table able
        if(empty($error_message)){
        create_agent($agent_ac_no,
            $agent_first_name, 
						$agent_last_name, 
						$agent_reg_id,  
						$agent_sex, 
						$agent_mobile_no, 
						$agent_email, 
						$agent_password, 
						$agent_status,
            $agent_territory, 
          $agent_date_created);
        
        //send a congratulatory message to the agent and redirect to the agent profile page
       if(empty($error_message) && empty($agent_ac_no)){   
        $sender = 'SwopMatch';
        
        //for bulkSMSweb only
        $webtoken = '7e51fe1d78da5158df9aeeb5b029443a';
            $body0 = 'Congratulations '.$agent_first_name.'!! You have been successfully registered as an agent for PatchIT! Together we will surely get there..';
        
        
        $body = urlencode($body0);
         
        //check whether the mobile # is a netone number and declare the username variable accordingly
        if(substr($agent_mobile_no, 0, 3) === '071'){
            $user = "263775263810";
        }else{
            $user = "patch";
        }
        
        $pass = "patchit";
        
        //bulkSMSweb service
        //$url  = "http://portal.bulksmsweb.com/index.php?app=ws&u=".$user."&h=".$webtoken."&op=pv&to=".$mobile."&msg=".$body;
        
        //if mobile # is for netone declare url for etxt else declare for bluedots
        if(substr($agent_mobile_no, 0, 3) === '071'){
             $url = "http://etext.co.zw/sendsms.php?user=".$user."&password=".$pass."&mobile=".$mobile."&senderid=".$sender."&message=".$body;
        }else{
          $url = "http://api.bluedotsms.com/api/mt/SendSMS?user=".$user."&password=".$pass."&senderid=".$sender."&channel=Normal&DCS=0&flashsms=0&number=".$mobile."&text=".$body;
        }
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_exec($ch);
            curl_close($ch);
        }
            
          header("location: agents.php?agent=$agent_ac_no");
      }
    }

        //set the page title  
        $pageTitle = 'SwopMatch Handler | Agent Details';
        		
include 'inc/header.php';

?>
<div class="row">
    <div class="col-1"></div>
        <div class="col-10">
            <div class="container">
                <div class="jumbotron my-1 py-3">
                  <?php	
                    if(!empty($_SESSION['logged_status']) ||
                        (isset($_SESSION['logged_status']) && $_SESSION['logged_status'] == 'SU') || (isset($_SESSION['logged_status']) && $_SESSION['logged_status'] == 'AD') || !empty($logged_in)){ ?>
                    <form id = 'agents' action = "agents.php" method = "post">
                	<?php if(!empty($error_message)){
                            echo '<div class = "alert text-center">';
                            echo '<span class = "err">'.$error_message.'</span>';
                            echo '</div>';
                		  }elseif(!empty($agent_ac_no) && (isset($_GET['agent']) && $_GET['agent'] != $_SESSION['logged_in']) && ($_SESSION['logged_status'] == 'SU' || $_SESSION['logged_status'] == 'AD')){
                             echo '<h1 id="welcome" class="text-center display-5"><b> '.$ex_agent_first_name.' '.$ex_agent_last_name;
                          }elseif(!empty($agent_ac_no) && (isset($_GET['agent']) && $_GET['agent'] == $_SESSION['logged_in']) && ($_SESSION['logged_status'] == 'SU' || $_SESSION['logged_status'] == 'AD')){
                                echo '<h1 id="welcome" class="text-center display-5"><b>Administrator '.$client_first_name;
                          }elseif(!empty($agent_ac_no)){
                                echo '<h1 id="welcome" class="text-center display-5"><b> Welcome Back '.$ex_agent_first_name;
                          }else{
                            echo '<h1 id="register" class="text-center display-5"><b>Register Agent';
                          } 
                          if(empty($error_message)){
                            echo '!';
                          }
                          echo '</b></h1>'; ?>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-10">
                                <div class="row <?php if(empty($agent_ac_no)){echo "d-none"; } ?>">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Agent Code:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type="text"  id="agent_ac_no" name = "agent_ac_no" value = "<?php if(!empty($agent_ac_no)){//retrieve agent account number
                                                                                      echo $agent_ac_no;
                                                                                      } ?>" class="form-control"/>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>First Name:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type="text" id="fname" name = "fname" value = "<?php if(!empty($ex_agent_first_name)){//retrieve agent first name
                                                                                      echo $ex_agent_first_name;
                                                                                      } ?>" class="form-control"/>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Surname:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type="text" id="lname" name = "lname" value = "<?php if(!empty($ex_agent_last_name)){//retrieve agent last name
                                                                                      echo $ex_agent_last_name;
                                                                                      } ?>" class="form-control"/>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>National ID:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type="text" id="reg_id" name = "reg_id" value = "<?php if(!empty($ex_agent_reg_id)){//retrieve agent National Id
                                                                                      echo $ex_agent_reg_id;
                                                                                      } ?>" class="form-control"/>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Gender:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <select id = "search_type" class="form-control">
                                                  <option value="" selected disabled>Select One Option</option>
                                            		<option value="M" <?php if(isset($_POST['agent_sex'])){//retrieve agent sex during registration if submission fails
                                            					if($_POST['agent_sex'] == 'M'){
                                            						echo 'selected';
                                            					} 
                                                    }elseif(isset($ex_agent_sex) && $ex_agent_sex == 'M'){//retrieve agent sex during updating
                                                        echo 'selected';
                                                      }?>>Male</option>
                                            		<option value="F" <?php if(isset($_POST['agent_sex'])){//retrieve agent sex during registration if submission fails
                                            					if($_POST['agent_sex'] == 'F'){
                                            						echo 'selected';
                                            					}
                                                    }elseif(isset($ex_agent_sex) && $ex_agent_sex == 'F'){//retrieve agent sex during updating
                                                        echo 'selected';
                                                      } ?>>Female</option>
                                                </select>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Mobile #:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type="text" id="cell" name = "cell" value = "<?php if(!empty($ex_agent_mobile_no)){//retrieve agent mobile number
                                                                                      echo $ex_agent_mobile_no;
                                                                                      } ?>" class="form-control"/>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Email:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type="text" id="agent_email" name = "agent_email" value = "<?php if(!empty($ex_agent_email)){//retrieve agent email
                                                                                      echo $ex_agent_email;
                                                                                      } ?>" class="form-control"/>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                        		<?php //hide password field if admin is logged
                        		if($_SESSION['logged_status'] != 'SU' && $_SESSION['logged_status'] != 'AD'){ ?>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Password:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type = "password" id = "agent_password" name = "agent_password" required placeholder="Password" class="form-control"/>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Confirm Password:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type = "password" id = "agent_pass_confirm" name = "agent_pass_confirm" required placeholder="Confirm Password" class="form-control"/>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
	                                <?php } ?>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Agent Territory:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <select id = "search_type" class="form-control">
                                                  <option value="" selected disabled>Select One</option>
                                                    <?php all_territories($districts) ?>
                                                </select>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Date Joined:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type = "text" id="agent_date_created" name = "agent_date_created" value = "<?php if(!empty($ex_agent_date_created)){//retrieve agent email
                                                                                      echo $ex_agent_date_created;
                                                                                      }else{
                                                                                            date_default_timezone_set('Africa/Harare'); 
                                                                                            echo date('d-m-Y H:i:s');
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
                                                <select id="agent_status" name="agent_status" <?php //disable select for non-superuser if client status is active
	                                                if($logged_status != 'SU' && $logged_status != 'AD'){ echo 'readonly'; } ?> class="form-control">
                                                  <option value="MA" <?php if(isset($_POST['agent_status'])){//retrieve agent status during registration if submission fails
                                					if($_POST['agent_status'] == 'MA'){
                                						echo 'selected';
                                					}
                                				    }elseif(isset($ex_agent_status) && $ex_agent_status == 'MA'){//retrieve agent status during updating
                                                        echo 'selected';
                                                    } ?> selected>Active</option>
                                                  <option value="D" <?php if(isset($_POST['agent_status'])){//retrieve agent status during registration if submission fails
                                					if($_POST['agent_status'] == 'D'){
                                						echo 'selected';
                                					}
                                				    }elseif(isset($ex_agent_status) && $ex_agent_status == 'D'){//retrieve agent status during updating
                                                        echo 'selected';
                                                    } ?>>Deactivated</option>
                                                </select>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                      <div class="form-group align-self-center col-3 pr-0">
                                        <h5><em>Commission Owed:</em></h5>
                                        </div>
                                            <div class="form-group col-md-7">
                                                <input type = "text" id="comm_owed" name = "comm_owed" value = "<?php if(!empty($comm_owed)){//retrieve agent previous payment reference
                                                                                      echo '$'.$comm_owed.'.00';
                                                                                      }else{echo '$0';} ?>" class="form-control" readonly/>
                                            <?php if(!empty($agent_ac_no)){
                                                        echo '<input type="hidden" value="'.$agent_ac_no.'" name="agent_ac_no"/>';
                                                  }
                                                  
                                                  if($logged_status == 'SU' || $logged_status == 'AD'){
                                                        echo '<input type="hidden" value="55555" name="agent_password"/>';
                                                  }
                                            ?>
                                            </div>
                                        <div class="col-1"></div>
                                  </div>
                                <div class="row">
                                    <div class="col-4"></div>
                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="form-group col-md-6 pr-md-1">
                                                <button type = "submit" id="agent-submit-update" class="btn btn-primary btn-lg btn-block">Submit</button>
                                            </div>
                                            <div class="form-group col-md-6 pl-md-1">
                                                <button type="reset" id="agent-reset-update" class="btn btn-primary btn-lg btn-block">Reset</button>
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