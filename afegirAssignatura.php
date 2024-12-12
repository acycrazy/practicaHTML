#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Exemple PHP: afegir Assignatura, entrada de dades</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php 
    include 'funcions.php';     
    iniciaSessio();
    connecta($conn);
    capcalera("Afegir una assignatura"); 
 ?>
  <form action="afegirAssignatura_BD.php" method="post">
  <p><label>Codi: </label><input type="text" name="codi"> </p>
  <p><label>Nom: </label><input type="text" name="nom"> </p>
  <p><label>Crèdits: </label><input type="number" name="credits"></p>
  <p><label>Professor responsable:</label>
      <select name="responsable">
      <option value="">--sense especificar--</option>
<?php 
    $professors = "SELECT codi, cognoms || ', ' || nom AS NOM 
                     FROM Professors order by NOM";
    $comanda = oci_parse($conn, $professors);
    $exit=oci_execute($comanda);
    if (!$exit){
        mostraErrorExecucio($comanda);
    }
    while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "      <option value=\"" . $fila['CODI'] . "\">" . $fila['NOM'] . "</option>\n";
    }
    echo "      </select></p>";
  ?>      
  <p><label>Semestre: </label><input type = "number" name="semestre"></p>
  <p><label>Curs: </label><input type="number" name="curs"></p>      
  <p><label>Tipus: </label>
      <select name="tipus">
      <option value="">--sense especificar--</option>
      <option value="optativa">Optativa</option>
      <option value="obligatoria">Obligatòria</option>
      </select></p>
  <p><label>Carrera: </label><input type = "text" name="carrera"></p>
  <p><label>&nbsp;</label><input type = "submit" value="Afegir"></p>
  </form>
<?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>
