#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Donar d’alta un vehicle</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php
  include 'funcions.php';
  iniciaSessio();
  connecta($conn);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recollir les dades del formulari
    $descripcio = trim($_POST['descripcio']);
    $color = trim($_POST['color']);
    $consum = (float)$_POST['consum'];
    $dataCompra = $_POST['dataCompra'];
    $preu = (float)$_POST['preu'];
    $grup = trim($_POST['grup']);
    $combustible = trim($_POST['combustible']);
    $propietari = trim($_POST['propietari']);

    // Generar el codi del vehicle
    $codiBase = substr($grup, 0, 2) . str_replace(' ', '', substr($descripcio, 0, 5));
    $codiVehicle = $codiBase;

    // Assegurar unicitat del codi
    while (!codiEsUnic($conn, $codiVehicle)) {
        $codiVehicle = $codiBase . rand(100, 999);
    }

    // Inserir el vehicle a la base de dades
    $sql = "INSERT INTO Vehicles (codi, descripcio, color, consum, data_compra, preu, grup, combustible, propietari) 
            VALUES (:codi, :descripcio, :color, :consum, TO_DATE(:dataCompra, 'YYYY-MM-DD'), :preu, :grup, :combustible, :propietari)";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':codi', $codiVehicle);
    oci_bind_by_name($stmt, ':descripcio', $descripcio);
    oci_bind_by_name($stmt, ':color', $color);
    oci_bind_by_name($stmt, ':consum', $consum);
    oci_bind_by_name($stmt, ':dataCompra', $dataCompra);
    oci_bind_by_name($stmt, ':preu', $preu);
    oci_bind_by_name($stmt, ':grup', $grup);
    oci_bind_by_name($stmt, ':combustible', $combustible);
    oci_bind_by_name($stmt, ':propietari', $propietari);

    if (oci_execute($stmt)) {
        echo "<p>Vehicle amb codi $codiVehicle donat d'alta correctament!</p>";
    } else {
        $e = oci_error($stmt);
        echo "<p>Error: " . htmlentities($e['message']) . "</p>";
    }

    oci_free_statement($stmt);
    oci_close($conn);
  }
?>

<h1>Donar d’alta un vehicle</h1>
<form method="post" action="donarAltavehicle.php">
  <p>Descripció: <input type="text" name="descripcio" required></p>
  <p>Color: <input type="text" name="color" required></p>
  <p>Consum (litres/100km): <input type="number" step="0.01" name="consum" required></p>
  <p>Data de compra: <input type="date" name="dataCompra" required></p>
  <p>Preu: <input type="number" step="0.01" name="preu" required></p>
  <p>Grup de vehicles: <input type="text" name="grup" required></p>
  <p>Combustible: <input type="text" name="combustible" required></p>
  <p>Propietari: <input type="text" name="propietari" required></p>
  <p><input type="submit" value="Donar d'alta"></p>
</form>
<?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>
