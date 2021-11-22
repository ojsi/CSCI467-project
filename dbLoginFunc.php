<?php
/*
 * CSCI 467/1 Fall 2021
 * Group 2B
 */


/**
 * This function connects to the specified database and returns the PDO
 * object for that connection.
 *
 * This function is NOT exception-safe.
 *
 * @param host the database host
 * @param dbname the name of the database
 * @param name the name of the user to login to
 * @param passwd the password for the user
 *
 * @return the PDO object for the established connection (might throw
 * exception!)
 */
function loginToDatabase($host, $dbname, $name, $passwd)
{
	// Connect to the mariadb server like normal
	$dsn = "mysql:host=" . $host . ";dbname=" . $dbname;
	$pdo = new PDO($dsn, $name, $passwd); //make new pdo object

	// Set error mode
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	return $pdo;    //return the pdo object that was created
}

?>