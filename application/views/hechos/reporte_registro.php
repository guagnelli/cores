<table>
    <thead>
        <?php
        if (count($data) > 0)
        {
            foreach ($data[0] as $key => $value)
            {
                ?>
            <th><?php echo utf8_encode($key); ?></th>
            <?php
        }
    }
    ?>
</thead>
<tbody>
    <?php
    foreach ($data as $row)
    {
        ?>
        <tr>
            <?php
            foreach ($row as $key => $value)
            {
                ?>
            <td><?php echo utf8_encode($value); ?></td>            
            <?php } ?>
        </tr>
        <?php
    }
    ?>
</tbody>
</table>