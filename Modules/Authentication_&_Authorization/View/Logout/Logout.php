<?php

@include '../../../Includes/db_connect.php';

session_start();
session_unset();
session_destroy();

header('Location: ../../../Authentication_&_Authorization/View/Login/login.php');

?>