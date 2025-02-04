<?php 
    $cidade = $data->codcid !== null ? Municipio::model()->find("CodCid = $data->codcid")->NomCid : null;
    $endereco_credenciado = str_replace("'", "", $data->endoem);
?>
<div class="<?php echo $index % 2 ? 'zebra1' : 'zebra2' ?>">
    <div>
        <b><?php echo $data->nomoem; ?></b>
        <?php
//        echo CHtml::link('MAPA 001 ', $this::createUrl('site/testeAjax'), array('target' => '_blank')) . '&nbsp;';
        echo CHtml::ajaxLink(Yii::t('app', 'Mapa'), $this::createUrl('Auxiliar/RecuperarEnderecoAjax/', array('cep' => CMask::removeMascara($data->codcep))), array(
            'dataType' => 'json',
            'success' => "function (data) {
                            var obj = jQuery.parseJSON(data);
                            var endereco = (obj && obj.logradouro) ? obj.logradouro : '" . $endereco_credenciado . "';
                            var localidade = (obj && obj.localidade) ? obj.localidade : '" . $cidade . "';
                            var url = '" . $this::createUrl('/site/mapaAjax') . "?endereco=' + endereco + '&localidade=' + localidade + '&telefone=" . $data->numtel . "' + '&credenciado=" . $data->nomoem. "';
                            $('#iframeshowIntroDialog_$index').attr('src', url);
                            $('#dialog_mapa_" . $index . "').dialog('open');
                          }"
                ), array(
            'id' => 'bt_abrir_mapa_' . $index,
        ));
        ?>
    </div>
    <div>
        <?php echo $endereco_credenciado; ?>
    </div>
    <div>
        Telefone(s): <?php echo $data->numtel; ?>
    </div>
    <div>
        <?php echo $cidade ?>
    </div>
    <?php if (!empty($data->hompag)): ?>
        <div>
            Site: 
            <?php
            $is_www = strstr($data->hompag, 'www');
            if ($is_www == true) {
                echo CHtml::link($data->hompag, 'http://' . $data->hompag, array('target' => '_blank'));
            } else {
                echo $data->hompag;
            }
            ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($data->emaemp)): ?>
        <div>
            E-mail: <?php echo $data->emaemp; ?>
        </div>
    <?php endif; ?>
    <div style="text-align: justify;">
        <b>
            <?php
            if (strlen($data->especialidades_credenciado) > 120):
                echo '<span class="linha_credenciado">' . substr($data->especialidades_credenciado, 0, 120) . ' <span style="color: blue; cursor: pointer;">Mais...</span></span>';
            ?>
            <div id="texto_completo_<?php echo $index ?>" style="display: none;"><?php echo $data->especialidades_credenciado; ?></div>
            <?php
            else:
                echo $data->especialidades_credenciado;
            endif;
            ?>
        </b>
    </div>
    <hr>
</div>
<?php
$this->beginWidget('application.common.extensions.jui.EDialog', array(
    'name' => 'dialog_mapa_' . $index,
    'htmlOptions' => array('title' => 'Mapa'),
    'options' => array(
        'autoOpen' => false,
        'modal' => true,
        'title' => $data->nomoem,
        'height' => 600,
        'width' => 600,
    ),
    'buttons' => array(
        "Fechar" => 'function(){$(this).dialog("close");}',
    ),
    'theme' => 'base',
));
?>
<iframe value="0" style="width: 100%; height: 450px;" title="mapa" class="iframeDialog" id="iframeshowIntroDialog_<?php echo $index ?>" frameborder="0" scrolling="no" src=""></iframe>
<?php $this->endWidget('application.common.extensions.jui.EDialog'); ?>
