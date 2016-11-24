<?php
$curso = $datos_curso['data'][0]['cur_id'];
echo form_open('curso/curso_bloque_grupos/'.$curso, array('id' => 'form_curso'));
?>

<div class="panel-heading clearfix breadcrumbs6">
    <h2><?php echo $datos_curso['data'][0]['cur_clave'].'-'.$datos_curso['data'][0]['cur_nom_completo'];?></h2><br>
    <h2 class="panel-title" style="padding-left:20px;">Gestión de bloques por curso</h2>
    <a href="<?php echo site_url('curso/info_curso/'.$curso); ?>" class="btn btn-info pull-right"> <span class="glyphicon glyphicon-level-up"></span> Regresar a detalle curso</a>
</div>

<input type="hidden" id="curso" name="curso" value="<?php echo $curso ?>">
<input type="hidden" id="bactiva" name="bactiva" value="0">

<div id="listado_resultado">
  <table class="table-responsive">
    <thead>
      <tr>
        <th>Grupo</th>
        <th>Bloque</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <?php foreach ($grupos['data'] as $key_g => $grupo) {
          echo '<tr><td>'.$grupo['grup_nom'].'</td>
              <td><select type="">
                  <option value="">'.rand(1,5).'</option>
                </select></td></tr>';
        } ?>
      </tr>
    </tbody>
  </table>
  <div class="right"><input type="button" class="form-control btn-primary" value="Guardar bloques" /></div>
</div>

<div class="clearfix"></div>    

<?php echo form_close(); ?>