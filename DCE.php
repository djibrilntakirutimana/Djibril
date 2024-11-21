<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dce</title>
    <link rel="stylesheet"href="styles.css">
</head>
<body>
    <?php
    include"connexion.php";
    include"header.php";
    $affichageRecl = $bdd->query("Select * from dce");
    ?>
    <?php
    
    $pdo = new PDO('mysql:host=localhost;dbname=orientation_bd;charset=utf8', 'root', '');
        
    ?>
    
    <h1>inscrivez-vous</h1>
    <meta charset="utf_8"/>
    </head>
    <body>
    
    <form method="POST" action="">
    <table>
    
    
    <tr> <th>id_dce</th>
    <td> <input type="text" name="id_dce"/> </td>
    </tr>
    <tr> <th>id_etabli</th>
    <td> <input type="text" name="id_etabli"/> </td>
    </tr>
    <tr> <th>id_etudiant</th>
    <td> <input type="text" name="id_etudiant"/> </td>
    </tr>
    <tr> <th> </th>
    <td> <input type="submit" name="envoyer" value ="s'inscrire"/>
       <input type="reset" value="supprimer"/>
       </td>
     </tr>
     </table>
     </from>
    
    </body>
    </html>
    <?php
    if(isset($_POST["envoyer"]))
    {
    //$recupId=$_POST["numero_patient"];
    $recupdce=$_POST["id_dce"];
    $recupetabli=$_POST["id_etabli"];
    $recupetud=$_POST["id_etudiant"];
    $Insertiondce ="INSERT INTO dce (id_dce,id_etabli,id_etudiant) VALUES('$recupdce','$recupetabli','$recupetud')";
    $bdd->exec($Insertiondce );
    
    
     
    
    }
    ?>
    <h1>Affichage</h1>
            <table  border="1" cellpadding="1" cellspacing="1" align="center" bgcolor = "white">
                <tr>
                    <th>id_dce</th>
                    <th>id_etabli</th>
                    <th>id_etudiant</th>
                    
    
                    
                </tr>
                <?php 
                    while ( $dceRecup = $affichageRecl->fetch()) {         
                ?>
                    <tr>
                        <td ><?php echo $dceRecup ["id_dce"]; ?></td>
                        <td><?php echo $dceRecup ["id_etabli"]; ?></td>
                         <td><?php echo $id_etudiant ["id_etudiant"]; ?></td>
                        
                    </tr>
                <?php } ?>
            </table>
  <h2>DCE</h2>
    <form action="dce.php"method="POST">
        <label>Residence:</label><br>
        <input type="text"name="Residence"required><br>
        
        <button type="submit">Ajouter</button>
        <button type="submit">Modifier</button>
        <button type="submit">visualiser</button>
        


    </form> 
</body>
</html>