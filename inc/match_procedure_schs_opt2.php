
<?php
include 'class_lib.php';

      echo 'Please relax while the engine sorts it for you!'.'<br>';
      echo 'Preparing matched preferred schools 2nd option'.'<br>';
            
      //$schools_opt2 reserves all schools in table match_pref_schools2 matched with current schools
      $schools_opt2 = new prep_match_data();
      $dup_keys = 'mcs_client_ec_no';
      echo 'The number of unique matches found is '.$schools_opt2->get_match($matched_schools2,$dup_keys).'.'.'<br>';
      
      $schools_opt2->create_matched_recs();
      
      $schools_opt2->update_tables();
  
      echo 'Reserving matched preferred schools for 2nd option and updating linked tables'.'<br>';
      echo "Procedure completed sucessfully!".'<br>';
      
      ?>