<form method="post" name="addprodfile" id="addprodfile" enctype="multipart/form-data">
    <input type="hidden" name="id_produit_file" id="id_produit_file" value="" data-mini="true" />
    <input type="hidden" name="nb_prod" id="nb_prod" value="" />
    <div data-role='fieldcontain' class="formLine60 readonly">
        <label for="nom_produit" data-mini='true' class="logo">Produit : </label>
        <input type="text" name="nom_produit" id="nom_produit" value="" />
    </div>
    <div data-role='fieldcontain' class="formLine60 readonly">
        <label for="uploadProdFile" data-mini='true' class="logo">Photo Produit : </label>
        <input type="file" name="uploadProdFile[]" id="uploadProdFile" accept=".jpg, .png, .jpeg" multiple />
    </div>
    <div id="progress-wrp" class="cache">
        <div class="progress-bar"></div>
        <div class="status">0%</div>
    </div>
    <div style="width:50%; margin: auto;">
        <div class="messageBox" style="height: auto;"></div>
        <div class="separator" style="margin-top : 30px"></div>
        <div id="ajaxloader" style="margin:8px auto"></div>
        <div id="actual" style="display:none;"><input type="reset" value="Reset" id="actualiseForm"></div>
        <input type="hidden" name="submitprodfile" value="submitprodfile" />
        <input type="submit" id="uploadSubmit" value="Enregistrer" data-theme='b' data-mini='true' data-icon='check' />
        <div id="targetLayer" style="display:none;"></div>
    </div>
</form>