<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Étudiant</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }
    </style>
</head>
<body>
   
    <?php
    include "header.php";
    include "connexion.php"; 
    
    // Connexion à la base de données
    $conn = new mysqli('localhost', 'root', '', 'goerc');

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Échec de la connexion : " . $conn->connect_error);
    }

    // Initialisation des variables
    $message = '';
    $etudiants = [];
    $nom = $prénom = $date_naissance = $Adresse = $etablissement = $matricule = $section = $resultats = $ancienDCE = $nouveauDCE = $ancienDPE = $NouveauDPE = '';

    // Envoyer les informations de l'étudiant
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        $nom = $_POST['nom'] ?? '';
        $prénom = $_POST['prénom'] ?? '';
        $date_naissance = $_POST['date_naissance'] ?? '';
        $Adresse = $_POST['Adresse'] ?? '';
        $etablissement = $_POST['etablissement'] ?? '';
        $matricule = $_POST['matricule'] ?? '';
        $section = $_POST['section'] ?? '';
        $resultats = $_POST['resultats'] ?? '';
        $ancienDCE = $_POST['ancienDCE'] ?? '';
        $nouveauDCE = $_POST['nouveauDCE'] ?? '';
        $ancienDPE = $_POST['ancienDPE'] ?? '';
        $NouveauDPE = $_POST['NouveauDPE'] ?? '';

        if ($action === 'Ajouter') {
            // Préparer la requête SQL pour l'insertion
            $sql = "INSERT INTO etudiant (nom, prénom, date_naissance, Adresse, etablissement, matricule, section, resultats, ancienDCE, nouveauDCE, ancienDPE, NouveauDPE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssssss", $nom, $prénom, $date_naissance, $Adresse, $etablissement, $matricule, $section, $resultats, $ancienDCE, $nouveauDCE, $ancienDPE, $NouveauDPE);

            if ($stmt->execute()) {
                $message = "Informations ajoutées.";
            } else {
                $message = "Erreur lors de l'ajout des informations : " . $stmt->error;
            }
            $stmt->close();
        } elseif ($action === 'Modifier') {
            // Préparer la requête SQL pour la modification
            $sql = "UPDATE etudiant SET prénom=?, date_naissance=?, Adresse=?, etablissement=?, section=?, resultats=?, ancienDCE=?, nouveauDCE=?, ancienDPE=?, NouveauDPE=? WHERE nom=? AND matricule=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssss", $prénom, $date_naissance, $Adresse, $etablissement, $section, $resultats, $ancienDCE, $nouveauDCE, $ancienDPE, $NouveauDPE, $nom, $matricule);

            if ($stmt->execute()) {
                $message = "Informations modifiées.";
            } else {
                $message = "Erreur lors de la modification des informations : " . $stmt->error;
            }
            $stmt->close();
        } elseif ($action === 'Supprimer') {
            // Préparer la requête SQL pour la suppression
            $sql = "DELETE FROM etudiant WHERE nom=? AND matricule=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $nom, $matricule);

            if ($stmt->execute()) {
                $message = "Informations supprimées.";
            } else {
                $message = "Erreur lors de la suppression des informations : " . $stmt->error;
            }
            $stmt->close();
        } elseif ($action === 'Visualiser') {
            // Récupérer tous les étudiants
            $sql = "SELECT * FROM etudiant";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $etudiants[] = $row;
                }
            } else {
                $message = "Aucune information trouvée.";
            }
        } elseif ($action === 'Charger') {
            // Charger les informations de l'étudiant par nom
            $sql = "SELECT * FROM etudiant WHERE nom=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $nom);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Remplir les inputs avec les données de l'étudiant
                $prénom = $row['prénom'];
                $date_naissance = $row['date_naissance'];
                $Adresse = $row['Adresse'];
                $etablissement = $row['etablissement'];
                $matricule = $row['matricule'];
                $section = $row['section'];
                $resultats = $row['resultats'];
                $ancienDCE = $row['ancienDCE'];
                $nouveauDCE = $row['nouveauDCE'];
                $ancienDPE = $row['ancienDPE'];
                $NouveauDPE = $row['NouveauDPE'];
            } else {
                $message = "Aucun étudiant trouvé avec ce nom.";
            }
            $stmt->close();
        }
    }

    // Fermer la connexion
    $conn->close();
    ?>

    <div>
        <h2>GESTION DES ETUDIANTS</h2>
        <form action="" method="POST">
            <label>Nom:</label>
            <input type="text" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required onblur="chargerEtudiant()"><br>
            <label>Prénom:</label>
            <input type="text" name="prénom" value="<?php echo htmlspecialchars($prénom); ?>" required><br>
            <label>Date de Naissance:</label>
            <input type="date" name="date_naissance" value="<?php echo htmlspecialchars($date_naissance); ?>" required><br>
            <label>Adresse:</label>
            <input type="text" name="Adresse" value="<?php echo htmlspecialchars($Adresse); ?>" required><br>
            <label>Établissement:</label>
            <input type="text" name="etablissement" value="<?php echo htmlspecialchars($etablissement); ?>" required><br>
            <label>Matricule:</label>
            <input type="text" name="matricule" value="<?php echo htmlspecialchars($matricule); ?>" required><br>
            <label>Section:</label>
            <input type="text" name="section" value="<?php echo htmlspecialchars($section); ?>" required><br>
            <label>Résultats:</label>
            <input type="text" name="resultats" value="<?php echo htmlspecialchars($resultats); ?>" required><br>
            <label>Ancien DCE:</label>
            <input type="text" name="ancienDCE" value="<?php echo htmlspecialchars($ancienDCE); ?>"><br>
            <label>Nouveau DCE:</label>
            <input type="text" name="nouveauDCE" value="<?php echo htmlspecialchars($nouveauDCE); ?>"><br>
            <label>Ancien DPE:</label>
            <input type="text" name="ancienDPE" value="<?php echo htmlspecialchars($ancienDPE); ?>"><br>
            <label>Nouveau DPE:</label>
            <input type="text" name="NouveauDPE" value="<?php echo htmlspecialchars($NouveauDPE); ?>"><br><br>
            <button type="submit" name="action" value="Ajouter">Ajouter</button>
            <button type="submit" name="action" value="Modifier">Modifier</button>
            <button type="submit" name="action" value="Supprimer">Supprimer</button>
            <button type="submit" name="action" value="Visualiser">Visualiser</button>
        </form>
        <?php if ($message): ?>
            <div><?php echo $message; ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Date de Naissance</th>
                    <th>Adresse</th>
                    <th>Établissement</th>
                    <th>Matricule</th>
                    <th>Section</th>
                    <th>Résultats</th>
                    <th>Ancien DCE</th>
                    <th>Nouveau DCE</th>
                    <th>Ancien DPE</th>
                    <th>Nouveau DPE</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($etudiants)): ?>
                    <?php foreach ($etudiants as $etudiant): ?>
                        <tr onclick="chargerEtudiant('<?php echo htmlspecialchars($etudiant['nom']); ?>', '<?php echo htmlspecialchars($etudiant['prénom']); ?>', '<?php echo htmlspecialchars($etudiant['date_naissance']); ?>', '<?php echo htmlspecialchars($etudiant['Adresse']); ?>', '<?php echo htmlspecialchars($etudiant['etablissement']); ?>', '<?php echo htmlspecialchars($etudiant['matricule']); ?>', '<?php echo htmlspecialchars($etudiant['section']); ?>', '<?php echo htmlspecialchars($etudiant['resultats']); ?>', '<?php echo htmlspecialchars($etudiant['ancienDCE']); ?>', '<?php echo htmlspecialchars($etudiant['nouveauDCE']); ?>', '<?php echo htmlspecialchars($etudiant['ancienDPE']); ?>', '<?php echo htmlspecialchars($etudiant['NouveauDPE']); ?>')">
                            <td><?php echo htmlspecialchars($etudiant['nom']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['prénom']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['date_naissance']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['Adresse']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['etablissement']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['matricule']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['section']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['resultats']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['ancienDCE']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['nouveauDCE']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['ancienDPE']); ?></td>
                            <td><?php echo htmlspecialchars($etudiant['NouveauDPE']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12">Aucune information trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function chargerEtudiant(nom, prenom, date_naissance, adresse, etablissement, matricule, section, resultats, ancienDCE, nouveauDCE, ancienDPE, NouveauDPE) {
            document.querySelector('input[name="nom"]').value = nom || '';
            document.querySelector('input[name="prénom"]').value = prenom || '';
            document.querySelector('input[name="date_naissance"]').value = date_naissance || '';
            document.querySelector('input[name="Adresse"]').value = adresse || '';
            document.querySelector('input[name="etablissement"]').value = etablissement || '';
            document.querySelector('input[name="matricule"]').value = matricule || '';
            document.querySelector('input[name="section"]').value = section || '';
            document.querySelector('input[name="resultats"]').value = resultats || '';
            document.querySelector('input[name="ancienDCE"]').value = ancienDCE || '';
            document.querySelector('input[name="nouveauDCE"]').value = nouveauDCE || '';
            document.querySelector('input[name="ancienDPE"]').value = ancienDPE || '';
            document.querySelector('input[name="NouveauDPE"]').value = NouveauDPE || '';
        }

        function chargerEtudiant() {
            const nomInput = document.querySelector('input[name="nom"]').value;
            if (nomInput) {
                fetch('charger_etudiant.php?nom=' + encodeURIComponent(nomInput))
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            document.querySelector('input[name="prénom"]').value = data.prénom || '';
                            document.querySelector('input[name="date_naissance"]').value = data.date_naissance || '';
                            document.querySelector('input[name="Adresse"]').value = data.Adresse || '';
                            document.querySelector('input[name="etablissement"]').value = data.etablissement || '';
                            document.querySelector('input[name="matricule"]').value = data.matricule || '';
                            document.querySelector('input[name="section"]').value = data.section || '';
                            document.querySelector('input[name="resultats"]').value = data.resultats || '';
                            document.querySelector('input[name="ancienDCE"]').value = data.ancienDCE || '';
                            document.querySelector('input[name="nouveauDCE"]').value = data.nouveauDCE || '';
                            document.querySelector('input[name="ancienDPE"]').value = data.ancienDPE || '';
                            document.querySelector('input[name="NouveauDPE"]').value = data.NouveauDPE || '';
                        } else {
                            // Réinitialiser le formulaire si aucun étudiant n'est trouvé
                            document.querySelector('input[name="prénom"]').value = '';
                            document.querySelector('input[name="date_naissance"]').value = '';
                            document.querySelector('input[name="Adresse"]').value = '';
                            document.querySelector('input[name="etablissement"]').value = '';
                            document.querySelector('input[name="matricule"]').value = '';
                            document.querySelector('input[name="section"]').value = '';
                            document.querySelector('input[name="resultats"]').value = '';
                            document.querySelector('input[name="ancienDCE"]').value = '';
                            document.querySelector('input[name="nouveauDCE"]').value = '';
                            document.querySelector('input[name="ancienDPE"]').value = '';
                            document.querySelector('input[name="NouveauDPE"]').value = '';
                        }
                    });
            } else {
                // Réinitialiser le formulaire si le champ est vide
                document.querySelector('input[name="prénom"]').value = '';
                document.querySelector('input[name="date_naissance"]').value = '';
                document.querySelector('input[name="Adresse"]').value = '';
                document.querySelector('input[name="etablissement"]').value = '';
                document.querySelector('input[name="matricule"]').value = '';
                document.querySelector('input[name="section"]').value = '';
                document.querySelector('input[name="resultats"]').value = '';
                document.querySelector('input[name="ancienDCE"]').value = '';
                document.querySelector('input[name="nouveauDCE"]').value = '';
                document.querySelector('input[name="ancienDPE"]').value = '';
                document.querySelector('input[name="NouveauDPE"]').value = '';
            }
        }
    </script>
</body>
</html>