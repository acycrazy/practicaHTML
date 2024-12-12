<?php
// inicia sessions per poder compartir dades
function iniciaSessio(){
  $dirSessions = exec("pwd") . "/tmp";
  ini_set('session.save_path', $dirSessions);
  session_start();
}

//obra una connexió amb ORACLE i la retorna a $connexio
function connecta(&$connexio){
  $connexio = oci_connect($_SESSION['usuari'], 
                          $_SESSION['password'], 'ORCLCDB');
  if (!$connexio) {
    header('Location: errorLogin.php');
  }
}

// escriu el que es mostra a dalt de la pàgina web
function capcalera($text){
  echo '<div id="textbox">'."\n";
  echo '<p class="capcaleraTitol">'.$text."</p>\n";
  echo '<p class="capcaleraUsuari">usuari actiu: <b>'. 
        $_SESSION['usuari'] . "</b></p>\n"; 
  echo "</div>\n";
  echo '<div style="clear: both;"></div>'."\n";
  echo "<hr>\n";
}
  
// escriu el peu de la pàgina web
function peu($text,$pagina){
  echo '<hr><p class="peu"><a class="menu" href="'.
       $pagina.'">'.$text."</a></p>\n";
}

// mostra un error provocat per oci_execute
function mostraErrorExecucio($comanda){
  $error = oci_error($comanda);
  echo "<hr>\n<h3>mostraErrorExecucio(\$comanda): Error amb l'execució de \$comanda</h3>";
  echo "<p>Oracle informa del següent error:<p>\n";
  echo "<p>Codi error: <tt>" . $error['code'] . "</tt></p>\n";
  echo "<p>Missatge error: <tt>" . $error['message'] . "</tt></p>\n";
  echo "<p>Sentència que ha provocat aquest error: </p>\n";
  echo "<p><tt>" . $error['sqltext'] . "</tt></p>\n";
  echo "<p>Posició error: <tt>" . $error['offset'] . "</tt></p>\n<hr>\n";
}

function mostraErrorParser($conn,$SQL){
  $error = oci_error($conn);
  echo "<hr>\n<h3>mostraErrorParser(\$conn,\$SQL): Error amb el parser de \$SQL via \$conn</h3>";
  echo "<p>Oracle informa del següent error:<p>\n";
  echo "<p>Codi error: <tt>" . $error['code'] . "</tt></p>\n";
  echo "<p>Missatge error: <tt>" . $error['message'] . "</tt></p>\n";
  echo "<p>Sentència que ha provocat aquest error: </p>\n";
  echo "<p><tt>" . $SQL . "</tt></p>\n<hr>\n";
}

?>
