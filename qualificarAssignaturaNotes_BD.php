#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Exemple PHP: enregistrar les qualificacions</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php 
  include 'funcions.php';
  iniciaSessio();
  connecta($conn);
  $codiAssignatura=$_POST['assignatura'];
  $consulta="SELECT nom FROM Assignatures WHERE codi=:codiAssignatura";
  $comanda = oci_parse($conn, $consulta);
  oci_bind_by_name($comanda,":codiAssignatura",$codiAssignatura);
  $exit = oci_execute($comanda);
  $fila= oci_fetch_array($comanda);
  $assig=$fila['NOM'];
  unset ($_POST['assignatura']); // per poder recorrer $_POST amb un foreach per les qualificacions
  capcalera("Qualificacions de ".$assig." enregistrades"); 
  oci_free_statement($comanda);
  $actualitzacio="UPDATE Matricula SET nota=:nota WHERE alumne=:codiAlumne AND assignatura=:codiAssignatura";
  $comanda = oci_parse($conn, $actualitzacio);
  oci_bind_by_name($comanda,":codiAssignatura",$codiAssignatura);
  foreach($_POST AS $clau => $valor) {
    oci_bind_by_name($comanda,":nota",$valor);
    oci_bind_by_name($comanda,":codiAlumne",$clau);
    oci_execute($comanda); // no fem control d'errors
  }
  echo "<p>Qualificacions de l'assignatura <b>". $assig . "</b> actualitzades</p>\n";
  oci_free_statement($comanda);
  oci_close($conn);
  peu("Qualificar una altra assignatura","qualificarAssignatura.php");;
  peu("Tornar al menú principal","menu.php");;
?>
</body>
</html>
