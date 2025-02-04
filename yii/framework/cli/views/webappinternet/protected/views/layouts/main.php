<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=<?php echo Yii::app()->charset ?>" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <!--[if IE]>
                <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/css/estiloIE.css" />
                <style>
                        * html body {behavior:url("<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/css/csshover.htc");}
                </style>
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/css/reset.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/css/estiloGeral.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/css/estiloEspecifico.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/estilo_yii.css" />
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/css/design.css" type="text/css" media="screen" />

        <?php
        $_jj = Yii::app()->getClientScript();
        //$_jj->registerScriptFile(Yii::app()->request->baseUrl . '/js/IE8.js');
        $_jj->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.pngFix.js');
        $_jj->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.mask.min3.js');
//        $_jj->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery-1.8.2.js');
//        $_jj->registerScriptFile(Yii::app()->request->baseUrl . '/js/lib/gmap3.min.js');
//        $_jj->registerScriptFile(Yii::app()->request->baseUrl . '/js/lib/gmap3.js');
        ?>
        <script type="text/javascript">
            $(document).ready(function() { // para permitir que o I.E. mostre corretamente imagens .PNG
                $(document).pngFix();
            });
        </script>
        <style>
            body {
                color: #333333;
                font-family: Arial,Verdana,"Trebuchet MS",sans-serif;
                font-size: 12px;
            }
            .zebra1 {background-color: #FFFFFF;}
            .zebra2 {background-color: #F4F4F4;}
        </style>
    </head>
    <body>
        <div class="container">
            <div class="clearfix">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo_topo.gif" width="960" height="89" />
            </div>

            <div id="wrapperConteudo">
                <?php echo $content; ?>
            </div><!-- fecha wrapperConteudo -->		
        </div> <!-- fecha container -->
    </body>
</html>	
