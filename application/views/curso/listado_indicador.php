<?php if (isset($registros) && !empty($registros)) { 
//echo form_open('cursoencuesta/guardar_asociacion', array('id'=>'form_asignar', 'class'=>'form-horizontal'));
//pr($empleado);

  ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th rowspan="2">Nombre docente evaluado</th>
                    <th rowspan="2">Rol evaluado</th>
                    <th rowspan="2">Nombre usuario evaluador</th>
                    <th rowspan="2">Rol evaluador</th>
                    <?php $html_head = '';
                    foreach ($indicadores as $key_ind => $indicador) {
                        $html_head .= '<th>'.$indicador['descripcion'].'</th>';
                    } 
                    echo '<th colspan="'.(isset($key_ind) ? ($key_ind+1) : 1).'">Indicadores</th>';
                    ?>
                </tr>
                <?php echo $html_head; ?>
            </thead>
            <tbody>
                <?php
                $registro_temp = array();
                foreach ($registros as $key => $val) { //Refinar datos para presentación
                    //$registro_temp[$val['evaluador']][$val['rol_evaluador']][$val['evaluado']][$val['rol_evaluado']][$val['tipo_indicador_cve']] = $val['porcentaje'];
                    $registro_temp[$val['usu_evaluador']][$val['rol_nombre_evaluador']][$val['usu_evaluado']][$val['rol_nombre_evaluado']][$val['tipo_indicador_cve']] = $val['porcentaje'];
                }
                //pr($registro_temp);
                foreach ($registro_temp as $key_eva => $evaluador) {
                    foreach ($evaluador as $key_rol => $rol_evaluador) {
                        foreach ($rol_evaluador as $key_eval => $evaluado) {
                            foreach ($evaluado as $key_rol_e => $rol_evaluado) {
                                //if($)
                                echo '<tr>
                                <td >'.$key_eval.'</td>
                                <td >'.$key_rol_e.'</td >
                                <td >'.$key_eva.'</td>
                                <td >'.$key_rol.'</td >';
                                //pr($rol_evaluado);
                                foreach ($indicadores as $key_ind => $indicador) {
                                    if(isset($rol_evaluado[$indicador['indicador_cve']])){
                                        echo '<td >'.$rol_evaluado[$indicador['indicador_cve']].'</td >';
                                    } else {
                                        echo '<td ></td >';
                                    }                                    
                                }
                                echo '</tr>';
                            }
                        }
                    }
                }
                ?>
            </tbody>
        </table>
            
    </div>
<?php } else { ?>
    <br><br>
    <div class="row">
        <div class="jumbotron"><div class="container"> <p class="text_center">No se encontraron datos registrados con esta busqueda</p> </div></div>
    </div>

<?php } 

 
?>

<script type="text/javascript">
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $('#btn_export').show();
});
</script>