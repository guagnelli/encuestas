<?php
echo form_open('reporte/index', array('id' => 'form_empleado'));
?>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="panel panel-amarillo">
            <div class="panel-heading clearfix breadcrumbs6">
                <h1 class="panel-title" style="padding-left:20px;">Listado de cursos-encuestas</h1>
            </div>
            <div class="panel-body">
                <div class="row">                    
                    <div class="col-lg-12">
                        <div class="col-lg-3 col-sm-3">
                            <div class="panel-body input-group input-group-sm">
                                <!--<span class="input-group-addon">Delegación:</span>-->
                                <label for="delegacionn">Año del curso</label>
                                <?php echo $this->form_complete->create_element(array('id' => 'anio', 'type' => 'dropdown', 'options' => $anios, 'first' => array('' => 'Seleccione un año'), 'attributes' => array('name' => 'delegacion', 'class' => 'form-control', 'placeholder' => 'Año que impartio curso', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Año en que se impartio curso', 'onchange' => "data_ajax(site_url+'/reporte_general/get_buscar_cursos_encuestas', '#form_empleado', '#listado_resultado_empleado')"))); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <div class="panel-body  input-group input-group-sm">
                                <!--<span class="input-group-addon">Sesiones:</span>-->
                                <label for="categoriaa">Rol del evaluado</label>
                                <?php echo $this->form_complete->create_element(array('id' => 'rol_evaluado', 'type' => 'dropdown', 'options' => $rol, 'first' => array('' => 'Seleccione un rol'), 'attributes' => array('name' => 'categoria', 'class' => 'form-control', 'placeholder' => 'Rol evaluado', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Rol que desempeño como docente', 'onchange' => "data_ajax(site_url+'/reporte_general/get_buscar_cursos_encuestas', '#form_empleado', '#listado_resultado_empleado')"))); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <div class="panel-body  input-group input-group-sm">
                                <!--<span class="input-group-addon">Sesiones:</span>-->
                                <label for="categoriaa">Rol del evaluador</label>
                                <?php echo $this->form_complete->create_element(array('id' => 'rol_evaluador', 'type' => 'dropdown', 'options' => $rol, 'first' => array('' => 'Seleccione un rol'), 'attributes' => array('name' => 'categoria', 'class' => 'form-control', 'placeholder' => 'Rol evaluador', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Rol que desempeño', 'onchange' => "data_ajax(site_url+'/reporte_general/get_buscar_cursos_encuestas', '#form_empleado', '#listado_resultado_empleado')"))); ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <div class="panel-body  input-group input-group-sm">
                                <!--<span class="input-group-addon">Sesiones:</span>-->
                                <label for="categoriaa">Tipo de curso</label>
                                <?php echo $this->form_complete->create_element(array('id' => 'tipo_curso', 'type' => 'dropdown', 'options' => $tipo_curso, 'first' => array('' => 'Seleccione tipo de curso'), 'attributes' => array('name' => 'tipo_curso', 'class' => 'form-control', 'placeholder' => 'Tipo de curso', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Curso tipo tutorizado o no-tutorizado', 'onchange' => "data_ajax(site_url+'/reporte_general/get_buscar_cursos_encuestas', '#form_empleado', '#listado_resultado_empleado')"))); ?>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-4 col-sm-4">
                        <div class="panel-body input-group input-group-sm">
                            <span class="input-group-addon">Número de registros a mostrar:</span>
                            <?php // echo $this->form_complete->create_element(array('id'=>'per_page', 'type'=>'dropdown', 'options'=>array(2=>2,3=>3,4=>4,10=>10, 20=>20, 50=>50, 100=>100), 'attributes'=>array('class'=>'form-control', 'placeholder'=>'Número de registros a mostrar', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'Número de registros a mostrar', 'onchange'=>"data_ajax(site_url+'/bonos_titular/get_buscar_cursos_encuestas', '#form_buscador', '#listado_resultado_empleado')"))); ?>
                            <?php echo $this->form_complete->create_element(array('id' => 'per_page', 'type' => 'dropdown', 'options' => array(5 => 5, 10 => 10, 20 => 20, 50 => 50, 100 => 100), 'attributes' => array('class' => 'form-control', 'placeholder' => 'Número de registros a mostrar', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Número de registros a mostrar', 'onchange' => "data_ajax(site_url+'/reporte_general/get_buscar_cursos_encuestas', '#form_empleado', '#listado_resultado_empleado')"))); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4">
                        <div class="panel-body input-group input-group-sm">
                            <span class="input-group-addon">Ordenar por:</span>
                            <?php echo $this->form_complete->create_element(array('id' => 'order', 'type' => 'dropdown', 'options' => $ordenar_por, 'attributes' => array('class' => 'form-control', 'placeholder' => 'Ordernar por', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Ordenar por', 'onchange' => "data_ajax(site_url+'/reporte_general/get_buscar_cursos_encuestas', '#form_empleado', '#listado_resultado_empleado')"))); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4">
                        <div class="panel-body input-group input-group-sm">
                            <span class="input-group-addon">Tipo de orden:</span>
                            <?php echo $this->form_complete->create_element(array('id' => 'order_type', 'type' => 'dropdown', 'options' => array('ASC' => 'Ascendente', 'DESC' => 'Descendente'), 'attributes' => array('class' => 'form-control', 'placeholder' => 'Ordernar por', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Ordenar por', 'onchange' => "data_ajax(site_url+'/reporte_general/get_buscar_cursos_encuestas', '#form_empleado', '#listado_resultado_empleado')"))); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="panel-body input-group">
                            <input type="hidden" id="menu_select" name="menu_busqueda" value="unidad">
                            <div class="input-group-btn">
                                <button id="btn_buscar_por" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-default dropdown-toggle " data-toggle="tooltip" data-original-title="Buscar por">Clave <span class="caret"> </span></button>
                                <ul id="ul_menu_buscar_por" data-seleccionado='unidad' class="dropdown-menu borderlist">
                                    <li class="lip" onclick="funcion_menu_tipo_busqueda('clavecurso')">Clave</li>
                                    <li class="lip" onclick="funcion_menu_tipo_busqueda('nombrecurso')">Nombre del curso</li>
                                </ul>

                            </div>

                            <?php
                            echo $this->form_complete->create_element(
                                    array('id' => 'buscar_unidad_medica', 'type' => 'text',
                                        'value' => '',
                                        'attributes' => array(
                                            'placeholder' => 'Buscar',
                                            'data-toggle' => 'tooltip',
                                            'data-placement' => 'bottom',
                                            'class' => 'form-control',
                                            'onkeypress' => 'return runScript(event);', //control key del enter para buscar
                                            'title' => 'Buscar',
//                                        'readonly'=>'readonly',
                                        )
                                    )
                            );
                            ?>
                            <div class="input-group-btn" >
                                <button type="button" id="btn_buscar_b" aria-expanded="false" class="btn btn-default browse" title="Buscar" data-toggle="tooltip" onclick="data_ajax(site_url+'/reporte_general/get_buscar_cursos_encuestas', '#form_empleado', '#listado_resultado_empleado')" ><span aria-hidden="true" class="glyphicon glyphicon-search"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="listado_resultado_empleado">

                </div>



            </div>  <!-- /panel-body-->
        </div> <!-- /panel panel-amarillo-->
    </div> <!-- /col 12-->
</div>
<div class="row"></div>    



<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        data_ajax(site_url + "/reporte_general/get_buscar_cursos_encuestas", "#form_empleado", "#listado_resultado_empleado");
        $("#btn_submit").click(function (event) {
            data_ajax(site_url + "/reporte_general/get_buscar_cursos_encuestas", "#form_empleado", "#listado_resultado_empleado");
            event.preventDefault();
        });
    });


</script>