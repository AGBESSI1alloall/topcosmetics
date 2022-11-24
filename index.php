<?php
include_once 'loader.php';

if (!isset($_SESSION['loginON'])) {
    header('Location:login.php');
    
}

$result_session = $_SESSION['data'];
$typeutilisateur = $result_session[0]['typeUser'];
$id_util = $result_session[0]['idUser'];
$pseudo = $result_session[0]['emailUser'];
$lastpage = $_SESSION['lastpage'];
$accueilpage = Lien::lastLien($id_util);

$_SESSION['test'] = 1;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
        <title>TopCosmetics</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="library/jquery/jquery-1.9.1.js"></script>
        <link rel="stylesheet" href="library/jqueryMobile/jquery.mobile-1.3.2.min.css" />
        <script src="library/fonction_js/index_header_file.js"></script>
        <script src="library/bootstrap/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="library/dataTables_1.10/1.10.21/datatables.min.css"/>
        <script type="text/javascript" src="library/dataTables_1.10/1.10.21/datatables.min.js"></script>
        <script type="text/javascript" src="library/dataTables_1.10/DateTime_Sort_Datatables_Jquery_Plugin.js"></script>
        <script src="library/js/datetimepicker/jquery.datetimepicker.full.js"></script>
        <link rel="stylesheet" type="text/css" href="library/js/datetimepicker/jquery.datetimepicker.min.css"/>
        <link rel="stylesheet" href="library/bootstrap/css/bootstrap.min.css"/>
        <script src="library/bootstrap/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="library/chosen/v1.6.2/chosen.min.css"/>
        <script type="text/javascript" src="library/js/command.js"></script>

        <!--//Plugin jBox-->
        <script type="text/javascript" src="library/jBox/dist/jBox.js"></script>
        <link rel="stylesheet" type="text/css" href="library/jBox/dist/jBox.all.css"/>
        <script src="library/chosen/v1.6.2/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="library/js/autocomplete/jquery.autocomplete.js" ></script>
        <script type="text/javascript" src="library/jqueryMobile/jquery.mobile-1.3.2-modified.js"></script>
        <!--<script src="library/js/uploadfiles/js/jquery.form.js"></script>-->
        <script src="library/js/uploadfiles/js/jquery.form.js"></script>
        <link rel="stylesheet" type="text/css" href="library/chosen/v1.6.2/chosen.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/general.css"/>
        <script src="library/chosen/v1.6.2/chosen.jquery.min.js"></script>
    </head>
    <body>
        <div data-role="page" id="seul-page" class="jqm-demos ui-responsive-panel">
            <div data-role="panel"  data-position-fixed="true" id="mypanel" data-dismissible="false">
                <ul data-role="listview" data-theme="c" class="nav-search">
                    <li data-icon="delete"><a href="#" data-icon="delete" data-rel="close"></a></li>
                </ul>
                <h3 style="font-weight:bold; text-align: center; margin-top: 25px;"><span style="color:#ff00b8;">TOPCOSMETICS</span></h3>
                <?php
                include_once 'menu.php';
                ?>
            </div><!-- /panel -->
            <div data-role="header" data-theme="e" data-position="fixed" role="banner">
                <a href="#mypanel" data-icon="bars" data-iconpos="notext" title="Utilisation des Menus">Menu</a>
                <div data-type="horizontal" data-mini="true" class="ui-btn-right">
                    <a href="#" data-icon="forward" data-theme='b' data-role="button" data-inline="true" data-mini="true" id="logout">déconnexion</a>
                    <span style='display:inline-block; width:15px'></span>
                </div>
                <h1>Utilisateur: <?= $pseudo."(".$typeutilisateur.")"?></h1>
            </div>
            <div data-role="content"  style="margin:0px; padding:0px;">
                <div id="page-header" class="ui-bar ui-bar-c" style="height:25px;" >

                </div>
                <div id="content_load" style='width:100%'>

                </div>	
            </div>
        </div>
        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Deconnexion?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Voulez vous vraiment se deconneter de la  session?</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                        <a class="btn btn-primary" href="logout.php">Valider</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<style>
    #seul-page .ui-bar-e {
        background: #ff00b8;
        background-image: none;
        color: #000000;
        font-weight: 600;
        background-image: none;
        background-image: none;
        background-image: none;
        background-image: none;
        background-image: none;
        background-image: none;
    }
    #seul-page h1 {color: white;}
    #seul-page h1 .ui-body-c, #seul-page h1 .ui-btn-down-c, #seul-page h1 .ui-btn-hover-c, #seul-page h1 .ui-btn-up-c, #seul-page h1 .ui-overlay-c {
        text-shadow: 0 1px 0 #000;
    }
    #seul-page h1 .ui-bar-e, #seul-page h1 .ui-body-e, #seul-page h1 .ui-overlay-e {
        text-shadow: 0 1px 0 #000;
    }
</style>
<script type="text/javascript">
    var last_page = "<?= $lastpage ?>";
    var pageouvert = "<?= $accueilpage ?>";
    var site_url = "<?= SITE_URL; ?>";
    menuisopen = null;

    var session_espire = "<?= $session_espire ?>";

    //Current page 
    currentPage = {
        script: "<?php echo "Cool En forme"; ?>", //String: Script PHP
        activeTab: null  //String : nom de class de l'onglet actuel
    };

    $(document).ready(function () {

        var page_url = window.location.href;
        var search = page_url.search('#&ui-state=dialog');
        //Rechargeons la page si l'url est erronée
        if (search > 0)
            window.location.href = site_url;
        //---------------------------------------

        $("#mypanel").panel("open");
        $("#mypanel").trigger("updatelayout");
        $("#content_load").load('' + pageouvert + '', function () {
            $(this).trigger('create');
            $("#menu li a[href='" + pageouvert + "']").closest("div[data-role='collapsible']").find('h3 a').click();

            $("#menu li a[href='" + pageouvert + "']").closest("li").addClass("at-state-active");
            return false;
        });
        $("#menu li a").click(function () {

            $("#menu ul li").removeClass("at-state-active");
            $(this).closest("li").addClass("at-state-active");
            //$("#menu li a[href='" + pageouvert + "']").addClass("at-state-active");

            var href = $(this).attr('href');

            $("#content_load").load(href, function () {
                $(this).trigger('create');
            });
            last_page = href;
            return false;
        });

        $("#logout").on('click', function () {
            console.log('Deconnexion');
            window.location = "logout.php";
        });
        //Ouvrir le menu si dialog est fermé
        if ($('#standard').dialog('isOpen') === false) {
            $("#mypanel").panel("open").trigger('create');

        }
        
        $("#standard").on("pageshow", function () {
            if (session_espire == 1) {
                window.location = 'logout.php';
            }
        })
    });

    $(document).on('pagebeforehide', '#seul-page', function () {
        //Modif
        setTimeout(function () {
            menuisopen = $("#mypanel").hasClass("ui-panel-open");
        }, 50);
        return false;
    });
    $(document).on('pageshow', '#seul-page', function () {
        setTimeout(function () {
            if (menuisopen)
                $("#mypanel").panel("open")
        }, 50)
        return false;
    });

</script>
<style>
    #mypanel .ui-btn-inner {
        overflow: visible;
    }
    a :link
    {
        text-decoration: none;
    }
</style>