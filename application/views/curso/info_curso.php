<div class="panel panel-default">
    <?php 
        $this->config->load('general');
        $tipo_msg = $this->config->item('alert_msg');
        /*
            [0] => id
            [1] => cur_id
            [2] => cur_clave
            [3] => cur_nom_completo
            [4] => cat_cve
            [5] => cat_nom
            [6] => fecha_inicio
            [7] => anio
            [8] => horascur
            [9] => modalidad
            [10] => tipocur
            [11] => startdatepre
            [12] => tutorizado
            [13] => curso_alcance

             # roles
            [0] => id
            [1] => rol_id
            [2] => nom_rol
            [3] => usuarios_por_rol

            # grupos
            [0] => id
            [1] => grup_id
            [2] => grup_nom

        */
        $check_ok = '<span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green;"> </span>';
        $check_no = '<span class="glyphicon glyphicon-remove" aria-hidden="true" style="color:red;"> </span>'; 

        if(isset($curso) && !empty($curso) && $curso['total']==1 ){
            $info_curso = $curso['data'][0];
    ?>
    <div class="panel-heading">  
        <table>
            <tr>
                <th>
                    NOMBRE DE IMPLEMENTACIÓN:
                </th>
            </tr>
            <tr>                
                <td>
                    <h3> <?php echo $info_curso['cur_nom_completo']; ?></h3> 
                </td>
            </tr>
        </table>
        <a href="<?php echo site_url('curso'); ?>" class="btn btn-info pull-right"> <span class="glyphicon glyphicon-level-up"></span> Ir a implementaciones</a>
        <br><br>
    </div>
    <div class="panel-body">
        <div class="row">            
            <div class="col-xs-12 col-sm-8 col-md-8">
                <div class="col-xs-12 col-md-12">

                        <h3>Información de la implementación</h3><br><br>
                        <!--Id curso: <br>
                        Clave curso: <br>
                        Categoria: <br>
                        Año: <br>
                        Fecha inicio: <br>
                        Duración en horas: <br>
                         tipo: <?php //echo $info_curso['tipocur']; ?><br>
                        modalidad: <?php //echo $info_curso['modalidad']; ?><br>
                        Tutorizado: <br>-->
                        <table>
                            <tr>
                                <td># IMPLEMENTACIÓN:</td>
                                <th><?php echo $info_curso['cur_id']; ?></td>
                            </tr>
                            <tr>
                                <td>CLAVE DE IMPLEMENTACIÓN:</td>
                                <th><?php echo $info_curso['cur_clave']; ?></td>
                            </tr>
                            <tr>
                                <td>CATEGORÍA:</td>
                                <th><?php echo $info_curso['cat_nom']; ?></td>
                            </tr>
                            <tr>
                                <td>AÑO:</td>
                                <th><?php echo $info_curso['anio']; ?></td>
                            </tr>
                            <tr>
                                <td>FECHA INICIO:</td>
                                <th><?php echo date("d-m-Y",strtotime($info_curso['fecha_inicio'])); ?></td>
                            </tr>
                            <tr>
                                <td>DURACIÓN HORAS:</td>
                                <th><?php echo $info_curso['horascur']; ?></td>
                            </tr>
                            <tr>
                                <td>TUTORIZADO:</td>
                                <th><?php echo (($info_curso['tutorizado'] == '1' ) ? $check_ok : $check_no ); ?></td>
                            </tr>
                        </table>

                </div>
                
                <div class="clearfix"></div>
                <div class="col-xs-4 col-md-12 list-group-item" >
                    <a href="<?php echo site_url('cursoencuesta/curso_encuesta/'.$info_curso['cur_id'])?>" class="btn btn-success btn-block">Asignar encuestas   </a>
                
                </div>
                
               

                <div class="col-xs-4 col-md-12 list-group-item">                
                    <a href="<?php echo site_url('resultadocurenrealizada/curso_encuesta_general/'.$info_curso['cur_id'])?>" class="btn btn-success btn-block">Concentrado de encuestas (Estatus)</a>
                
                </div>

                 <div class="col-xs-4 col-md-12 list-group-item">                
                    <a href="<?php echo site_url('resultadocursoencuesta/curso_encuesta_resultado/'.$info_curso['cur_id'])?>" class="btn btn-success btn-block">Encuestas contestadas </a>
                
                </div>
                
                  <div class="col-xs-4 col-md-12 list-group-item">                
                    <a href="<?php echo site_url('resultadocursoencuesta/curso_encuesta_resultado_detalle/'.$info_curso['cur_id'])?>" class="btn btn-success btn-block">Detalle de encuestas contestadas</a>
                
                </div>
                <br><br>

                <div class="clearfix"></div>
                <div class="col-xs-12 col-md-12">
                    <h3>Roles de implementación</h3><br><br>
                    <div class="list-group">
                        <?php
                            $roles_mostrar = array(5,14,18,32,33,30);
                            foreach ($roles['data'] as $row) {

                                if (in_array($row['rol_id'], $roles_mostrar)) {

                                
                                    ?>
                                    <div class="list-group-item" >
                                        <span  class="badge"><?php echo $row['usuarios_por_rol']; ?></span>
                                        <?php echo $row['nom_rol']; ?>
                                    </div>
                                    <?php

                                }
                            }
                            
                            ?>
                            
                    </div>
                </div>

            </div>
            
            <div class="col-xs-12 col-sm-4 col-md-4">
                <h3>Grupos de implementación</h3><br><br>
                <div class="list-group">
                    <?php
                        foreach ($grupos['data'] as $row) {
                            ?>
                            <div class="list-group-item" data-gorupId="<?php echo $row['grup_id']; ?>">
                                <?php echo $row['grup_nom']; ?>
                            </div>
                            <?php
                        }
                    ?>
                </div>

            </div>


        </div>
        
    </div>
    <?php
        }else{
    ?>
    <div class="panel-body">
        <?php echo html_message('No se encontro información del curso', $tipo_msg['WARNING']['class']); ?>
    </div>
    <?php

        }

    ?>

</div>
