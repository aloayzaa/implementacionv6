<!-- footer start (Add "light" class to #footer in order to enable light footer) -->
<!-- ================ -->
<footer style="margin-top: 10px; width: 100%; bottom: 0; position: fixed; z-index: 100;">
<!-- .footer start -->
<!-- ================ -->
<div class="subfooter">
    <div class="container">
        <div class="row">
            <div class="col-md-5 hidden-xs">
                <p><font color="white">Copyright © 2016 - 2017</font> <a href="<?php echo base_url(); ?>" style="text-decoration: none"><font color="#428bca">Anikama Group S.A.C.</font></a> <font color="white">All Rights Reserved</font></p>
            </div>
            
            <!--------- Mov footer start ---------->
            <div class="col-md-5 hidden-lg hidden-md hidden-sm">
                <p><font color="white" size="1px">Copyright © 2016 - 2017</font> 
                    <a href="<?php echo base_url(); ?>"><font color="#428bca" size="1px">ANIKAMA GROUP S.A.C.</font></a> <font color="white" size="1px">All Rights Reserved</font>
                </p>
            </div>
            <!---------- Mov footer end ----------->
            
            <div class="col-md-7 hidden-xs hidden-sm">
                <nav class="navbar navbar-default" role="navigation">
                    <!-- Toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-2">
                            <span class="sr-only">Menú</span>
                            <span class="icon-bar" style="background: #428bca"></span>
                            <span class="icon-bar" style="background: #428bca"></span>
                            <span class="icon-bar" style="background: #428bca"></span>
                        </button>
                    </div>   
                    <div class="collapse navbar-collapse" id="navbar-collapse-2">
                        <ul class="nav navbar-nav">
                            <li><a href="<?php echo base_url(); ?>"><font color="white">Inicio</font></a></li>
                            <li><a href="<?php echo base_url('#nosotros'); ?>"><font color="white">Nosotros</font></a></li>
                            <li><a href="<?php echo base_url('#servicios'); ?>" class="smooth" data-alt="#servicios"><font color="white">Servicios</font></a></li>
                            <li><a href="<?php echo base_url('#partners'); ?>" class="smooth" data-alt="#partners"><font color="white">Partners</font></a></li>
                            <li><a href="<?php echo base_url('eStore'); ?>"><font color="white">Tienda</font></a></li>
                            <li><a href="<?php echo base_url('blog_noticias'); ?>"><font color="white">Noticias</font></a></li>
                            <li><a href="<?php echo base_url('contactos'); ?>"><font color="white">Contactos</font></a></li>
                            <li><a href="<?php echo base_url('sitemap'); ?>"><font color="white">Mapa del Sitio</font></a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
</footer>
<!-- footer end -->