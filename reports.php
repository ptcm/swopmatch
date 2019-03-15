<?php
ob_end_flush();
ob_start();
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('html_errors', 1);


include 'inc/functions.php';

unset($error_message);

//set variable for report data and sort order
$rep_data = '';
$sort = '';

if(empty($_SESSION['logged_status']) ||
    ($_SESSION['logged_status'] != 'SU' && $_SESSION['logged_status'] != 'AD' && $_SESSION['logged_status'] != 'MA')){ //checks if an administrator or a marketing agent is logged in  

    $error_message = "Whoa! You do not have rights to view this report!";
}
/*
if(isset($_POST['delete'])){
  if(delete_client(filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_STRING))){
    header('Location:clients_list.php?msg=Client+Deleted');
  }else{
    header('Location:clients_list.php?msg=Unable+to+delete+Client');
    exit;
  }
}
*/
if(isset($_GET['msg'])){
  $error_message = trim(filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_STRING));
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
           
       //declare a variable for the agent
       if(!empty($_SESSION['logged_status']) && $_SESSION['logged_status'] != "MA"){
        $agent = filter_input(INPUT_POST, 'agent', FILTER_SANITIZE_STRING);
       }else{
         $agent = filter_input(INPUT_POST, 'logged_agent', FILTER_SANITIZE_STRING); 
       }
   
       //declare a variable for the report type
       if(!empty($_POST['rep_type'])){
        $repType = filter_input(INPUT_POST, 'rep_type', FILTER_SANITIZE_STRING);
       }else{
           $error_message = 'Whoa! Select the report type, at least!';
       }
        
       //declare a variable of pattern to search
       if(!empty($_POST['search']) && empty($_POST['start_date'])){
        $search = trim(filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING));
       }
       
       //declare a variable for sort order
       if(!empty($_POST['sort_order'])){
        $sort = filter_input(INPUT_POST, 'sort_order', FILTER_SANITIZE_STRING);
       }
   
       //declare a variable for the search parameter
       if(!empty($_POST['search_column'])){
            $column = filter_input(INPUT_POST, 'search_column', FILTER_SANITIZE_STRING);
       }elseif(!empty($_POST['start_date']) && !empty($_POST['end_date'])){
            $column = 'client_date_created';
       }elseif(!empty($_POST['rep_type'])){
            $column = 'client_last_name';
       }
   
       //declare a variable for range start date if filter is by date range
       if(!empty($_POST['start_date'])){
        $instartdate = trim(filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_STRING));
            if (!isDate($instartdate)) {
                $error_message = 'Whoa! Invalid Date';
            }else{
                $reform_startdate = str_replace('/', '-', $instartdate);
                $startdate = strtotime($reform_startdate);
            }
   
       //declare a variable for range end date if filter is by date range
       if(isset($_POST['end_date']) &&!empty($_POST['end_date'])){
        $inenddate = trim(filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_STRING));
            if (!isDate($inenddate)) {
                $error_message = 'Whoa! Invalid Date';
            }else{
                $reform_enddate = str_replace('/', '-', $inenddate);
                $enddate = strtotime($reform_enddate);
            }
        }
    }  
}

    //declare a variable for select statement
       if(!empty($_POST['rep_type']) && $_POST['rep_type'] == 'agent_report'){
        $SELECT = 'SELECT * FROM clients 
                    INNER JOIN match_current_schools 
                    ON clients.client_ec_no = match_current_schools.mcs_client_ec_no';
       }elseif(!empty($_POST['rep_type'])){
        $SELECT = 'SELECT * FROM clients';
       }

if(isset($_GET['agent']) && $_GET['agent'] == $_SESSION['logged_in'] && $_SESSION['logged_status'] == "MA"){
    $CONDITION = ' WHERE  client_agent_id = "'.$_SESSION['logged_in'].'" AND client_status <> "N"';
}elseif(!empty($search) && !empty($column)){
    $CONDITION = ' WHERE '.$column.' LIKE "%'.$search.'%" AND client_status NOT IN ("SU", "AD", "N")';
}elseif(!empty($startdate) && !empty($enddate)){
    $CONDITION = ' WHERE  client_status NOT IN ("SU", "AD", "N")';
}elseif(!empty($repType) && $repType == 'new_clients'){
    $CONDITION = ' WHERE  client_status = "N"';
}elseif(!empty($repType) && $repType == 'agent_report'){
    $CONDITION = ' WHERE  clients.client_agent_id = "'.$agent.'" AND clients.client_status <> "N"';
}else{
    $CONDITION = ' WHERE client_status NOT IN ("SU", "AD", "N")';
}

//declare a variable for sort order
if(!empty($sort) && $sort == 'first'){
    $ORDER = ' ORDER BY client_first_name ASC, client_last_name, client_ec_no';
}elseif(!empty($sort) && $sort == 'entry_date'){
    $ORDER = ' ORDER BY client_id ASC';
}elseif(!empty($repType) && $repType == 'new_clients'){
    $ORDER = ' ORDER BY client_id ASC';
}else{
    $ORDER = ' ORDER BY client_last_name ASC, client_first_name, client_ec_no';
}

//create an array to use as report data if query is by date range
if(!empty($startdate) && !empty($enddate)){
   try{$results = $db->query($SELECT.$CONDITION.$ORDER);

	}catch (Exception $e){
			echo 'Failed to retrieve clients';
			exit;
	}

$clients = $results->fetchAll(PDO::FETCH_ASSOC);

  //re-format string date to date format and remove records our of range
  foreach($clients as $key=>$value){
    foreach($value as $colName=>$colValue){
      if($colName == 'client_date_created'){
        //remove hours, minutes, and seconds
        $stringDate = substr($colValue, 0, 10);
        $rawDate = date_create_from_format('d-m-Y', $stringDate);
        //make the format more readable
        $colValue = strtotime(date_format($rawDate, 'd-m-Y'));
        //remove all records out of range
        if(($colValue < $startdate) || ($colValue > $enddate)){
          unset($clients[$key]);
        }
      }
    }   
  }
}

//choose the report data to use
if(!empty($clients)){
    $rep_data = $clients;
}elseif(empty($error_message) && !empty($repType)){
    $rep_data = get_clients_list($SELECT, $CONDITION, $ORDER);
}

$pageTitle = 'SwopMatch Handler | Reports';

include 'inc/header.php';

?><div class="row">
    <div class="col-1">
    </div>
        <div class="col-10">
            <div class="container">
                <div class="jumbotron my-1 py-3">
                    <form action = 'reports.php' method = 'post' id='search-form'>
                        <div id='filter_name' class = "">
                            <?php if(!empty($error_message)){
                                        echo '<div class = "alert text-center">';
                                        echo '<span class = "err">'.$error_message.'</span>';
                                        echo '</div>';
                                    
                                    goto restart_here;
                            	}
                            	
                            if(!empty($_SESSION['logged_status']) &&
                                ($_SESSION['logged_status'] == "SU") ||
                                !empty($_SESSION['logged_status']) &&
                                ($_SESSION['logged_status'] == "AD") ||
                                (isset($_GET['agent']) && $_GET['agent'] == $_SESSION['logged_in']) ||
                                (!empty($_POST['logged_agent']) && $_POST['logged_agent'] == $_SESSION['logged_in'])){ ?>
                            <div class="row">
                              <div class="col-3"></div>
                              <div class="form-group align-self-center col-2 pr-0">
                                <h5><em>Report Type</em></h5>
                                </div>
                                <div class="form-group col-md-4">
                                <select name = "rep_type" id = "rep_type" class="form-control mySelect"<?php if($_SESSION['logged_status'] == "MA"){echo 'readonly';} ?>>
                                  <?php if($_SESSION['logged_status'] != "MA"){?>
                                  <option value="" selected disabled>Please select one</option>
                                    <option value="clients"  id="clients_list">Clients List</option>
                                    <option value="new_clients" id="new_clients">New Clients</option>
                                  <?php } ?>
                                    <option value="agent_report" id="agent_report">Agent Report</option>
                                </select>
                              </div>
                              <div class="col-3"></div>
                              </div>
                        </div>
                        <div id = "agent_code"  style = "display: none;">
                            <div class="row">
                              <div class="col-3"></div>
                              <div class="form-group align-self-center col-2 pr-0">
                                <h5><em>Agent Code</em></h5>
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="text" name='agent' id='agent' class="form-control"/>
                                </div>
                              <div class="col-3"></div>
                              </div>
                        </div>
                        <div class="row">
                          <div class="col-3"></div>
                          <div class="form-group align-self-center col-2 pr-0">
                            <h5><em>Search Type</em></h5>
                            </div>
                            <div class="form-group col-md-4">
                            <select id = "search_type" class="form-control">
                              <option value="" selected disabled>Please select one</option>
                                <option value="c_name"  id="c_name">Client Name</option>
                                <option value="entry_date" id="entry_date">Entry Date</option>
                            </select>
                          </div>
                        </div> 
                          <div class="col-3"></div>
                        <div id="by_name" class="form-row" style = "display: none;"> 
                          <div class="col-2"></div>
                          <div class="form-group col-md-4">
                              <input type="text" name='search' id='search' class="form-control" placeholder = 'Enter your search string here'/>
                          </div>
                          <div class="form-group col-md-4">
                            <select name = "search_column" class="form-control">
                                <option value="">Select Filter</option>
                                <option value="client_first_name">First Name</option>
                                <option value="client_last_name">Last Name</option>
                            </select>
                          </div> 
                          <div class="col-2"></div>
                        </div>
                        <div id='date_range' class="form-row" style = "display: none;">
                          <div class="col-2"></div>
                          <div class="form-group col-md-4">
                              <label for='start_date'><b>Start Date:</b></label>
                              <input type="text" name='start_date' id='start_date' class="form-control" placeholder = 'DD/MM/YYYY'/>
                          </div>
                          <div class="form-group col-md-4">
                              <label for='end_date'><b>End Date:</b></label>
                              <input type="text" name='end_date' id='end_date' class="form-control" placeholder = 'DD/MM/YYYY'/>
                          </div>
                          <div class="col-2"></div>
                        </div>
                        <div class="row">
                          <div class="col-3"></div>
                          <div class="form-group align-self-center col-2 pr-0">
                            <h5><em>Sort Order</em></h5>
                            </div>
                            <div class="form-group col-md-4">
                            <select name = "sort_order" class="form-control">
                                <option value="">Sort Order</option>
                                <option value="first">First Name</option>
                                <option value="last">Surname</option>
                                <option value="entry_date">Entry Date</option>
                            </select>
                          </div>
                          <div class="col-3"></div>
                        </div>
                        <?php
                        if(!empty($_GET['agent'])){
                                echo '<input type="hidden" value="'.$_GET['agent'].'" name="logged_agent"/>';
                          }
                        ?>
                          <div id = 'run_rep' class="row my-1">
                              <div class="col-5"></div>
                              <div class="col-md-4">
                                  <button type="submit" id="run" class="btn btn-primary btn-lg btn-block">Run</button>
                              </div>
                              <div class="col-3"></div>
                          </div>
                    	<?php 
                            restart_here:
                        ?>
                        </div>
                    </form>
    
                    <div class="jumbotron my-1 py-3"<?php if(empty($repType) || isset($error_message)){
                                echo 'style = "display: none;"';
                            } ?>>
                    			
                	<div id ='clients-list'>
                	    <div class="table-responsive-sm">
                    		<table class = "table  table-bordered table-sm  table-striped">
                    		    <thead class = "thead-dark">
                        			<tr>
                                        <th scope="col-auto">#</th>
                        				<th scope="col-auto">A/C Number</th>
                        				<th scope="col-auto">First Name</th>
                        				<th scope="col-auto">Last Name</th>
                                <?php if(!empty($clients) || $repType == 'new_clients' || $sort == 'entry_date'){ ?>
                        				<th scope="col-auto">Entry Date</th> 
                                <?php }elseif($repType == 'agent_report'){ ?>
                        				<th scope="col-auto">Current School</th>
                        				<th scope="col-auto">Entry Date</th>
                        				<th scope="col-auto">Comm. Status</th>
                        				<th scope="col-auto">Payment Ref#</th>
                                <?php } ?>
                        			</tr>
                    			</thead>
                    			    <?php $rowNum = 1; ?>
                        			<tbody>
                        			<?php
                        			if(!empty($rep_data)){
                        			foreach ($rep_data as $item){
                        			    if($repType != 'agent_report'){
                        				echo '<tr><td>'.$rowNum; $rowNum++.'</td>';
                        				echo '<td><a href="Account_manage.php?id='.$item['client_ec_no'].'">'.strtoupper($item['client_ec_no']).'</a></td>';
                        			    }else{
                        			     echo '<td>'.$rowNum; $rowNum++.'</td>';
                        			     echo '<td>'.strtoupper($item['client_ec_no']).'</td>';
                        			    }
                        				echo '<td>'.ucwords(strtolower($item['client_first_name'])).'</td>'.
                        				'<td>'.ucwords(strtolower($item['client_last_name'])).'</td>';
                                if($repType == 'agent_report'){ //for agent report only
                                      echo '<td>'.ucwords(strtolower(get_school_name($item['mcs_school_id']))).'</td>'.
                                      '<td>'.ucwords(strtolower($item['client_date_created'])).'</td>'.
                                      '<td>'.$item['client_agent_comm_status'].'</td>'.
                                      '<td>'.ucwords(strtolower($item['client_agent_pay_ref'])).'</td>';
                                }elseif(!empty($clients) || $repType == 'new_clients' || $sort == 'entry_date'){
                                    echo '<td>'.ucwords(strtolower($item['client_date_created'])).'</td>';
                                }
                                echo '</tr>';
                        			}
                        		if($repType == 'agent_report'){
                        		    echo '<tr><td><b>A/C Bal.:</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td><b>$'.get_unpaid_comm($item['client_agent_id']).'.00</b></td></tr>';
                        		}
                        		} ?>
                        		</tbody>
                    		</table>
                		</div>
                	</div>
                	</div>
                </div>
            </div>
        </div>
        <div class="col-1"></div>
<?php } include("inc/footer.php"); ?>