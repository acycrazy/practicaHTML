<?php
include 'funcions.php';
iniciaSessio();
connecta($conn);

// Recollim les dades del formulari
$descripcio = $_POST['descripcio'] ?? null;
$color = $_POST['color'] ?? null;
$consum = $_POST['consum'] ?? null;
$data_compra = $_POST['data_compra'] ?? null;
$preu = $_POST['preu'] ?? null;
$grupVehicle = $_POST['grup'] ?? null;
$combustible = $_POST['combustible'] ?? null;
$propietari = $_POST['propietari'] ?? null;

// Comprovem que totes les dades necessàries estan presents
if (!$descripcio || !$color || !$consum || !$data_compra || !$preu || !$grupVehicle || !$combustible || !$propietari) {
    die("Error: Falten dades obligatòries.");
}

// Generem el codi del vehicle
$codiBase = substr($grupVehicle, 0, 2) . substr(str_replace(' ', '', $descripcio), 0, 5);
do {
    $codiUnic = $codiBase . rand(100, 999);
    $sqlCheck = "SELECT COUNT(*) AS TOTAL FROM Vehicles WHERE codi = :codi";
    $comanda = oci_parse($conn, $sqlCheck);
    oci_bind_by_name($comanda, ':codi', $codiUnic);
    oci_execute($comanda);
    $fila = oci_fetch_assoc($comanda);
} while ($fila['TOTAL'] > 0);

// Inserim el nou vehicle a la base de dades
$sqlInsert = "INSERT INTO Vehicles (codi, descripcio, color, consum, data_compra, preu, grupVehicle, combustible, propietari)
              VALUES (:codi, :descripcio, :color, :consum, TO_DATE(:data_compra, 'YYYY-MM-DD'), :preu, :grupVehicle, :combustible, :propietari)";
$comanda = oci_parse($conn, $sqlInsert);
oci_bind_by_name($comanda, ':codi', $codiUnic);
oci_bind_by_name($comanda, ':descripcio', $descripcio);
oci_bind_by_name($comanda, ':color', $color);
oci_bind_by_name($comanda, ':consum', $consum);
oci_bind_by_name($comanda, ':data_compra', $data_compra);
oci_bind_by_name($comanda, ':preu', $preu);
oci_bind_by_name($comanda, ':grupVehicle', $grupVehicle);
oci_bind_by_name($comanda, ':combustible', $combustible);
oci_bind_by_name($comanda, ':propietari', $propietari);

if (oci_execute($comanda)) {
    echo "<p>El vehicle amb codi $codiUnic s'ha donat d'alta correctament.</p>";
} else {
    mostraErrorExecucio($comanda);
    die("Error en donar d'alta el vehicle.");
}

peu("Tornar al menú principal", "menu.php");
?>
