<?php
ob_end_flush();
ob_start();
session_start();

//check if user has administrator privileges to view or update locations
 if(!empty($_SESSION['logged_status']) &&
        $_SESSION['logged_status'] != 'SU'){ //checks if the administrator is logged in  

    $error_message = "Whoa! You must have administrator rights for you to access routines!";
}

?>

<html>
  <body>
      
      <?php if(!empty($error_message)){
                echo '<div class = "hidden message">';
                echo '<span class = "err">'.$error_message.'</span>';
                echo '</div>';
        	} 
        	
      if(!empty($_SESSION['logged_in'])== 'EC4567'){?>
    <!-- <a href="inc/match_procedure_schs_opt1.php"  style = "font-size: 19px" >Match Procedure Schools Option 1</a><br>
    <a href="inc/match_procedure_schs_opt2.php"  style = "font-size: 19px" >Match Procedure Schools Option 2</a><br>
    <a href="inc/match_procedure_schs_opt3.php"  style = "font-size: 19px" >Match Procedure Schools Option 3</a><br>
    <a href="inc/match_procedure_schs_opt4.php"  style = "font-size: 19px" >Match Procedure Schools Option 4</a><br>
    <a href="inc/match_procedure_schs_opt5.php"  style = "font-size: 19px" >Match Procedure Schools Option 5</a><br>
    <a href="inc/match_procedure_schs_opt6.php"  style = "font-size: 19px" >Match Procedure Schools Option 6</a><br>
    <a href="inc/match_procedure_schs_opt7.php"  style = "font-size: 19px" >Match Procedure Schools Option 7</a><br>
    <a href="inc/match_procedure_schs_opt8.php"  style = "font-size: 19px" >Match Procedure Schools Option 8</a><br>
    <a href="inc/match_procedure_schs_opt9.php"  style = "font-size: 19px" >Match Procedure Schools Option 9</a><br>
    <a href="inc/match_procedure_schs_opt10.php"  style = "font-size: 19px" >Match Procedure Schools Option 10</a><br> 
    <a href="inc/match_procedure_provs_opt1.php"  style = "font-size: 19px" >Match Procedure Provinces Option1</a><br>
    <a href="inc/match_procedure_provs_opt2.php"  style = "font-size: 19px" >Match Procedure Provinces Option2</a><br>
    <a href="inc/match_procedure_towns_opt1.php"  style = "font-size: 19px" >Match Procedure Towns Option1</a><br>
    <a href="inc/match_procedure_towns_opt2.php"  style = "font-size: 19px" >Match Procedure Towns Option2</a><br>
    <a href="inc/match_procedure_towns_opt3.php"  style = "font-size: 19px" >Match Procedure Towns Option3</a><br>
    <a href="inc/match_procedure_distrs_opt1.php"  style = "font-size: 19px" >Match Procedure Districts Option 1</a><br>
    <a href="inc/match_procedure_distrs_opt2.php"  style = "font-size: 19px" >Match Procedure Districts Option 2</a><br>
    <a href="inc/match_procedure_distrs_opt3.php"  style = "font-size: 19px" >Match Procedure Districts Option 3</a><br>
    <a href="inc/match_procedure_distrs_opt4.php"  style = "font-size: 19px" >Match Procedure Districts Option 4</a><br>
    <a href="inc/match_procedure_locs_opt1.php"  style = "font-size: 19px" >Match Procedure Locations Option 1</a><br>
    <a href="inc/match_procedure_locs_opt2.php"  style = "font-size: 19px" >Match Procedure Locations Option 2</a><br>
    <a href="inc/match_procedure_locs_opt3.php"  style = "font-size: 19px" >Match Procedure Locations Option 3</a><br>
    <a href="inc/match_procedure_locs_opt4.php"  style = "font-size: 19px" >Match Procedure Locations Option 4</a><br>
    <a href="inc/match_procedure_locs_opt5.php"  style = "font-size: 19px" >Match Procedure Locations Option 5</a><br> -->
    <a href="inc/match_loc_to_loc.php"  style = "font-size: 19px" >Match Procedure Locations to Locations</a><br>
    <a href="inc/match_loc_to_town.php"  style = "font-size: 19px" >Match Procedure Locations to Towns</a><br>
    <a href="inc/match_loc_to_distr.php"  style = "font-size: 19px" >Match Procedure Locations to Districts</a><br>
    <a href="inc/match_town_to_town.php"  style = "font-size: 19px" >Match Procedure Towns to Towns</a><br>
    <a href="inc/match_distr_to_town.php"  style = "font-size: 19px" >Match Procedure Districts to Towns</a><br>
    <a href="inc/match_distr_to_distr.php"  style = "font-size: 19px" >Match Procedure Districts to Districts</a><br>
    <a href="inc/match_province_to_loc.php"  style = "font-size: 19px" >Match Procedure Provinces to Locations</a><br>
    <a href="inc/match_province_to_town.php"  style = "font-size: 19px" >Match Procedure Provinces to Towns</a><br>
    <a href="inc/match_province_to_distr.php"  style = "font-size: 19px" >Match Procedure Provinces to Districts</a><br>
    <a href="inc/match_province_to_province.php"  style = "font-size: 19px" >Match Procedure Provinces to Provinces</a><br>
    <?php } ?>
  </body>
</html>