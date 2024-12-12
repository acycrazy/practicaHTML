#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Exemple PHP: afegir Assignatura, inserció a la base de dades</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php 
  include 'funcions.php';     
  iniciaSessio();
  connecta($conn);
  capcalera("Inserir assignatura a la base de dades"); 
  $consultaCodi="SELECT codi, nom FROM Assignatures WHERE codi=:codiAssignatura";
  $comanda = oci_parse($conn, $consultaCodi);
  oci_bind_by_name($comanda,":codiAssignatura",$_POST["codi"]);
  $exit = oci_execute($comanda);
  $fila=oci_fetch_array($comanda); // no fem control d'errors 
  if (!$fila){ // no existeix cap assignatura amb el codi rebut 
    oci_free_statement($comanda);
    $sentenciaSQL = "INSERT INTO Assignatures (codi, nom, credits, responsable, semestre, curs, tipus, carrera) 
                     VALUES (:codi, :nom, :credits, :responsable, :semestre, :curs, :tipus, :carrera)";
    $comanda = oci_parse($conn, $sentenciaSQL);
    oci_bind_by_name($comanda, ":codi", $_POST["codi"]);
    oci_bind_by_name($comanda, ":nom", $_POST["nom"]);
    oci_bind_by_name($comanda, ":credits", $_POST["credits"]);
    oci_bind_by_name($comanda, ":responsable", $_POST["responsable"]);
    oci_bind_by_name($comanda, ":semestre", $_POST["semestre"]);
    oci_bind_by_name($comanda, ":curs", $_POST["curs"]);
    oci_bind_by_name($comanda, ":tipus", $_POST["tipus"]);
    oci_bind_by_name($comanda, ":carrera", $_POST["carrera"]);
    $exit = oci_execute($comanda); 
    if ($exit) {
        echo "<p>Nova assignatura amb codi " . $_POST['codi'] . " inserida a la base de dades</p>\n";
    } else {
        mostraErrorExecucio($comanda);
    }
  } else {
      echo "<strong>COMPTE! Assigatura no creada:</strong> ja existia una assignatura amb codi ".
           $_POST['codi']." amb nom [".$fila['NOM']."]\n";
  }
  oci_free_statement($comanda);
  oci_close($conn);
  peu("Tornar al menú principal","menu.php");;
?>
</body>
</html>
