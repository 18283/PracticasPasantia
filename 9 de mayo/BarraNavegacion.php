<?php
class BarraNavegacion
{
    private $elementos;
    private $archivo;
    private $fondo;
    private $altura;
    private $ancho;

    public function __construct($elem = [])
    {
        $this->archivo = 'C:\\Users\\senavarro\\Documents\\Proyectos\\menu.json';
        //$this->archivo = 'menu.json';

        if (file_exists($this->archivo)) {
            $json = file_get_contents($this->archivo);
            $data = json_decode($json, true);

            if (isset($data['menu']) && isset($data['fondo'])) {
                $this->elementos = $data['menu'];
                $this->fondo = $data['fondo'];
                $this->altura = $data['altura'] ?? '50px'; // altura por defecto
                $this->ancho = $data['ancho'] ?? '100%'; // ancho por defecto
            } else {
                $this->elementos = $elem;
                $this->fondo = '#d1c4e9';
                $this->altura = '50px';
                $this->ancho = '100%';
                $this->guardarEnArchivo();
            }
        } else {
            $this->elementos = $elem;
            $this->fondo = '#d1c4e9';
            $this->altura = '50px';
            $this->guardarEnArchivo();
        }
    }

    public function render()
    {
        echo '<nav style="background-color:' . htmlspecialchars($this->fondo) . '; height:' . htmlspecialchars($this->altura) . '; width:' . htmlspecialchars($this->ancho) . '; padding:12px;">';
        echo '<ul style="list-style:none; display:flex; margin:0; padding:0;">';
        foreach ($this->elementos as $texto => $url) {
            echo '<li style="margin-right:20px;"><a href="' . htmlspecialchars($url) . '" style="text-decoration:none; color:#007bff;">' . htmlspecialchars($texto) . '</a></li>';
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
            'menu' => $this->elementos,
            'fondo' => $this->fondo,
            'altura' => $this->altura,
            'ancho' => $this->ancho
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

    //cambiar tamaño de etra
    //cambiar tipo de letra
    //cambiar color de letra
}
