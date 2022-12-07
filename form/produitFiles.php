<form method="post" name="addprodfile" id="addprodfile" enctype="multipart/form-data">
    <input type="hidden" name="id_produit_file" id="id_produit_file" value="" data-mini="true" />
    <div data-role='fieldcontain' class="formLine60 readonly">
        <label for="libelle_voiture" data-mini='true' class="logo">Voiture : </label>
        <input type="text" name="libelle_voiture" id="libelle_voiture" value="" />
    </div>
    <div data-role='fieldcontain' class="formLine60 readonly">
        <label for="uploadVoitFile" data-mini='true' class="logo">Photo Voiture : </label>
        <input type="file" name="uploadVoitFile[]" id="uploadVoitFile" accept=".jpg, .png, .jpeg" multiple />
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
        <input type="hidden" name="submitvoitfile" value="submitvoitfile" />
        <input type="submit" id="uploadSubmit" value="Enregistrer" data-theme='b' data-mini='true' data-icon='check' />
        <div id="targetLayer" style="display:none;"></div>
    </div>
</form>