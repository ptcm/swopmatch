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
    
    // Escape A/C # to protect against SQL injections
    $ec_num = trim(filter_input(INPUT_POST, 'client_ec', FILTER_SANITIZE_STRING));
    
    $delRecs = array( //this is an array of all the tables from where the record of a rejected client must be deleted
                            "clients"=>"client_ec_no",
                            "match_current_schools"=>"mcs_client_ec_no",
                            "match_pref_provinces"=>"mpp_client_ec_no",
                            "match_pref_provinces2"=>"mpp2_client_ec_no",
                            "match_pref_districts"=>"mpd_client_ec_no",
                            "match_pref_districts2"=>"mpd2_client_ec_no",
                            "match_pref_districts3"=>"mpd3_client_ec_no",
                            "match_pref_districts4"=>"mpd4_client_ec_no",
                            "match_pref_locations"=>"mpl_client_ec_no",
                            "match_pref_locations2"=>"mpl2_client_ec_no",
                            "match_pref_locations3"=>"mpl3_client_ec_no",
                            "match_pref_locations4"=>"mpl4_client_ec_no",
                            "match_pref_locations5"=>"mpl5_client_ec_no",
                            "match_pref_towns"=>"mpt_client_ec_no",
                            "match_pref_towns2"=>"mpt2_client_ec_no",
                            "match_pref_towns3"=>"mpt3_client_ec_no",
                            "match_pref_schools"=>"mps_client_ec_no",
                            "match_pref_schools2"=>"mps2_client_ec_no",
                            "match_pref_schools3"=>"mps3_client_ec_no",
                            "match_pref_schools4"=>"mps4_client_ec_no",
                            "match_pref_schools5"=>"mps5_client_ec_no",
                            "match_pref_schools6"=>"mps6_client_ec_no",
                            "match_pref_schools7"=>"mps7_client_ec_no",
                            "match_pref_schools8"=>"mps8_client_ec_no",
                            "match_pref_schools9"=>"mps9_client_ec_no",
                            "match_pref_schools10"=>"mps10_client_ec_no"
                          );
                          
    if(!empty($ec_num)){
        
        try{$sql = $db->query("SELECT count(*) FROM clients WHERE client_ec_no = '$ec_num'");
            $count = $sql->fetchColumn();
        }catch (Exception $e){
			echo 'Failed to count from clients table';
			exit;
        }
        
        if(!empty($count) && $count == 1){
            foreach($delRecs as $table=>$column){
                try{$sql = $db->query("DELETE FROM ".$table." WHERE ".$column." = '$ec_num'");
                    //$count = $sql->fetchColumn();
                }catch (Exception $e){
        			echo 'Failed to delete from table';
        			exit;
                }
            }
            header("Location: Account_manage.php?id=$ecNumber");  
        }else{
            $error_message = "Whoa! Account '".$ec_num."' not found!";
        }      
    }
}

$pageTitle = 'SwopMatch Handler | Delete Client';

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
                    <form action = "del_client.php" method = "post" id="delete-client"  onsubmit="if(confirm('Are you sure you want to delete this client from the system?')){ return true; } else{ return false; }">
	                    <h1 class="text-center display-5"><b>Delete Client</b></h1>
                        <div class="row">
                            <div class="col"></div>
                            <div class="col">
                                <div class="offset-md-3 card border-danger text-center mb-3 dev-width">
                                  <div class="card-body bg-danger">
                                    <h5 class="card-title">Warning:</h5>
                                    <p class="card-text">This form action will delete the client from all the tables in the database!</p>
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
                                        <input type="text" id="client_ec" name="client_ec" class="form-control"/>
                                    </div>
                                <div class="col-1"></div>
                          </div>
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <button type = "submit" id="client-submit" class="btn btn-primary btn-lg btn-block hover">Delete</button>
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