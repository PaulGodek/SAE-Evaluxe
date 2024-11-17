<form action="controleurFrontal.php?action=importerExcel&controleur=utilisateur" method="post" enctype="multipart/form-data">
    <label for="excelFile">Choisissez le fichier Excel à importer :</label>
    <input type="file" name="excelFile" id="excelFile" accept=".xlsx, .xls" required>
    <button type="submit">Téléchargement et importation</button>
</form>
