
<?php
if (!isset($recovery) && !isset($form_recovery) && !isset($success))
{
    ?>
    <div class="login-form">
        <p>¿Perdiste tu constraseña? Por favor introduce tu nombre de usuario o correo electrónico. Recibirás un enlace para crear una contraseña nueva por correo electronico.</p>
        <?php echo form_open('welcome/index', array('id' => 'session_form')); ?>
        <input type="hidden" name="recovery" value="1">
        <div class="sign-in-htm">
            <div class="group">
                <label for="user" class="label"></label>
                <input id="usuario" name="usuario" type="text" class="input" placeholder="Matrícula o correo electrónico">
            </div>
            <?php echo form_error_format('usuario'); ?>
            <div class="group">
                <input type="submit" class="btn btn-success btn-lg btn-login" value="Restablecer contraseña">
            </div>
            <?php echo form_close(); ?>
        </div>
        <address class="">
          <br><br><br>
          <p style="text-align:center;"><b>Mesa de ayuda</b></p>
          <p style="text-align:center;">¿Tienes alguna duda? Comunícate con nosotros: </p>
          <p style="text-align:center;"><b>Teléfono:</b> 56 27 69 00 Ext. 21146, 21147 y 21148</p>
          <p style="text-align:center;"><b>Email:</b> soporte.cores@gmail.com &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</p>
          <p style="text-align:center;"><b>Horario:</b> de lunes a viernes, de 8h a 16h &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</p>
        </address>

    </div>
    <?php
} else if (isset($form_recovery))
{
    ?>
    <div class="login-form">
        <p>Por favor indica cual será tu nueva contraseña</p>
        <?php echo form_open('welcome/index/' . $code, array('id' => 'session_form')); ?>
        <input type="hidden" name="recovery" value="1">
        <div class="sign-in-htm">
            <div class="group">
                <label for="new_password" class="label"></label>
                <input id="new_password" name="new_password" type="password" class="input" data-type="password" placeholder="Contraseña:">
            </div>
            <?php echo form_error_format('new_password'); ?>

            <div class="group">
                <label for="new_password_confirm" class="label"></label>
                <input id="new_password_confirm" name="new_password_confirm" type="password" class="input" data-type="password" placeholder="Confirmar Contraseña:">
            </div>
            <?php echo form_error_format('new_password_confirm'); ?>
            <div class="group">
                <input type="submit" class="btn btn-success btn-lg btn-login" value="Restablecer contraseña">
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
    <?php
} else if (isset($success))
{
    ?>
    <p>Contraseña actualizada con éxito</p>
    <?php
} else
{
    ?>
    <p>El sistema ha recibido tu solicud con éxito, recibirás un enlace para crear una contraseña nueva por correo electrónico.</p>
    <?php
}
?>
