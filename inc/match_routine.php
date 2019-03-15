<?php
include 'connection.php';
include 'functions.php';
 
 

class match_schs{
  
  var $t_name;
  var $t_status_col;
  var $t_ec_col;
  var $dup_keys;
  
//the below constructor provides names for specific tables and columns being updated on matching.
    public function __construct($table_name, $table_status_col, $table_ec_col){
		
      $this->t_name = $table_name;
      $this->t_status_col = $table_status_col;
      $this->t_ec_col = $table_ec_col;

  }
  
 //the below function sets the parameters used in updating.
    public function set_params($table, $status, $ec_no){
      $this->t_name =$table;
      $this->t_status_col = $status;
      $this->t_ec_col = $ec_no;
  }
  
 //the below function will be used to set the reserved ('R') flag on all matches awaiting finalization by payment.
    public function update_pref_table(){
		include ('connection.php');
       
         $sql = 'UPDATE '."$this->t_name".'
                    SET '."$this->t_status_col".' = "R"
                    WHERE '."$this->t_ec_col".' = ?';

							try {
								$results = $db->prepare($sql);
								$results->bindValue(1, $ec_no, PDO::PARAM_STR);
								$results->execute();
							} catch (Exception $e) {
								echo "Error!: " . $e->getMessage() . "<br />";
								return false;
							}
							return true;

  }
 
}
/*
public function matchit(array $raw_match, $dup_keys_col){
     
     global $matched;
     $matched = [];
    foreach ($raw_match as $key => $value) {
        if (isset($matched[$value[$dup_keys_col]]))
            continue;
        $matched[$value[$dup_keys_col]] = $value;
    }
    
    $sql_update = [];
    
    //school ID's have to be unset to avoid confusion between school ID's as EC numbers
    foreach($matched as $key => $value){
      unset($value['mcs_school_id']);
      unset($value['mps_school_id']);
      unset($value['mps2_school_id']);
      unset($value['mps3_school_id']);
      unset($value['mps4_school_id']);
      unset($value['mps5_school_id']);
      unset($value['mps6_school_id']);
      unset($value['mps7_school_id']);
      unset($value['mps8_school_id']);
      unset($value['mps9_school_id']);
      unset($value['mps10_school_id']);
      $sql_update[] = $value;
     }
   }
   */

class prep_match_data{
  
  var $raw_match = [];
  var $matched = [];
  var $sql_update = [];
  var $dup_keys_col;
  
  
   //the below constructor provides name for specific raw table and column being made unique.
    public function __construct($raw_table_var, $dup_ec_col){
		
      $this->raw_match = $table_var;
      $this->dup_keys_col = $dup_col;

  }
  
  //the below setter sets the variable parameters to be used when getting rid of duplicate matches.
    public function set_match($raw_table_var, $dup_ec_col){
		
      $this->raw_match = $table_var;
      $this->dup_keys_col = $dup_col;

  }
   
   public function get_match(){
     
    foreach ($raw_table_var as $key => $value) {
        if (isset($this->matched[$value[$this->dup_keys_col]]))
            continue;
        $this->matched[$value[$this->dup_keys_col]] = $value;
    }
    
    
    //school ID's have to be unset to avoid confusion between school ID's as EC numbers
    foreach($this->matched as $key => $value){
      unset($value['mcs_school_id']);
      unset($value['mps_school_id']);
      unset($value['mps2_school_id']);
      unset($value['mps3_school_id']);
      unset($value['mps4_school_id']);
      unset($value['mps5_school_id']);
      unset($value['mps6_school_id']);
      unset($value['mps7_school_id']);
      unset($value['mps8_school_id']);
      unset($value['mps9_school_id']);
      unset($value['mps10_school_id']);
      var $sql_update[] = $value;
     }
     
         //the below variables will be used to prepare data used to change the status of the matched schools to 'RESERVED' in both the preferred schools database and the current schools database.
       // foreach($sql_update as $key => $value){
         // $sql_updates_mps[] = $value[$ec_no];
         // $sql_updates_mcs[] = $value[$dup_keys];
      //}
      
        //the below foreach loop will be used to change the status of the matched schools to 'RESERVED' in both the preferred schools database and the current schools database.
        //foreach($sql_updates_mps as $key => $value){
         // $ec_no = $value;
          //$sql_updates_mcs[] = $value['mcs_client_ec_no'];
      //}
   }
}

$match_pro = new match_schs();

$dup_keys = 'mcs_client_ec_no';
$match_pro->matchit($matched_schools1, $dup_keys);
//foreach($match_pro->$matched){}


echo '<pre>';
var_dump($match_pro->$matched);
echo '</pre>';

?>