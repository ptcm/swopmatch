<?php
ob_end_flush();
ob_start();
session_start();

include 'inc/functions.php';

   unset($error_message);
   
   $locuptown = NULL;
   
    if(empty($_SESSION['logged_status']) ||
        ($_SESSION['logged_status'] != 'SU' &&
        $_SESSION['logged_status'] != 'AD')){ //checks if the administrator is logged in  
    
        $error_message = "Whoa! You do not have rights to update locations!";
    }elseif(isset($_GET['loc'])){ //checks if an update is intended and creates variables containing details of a location called for possible updating
    list( $loc_name,
          $loc_distr,
          $loc_town,
          $loc_status,
          $loc_id) = get_loc(filter_input(INPUT_GET,'loc', FILTER_SANITIZE_NUMBER_INT));
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        //declare a variable for location ID
        $loc_id = trim(filter_input(INPUT_POST, 'loc_id', FILTER_SANITIZE_NUMBER_INT));
        
        //declare a variable for location name
        if(!empty($_POST['loc_nm'])){
        $locupname = trim(filter_input(INPUT_POST, 'loc_nm', FILTER_SANITIZE_STRING));
        }else{
            $error_message = 'Whoa! Location name cannot be empty!';
        }
        
        //if location name is provided, declare a variable for location district
        if(empty($error_message) && !empty($_POST['loc_distr_nm'])){
        $locupdistr = trim(filter_input(INPUT_POST, 'loc_distr_nm', FILTER_SANITIZE_STRING));
        }elseif(empty($_POST['loc_distr_nm'])){
            $error_message = 'Whoa! Please select the location district';
        }
        
        //if location falls within a town, declare a variable for location town name
        if(empty($error_message) && !empty($_POST['loc_town_nm'])){
           $locuptown = trim(filter_input(INPUT_POST, 'loc_town_nm', FILTER_SANITIZE_STRING)); 
        }
        
         //declare a variable for location province if location district is provided
         if(empty($error_message)){
         try{$results = $db->query("SELECT distr_province_id FROM districts WHERE distr_id = '$locupdistr'");
        
        	}catch (Exception $e){
        			echo 'Failed to retrieve location province name';
        			exit;
        
        	}
        	$locupprov = $results->fetchColumn();
        }
        
        //declare a variable for location status
        if(empty($error_message)){
           $locupstatus = trim(filter_input(INPUT_POST, 'loc_status', FILTER_SANITIZE_STRING)); 
        }
    
        //if all conditions are met, the location in the database
        if(empty($error_message)){
        loc_update($locuptown, $locupdistr, $locupname, $locupstatus, $locupprov, $loc_id);
        }
        
        //redirects to the locations home page
        if(empty($error_message)){
	        header("location: locs.php");
        }
    }

        //set the page title  
        $pageTitle = 'SwopMatch Handler | Update Location';
        		
include 'inc/header.php';
?>

<div class="row">
    <div class="col-1">
    </div>
        <div class="col-10">
            <div class="container">
                <div id="update_location" class="jumbotron my-1 py-3">
                  <?php if(!empty($error_message)){
                            echo '<div class = "alert text-center">';
                            echo '<span class = "err">'.$error_message.'</span>';
                            echo '</div>';
                    	} 
                    	
                    if(!empty($_SESSION['logged_status']) &&
                        $_SESSION['logged_status'] == 'SU' || $_SESSION['logged_status'] == 'AD'){ ?>
                    <form action = "loc_update.php" method = "post">
	                    <h1 class="text-center display-5"><b>Update Location</b></h1>
                        <div class="row">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-3 pr-0">
                                <h5><em>Loc. Name:</em></h5>
                                </div>
                                    <div class="form-group col-md-7">
                                        <input type="text" id="loc_nm" name = "loc_nm" value = "<?php 
                                    	    if(!empty($locupname)){echo $locupname;
                                            }elseif(!empty($loc_name)){
                                                echo $loc_name;
                                           }
                                    	 ?>"  class="form-control"/>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                        <div class="row">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-3 pr-0">
                                <h5><em>Loc. District:</em></h5>
                                </div>
                                    <div class="form-group col-md-7">
                                        <select id="distr" name="loc_distr_nm" class="form-control">
                                             <option value="" selected disabled>Select location district</option>
                                                <?php all_districts_up($districts);?>
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
                                        <select id="town" name="loc_town_nm" class="form-control">
                                             <option value="" selected>Select location town</option>
                                           <?php all_towns_up($towns);?>
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
                                           <option value="" selected disabled>Select location status</option>
                                           <option value="A" <?php 
                                           if(!empty($_POST['loc_status']) && ($_POST['loc_status'] == 'A')){
                                              echo 'selected';
                                            }elseif(!empty($loc_status) && ($loc_status == 'A')){
                                              echo 'selected';
                                            }
                                           ?>>Active</option>
                                           <option value="D"<?php 
                                           if(!empty($_POST['loc_status']) && ($_POST['loc_status'] == 'D')){
                                              echo 'selected';
                                            }elseif(!empty($loc_status) && ($loc_status == 'D')){
                                              echo 'selected';
                                            }
                                           ?>>Inactive</option>
                                        </select>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                        	<?php if(!empty($loc_id)){
                                echo '<input type="hidden" value="'.$loc_id.'" name="loc_id"/>';
                            } ?>
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="form-group col-md-6 pr-md-1">
                                            <button type = "submit" id="loc-submit-update" class="btn btn-primary btn-lg btn-block hover">Submit</button>
                                        </div>
                                        <div class="form-group col-md-6 pl-md-1">
                                            <button type="reset" id="loc-reset-update" class="btn btn-primary btn-lg btn-block hover">Reset</button>
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