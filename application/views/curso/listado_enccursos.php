<?php if (isset($empleados) && !empty($empleados)) { 

        if(exist_and_not_null($error))
        {
            echo '<div class="row">
                        <div class="col-md-10 col-sm-10 col-xs-10 alert alert-danger">
                        '.$error.'
                        </div>
                    </div>';
                
        }

        $this->config->load('general');
        $tipo_msg = $this->config->item('alert_msg');
        
        if ($this->session->flashdata('success') == TRUE)
        { 
            echo '<br><br><br>'.html_message($this->session->flashdata('success'), $tipo_msg['SUCCESS']['class']); 
        
        }
        if ($this->session->flashdata('danger') == TRUE)
        { 
            echo '<br><br><br>'.html_message($this->session->flashdata('danger'), $tipo_msg['DANGER']['class']); 
        
        }
        
//echo form_open('cursoencuesta/guardar_asociacion', array('id'=>'form_asignar', 'class'=>'form-horizontal'));

$check_ok = '<span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green;"> </span>';
$check_no = '<span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red;"> </span>'; 

  ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>Folio instrumento</th>
                    <th>Nombre instrumento</th>
                    <th>Rol evaluador</th>
                    <th>Rol evaluado</th>
                    <th>Tutorizado</th>
                    <th>Bono</th>  
                    <th>Asignado</th> 
                    <th>Estatus</th>                   
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                foreach ($empleados as $key => $val) {
                     $desactivar = '<a onclick="desasociar_encuesta('.$val['encuesta_cve'].');" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Desasociar instrumento">
                                        <span class="glyphicon glyphicon-off"></span>
                                    </a>';
                    echo '<tr>
                    <td > <input type="checkbox" name="encuestacve[]"  id="encuestacve[]" value="'.$val['encuesta'].'">
                    </td>
                    <td >' . $val['encuestaclavecorta'] . '</td>
                    <td >' . $val['descrip'].'</td > 
                    <td > ' . $val['evaluador'] . '</td > 
                    <td >' . $val['evaluado'] . '</td>
                    <td ><h4>' . (($val['tutorizado'] == 1 ) ? $check_ok : $check_no) . '</td >
                    <td> <h4>' . (($val['bono']==1) ? $check_ok : $check_no).'</h4> </td >
                    <td> <h4>' . (($val['asig']==1) ? $check_ok : $check_no).'</h4> </td >
                    <td > <h4>' . (($val['estatus'] == 1 ) ? $check_ok : $check_no) . '</td >
             
                    <td><a data-toggle="modal" data-target="#modal_censo" onclick="data_ajax(\''.site_url('modal/mod_encuestas/'.$val['encuesta']).'\', \'null\', \'#modal_censo\');" class="btn btn-info btn-block">  <span class="glyphicon glyphicon-search"></span></a>
                    '.((isset($val['asig']) && $val['asig']== True) ? $desactivar : '').'
                    </td>
                    ';



                    echo '</tr>';
                }
                
                ?>
            </tbody>
        </table>
        <input type="hidden" id="curso" name="curso" value="<?php echo $curso ?>">
            
           <div class="form-group text-center">
                            <?php
                            /*echo $this->form_complete->create_element(array(
                                'id' => 'btn_submit',
                                'type' => 'button',
                                'value' => 'Asociar',
                                'attributes' => array(
                                    'class' => 'btn btn-primary'
                                    ),
                                'onclick'=>"data_ajax(site_url+'/cursoencuesta/get_data_ajax/'+".$curso.", '#form_asignar', '#listado_resultado')"
                                ));*/

           /*echo $this->form_complete->create_element(array('id'=>'btn_submit', 
                          'type'=>'button',  'value' => 'Asociar'
                         
                         ));*/


          /* echo $this->form_complete->create_element(array(
                                'id' => 'btn_submit',
                                'type' => 'button',
                                'value' => 'Asociar',
                                'attributes' => array(
                                    'class' => 'btn btn-primary',
                                    'onclick'=>"data_ajax(site_url+'/cursoencuesta/get_data_ajax/'+".$curso.", '#form_asignar', '#listado_resultado')"
                                    ),
                                
                                ));*/

           //echo form_close();
                            ?>
        </div>
            
 
        
    </div>
<?php } else { ?>
    <br><br>
    <div class="row">
        <div class="jumbotron"><div class="container"> <p class="text_center">No se encontraron datos registrados con esta busqueda</p> </div></div>
    </div>

<?php } 

 
?>
<script type="text/javascript">
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

</script>