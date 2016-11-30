<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//pr($lista_unidades);
?>
<div id="div_tabla_reporte_general_encuestas table-responsive" style="overflow-x: auto; width: 1200px;">
    <!--Mostrará la tabla de actividad docente --> 
    <table class="table table-striped table-hover table-bordered " id="tabla_reporte_gral_encuestas">
        <thead>
            <tr class="bg-info">
                <th>Encuesta</th>
                <th>Tipo encuesta</th>
                <th>Bonos</th>
                <th>Tutorizado</th>
                <th>Curso</th>
                <th>Tipo de curso</th>                
                <th>Año</th>
                <th>Fecha inicio</th>
                <th>Fecha fin</th>
                <th>Grupo</th>
                <th>Regla evaluación</th>
                <th>Matrícula evaluado</th>
                <th>Nombre evaluado</th>
                <th>Rol evaluado</th>
                <th>Delegación evaluado</th>
                <th>Adscripción evaluado</th>
                <th>Categoría evaluado</th>
                <th>Matrícula evaluador</th>
                <th>Nombre evaluador</th>
                <th>Rol evaluador</th>
                <th>Delegación evaluador</th>
                <th>Adscripción evaluador</th>
                <th>Categoría evaluador</th>
                <?php //pr($preguntas); 
                foreach ($preguntas as $key_p => $pregunta) {
                    echo '<th>'.$pregunta['pregunta'].'</th>';
                } ?>
                <th>Porcentaje de evaluador</th>
            </tr>
        </thead>
        <tbody>
            <?php //pr($datos); 
            //pr($respuestas);
            foreach ($datos as $key_d => $dato) {
                $depto_evaluado = ((!empty($dato['depto_tut_nombre'])) ? $dato['depto_tut_nombre'] : $dato['depto_user_nombre']);
                $depto_rama_evaluado = ((!empty($dato['depto_tut_nombre'])) ? $dato['depto_tut_nombre'] : $dato['depto_user_nombre']);
                echo '<tr>
                        <td>'.$dato['descripcion_encuestas'].'</td>
                        <td>'.($this->config->item('TIPO_INSTRUMENTOV')[$dato['tipo_encuesta']]).'</td>
                        <td>'.(($dato['is_bono']==1) ? 'Bonos' : ' - ').'</td>
                        <td>'.$dato['tex_tutorizado'].'</td>
                        <td>'.$dato['namec'].' ('.$dato['curso_clave'].')</td>
                        <td>'.$dato['tipo_curso'].'</td>
                        <td>'.$dato['anio'].'</td>
                        <td>'.$dato['fecha_inicio'].'</td>
                        <td>'.$dato['fecha_fin'].'</td>
                        <td>'.$dato['grupo_nombre'].'</td>
                        <td>'.$dato['evaluador_rol_nombre'].' a '.$dato['evaluado_rol_nombre'].' - '.$dato['tex_tutorizado'].'</td>
                        <td>'.$dato['evaluado_matricula'].'</td>
                        <td>'.$dato['evaluado_nombre'].' '.$dato['evaluado_apellido'].'</td>
                        <td>'.$dato['evaluado_rol_nombre'].'</td>
                        <td>'.((!empty($dato['depto_tut_nom_del'])) ? $dato['depto_tut_nom_del'] : $dato['depto_user_nom_del']).'</td>
                        <td>'.$depto_evaluado.'</td>
                        <td>'.((!empty($dato['evaluado_cat_tut_nom'])) ? $dato['evaluado_cat_tut_nom'] : $dato['evaluado_cat_user_nom']).'</td>
                        <td>'.$dato['evaluador_matricula'].'</td>
                        <td>'.$dato['evaluador_nombre'].' '.$dato['evaluador_apellido'].'</td>
                        <td>'.$dato['evaluador_rol_nombre'].'</td>';
                if($dato['evaluador_rol_id']==$this->config->item('ENCUESTAS_ROL_EVALUADOR')['ALUMNO']){
                    echo '<td>'.((!empty($dato['depto_e_pre_nom_del'])) ? $dato['depto_e_pre_nom_del'] : $dato['depto_e_user_nom_del']).'</td>
                            <td>'.((!empty($dato['depto_e_pre_nombre'])) ? $dato['depto_e_pre_nombre'] : $dato['depto_e_user_nombre']).'</td>
                            <td>'.((!empty($dato['evaluador_cat_pre_nom'])) ? $dato['evaluador_cat_pre_nom'] : $dato['evaluador_cat_user_nom']).'</td>
                        ';
                } else {
                    echo '<td>'.((!empty($dato['depto_e_tut_nom_del'])) ? $dato['depto_e_tut_nom_del'] : $dato['depto_e_user_nom_del']).'</td>
                            <td>'.((!empty($dato['depto_e_tut_nombre'])) ? $dato['depto_e_tut_nombre'] : $dato['depto_e_user_nombre']).'</td>
                            <td>'.((!empty($dato['evaluador_cat_tut_nom'])) ? $dato['evaluador_cat_tut_nom'] : $dato['evaluador_cat_user_nom']).'</td>
                        ';
                }
                foreach ($preguntas as $key_p => $pregunta) {
                    //echo '<td>'.$respuestas[$dato['course_cve']][$dato['group_id']][$dato['encuesta_cve']][$dato['evaluado_user_cve']][$dato['evaluador_user_cve']][$pregunta['preguntas_cve']]['texto'].'</td>';
                    echo '<td>'.$respuestas[$dato['course_cve']][$dato['group_id']][$dato['encuesta_cve']][$dato['evaluado_user_cve']][$dato['evaluador_user_cve']][$pregunta['preguntas_cve']]['texto'].'</td>';
                    //':'.$dato['course_cve'].'-'.$dato['group_id'].'-'.$dato['encuesta_cve'].'-'.$dato['evaluado_user_cve'].'-'.$dato['evaluador_user_cve'].'-'.$dato['preguntas_cve'].
                }
                echo '<td>'.rand(80, 95).' %</td>';
                echo '</tr>';
            } ?>
        </tbody>
    </table>
</div>

