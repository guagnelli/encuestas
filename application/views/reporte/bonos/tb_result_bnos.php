<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//pr($reglas_evaluacion);
//pr($result);
//pr($result_promedio);
?>
<div id="div_tabla_reporte_general_encuestas" style="overflow-x: auto; width: 1200px;">
    <!--Mostrará la tabla de actividad docente --> 
    <table class="table table-striped table-hover table-bordered " id="tabla_reporte_gral_encuestas">
        <thead>
            <tr class="bg-info">
                <th>Clave curso</th>
                <th>Curso</th>
                <th>Tipo implementación</th>
                <th>Tipo</th>
                <th>Bloque</th>
                <th>Grupo</th>
                <th>Matricula</th>
                <th>Nombre evaluado</th>    
                <th>Rol evaluado</th>
                <th>Región</th>
                <th>Delegación</th>
                <th>Clave de Adscripción</th>
                <?php foreach ($reglas_evaluacion as $val) { ?>
                    <th><?php echo $val; ?></th>
                <?php } ?>
<!--<th>Opciones</th>-->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $key => $val) { ?>

                <?php
                $evaluado = (isset($val['name_rol_evaluado'])) ? $val['name_rol_evaluado'] : '--';
                $bloque = (isset($val['bloque'])) ? $val['bloque'] : '--';
                $grupo = (isset($val['mdl_groups_cve'])) ? $val['mdl_groups_cve'] : '--';
                echo "<tr id='id_row_" . $key . "' data-keyrow=" . $key . ">";
                echo "<td>" . $val['clave'] . "</td>";
                echo "<td>" . $val['namec'] . "</td>";
                echo "<td>" . $val['tipo_curso'] . "</td>";
                echo "<td>" . $val['tex_tutorizado'] . "</td>";
                echo "<td>" . $bloque . "</td>";
                echo "<td>" . $grupo . "</td>";
                echo "<td>" . $val['username'] . "</td>";
                echo "<td>" . $val['name_evaluado'] . "</td>";
                echo "<td>" . $evaluado . "</td>";
                echo "<td>" . $val['name_region'] . "</td>";
                echo "<td>" . $val['cve_depto_adscripcion'] . "</td>";
                echo "<td>" . $val['nom_delegacion'] . "</td>";
                foreach ($reglas_evaluacion as $key_rol => $vp) {
                    if (isset($result_promedio[$val['course_cve']][$val['evaluado_user_cve']][$val['tutorizado']][$key_rol])) {
                        echo "<td>" . $result_promedio[$val['course_cve']][$val['evaluado_user_cve']][$val['tutorizado']][$key_rol]['promedio'] . " %</td>";
                    } else {
//                        echo "<td>".$val['course_cve']. ', '. $val['evaluado_user_cve'] . ', ' . $val['tutorizado'] . ', ' .$key_rol ."</td>";
                        echo "<td> </td>";
                    }
                }
                echo "<tr>";
                ?>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

