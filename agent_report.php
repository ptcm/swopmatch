<?php
ob_end_flush();
ob_start();
session_start();

include 'inc/functions.php';

unset($error_message);

//set variable for report data and sort order
$rep_data = '';
$sort = '';

if(isset($_GET['agent'])){ //checks if the user wants to view the agent report
    if(($_SESSION['logged_in'] != isset($_SESSION['ec_number']) ||
        $_GET['agent'] != $_SESSION['logged_in']) && ($_SESSION['logged_status'] != 'SU' && $_SESSION['logged_status'] != 'AD')){ //checks if the $_GET['agent'] is for the agent who logged in before displaying any information  
    
        $error_message = "Whoa! You must be logged in as ".'"'.$_GET['agent'].'"'." for you to view this page!";
    }else{
        
        if(isset($_GET['msg'])){
          $error_message = trim(filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_STRING));
        }
           
               //declare a variable for the report type
                $repType = filter_input(INPUT_POST, 'rep_type', FILTER_SANITIZE_STRING);
                
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
        
            //declare a variable for select statement
               if(!empty($_POST['rep_type'])){
                $SELECT = 'SELECT * FROM clients';
               }
        
        if(!empty($search) && !empty($column)){
            $CONDITION = ' WHERE '.$column.' LIKE "%'.$search.'%" AND client_agent = '.$_SESSION['logged_in'].' AND client_status <> "N"';
        }else{
            $CONDITION = ' WHERE  client_agent = '.$_SESSION['logged_in'].' AND client_status <> "N"';
        }
        
        //declare a variable for sort order
        if(!empty($sort) && $sort == 'first'){
            $ORDER = ' ORDER BY client_first_name ASC, client_last_name, client_ec_no';
        }elseif(!empty($sort) && $sort == 'entry_date'){
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
    }
}


$pageTitle = 'SwopMatch Handler | Reports';

include 'inc/header.php';
        
        echo '<pre>';
       //print_r( $_POST);
        echo '</pre>';
        echo '<pre>';
       //var_dump($startdate);
        echo '</pre>';
        echo '<pre>';
       //var_dump($enddate);
        echo '</pre>';
        echo '<pre>';
       //print_r($rep_data);
        echo '</pre>';

?>


<form action = 'reports.php' method = 'post' id='search-form' >
    <div id='filter_name' class = "form">
<?php if(!empty($error_message)){
            echo '<div class = "hidden message">';
            echo '<span class = "err">'.$error_message.'</span>';
            echo '</div>';
        
        goto restart_here;
	} 
	
if(isset($_SESSION['logged_status']) &&
    ($_SESSION['logged_status'] == "SU") ||
    ($_SESSION['logged_status'] == "AD") ||
    ($_GET['agent'] == $_SESSION['logged_in'])){ ?>
    <div>
        <select name = "rep_type" id = "rep_type" style="
    width: 25%; margin-top: 5px">
            <option value="agent_report" id="agent_report" style = "width: 25%;">Agent Report</option>
        </select>
    </div>
    <div>
        <select id = "search_type" style="
    width: 25%; margin-top: 5px">
            <option value=""  style = "width: 25%;">Search Type</option>
            <option value="c_name"  id="c_name" style = "width: 25%;">Client Name</option>
            <option value="entry_date" id="entry_date" style = "width: 25%;">Entry Date</option>
        </select>
    </div>
    <div id="by_name" style = "display: none;">
    <input type="text" name='search' id='search' placeholder = 'Enter your search string here' style = "width: 25%;"/>
    <select name = "search_column" style="
    width: 25%; margin-top: 5px">
        <option value=""  style = "width: 25%;">Select Filter</option>
        <option value="client_first_name"  style = "width: 25%;">First Name</option>
        <option value="client_last_name"  style = "width: 25%;">Last Name</option>
    </select>
    </div>
    <div id='date_range' style = "display: none;">
        <label for='start_date'  style = "width: 25%;">Start Date:</label>
        <input type="text" name='start_date' id='start_date' placeholder = 'DD/MM/YYYY'  style = "width: 25%;"/>
        <label for='end_date'  style = "width: 25%;">End Date:</label>
        <input type="text" name='end_date' id='end_date' placeholder = 'DD/MM/YYYY'  style = "width: 25%;"/>
    </div>
    <div>
        <select name = "sort_order" style="
    width: 25%;">
            <option value=""  style = "width: 25%;">Sort Order</option>
            <option value="first"  style = "width: 25%;">First Name</option>
            <option value="last"  style = "width: 25%;">Surname</option>
            <option value="entry_date"  style = "width: 25%;">Entry Date</option>
        </select>
    </div>
    <div id = 'run_rep'>
        <input type = 'submit'  value = 'Run' class = "sub-reset" style = "width: 25%; margin-bottom: 5px" />
    </div>
	<?php 
        restart_here:
    ?>
    </div>
</form>
<div class = "form" <?php if(empty($repType) || isset($error_message)){
                echo 'style = "display: none;"';
            } ?>>
    			
	<div id ='clients-list' style="
    margin-top: 10px;
    margin-bottom: 10px;">
		<table class = "fixed_header reports">
		    <thead>
    			<tr>
    				
    				<th>A/C Number</th>
    				<th>First Name</th>
    				<th>Last Name</th>
    				<th>Current School</th>
    				<th>Entry Date</th>
    				<th>Commission Status</th>
    				<th>Payment Ref#</th>
    			</tr>
			</thead>
    			<tbody>
    			<?php
    			if(!empty($rep_data)){
    			foreach ($rep_data as $item){
    				echo '<tr>
    				<td>'.strtoupper($item['client_ec_no']).'</td>'.
    				'<td>'.ucwords(strtolower($item['client_first_name'])).'</td>'.
    				'<td>'.ucwords(strtolower($item['client_last_name'])).'</td>'.
    				'<td>'.ucwords(strtolower($item['client_curr_school'])).'</td>'.
    				'<td>'.ucwords(strtolower($item['client_date_created'])).'</td>'.
    				'<td>'.ucwords(strtolower($item['client_comm_status'])).'</td>'.
    				'<td>'.ucwords(strtolower($item['client_agent_pay_ref'])).'</td>';
            echo '</tr>';
    			}
    		} ?>
    		</tbody>
		</table>
	</div>
	</div>
   
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script> 
    <script type="text/javascript" src="js/scripts.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script src="js/additional-methods.js"></script>
    <script src="js/sign_up.js"></script>

<?php } include("inc/footer.php"); ?>
