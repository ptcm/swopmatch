
<?php
include 'class_lib.php';

      echo 'Please relax while the engine sorts it for you!'.'<br>';
      echo 'Preparing matched preferred schools 4th option'.'<br>';
            
      //$schools_opt4 reserves all schools in table match_pref_schools4 matched with current schools
      $schools_opt4 = new prep_match_data($matched_schools4,$dup_keys);
      $dup_keys = 'mcs_client_ec_no';
      echo 'The number of unique matches found is '.$schools_opt4->get_match($matched_schools4,$dup_keys).'.'.'<br>';
      
      $schools_opt4->create_matched_recs();
      
      $schools_opt4->update_tables();
  
      echo 'Reserving matched preferred schools for 4th option and updating linked tables'.'<br>';
      echo "Procedure completed sucessfully!".'<br>';
      
      ?>