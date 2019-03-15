
<?php
include 'class_lib.php';

      echo 'Please relax while the engine sorts it for you!'.'<br>';
      echo 'Preparing matched preferred schools 5th option'.'<br>';
            
      //$schools_opt5 reserves all schools in table match_pref_schools5 matched with current schools
      $schools_opt5 = new prep_match_data($matched_schools5,$dup_keys);
      $dup_keys = 'mcs_client_ec_no';
      echo 'The number of unique matches found is '.$schools_opt5->get_match($matched_schools5,$dup_keys).'.'.'<br>';
      
      $schools_opt5->create_matched_recs();
      
      $schools_opt5->update_tables();
  
      echo 'Reserving matched preferred schools for 5th option and updating linked tables'.'<br>';
      echo "Procedure completed sucessfully!".'<br>';
      
      ?>