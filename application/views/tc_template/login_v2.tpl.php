<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo isset($texts["title"]) ? $texts["title"] . "::" : ""; ?>CORES</title>
        <link href="<?php echo base_url(); ?>assets/login/fonts.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>assets/login/bootstrap.css" rel="stylesheet"/>
        <link href="<?php echo base_url(); ?>assets/login/styles_tablero_control.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/login/style_sesion_v2.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/login/securimage.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/third-party/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <script src="<?php echo base_url(); ?>assets/tablero_tpl/js/jquery-3.1.0.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/tablero_tpl/js/bootstrap.min.js" type="text/javascript"></script>
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
        <?php echo js("login_animation.js"); ?>
        <?php echo js("login.js"); ?>
        <style type="text/css">
            .text-cores-bottom {
                color:#333333;
                font-size: 14px;
            }
        </style>
        <!-- Google Analytics -->
        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-107669218-1', 'auto');
            ga('send', 'pageview');
        </script>
        <!-- End Google Analytics -->
    </head>

    <body>
        <!-- Modal recuperar contraseña -->
        <div class="modal fade" id="modalRecovery" tabindex="1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="col-md-12">
                            <img class="cores-logo" src="<?php echo base_url(); ?>assets/login/cores.png">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">Cerrar <span aria-hidden="true">&times;</span></button>
                        </div>
                    </div>
                    <div class="modal-body cores-recovery-modal">
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo $modal_recovery; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- fin Modal recuperar contraseña -->

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="col-md-12">
                            <img class="cores-logo" src="<?php echo base_url(); ?>assets/login/cores.png">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">Cerrar <span aria-hidden="true">&times;</span></button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                Inicio de sesión
                                <div class="login-html">
                                    <div class="login-form">
                                        <?php echo form_open('welcome/index', array('id' => 'session_form', 'autocomplete' => "off")); ?>
                                        <div class="sign-in-htm">
                                            <div class="group">
                                                <!--label for="user" class="label">Usuario:</label-->
                                                <input id="usuario"
                                                       name="usuario"
                                                       type="text"
                                                       class="input"
                                                       autocomplete="off"
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
                                                       autocomplete="off"
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
                                                    <a class="btn btn-lg btn-success pull-right" onclick="new_captcha()">
                                                        <span class="glyphicon glyphicon-refresh"></span>
                                                    </a>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="group">
                                                <input type="submit" class="btn btn-success btn-lg btn-login" value="Iniciar sesión">
                                            </div>
                                            <?php echo form_close(); ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="cores-help-modal">
                                <p><a href="#">Necesita ayuda</a></p>
                                <p>Olvido su contraseña, <a onclick="recovery_password()">Solicitela aquí</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row div-logos">
                <div class="cores-logos">
                    <img src="<?php echo base_url(); ?>assets/tablero_tpl/img/ces.png">
                    <img src="<?php echo base_url(); ?>assets/tablero_tpl/img/imss.png">
                </div>
            </div>
            <div class="row cores-orbitas"></div>
            <div id="cores-area-principal" class="row cores-background">

                <div class="col-md-9">
                    <div id="cores-area-animation"></div>
                </div>
                <div class="col-md-3 cores-column-info">
                    <div class="cores-info">
                        <div class="row">
                            <div><p><a class="cores-acceso" href="#" data-toggle="modal" data-target="#myModal">Acceso <i class="fa fa-bars" aria-hidden="true"></i></a></p></div>
                        </div>
                        <div class="row cores-slider-text">
                            <div><p id="cores-banner">Decisiones basadas en información</p></div>
                        </div>
                        <div style="display: none;" class="row cores-help">
                            <div><p>Preguntas frecuentes <i class="fa fa-question-circle" aria-hidden="true"></i></p></div>
                        </div>
                    </div>
                </div>
                <address class=""><br>
                  <p style="text-align:center;"><b>Mesa de ayuda</b></p>
                  <p style="text-align:center; font-size:10px;">¿Tienes alguna duda? Comunícate con nosotros: </p>
                  <p style="text-align:center; font-size:10px;"><b>Teléfono:</b> 56 27 69 00 Ext. 21146, 21147 y 21148</p>
                  <p style="text-align:center; font-size:10px;"><b>Email:</b> soporte.cores@gmail.com &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</p>
                  <p style="text-align:center; font-size:10px;"><b>Horario:</b> de lunes a viernes, de 8h a 16h &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</p>
                </address>
            </div>
            <div class="cores-bottom text-center text-cores-bottom">Este sitio se visualiza correctamente apartir Mozila Firefox 50 y Google Chrome 55.</div>
        </div>
        <script>
            document.getElementsByTagName("BODY")[0].onresize = function () {
                cores_animation()
            };

            var x = 0;
            function cores_animation() {
                //var txt = x += 1;
                //document.getElementById("demo").innerHTML = txt;
                cores_render_points2();
            }
<?php
if (isset($errores))
{
    ?>
                $('#myModal').modal({show: true});
    <?php
}

if (isset($user_recovery) || isset($code_recovery))
{
    ?>
                $('#modalRecovery').modal({show: true});
    <?php
}
?>
        </script>
    </body>
</html>
