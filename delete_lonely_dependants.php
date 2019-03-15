<?php
ob_end_flush();
ob_start();
session_start();

include 'inc/functions.php';

//check if user has administrator privileges to delete a records
if(empty($_SESSION['logged_status']) ||
        ($_SESSION['logged_status'] != 'SU'/* &&
        $_SESSION['logged_status'] != 'AD'*/)){ //checks if the administrator is logged in  

         $error_message = "Whoa! You do not have permission to access this page!";
}

//declare variable for logged account from session variable
$ecNumber = $_SESSION['logged_in'];

//this is an array of all the tables from where the record of a rejected client must be deleted
$delRecs = array( 
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


    //count the number of lonely records to be deleted
    $loneliesCount = count($lonelies);
  
    //echo 'The total number of lonely dependants that will be deleted is '.$loneliesCount.'.'.'<br>';
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
      //declare an array variable of records to be deleted
      $del_list = [];

      //create an array of records to be deleted from a multi-dimensional array extracted from the tables
      foreach($lonelies as $key=>$item){
          foreach($item as $colkey=>$client){
              
              //add to $del_list array if a record is found
              $del_list[] = $client;
          }
      }
          
      //delete record from the tables
      foreach($del_list as $key=>$value){
          
      //the below code executes deletion from all tables where a rejected client has been included
        
        foreach($delRecs as $table=>$col){
          
           $sql_delete = 'DELETE FROM '.$table.' 
                  WHERE '.$col.' = ?';

          try {
            $results_delete = $db->prepare($sql_delete);
            $results_delete->bindValue(1, $value, PDO::PARAM_STR);
            $results_delete->execute();
          } catch (Exception $e) {
            echo "Error!: " . $e->getMessage() . "<br />";
            return false;
          } 
        }
      }
        header("Location: Account_manage.php?id=$ecNumber"); 
    }

$pageTitle = 'SwopMatch Handler | Delete Lonely Dependants';

include 'inc/header.php';
    
?>

    <div class = "form">
      <?php if(!empty($error_message)){
                echo '<div class = "hidden message">';
                echo '<span class = "err">'.$error_message.'</span>';
                echo '</div>';
        	}
        	
    if(!empty($_SESSION['logged_status']) ||
    isset($_SESSION['logged_status']) && ($_SESSION['logged_status'] == 'SU' || $_SESSION['logged_status'] == 'AD')){ ?>
    
	<h1>Delete Lonely Dependants</h1>
     <?php echo '<p>The total number of lonely dependants that will be deleted is <strong>'.$loneliesCount.'</strong>.'.'<br></p>'; ?>
    <form action = "delete_lonely_dependants.php" method = "post" id="delete-lonelies"  onsubmit="if(confirm('Are you sure you want to delete ALL lonely dependants from the system?')){ return true; } else{ return false; }">
    <button type = "submit" id="client-submit" class="sub-reset">Delete</button>
    </form>
    <?php } ?>
    </div>
   
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script> 
    <script type="text/javascript" src="js/scripts.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script src="js/additional-methods.js"></script>
    <script src="js/sign_up.js"></script>

<?php include("inc/footer.php"); ?>