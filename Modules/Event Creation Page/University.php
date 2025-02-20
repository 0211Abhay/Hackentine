<?php

include '../Includes/db_connect.php';

function getAllUniversities() {
    global $conn; // Use the existing database connection

    $query = "SELECT id, name FROM universities ORDER BY name ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Example usage
// $universities = getAllUniversities($conn);
// print_r($universities);

?>
