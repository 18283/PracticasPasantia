<?php

echo '<!DOCTYPE html>
<html>
<head>
    <title>Consulta de Registros de Marcación</title>
</head>
<body>
    <h2>Consultar Registros de Marcación</h2>
    <form action="resultado.php" method="post">
        <label>CI:</label>
        <input type="text" name="ci" required><br><br>

        <label>Fecha de Inicio:</label>
        <input type="date" name="fechaInicio" required><br><br>

        <label>Fecha de Fin:</label>
        <input type="date" name="fechaFin" required><br><br>

        <input type="submit" value="Buscar">
    </form>
</body>
</html>';
?>