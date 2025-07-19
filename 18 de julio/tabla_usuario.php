<?php

require_once '../descargar_departamentos.php';
require_once '../../CONTROLADOR/USUARIOS/UsuariosController.php';

class view_tabla {
    private $tabla;
    private $estiloTabla;
    private $colorFondo;
    private $colorEncabezado;
    private $colorHover;
    private $filtrados;
    private $rutaEdicion = null;
    private $rutaAnadir = null;
    private $rutaBorrar = null;
    private $nombreCampoID = 'Carnet';
    private $filasPorPagina = 10;
    private $paginaActual = 1;


    public function __construct($resultado) {
        $this->tabla = $resultado;
        $this->estiloTabla = "";
        $this->colorFondo = "#ffffff";
        $this->colorEncabezado = "#cb8db5";
        $this->colorHover = "#f3d8eb";
    }

    public function obtenerTabla() {
        return $this->tabla;
    }

    public function Devolver_filtrados() {
        return $this->filtrados;
    }
    public function mostrarBotonDescarga() {
        echo '<form method="post" style="text-align: center; margin-top: 20px;">';
        echo '<input type="submit" name="descargar" value="Descargar Tabla" style="padding: 10px 20px; background-color: #cb8db5; color: white; border: none; border-radius: 4px; cursor: pointer;">';
        echo '</form>';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['descargar'])) {
           //$this->procesarDescarga();
        }
    }

    public function procesarDescarga() {
        $datos_array = $this->Devolver_filtrados() ?? $this->tabla;
        if (empty($datos_array)) {
            echo "<p style='text-align: center;'>No hay datos para descargar.</p>";
            return;
        }
        $descargar = new DescargarDepartamento($datos_array, 'descarga');
        $descargar->descargar();
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

    public function mostrarTabla($datos_array = null) {
        $mostrarEdicion = $this->rutaEdicion && isset($datos_array[0]['USERID']);
        $datos_array = $datos_array ?? $this->tabla;
        
        $totalFilas = count($datos_array);
        $totalPaginas = ceil($totalFilas / $this->filasPorPagina);
        $this->paginaActual = min($this->paginaActual, $totalPaginas);
        $inicio = ($this->paginaActual - 1) * $this->filasPorPagina;
        $datos_array = array_slice($datos_array, $inicio, $this->filasPorPagina);

        if (!$datos_array || empty($datos_array)) {
            echo "<p style='text-align: center;'>No se encontraron resultados.</p>";
            return;
        }

        // Estilos completos
        echo "<style>
            .tabla-contenedor {
                width: fit-content;
                max-width: 100%;
                margin: 0 auto 30px auto;
                max-height: 700px;
                overflow-x: auto;
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
                width: auto;
                max-width: 100%;
                min-width: 300px;
                margin-left: auto;
                margin-right: auto;
                width: 100%;
                border-collapse: collapse;
                margin: 0;
            }
            .edit-icon-cell {
                width: 50px;
                text-align: center;
            }
            .edit-icon {
                visibility: hidden;
                font-size: 18px;
                text-decoration: none;
                color: #007bff;
                transition: color 0.3s;
            }
            .view-tabla tr:hover .edit-icon {
                visibility: visible;
            }
            .edit-icon:hover {
                color: #0056b3;
            }
        </style>";

        if ($this->rutaAnadir) {
            $linkNuevo = $this->rutaAnadir;
            echo "<div style='text-align: left; margin: 10px 0;'>
                    <a href='$linkNuevo' style='
                        display: inline-block;
                        padding: 10px 20px;
                        background-color: #007bff;
                        color: white;
                        border-radius: 4px;
                        text-decoration: none;
                        font-weight: bold;
                    '>➕ Nuevo</a>
                </div>";
        }


        //echo "<div style='text-align: center;'><h3>Resultados encontrados:</h3></div>";
        echo "<div class='tabla-contenedor'>";
        echo "<table class='view-tabla' {$this->estiloTabla}>";

        // Obtener columnas desde la primera fila
        if (empty($datos_array) || !isset($datos_array[0])) {
            echo "<p style='text-align: center;'>No se encontraron resultados.</p>";
            return;
        }

        $columnas = array_keys($datos_array[0]);
        // Encabezado
        echo "<thead><tr>";
        $mostrarEdicion = $this->rutaEdicion && isset($datos_array[0][$this->nombreCampoID]);
        $mostrarBorrado = !empty($this->rutaBorrar);
        if ($mostrarEdicion || $mostrarBorrado) {
            echo "<th>Acciones</th>"; // Nueva columna solo si se activó edición y USERID existe
        }
        foreach ($columnas as $col) {
            echo "<th>" . htmlspecialchars($col) . "</th>";
        }
        echo "</tr></thead>";


        // Cuerpo
        echo "<tbody>";
        foreach ($datos_array as $fila) {
            echo "<tr>";
        //Lo que dirije a al link de editar
            if ($mostrarEdicion || $mostrarBorrado) {
                $id = urlencode($fila[$this->nombreCampoID]); //es este :)

                $UC= new UsuariosController();
                $idUs = $UC->CarnetAUserID($id);
                $datosUs = $UC->obtenerUsuarioPorId($idUs);

                echo "<td class='edit-icon-cell'>";
                if ($mostrarEdicion) {
                    echo "<form method='POST' action='{$this->rutaEdicion}' style='display:inline;'>
                                <input type='hidden' name='datosUsuario[USERID]' value='" . htmlspecialchars($datosUs['USERID'] ?? '') . "'>
                                <input type='hidden' name='datosUsuario[BADGENUMBER]' value='" . htmlspecialchars($datosUs['BADGENUMBER'] ?? '') . "'>
                                <input type='hidden' name='datosUsuario[NAME]' value='" . htmlspecialchars($datosUs['NAME'] ?? '') . "'>
                                <input type='hidden' name='datosUsuario[privilege]' value='" . htmlspecialchars($datosUs['privilege'] ?? '') . "'>
                                <input type='hidden' name='datosUsuario[GENDER]' value='" . htmlspecialchars($datosUs['GENDER'] ?? '') . "'>
                                <input type='hidden' name='datosUsuario[EMail]' value='" . htmlspecialchars($datosUs['EMail'] ?? '') . "'>
                                <input type='hidden' name='datosUsuario[HIREDDAY]' value='" . htmlspecialchars($datosUs['HIREDDAY'] ?? '') . "'>
                                <input type='hidden' name='datosUsuario[BIRTHDAY]' value='" . htmlspecialchars($datosUs['BIRTHDAY'] ?? '') . "'>
                                <input type='hidden' name='datosUsuario[DEPTID]' value='" . htmlspecialchars($datosUs['DEPTID'] ?? '') . "'>
                                <input type='hidden' name='datosUsuario[OPHONE]' value='" . htmlspecialchars($datosUs['OPHONE'] ?? '') . "'>
                                <input type='hidden' name='datosUsuario[FPHONE]' value='" . htmlspecialchars($datosUs['FPHONE'] ?? '') . "'>
                                <button type='submit' class='edit-icon' title='Editar' style='background:none;border:none;cursor:pointer;'>📝</button>
                            </form>";
                    //Cuando aprete 📝, quiero que ejecute la funcion $formularioEdicion->mostrarFormularioEdicion();
                }
                if ($mostrarBorrado) {
                    echo "<a href='{$this->rutaBorrar}?id=$id' class='edit-icon' title='Eliminar' style='color: red; margin-left: 8px;'>🗑️</a>";
                }
                echo "</td>";
            }

            foreach ($columnas as $col) {
                $valor = $fila[$col] ?? '';
                echo "<td>" . htmlspecialchars(is_string($valor) ? $valor : (string)$valor) . "</td>";
            }

            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        if ($totalPaginas > 1) {
            echo "<div style='text-align:center; margin-top:10px;'>";

            if ($totalPaginas > 1) {
                echo "<div style='text-align:center; margin-top:10px;'>";

                $rango = 5; // Número de páginas visibles a la vez
                $inicio = max(1, $this->paginaActual - floor($rango / 2));
                $fin = min($totalPaginas, $inicio + $rango - 1);

                if ($fin - $inicio + 1 < $rango) {
                    $inicio = max(1, $fin - $rango + 1);
                }

                $baseUrl = strtok($_SERVER["REQUEST_URI"], '?');
                $params = $_GET;
                
                // Botón « ir a primera
                if ($this->paginaActual > 1) {
                    $params['pagina'] = 1;
                    $url = $baseUrl . '?' . http_build_query($params);
                    echo "<a href='$url' style='margin: 0 5px;'>&laquo;</a>";
                }

                // Botón ‹ página anterior
                if ($this->paginaActual > 1) {
                    $params['pagina'] = $this->paginaActual - 1;
                    $url = $baseUrl . '?' . http_build_query($params);
                    echo "<a href='$url' style='margin: 0 5px;'>&lsaquo;</a>";
                }

                // Números visibles
                for ($i = $inicio; $i <= $fin; $i++) {
                    $params['pagina'] = $i;
                    $url = $baseUrl . '?' . http_build_query($params);
                    $active = $i == $this->paginaActual ? "font-weight:bold; text-decoration:underline;" : "";
                    echo "<a href='$url' style='margin: 0 3px; $active'>$i</a>";
                }

                // Botón › página siguiente
                if ($this->paginaActual < $totalPaginas) {
                    $params['pagina'] = $this->paginaActual + 1;
                    $url = $baseUrl . '?' . http_build_query($params);
                    echo "<a href='$url' style='margin: 0 5px;'>&rsaquo;</a>";
                }

                // Botón » última
                if ($this->paginaActual < $totalPaginas) {
                    $params['pagina'] = $totalPaginas;
                    $url = $baseUrl . '?' . http_build_query($params);
                    echo "<a href='$url' style='margin: 0 5px;'>&raquo;</a>";
                }

                echo "</div>";
            }


            echo "</div>";
        }

    }
    
    public function buscador($campos) {
        if (!is_array($campos) || empty($campos)) {
            echo "<p>Parámetros inválidos para el buscador.</p>";
            return;
        }

        $palabra_a_filtrar = filter_input(INPUT_GET, 'palabra_a_filtrar', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
        $columnaSeleccionada = filter_input(INPUT_GET, 'columna', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';

        // Estilos personalizados para el formulario
        echo "<style>
            .buscador-contenedor {
                background-color: #d1c4e9;
                //border: 1px solid #a887e6;
                border-radius: 8px;
                padding: 20px;
                margin: 10px auto;
                max-width: 800px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                flex-wrap: wrap;
                font-family: Arial, sans-serif;
            }

            .buscador-contenedor label {
                font-weight: bold;
                color: #444;
            }

            .buscador-contenedor select,
            .buscador-contenedor input[type='text'] {
                padding: 10px;
                border-radius: 6px;
                border: 1px solid #ccc;
                flex: 1;
                min-width: 150px;
            }

            .buscador-contenedor input[type='submit'] {
                background-color: white;
                color: #a887e6;
                padding: 10px 20px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                transition: background-color 0.3s ease;
                font-weight: bold;
            }

            .buscador-contenedor input[type='submit']:hover {
                background-color: #000000;
            }
        </style>";

        echo "<form method='GET'>
            <div class='buscador-contenedor'>
                <label for='columna'>Buscar por:</label>
                <select name='columna' id='columna'>";
        foreach ($campos as $campo) {
            $selected = ($columnaSeleccionada === $campo) ? "selected" : "";
            echo "<option value='" . htmlspecialchars($campo) . "' $selected>" . htmlspecialchars($campo) . "</option>";
        }
        echo "</select>
                <input type='text' name='palabra_a_filtrar' id='palabra_a_filtrar' placeholder='Ingrese palabra a filtrar' value='$palabra_a_filtrar'>
                <input type='submit' value='Buscar'>
            </div>
        </form>";

        $this->filtrar($palabra_a_filtrar, $columnaSeleccionada);
    }


    public function preparar_busqueda($palabra) {
        // Elimina espacios al principio y al final
        $palabra = trim($palabra);

        // Reemplaza múltiples espacios entre palabras con un solo espacio
        $palabra = preg_replace('/\s+/', ' ', $palabra);

        return $palabra;
    }

    //Funcion de filtrar para filtrar: que me filtre el arreglo en la misma base de datos, actualizar arreglo con la  copia
    public function filtrar($palabra, $columna){
        if (empty($palabra) || empty($columna)) {        //if (($palabra==null || $palabra=="")||($columna==null || $columna=="")){
            $this->mostrarTabla($this->tabla);
        } else {
            $palabra = $this->preparar_busqueda($palabra);
            $palabra = strtolower($palabra); // para comparación sin importar mayúsculas/minúsculas

            $this->filtrados = array_filter($this->tabla, function($fila) use ($palabra, $columna) {
                if (!isset($fila[$columna])) return false;

                $valor = strtolower(strval($fila[$columna]));
                return strpos($valor, $palabra) !== false;
            });

            // Reindexar el array por seguridad
            $this->filtrados = array_values($this->filtrados);

            $this->mostrarTabla($this->filtrados);
        }
        
    }

    public function RutasDeAcciones($rutaE, $rutaA, $rutaB) {
        $this->rutaEdicion = $rutaE; // Ejemplo: "view_actualizar.php"
        $this->rutaAnadir = $rutaA;
        $this->rutaBorrar = $rutaB;
    }

    public function establecerCampoID($campo) {
        $this->nombreCampoID = $campo;
    }

    public function configurarPaginacion($filasPorPagina = 10, $paginaActual = 1) {
        $this->filasPorPagina = max(1, (int)$filasPorPagina);
        $this->paginaActual = max(1, (int)$paginaActual);
    }
}
