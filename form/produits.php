<form method="post" name="addprod" id="addprod">
    <table style="width:98%; margin: 10px auto;">
        <tr>
            <td style="width:30%;">
                <div data-role='fieldcontain' class="formLine60">
                    <input type="hidden" name="id_prod" id="id_prod" value="" />
                    <input type="hidden" name="old_type_action" id="old_type_action" value="" />
                    <label for="cat_prod" class="chosen-label">Catégorie: </label>
                    <select name="cat_prod" id="cat_prod" data-mini='true' style="width:300px; height: 30px; margin-left: 50px;" data-role="none" data-enhance="false">
                        <?= $list_categorie ?>
                    </select>
                </div>
            </td>
            <td style="width:5%;">&nbsp;</td>
            <td style="width:30%;">
                <div data-role='fieldcontain' class="formLine60 ui-field-contain">
                    <label for='nom_prod'>Libelle:</label>
                    <input type="text" name="nom_prod" id="nom_prod" data-mini="true" />
                </div>
            </td>
        </tr>
        <tr>
            <td style="width:30%;">
                <div data-role='fieldcontain' class="formLine60 ui-field-contain">
                    <label for='desc_prod'>Description:</label>
                    <textarea name="desc_prod" id="desc_prod"></textarea>
                </div>
            </td>
            <td style="width:5%;">&nbsp;</td>
            <td style="width:30%;">
                <div data-role='fieldcontain' class="formLine60 ui-field-contain">
                    <label for='prix_prod'>Prix Produit:</label>
                    <input type="text" name="prix_prod" id="prix_prod" data-mini="true" />
                </div>
                <div data-role='fieldcontain' class="formLine60 ui-field-contain" style="margin-top: 10px;">
                    <label for='prix_promo_prod'>Prix Promo Produit:</label>
                    <input type="text" name="prix_promo_prod" id="prix_promo_prod" data-mini="true" />
                </div>
            </td>
        </tr>
    </table>
    <div style="width:50%; margin: auto;">
        <div class="messageBox" style="height: auto;"></div>
        <div class="separator" style="margin-top : 30px"></div>
        <div id="ajaxloader" style="margin:8px auto"></div>
        <div id="actual" style="display:none;"><input type="reset" value="Reset" id="actualiseForm"></div>
        <input type="hidden" name="submitproduit" value="submitproduit" />
        <input type="submit" id="uploadSubmit" value="Enregistrer" data-theme='b' data-mini='true' data-icon='check' />
        <div id="targetLayer" style="display:none;"></div>
    </div>
</form>
<style>
    #radiogroup .ui-controlgroup-controls {
        margin: 0px;
        padding: 0px;
        width: auto;
    }

    #radiogroup .ui-controlgroup-label {
        width: auto;
    }
</style>