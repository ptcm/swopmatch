<?php
ob_end_flush();
ob_start();
session_start();

include 'inc/functions.php';

// Make sure the form is being submitted with method="post"
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

    // Make sure the two passwords match
    if ( $_POST['newpassword'] == $_POST['confirmpassword'] ) {
        
        $_SESSION['newpassword'] = $_POST['newpassword'];
        
        header("location: reset_confirm.php");
        
    }else {
        $error_message = "Two passwords you entered don't match, try again!";
    }
}

$pageTitle = 'SwopMatch Handler | Change Admin Password';

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
                    	} ?>
                    <form action="reset.php" method="post">
	                    <h1 class="text-center display-5"><b>Change Your Password</b></h1>
                        <div class="row">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-3 pr-0">
                                <h5><em>New Password<span class="">*</span></em></h5>
                                </div>
                                    <div class="form-group col-md-7">
                                        <input type="password"required name="newpassword" autocomplete="off" class="form-control"/>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                        <div class="row">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-3 pr-0">
                                <h5><em>Confirm New Password<span class="">*</span></em></h5>
                                </div>
                                    <div class="form-group col-md-7">
                                        <input type="password"required name="confirmpassword" autocomplete="off" class="form-control"/>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <button type = "submit" class="btn btn-primary btn-lg btn-block hover">Apply</button>
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