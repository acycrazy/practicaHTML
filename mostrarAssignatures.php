#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Exemple PHP: mostrar totes les assignatures</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php
  include 'funcions.php';     
  iniciaSessio();
  connecta($conn);
  capcalera("Mostrar totes les assignatures"); 
	$consulta="SELECT a.codi, a.nom, a.credits,
	                  nvl2(p.codi,p.nom||' '||p.cognoms,'** sense responsable **') AS responsable,
	                  a.curs, a.tipus, a.carrera
	             FROM Assignatures a LEFT OUTER JOIN Professors p ON a.responsable=p.codi";
//	$consulta="SELECT * FROM Assignatures WHERE codi='aaa"; //error oci_parse
//  $consulta="SELECT * FROM Assignatur"; // error oci_execute             
	$comanda = oci_parse($conn, $consulta);
	if (!$comanda) { mostraErrorParser($conn,$consulta);} // mostrem error i avortem
	$exit=oci_execute($comanda);
	if (!$exit) { mostraErrorExecucio($comanda);} // mostrem error i avortem
	$numColumnes=oci_num_fields($comanda);
	// mostrem les capceleres
	echo "<table>\n";
	echo "  <tr>";
	for ($i=1;$i<=$numColumnes; $i++) {
		echo "<th>".htmlentities(oci_field_name($comanda, $i), ENT_QUOTES) . "</th>"; 
	}
	echo "</tr>\n";
	// recorrem les files
	while (($row = oci_fetch_array($comanda, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
		echo "  <tr>";
		foreach ($row as $element) {
			echo "<td>".($element !== null ? 
			             htmlentities($element, ENT_QUOTES) : 
			             "&nbsp;") . "</td>";
		}
		echo "</tr>\n";
	}
	echo "</table>\n";
	oci_free_statement($comanda);
	oci_close($conn);
  peu("Tornar al menú principal","menu.php");
?>
</body>
</html>
