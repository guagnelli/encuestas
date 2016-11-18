
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="panel panel-amarillo">
            <div class="panel-body">
                <a href="<?php echo site_url('encuestas/edit/'.$encuesta_cve); ?>" class="btn btn-info pull-right">
                	<span class="glyphicon glyphicon-list" aria-hidden="true"> </span> Regresar a preguntas 
                </a>
            	<br><br>
				<?php
				$activa_padre = "";

				$campos_pregunta = array(
					'encuesta_cve',
					'seccion_cve',
					'tipo_pregunta_cve',
					'pregunta',
					'obligada',
					'orden',
					'is_bono',
					'val_ref',
					'pregunta_padre',
					'reactivos_cve',
					'texto',
					'ponderacion'
				);
				//pr($pregunta);

				if (isset($pregunta) && !empty($pregunta)) {
					//pr($pregunta);
					echo form_open('encuestas/edita_pregunta/'.$preguntas_cve.'/'.$encuesta_cve, array('id'=>'edita_pregunta')); 
					if ($this->session->flashdata('success') == TRUE)
			        { 
			            echo html_message($this->session->flashdata('success'), 'success'); 
			        }
				?>

				<div class="list-group">
					<div class="list-group-item">
						<h3>Editar pregunta</h3>
					</div>
					<div class="list-group-item">
						<label for="seccion_cve">Nombre de sección:</label>
						<?php 
						echo $this->form_complete->create_element(array('id' => 'seccion_cve', 'type' => 'dropdown', 'options' => $secciones,'value'=>$pregunta[0]['seccion_cve'], 'first' => array('' => 'Seleccione una sección'), 'attributes' => array('name' => 'seccion_cve', 'class' => 'form-control', 'placeholder' => 'Sección pregunta', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Sección pregunta'))); 
						?>
						<?php echo form_error_format('seccion_cve');?>
						<p class="help-block"></p>
					</div>

					<div class="list-group-item">
						<label for="seccion_cve">Nombre de indicador:</label>
						<?php 
						echo $this->form_complete->create_element(array('id' => 'tipo_indicador_cve', 'type' => 'dropdown', 'options' => $indicadores,'value'=>$pregunta[0]['tipo_indicador_cve'], 'first' => array('' => 'Seleccione indicador'), 'attributes' => array('name' => 'tipo_indicador_cve', 'class' => 'form-control', 'placeholder' => 'Indicador de pregunta', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Indicador de pregunta'))); 
						?>
						<?php echo form_error_format('tipo_indicador_cve');?>
						<p class="help-block"></p>
					</div>						
					<div class="list-group-item">
						<label for="pregunta">Pregunta:</label>
						<?php
						echo $this->form_complete->create_element(array('id'=>'pregunta', 'type'=>'textarea', 'value'=>$pregunta[0]['pregunta'], 'attributes'=>array('rows'=>3, 'class'=>'form-control', 'placeholder'=>'pregunta', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'title'=>'pregunta')));
						?>
						<?php echo form_error_format('pregunta');?>
						<p class="help-block"></p>
					</div>
					<div class="list-group-item">
						<div class="col-lg-4 col-md-4 col-sm-4">
						<label>
							<?php

							$check_bono = (isset($pregunta[0]['is_bono']) && $pregunta[0]['is_bono']==1) ? 'checked': '';
							echo $this->form_complete->create_element(array('id' => 'is_bono', 'type' => 'checkbox', 'value'=>1, 'attributes' => array('name' => 'is_bono', 'checked'=>$check_bono))); 
							?>
							Aplica para bono	
							</label>
							<?php echo form_error_format('is_bono');?> 
						</div><div class="col-lg-4 col-md-4 col-sm-4">
							<label>
							<?php
							$check_obligada = (isset($pregunta[0]['obligada']) && $pregunta[0]['obligada']==1) ? 'checked': '';
							echo $this->form_complete->create_element(array('id' => 'obligada', 'type' => 'checkbox', 'value'=>1, 'attributes' => array('name' => 'obligada', 'checked'=>$check_obligada))); 
							?>
							Obligada	
							</label>
							<?php echo form_error_format('obligada');?>
						</div>
						<br>
						<p class="help-block"></p>
					</div>
					<div class="list-group-item">
						<label for="tipo_pregunta_cve">Tipo de respuesta:</label><br>
						<br>
						<label>
							<?php 

							$check_null;

							if(isset($pregunta[0]['tipo_pregunta_cve']) && ($pregunta[0]['tipo_pregunta_cve']==2 OR $pregunta[0]['tipo_pregunta_cve']==4 OR $pregunta[0]['tipo_pregunta_cve']==6))
							 {
							 	$check_null=1;
							 }
							 elseif (isset($pregunta[0]['tipo_pregunta_cve']) && $pregunta[0]['tipo_pregunta_cve']==8) 
							 {
							  	$check_null=7;
							 }
							 else{ $check_null=0;} 

							$tiponoobligatoria=array(1 => 'No aplica',7 => 'No envió mensaje');
							//echo "Respuesta tipo no obligatoria";
							echo $this->form_complete->create_element(array('id' => 'no_obligatoria', 'type' => 'dropdown', 'options' => $tiponoobligatoria,'value'=>$check_null, 'first' => array('' => 'Seleccione'), 'attributes' => array('name' => 'no_obligatoria', 'class' => 'form-control', 'placeholder' => 'Respuesta', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Respuesta'))); 



							//$check_null = (isset($pregunta[0]['tipo_pregunta_cve']) && ($pregunta[0]['tipo_pregunta_cve']==2 OR $pregunta[0]['tipo_pregunta_cve']==4 OR $pregunta[0]['tipo_pregunta_cve']==6)) ? 'checked': '';

							//echo $this->form_complete->create_element(array('id' => 'no_obligatoria', 'type' => 'radio', 'value'=>1, 'attributes' => array('name' => 'no_obligatoria', 'checked'=>$check_null))); 

							?>
						<!--Sin selección-->
						</label>		
						<label>
							<?php 
							/*
							$check_null = (isset($pregunta[0]['tipo_pregunta_cve']) && ($pregunta[0]['tipo_pregunta_cve']==2 OR $pregunta[0]['tipo_pregunta_cve']==4 OR $pregunta[0]['tipo_pregunta_cve']==6)) ? 'checked': '';

							echo $this->form_complete->create_element(array('id' => 'no_obligatoria', 'type' => 'radio', 'value'=>1, 'attributes' => array('name' => 'no_obligatoria', 'checked'=>$check_null))); 
                           */
							?>
						 <!--No aplica-->
						</label>

						<label>
							<?php 
							/*$check_null = (isset($pregunta[0]['tipo_pregunta_cve']) && ($pregunta[0]['tipo_pregunta_cve']==2 OR $pregunta[0]['tipo_pregunta_cve']==4 OR $pregunta[0]['tipo_pregunta_cve']==6)) ? 'checked': '';

							echo $this->form_complete->create_element(array('id' => 'no_obligatoria', 'type' => 'radio', 'value'=>7, 'attributes' => array('name' => 'no_obligatoria', 'checked'=>$check_null))); 
*/
							?>
						 <!--No envió mensaje-->
						</label><br>
						<label>
							<?php

							$check_sino = (isset($pregunta[0]['tipo_pregunta_cve']) && ($pregunta[0]['tipo_pregunta_cve']==1 OR $pregunta[0]['tipo_pregunta_cve']==2)) ? 'checked': '';

							echo $this->form_complete->create_element(array('id'=>'tipo_pregunta_1', 'type'=>'radio', 'value'=>1, 'attributes'=>array('name'=>'tipo_pregunta_radio', 'checked'=>$check_sino)));
							?>
							[Si / No]
						</label><br>		
						<label>
							<?php

							$check_siemprenunca = (isset($pregunta[0]['tipo_pregunta_cve']) && ($pregunta[0]['tipo_pregunta_cve']==3 OR $pregunta[0]['tipo_pregunta_cve']==4)) ? 'checked': '';

							echo $this->form_complete->create_element(array('id'=>'tipo_pregunta_2', 'type'=>'radio', 'value'=>2, 'attributes'=>array('name'=>'tipo_pregunta_radio', 'checked'=>$check_siemprenunca)));
							?>
							[Siempre, Casi siempre, Algunas veces, Casi nunca, Nunca]
						</label><br>
						<label>
							<?php

							$check_abierta = (isset($pregunta[0]['tipo_pregunta_cve']) && ($pregunta[0]['tipo_pregunta_cve']==5 OR $pregunta[0]['tipo_pregunta_cve']==6)) ? 'checked': '';

							echo $this->form_complete->create_element(array('id'=>'tipo_pregunta_3', 'type'=>'radio', 'value'=>3, 'attributes'=>array('name'=>'tipo_pregunta_radio', 'checked'=>$check_abierta)));
							?>
							[Respuesta abierta]
						</label><br>


						
						<?php echo form_error_format('no_obligatoria');?>
						<?php echo form_error_format('tipo_pregunta_radio');?>
						<p class="help-block"></p>
					</div>


				    <div class="list-group-item" style='display:none;' id='validonap'>
						<label for="seccion_cve">Valido no aplica:</label><br>
					    <label>
						<input type="radio" name="valido_no_aplica" id="valido_no_aplica" value="1" <?php 
						    echo set_value('valido_no_aplica', $pregunta[0]['valido_no_aplica']) == 1 ? "checked" : ""; 
						?> />Si

						<input type="radio" name="valido_no_aplica" id="valido_no_aplica" value="0" <?php 
						    echo set_value('valido_no_aplica', $pregunta[0]['valido_no_aplica']) == 0 ? "checked" : ""; 
						?> />No

                        </label><br>
					<br>
						<p class="help-block"></p>
					</div>
				    <?php  /*<div class="list-group-item">
				        <?php
				        $check_padre = (isset($pregunta[0]['pregunta_padre']) && !empty($pregunta[0]['pregunta_padre'])) ? 'checked': '';
						$pregunta_padre = (isset($pregunta[0]['pregunta_padre']) && empty($pregunta[0]['pregunta_padre'])) ? $pregunta[0]['pregunta_padre'] : 0;
						$val_ref = (isset($pregunta[0]['val_ref']) && empty($pregunta[0]['val_ref'])) ? $pregunta[0]['val_ref'] : 0;

						if ($pregunta_padre != 0) {
						$activa_padre = "data_ajax(site_url+'/encuestas/get_respuesta_esperada_ajax/".$val_ref."', '#edita_pregunta', '#respuesta_esperada');";
						}
						?>
				        <label data-toggle="collapse" data-target="#div_pregunta_padre" aria-expanded="false" aria-controls="collapseExample">		
						
						<?php 
						echo $this->form_complete->create_element(array('id' => 'tiene_pregunta_padre', 'type' => 'checkbox', 'value'=>1, 'attributes' => array('name' => 'tiene_pregunta_padre', 'checked'=>$check_padre))); 
						?>
						Tiene pregunta padre
						</label><br>
				        <div class="collapse" id="div_pregunta_padre">
				            <div class="well">
				                <div class="col-lg-6 col-md-6 col-sm-6">
				                    <label for="pregunta_padre">Pregunta padre:</label>
				                    <?php 
				                    echo $this->form_complete->create_element(array('id' => 'pregunta_padre', 'type' => 'dropdown', 'options' => $preguntas_padre,'value'=>$pregunta_padre, 'first' => array('' => 'Seleccione una pregunta'), 'attributes' => array('name' => 'pregunta_padre', 'class' => 'form-control', 'placeholder' => 'Pregunta padre', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Pregunta padre','onchange' => "data_ajax(site_url+'/encuestas/get_respuesta_esperada_ajax/".$val_ref."', '#edita_pregunta', '#respuesta_esperada')"))); 
				                    ?>
				                    <span class="text-danger"> <?php echo form_error('pregunta_padre','','');?> </span>
				                    <p class="help-block"></p>
				                </div>
				                <div class="col-lg-6 col-md-6 col-sm-6">
				                    <div id="respuesta_esperada">
				                        <label for="val_ref">Respuesta esperada:</label>
				                        <?php 
				                        echo $this->form_complete->create_element(array('id' => 'val_ref', 'type' => 'dropdown', 'options' => '','value'=>'', 'first' => array('' => 'Seleccione una respuesta'), 'attributes' => array('name' => 'val_ref', 'class' => 'form-control', 'placeholder' => 'Respuesta esperada', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Respuesta esperada'))); 
				                        ?>
				                        <span class="text-danger"> <?php echo form_error('val_ref','','');?> </span>
				                        <p class="help-block"></p>
				                    </div>
				                </div>
				                <div class="clearfix"></div>
				            </div>
				        </div>
						<span class="text-danger"> <?php echo form_error('tiene_pregunta_padre','','');?> </span>
						<p class="help-block"></p>
					</div> */
					?>					
					<div class="list-group-item">
						<?php
						echo $this->form_complete->create_element(array('id'=>'btn_submit', 'type'=>'submit', 'value'=>'Guardar pregunta', 'attributes'=>array('class'=>'btn btn-success btn-block espacio')));
						?>
					</div>
					<div class="list-group-item">
					</div>
				</div>

				<?php 	
					echo form_close(); 		

				}else{ 

				?>
	            <br><br>
	            <div class="row">
	                <div class="jumbotron"><div class="container"> <p class="text_center">No se encontraron datos registrados en esta busqueda</p> </div></div>
	            </div>

				<?php 

				} 

				?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
    	if ($('#tiene_pregunta_padre').is(':checked')) {
    		$('#div_pregunta_padre').collapse('show');
    	}
    	<?php echo $activa_padre; ?>

     var op = $("#no_obligatoria option:selected").val();
     if(op == 7)
	    {
	    	$("input[id=tipo_pregunta_2]").attr("disabled", true);
	    	$("input[id=tipo_pregunta_3]").attr("disabled", true);
	    	$("input[id=tipo_pregunta_1]").attr("checked", true);
	    	$('#validonap').hide();
	    } 
	 else if(op == 1)
	    {
	    	$("input[id=tipo_pregunta_2]").attr("disabled", false);
	    	$("input[id=tipo_pregunta_3]").attr("disabled", false);
	    	$("input[id=tipo_pregunta_1]").attr("checked", true);
	    	$('#validonap').show();

	    }
	 else
	 {
	 	$("input[id=tipo_pregunta_2]").attr("disabled", false);
	    $("input[id=tipo_pregunta_3]").attr("disabled", false);
	    $("input[id=tipo_pregunta_1]").attr("checked", true);
	    $('#validonap').hide();

	 }   	

    $("#no_obligatoria").change(function(){
        var op = $("#no_obligatoria option:selected").val();
	    if(op == 7)
	    {
	    	$("input[id=tipo_pregunta_2]").attr("disabled", true);
	    	$("input[id=tipo_pregunta_3]").attr("disabled", true);
	    	$("input[id=tipo_pregunta_1]").attr("checked", true);
	    	$('#validonap').hide();
	    } 
	 else if(op == 1)
	    {
	    	$("input[id=tipo_pregunta_2]").attr("disabled", false);
	    	$("input[id=tipo_pregunta_3]").attr("disabled", false);
	    	$("input[id=tipo_pregunta_1]").attr("checked", true);
	    	$('#validonap').show();

	    }
	 else
	 {
	 	$("input[id=tipo_pregunta_2]").attr("disabled", false);
	    $("input[id=tipo_pregunta_3]").attr("disabled", false);
	    $("input[id=tipo_pregunta_1]").attr("checked", true);
	    $('#validonap').hide();

	 }

    });


    });
</script>