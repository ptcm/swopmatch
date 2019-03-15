<?php
ob_end_flush();
ob_start();
session_start();

include 'inc/functions.php';

    if(empty($_SESSION['logged_status']) ||
        ($_SESSION['logged_status'] != 'SU' &&
        $_SESSION['logged_status'] != 'AD')){ //checks if the administrator is logged in  
    
        $error_message = "Whoa! You have no rights to add locations!";
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        //declare a variable for location name
        if(empty($error_message) && !empty($_POST['loc_n'])){
        $locnewname = trim(filter_input(INPUT_POST, 'loc_n', FILTER_SANITIZE_STRING));
        }else{
            $error_message = 'Whoa! Please provide a location name';
        }
        
        //if location name is provided, declare a variable for location district
        if(empty($error_message) && !empty($_POST['loc_distr_n'])){
        $locnewdistr = trim(filter_input(INPUT_POST, 'loc_distr_n', FILTER_SANITIZE_STRING));
        }else{
            $error_message = 'Whoa! Please select the location district';
        }
        
        //if location falls within a town, declare a variable for location town name
        if(empty($error_message) && !empty($_POST['loc_town_nm'])){
           $locnewtown = trim(filter_input(INPUT_POST, 'loc_town_nm', FILTER_SANITIZE_STRING)); 
        }else{
            $locnewtown = NULL;
        }
        
         //declare a variable for location province if location district is provided
         if(empty($error_message)){
         try{$results = $db->query("SELECT distr_province_id FROM districts WHERE distr_id = '$locnewdistr'");
        
        	}catch (Exception $e){
        			echo 'Failed to retrieve location province name';
        			exit;
        
        	}
        	$locnewprov = $results->fetchColumn();
        }
        
        //declare a variable for location status
        if(empty($error_message)){
           $locnewstatus = trim(filter_input(INPUT_POST, 'loc_status', FILTER_SANITIZE_STRING)); 
        }
        
        //if all conditions are met, insert the new location in the database
        if(empty($error_message)){
          loc_create($locnewtown, $locnewdistr, $locnewname, $locnewstatus, $locnewprov);
        }
        
        //redirects to the locations home page
        if(empty($error_message)){
	        header("location: locs.php");
        }
    }
       
    $pageTitle = 'SwopMatch Handler | Locations';
        		
include 'inc/header.php';

?>

<div class="row">
    <div class="col-1">
    </div>
        <div class="col-10">
            <div class="container">
                <div id="new_location" class="jumbotron my-1 py-3">
                  <?php if(!empty($error_message)){
                        echo '<div class = "alert text-center">';
                        echo '<span class = "err">'.$error_message.'</span>';
                        echo '</div>';
                		}
                		
                		if(!empty($_SESSION['logged_in']) &&
                            ($_SESSION['logged_status'] == 'SU' || $_SESSION['logged_status'] == 'AD')){ 
                    ?>
                    <form action = "loc_new.php" method = "post">
	                    <h1 class="text-center display-5"><b>Input New Location</b></h1>
                        <div class="row">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-3 pr-0">
                                <h5><em>Loc. Name:</em></h5>
                                </div>
                                    <div class="form-group col-md-7">
                                        <input type="text" id="loc_n" name = "loc_n" placeholder = "Location Name" class="form-control"/>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                        <div class="row">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-3 pr-0">
                                <h5><em>Loc. District:</em></h5>
                                </div>
                                    <div class="form-group col-md-7">
                                        <select id="distr" name="loc_distr_n" class="form-control">
                                            <option value="" selected disabled>Select location district</option>
                                                <?php all_districts($districts);?>
                                        </select>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                        <div class="row">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-3 pr-0">
                                <h5><em>Loc. Town:</em></h5>
                                </div>
                                    <div class="form-group col-md-7">
                                        <select id="town_n" name="loc_town_nm" class="form-control">
                                         <option value="" selected disabled>Select location town</option>
                                            <?php all_towns($towns);?>
                                        </select>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                        <div class="row">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-3 pr-0">
                                <h5><em>Loc. Status:</em></h5>
                                </div>
                                    <div class="form-group col-md-7">
                                        <select id="loc_status" name="loc_status" class="form-control">
                                           <option value="A" selected>Active</option>
                                           <option value="D">Inactive</option>
                                        </select>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="form-group col-md-6 pr-md-1">
                                            <button type = "submit" id="loc-submit-new" class="btn btn-primary btn-lg btn-block hover">Submit</button>
                                        </div>
                                        <div class="form-group col-md-6 pl-md-1">
                                            <button type="reset" id="loc-reset-new" class="btn btn-primary btn-lg btn-block hover">Reset</button>
                                        </div>
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