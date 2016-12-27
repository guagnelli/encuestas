<?php if (isset($datos_user_aeva) && !empty($datos_user_aeva)) { 
   //pr($datos_user_aeva);

    ?>
    <div class="list-group-item">
       <div style="text-align:right"><small><?php echo $nombreevaluador?>
        </small></div>
        <?php
        if (isset($error) AND ! is_null($error) AND ! empty($error)) {
            echo '<div class="row">
                                <div class="col-md-1 col-sm-1 col-xs-1"></div>
                                <div class="col-md-10 col-sm-10 col-xs-10 alert alert-danger">
                                    ' . $error . '
                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-1"></div>
                            </div>';
        }
        ?>
    </div>
    <div class="panel-heading">  
            <table>
            <tr>
                <th>
                    NOMBRE DE IMPLEMENTACIÓN:
                </th>
            </tr>
            <tr>                
                <td>
                    <h3> <?php echo $datos_curso['data'][0]['cur_nom_completo']; ?></h3> 
                </td>
            </tr>
        </table>
      </div>
    </div>


  
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>Regla</th>
                    <th>Rol evaluador</th>

                    <th>Rol a evaluar</th>
                    <th>Nombre docente a evaluar</th>
                    <th>Grupo</th>
                   <!--<th>Encuesta</th>-->
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                  //pr($datos_user_aeva);
                foreach ($datos_user_aeva as $val) {
                    //pr($val);
                    foreach ($val as $keyl => $valuel) {

                        //pr($valuel);# code...
                    //}
                    

                    if (isset($valuel)) {
                        if (isset($valuel['ngpo']) && $valuel['ngpo'] != '0') {
                            //$grupo = $val[0]['ngpo'];
                            $grupo = (!empty($valuel['ngpo'])) ? implode(str_getcsv(trim($valuel['ngpo'], '{}')), ', ') : '';
                        } else {
                            $grupo = '--';
                        }

                        echo '<tr>
                        <td >' . $valuel['regla'] . '</td >
                        <td >' . $valuel['evaluador'] . '</td >
                        <td >' . $valuel['role'] . '</td > 
                         <td >' . $valuel['firstname'] . ' ' . $valuel['lastname'] . '</td>
                        <td > ' . $grupo . '</td >';
                        //<td >' . $val[0]['regla'] . '</td>

                        echo '<td>';
                        echo form_open('encuestausuario/instrumento_asignado', array('id' => 'form_curso'));
                        ?>
                    <input type="hidden" id="idencuesta" name="idencuesta" value="<?php echo $valuel['regla'] ?>">
                    <input type="hidden" id="iduevaluado" name="iduevaluado" value="<?php echo $valuel['userid'] ?>">
                    <input type="hidden" id="idcurso" name="idcurso" value="<?php echo $valuel['cursoid'] ?>">
                    <input type="hidden" id="idgrupo" name="idgrupo" value="<?php
                    if (isset($valuel['gpoid']) && $valuel['gpoid'] > 0) {
                        echo $valuel['gpoid'];
                    } else {
                        echo '0';
                    }
                    ?>">
                    <input type="hidden" id="iduevaluador" name="iduevaluador" value="<?php echo $iduevaluador ?>">


                    <?php
                    if (isset($valuel['realizado']) || !empty($valuel['realizado'])) {
                        echo "Realizada";
                    } else {
                        ?>
                        <input type="submit"  class="btn btn-info btn-block" value="Evaluar usuario">
                        <?php
                    }
                    ?>

                    <!--
                      <a href="'.site_url('encuestausuario/instrumento_asignado/'.$val[0]['regla']).'" class="btn btn-info btn-block">
                          <span class="glyphicon glyphicon-search"></span>
                      </a>-->
                    <?php
                    echo form_close();

                    echo '</td>
                        ';

                    echo '</tr>';
                    # code...
                }
              }  
            }
            ?>
            </tbody>
        </table>

    </div>
<?php } else if (isset($coment_general)) { ?>
    <div class="row">
        <div class="jumbotron"><div class="container"><p class="text_center"><?php echo $coment_general; ?></p> </div></div>
    </div>
<?php } else { ?>
    <br><br>
    <div class="row">
        <div class="jumbotron"><div class="container"> <p class="text_center">No se encontraron datos registrados con esta busqueda</p> </div></div>
    </div>

<?php } ?>
