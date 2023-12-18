<?php
require '../Database/db_connect.php';

session_unset();
session_destroy();
header("Location: login.php")
?>