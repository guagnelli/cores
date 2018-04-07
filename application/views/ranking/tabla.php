<?php
//pr($datos); 
if (!empty($datos))
{
    ?>
    <table id="table_ranking" class="table">
        <thead class="text-primary">
        <th><?php echo (isset($filtros['umae']) && $filtros['umae'] ? 'UMAE' : 'Delegación'); ?></th>
        <th><?php echo ($filtros['tipo'] == 1 || $filtros['tipo'] == '' ? 'Número de Alumnos Aprobados' : 'Eficiencia terminal modificada'); ?></th>
    </thead>
    <tbody>
        <?php
//    pr($datos);
        foreach ($datos as $row)
        {
            $class_i = '';

            if (isset($filtros['umae']) && $filtros['umae'] && $usuario['name_unidad_ist'] == $row['nombre'])
            {
                $class_i = 'current_comparativa_region';
                $row['nombre'] = '<strong>* ' . $row['nombre'] . '<strong>';
            } else if ($usuario['nombre_grupo_delegacion'] == $row['nombre'])
            {
                $class_i = 'current_comparativa_region';
                $row['nombre'] = '<strong>* ' . $row['nombre'] . '<strong>';
                ;
            }

            if ($filtros['tipo'] == 1 || $filtros['tipo'] == '')
            {
                $value = $row['aprobados'];
            } else if ($row['inscritos'] != $row['no_acceso'])
            {
                $value = ($row['aprobados']) / ($row['inscritos'] - $row['no_acceso']) * 100;
            } else
            {
                $value = 0;
            }
            ?>
            <tr class="<?php echo $class_i; ?>">
                <th><?php echo $row['nombre']; ?></th>
                <td><?php echo intval($value); ?></td>
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
    
    <?php
}
?>