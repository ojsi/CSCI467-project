<?php
//Wesley Kwiecinski - Z1896564, Group 2B
//Quote System
//CSCI 467

//Moved logging into the database into a separate function because it was used a lot
//string $host - hostname of database
//string $dbname - database name
//string $name - username
//string $passwd - password
function login_to_database($host, $dbname, $name, $passwd)
{
    //Connect to the mariadb server like normal
    $dsn = "mysql:host=".$host.";dbname=" . $dbname;
    $pdo = new PDO($dsn, $name, $passwd); //make new pdo object
    //Set error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;    //return the pdo object that was created
}

//Gets information from given query with a pdo object
//string $query - requested sql query
//string $pdo - pdo object connected to database
function get_information($query, $pdo)
{
    $rs = $pdo->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $rs->execute();
    return ($rs->fetchAll(PDO::FETCH_ASSOC));
}
?>