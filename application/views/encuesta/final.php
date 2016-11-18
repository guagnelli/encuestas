<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="panel panel-amarillo">
            <div class="panel-body">
<?php 
$this->config->load('general');
$tipo_msg = $this->config->item('alert_msg');

if (isset($mensaje))
{ 
	echo html_message($mensaje, $tipo_msg['WARNING']['class']);
}
?>


</div>
</div>
</div>
</div>