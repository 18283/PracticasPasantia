
<?php

class BarraNavegacion {
    private $elementos;

    public function __construct($elementos) {
        $this->elementos = $elementos;
    }

    public function render() {
		echo ' <link href="../css/bootstrap.css" rel="stylesheet">
     <link href="../css/reboost.css" rel="stylesheet" type="text/css">
    <link href="../css/base.css" rel="stylesheet">
    <link href="../css/stiloUser" rel="stylesheet"> 
    <link href="../css/estilo.css" rel="stylesheet" type="text/css">';
        echo '<nav class="navbar navbar-expand-lg my-4 py-4 navbar-light shadow-sm" style="background-color:#b19146;">
        <div class="container-fluid">
            <a class="navbar-left" href="#"><img src="../Recursos/logo1.png" alt="logo" width="250px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
								<div class="collapse navbar-collapse" id="navbarSupportedContent">';
							echo ' <ul class="navbar-nav me-auto mb-2 mb-lg-0">';
							foreach ($this->elementos as $texto => $url) {
								echo '<li  style="margin-right:20px;"><a class="nav-link active text-white h1" href="' . htmlspecialchars($url) . '">' . htmlspecialchars($texto) . '</a></li>';
							}
							echo '</ul>';
							echo '</div>';
		echo '</div>';
        echo '</nav>';
    }
	
}
?>