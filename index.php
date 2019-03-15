<?php
session_start();

/* Main page with two forms: sign up and log in */
//unset session message for password recovery if set
if(!empty($_SESSION['message'])){
    unset($_SESSION['message']);
    
}
include 'inc/functions.php';
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if (isset($_POST['login'])) { //user logging in

        require 'login.php';
        
        }
        
    }else{
      
      //the below session will later be used when requesting a profile view
      $_SESSION['ec_number'] = '';;
    }
        

$pageTitle = "Swop Match Handler | Home";
include("inc/header.php");

//echo $status;

?>
<div id="jumbo-row" class="row">
    <div id="leftBodDiv" class="col-1"></div>
        <div id="logDiv"  class="col-4 jumbotron my-1 mx-1 pl-5">
            <form action="index.php" method="post" autocomplete="off">
              <?php if(!empty($_SESSION['message'])){
                      echo '<div class = "alert text-center">';
                      echo '<span>'.$_SESSION['message'].'</span>';
                      echo '</div>';
            			}?>
                <div class="row">
                    <div class="col-1"></div>
                            <div class="form-group col-md-10">
                                <div class="text-center mb-0">
                                    <label class="mb-0">A/C Number *</label>
                                </div>
                                <input type="text" id="ac-no" required autocomplete="off" name="ec_number" placeholder= "Your A/C Number" class="form-control"/>
                            </div>
                        <div class="col-1"></div>
                  </div>
                <div class="row">
                    <div class="col-1"></div>
                            <div class="form-group col-md-10">
                                <div class="text-center mb-0">
                                    <label class="mb-0">Password *</label>
                                </div>
                                <input type="password" autocomplete="off" name="password" placeholder= "Your Password" class="form-control"/>
                            </div>
                        <div class="col-1"></div>
                  </div>
                    <div class="row mb-0">
                        <div class="col-1"></div>
                        <div class="col-10">
                            <div class="form-group text-center">
                                <a href="forgot.php" id="pwd-forgot" class="hover" role="button" aria-pressed="true">Forgot Password?</a>
                            </div>
                        </div>
                        <div class="col-1"></div>
                      </div>
                    <div class="row">
                        <div class="col-1"></div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <button type = "submit" name="login" class="btn btn-primary btn-lg btn-block hover">Log In</button>
                            </div>
                        </div>
                        <div class="col-1"></div>
                      </div>
                    <div class="row mb-0">
                        <div class="col-1"></div>
                        <div class="col-10">
                            <div class="form-group text-center">
                                <p class="mb-0"><em>OR</em></p>
                            </div>
                        </div>
                        <div class="col-1"></div>
                      </div>
                    <div class="row mb-0">
                        <div class="col-1"></div>
                        <div class="col-10">
                            <div class="form-group text-center">
                                <a href="Account_manage.php" class="hover brand-name" role="button" aria-pressed="true"><h3 class="advert"><b>Register Here..</b></h3></a>
                            </div>
                        </div>
                        <div class="col-1"></div>
                      </div>
            </form>
        </div>
        <div id="infoDiv"  class="col-6 jumbotron my-1 mx-1">
            <h2 id="welcome" class="text-center brand-name">Welcome to SwopMatch Handler!</h2> 
            <blockquote class="blockquote">
              <p class="mb-0">A place where we take care of swop requests.</p>
            </blockquote>
            <div class="container">
        		<p>If you are a Zimbabwean teacher and you are looking for someone to swop with; you are at the right place! We do it in a <strong>SMART</strong> way for the least cost.</p>
        		<blockquote class="emphasis">To shorten the waiting time before finding a SwopMatch, we have to bring as many colleagues as we can together here! Please copy the link of our <a href="inc/images/advert.jpg" class='advert'>LATEST ADVERT</a> and send to colleagues by Whatsapp or other means. You can as well download the <a href="inc/images/advert.jpg" class='advert'>IMAGE</a> and send it.</blockquote>
        		
        		<div class="text-center mb-2">
            		<button id="show_how" type="button" class="btn btn-info btn-sm">Explain How You Do It?</button>
            		<button id="hide_how" type="button" class="btn btn-danger btn-sm" style="display:none">Hide Your Explanation?</button>
        		</div>
            
            </div>
            <div id="how"class="container" style="display:none">
                <p><strong>REGISTER FOR $2.00 ONLY </strong>payable through our PatchIT Ecocash Biller and you are in! .<br><br>

                Below is an overview of how you <a href="Account_manage.php">register</a> and keep track of your profile and the progress in our journey together:</p>
                <ol>
                
                    <li>	We need your current station details in as much detail as is possible. This will assist in quickly finding a perfect ‘SwopMatch’ for you.</li><br>
                    <li>	If you are a Secondary School teacher, a maximum of <strong>TWO (2)</strong> subjects can be registered on our site <strong>BUT</strong> we only find a match for you using <strong>ANY ONE</strong> of the two subjects. If you want to be matched with someone who teaches a <strong>SPECIFIC</strong> single subject, please just select that one subject only.</li><br>
                    <li>	Make sure you register a mobile number that you use daily because updates and notifications will be send on the registered number only as well as assistance in resetting forgotten passwords.</li><br>
                    <li>	The most specific place you may wish to relocate to is found mostly when your register your preference by <strong>SPECIFIC SCHOOLS</strong>. You may, however, also choose your preferences by <strong>PROVINCE, DISTRICT, TOWN</strong> or <strong>LOCATION.</strong></li><br>
                    <li>	Once registered you can <a href="#ac-no" id = "body_log">login</a> to our site and view the status of your profile and, if you wish, modify some of your registered details.  Once a match has been reserved for you, you will not be able to change any details on your profile until the process has been finalized. <strong>YOU CANNOT MODIFY YOUR ACCOUNT NUMBER</strong>.</li><br>
                    <li>	Once a match has been found for you, an SMS will be send to your <strong>registered mobile number</strong> and you will be given <strong>7 days</strong> to pay a service fee <i>(currently $20.00)</i> before full details of your match can be send to you. Please note that <strong>ONLY</strong> Zimbabwean numbers can be considered by the system as <strong>VALID</strong> mobile numbers.</li><br>
                    <li>	As much as you are able to, please periodically view the status of your account online. This will especially help in cases where SMSs send to your mobile number may have failed to deliver.</li><br>
                    <li>	Please carefully read our <a href="inc/docs/Terms_and_Conditions_for_SwopMatch_ Handler_Service_v1.pdf">Terms and Conditions</a>.</li><br>
                    <li>	If you have any form of <strong>FEEDBACK</strong>, please leave it <a href="suggest.php" target = "_blank">here</a>. We value your suggestions and comments.</li>
                </ol>
                <blockquote class="emphasis">As at the time you visited our site; we had <span class="advert" style='color: #f70909;'><?php echo $active; ?></span> client(s) ready to swop as soon as they find a match. They may be waiting for you!</blockquote>
                <p><i><strong>Let's take the journey together and we will surely get there!!</strong></i></p>
                Grace abound.
            </div>
        </div>
    <div id="rightBodDiv" class="col-1"></div>
</div>
<?php include("inc/footer.php"); ?>