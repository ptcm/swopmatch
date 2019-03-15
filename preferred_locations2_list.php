<?php
session_start();
include 'inc/header.php';
include 'inc/functions.php';

if(isset($_POST['delete'])){
  if(delete_pref_location(filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_STRING))){
    header('Location:preferred_locations_list.php?msg=Preferred+Location+Deleted');
  }else{
    header('Location:preferred_locations_list.php?msg=Unable+to+delete+location');
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
				<th>Location Name</th>
				</tr>
			<?php
			foreach (get_pref_locations2_list() as $item){
				echo '<tr><td>'.$item['mpl2_id'].'</td>'.
				'<td>'.strtoupper($item['mpl2_client_ec_no']).'</td>'.
				'<td>'.ucwords(strtolower($item['loc_name'])).'</td>';
        echo '<td><form method="post" action="preferred_locations2_list.php" onsubmit="return confirm(\'Are you sure you want to delete this school?\')">';
        echo '<input type="hidden" value="'.$item['mpl2_id'].'" name="delete"/>';
        echo '<input type="submit" value="Delete"/>';
        echo '</form>';
        echo '</td></tr>';
			}
			
			?>
		</table>
	</body>
</html>

