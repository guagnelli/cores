<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Lista de cursos</h4>
</div>
<div class="modal-body">
    <div class="">
        <?php
        if (!empty($lista_cursos))
        {
            ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lista_cursos as $row)
                    {
                        ?>            
                        <tr>                
                            <td><?php echo $row['clave']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else
        {
            ?>
            <div class="alert alert-warning">
                <span>
                    No existen resultados para esa busqueda, intente con otro programa por favor.
                </span>
            </div>
        <?php } ?>
    </div>
</div>