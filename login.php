<?php
    require_once 'dbinfo.php';
    require_once 'htmlbuilder.php';
    require_once 'sanitizer.php';

    $connection = new mysqli($hn, $un, $pw, $db);
    if ($connection->connect_error) die($connection->connect_error);
    //Set auto expiration to 1 day
    ini_set('session.gc_maxlifetime', 60 * 60 * 24);
    
    //Create a session
    session_start();
    if($_SESSION != NULL) {
        buildHTML("slogin" , "Welcome back, " . $_SESSION['fn'] . " " . $_SESSION['ln']);
        return;
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // pressed login button
        if (isset($_POST['login'])) {
            //Create an array to validate if the user entered all the field
            $requiredLogin = array('un', 'pw');
            if(isset($_POST['un'], $_POST['pw']) && !checkEmpty($requiredLogin, $_POST)) {
                //Both the input fields are not empty
                $un_temp = mysql_entities_fix_string($connection, $_POST['un']);
                $pw_temp = mysql_entities_fix_string($connection, $_POST['pw']);
                //Build query
                $query = "SELECT * FROM credentials WHERE username = '$un_temp'";
                $result = $connection->query($query);
                if(!$result) die($connection->error);
                else if ($result->num_rows) {
                    //There is a record in the database
                    //Retrieve the data
                    $row = $result->fetch_array(MYSQLI_NUM);
                    $salt1 = $row[5];
                    $salt2 = $row[6];
                    $result->close();
                    //Check the user input of password against the database;
                    $token = hash('ripemd128', "$salt2$pw_temp$salt1");
                    if($token == $row[2]) {
                        //The user's password and db's password match
                        //If this is client's first session, create a session id
                        if (!isset($_SESSION['initiated'])) {
                            session_regenerate_id();
                            $_SESSION['initiated'] = 1;
                        }
                        if (!isset($_SESSION['count'])) $_SESSION['count'] = 0;
                        else ++$_SESSION['count'];
                        //Add necessary field for session's data
                        $_SESSION['username'] = $un_temp;
                        $_SESSION['fn'] = $row[3];
                        $_SESSION['ln'] = $row[4];
                        $_SESSION['email'] = $row[0];
                        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
                        $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
                        //Display successful login message and redirect to index.php
                        buildHTML("slogin", "Hi $row[3] $row[4], you are now logged in as $row[0]");
                    }
                    else {
                        // Password is incorrect
                        buildHTML("elogin", "Incorrect username/password");
                        return;
                    }
                }
                else {
                    //There is no user name associated in the db
                    buildHTML("elogin", "Incorrect username/password");
                    return;
                }
            }
            else {
                //One or both of the input fields are empty
                buildHTML("elogin", "Must enter both fields!");
            }
        }
        // pressed create account button
        else if(isset($_POST['create'])) {
            //Create an array to validate if the user entered all fields
            $requiredCreate = array('un', 'pw', 'fn', 'ln', 'email');
            if(isset($_POST['un'], $_POST['pw'], $_POST['email'], $_POST['fn'], $_POST['ln']) && !checkEmpty($requiredCreate, $_POST)) {
                //sanitize inputs
                $un_temp = mysql_entities_fix_string($connection, $_POST['un']);
                $email_temp = mysql_entities_fix_string($connection, $_POST['email']);
                //Build a query to check if username and email are already in the database;
                $query = "SELECT * FROM credentials WHERE username = '$un_temp' || email = '$email_temp'";
                $result = $connection->query($query);
                //Throw an error if sql execution went wrong
                if(!$result) die($connection->error);
                //Check if there is no rows associated with email and username
                else if (!$result->num_rows){
                    //Sanitize the rest of the input
                    $fn_temp = mysql_entities_fix_string($connection, $_POST['fn']);
                    $ln_temp = mysql_entities_fix_string($connection, $_POST['ln']);
                    $pw_temp = mysql_entities_fix_string($connection, $_POST['pw']);
                    //create salt using bcrypt algorithm;
                    $salt1_temp = password_hash($pw_temp, PASSWORD_DEFAULT);
                    $salt2_temp = password_hash($pw_temp, PASSWORD_DEFAULT);
                    //create the hashed password
                    $pw_hashed = hash('ripemd128', "$salt2_temp$pw_temp$salt1_temp");
                    //Query building
                    $query = "INSERT INTO credentials VALUES('$email_temp', '$un_temp', '$pw_hashed', '$fn_temp', '$ln_temp', '$salt1_temp', '$salt2_temp')";
                    $result = $connection->query($query);
                    if(!$result) die($connection->error);
                    //Display succesful account creation message to client
                    buildHTML("screate", "Successfully Created An Account, Please Login");
                }
                else {
                    //There is already a record in the database
                    buildHTML("ecreate", "Username/email is taken");
                    //htmlBuilder(2, null);
                    return;
                }
            }
            else {
                //One or both fields are empty
                buildHTML("ecreate", "Must enter all fields");
            }
        }
        //pressed the back to login button
        else if (isset($_POST['loginView'])) {
            buildHTML("login", null);
        }
        else {
            // pressed Or Sign Up button
           buildHTML("create", null);
        }
    }
    else {
        //GET, first time request
        buildHTML("login", null);
    }
?>