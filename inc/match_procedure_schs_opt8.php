
<?php
include 'class_lib.php';

      echo 'Please relax while the engine sorts it for you!'.'<br>';
      echo 'Preparing matched preferred schools 8th option'.'<br>';
            
      //$schools_opt8 reserves all schools in table match_pref_schools8 matched with current schools
      $schools_opt8 = new prep_match_data($matched_schools8,$dup_keys);
      $dup_keys = 'mcs_client_ec_no';
      echo 'The number of unique matches found is '.$schools_opt8->get_match($matched_schools8,$dup_keys).'.'.'<br>';
      
      $schools_opt8->create_matched_recs();
      
      $schools_opt8->update_tables();
  
      echo 'Reserving matched preferred schools for 8th option and updating linked tables'.'<br>';
      echo "Procedure completed sucessfully!".'<br>';
      
      ?>