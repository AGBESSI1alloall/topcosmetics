<?php
if ($typeutilisateur == 'developper' || $typeutilisateur == 'superadmin') {
    
    $result = Menu::listMenu();
    
} else {
    if (isset($id_util)) {
        $result = Menu::listMenu($id_util);
    }
}

$listmenu = count($result) ? array_unique(array_column($result, "menu")) : [];
?>
<div  data-role="collapsible-set" data-theme="b" data-content-theme="d" data-inset="true" style="" data-corners="false" id="menu">
    <?php
    foreach ($listmenu as $list) {
        ?> 
        <div data-role="collapsible" data-inset="false">
            <h3><?= $list ?></h3>
            <ul data-role="listview">
                <?php
                foreach ($result as $line) {
                    if ($line['menu'] == $list) {
                        ?>    
                        <li data-mini="true"><a href="<?= $line['fichierSousMenu'] ?>"><?= $line['sousMenu'] ?></a></li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
        <?php
    }
    ?>
</div>

<style type="text/css">
    a:link
    {
        text-decoration: none;
    }
    a:hover, a.ui-focus
    {
        color: white;
    }

</style>
<script>
    var previewInterval;
    $(document).ready(function () {
        $("#menu-panel ul li").find('div.ui-li span.ui-icon').remove()
        $("#menu-panel ul li a.ui-link-inherit").css("padding", ".3em 0 .3em 15px")
        //Cacher le bouton servant à; mettre le menu à coté
        $("a#menuOverlayLink").hide()

        /*** ON CHARGE LA PAGE DANS #content AU CLICK D'UN LIEN DU MENU****************/
        $("#menu-panel ul li a").each(function () {
            $(this).click(function (event) {
                if (!$(this).attr('target')) {//S'il ne s'agit pas d'un lien externe
                    //Conservation du state active des liens
                    $("#menu-panel ul li").removeClass("at-state-active")
                    $(this).closest("li").addClass("at-state-active")

                    //Affichage du filtre d'entete correspondant
                    if ($(this).attr('at-header-box')) {
                        var filtre = $(this).attr('at-header-box')
                        toggleHeaderFilter(filtre)
                    } else
                    if ($(this).closest('div[data-role="collapsible"]').attr('at-header-box')) {
                        var filtre = $(this).closest('div[data-role="collapsible"]').attr('at-header-box')
                        toggleHeaderFilter(filtre)

                    } else {
                        toggleHeaderFilter('none')
                    }

                    //On ferme tous les qtip actifs
                    $("body > div.qtip").remove()

                    AjaxContentLoader($(this));
                    //event.preventDefault()
                } else {
                    //$(this).parent('li').removeClass("ui-btn-active")
                    var fleet_id = parseInt($(this).attr('fleet_id'));

                    if (isNaN(fleet_id))
                        fleet_id = $("#idfleet option:selected").val();

                    $("form#goFleetMapForm").attr('action', $(this).attr('href'))
                    $("form#goFleetMapForm input#fleet_id").val(fleet_id)
                    $("form#goFleetMapForm").submit();
                }

                event.preventDefault();
            });
        });


        $("#menu-panel a#menuOverlayLink").unbind('click').click(function () {
            //alert("Secondly")
            current = $("#menu-panel").panel("option", "display");
            //alert(current)
            if (current == 'reveal') {
                $("#menuCloseLink").click()

                $("#menu-panel").panel("option", "display", "overlay")//.page();

                $("a#menuOverlayLink").hide()
                $("a#menuRevealLink").show()
                $("a[href='#menu-panel']").click()
            }
        })

        $("#menu-panel a#menuRevealLink").unbind('click').click(function () {
            current = $("#menu-panel").panel("option", "display");
            if (current == 'overlay') {
                $("#menuCloseLink").click()

                $("#menu-panel").panel("option", "display", "reveal")

                $("a#menuOverlayLink").show()
                $("a#menuRevealLink").hide()
                $("a[href='#menu-panel']").click()
            }
        })
        //
        /*ANIMATION ET CHANGEMENT D'ICON arrow POUR LE MENU ACCORDEON*************************************************/
        $("#menu-panel div:jqmData(role='collapsible') h3").unbind('click').click(function (event) {
            var currentH3 = $(this)
            //On plier tous les autres Groupes de MENU								
            $(this).parent("div:jqmData(role='collapsible')").prevAll("div:jqmData(role='collapsible')").children("div.ui-collapsible-content").slideUp(300)
            $(this).parent("div:jqmData(role='collapsible')").nextAll("div:jqmData(role='collapsible')").children("div.ui-collapsible-content").slideUp(300)
            //ON Plie ou Dplie le Groupe de MENU cliqu
            $(this).next('div.ui-collapsible-content').slideToggle(400, function () {
                //On modifier les icones
                $("#menu-panel div:jqmData(role='collapsible') div.ui-collapsible-content").each(function (i, element) {
                    if ($(this).css("display") == 'block') {
                        span = $(this).prev("h3").find("span.ui-icon")
                        span.removeClass("ui-icon-arrow-r").addClass("ui-icon-arrow-d")
                    } else if ($(this).css("display") == 'none') {
                        span = $(this).prev("h3").find("span.ui-icon")
                        span.removeClass("ui-icon-arrow-d").addClass("ui-icon-arrow-r")
                    }
                })
                //Afficher Cacher les filtres dans la barre de Titre des pages----------------
                if (currentH3.next('div.ui-collapsible-content').css("display") == "none") {
                    //pas de filtre à afficher
                    toggleHeaderFilter("none")
                } else {
                    //Affichage du filtre correspondant au menu
                    toggleHeaderFilter(currentH3.parent("div").attr("at-header-box"))
                    //Affichage du filtre correspondant au sous-menu s'il y a lieu
                    if (activeMenuLink.attr("at-header-box") &&
                            activeMenuLink.parents('div.ui-collapsible-content').css("display") != "none") {
                        toggleHeaderFilter(activeMenuLink.attr("at-header-box"))
                    } /*else if ( !activeMenuLink.attr("at-header-box") ) {
                     toggleHeaderFilter('none');
                     }*/
                }
            });

            /*REGLAGE DU PROBLEME DE CONSERVATION DES TOOLTIPS DU PLUGINS qtip**********************/
            $("body > div.qtip").hide() //Suppression de tous les div tooltip

            return false;
        })

        /*A LA FERMETURE DU PANEL paramètres, ON OUVRE LE PANEL MENU SEULEMENT SI
         IL ETAIT AU PREALABLE OUVERT L'OUVERTURE DE CETTE PREMIERE ****************************/
        $("#param-panel").on("panelbeforeopen", function (event, ui) {
            menuisopen = $("#menu-panel").hasClass("ui-panel-open");
        })
        $("#param-panel").on("panelclose", function (event, ui) {
            if (menuisopen)
                setTimeout(function () {
                    $("#menu-panel").panel("open")
                }, 500)
        });
        //
        /*OUVERTURE DU MENU AU CHARGEMENT DE LA PAGE**********************######################*****/
        $("a#menuOverlayLink").show()
        $("a#menuRevealLink").hide();

        setTimeout(function () {
            $("a[href='#menu-panel']").click();
            // main.php est par defaut charger dans l'index

            //------ ANIMATION ----------
            setTimeout(function () {
                if (typeof activeMenuLink == 'object') {
                    activeMenuLink.closest("li").removeClass("at-state-active ui-btn-active");
                    activeMenuLink.closest("div.ui-collapsible").find("h3").click();
                    setTimeout(function () {
                        activeMenuLink.closest("li").addClass("at-state-active");
                        //
                        //Added recently 2018-09-27
                        $("#filter-div select#idfleet").selectmenu("refresh").change()
                        //Chargement de la dernière page ouverte à la dernière session
                        if (currentPage.script != "main.php" && wholePageIsLoaded) {
                            setTimeout(function () {
                                activeMenuLink.click();
                                wholePageIsLoaded = false;
                            }, 300)
                        }
                    }, 500)
                }
            }, 500)
        }, 500)

        $("#menu-panel").panel("option", "display", "reveal")

        //
        $(document).on('pagebeforehide', '#seul-page', function () {
            //setTimeout(function () {
            menuisopen = $("#menu-panel").hasClass("ui-panel-open");
            // }, 50);
        });
        $(document).on('pageshow', '#seul-page', function () {
            setTimeout(function () {
                if (menuisopen)
                    $("#menu-panel").panel("open")
                alarmSetActiveTab = null;
            }, 50)
            /** Enlever le popup calendrier de datepicker s'il ne l'est pas encore**/
            $(".datepick-popup").remove();
        });

    })
</script>