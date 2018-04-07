
<ul class="dropdown-menu">
    <li>
        <a class="link_ficha_usuario" href="#">
            <b>Nombre:</b> <?php echo $name_user; ?> <br>
            <b>Matrícula:</b> <?php echo $matricula; ?> <br>
            <b>Categoría:</b> <?php echo $name_categoria; ?> <br>
            <?php
            if ($umae)
            {
                ?>
                <b>UMAE:</b> <?php echo $name_unidad_ist; ?> <br>
                <?php
            } else
            {
                ?>
                <b>Delegación:</b> <?php echo $nombre_grupo_delegacion; ?> <br>
                <b>Unidad:</b> <?php echo $name_unidad_ist; ?> <br>
            <?php } ?>
            <div class="ripple-container"></div></a>
    </li>
    <li>
        <a class="link_ficha_usuario" href="<?php echo site_url() ?>/perfil_usuario">
            <i class="material-icons">mode_edit</i>
            Editar perfil
            <div class="ripple-container"></div></a>
    </li>
    <li>
        <a class="link_ficha_usuario" href="<?php echo site_url() ?>/welcome/cerrar_sesion">
            <i class="fa fa-sign-out"></i>
            Cerrar sesión
        </a>
    </li>
</ul>