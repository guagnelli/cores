<?php
//pr($texts);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo isset($texts["title"]) ? $texts["title"] . "::" : ""; ?>Tablero de control</title>
        <link href="<?php echo base_url(); ?>assets/login/fonts.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>assets/login/bootstrap.css" rel="stylesheet"/>
        <link href="<?php echo base_url(); ?>assets/login/styles_tablero_control.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/login/style_sesion.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/login/securimage.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/login/jquery-2.min.js"></script>

        <script type="text/javascript">
            var img_url_loader = "<?php echo img_url_loader('loading.gif'); ?>";
            var site_url = "<?php echo site_url(); ?>";
            if (typeof (Storage) !== "undefined") {
                console.log('colocando para: ' + sessionStorage.menu_active);
                if (sessionStorage.menu_active) {
                    sessionStorage.menu_active = "";
                }
            }
        </script>
        <?php echo js("general.js"); ?>
        <?php echo js("captcha.js"); ?>
    </head>

    <body>
        <div class="container">


            <div class="login-wrap" >
                <div class="login-html">
                    <!--<input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Entrar</label>-->
                    <!--<input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab">Registrarse</label>-->
                    <div class="login-form">
                        <?php echo form_open('welcome/index', array('id' => 'session_form')); ?>
                        <div class="sign-in-htm">
                            <div class="group">
                                <!--label for="user" class="label">Usuario:</label-->
                                <input id="usuario"
                                       name="usuario"
                                       type="text"
                                       class="input"
                                       placeholder="<?php echo $texts['user']; ?>:">

                            </div>
                            <?php
                            echo form_error_format('usuario');
                            if ($this->session->flashdata('flash_usuario'))
                            {
                                ?>
                                <div class="alert alert-danger" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                <?php echo $this->session->flashdata('flash_usuario'); ?>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="group">
                                <!--label for="pass" class="label">Contraseña:</label-->
                                <input id="password"
                                       name="password"
                                       type="password"
                                       class="input"
                                       data-type="password"
                                       placeholder="<?php echo $texts['passwd']; ?>:">
                            </div>
                            <?php
                            echo form_error_format('password');
                            if ($this->session->flashdata('flash_password'))
                            {
                                ?>
                                <div class="alert alert-danger" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                <?php echo $this->session->flashdata('flash_password'); ?>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="group">
                                <!--label for="captcha" class="label"></label-->
                                <input id="captcha"
                                       name="captcha"
                                       type="text"
                                       class="input"
                                       placeholder="<?php echo $texts['captcha']; ?>:">
                                       <?php
                                       echo form_error_format('captcha');
                                       ?>
                                <br>
                                <div class="captcha-container" id="captcha_first">
                                    <img id="captcha_img" src="<?php echo site_url(); ?>/captcha" alt="CAPTCHA Image" />
                                </div>
                                <br />
                                <a class="btn btn-lg btn-primary pull-right" onclick="new_captcha()">
                                    <span class="glyphicon glyphicon-refresh"></span>
                                </a>
                            </div>
                            <br>
                            <div class="group">
                                <input type="submit" class="btn btn-success btn-lg btn-login" value="Iniciar sesión">
                            </div>


                            <br>
                            <div class="foot-lnk">
                                <?php
                                echo anchor(
                                    "welcome/recuperar_password", "Si olvidó su contraseña, solicítela aquí", array(
                                    "title" => "Si olvidó su contraseña, solicítela aquí",
                                    "alt" => "Si olvidó su contraseña, solicítela aquí",
                                        )
                                );
                                ?>
                                <a href="<?php echo site_url(); ?>/sesion/recuperar_password"></a>
                            </div>

<?php echo form_close(); ?>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </body>
</html>
<script type="text/javascript">
    function change_image() {
        data_ajax(site_url + "/captchac/get_new_captcha_ajax", null, "#captcha_first"); // cargamos por primera vez el captcha
    }
</script>
