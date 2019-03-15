
<?php
include 'class_lib.php';

      echo 'Please relax while the engine sorts it for you!'.'<br>';
      echo 'Preparing matched preferred schools 7th option'.'<br>';
            
      //$schools_opt7 reserves all schools in table match_pref_schools7 matched with current schools
      $schools_opt7 = new prep_match_data($matched_schools7,$dup_keys);
      $dup_keys = 'mcs_client_ec_no';
      echo 'The number of unique matches found is '.$schools_opt7->get_match($matched_schools7,$dup_keys).'.'.'<br>';
      
      $schools_opt7->create_matched_recs();
      
      $schools_opt7->update_tables();
  
      echo 'Reserving matched preferred schools for 7th option and updating linked tables'.'<br>';
      echo "Procedure completed sucessfully!".'<br>';
      
      ?>