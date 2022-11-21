<?php
include_once 'loader.php';

$insertpage = $_SESSION["lastpage"];
//recuiel du id_utilisateur

$requet = "SELECT id_connexion FROM connexion WHERE id_user=? ORDER BY id_connexion DESC LIMIT 1";
$result = $DB->query($requet,[$_SESSION['id_user']]);
$id_conn = $result[0]['id_connexion'];

if(!empty($id_conn)){
    $sql = "UPDATE connexion SET date_deconnexion = NOW() WHERE id_connexion = ?";
    $DB->query($sql, array($id_conn));
}
    
unset($_SESSION['loggedIN']);

session_destroy();

header('Location:login.php');
exit();
?>
<script type="text/javascript">
    $(document).ready(function() {
        location.reload();
    }
</script>