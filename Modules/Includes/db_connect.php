<?php
    $host = 'localhost:3307';
    $dbname = 'event_management';
    $dbusername = 'root';
    $dbpassword = '';

    try
    {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername,$dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e)
    {
        die("Connection Failed : ". $e->getMessage());
    }
?>