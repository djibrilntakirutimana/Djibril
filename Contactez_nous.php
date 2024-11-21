<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez Nous</title>
    
    <style>
        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            background-color: #FFD700; /* Couleur jaune */
            height: 100vh; /* Prend la hauteur complète de la fenêtre */
            font-family: Arial, sans-serif;
            
        }
        h1 {
            color: rgb(130, 10, 241);
            position: absolute;
            top: 20px; /* Positionnement en haut */
            left: 20px; /* Positionnement à gauche */
            opacity: 0.8;
            margin: 0; /* Supprime les marges par défaut */
        }
        .menu {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 60px; /* Espace entre le h1 et le menu */
            margin-bottom: 20px; /* Espace entre le menu et le formulaire */
        }
        .menu a {
            color: rgb(32, 175, 241);
            text-decoration: none;
            font-size: 16px;  /* Légèrement réduit */
            margin: 0 5px; /* Espacement réduit entre les liens */
            padding: 8px; /* Espacement interne réduit */
        }
        .menu a:hover {
            background-color: rgba(32, 175, 241, 0.2); /* Effet de survol */
            border-radius: 4px;
        }
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: stretch; /* Remplit la largeur du conteneur */
        }
        label {
            margin-bottom: 5px;
            font-size: 14px; /* Taille de police pour les labels */
        }
        input, textarea, button {
            width: 100%; /* Remplir la largeur totale du conteneur */
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            font-size: 14px; /* Taille de police pour les champs */
        }
        textarea {
            height: 120px; /* Hauteur augmentée pour le message */
            resize: none;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .info-table {
            margin-top: 20px; /* Espace entre le formulaire et le tableau */
            width: 400px; /* Largeur du tableau */
            border-collapse: collapse; /* Collapse borders */
        }
        .info-table th, .info-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
            word-wrap: break-word; /* Permet de couper les mots longs */
        }
        .info-table th {
            background-color: #f2f2f2; /* Couleur d'arrière-plan des en-têtes */
        }
    </style>
</head>
<body>
    <h3><a href="Accueil.php"><<</a></h3>
    <h1>Contactez Nous</h1>
    <div class="form-container">
        <?php
        //include"header.php";
        include "connexion.php"; 
        
        // Création de la connexion à la base de données
        $conn = new mysqli('localhost', 'root', '', 'goerc');

        // Vérifiez la connexion
        if ($conn->connect_error) {
            die("Échec de la connexion : " . $conn->connect_error);
        }

        $message = '';

        // Vérifiez si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $email = $_POST['email'] ?? '';
            $messageText = $_POST['message'] ?? '';

            // Vérifiez que les champs ne sont pas vides
            if (empty($nom) || empty($email) || empty($messageText)) {
                $message = "Veuillez remplir tous les champs.";
            } else {
                // Préparer la requête SQL pour l'insertion
                $sql = "INSERT INTO contactez_nous (nom, email, message) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param("sss", $nom, $email, $messageText);

                    if ($stmt->execute()) {
                        // Redirection pour éviter la soumission répétée
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    } else {
                        $message = "Erreur lors de l'envoi des informations : " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $message = "Erreur de préparation de la requête : " . $conn->error;
                }
            }
        }

        // Récupérer les données pour afficher dans le tableau
        $result = $conn->query("SELECT nom, email, message FROM contactez_nous");
        ?>

        <form action="" method="POST">
            <label>Nom:</label>
            <input type="text" name="nom" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Message:</label>
            <textarea name="message" required></textarea>
            <button type="submit">Envoyer</button>
        </form>
        <?php if ($message): ?>
            <div><?php echo $message; ?></div>
        <?php endif; ?>
    </div>

    <div>
        <h3>Informations Envoyées</h3>
        <table class="info-table">
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Message</th>
            </tr>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nom']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Aucune information disponible.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <?php
    // Fermer la connexion
    $conn->close();
    ?>
</body>
</html>