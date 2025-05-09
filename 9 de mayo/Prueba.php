<!--Crear la instancia de la clase.

Procesar formularios.

Renderizar la barra.

Mostrar formularios.-->

<?php
require_once 'BarraNavegacion.php';

$barra = new BarraNavegacion();

// Procesar fondo
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cambiar_color"])) {
    $nuevoColor = $_POST["color_fondo"] ?? '#d1c4e9';
    $barra->cambiarFondo($nuevoColor);
}

// Procesar inserción
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["crear"])) {
    $textos = $_POST["texto"];
    $urls = $_POST["url"];

    $nuevosElementos = [];

    for ($i = 0; $i < count($textos); $i++) {
        $texto = trim($textos[$i]);
        $url = trim($urls[$i]);
        if ($texto !== "" && $url !== "") {
            $nuevosElementos[$texto] = $url;
        }
    }

    $barra->insertar($nuevosElementos);
} else {
    $barra->render();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["eliminar"])) {
    $textoEliminar = $_POST["eliminar_texto"] ?? '';
    $barra->eliminarElemento($textoEliminar);
}
?>

<!-- Formulario para ingresar nuevos elementos -->
<form method="post">
    <h3>Agregar elementos a la barra</h3>
    <div id="contenedor-elementos">
        <div>
            Texto: <input type="text" name="texto[]" required>
            URL: <input type="text" name="url[]" required>
        </div>
    </div>
    <button type="button" onclick="agregarElemento()">Agregar otro elemento</button>
    <br><br>
    <input type="submit" name="crear" value="Crear Barra">
</form>

<script>
function agregarElemento() {
    const contenedor = document.getElementById("contenedor-elementos");
    const nuevo = document.createElement("div");
    nuevo.innerHTML = 'Texto: <input type="text" name="texto[]" required> URL: <input type="text" name="url[]" required>';
    contenedor.appendChild(nuevo);
}
</script>

<?php
    echo '<pre>';
    print_r($barra->devolverMenu());
    echo '</pre>';

    

?>

// Formulario para eliminar un elemento 
    <form method="post">
        <h3>Eliminar un elemento de la barra</h3>
        <select name="eliminar_texto">
            <?php foreach ($barra->devolverMenu() as $texto => $url): ?>
                <option value="<?= htmlspecialchars($texto) ?>"><?= htmlspecialchars($texto) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="eliminar" value="Eliminar">
    </form>

    <!-- Formulario para cambiar el color de fondo -->
<form method="post">
    <h3>Cambiar color de fondo de la barra</h3>
    <input type="color" name="color_fondo" value="#d1c4e9">
    <input type="submit" name="cambiar_color" value="Cambiar fondo">
</form>