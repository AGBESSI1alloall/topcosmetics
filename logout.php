<?php
include_once 'loader.php';

$insertpage = $_SESSION["lastpage"];
//recuiel du id_utilisateur

$result = Connexion::lineConnexion($_SESSION['id_user']);
$id_conn = $result[0]['id_connexion'];

if(!empty($id_conn)){
    Connexion::updateConnexion($id_conn);
}
    
unset($_SESSION['loggedIN']);

session_destroy();

header('Location:login.php');
exit();
?>
<script type="text/javascript">
    $(document).ready(function() {
        location.reload();
    });
</script>