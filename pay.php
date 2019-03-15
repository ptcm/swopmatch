<?php
ob_end_flush();
ob_start();
session_start();

include 'inc/functions.php';

//check if user has administrator privileges to update payments
if(empty($_SESSION['logged_status']) || (isset($_SESSION['logged_status']) && ($_SESSION['logged_status'] != 'SU' &&
    $_SESSION['logged_status'] != 'AD'))){ //checks if the administrator is logged in  

    $error_message = "Whoa! You do not have rights to update payments!";
}
    
   // Escape account to protect against SQL injections
    $account = trim(strtoupper(filter_input(INPUT_POST, 'acc_no', FILTER_SANITIZE_STRING)));
    
    if(!empty($account)){
        header("location: payments.php?id=$account");
    }

//set the page title  
$pageTitle = 'SwopMatch Handler | Process Payment';

include 'inc/header.php';

?>
<div class="row">
    <div class="col-1">
    </div>
        <div class="col-10">
            <div class="container">
                <div class="jumbotron my-1 py-3">
                  <?php if(!empty($error_message)){
                            echo '<div class = "alert text-center">';
                            echo '<span class = "err">'.$error_message.'</span>';
                            echo '</div>';
                    	}
                    	
                    if(!empty($_SESSION['logged_status']) ||
                    (isset($_SESSION['logged_status']) && $_SESSION['logged_status'] == 'SU') || (isset($_SESSION['logged_status']) && $_SESSION['logged_status'] == 'AD')){ ?>
                    <form action = "pay.php" method = "post" id="account-number">
                        <div class="row">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-md-3 pr-0">
                                <h5><em>Acc. Number:</em></h5>
                                </div>
                                    <div class="form-group col-7">
                                        <input type="text" id="acc_no" name = "acc_no" placeholder = "Account Number" class="form-control"/>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <button type = "submit" id="pay-submit-update" class="btn btn-primary btn-lg btn-block hover">Submit</button>
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
</div>
<?php include("inc/footer.php"); ?>