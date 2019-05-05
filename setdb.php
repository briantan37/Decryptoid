<?php
    require_once 'dbinfo.php';
    $connection = new mysqli($hn, $un, $pw, $db);
    if ($connection->connect_error) die($connection->connect_error);
    $query = "CREATE TABLE IF NOT EXISTS credentials (
        /*Defined by the ITEF, which states that the maximum length 
        of an email should not exceed 254 characters */
        email VARCHAR(255) NOT NULL,
        username VARCHAR(16) NOT NULL UNIQUE,
        /*Passwords hashed with ripemd128 will produce 32 characters */
        password VARCHAR(32) NOT NULL,
        /*Length used by US government agencies and companies */
        firstname VARCHAR(35) NOT NULL,
        lastname VARCHAR(35) NOT NULL,
        /*As stated in PHP Documentation, salts generated with bcrypt can expand 
        beyond 60 characters (255 characters would be a good choice) */
        salt1 VARCHAR(255) NOT NULL,
        salt2 VARCHAR(255) NOT NULL
        )";
    $result = $connection->query($query);

    // $query = "CREATE TABLE IF NOT EXISTS records (
    //     id SMALLINT NOT NULL AUTO_INCREMENT,
    //     username VARCHAR(16) NOT NULL,
    //     name VARCHAR(32) NOT NULL,
    //     /*We dont know how big of a text file the user will add, therefore 
    //     we will use TEXT datatype to store the content */
    //     content TEXT,
    //     PRIMARY KEY (id)
    //     )";
    // $result = $connection->query($query);
?>