<?php
$logueado = $this->session->userdata('logueado');
$nombre =  $this->session->userdata("nombre");  //Tipo de usuario almacenado en sesión

if (isset($logueado) && !empty($logueado)) { 
    ?>
    <nav id="nav-sied" class="top-bar">
        <div class="top-bar-section">
            <ul>
                <li>
                    <a href="<?php echo site_url('encuestas/index'); ?>" class="a_nav_sied" >
                        Encuestas
                    </a>
                </li>
                <li>
                    <a href="<?php echo site_url('curso'); ?>" class="a_nav_sied" >
                        Implementaciones
                    </a>
                </li>

                    <li>
                        <a href="#" class="a_nav_sied">Reportes
                            <i class="glyphicon glyphicon-chevron-down"></i></a>
                        <ul class="a_nav_sied">
                            <li>
                                <a href="<?php echo site_url('reporte'); ?>" class="a_nav_sied">Reporte resumen de bonos</a>
                            </li>
                           
                        </ul>

                           <ul class="a_nav_sied">
                            <li>
                                <a href="<?php echo site_url('registro/registrosagenda'); ?>" class="a_nav_sied">Reporte general por curso</a>
                            </li>
                           
                        </ul>
                    </li>
                <!--<li>
                    <a href="<?php echo site_url('reporte'); ?>" class="a_nav_sied" >
                        Reporte
                    </a>
                </li>-->
                <li>
                    <a href="<?php echo site_url('login/regresar_sied'); ?>" class="a_nav_sied" >
                        Regresar a SIED
                    </a>
                </li>
                <!--<li>
                    <a href="<?php //echo site_url('dashboard'); ?>" class="a_nav_sied" >
                    Inicio
                    </a>
                </li>-->        
            </ul>
            <ul class="navbar-right">
                <li>
                    <a href="<?php echo site_url('login/cerrar_session'); ?>" class="a_nav_sied" >
                        <span class="glyphicon glyphicon-log-out"></span>
                        Cerrar sesión
                    </a>
                </li>            
            </ul>     
        </div>
    </nav>
    <div style="text-align:right"><small><?php echo $nombre; ?> | </small></div>
    <div class="clearfix"></div>
<?php

} 


