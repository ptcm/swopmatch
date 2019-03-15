
<?php
include 'class_lib.php';

      echo 'Please relax while the engine sorts it for you!'.'<br>';
      echo 'Preparing matched preferred schools 9th option'.'<br>';
            
      //$schools_opt9 reserves all schools in table match_pref_schools9 matched with current schools
      $schools_opt9 = new prep_match_data($matched_schools9,$dup_keys);
      $dup_keys = 'mcs_client_ec_no';
      echo 'The number of unique matches found is '.$schools_opt9->get_match($matched_schools9,$dup_keys).'.'.'<br>';
      
      $schools_opt9->create_matched_recs();
      
      $schools_opt9->update_tables();
  
      echo 'Reserving matched preferred schools for 9th option and updating linked tables'.'<br>';
      echo "Procedure completed sucessfully!".'<br>';
      
      ?>