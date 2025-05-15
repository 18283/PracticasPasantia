<?php
class BarraNavegacion
{
    protected $elementos;
    protected $archivo;
    protected $fondo;
    protected $altura;
    protected $ancho;
	protected $tipoLetra = 'Arial, sans-serif';
	protected $tamanoLetra = '16px';
	protected $colorLetra = '#ffffff';
	protected $negrita = false;
	protected $cursiva = false;
    public function __construct($elem = [])
    {
        //$this->archivo = 'C:\\Users\\senavarro\\Documents\\Proyectos\\bn.json';
        $this->archivo = 'bn.json';

if (file_exists($this->archivo)) {
    $json = file_get_contents($this->archivo);
    $data = json_decode($json, true);

    if (isset($data['bn']) && isset($data['fondo'])) {
        $this->elementos = $data['bn'];
        $this->fondo = $data['fondo'];
        $this->altura = $data['altura'] ?? '50px';
        $this->ancho = $data['ancho'] ?? '100%';

        // Cargar estilos de letra si existen
        $this->tipoLetra = $data['tipoLetra'] ?? 'Arial, sans-serif';
        $this->tamanoLetra = $data['tamanoLetra'] ?? '16px';
        $this->colorLetra = $data['colorLetra'] ?? '#ffffff';
        $this->negrita = $data['negrita'] ?? false;
        $this->cursiva = $data['cursiva'] ?? false;

        echo $this->$data['colorLetra'];
    } else {
        // Valores por defecto
        $this->elementos = $elem;
        $this->fondo = '#d1c4e9';
        $this->altura = '50px';
        $this->ancho = '100%';
        $this->colorLetra = $data['colorLetra'] ?? '#ffffff';
        $this->guardarEnArchivo();
    }
}
    }

/*public function render()
{
    echo '<nav style="position:fixed; top:0; left:0; z-index:1030; background-color:' . htmlspecialchars($this->fondo) . '; height:' . htmlspecialchars($this->altura) . '; width:' . htmlspecialchars($this->ancho) . '; padding:12px;">';
    echo '<ul style="list-style:none; display:flex; margin:0; padding:0;">';
    foreach ($this->elementos as $texto => $url) {
        echo '<li style="margin-right:20px;"><a href="' . htmlspecialchars($url) . '" style="text-decoration:none; color:#007bff;">' . htmlspecialchars($texto) . '</a></li>';
    }
    echo '</ul>';
    echo '</nav>';
}
*/
public function render()
{
    // Estilos de texto para los enlaces del menú
    $estiloTexto = "text-decoration:none;";
    $estiloTexto .= "font-family:{$this->tipoLetra};";
    $estiloTexto .= "font-size:{$this->tamanoLetra};";
   $estiloTexto .= "color:{$this->colorLetra};";
    $estiloTexto .= $this->negrita ? "font-weight:bold;" : "";
    $estiloTexto .= $this->cursiva ? "font-style:italic;" : "";

    // Renderizado del nav fijo
    echo '<nav style="position:fixed; top:0; left:0; z-index:1030; background-color:' . htmlspecialchars($this->fondo) . '; height:' . htmlspecialchars($this->altura) . '; width:' . htmlspecialchars($this->ancho) . '; padding:12px;">';
    echo '<ul style="list-style:none; display:flex; margin:0; padding:0;">';
    foreach ($this->elementos as $texto => $url) {
        echo '<li style="margin-right:20px;"><a href="' . htmlspecialchars($url) . '" style="' . $estiloTexto . '">' . htmlspecialchars($texto) . '</a></li>';
    }
    echo '</ul>';
    echo '</nav>';
}
    //public funtion insertar <Menu>, cambiar valores de menu, parametro lista
    public function insertar($nuevosElementos)
    {
        $this->elementos = array_merge($this->elementos, $nuevosElementos);
        $this->guardarEnArchivo();
        //$this->render();
    }

    //guardar lista de archivo
private function guardarEnArchivo()
{
    $data = [
        'bn' => $this->elementos,
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

    //funcion devolver lista de menu
    public function devolverMenu()
    {
        return $this->elementos;
    }

    //eliminar menu o elemneto de menu
    // Eliminar un elemento del menú por su texto (clave)
    public function eliminarElemento($texto)
    {
        if (isset($this->elementos[$texto])) {
            unset($this->elementos[$texto]);
            $this->guardarEnArchivo();
        }
    }

    //cambiar background color
    public function cambiarFondo($colorHex)
    {
        $this->fondo = $colorHex;
        $this->guardarEnArchivo();
    }

    //cambiar altura
    public function cambiarAltura($nuevaAltura) {
        if (preg_match('/^\d+(px|em|%)$/', $nuevaAltura)) {
            $this->altura = $nuevaAltura;
            $this->guardarEnArchivo();
        } else {
            echo "<p style='color:red;'>Altura inválida. Usa valores como 60px, 3em, 100%...</p>";
        }
    }

    //cambiar ancho
    public function cambiarAncho($nuevoAncho) {
        if (preg_match('/^\d+(px|em|%)$/', $nuevoAncho)) {
            $this->ancho = $nuevoAncho;
            $this->guardarEnArchivo();
        } else {
            echo "<p style='color:red;'>Ancho inválido. Usa valores como 100%, 800px, 60em...</p>";
        }
    }
	public function configurarLetra($tipo, $tamano, $color, $negrita = false, $cursiva = false)
	{
		$this->tipoLetra = $tipo;
		$this->tamanoLetra = $tamano;
		$this->colorLetra = $color;
		$this->negrita = $negrita;
		$this->cursiva = $cursiva;
	}
    //cambiar tamaño de etra
    //cambiar tipo de letra
    //cambiar color de letra
}
