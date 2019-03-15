<?php
ob_end_flush();
ob_start();
session_start();
$pageTitle = 'SwopMatch Handler | Suggest';
include("inc/header.php");
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$name = trim(filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING));
	$email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
	$suggestion = trim(filter_input(INPUT_POST, "user_suggestion", FILTER_SANITIZE_SPECIAL_CHARS));
	
	if ($name == "" || $email == "" || $suggestion == ""){
		
		$error_message = 'Please fill in all the required fields: Name, Email and Details!';		
	}
	
	if (!isset($error_message) && $_POST["address"] != ""){
		
		$error_message = "Bad form input";
	}

	require("inc/phpmailer/class.phpmailer.php");
	
	$mail = new PHPMailer;
	
    if (!$mail->ValidateAddress($email)){
    	   echo "Invalid Email Address";
    	   exit;
    	}
    	$email_body = "";
    	$email_body .= "Name: ". $name . "\n";
    	$email_body .= "Email: ". $email . "\n";
    	$email_body .= "Details: ". $suggestion . "\n";
    	//Recipients
        $mail->setFrom($email, $name);
        $mail->addAddress('admin@swopmatch.co.zw', 'Admin');     // Add a recipient Optional name
    
        //Content
        $mail->isHTML(false);                                  // Set email format to HTML
        $mail->Subject = 'Suggestion/Comment from '. $name;
        $mail->Body    = $email_body;
    
        if(!$mail->send()){
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        exit;
     }
        header("location: success.php");
} 

 ?>
<div class="row">
    <div class="col-1">
    </div>
        <div class="col-10">
            <div class="container">
                <div class="jumbotron my-1 py-3"><div id="feedback">
                	  <?php if (isset($_GET["status"]) && $_GET["status"] == "thanks"){
                		  echo '<p>Thank you for the email! We will check your suggestion shortly!</p>';
                	  }else{?> 
                	  
                	  <?php
                	  if (isset($error_message)){
                			echo "<p class = 'alert text-center'>".$error_message."</p>";
                			}else{
                			echo '<h4 class="text-center" id = "sugh2_1"><b>Let Us Hear Your Comments & Suggestions</b></h4>';				
                			}
                	  ?>
                    <form action = "suggest.php" method = "post">
	                    <h6 class="text-center">Please type your suggestion below and click the Send Button to send us your suggestions</h6>
                        <div class="row">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-3 pr-0">
                                <h5><em>Name:</em></h5>
                                </div>
                                    <div class="form-group col-md-7">
                                        <input type="text" id = "name" name = "name" value = "<?php if (isset($name)){echo $name; }?>" placeholder="Name" class="form-control"/>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                        <div class="row">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-3 pr-0">
                                <h5><em>Email:</em></h5>
                                </div>
                                    <div class="form-group col-md-7">
                                        <input type="text" id = "email" name = "email" value = "<?php if (isset($email)){echo $email; }?>" placeholder="E-Mail" class="form-control"/>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                        <div class="row">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-3 pr-0">
                                <h5><em>Suggestions:</em></h5>
                                </div>
                                    <div class="form-group col-md-7">
                                        <textarea rows="5" class="form-control" id = "user_suggestion" name = "user_suggestion" ><?php if(isset($suggestion)){echo htmlspecialchars($_POST["user_suggestion"]);} ?></textarea>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                        <div class="row d-none">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-3 pr-0">
                                <h5><em>Address:</em></h5>
                                </div>
                                    <div class="form-group col-md-7">
                                        <input type="text" id = "address" name = "address" class="form-control"/><p>Please leave this field blank</p>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <button type = "submit" id="suggestsubmit" class="btn btn-primary btn-lg btn-block hover">Send</button>
                                    </div>
                                </div>
                                <div class="col-1"></div>
                              </div>
                    </form>
                    <?php } ?>
                </div>
                </div>
            </div>
        </div>
    <div class="col-1"></div>
</div>

<?php
include("inc/footer.php");
?>