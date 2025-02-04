<?php
/********************************************************************************
*  Copyright 2015 Conab - Companhia Nacional de Abastecimento                   *
*                                                                               *
*  Este arquivo é parte do Sistema SIAUDI.                                      *
*                                                                               *
*  SIAUDI  é um software livre; você pode redistribui-lo e/ou                   *
*  modificá-lo sob os termos da Licença Pública Geral GNU conforme              *
*  publicada pela Free Software Foundation; tanto a versão 2 da                 *
*  Licença, como (a seu critério) qualquer versão posterior.                    *
*                                                                               *
*  SIAUDI é distribuído na expectativa de que seja útil,                        *
*  porém, SEM NENHUMA GARANTIA; nem mesmo a garantia implícita                  *
*  de COMERCIABILIDADE OU ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA.                *
*  Consulte a Licença Pública Geral do GNU para mais detalhes em português:     *
*  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt                     *
*                                                                               *
*  Você deve ter recebido uma cópia da Licença Pública Geral do GNU             *
*  junto com este programa; se não, escreva para a Free Software                *
*  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    *
*                                                                               *
*  Sistema   : SIAUDI - Sistema de Auditoria Interna                            *
*  Data      : 05/2015                                                          *
*                                                                               *
********************************************************************************/
?>
<?
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl.'/js/Resposta.js');  
    
?>
<script type="text/javascript">

    function disparaDownload(resposta_fk) {
        window.open('<?= CController::createUrl("Resposta/BaixarAnexoAjax"); ?>?resposta_fk='+ resposta_fk)
    }

</script>

<div class="formulario" Style="width: 70%;">
    <?php
    $recomendacao = Recomendacao::model()->findByAttributes(array('id' => $model_recomendacao->id));
    $item = Item::model()->findByAttributes(array('id' => $recomendacao->item_fk));
    $capitulo = Capitulo::model()->findByAttributes(array('id' => $item->capitulo_fk));
    $relatorio = Relatorio::model()->findByAttributes(array('id' => $capitulo->relatorio_fk));
    
    $valor = $item->valor_nao_se_aplica ? "Não se aplica." : "R$ ". $item->valor_reais;

    include_once(Yii::app()->basePath . '/../js/resposta/tiny-mce-js.jsp');
    $form = $this->beginWidget('GxActiveForm', array(
        'id' => 'resposta-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data')
    ));

    echo $form->errorSummary($model);


    //verifica se usuário tem acesso a este follow-up
    // (pois o ID do relatório pode ser trocado no GET
    $relatorios_autorizados = Resposta::model()->FollowUp_combo();
    foreach ($relatorios_autorizados as $ids => $values) {
        if ($relatorio->id == $ids) {
            $acesso_autorizado = 1;
        }
    }
    
    
    // verifica se o usuário é um cliente item
    // e se esse item pode ser acessado
    $perfil = strtolower(Yii::app()->user->role); 
    $perfil = str_replace("siaudi2","siaudi",$perfil);       
    if ($perfil=="siaudi_cliente_item"){
        $item_autorizado = Resposta::model()->Item_autorizado($item->id);
        $acesso_autorizado = $item_autorizado;
    }
    

    // verifica se recomendação é uma sugestão
    // (em caso afirmativo, não entra no follow-up)
    $recomendacao_tipo = $recomendacao->recomendacao_tipo_fk;
    if ($recomendacao_tipo == RecomendacaoTipo::model()->find("nome_tipo ilike '%sugestão%'")->id) {
        $acesso_autorizado = 0;
    }

    if (!$acesso_autorizado) {
        echo "<br><Br><center><b><font face=Verdana size=2>Acesso negado.</font></b></center>";
    } else {



        $perfil = strtolower(Yii::app()->user->role);
        $perfil = str_replace("siaudi2", "siaudi", $perfil);

        // pega último status do follow up
        $status = Resposta::model()->FollowUp_Ultimo_Status($model_recomendacao->id, 1);
        if ($status == "") {
            $status = 1; // pendente 
        }

        $data_termino = Feriado::model()->DiasUteis($relatorio->data_relatorio, $relatorio->valor_prazo);
        echo"
    <fieldset class='visivel' align='center'>
        <legend class='legendaDiscreta'> Dados do Relatório</legend>
        <center>
        <b>Nº Relatório</b>: " . $relatorio->numero_relatorio . " 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <b>Data</b>: " . $relatorio->data_relatorio . "
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <b>Nº Capítulo</b>: " . $capitulo->numero_capitulo . "
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <b>Nº Item</b>: $item->numero_item
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <b>Nº Recomendacao</b>: " . $item->numero_item . "." . $recomendacao->numero_recomendacao . " <br>
        <b>Prazo</b>: " . $relatorio->valor_prazo . " (dias úteis) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <b>Data Término</b>: " . $data_termino . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        if ($status != "3" && $status != "4") {
            $data_americana = explode("/", $relatorio->data_relatorio);
            $data_americana = $data_americana[2] . "-" . $data_americana[1] . "-" . $data_americana[0];
            $dias_restantes = Feriado::model()->dias_uteis_restantes($data_americana, $relatorio->valor_prazo);
            $css_dias_restantes = ($dias_restantes <= 0) ? "style=color:#FF3333;font-weight:bold" : "";
            echo "<b>Prazo restante:</b> <span $css_dias_restantes > $dias_restantes (dias úteis) </span>";
        }
        
        // troca diretório das imagens do sistema
        $descricao_item = str_replace("src=\"/imgitem/","src=\"../../imgitem/",$item->descricao_item);
        $descricao_item = str_replace("src=\"imgitem/","src=\"../../imgitem/",$descricao_item);
        
        
        echo "</center></fieldset>   
    <br>
    
    <fieldset class='visivel'>
        <legend class='legendaDiscreta'> Item</legend>
        <b>Nome</b>: " . $item->nome_item . "<br> 		
        <b>Valor</b>: " . $valor . "<br><Br>
        <b>Descrição</b>: " . $descricao_item . "
     </fieldset>  
    <br>


    <fieldset class='visivel'>
           <legend class='legendaDiscreta'> Recomendação</legend>" .
        $recomendacao->descricao_recomendacao . "
        </fieldset>  
       <br>";

                // busca no sistema legado (tb_imagem) se existe
                // anexo para esta resposta. (o anexo do sistema legado era gravado
                // no diretório, enquanto o anexo no sistema novo é gravado no banco).
                $anexo_legado = Imagem::model()->findAllByAttributes(array("recomendacao_fk" => $recomendacao->id,), array("order" => "arquivo_imagem ASC"));
                if (sizeof($anexo_legado)>0) {
                    // orderna array para formato natural
                    natcasesort($anexo_legado);
                    echo"<fieldset class='visivel'>
                        <legend class='legendaDiscreta'><b>Anexos da versão anterior do Siaudi</b></legend>";                    
                    foreach ($anexo_legado as $vetor){
                        $url_arquivo ="../../imgrec/" . $vetor[arquivo_imagem];
                        echo "<a href='$url_arquivo' target='_blank'>
                            <img src='../../images/download.gif' align=left border=0> &nbsp; 
                            {$vetor[arquivo_imagem]}</a><br><br>";
                    }  
                  echo"</fieldset><br>";
                }
        
        $Respostas = Resposta::model()->findAll('recomendacao_fk=' . $model_recomendacao->id . ' ORDER BY id ASC');
        if (sizeof($Respostas) > 0) {
            echo"<fieldset class='visivel'>
                <legend class='legendaDiscreta'> Respostas</legend>";
            foreach ($Respostas as $vetor) {
                $vetor_status = "";
                if ($vetor->tipo_status_fk) {
                    $vetor_status = TipoStatus::model()->findbyPk($vetor->tipo_status_fk);
                    $vetor_status = " como " . $vetor_status->descricao_status;
                }
                
                
                if ($vetor->nome_arquivo) {
                    $nome_arquivo_full = $vetor->nome_arquivo . "." . $vetor->tipo_arquivo;
                    echo"<fieldset class='visivel'>
                        <legend class='legendaDiscreta'><b>De {$vetor->id_usuario_log} em {$vetor->data_resposta} {$vetor_status}</b></legend>
                        {$vetor->descricao_resposta}
                        <fieldset class='visivel'>
                            <legend class='legendaDiscreta'><b>Anexo</b></legend>
                            <a onclick='javascript:disparaDownload({$vetor->id})' >
                            <img src='../../images/download.gif' align=left> &nbsp; 
                            {$nome_arquivo_full}
                            </a>
                        </fieldset>
                    </fieldset><br>";
                } else {
                    echo"<fieldset class='visivel'>
                        <legend class='legendaDiscreta'><b>De {$vetor->id_usuario_log} em {$vetor->data_resposta} {$vetor_status}</b></legend>
                        {$vetor->descricao_resposta}
                    </fieldset><br>";
                }
            }
            echo"</fieldset>  
            <br>";
        }

        $autoriza_resposta = 0;
        // verifica se pode abrir resposta
        // regras: status deve ser diferente de baixado ou solucionado
        // perfil deve ser 1 desses: auditor, gerente, coordenador, cliente,
        // cliente_item, diretor_difin, diretor_dirab, diretor_dipai, diretor_dirad
        if ($perfil == "siaudi_auditor" || $perfil == "siaudi_gerente" || $perfil == "siaudi_gerente_nucleo" || $perfil == "siaudi_chefe_auditoria" ||
                $perfil == "siaudi_cliente" || $perfil == "siaudi_cliente_item" || 
                  ((string) strpos($perfil, "siaudi_diretor") === (string) 0)) {

            $autoriza_resposta = 1;
        }
        // se status baixado ou solucionado, então não pode responder
        if ($status == "3" || $status == "4") {
            $autoriza_resposta = 0;
            if ($status == 3) {
                $recomendacao_mensagem = "Solucionada";
            }
            if ($status == 4) {
                $recomendacao_mensagem = "Baixada";
            }
            $resposta = Resposta::model()->findByAttributes(array('recomendacao_fk' => $model_recomendacao->id, "tipo_status_fk" => $status));
            echo "<table border='0' align='center' cellpadding='2' cellspacing='6' class='labelCampo2'>
                <tr>
                        <td align='center'>
                                <fieldset class='visivel'>
                                        <strong><i>Recomendação {$recomendacao_mensagem} em {$resposta->data_resposta}.</i></strong>
                                </fieldset>
                        </td>
                </tr>
            </table><br>
            <center>" .
            	CHtml::link(Yii::t('app', 'Voltar'), $this->createUrl('Resposta/index?' . $_SERVER['QUERY_STRING']), array('class' => 'imitacaoBotao')); 
            "</center>";
        }
        if ($autoriza_resposta) {
            ?>
            <fieldset class="visivel" style="width:860px;">
                <legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>

                <?php
                // troca do status do follow-up só é permitida
                // para auditor, gerente e coordenador
                if ($perfil == "siaudi_gerente" || $perfil == "siaudi_chefe_auditoria" || $perfil == "siaudi_auditor"|| $perfil == "siaudi_gerente_nucleo") {
                    ?>        
                    <div class='row'>
                        <div class='label' style="width:95px;"><?php echo $form->labelEx($model, 'tipo_status_fk'); ?></div>
                        <div class='field'><?php
                            $model->tipo_status_fk = $status;
                            echo $form->dropDownList($model, 'tipo_status_fk', GxHtml::listDataEx(TipoStatus::model()->findAllAttributes(null, true, array('order' => 'descricao_status'))));
                            ?></div>
                    </div><!-- row -->
                <? } ?>

                <div class='row'>
                    <div class='label' style="width:95px;"><?php echo $form->labelEx($model, 'descricao_resposta'); ?></div>
                    <div class='field'>
                        <?php echo $form->textArea($model, 'descricao_resposta', array('cols' => '60', 'rows' => '5', 'class' => 'tinymce_advanced')); ?>
                    </div>
                </div><!-- row -->
                <?php 
                    /*if ($perfil == "siaudi_cliente" || $perfil == "siaudi_cliente_item" || $perfil == "siaudi_diretor"):*/
                ?>
                <fieldset class="visivel">
                    <legend class="legendaDiscreta"> Anexo </legend>

                    <div class='row'>
                        <div class='label'><?php echo $form->label($model, 'anexo'); ?></div>
                        <div class='field'>

                            <?php
                            $this->widget('CMultiFileUpload', array(
                                'name' => 'attachment',
                                'model' => $model,
                                'attribute' => 'anexo',
                                'accept' => '*',
                                'max' => 1,
                                'remove' => '<img style="vertical-align:bottom;" alt="Excluir Tramite" src="' . Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . "/img/excluir.gif" . '">',
                            ));
                            ?>


                        </div>
                    </div>
                    <br />

                </fieldset>
                <?php /*endif;*/ ?>
            </fieldset>
    <div id="pdf_download"></div>
            <p class="note">
                <?php echo Yii::t('app', 'Fields with'); ?>
                <span class="required">*</span>
                <?php echo Yii::t('app', 'are required'); ?>.
            </p>
            <div class="rowButtonsN1">
                <input type="hidden" name="numero_recomendacao" value="<?php echo $item->numero_item . "." . $recomendacao->numero_recomendacao; ?>">
                <input type="hidden" name="recomendacao_fk" value="<?php echo $model_recomendacao->id; ?>">
                <input type="hidden" name="relatorio_fk" value="<?php echo $relatorio->id; ?>">
                <?php /* echo GxHtml::submitButton(Yii::t('app', 'Confirm'), array('class' => 'botao')); */ ?>
                <?php echo CHtml::link(Yii::t('app', 'Confirmar'), 'javascript:validaFollowUp()', array('class' => 'imitacaoBotao')); ?>
                <?php echo CHtml::link(Yii::t('app', 'Cancel'), 'javascript:history.back()', array('class' => 'imitacaoBotao')); ?>
                <?php echo CHtml::link(Yii::t('app', 'Voltar'), $this->createUrl('resposta/index?' . $_SERVER['QUERY_STRING']), array('class' => 'imitacaoBotao')); ?>
            </div>
            <?php
        }
    }
    $this->endWidget();
    ?>
</div><!-- form -->
