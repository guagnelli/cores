<div class="form-group row">
    <div class="col-lg-4 col-md-6 col-sm-6">
        <label class="col-form-label"><?php echo $lenguaje['nombre']; ?>: &nbsp;&nbsp;<?php echo $name_user; ?></label>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6">
        <label class="col-form-label"><?php echo $lenguaje['matricula']; ?>: &nbsp;&nbsp;<?php echo $matricula; ?></label>
    </div>
    <?php if(isset($name_categoria) AND !empty(trim($name_categoria))) { ?>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <label class="col-form-label"><?php echo $lenguaje['categoria']; ?>: &nbsp;&nbsp;<?php echo $name_categoria; ?></label>
        </div>
    <?php } 
    if(in_array($grupos[0]['id_grupo'], array(En_grupos::NIVEL_CENTRAL, En_grupos::ADMIN, En_grupos::SUPERADMIN))) { ?>
        <!-- <label class="col-lg-4 col-md-6 col-sm-6 col-form-label"><?php echo $lenguaje['umae']; ?>:</label> -->
        <div class="col-lg-4 col-md-6 col-sm-6">
            <label class="col-form-label"><?php echo $lenguaje['nivel_central']; ?></label>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <label class="col-form-label"><?php echo $lenguaje['direccion_normativa']; ?>: &nbsp;&nbsp;<?php echo $name_unidad_ist; ?></label>
        </div>
        <?php 
    } else {
        if($umae==true){ ?>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <label class="col-form-label"><?php echo $lenguaje['umae']; ?>: &nbsp;&nbsp;<?php echo $name_unidad_ist; ?></label>
            </div>
        <?php } else { ?>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <label class="col-form-label"><?php echo $lenguaje['delegacion']; ?>: &nbsp;&nbsp;<?php echo $nombre_grupo_delegacion; ?></label>
            </div>
            <?php if(in_array($grupos[0]['id_grupo'], array(En_grupos::N1_CEIS,En_grupos::N1_DH,En_grupos::N1_DUMF,En_grupos::N1_DEIS,En_grupos::N1_DM,En_grupos::N1_JDES))) { ?>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <label class="col-form-label"><?php echo $lenguaje['unidad']; ?>: &nbsp;&nbsp;<?php echo $name_unidad_ist; ?></label>
                </div>
            <?php }
        }
    }?>
</div>