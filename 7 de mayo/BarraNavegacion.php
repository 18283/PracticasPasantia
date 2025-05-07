<?php
class BarraNavegacion {
    private $elementos;

    public function __construct($elem) {
        $this->elementos = $elem;
    }

    public function render() {
        echo '<nav style="background-color:#d1c4e9; padding:12px;">'; //codigo HTML fondo y epacio
        echo '<ul style="list-style:none; display:flex; margin:0; padding:0;<!--para comentar-->">';
        foreach ($this->elementos as $texto => $url) {
            echo '<li style="margin-right:20px;"><a href="' . htmlspecialchars($url) . '" style="text-decoration:none; color:#007bff;">' . htmlspecialchars($texto) . '</a></li>';
        }
        echo '</ul>';
        echo '</nav>';
    }

  //  public funtion insertar <Menu>, cambiar valores de menu, parametro lista
    public function insertar($nuevosElementos){
        $this->elementos=$nuevosElementos;
        $this->render();
    }

   //funcion devolver lista de menu
   //eliminar menu o elemneto de menu
   //cambiar background color
   //cambiar altura
   //cambiar ancho
   //cambiar tamaño de etra
   //cambiar tipo de letra
   //cambiar color de letra
}
?>