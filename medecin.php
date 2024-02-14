<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medecin</title>
</head>

<body>
    <?php
    session_start();
    require("connexion.php");
    if (empty($_SESSION['login']) and (empty(['userpass']))) {
        header(":deconnexion.php");
    }
    try {
        $query = "SELECT * from Medecin";
        $resultat = $conn->prepare($query);
        echo "<table><tr><th>Code medecin</th><th>nom medecin</th><th>tel medecin</th><th>date Embauche</th><th>specialisite</th><th>Action</th></tr>";
        if ($row = $resultat->execute()) {
            while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>
                    <td> <form method="post">  <input type="submit" value=' . $row['codeMedecin'] . ' name=' . $row['codeMedecin'] . '></td>
                    <td>' . $row['nomMedecin'] . '</td>
                    <td>' . $row['telMedecin'] . '</td>
                    <td>' . $row['dateEmbauche'] . '</td>
                    <td>' . $row['SpecialiteMedecin'] . '</td>
                    <td>
                        <a id="supp" href="supprimerMed.php?codeM=' . $row['codeMedecin'] . '" onclick="return confirm(\'Vous êtes sûr que vous voulez supprimer?\')">Supprimer</a>
                        <a href="modifierMed.php?codeM=' . $row['codeMedecin'] . '">Modifier</a>
                    </td>
                </tr>';
                if (isset($_POST[$row['codeMedecin']])) {
                    $resu = $conn->prepare('SELECT * FROM rdv WHERE codeMedecin=:para');
                    $resu->bindParam('para', $row['codeMedecin']);
                    if ($resu->execute()) {
                        echo "<tfoot><tr><th>NUMRDV</th><th>dateRDV</th><th>heureRDV</th><th>CodePatient</th><th>CodeMedcin</th><th>Action</th></tr>";
                        while ($rowx = $resu->fetch(PDO::FETCH_ASSOC)) {
                                                    echo '<tr>
                                <td>' . $rowx['numRDV'] . '</td>
                                <td>' . $rowx['dateRDV'] . '</td>
                                <td>' . $rowx['heurRDV'] . '</td>
                                <td>' . $rowx['codePatient'] . '</td>
                                <td>' . $rowx['codeMedecin'] . '</td>
                                <td>
                                    <a id="supp" href="supprimerR.php?num_rdv=' . $rowx['numRDV'] . '" onclick="return confirm(\'Vous êtes sûr que vous voulez supprimer?\')">Supprimer</a>
                                    <a href="modifierR.php?num_rdv=' . $rowx['numRDV'] . '">Modifier</a>
                                </td>
                            </tr>';
                        }
                        echo "</tfoot>";
                    }
                }
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