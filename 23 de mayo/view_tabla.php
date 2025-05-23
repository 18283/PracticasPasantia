<?php
class view_tabla {
    private $stmt;
    private $estiloTabla;
    private $colorFondo;
    private $colorEncabezado;
    private $colorHover;

    public function __construct($resultado) {
        $this->stmt = $resultado;
        $this->estiloTabla = "";
        $this->colorFondo = "#ffffff";
        $this->colorEncabezado = "#cb8db5"; // Usamos el color que mencionaste
        $this->colorHover = "#f3d8eb";      // Color hover suave acorde al encabezado
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

    public function mostrarTabla() {
        if (!$this->stmt) {
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
        $metadata = sqlsrv_field_metadata($this->stmt);
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
        while ($fila = sqlsrv_fetch_array($this->stmt, SQLSRV_FETCH_ASSOC)) {
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
        if (!is_array($campos) || empty($campos)) {
            echo "<p>Parámetros inválidos para el buscador.</p>";
            return;
        }

        $valor = isset($_GET['valor']) ? htmlspecialchars($_GET['valor']) : '';
        $columnaSeleccionada = isset($_GET['columna']) ? $_GET['columna'] : '';

        echo "<form method='GET' style='text-align: center; margin-bottom: 20px;' onsubmit='return buscarEnTabla();'>
            <label for='columna'>Buscar por:</label>
            <select name='columna' id='columna'>";
        
        foreach ($campos as $campo) {
            $selected = ($columnaSeleccionada === $campo) ? "selected" : "";
            echo "<option value='" . htmlspecialchars($campo) . "' $selected>" . htmlspecialchars($campo) . "</option>";
        }

        echo "</select>
            <input type='text' name='valor' id='valor' placeholder='Ingrese valor' value='$valor'>
            <input type='submit' value='Buscar'>
        </form>";

        // Agregamos el script de búsqueda en la tabla (JavaScript)
        echo "<script>
        function buscarEnTabla() {
            const columna = document.getElementById('columna').value;
            const valor = document.getElementById('valor').value.toLowerCase();

            const tabla = document.querySelector('.view-tabla');
            const headers = Array.from(tabla.querySelectorAll('thead th'));
            const index = headers.findIndex(th => th.textContent.trim() === columna);

            if (index === -1) {
                alert('Columna no encontrada');
                return false;
            }

            const filas = tabla.querySelectorAll('tbody tr');
            let encontrado = false;

            filas.forEach(row => row.style.outline = ''); // limpiar estilos previos

            for (let fila of filas) {
                const celda = fila.children[index];
                if (celda && celda.textContent.toLowerCase().includes(valor)) {
                    fila.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    fila.style.outline = '2px solid red';
                    encontrado = true;
                    break;
                }
            }

            if (!encontrado) {
                alert('No se encontró el valor en la tabla.');
            }

            return false; // evitar que se recargue la página
        }
        </script>";
    }

}