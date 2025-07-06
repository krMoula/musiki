<?php
$servername = "localhost";
$username = "musiki";
$password = "motdepassefort";
$database = "musiki";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
?>
