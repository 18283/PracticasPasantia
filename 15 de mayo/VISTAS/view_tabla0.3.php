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
            if (sqlsrv_has_rows($stmt)) {
                while ($fila = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $this->resultados[] = $fila;
                }
            } else {
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
                margin: 50px 0 20px;
            }
            
            /* [Resto de estilos CSS originales...] */
        </style>";

        // Filtrado por búsqueda
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
        // Mantener todos los parámetros GET excepto 'buscar' y 'pagina'
        foreach ($_GET as $key => $value) {
            if ($key != 'buscar' && $key != 'pagina') {
                echo "<input type='hidden' name='$key' value='".htmlspecialchars($value)."'>";
            }
        }
        echo "<input type='text' name='buscar' placeholder='Buscar en resultados...' value='".htmlspecialchars($terminoBusqueda)."'>";
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
            echo "<th>".htmlspecialchars($col)."</th>";
        }
        echo "</tr></thead><tbody>";

        // Filas
        foreach ($registrosPagina as $fila) {
            echo "<tr>";
            foreach ($columnas as $col) {
                $valor = $fila[$col] ?? '';
                if ($valor instanceof DateTime) {
                    $valor = $valor->format('Y-m-d H:i:s');
                }
                echo "<td>".htmlspecialchars($valor)."</td>";
            }
            echo "</tr>";
        }

        echo "</tbody></table>";
        echo "</div>";

        // CAMBIO PRINCIPAL: Paginación mejorada
        if ($totalPaginas > 1) {
            echo "<div class='paginacion'>";
            
            // Construir parámetros base manteniendo todos los GET existentes
            $baseParams = $_GET;
            unset($baseParams['pagina']); // Eliminamos el parámetro de página actual
            
            // Botón Anterior
            if ($this->paginaActual > 1) {
                $params = array_merge($baseParams, ['pagina' => $this->paginaActual - 1]);
                echo "<a href='?".http_build_query($params)."' title='Página anterior'>&laquo; Anterior</a>";
            }
            
            // Rango de páginas
            $rangoInicio = max(1, $this->paginaActual - 2);
            $rangoFin = min($totalPaginas, $this->paginaActual + 2);
            
            // Primera página
            if ($rangoInicio > 1) {
                $params = array_merge($baseParams, ['pagina' => 1]);
                echo "<a href='?".http_build_query($params)."' title='Primera página'>1</a>";
                if ($rangoInicio > 2) echo "<span>...</span>";
            }
            
            // Páginas intermedias
            for ($i = $rangoInicio; $i <= $rangoFin; $i++) {
                $params = array_merge($baseParams, ['pagina' => $i]);
                if ($i == $this->paginaActual) {
                    echo "<span class='activa' title='Página actual'>$i</span>";
                } else {
                    echo "<a href='?".http_build_query($params)."' title='Página $i'>$i</a>";
                }
            }
            
            // Última página
            if ($rangoFin < $totalPaginas) {
                if ($rangoFin < $totalPaginas - 1) echo "<span>...</span>";
                $params = array_merge($baseParams, ['pagina' => $totalPaginas]);
                echo "<a href='?".http_build_query($params)."' title='Última página'>$totalPaginas</a>";
            }
            
            // Botón Siguiente
            if ($this->paginaActual < $totalPaginas) {
                $params = array_merge($baseParams, ['pagina' => $this->paginaActual + 1]);
                echo "<a href='?".http_build_query($params)."' title='Página siguiente'>Siguiente &raquo;</a>";
            }
            
            echo "</div>";
        }

        echo "</div>";
    }
}