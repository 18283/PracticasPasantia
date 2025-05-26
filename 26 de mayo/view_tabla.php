<?php
class view_tabla {
    private $stmt;
    private $estiloTabla;
    private $colorFondo;
    private $colorEncabezado;
    private $colorHover;
    private $copia_stmt;

    public function __construct($resultado) {
        $this->stmt = $resultado;
        $this->estiloTabla = "";
        $this->colorFondo = "#ffffff";
        $this->colorEncabezado = "#cb8db5";
        $this->colorHover = "#f3d8eb";
        $this->copia_stmt = [];

        // Cargar todos los resultados en memoria
        while ($fila = sqlsrv_fetch_array($this->stmt, SQLSRV_FETCH_ASSOC)) {
            $this->copia_stmt[] = $fila;
        }
    }


    public function establecerEstiloColorido() {
        $this->estiloTabla = "style='
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
        '";
    }

    public function establecerColorFondo($colorHex) {
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $colorHex)) {
            $this->colorFondo = $colorHex;
        } else {
            echo "<p>Color de fondo inválido.</p>";
        }
    }

    public function establecerColorEncabezado($colorHex) {
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $colorHex)) {
            $this->colorEncabezado = $colorHex;
        } else {
            echo "<p>Color de encabezado inválido.</p>";
        }
    }

    public function establecerColorHover($colorHex) {
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $colorHex)) {
            $this->colorHover = $colorHex;
        } else {
            echo "<p>Color hover inválido.</p>";
        }
    }

    public function mostrarTabla($stmt_variable) {
        if (!$stmt_variable) {
            echo "<p>No hay resultados que mostrar.</p>";
            return;
        }

        // Estilos completos
        echo "<style>
            .tabla-contenedor {
                width: 60%;
                margin: 0 auto 30px auto;
                max-height: 700px;
                overflow-y: auto;
                border: 1px solid #999;
                border-radius: 6px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                background-color: {$this->colorFondo};
            }
            table.view-tabla th, table.view-tabla td {
                border: 1px solid #999;
                padding: 12px;
                text-align: center;
            }
            table.view-tabla th {
                background-color: {$this->colorEncabezado};
                color: white;
                position: sticky;
                top: 0;
                z-index: 10;
            }
            table.view-tabla td {
                transition: background-color 0.3s ease;
            }
            table.view-tabla tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            table.view-tabla tr:nth-child(odd) {
                background-color: #ffffff;
            }
            table.view-tabla tr:hover td {
                background-color: {$this->colorHover};
            }
            table.view-tabla {
                width: 100%;
                border-collapse: collapse;
                margin: 0;
            }
        </style>";

        echo "<div style='text-align: center;'><h3>Resultados encontrados:</h3></div>";
        echo "<div class='tabla-contenedor'>";
        echo "<table class='view-tabla' {$this->estiloTabla}>";

        // Columnas
        $columnas = [];
        $metadata = sqlsrv_field_metadata($stmt_variable);
        if ($metadata === false) {
            echo "<p>Error al obtener la metadata de los campos.</p>";
            return;
        }
        foreach ($metadata as $field) {
            $columnas[] = $field['Name'];
        }

        // Encabezado
        echo "<thead><tr>";
        foreach ($columnas as $col) {
            echo "<th>" . htmlspecialchars($col) . "</th>";
        }
        echo "</tr></thead>";

        // Cuerpo
        echo "<tbody>";
        $hayResultados = false;
        while ($fila = sqlsrv_fetch_array($stmt_variable, SQLSRV_FETCH_ASSOC)) {
            if ($fila === false) {
                echo "<p>Error al obtener los datos.</p>";
                return;
            }
            $hayResultados = true;
            echo "<tr>";
            foreach ($columnas as $col) {
                echo "<td>" . htmlspecialchars($fila[$col]) . "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";

        if (!$hayResultados) {
            echo "<p style='text-align: center;'>No se encontraron resultados.</p>";
        }
    }
    
    public function buscador($campos) {
        echo $campo;
        if (!is_array($campos) || empty($campos)) {
            echo "<p>Parámetros inválidos para el buscador.</p>";
            return;
        }

        // Obtener valores actuales del GET (si existen), palabra a filtrar y la columna seleccionada
        $palabra_a_filtrar = isset($_GET['palabra_a_filtrar']) ? htmlspecialchars($_GET['palabra_a_filtrar']) : '';
        $columnaSeleccionada = isset($_GET['columna']) ? $_GET['columna'] : '';

        // Mostrar formulario
        echo "<form method='GET' style='text-align: center; margin-bottom: 20px;'>
            <label for='columna'>Buscar por:</label>
            <select name='columna' id='columna'>";

        foreach ($campos as $campo) {
            $selected = ($columnaSeleccionada === $campo) ? "selected" : "";
            echo "<option value='" . htmlspecialchars($campo) . "' $selected>" . htmlspecialchars($campo) . "</option>";
        }

        echo "</select>
            <input type='text' name='palabra_a_filtrar' id='palabra_a_filtrar' placeholder='Ingrese palabra a filtrar' value='$palabra_a_filtrar'>
            <input type='submit' value='Buscar'>
        </form>";

        // Si se envió el formulario (se presionó "Buscar")
        if (!empty($columnaSeleccionada) && !empty($palabra_a_filtrar)) {
                

            // Llamar a la función externa que muestra la tabla filtrada
            $this->filtrar($palabra_a_filtrar, $columnaSeleccionada);
        }
    }

    //Funcion de filtrar para filtrar: que me filtre el stmt en la misma base de datos, actualizar stmt con la  copia
    public function filtrar($palabra, $columna){
        $stmt=$copia_stmt;
        //trabaja con la consulta de $copia_stmt y filtra toda las filas, de tal manera que la colunna que seleccione solo tenga filas que tengan la palabra o numero que envie como parametro
        //no necesariamente mayuscula o minusculas si asi se escribió, que mantenga todas las filas con concidencias de esa palabra y las filas que no coinciden que no las tenga en cuenta, que todo esto
        //se haga en la $copia_stmt, no con la base de datos, no con la vista que dibujo mostrarTabla($stmt_variable), con la variable $copia_stmt, para que asi dibuje esa copia con un mostrarTabla($stmt_variable) que reciba un parametro
        //que en este caso seria $copia_stmt
        //.....

        $this->mostrarTabla($copia_stmt);
    }

}