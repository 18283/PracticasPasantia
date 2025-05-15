<?php
require_once 'BarraNavegacion.php';

class Menu extends BarraNavegacion
{
    public function __construct($archivoJson, $claveMenu, $elem = [])
    {
        $this->archivo = __DIR__ . '/' . $archivoJson;

        if (file_exists($this->archivo)) {
            $json = file_get_contents($this->archivo);
            $data = json_decode($json, true);

            if (isset($data[$claveMenu]) && isset($data['fondo'])) {
                $this->elementos = $data[$claveMenu];
                $this->fondo = $data['fondo'];
                $this->altura = $data['altura'] ?? '100vh';
                $this->ancho = $data['ancho'] ?? '200px';
                $this->tipoLetra = $data['tipoLetra'] ?? 'Arial, sans-serif';
                $this->tamanoLetra = $data['tamanoLetra'] ?? '16px';
                $this->colorLetra = $data['colorLetra'] ?? '#007bff';
                $this->negrita = $data['negrita'] ?? false;
                $this->cursiva = $data['cursiva'] ?? false;
            } else {
                $this->elementos = $elem;
                $this->fondo = '#f0f0f0';
                $this->altura = '100vh';
                $this->ancho = '200px';
                $this->guardarEnArchivo($claveMenu);
            }
        } else {
            $this->elementos = $elem;
            $this->fondo = '#f0f0f0';
            $this->altura = '100vh';
            $this->ancho = '200px';
            $this->guardarEnArchivo($claveMenu);
        }
    }

    public function render()
    {
        $estiloTexto = "text-decoration:none;";
        $estiloTexto .= "font-family:{$this->tipoLetra};";
        $estiloTexto .= "font-size:{$this->tamanoLetra};";
        $estiloTexto .= "color:{$this->colorLetra};";
        $estiloTexto .= $this->negrita ? "font-weight:bold;" : "";
        $estiloTexto .= $this->cursiva ? "font-style:italic;" : "";

        // Contenedor del menú
        echo '<aside style="position:fixed; top:50px; left:0; height:calc(' . htmlspecialchars($this->altura) . ' - 60px); width:' . htmlspecialchars($this->ancho) . '; background-color:' . htmlspecialchars($this->fondo) . '; padding:20px; overflow-y:auto;">';

        // Estilos adicionales inline (puedes mover esto a un CSS si prefieres)
        echo <<<STYLE
        <style>
            .menu-item {
                margin-bottom: 12px;
                padding: 10px;
                border-radius: 6px;
                position: relative;
                transition: background-color 0.3s ease, transform 0.2s;
            }

            .menu-item:hover {
                background-color: rgba(255, 255, 255, 0.8); /* color más blanquecino */
                box-shadow: 0 4px 12px rgba(0,0,0,0.05);
                transform: translateX(5px);
            }

            .menu-item a {
                display: block;
                width: 100%;
            }
        </style>
    STYLE;

        echo '<ul style="list-style:none; padding:0;">';

        foreach ($this->devolverMenu() as $texto => $url) {
            echo '<li class="menu-item"><a href="' . htmlspecialchars($url) . '" style="' . $estiloTexto . '">' . htmlspecialchars($texto) . '</a></li>';
        }

        echo '</ul>';
        echo '</aside>';
    }


    // Sobrescribe guardarEnArchivo para usar el archivo correcto
    protected function guardarEnArchivo($claveMenu)
    {
        $data = [
            $claveMenu => $this->elementos,
            'fondo' => $this->fondo,
            'altura' => $this->altura,
            'ancho' => $this->ancho,
            'tipoLetra' => $this->tipoLetra,
            'tamanoLetra' => $this->tamanoLetra,
            'colorLetra' => $this->colorLetra,
            'negrita' => $this->negrita,
            'cursiva' => $this->cursiva
        ];

        $resultado = file_put_contents($this->archivo, json_encode($data, JSON_PRETTY_PRINT));
        if ($resultado === false) {
            echo "<p style='color:red;'>Error al guardar en {$this->archivo}</p>";
        }
    }
}
