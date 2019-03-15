
<?php
include '../inc/class_lib.php';

      echo 'Please relax while the engine sorts it for you!'.'<br>';
      echo 'Preparing matched preferred schools 10th option'.'<br>';
            
      //$schools_opt10 reserves all schools in table match_pref_schools10 matched with current schools
      $schools_opt10 = new prep_match_data($matched_schools10,$dup_keys);
      $dup_keys = 'mcs_client_ec_no';
      echo 'The number of unique matches found is '.$schools_opt10->get_match($matched_schools10,$dup_keys).'.'.'<br>';
      
      $schools_opt10->create_matched_recs();
      
      $schools_opt10->update_tables();
  
      echo 'Reserving matched preferred schools for 10th option and updating linked tables'.'<br>';
      echo "Procedure completed sucessfully!".'<br>';
      
      ?>