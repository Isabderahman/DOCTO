<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient</title>
</head>
<body>
<?php
session_start();
require("connexion.php");
if (empty($_SESSION['login']) and (empty(['userpass']))) {
    header(":deconnexion.php");
}
try {
    $query = "SELECT * from patient";
    $resultat = $conn->prepare($query);
    echo "<table><tr><th>codePatient</th><th>nomPatient</th><th>adressePatient</th><th>dateNaissance</th><th>sexePatient</th><th>Action</th></tr>";
    if ($row = $resultat->execute()) {
        while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>
        <td>' . $row['codePatient'] . '</td>
        <td>' . $row['nomPatient'] . '</td>
        <td>' . $row['adressePatient'] . '</td>
        <td>' . $row['dateNaissance'] . '</td>
        <td>' . $row['sexePatient'] . '</td>
        <td>
            <a id="supp" href="supprimerP.php?codeP=' . $row['codePatient'] . '" onclick="return confirm(\'Vous êtes sûr que vous voulez supprimer?\')">Supprimer</a>
            <a href="modifierP.php?codeP=' . $row['codePatient'] . '">Modifier</a>
        </td>
    </tr>';
        }
        echo "</table>";
    }
} catch (PDOException $e) {
    $test = $e->getMessage();
    header("location: login.php?test=$test");
}
?>
</body>
</html>
