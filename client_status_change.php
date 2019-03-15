<?php
ob_end_flush();
ob_start();
session_start();

include 'inc/functions.php';

$ecNumber = '';

//check if user has administrator privileges to delete a client
if(empty($_SESSION['logged_status']) ||
        ($_SESSION['logged_status'] != 'SU'/* &&
        $_SESSION['logged_status'] != 'AD'*/)){ //checks if the administrator is logged in  

         $error_message = "Whoa! You do not have permission to access this page!";
}

//declare a variable for the logged EC Number to redirect to after delition
if(!empty($_SESSION['logged_in'])){
    $ecNumber = $_SESSION['logged_in'];
}


if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
    
    // declare variable for A/C #
    $ec_num = trim(filter_input(INPUT_POST, 'client_acc', FILTER_SANITIZE_STRING));
    
    // declare variable for new status
    $new_status = trim(filter_input(INPUT_POST, 'new_status', FILTER_SANITIZE_STRING));
    
    $changeRecs = array( //this is an array of all the tables from where the record of a client must be changed
                            "clients"=>array("client_status", "client_ec_no"),
                            "match_current_schools"=>array("mcs_status", "mcs_client_ec_no"),
                            "match_pref_provinces"=>array("mpp_status", "mpp_client_ec_no"),
                            "match_pref_provinces2"=>array("mpp2_status", "mpp2_client_ec_no"),
                            "match_pref_districts"=>array("mpd_status", "mpd_client_ec_no"),
                            "match_pref_districts2"=>array("mpd2_status", "mpd2_client_ec_no"),
                            "match_pref_districts3"=>array("mpd3_status", "mpd3_client_ec_no"),
                            "match_pref_districts4"=>array("mpd4_status", "mpd4_client_ec_no"),
                            "match_pref_locations"=>array("mpl_status", "mpl_client_ec_no"),
                            "match_pref_locations2"=>array("mpl2_status", "mpl2_client_ec_no"),
                            "match_pref_locations3"=>array("mpl3_status", "mpl3_client_ec_no"),
                            "match_pref_locations4"=>array("mpl4_status", "mpl4_client_ec_no"),
                            "match_pref_locations5"=>array("mpl5_status", "mpl5_client_ec_no"),
                            "match_pref_towns"=>array("mpt_status", "mpt_client_ec_no"),
                            "match_pref_towns2"=>array("mpt2_status", "mpt2_client_ec_no"),
                            "match_pref_towns3"=>array("mpt3_status", "mpt3_client_ec_no"),
                            "match_pref_schools"=>array("mps_status", "mps_client_ec_no"),
                            "match_pref_schools2"=>array("mps2_status", "mps2_client_ec_no"),
                            "match_pref_schools3"=>array("mps3_status", "mps3_client_ec_no"),
                            "match_pref_schools4"=>array("mps4_status", "mps4_client_ec_no"),
                            "match_pref_schools5"=>array("mps5_status", "mps5_client_ec_no"),
                            "match_pref_schools6"=>array("mps6_status", "mps6_client_ec_no"),
                            "match_pref_schools7"=>array("mps7_status", "mps7_client_ec_no"),
                            "match_pref_schools8"=>array("mps8_status", "mps8_client_ec_no"),
                            "match_pref_schools9"=>array("mps9_status", "mps9_client_ec_no"),
                            "match_pref_schools10"=>array("mps10_status", "mps10_client_ec_no")
                          );
                          
    if(!empty($ec_num)){
        
        try{$sql = $db->query("SELECT count(*) FROM clients WHERE client_ec_no = '$ec_num'");
            $count = $sql->fetchColumn();
        }catch (Exception $e){
			echo 'Failed to count from clients table';
			exit;
        }
        
        if(!empty($count) && $count == 1){
            foreach($changeRecs as $table=>$column){
                try{$sql = $db->query("UPDATE ".$table." SET ".$column[0]." = '$new_status' WHERE ".$column[1]." = '$ec_num'");
                }catch (Exception $e){
        			echo 'Failed to update table';
        			exit;
                }
            }
        }else{
            $error_message = "Whoa! Account '".$ec_num."' not found!";
        }      
    }
}

$pageTitle = 'SwopMatch Handler | Update Client Status';

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
                    isset($_SESSION['logged_status']) && ($_SESSION['logged_status'] == 'SU' || $_SESSION['logged_status'] == 'AD')){ ?>
                    <form action = "client_status_change.php" method = "post" id="change-status"  onsubmit="if(confirm('Are you sure you want to change the client status?')){ return true; } else{ return false; }">
	                    <h1 class="text-center display-5"><b>Change Client Status</b></h1>
                        <div class="row">
                            <div class="col"></div>
                            <div class="col">
                                <div class="card offset-md-3 border-danger text-center mb-3 dev-width" style="width: 18rem;">
                                  <div class="card-body bg-warning">
                                    <h5 class="card-title">Warning:</h5>
                                    <p class="card-text">This form action will change the client status for all the tables in the database!</p>
                                  </div>
                                </div>
                            </div>
                            <div class="col"></div>
	                    </div>
                        <div class="row">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-3 pr-0">
                                <h5><em>Client A/C #:</em></h5>
                                </div>
                                    <div class="form-group col-md-7">
                                        <input type="text" id="client_acc" name="client_acc" class="form-control"/>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                        <div class="row">
                            <div class="col-1"></div>
                              <div class="form-group align-self-center col-3 pr-0">
                                <h5><em>New Status:</em></h5>
                                </div>
                                    <div class="form-group col-md-7">
                                        <select id="new_status" name="new_status" class="form-control">
                                            <option value="" selected disabled>Select Option</option>
                                                <?php pull_status($statuses);?>
                                        </select>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <button type = "submit" id="client-change" class="btn btn-primary btn-lg btn-block hover">Submit</button>
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