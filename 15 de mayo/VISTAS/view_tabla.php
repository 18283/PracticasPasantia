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
        $this->colorFondo = "#ffffff";      // Fondo de la tabla
        $this->colorEncabezado = "#4CAF50"; // Color encabezado
        $this->colorHover = "#c8e6c9";      // Hover
    }

    // Estilo colorido moderno
    public function establecerEstiloColorido() {
        // Aplicar estilos como atributo style del contenedor
        $this->estiloTabla = "style='
            margin: 30px auto;
            border-collapse: collapse;
            width: 95%;
            max-width: 1000px;
            font-family: Verdana, sans-serif;
            background-color: {$this->colorFondo};
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        '";
    }

    // Cambiar color de fondo
    public function establecerColorFondo($colorHex) {
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $colorHex)) {
            $this->colorFondo = $colorHex;
        } else {
            echo "<p>Color de fondo inválido.</p>";
        }
    }

    // Cambiar color del encabezado
    public function establecerColorEncabezado($colorHex) {
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $colorHex)) {
            $this->colorEncabezado = $colorHex;
        } else {
            echo "<p>Color de encabezado inválido.</p>";
        }
    }

    // Cambiar color de hover
    public function establecerColorHover($colorHex) {
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $colorHex)) {
            $this->colorHover = $colorHex;
        } else {
            echo "<p>Color hover inválido.</p>";
        }
    }

    // Mostrar la tabla
    public function mostrarTabla() {
        if (!$this->stmt) {
            echo "<p>No hay resultados que mostrar.</p>";
            return;
        }

        // CSS dinámico con PHP variables
        echo "<style>
            table.view-tabla th {
                background-color: {$this->colorEncabezado};
                color: white;
                padding: 12px;
                text-align: left;
                border-bottom: 2px solid #999;
            }
            table.view-tabla td {
                padding: 10px;
                border-bottom: 1px solid #ddd;
                transition: background-color 0.3s ease;
            }
            table.view-tabla tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            table.view-tabla tr:nth-child(odd) {
                background-color: #ffffff;
            }
            table.view-tabla tr:hover {
                background-color: {$this->colorHover};
            }
        </style>";

        echo "<table class='view-tabla' {$this->estiloTabla}>";

        // Obtener columnas
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
        echo "<tr>";
        foreach ($columnas as $col) {
            echo "<th>" . htmlspecialchars($col) . "</th>";
        }
        echo "</tr>";

        // Filas
        while ($fila = sqlsrv_fetch_array($this->stmt, SQLSRV_FETCH_ASSOC)) {
            if ($fila === false) {
                echo "<p>Error al obtener los datos.</p>";
                return;
            }
            echo "<tr>";
            foreach ($columnas as $col) {
                echo "<td>" . htmlspecialchars($fila[$col]) . "</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
    }
}
