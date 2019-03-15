<?php
ob_end_flush();
ob_start();
session_start();

include 'inc/functions.php';

//check if user has administrator privileges to view or update locations
if(empty($_SESSION['logged_status']) ||
        ($_SESSION['logged_status'] != 'SU' &&
        $_SESSION['logged_status'] != 'AD')){ //checks if the administrator is logged in  

    $error_message = "Whoa! You do not have rights to view/update locations!";
}
    
   // Escape location to protect against SQL injections
    $locname = trim(filter_input(INPUT_POST, 'loc_name', FILTER_SANITIZE_STRING));
    
    if(!empty($locname)){
        header("location: loc_update.php?loc=$locname");
    }

//set the page title  
$pageTitle = 'SwopMatch Handler | Insert/Update Location';

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
                        ?>
                        <div class="row <?php
                            //hide buttons if administrator is not logged in
                            if(!empty($error_message)){
                                echo "d-none";
                                } ?>" id = "loc-butts">
                            <div class="col-2"></div>
                            <div class="col-md-8 d-md-flex d-sm-block flex-sm-wrap justify-content-center">
                                <button type="button"   id="new_loc" onclick = 'window.location.href= "loc_new.php";' class="btn btn-secondary mr-1 mb-2">New Location</button>
                                <button type="button"  id="update_loc" class="btn btn-secondary ml-1 mb-2">Update Location</button>
                            </div>
                            <div class="col-2"></div>
                        </div>
                        <?php
                            if(!empty($_SESSION['logged_status']) ||
                            isset($_SESSION['logged_status']) && ($_SESSION['logged_status'] == 'SU' || $_SESSION['logged_status'] == 'AD')){ ?>
                    <form action = "locs.php" method = "post"  style = "display: none" id="location-name">
                        <div class="row">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-3 pr-0">
                                <h5 class="float-right"><em>Loc. Name:</em></h5>
                                </div>
                                    <div class="form-group col-md-7">
                                        <select id="loc_name" name="loc_name" class="form-control">
                                          <option value="" selected disabled>Select location</option>
                                            <?php all_locations_up($locations);?>
                                        </select>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                          <div class="row my-1">
                              <div class="col-4"></div>
                              <div class="col-md-7">
                                  <button type="submit" id="loc-submit-update" class="btn btn-primary btn-lg btn-block hover">Submit</button>
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