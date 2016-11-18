<?php 

if (isset($empleados) && !empty($empleados)) { 

$check_ok = '<h4><span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green;"> </span></h4>';
$check_no = '<h4><span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red;"> </span></h4>'; 

?>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>Clave implementación</th>
                    <th>Nombre curso</th>
                    <th>Categor&iacute;a</th>
                    <th>Año curso</th>
                    <th>Horas Curso</th>
                    <!--<th>Tipo curso</th>-->
                    <!--<th>Modalidad</th>-->
                    <th>Tutorizado</th>
                    <!--<th>Alcance</th>-->
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                foreach ($empleados as $key => $val) {
                    
                    //$info_curso = json_encode($val);
                    /*echo '<tr>
                    <td >' . $val['cur_clave'] . '</td>
                    <td >' . $val['cur_nom_completo'].'</td > 
                    <td > ' . $val['cat_nom'] . '</td > 
                    <td >' . $val['anio'] . '</td>
                    <td >' . $val['horascur']. '</td > 
                    <td >' . $val['tutorizado']. '</td > 
                    <td>
                        <a href="'.site_url('curso/info/'.$val['cur_id']).'" class="btn btn-info btn-block">
                            <span class="glyphicon glyphicon-search"></span>
                        </a>
                    </td>
                    ';*/

                    echo '<tr>
                    <td >' . $val['cur_clave'] . '</td>
                    <td >' . $val['cur_nom_completo'].'</td > 
                    <td > ' . $val['cat_nom'] . '</td > 
                    <td >' . $val['anio'] . '</td>
                    <td >' . $val['horascur']. '</td > 
                    <td >' . ((isset($val['tutorizado']) && $val['tutorizado'] == '1') ? $check_ok : $check_no) . '</td > 
                    <td>
                        <a href="'.site_url('curso/info_curso/'.$val['cur_id']).'" class="btn btn-info btn-block">
                            <span class="glyphicon glyphicon-search"></span>
                        </a>
                    </td>
                    ';

                    echo '</tr>';
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

<?php } ?>
