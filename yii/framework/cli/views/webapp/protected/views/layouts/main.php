<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo Yii::app()->charset ?>" />
        <meta name="language" content="pt-BR" />

        <!-- blueprint CSS framework -->
        <!--[if lt IE 8]>
                <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
                <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/css/estiloGeral.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/css/estiloEspecifico.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/estilo_yii.css" />

        <?php
        $_jj = Yii::app()->getClientScript();
        $_jj->registerScriptFile(Yii::app()->request->baseUrl . '/js/menu.js');
        //$_jj->registerScriptFile(Yii::app()->request->baseUrl . '/js/formatacaoGenerica.js');
        $_jj->registerScriptFile(Yii::app()->request->baseUrl . '/js/init.js');
        $_jj->registerScriptFile(Yii::app()->request->baseUrl . '/js/lib/jquery.maskedinput-1.1.4.pack.js');
        $_jj->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.mask.min3.js');
        $_jj->registerScriptFile(Yii::app()->request->baseUrl . '/themes/js/Cronometro.js'); 
        ?>
        <style>
            .celula_destaque_vermelho{
                color: #FF0000;
            }
            .celula_destaque_preto{
                color: #000000;
            }
            .button-column img{
                width: 16px;
                height: 16px;
            }
        </style>
        <title><?php echo Yii::app()->name; ?></title>
    </head>

    <body>
        <div id="popupBoxFundo">&nbsp;<iframe src="javascript:false;"></iframe></div>
        <div id="popupBox">
            <div>
                <span class="indicadorCarregando" style="padding-bottom: 15px;">&nbsp;</span>
                Aguarde, o sistema está processando os dados.
            </div>
        </div>

        <div id="geral">
            <a name="topo"></a>
            <!-- Cabecalho do sistema -->
            <div id="cabecalho">
                <div id="marca">
                    <img alt="logo" src="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/img/logo.jpg" />
                </div>
                <!-- Menu Auxiliar -->
                <?php if( ($this->getViewFile('/layouts/_msg')) !==false)  echo $this->renderPartial('/layouts/_menu_aux'); ?>
                <div id="tituloSistema">
                   <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo_sistemas.jpg" /> 
                </div>
            </div>

            <!-- Menu Principal -->
            <?php
            echo $this->menu;
            ?>
        </div>

        <div id='wrapperConteudo'>
            <div id='conteudo'>

                <div id="pageTitle">
                    <h1><?php echo $this->titulo; ?></h1>
                    <h2><?php echo $this->subtitulo; ?></h2>

                    <div id="menuContexto">
                        <?php
                        $this->widget('zii.widgets.CMenu', array(
                            'items' => $this->menu_acao,
                            'activeCssClass' => 'ativo',
                        ));
                        ?>
                    </div>
                </div>

                <!-- mensagens> -->
                <?php if( ($this->getViewFile('/layouts/_msg'))!==false) echo $this->renderPartial('/layouts/_msg'); ?>
                <?php echo $content; ?>
            </div>
        </div>


    </body>
</html>
