<?php
class BarraNavegacion {
    private $archivo;
    private $elementos;
    private $fondo;
    private $altura;
    private $ancho;
    private $tamanoLetra;
    private $tipoLetra;
    private $colorLetra;

    public function __construct($archivo = null) {
        // Usar ruta en el servidor
        $this->archivo = $archivo ?? __DIR__ . 'menu.json';
		//$this->archivo = 'menu.json';
        if (file_exists($this->archivo)) {
            $data = json_decode(file_get_contents($this->archivo), true);
            $this->elementos = $data['menu'] ?? [];
            $this->fondo = $data['fondo'] ?? '#f8f9fa';
            $this->altura = $data['altura'] ?? '60px';
            $this->ancho = $data['ancho'] ?? '100%';
            $this->tamanoLetra = $data['tamanoLetra'] ?? '16px';
            $this->tipoLetra = $data['tipoLetra'] ?? 'Arial, sans-serif';
            $this->colorLetra = $data['colorLetra'] ?? '#007bff';
        } else {
            // Valores por defecto
            $this->elementos = [];
            $this->fondo = '#f8f9fa';
            $this->altura = '60px';
            $this->ancho = '100%';
            $this->tamanoLetra = '16px';
            $this->tipoLetra = 'Arial, sans-serif';
            $this->colorLetra = '#007bff';
        }
    }

    public function agregarElemento($texto, $url) {
        $this->elementos[] = ['texto' => $texto, 'url' => $url];
        $this->guardarEnArchivo();
    }

    public function cambiarColorFondo($colorHex) {
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $colorHex)) {
            $this->fondo = $colorHex;
            $this->guardarEnArchivo();
        }
    }

    public function cambiarAltura($altura) {
        $this->altura = $altura;
        $this->guardarEnArchivo();
    }

    public function cambiarAncho($ancho) {
        $this->ancho = $ancho;
        $this->guardarEnArchivo();
    }

    public function cambiarTamanoLetra($tam) {
        if (preg_match('/^\d+(px|em|rem|%)$/', $tam)) {
            $this->tamanoLetra = $tam;
            $this->guardarEnArchivo();
        }
    }

    public function cambiarTipoLetra($tipo) {
        $this->tipoLetra = $tipo;
        $this->guardarEnArchivo();
    }

    public function cambiarColorLetra($colorHex) {
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $colorHex)) {
            $this->colorLetra = $colorHex;
            $this->guardarEnArchivo();
        }
    }

    private function guardarEnArchivo() {
        $data = [
            'menu' => $this->elementos,
            'fondo' => $this->fondo,
            'altura' => $this->altura,
            'ancho' => $this->ancho,
            'tamanoLetra' => $this->tamanoLetra,
            'tipoLetra' => $this->tipoLetra,
            'colorLetra' => $this->colorLetra
        ];

        file_put_contents($this->archivo, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function render() {
        echo '<nav style="background-color:' . htmlspecialchars($this->fondo) . '; height:' . htmlspecialchars($this->altura) . '; width:' . htmlspecialchars($this->ancho) . '; padding:12px;">';
        echo '<ul style="list-style:none; display:flex; margin:0; padding:0; font-size:' . htmlspecialchars($this->tamanoLetra) . '; font-family:' . htmlspecialchars($this->tipoLetra) . '; color:' . htmlspecialchars($this->colorLetra) . ';">';

        foreach ($this->elementos as $item) {
            $texto = htmlspecialchars($item['texto']);
            $url = htmlspecialchars($item['url']);
            echo '<li style="margin-right:20px;"><a href="' . $url . '" style="text-decoration:none; color:' . $this->colorLetra . ';">' . $texto . '</a></li>';
        }

        echo '</ul>';
        echo '</nav>';
    }
}
