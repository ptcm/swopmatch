<?php
include 'class_lib.php';

      echo 'Please relax while the engine sorts it for you!'.'<br>';
      echo 'Preparing town to town matches'.'<br>';
            
      //declare a variable with the matching conditions
      $conditions = "ON
          mcs.mcs_town_id = co_prefs.town_id 
          AND co_prefs.curr_town_id != 0
          AND mcs.mcs_town_id != 0
          AND (
                (mcs.mcs_pref_town_id = co_prefs.curr_town_id) OR 
                (mcs.mcs_pref_town2_id = co_prefs.curr_town_id) OR 
                (mcs.mcs_pref_town3_id = co_prefs.curr_town_id)
              )
          AND co_prefs.level_taught = mcs.mcs_client_level_taught 
          AND(
              (
                  (co_prefs.sub1_id = mcs.mcs_sub1_id) && (mcs.mcs_sub1_id != 0)
              ) OR(
                  (co_prefs.sub1_id = mcs.mcs_sub2_id) && (mcs.mcs_sub2_id != 0)
              ) OR(
                  (co_prefs.sub2_id = mcs.mcs_sub1_id) && (mcs.mcs_sub1_id != 0)
              ) OR(
                  (co_prefs.sub2_id = mcs.mcs_sub2_id) && (mcs.mcs_sub2_id != 0)
              )
          )
          AND co_prefs.status = 'A' 
          AND mcs.mcs_status = 'A' 
        ORDER BY
            mcs.mcs_id";
      
      //declare an array of raw matched records
      $matched_records = matched_records($conditions);
      
      //$town_to_town reserves all matched records in relative tables 
      //and deactivates the reserved option in other linked tables
      $town_to_town = new prep_match_data();
            
      $dup_keys1 = 'mcs_client_ec_no';
      $dup_keys2 = 'EC_NO';
      echo 'The number of unique matches found is '.$town_to_town->get_match($matched_records,$dup_keys1, $dup_keys2).'.'.'<br>';
      
      $town_to_town->create_matched_recs();

      $town_to_town->update_tables();
  
      echo 'Reserving matched locations and updating linked tables'.'<br>';
      echo "Procedure completed sucessfully!".'<br>';
      
      ?>