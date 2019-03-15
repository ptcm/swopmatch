<?php
ob_start();
/* User login process, checks if user exists and password is correct */
$_SESSION['ec_number'] = '';

// Escape A/C Number to protect against SQL injections
$ecNumber = trim(strtoupper(filter_input(INPUT_POST, 'ec_number', FILTER_SANITIZE_STRING)));
if($ecNumber){
    $_SESSION['ec_number'] = $ecNumber;
}

//cast $ecNumber to integer for agents
if(strlen($ecNumber) <= 4){
    $ecNumber = intval($ecNumber);
}

//query the user with the specified A/C Number
if(!is_int($ecNumber)){
    $result = $db->query("SELECT * FROM clients WHERE client_ec_no = '$ecNumber'");
}else{
    $result = $db->query("SELECT * FROM agents WHERE agent_ac_no = '$ecNumber'");
}

//the query results are put in an array
$user = $result->fetchAll(PDO::FETCH_ASSOC);

//the query results should either contain one or no rows
$num_rows = count($user);

//declare a variable for the client status
if(!empty($user[0]['client_status'])){
    $c_status = $user[0]['client_status'];
}

if (!empty($c_status) && $c_status === 'R'){ // User registration rejected 
    $_SESSION['message'] = "Whoa! Registration of client with A/C Number "."'".$ecNumber."'"." was rejected. Please call/walk into our office on/before the next 28th Day of the month if you think your registration should have been approved!";
}elseif ($num_rows == 0){ // User doesn't exist 
    $_SESSION['message'] = "Whoa! Client with A/C Number "."'".$ecNumber."'"." doesn't exist!";
}
else{ // client exists
    
    if(!is_int($ecNumber)){
        if(password_verify($_POST['password'], $user[0]['client_password'])  || $_POST['password'] == $user[0]['client_ranum']){
    
            //declare the status variable and session 
            $status = get_status($ecNumber);
            $_SESSION['logged_status'] = $user[0]['client_status'];
            
            //if account is new and has not been approved by the admin yet
            if($status === 'N'){
               $_SESSION['message'] = "Thank you for visiting our site! Your account has not been activated yet. Please pay your registration fee of $2.00 using our PatchIT Ecocash Biller Code 204320 and your A/C # (".$ecNumber.") within 48hrs from your registration time and your profile will be activated. Together we will surely get there..."; 
            }else{
                // This is how we'll know the user is logged in
                $_SESSION['logged_in'] = $_SESSION['ec_number'];
                
                //echo $_POST['password'];
               header("location: Account_manage.php?id=$ecNumber");
            }
        }else{ //if password has been entered but does not match
            $_SESSION['message'] = "Whoa! You have entered wrong password, try again!";
        }
    }else{
    
        if( password_verify($_POST['password'], $user[0]['agent_password'])){
            
            //declare the status variable and session variable
            $status = get_status($ecNumber);
            $_SESSION['logged_status'] = $user[0]['agent_status'];
            
            //if account is deactivated
            if($status === 'D'){
               $_SESSION['message'] = "Your account has been DEACTIVATED. Please contact PatchIT Offices for more information on how to reactivate it."; 
            }else{
                // This is how we'll know the user is logged in
                $_SESSION['logged_in'] = $_SESSION['ec_number'];
                
                //echo $_POST['password'];
                header("location: reports.php?agent=$ecNumber");
            }
        }else{ //if password has been entered but does not match
            $_SESSION['message'] = "Whoa! You have entered wrong password, try again!";
        }
    }
}
    
ob_end_flush();

?>