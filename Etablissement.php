<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>etablissement</title>
    <link rel="stylesheet"href="styles.css">
</head>
<body>
    <?php
    include"connexion.php";
    include"header.php";
    $affichageRecl = $bdd->query("Select * from etablissement");
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


<tr> <th>id_etabli</th>
<td> <input type="text" name="id_etabli"/> </td>
</tr>
<tr> <th>nom</th>
<td> <input type="text" name="nom"/> </td>
</tr>
<tr> <th>adresse</th>
<td> <input type="text" name="adresse"/> </td>
</tr>
<tr> <th>id_section</th>
<td> <input type="text" name="id_section"/> </td>
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
$recupetabli=$_POST["id_etabli"];
$recupnom=$_POST["nom"];
$recupadre=$_POST["adresse"];
$recupsect=$_POST["id_section"];
$Insertionetablissement ="INSERT INTO etablissement (id_etabli,nom,adresse,id_section) VALUES('$recupetabli','$recupnom','$recupadre','$recupsect')";
$bdd->exec($Insertionetablissement );


 

}
?>
<h1>Affichage</h1>
        <table  border="1" cellpadding="1" cellspacing="1" align="center" bgcolor = "white">
            <tr>
                <th>id_etabli</th>
                <th>nom</th>
                <th>adresse</th>
                <th>id_section</th>
                

                
            </tr>
            <?php 
                while ( $etablissementRecup = $affichageRecl->fetch()) {         
            ?>
                <tr>
                    <td ><?php echo $etablissementRecup ["id_etabli"]; ?></td>
                    <td><?php echo $etablissementRecup ["nom"]; ?></td>
                     <td><?php echo $etablissementRecup ["adresse"]; ?></td>
                    <td><?php echo $etablissementRecup ["id_section"]; ?></td>
                    
                </tr>
            <?php } ?>
        </table>


  <h2>etablissement</h2>
    <form action="etablissement.php"method="POST">
        <label>Nom:</label><br>
        <input type="text"name="nom"required><br>
        <label>Adresse</label><br>
        <input type="text"name="Adresse"required><br><br>
        <button type="submit">Ajouter</button>
        <button type="submit">Modifier</button>
        <button type="submit">Visualiser</button>
        


    </form> 
</body>
</html>