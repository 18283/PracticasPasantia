<?php
class view_tabla
{
    private $resultados;
    private $estiloTabla;
    private $colorFondo;
    private $colorEncabezado;
    private $colorHover;
    private $paginaActual;
    private $registrosPorPagina;
    private $mensajeError;

    public function __construct($stmt, $paginaActual = 1, $registrosPorPagina = 15)
    {
        $this->resultados = [];
        $this->mensajeError = null;

        // Almacenar todos los resultados y manejar posibles mensajes
        if ($stmt) {
            // Verificar si es un resultado válido
            // [ANTES] No manejaba resultados inmediatamente
            // [AHORA] Almacena todos los resultados y mensajes del SP
            if (sqlsrv_has_rows($stmt)) {
                while ($fila = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $this->resultados[] = $fila;
                }
            } else {
                // Intentar obtener mensaje del SP
                $fila = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
                if ($fila) {
                    if (isset($fila['MensajeError'])) {
                        $this->mensajeError = $fila['MensajeError'];
                    } elseif (isset($fila['Informacion'])) {
                        $this->mensajeError = $fila['Informacion'];
                    }
                }
            }
        }

        $this->paginaActual = max(1, (int)$paginaActual);
        $this->registrosPorPagina = max(5, (int)$registrosPorPagina);
        $this->estiloTabla = "";
        $this->colorFondo = "#f8fbff";
        $this->colorEncabezado = "#1976d2";
        $this->colorHover = "#e3f2fd";
    }

    public function establecerEstiloColorido()
    {
        $this->estiloTabla = "style='
            margin: 20px auto;
            border-collapse: collapse;
            width: 98%;
            max-width: 1000px;
            font-family: Verdana, sans-serif;
            background-color: {$this->colorFondo};
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow-x: auto;
        '";
    }

    public function establecerColorFondo($colorHex)
    {
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $colorHex)) {
            $this->colorFondo = $colorHex;
        } else {
            echo "<p>Color de fondo inválido.</p>";
        }
    }

    public function establecerColorEncabezado($colorHex)
    {
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $colorHex)) {
            $this->colorEncabezado = $colorHex;
        } else {
            echo "<p>Color de encabezado inválido.</p>";
        }
    }

    public function establecerColorHover($colorHex)
    {
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $colorHex)) {
            $this->colorHover = $colorHex;
        } else {
            echo "<p>Color hover inválido.</p>";
        }
    }

    public function mostrarTabla()
    {
        // Mostrar mensaje de error si existe
        if ($this->mensajeError !== null) {
            echo "<div class='mensaje-sp' style='margin: 20px auto; padding: 15px; background-color: #fff3cd; 
                  border-left: 5px solid #ffc107; max-width: 1000px; border-radius: 4px;'>
                  <p style='margin: 0; color: #856404;'>{$this->mensajeError}</p>
              </div>";
            return;
        }

        // Verificar si hay resultados
        if (empty($this->resultados)) {
            echo "<p style='text-align: center; margin: 20px;'>No se encontraron registros.</p>";
            return;
        }

        // CSS 
        echo "<style>
            .contenedor-tabla-completo {
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 100%;
                margin: 50px 0 20px; /* <-- aquí ajustamos el margen superior */
            }
            
            .buscador-tabla {
                margin: 20px 0;
                width: 100%;
                max-width: 1000px;
                display: flex;
                justify-content: center;
            }
            
            .buscador-tabla input {
                padding: 10px 15px;
                width: 70%;
                max-width: 500px;
                border: 1px solid #ddd;
                border-radius: 20px;
                font-size: 16px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                transition: all 0.3s;
            }
            
            .buscador-tabla input:focus {
                outline: none;
                border-color: {$this->colorEncabezado};
                box-shadow: 0 2px 8px rgba(25, 118, 210, 0.2);
            }
            
            .buscador-tabla button {
                padding: 10px 20px;
                margin-left: 10px;
                background-color: {$this->colorEncabezado};
                color: white;
                border: none;
                border-radius: 20px;
                cursor: pointer;
                transition: background-color 0.3s;
                font-weight: bold;
            }
            
            .buscador-tabla button:hover {
                background-color: #1565c0;
            }
            
            .contenedor-tabla {
                width: 100%;
                max-width: 1300px;
                overflow-x: auto;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                border-radius: 8px;
                background-color: white;
            }
            
            table.view-tabla {
                width: 100%;
                border-collapse: collapse;
                font-size: 14px;
            }
            
            table.view-tabla th {
                background-color: {$this->colorEncabezado};
                color: white;
                padding: 12px 15px;
                text-align: left;
                position: sticky;
                top: 0;
                font-weight: 600;
            }
            
            table.view-tabla td {
                padding: 10px 15px;
                border-bottom: 1px solid #e0e0e0;
                vertical-align: top;
            }
            
            table.view-tabla tr:nth-child(even) {
                background-color: #f5f9ff;
            }
            
            table.view-tabla tr:nth-child(odd) {
                background-color: {$this->colorFondo};
            }
            
            table.view-tabla tr:hover {
                background-color: {$this->colorHover};
            }
            
            .paginacion {
                display: flex;
                justify-content: center;
                margin: 20px 0;
                flex-wrap: wrap;
                gap: 5px;
            }
            
            .paginacion a, .paginacion span {
                padding: 8px 12px;
                border: 1px solid #ddd;
                text-decoration: none;
                color: #333;
                border-radius: 4px;
                transition: all 0.3s;
                min-width: 40px;
                text-align: center;
                display: inline-block;
            }
            
            .paginacion a:hover {
                background-color: {$this->colorHover};
                border-color: {$this->colorEncabezado};
            }
            
            .paginacion .activa {
                background-color: {$this->colorEncabezado};
                color: white;
                border-color: {$this->colorEncabezado};
                font-weight: bold;
            }
            
            .mensaje-sp {
                margin: 20px auto;
                padding: 15px;
                background-color: #fff3cd;
                border-left: 5px solid #ffc107;
                max-width: 1000px;
                border-radius: 4px;
            }
            
            @media (max-width: 768px) {
                table.view-tabla th, 
                table.view-tabla td {
                    padding: 8px 10px;
                    font-size: 13px;
                }
                
                .buscador-tabla input {
                    width: 60%;
                    padding: 8px 12px;
                }
                
                .buscador-tabla button {
                    padding: 8px 15px;
                    margin-left: 8px;
                }
            }
        </style>";

        // Filtrado por búsqueda,Búsqueda ahora filtra números y strings
        $terminoBusqueda = $_GET['buscar'] ?? '';
        $registrosFiltrados = $this->resultados;

        if ($terminoBusqueda) {
            $registrosFiltrados = array_filter($this->resultados, function ($fila) use ($terminoBusqueda) {
                foreach ($fila as $valor) {
                    if (is_string($valor) && stripos($valor, $terminoBusqueda) !== false) {
                        return true;
                    } elseif (is_numeric($valor) && strpos((string)$valor, $terminoBusqueda) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        $totalRegistros = count($registrosFiltrados);
        $totalPaginas = ceil($totalRegistros / $this->registrosPorPagina);
        $this->paginaActual = min($this->paginaActual, $totalPaginas);
        $inicio = ($this->paginaActual - 1) * $this->registrosPorPagina;
        $registrosPagina = array_slice($registrosFiltrados, $inicio, $this->registrosPorPagina);

        // Obtener nombres de columnas del primer registro
        $columnas = !empty($this->resultados) ? array_keys($this->resultados[0]) : [];

        // Contenedor completo
        echo "<div class='contenedor-tabla-completo'>";

        // Buscador
        echo "<form class='buscador-tabla' method='get' action=''>";
        foreach ($_GET as $key => $value) {
            if ($key != 'buscar' && $key != 'pagina') {
                echo "<input type='hidden' name='$key' value='$value'>";
            }
        }
        echo "<input type='text' name='buscar' placeholder='Buscar en resultados...' value='" . htmlspecialchars($terminoBusqueda) . "'>";
        echo "<button type='submit'>Buscar</button>";
        echo "</form>";

        // Mostrar estadísticas
        echo "<div style='width: 100%; max-width: 1000px; text-align: right; margin-bottom: 10px; font-size: 14px; color: #666;'>
                Mostrando " . count($registrosPagina) . " de $totalRegistros registros
              </div>";

        // Contenedor tabla
        echo "<div class='contenedor-tabla'>";
        echo "<table class='view-tabla' {$this->estiloTabla}>";

        // Encabezados
        echo "<thead><tr>";
        foreach ($columnas as $col) {
            echo "<th>" . htmlspecialchars($col) . "</th>";
        }
        echo "</tr></thead><tbody>";

        // Filas
        foreach ($registrosPagina as $fila) {
            echo "<tr>";
            foreach ($columnas as $col) {
                $valor = $fila[$col] ?? '';
                // Formatear fechas si es necesario
                if ($valor instanceof DateTime) {
                    $valor = $valor->format('Y-m-d H:i:s');
                }
                echo "<td>" . htmlspecialchars($valor) . "</td>";
            }
            echo "</tr>";
        }

        echo "</tbody></table>";
        echo "</div>";

        // Paginación
        if ($totalPaginas > 1) {
            echo "<div class='paginacion'>";

            // Anterior
            if ($this->paginaActual > 1) {
                $url = "?" . http_build_query(array_merge($_GET, ['pagina' => $this->paginaActual - 1]));
                echo "<a href='$url' title='Página anterior'>&laquo; Anterior</a>";
            }

            // Rango de páginas
            $inicioPaginas = max(1, $this->paginaActual - 2);
            $finPaginas = min($totalPaginas, $this->paginaActual + 2);

            // Primera página
            if ($inicioPaginas > 1) {
                $url = "?" . http_build_query(array_merge($_GET, ['pagina' => 1]));
                echo "<a href='$url' title='Primera página'>1</a>";
                if ($inicioPaginas > 2) echo "<span>...</span>";
            }

            // Páginas intermedias
            for ($i = $inicioPaginas; $i <= $finPaginas; $i++) {
                $url = "?" . http_build_query(array_merge($_GET, ['pagina' => $i]));
                if ($i == $this->paginaActual) {
                    echo "<span class='activa' title='Página $i'>$i</span>";
                } else {
                    echo "<a href='$url' title='Página $i'>$i</a>";
                }
            }

            // Última página
            if ($finPaginas < $totalPaginas) {
                if ($finPaginas < $totalPaginas - 1) echo "<span>...</span>";
                $url = "?" . http_build_query(array_merge($_GET, ['pagina' => $totalPaginas]));
                echo "<a href='$url' title='Última página'>$totalPaginas</a>";
            }

            // Siguiente
            if ($this->paginaActual < $totalPaginas) {
                $url = "?" . http_build_query(array_merge($_GET, ['pagina' => $this->paginaActual + 1]));
                echo "<a href='$url' title='Página siguiente'>Siguiente &raquo;</a>";
            }

            echo "</div>";
        }

        echo "</div>";
    }
}
