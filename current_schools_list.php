<?php
session_start();
include 'inc/header.php';
include 'inc/functions.php';

if(isset($_POST['delete'])){
  if(delete_current_school(filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_STRING))){
    header('Location:current_schools_list.php?msg=Current+School+Deleted');
  }else{
    header('Location:current_schools_list.php?msg=Unable+to+delete+Client');
    exit;
  }
}

if(isset($_GET['msg'])){
  $error_message = trim(filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_STRING));
}

if(isset($error_message)){
  echo '<p>'.$error_message.'</p>';
}
?>
<html>
  <head>
    <link rel = "stylesheet" href = "css/styles.css">
  </head>
	<body class = "body">
		<table>
			<tr>
				<th>Record Id</th>
				<th>EC Number</th>
				<th>School Name</th>
				<th>District Name</th>
				<th>Province Name</th>
				<th>Level Taught</th>
        <th>Subject1 Taught</th>
        <th>Subject2 Taught</th>
				</tr>
			<?php
			foreach (get_current_schools_list() as $item){
				echo '<tr><td>'.$item['mcs_id'].'</td>'.
				'<td>'.strtoupper($item['mcs_client_ec_no']).'</td>'.
				'<td>'.ucwords(strtolower($item['school_name'])).'</td>'.
				'<td>'.ucwords(strtolower($item['distr_name'])).'</td>'.
				'<td>'.ucwords(strtolower($item['province_name'])).'</td>'.
				'<td>'.ucwords(strtolower($item['mcs_client_level_taught'])).'</td>'.
        '<td>'.ucwords(strtolower($item['subject1'])).'</td>'.
        '<td>'.ucwords(strtolower($item['subject2'])).'</td>';
        echo '<td><form method="post" action="current_schools_list.php" onsubmit="return confirm(\'Are you sure you want to delete this school?\')">';
        echo '<input type="hidden" value="'.$item['mcs_id'].'" name="delete"/>';
        echo '<input type="submit" value="Delete"/>';
        echo '</form>';
        echo '</td></tr>';
			}
			//echo '<pre>';
      //print_r($item);
      //echo '</pre>';
			?>
		</table>
	</body>
</html>

