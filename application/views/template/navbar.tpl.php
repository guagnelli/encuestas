<?php
$logueado = $this->session->userdata('logueado');
$nombre = $this->session->userdata("nombre");  //Tipo de usuario almacenado en sesiÃ³n

if (isset($logueado) && !empty($logueado)) {
    ?>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header right">
                <a class="navbar-brand" href="<?php echo site_url('login/cerrar_session'); ?>">Cerrar sesión
                    <span class="glyphicon glyphicon-log-out"></span></a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="<?php echo site_url('encuestas/index'); ?>">Encuestas</a></li>
                <li><a href="<?php echo site_url('curso'); ?>" class="a_nav_sied" >Implementaciones</a>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Reportes
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo site_url('reporte_general'); ?>" class="a_menu">Reporte general</a></li>
                        <li><a href="<?php echo site_url('reporte'); ?>" class="a_menu">Reporte resumen de bonos</a></li>
                        <li><a href="<?php echo site_url('reporte_bonos'); ?>" class="a_menu">Reporte de implementación</a></li>
                        <li><a href="<?php echo site_url('reporte_detallado'); ?>" class="a_menu">Reporte detalle de encuestas</a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo site_url('login/regresar_sied'); ?>" class="a_nav_sied">
                        Regresar a SIED
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div style="text-align:right"><small><?php echo $nombre; ?> | </small></div>
    <div class="clearfix"></div>
    <?php
} 


