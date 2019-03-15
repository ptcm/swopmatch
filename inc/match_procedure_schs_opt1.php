
<?php
include 'class_lib.php';

      echo 'Please relax while the engine sorts it for you!'.'<br>';
      echo 'Preparing matched preferred schools 1st option'.'<br>';
            
      //$schools_opt1 reserves all schools in table match_pref_schools matched with current schools
      //and deactivates the reserved option in other linked tables
      $schools_opt1 = new prep_match_data();
      $dup_keys = 'mcs_client_ec_no';
      echo 'The number of unique matches found is '.$schools_opt1->get_match($matched_schools1,$dup_keys).'.'.'<br>';
      
      $schools_opt1->create_matched_recs();

      $schools_opt1->update_tables();
  
      echo 'Reserving matched preferred schools for 1st option and updating linked tables'.'<br>';
      echo "Procedure completed sucessfully!".'<br>';
  
      ?>