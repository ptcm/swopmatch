
<?php
include 'class_lib.php';

      echo 'Please relax while the engine sorts it for you!'.'<br>';
      echo 'Preparing matched preferred schools 6th option'.'<br>';
            
      //$schools_opt6 reserves all schools in table match_pref_schools6 matched with current schools
      $schools_opt6 = new prep_match_data($matched_schools6,$dup_keys);
      $dup_keys = 'mcs_client_ec_no';
      echo 'The number of unique matches found is '.$schools_opt6->get_match($matched_schools6,$dup_keys).'.'.'<br>';
      
      $schools_opt6->create_matched_recs();
      
      $schools_opt6->update_tables();
  
      echo 'Reserving matched preferred schools for 6th option and updating linked tables'.'<br>';
      echo "Procedure completed sucessfully!".'<br>';
      
      ?>