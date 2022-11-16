<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $address = $salary620 = $username = $password ="";
$name_err = $address_err = $salary620_err = $username_err =$password_err ="";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }
    
    // Validate salary620
    $input_salary620 = trim($_POST["salary620"]);
    if(empty($input_salary620)){
        $salary620_err = "Please enter the salary620 amount.";     
    } elseif(!ctype_digit($input_salary620)){
        $salary620_err = "Please enter a positive integer value.";
    } else{
        $salary620 = $input_salary620;
    }

    // Validate username
if(empty(trim($_POST["username"]))){
    $username_err = "Please enter a username.";
} elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
    $username_err = "Username can only contain letters, numbers, and underscores.";
} else{
    // Prepare a select statement
    $sql = "SELECT id FROM employees WHERE username = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        
        // Set parameters
        $param_username = trim($_POST["username"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            /* store result */
            mysqli_stmt_store_result($stmt);
            
            if(mysqli_stmt_num_rows($stmt) == 1){
                $username_err = "This username is already taken.";
            } else{
                $username = trim($_POST["username"]);
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Validate confirm password
if(empty(trim($_POST["password"]))){
    $password_err = "Please confirm password.";     
} else{
    $password = trim($_POST["password"]);
    if(empty($password_err) && ($password != $password)){
        $password_err = "Password did not match.";
    }
}
 
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($salary620_err)&& empty($username_err)&& empty($password_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO employees (name, address, salary620, username, password) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssiss", $param_name, $param_address, $param_salary620, $param_username, $param_password);
            
            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_salary620 = $salary620;
            $param_username = $username;
            $param_password = $password;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: register2.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>




<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="default.css" rel="stylesheet" type="text/css" media="all" />

<link rel="stylesheet" href="style3.css" />

</head>
<body>
<div id="header-wrapper">
<nav class="nav">
      <div class="container">
        <br>
        <img src="product-images/logo.png" alt="Logo">
        <h1 class="logo"><a href="/index.html">Welcome to Luca’s Loaves</a></h1>
        <ul>
          <li><a href="index.php" accesskey="1" title="">Home</a></li>
          <li><a href="aboutus.php" accesskey="2" title="">About US</a></li>
          <li><a href="upload.php" accesskey="3" title="">Careers </a></li>
          <li><a href="orderonline.php" accesskey="4" title="">Order online </a></li>
          <li><a href="contactus.php" accesskey="5" title="">Contact Us</a></li>
          <li><a href="register.php" accesskey="6" title="">Register</a></li>
        </ul> 
      </div>
    </nav>
</div>

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div align = "center">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="fname">Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label for="fname">Address</label>
                            <input type="text" name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $address; ?>">
                            <span class="invalid-feedback"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group">
                            <label for="fname">salary</label>
                            <input type="text" name="salary620" class="form-control <?php echo (!empty($salary620_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salary620; ?>">
                            <span class="invalid-feedback"><?php echo $salary620_err;?></span>
                        </div>
                        <div class="form-group">
                            <label for="fname">Username</label>
                            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $username_err;?></span>
                        </div>
                        <div class="form-group">
                            <label for="fname">Password</label>
                            <input type="text" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                            <span class="invalid-feedback"><?php echo $password_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </form>
                </div>
            </div>        
        </div>
    </div>
    </div>
    <footer class="footer2">  
    Luca’s bread
          </footer>
	
</body>

	


</body>
</html>
