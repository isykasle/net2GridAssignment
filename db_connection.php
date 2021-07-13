<?php

    include_once __DIR__ . '/vendor/autoload.php';

    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();

    /**
        * Sets the connection to the database 
        * @return $conn the connection to a database
    */
    function OpenCon()
    {
 
        $conn = new mysqli($_ENV['dbhost'], $_ENV['dbuser'], $_ENV['dbpass'],$_ENV['db']) or die("Connect failed: %s\n". $conn -> error);
 
        return $conn;
    }

    /**
        * Closes the connection to the database 
    */
    function CloseCon($conn)
    {
    $conn -> close();
    }
   
?>