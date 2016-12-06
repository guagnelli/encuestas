<!--<ul class="dropdown-menu" style="visibility: visible;">-->
<!--<li id="" class="">-->
    <!--<span>//<?php // echo $value['curso'];              ?></span>-->
<!--</li>-->

<!--</ul>-->

    <?php // pr($res_busqueda); ?>

<div id="lista_cn" class="dropdown open" >
    <!--    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Tutorials
            <span class="caret"></span></button>-->
    <ul class="dropdown-menu" style="overflow-y: auto; height: 100px; width: 300px; float: right">
        <?php foreach ($res_busqueda as $key => $value) { ?>
            <li >
                <font id="tar_<?php echo $key; ?>" class="ocultar" data-idcurso="<?php echo $value['idcurso']; ?>">
                <!--<a class="a_color_" href="#">-->
                <?php echo $value['curso']; ?>
                <!--</a>-->
                </font>
            </li>
        <?php } ?>
    </ul>
</div>

<script type="text/javascript">
//    $("#target").click(function () {
//        alert("Handler for .click() called.");
//    });
    $('div>ul>li>.ocultar').click(function () {
        var datathis= $(this);//Obtiene el id del curso
        var idcurso= datathis.data('idcurso');//Obtiene el id del curso
        $('#text_buscar_instrumento').focus();
        $('#lista_cn').removeClass('dropdown open').addClass('dropdown');
        $('#text_buscar_instrumento').val(datathis.text().trim());
        funcion_cargar_bloque(idcurso);//Carga bloque
    });
//    $('#lista_cn').click(function () {
//        if ($('#lista_cn').hasClass('dropdown open')) {
//            $('#lista_cn').removeClass('dropdown open').addClass('dropdown');
//        } else {
//            $('#lista_cn').removeClass('dropdown').addClass('dropdown open');
//
//        }
//    });
</script>
