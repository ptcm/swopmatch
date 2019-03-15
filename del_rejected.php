<?php

include 'inc/functions.php';

$pageTitle = 'SwopMatch Handler | Delete Rejected';

$delRecs = array( //this is an array of all the tables from where the record of a rejected client must be deleted
                        "clients"=>"client_ec_no",
                        "match_current_schools"=>"mcs_client_ec_no",
                        "match_pref_provinces"=>"mpp_client_ec_no",
                        "match_pref_provinces2"=>"mpp2_client_ec_no",
                        "match_pref_districts"=>"mpd_client_ec_no",
                        "match_pref_districts2"=>"mpd2_client_ec_no",
                        "match_pref_districts3"=>"mpd3_client_ec_no",
                        "match_pref_districts4"=>"mpd4_client_ec_no",
                        "match_pref_locations"=>"mpl_client_ec_no",
                        "match_pref_locations2"=>"mpl2_client_ec_no",
                        "match_pref_locations3"=>"mpl3_client_ec_no",
                        "match_pref_locations4"=>"mpl4_client_ec_no",
                        "match_pref_locations5"=>"mpl5_client_ec_no",
                        "match_pref_towns"=>"mpt_client_ec_no",
                        "match_pref_towns2"=>"mpt2_client_ec_no",
                        "match_pref_schools"=>"mps_client_ec_no",
                        "match_pref_schools2"=>"mps2_client_ec_no",
                        "match_pref_schools3"=>"mps3_client_ec_no",
                        "match_pref_schools4"=>"mps4_client_ec_no",
                        "match_pref_schools5"=>"mps5_client_ec_no",
                        "match_pref_schools6"=>"mps6_client_ec_no",
                        "match_pref_schools7"=>"mps7_client_ec_no",
                        "match_pref_schools8"=>"mps8_client_ec_no",
                        "match_pref_schools9"=>"mps9_client_ec_no",
                        "match_pref_schools10"=>"mps10_client_ec_no"
                      );
                      
$del_detail = [];


      //count the number of rejected records to be deleted
      $rejectedCount = count($rejected);
      
      echo 'The total number of rejected clients that will be deleted is '.$rejectedCount.'.'.'<br>';

    foreach($rejected as $key=>$value){
        foreach($value as $ecKey=>$ec_num){
            foreach($delRecs as $table=>$column){
                try{$sql = $db->query("SELECT count(".$column.") FROM ".$table." WHERE ".$column." = '$ec_num'");
                    $count = $sql->fetchColumn();
                }catch (Exception $e){
        			echo 'Failed to count selection from table';
        			exit;
                }
                
                //add to $del_detail array if a record is found
                if(!empty($count)){
                    $del_detail[] = array($ec_num=>$table);
                }
            }
            delete_rejected($ec_num);
        }
    }
    
    
        echo '<pre>';
        print_r($del_detail);
        echo '</pre>';
    
?>