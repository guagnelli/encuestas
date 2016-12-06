<label for="grupo">Bloques</label>
<?php
echo $this->form_complete->create_element(array('id' => 'bloque', 'type' => 'dropdown',
    'options' => $bloques_p, 'first' => array('' => 'Seleccione bloque'),
    'attributes' => array(
        'name' => 'bloque',
        'class' => 'form-control',
        'placeholder' => 'Bloque',
        'data-toggle' => 'tooltip',
        'data-tipo' => 'b',
        'data-placement' => 'top',
        'title' => 'Bloques',
)));
?>