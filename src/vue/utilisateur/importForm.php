<form action="controleurFrontal.php?action=importerExcel&controleur=utilisateur" method="post" enctype="multipart/form-data">
    <label for="excelFile">Choose Excel file to import:</label>
    <input type="file" name="excelFile" id="excelFile" accept=".xlsx, .xls" required>
    <button type="submit">Upload and Import</button>
</form>
