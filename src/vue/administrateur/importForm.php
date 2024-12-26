<form action="controleurFrontal.php?action=importerExcel&controleur=administrateur" method="post"
      enctype="multipart/form-data">
    <label for="excelFile">Choisissez le fichier Excel à importer (format du nom : semestre-numéro-année ou semestre_numéro_année) :</label>
    <input type="file" name="excelFile" id="excelFile" accept=".xlsx, .xls" required>
    <button type="submit">Importer</button>
</form>
