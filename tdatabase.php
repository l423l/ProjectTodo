<?php

    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "todolist";
    $conn = "";

    try{
        $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
    }
    catch(mysqli_sql_exception){
        echo"Couldn't not connect <br>";
    }

    /*
    
        The Variables for the database

        name = todolist

        table_name = lists
        and 4 tables

        Variables

        id int(16) primary null no default None AI(AUTO_INCREMENT)
        task varchar(255) utf8mb4_general_ci null yes default null
        created_at timestamp null no default current_timestamp()
        completed tinyint(1) null no default none
    
    */
?>
