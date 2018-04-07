<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>assets/tablero_tpl/img/apple-icon.png" />
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/tablero_tpl/img/favicon.ico" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

        <title>
            <?php echo (!is_null($title)) ? "{$title}&nbsp;|" : "" ?>
            <?php echo (!is_null($main_title)) ? $main_title : "CORES" ?>
        </title>

        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <meta name="viewport" content="width=device-width" />

        <!-- Bootstrap core CSS     -->
        <link href="<?php echo base_url(); ?>assets/tablero_tpl/css/bootstrap.min.css" rel="stylesheet" />

        <!--  Material Dashboard CSS    -->
        <link href="<?php echo base_url(); ?>assets/tablero_tpl/css/material-dashboard.css" rel="stylesheet"/>
        <link href="<?php echo base_url(); ?>assets/tablero_tpl/css/my-material-dashboard.css" rel="stylesheet"/>

        <!--  CSS for Demo Purpose, don't include it in your project     -->
        <link href="<?php echo base_url(); ?>assets/tablero_tpl/css/demo.css" rel="stylesheet" />

        <!--     Fonts and icons     -->
        <link href="<?php echo base_url(); ?>assets/third-party/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet' type='text/css'>
        <script type="text/javascript">
            var url = "<?php echo base_url(); ?>";
            var site_url = "<?php echo site_url(); ?>";
        </script>
        <!--   Core JS Files   -->
        <script src="<?php echo base_url(); ?>assets/tablero_tpl/js/jquery-3.1.0.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/tablero_tpl/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/tablero_tpl/js/material.min.js" type="text/javascript"></script>
        <?php
        if (isset($css_files) && !empty(($css_files)))
        {
            foreach ($css_files as $key => $css)
            {
                echo css($css);
            }
        }
        if (isset($js_files) && !empty(($js_files)))
        {
            foreach ($js_files as $key => $js)
            {
                echo js($js);
            }
        }
        ?>
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
        <div id="overlay">
            <img src="<?php echo base_url(); ?>assets/tablero_tpl/img/loader.gif" alt="Loading" /><br/>
            Cargando...
        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div id="cores-modal">
<?php
if (isset($cuerpo_modal))
{
    echo $cuerpo_modal;
}
?>
                    </div>
                </div>
            </div>
        </div>

        <div class="wrapper">
            <div class="sidebar"
                 data-color="green"
                 data-image="<?php echo base_url(); ?>assets/tablero_tpl/img/Escultura-Ortiz.jpg">
                <!--
                    Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"
                    Tip 2: you can also add an image using data-image tag
                -->
                <div class="logo">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <img src="<?php echo base_url(); ?>assets/tablero_tpl/img/ces.png" />
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <img src="<?php echo base_url(); ?>assets/tablero_tpl/img/imss.png" />
                        </div>
                    </div>
                    <!--a href="http://www.creative-tim.com" class="simple-text">
                            Coordinación de Educación en Salud
                    </a-->
                </div>

                <div class="sidebar-wrapper">
                    <?php
                    if (isset($menu))
                    {
                        echo render_menu($menu);
                    }
                    ?>
                    <address class="">
                      <br><br><br>
                      <p style="text-align:center;"><b>Mesa de ayuda</b></p>
                      <p style="text-align:center; font-size:11px;">¿Tienes alguna duda? Comunícate con nosotros: </p>
                      <p style="text-align:center; font-size:11px;"><b>Teléfono:</b> 56 27 69 00 Ext. 21146, 21147 y 21148</p>
                      <p style="text-align:center; font-size:11px;"><b>Email:</b> soporte.cores@gmail.com &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</p>
                      <p style="text-align:center; font-size:11px;"><b>Horario:</b> de lunes a viernes, de 8h a 16h &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</p>
                    </address>
                </div>
            </div>

            <div class="main-panel">
                <nav class="navbar navbar-transparent navbar-absolute">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <img class="cores-logo" src="<?php echo base_url(); ?>assets/login/cores.png">
                        </div>
                        <div class="collapse navbar-collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <li>
                                    <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="material-icons">person</i>
                                        <p class="hidden-lg hidden-md">Profile</p>
                                    </a>

<?php
if (isset($perfil_usuario))
{
    echo $perfil_usuario;
}
?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <div class="content">
                    <div class="container-fluid">
                                <?php
                                if (isset($blank))
                                {
                                    ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                            <?php
                            echo $blank;
                            ?>
                                </div>
                            </div>
<?php } //fin blank zone ?>

                                    <?php
                                    if (isset($main_content))
                                    {
                                        ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="card">
                                            <?php
                                            if (isset($sub_title) && !empty($sub_title))
                                            {
                                                ?>
                                            <div class="card-header" data-background-color="">
                                                <h4 class="title">
                                                    <?php echo $sub_title; ?>
                                                </h4>
                                                <?php
                                                if (isset($descripcion) && !empty($descripcion))
                                                {
                                                    ?>
                                                    <p class="category">
                                                    <?php echo $descripcion ?>
                                                    </p>
                                                <?php } ?>
                                            </div>
        <?php
    }
    ?>
                                        <div class="card-content">
                            <?php
                            echo $main_content;
                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
<?php } //fin content card   ?>
                    </div>
                </div>

                <footer class="footer">
                    <div class="container-fluid">
                        <nav class="pull-left">
                            <ul>

                            </ul>
                        </nav>
                        <p class="copyright pull-right">
                            <script>document.write(new Date().getFullYear())</script>
                            <a style="text-decoration: underline;" href="http://educacionensalud.imss.gob.mx" target="_blank">Coordinación de Educación en Salud</a>
                        </p>

                        <p class="copyright pull-left">Este sitio se visualiza correctamente apartir Mozila Firefox 50 y Google Chrome 55.</p>
                    </div>
                </footer>
            </div>
        </div>
    </body>

    <!--  Notifications Plugin    -->
    <script src="<?php echo base_url(); ?>assets/tablero_tpl/js/bootstrap-notify.js"></script>

    <!-- Material Dashboard javascript methods -->
    <script src="<?php echo base_url(); ?>assets/tablero_tpl/js/material-dashboard.js"></script>

    <script src="<?php echo base_url(); ?>assets/js/general.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/help.js"></script>
<?php echo js('menu.js'); ?>


</html>
