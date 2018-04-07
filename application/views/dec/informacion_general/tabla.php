<?php
if (!empty($datos['datos']))
{
    ?>
    <table id="table_ranking" class="table">
        <thead class="text-primary">

          <?php
          if(isset($delegacion) && $delegacion == "Todos")
          { ?>
            <th>Delegación</th>
          <?php
          }
          ?>

        <th>Unidad</th>
        <th>Programa Educativo</th>
        <th>Asistentes programados</th>
        <th>Asistentes reales</th>
        <th>Procentaje de asistentes aprobados</th>
    </thead>
    <tbody>
      <?php
      //pr($datos['datos']);
      foreach ($datos['datos'] as $row)
      { ?>
        <tr>
            <?php
            if(isset($delegacion) && $delegacion == "Todos")
            { ?>
              <td><?php echo $row['delegacion']?></td>
            <?php
            }
            ?>

            <td><?php echo $row['unidad']?></td>
            <td><?php if($row['numerador'] > 0 ){echo $row['programa_educativo'];}else{echo 'NP';}?></td>
            <td><?php if($row['denominador'] != ''){echo $row['denominador']." %";}else{echo 0;}?></td>
            <td><?php if($row['numerador'] != ''){echo $row['numerador']." %";}else{echo 0;}?></td>
            <td><?php if($row['porcentaje'] != ''){echo $row['porcentaje']." %";}else{echo "0 %";}?></td>
        </tr>
      <?php } ?>
    </tbody>
    </table>
    <?php
}else
{
    ?>
      <center>
        <h2>NO SE ENCONTRARON DATOS</h2>
      </center>
    <?php
}
?>
