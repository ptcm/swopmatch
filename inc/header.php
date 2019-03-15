<?php 
ob_start();

include_once 'connection.php';

?>
<!doctype html>
<html lang="en">
    <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="css/footer-distributed-with-address-and-phones.css">
    <link rel = "stylesheet" href = "css/custom-styles.css">

    <title><?php echo $pageTitle; ?></title>
    <link rel="shortcut icon" type="image/png" href="inc/images/logo.png">
  </head>
  <body>
      <div class="d-flex">
        <div id="wrapper-container" class="container">
          <div class="row">
            <div id="leftScrDiv" class="col-1"></div>
                <div id="headerDiv" class="col-10">
                      <nav class="navbar  navbar-expand-lg navbar-light flex-column rounded sticky-top">
                      <div class="w-100 align-items-center d-xs-block d-md-flex">
                          <a class="navbar-brand  mx-auto font-weight-bold brand-name" href="#">SwopMatch Handler..</a>
                          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                          </button>
                      </div>
                      <div class="collapse navbar-collapse" id="navbarSupportedContent" style="z-index: 900">
                        <ul class="navbar-nav text-center drop-items">
                          <li class="nav-item active">
                            <a class="nav-link text-white" href="index.php">Home <span class="sr-only">(current)</span></a>
                          </li>
              <?php
              
                if(isset($_SESSION['logged_status']) &&  ($_SESSION['logged_status'] == 'SU')){?>
                          <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Admin Menu
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                              <a class="dropdown-item" href="pay.php">Payments</a>
                              <a class="dropdown-item" href="agents.php">Agents</a>
                              <a class="dropdown-item" href="routines.php">Routines</a>
                              <a class="dropdown-item" href="locs.php">Locations</a>
                              <a class="dropdown-item" href="client_status_change.php">Change Client Status</a>
                              <a class="dropdown-item" href="del_client.php">Delete Client</a>
                              <a class="dropdown-item" href="reports.php">Reports</a>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="reset.php">Reset Password</a>
                            </div>
                          </li><?php
                }elseif(isset($_SESSION['logged_status']) &&  ($_SESSION['logged_status'] == 'AD')){?>
                          <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Admin Menu
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                              <a class="dropdown-item" href="pay.php">Payments</a>
                              <a class="dropdown-item" href="agents.php">Agents</a>
                              <a class="dropdown-item" href="locs.php">Locations</a>
                              <a class="dropdown-item" href="reports.php">Reports</a>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="reset.php">Reset Password</a>
                            </div>
                          </li><?php } ?>
                          <li class="nav-item">
                            <a class="nav-link text-white" href="suggest.php">Suggest</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link text-white" href="#contact">Contact</a>
                          </li>
    		            <?php
    		                if(isset($_SESSION['logged_in']) && !empty($_SESSION['logged_in']) && strlen($_SESSION['logged_in']) > 4){
    		                    $ecNumber = $_SESSION['logged_in'];
    		            ?>
                          <li class="nav-item">
                            <a class="nav-link text-white btn btn-info" href="Account_manage.php?id=<?php echo $ecNumber; ?>">Client Area</a>
                          </li>
                        <?php }elseif(isset($_SESSION['logged_in']) && !empty($_SESSION['logged_in']) && strlen($_SESSION['logged_in']) <= 4){ //agent links
        		            $ecNumber = intval($_SESSION['logged_in']);
                        ?>
                          <li class="nav-item">
                            <a class="nav-link text-white" href="agents.php?agent=<?php echo $ecNumber; ?>">My Profile</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link text-white" href="reports.php?agent=<?php echo $ecNumber; ?>">My Report</a>
                          </li>
                        <?php } ?>
                          <li class="nav-item">
                            <a href="about_us.php" target = "_blank" class="nav-link text-white">About Us</a>
                          </li>
                        </ul>
                      </div>
                <?php
                        //echo '<div class="d-flex"';
                   if(empty($_SESSION['logged_in'])){
    	            echo '<span>Not logged in.</span>';
        	        }elseif(isset($_SESSION['logged_in']) && !empty($_SESSION['logged_in'])){
        	            
                      
                        //query for a user who is a client
                        if(!is_int($ecNumber)){
                        $sql = 'SELECT client_first_name FROM clients 
                            WHERE client_ec_no = ?';
                        }else{
                        //query for agent
                        $sql = 'SELECT agent_first_name FROM agents 
                            WHERE agent_ac_no = ?';
                        }
        
                          try {
                            $results = $db->prepare($sql);
                            $results->bindValue(1, $ecNumber);
                            $results->execute();
                          } catch (Exception $e) {
                            echo "Error!: " . $e->getMessage() . "<br />";
                            return false;
                          }
                        	$cname = $results->fetchAll(PDO::FETCH_ASSOC);
                        	if(!is_int($ecNumber)){
                        	    $logged = $cname[0]['client_first_name'];
                        	}else{
                        	    $logged = $cname[0]['agent_first_name'];
                        	}
                    	
        	            echo '<span  style="z-index: 2">'.$logged.' logged in.</span>';
        	        }
        	        
        	      if(empty($_SESSION['logged_in'])){
        	            echo '<a href="../index.php#ac-no" id = "ulog_in"  style="z-index: 3">(Login)</a>';
        	        }else{
        	           echo '  <a href="logout.php"  style="z-index: 3"><b>(Logout)</b></a>'; 
        	        } 
        	        
        	        
                    //echo '</div';
                ?>
                    </nav>