<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
?>
<div class="col col-md-12">
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
</div>