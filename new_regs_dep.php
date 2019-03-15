<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('html_errors', 1);
include 'inc/functions.php';

unset($error_message);

if(empty($_SESSION['logged_status']) ||
    ($_SESSION['logged_status'] != 'SU' && $_SESSION['logged_status'] != 'AD')){ //checks if the administrator is logged in  

    $error_message = "Whoa! You do not have rights to view this report!";
}

$pageTitle = 'SwopMatch Handler | New Clients';

include 'inc/header.php';

?>
<!-- <html>
  <head>
    <link rel = "stylesheet" href = "css/styles.css">
  </head> 
	<body class = "body"> -->
	<div class = "form" >
  <?php if(!empty($error_message)){
            echo '<div class = "alert text-center">';
            echo '<span class = "err">'.$error_message.'</span>';
            echo '</div>';
            
            goto inc_footer;
    	} 
    	
    if(isset($_SESSION['logged_status']) &&
        ($_SESSION['logged_status'] == "SU") ||
        ($_SESSION['logged_status'] == "AD")){ ?>
    			
	<div id ='new-regs' style="
    margin-top: 10px;
    margin-bottom: 10px;">
		<table style = 'border: 1px solid #689c90; border-collapse: collapse; width: 100%;'>
			<tr style = 'border: 1px solid #689c90;'>
				<th style = 'border: 1px solid #689c90;'>First Name</th>
				<th style = 'border: 1px solid #689c90;'>Last Name</th>
				<th style = 'border: 1px solid #689c90;'>EC Number</th>
			</tr>
			<?php
			foreach (get_new_regs_list() as $item){
				echo '<tr style = "border: 1px solid #689c90;"><td style = "border: 1px solid #689c90; text-align: center;">'.ucwords(strtolower($item['client_first_name'])).'</td>'.
				'<td style = "border: 1px solid #689c90; text-align: center;">'.ucwords(strtolower($item['client_last_name'])).'</td>'.
				'<td style = "border: 1px solid #689c90; text-align: center;">'.$item['client_ec_no'].'</td></tr>';
			}
			
			?>
		</table>
	</div>
	<?php 
        inc_footer:
    ?>
	</div>

<?php } include("inc/footer.php"); ?>


