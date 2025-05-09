<?php
class BarraNavegacion {
    private $elementos;
    private $archivo;
    private $fondo;

    public function __construct($elem = []) {
        $this->archivo = 'C:\\Users\\senavarro\\Documents\\Proyectos\\menu.json';

        if (file_exists($this->archivo)) {
            $json = file_get_contents($this->archivo);
            $data = json_decode($json, true);

            if (isset($data['menu']) && isset($data['fondo'])) {
                $this->elementos = $data['menu'];
                $this->fondo = $data['fondo'];
            } else {
                $this->elementos = $elem;
                $this->fondo = '#d1c4e9'; // Valor por defecto
                $this->guardarEnArchivo();
            }
        } else {
            $this->elementos = $elem;
            $this->fondo = '#d1c4e9'; // Valor por defecto
            $this->guardarEnArchivo();
        }
    }

    public function render() {
        echo '<nav style="background-color:' . htmlspecialchars($this->fondo) . '; padding:12px;">';
        echo '<ul style="list-style:none; display:flex; margin:0; padding:0;">';
        foreach ($this->elementos as $texto => $url) {
            echo '<li style="margin-right:20px;"><a href="' . htmlspecialchars($url) . '" style="text-decoration:none; color:#007bff;">' . htmlspecialchars($texto) . '</a></li>';
        }
        echo '</ul>';
        echo '</nav>';
    }

    //public funtion insertar <Menu>, cambiar valores de menu, parametro lista
    public function insertar($nuevosElementos) {
        $this->elementos = array_merge($this->elementos, $nuevosElementos);
        $this->guardarEnArchivo();
        $this->render();
    }

    //guardar lista de archivo
    private function guardarEnArchivo() {
        $data = [
            'menu' => $this->elementos,
            'fondo' => $this->fondo
        ];

        $resultado = file_put_contents($this->archivo, json_encode($data, JSON_PRETTY_PRINT));
        if ($resultado === false) {
            echo "<p style='color:red;'>Error al guardar en {$this->archivo}</p>";
        }
    }

    //funcion devolver lista de menu
    public function devolverMenu() {
        return $this->elementos;
    }

    //eliminar menu o elemneto de menu
    // Eliminar un elemento del menú por su texto (clave)
    public function eliminarElemento($texto) {
        if (isset($this->elementos[$texto])) {
            unset($this->elementos[$texto]);
            $this->guardarEnArchivo();
        }
    }

   //cambiar background color
    public function cambiarFondo($colorHex) {
        $this->fondo = $colorHex;
        $this->guardarEnArchivo();
    }

   //cambiar altura
   //cambiar ancho
   //cambiar tamaño de etra
   //cambiar tipo de letra
   //cambiar color de letra
}
?>