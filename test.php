<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connexion
$servername = "localhost";
$username = "musiki";
$password = "motdepassefort";
$database = "musiki";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("❌ Connexion échouée : " . $conn->connect_error);
}
echo "✅ Connexion à la base MariaDB réussie.<br>";

// Vérifier si la table `musiki_alts` existe
$result = $conn->query("SHOW TABLES LIKE 'musiki_alts'");
if ($result && $result->num_rows > 0) {
    echo "✅ La table <strong>musiki_alts</strong> existe.<br>";
} else {
    echo "⚠️ La table <strong>musiki_alts</strong> n'existe pas encore.<br>";
}

// Requête de test si la table existe
if ($result && $result->num_rows > 0) {
    $query = $conn->query("SELECT id, name FROM musiki_alts LIMIT 1");
    if ($query && $query->num_rows > 0) {
        $row = $query->fetch_assoc();
        echo "🎵 Premier alt dans la base : <strong>" . htmlspecialchars($row['name']) . "</strong><br>";
    } else {
        echo "ℹ️ Aucun alt trouvé pour le moment.<br>";
    }
}

$conn->close();
?>
