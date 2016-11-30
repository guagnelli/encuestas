<fieldset class="scheduler-border well">
    <legend class="scheduler-border">Curso</legend>
    <?php
    if (isset($buscar_instrumento)) {
        $boton_buscar = 1;
        ?>
        <div class="col-lg-12 col-sm-12">
            <div class="panel-body input-group">
                <input type="hidden" id="menu_instrumento" name="tipo_buscar_instrumento" value="clavecurso">
                <div class="input-group-btn">
                    <button id="btn_tipo_buscar_instrumento" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-default dropdown-toggle " data-toggle="tooltip" data-original-title="Buscar por"><?php echo $buscar_instrumento['clavecurso']; ?><span class="caret"> </span></button>
                    <ul id="ul_menu_buscar_por_i" data-seleccionado='clavecurso' class="dropdown-menu borderlist">
                        <?php foreach ($buscar_instrumento as $key => $value) { ?>
                            <li class="lip" onclick="funcion_menu_tipo_busqueda('<?php echo $key; ?>')"><?php echo $value; ?></li>
                        <?php } ?>
                    </ul>

                </div>

                <?php
                echo $this->form_complete->create_element(
                        array('id' => 'text_buscar_instrumento', 'type' => 'text',
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
            </div>
        </div>
    <?php } ?>
    <div class="col-lg-12 col-sm-12">
        <div class="panel-body input-group input-group-sm">
            <!--<span class="input-group-addon">Delegación:</span>-->
            <label for="anio_curso">Año de la implementación</label>
            <?php echo $this->form_complete->create_element(array('id' => 'anio', 'type' => 'dropdown', 'options' => $anios, 'first' => array('' => 'Seleccione un año'), 'attributes' => array('name' => 'anio', 'class' => 'form-control', 'placeholder' => 'Año que impartio curso', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Año en que se impartio curso', 'onchange' => "data_ajax($url_control)"))); ?>
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="panel-body  input-group input-group-sm">
            <!--<span class="input-group-addon">Sesiones:</span>-->
            <label for="tipo_curso">Tipo de implementación</label>
            <?php echo $this->form_complete->create_element(array('id' => 'tipo_implementacion', 'type' => 'dropdown', 'options' => $tipo_implementacion, 'first' => array('' => 'Seleccione tipo de implementación'), 'attributes' => array('name' => 'tipo_curso', 'class' => 'form-control', 'placeholder' => 'Curso tipo tutorizado o no-tutorizado', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Curso tipo tutorizado o no-tutorizado', 'onchange' => "data_ajax($url_control)"))); ?>
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="panel-body  input-group input-group-sm">
            <!--<span class="input-group-addon">Sesiones:</span>-->
            <label for="tipo_curso">Aplica para bono</label>
            <?php echo $this->form_complete->create_element(array('id' => 'is_bono', 'type' => 'dropdown', 'options' => array('1' => 'Si', '0' => 'No'), 'first' => array('' => 'Seleccione si aplica para bono'), 'attributes' => array('name' => 'tipo_curso', 'class' => 'form-control', 'placeholder' => 'Aplica para bono', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Aplica para bono', 'onchange' => "data_ajax($url_control)"))); ?>
        </div>
    </div>
</fieldset>