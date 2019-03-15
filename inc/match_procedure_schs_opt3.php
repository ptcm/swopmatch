
<?php
include 'class_lib.php';

      echo 'Please relax while the engine sorts it for you!'.'<br>';
      echo 'Preparing matched preferred schools 3rd option'.'<br>';
            
      //$schools_opt3 reserves all schools in table match_pref_schools3 matched with current schools
      $schools_opt3 = new prep_match_data($matched_schools3,$dup_keys);
      $dup_keys = 'mcs_client_ec_no';
      echo 'The number of unique matches found is '.$schools_opt3->get_match($matched_schools3,$dup_keys).'.'.'<br>';
      
      $schools_opt3->create_matched_recs();
      
      $schools_opt3->update_tables();
  
      echo 'Reserving matched preferred schools for 3rd option and updating linked tables'.'<br>';
      echo "Procedure completed sucessfully!".'<br>';
      
      ?>