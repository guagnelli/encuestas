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
    if (isset($curso) && !empty($curso) && $curso['total'] == 1) {
        $info_curso = $curso['data'][0];
        ?>
        <div class="panel-heading">  
            <table>
                <tr>
                    <th>
                        <h3>Información de la implementación</h3><br>
                    </th>
                </tr>
                <tr>                
                    <td>
                        <h4>Nombre: <?php echo $info_curso['cur_nom_completo']; ?>
                        </h4> 
                    </td>
                    <td>
                        <a href="<?php echo site_url('curso'); ?>" class="btn btn-info pull-right"> <span class="glyphicon glyphicon-level-up"></span> Ir a implementaciones</a>
                    </td>
                </tr>
                <tr>
                    <td># IMPLEMENTACIÓN:</td>
                    <th><?php echo $info_curso['cur_id']; ?></th>
                </tr>
                <tr>
                    <td>CLAVE DE IMPLEMENTACIÓN:</td>
                    <th><?php echo $info_curso['cur_clave']; ?></th>

                </tr>
                <tr>
                    <td>CATEGORÍA:</td>
                    <th><?php echo $info_curso['cat_nom']; ?></th>
                </tr>
                <tr>
                    <td>AÑO:</td>
                    <th><?php echo $info_curso['anio']; ?></th>
                </tr>
                <tr>
                    <td>FECHA INICIO:</td>
                    <th><?php echo date("d-m-Y", strtotime($info_curso['fecha_inicio'])); ?></th>
                </tr>
                <tr>
                    <td>DURACIÓN HORAS:</td>
                    <th><?php echo $info_curso['horascur']; ?></th>
                </tr>
                <tr>
                    <td>TUTORIZADO:</td>
                    <th><?php echo (($info_curso['tutorizado'] == '1' ) ? $check_ok : $check_no ); ?></th>
                </tr>
            </table>
        </div>
        <div class="panel-body">
            <div class="row">            
                <div class="col-xs-12 col-sm-8 col-md-8">
                    <div class="col-xs-12 col-md-12">
                        <h3>Roles que participan en la implementación:</h3><br>
                        <div class="list-group">
                            <?php
                            $roles_mostrar = array(5, 14, 18, 32, 33, 30);
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
                    <div class="clearfix"></div>
                    <div class="col-xs-12 col-md-12 list-group-item" >
                        <table>
                            <tr>
                                <th>
                                    <h3>Acciones:</h3>
                                </th>
                            </tr>
                            <tr>                
                                <td>
                                    <a href="<?php echo site_url('cursoencuesta/curso_encuesta/' . $info_curso['cur_id']) ?>" class="btn btn-info btn-block">Asignar encuestas</a>
                                </td>
                            </tr>
                            <?php if ($info_curso['tutorizado'] == 1) { ?>
                                <tr>                
                                    <td>
                                        <a href="<?php echo site_url('curso/curso_bloque_grupos/' . $info_curso['cur_id']) ?>" class="btn btn-info btn-block">Definir bloques</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="clearfix"></div><br>
                    <div class="col-xs-12 col-md-12 list-group-item" >
                        <table>
                            <tr>
                                <th>
                                    <h3>Reportes:</h3>
                                </th>
                            </tr>
                            <tr>                
                                <td>
                                    <a href="<?php echo site_url('resultadocurenrealizada/curso_encuesta_general/' . $info_curso['cur_id']) ?>" class="btn btn-success btn-block">Concentrado de encuestas (Estatus)</a>
                                </td>
                            </tr>
                            <tr>                
                                <td>
                                    <a href="<?php echo site_url('resultadocursoencuesta/curso_encuesta_resultado/' . $info_curso['cur_id']) ?>" class="btn btn-success btn-block">Encuestas contestadas </a>
                                </td>
                            </tr>
                            <!-- <tr>                
                                <td>
                                    <a href="<?php echo site_url('resultadocursoencuesta/curso_encuesta_resultado_detalle/' . $info_curso['cur_id']) ?>" class="btn btn-success btn-block">Detalle de encuestas contestadas</a>
                                </td>
                            </tr> -->
                            <tr>                
                                <td>
                                    <a href="<?php echo site_url('resultadocursoindicador/index/' . $info_curso['cur_id']) ?>" class="btn btn-success btn-block">Reporte de encuestas por indicador</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="clearfix"></div><br>

                </div>

                <div class="col-xs-12 col-sm-4 col-md-4">
                    <h3>Grupos de implementación</h3>
                    <div class="list-group">
                        <div class="list-group-item left" style="width:30%; vertical-align: middle; height: 100%; line-height:100%; vertical-align: middle;">Bloque</div>
                        <div class="list-group-item right" style="width:70%; vertical-align: middle; height: 100%; line-height:100%; vertical-align: middle;">Grupo</div>
                        <?php
                        foreach ($grupos['data'] as $row) {
                            ?>
                            <div class="list-group-item left" style="width:30%; vertical-align: middle; height: 100%; line-height:100%; vertical-align: middle;"><?php echo rand(1, 4); ?></div>
                            <div class="list-group-item right" data-groupId="<?php echo $row['grup_id']; ?>" style="width:70%; vertical-align: middle; height: 100%; line-height:100%; vertical-align: middle;">
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
    } else {
        ?>
        <div class="panel-body">
            <?php echo html_message('No se encontro información del curso', $tipo_msg['WARNING']['class']); ?>
        </div>
        <?php
    }
    ?>

</div>
