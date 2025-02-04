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
<?php

$model = $this->loadModel($id, 'Relatorio');
$baseUrl2 = "http://" . $_SERVER["HTTP_HOST"] . Yii::app()->baseUrl;
$perfil = strtolower(Yii::app()->user->role);
$perfil = str_replace("siaudi2", "siaudi", $perfil);
$login = Yii::app()->user->login;
//$model->unsetAttributes();  // clear any default values
$autorizar_pdf = Relatorio::model()->Relatorio_Autorizar_PDF($model->data_relatorio);

if ($autorizar_pdf) {

    $html = "        
            <style type='text/css'>
            <!--
                    table.page_header {width: 100%; border: none; background-color: white; border-bottom: solid 1mm #FFFFFF; padding: 2mm }
                    table.page_footer {width: 100%; border: none; background-color: white; border-top: solid 1px #COCOCO; padding: 2mm}
                    h1 {color: #000033}
                    h2 {color: #000055}
                    h3 {color: #000077}
                    div.standard{ padding-left: 5mm;}
                    .tabela_acao{width: 800px; border: 3px solid #858585; background-color: #E8E8E8; padding: 2mm}
                    .tabela_acao2{width: 800px; border-bottom: 1px solid #000000; border-right: 1px solid #000000;}                    
                    .th_acao {padding:5px; background-color: #EFEFEF; font-size: 11px; vertical-align:middle; text-align:center; font-weight: bold; border-top: 1px solid #000000; border-left: 1px solid #000000;}
                    .td_acao {padding:5px; font-size: 10px; vertical-align:top; text-align:justify;  border-top: 1px solid #000000; border-left: 1px solid #000000; }                    
                    .tabela_atributo2 {  margin-left:auto; margin-right:auto; }                    
                    .tabela_atributo {  border: 1px solid #444;  margin-left:auto; margin-right:auto; }
                    p, P {text-indent: 12.5mm; line-height:170%; left:0px; margin: 0 0; font-family:Arial; font-size:12px; }
                    .th {background-color: #DBDBDB; padding:5px; font-size: 10px; vertical-align:middle; text-align:center;}
                    .even {background-color: #E8E8E8; padding:3px; font-size: 10px;   }                   
                    .odd {background-color: #FFFFFF; padding:3px; font-size: 10px;   }
                    .labelCampo {
                            font-family: Arial, Helvetica, sans-serif;
                            font-size: 10px;
                            font-weight: bold;
                            color: #3B3B3B;
                    }

                    .labelCampo2 {
                            font-family: Arial, Helvetica, sans-serif;
                            font-size: 10px;
                            color: #3B3B3B;
                    }

                    .btnmenuContexto{
                            display:inline;
                            padding:0px 5px;
                            border:2px solid #dbdbdb;
                            margin:0px 2px 0px 0px;
                            background:#dbdbdb;
                    }

                    .linkSublinhado{
                            cursor:pointer;
                            cursor:hand;
                            text-decoration: underline; 
                            color:#3B3B3B;
                    }

                    .labelRelatorio{
                            font-family: Arial, Helvetica, sans-serif;
                            font-size: 11px;
                            font-weight: bold;	
                    }

                    .labelRelatorio2{
                            font-family: Arial, Helvetica, sans-serif;
                            font-size: 11px;
                    }

                    .labelRecomendacao{
                            font-family: Arial, Helvetica, sans-serif;
                            font-size: 10px;
                            font-weight: bold;
                    }

                    .labelSugestao{
                            font-family: Arial, Helvetica, sans-serif;
                            font-size: 10px;
                    }

                    .tabelaAcesso tr.zebra {
                            background-color: #f4f4f4;
                    }
                    
                    .tabelaAcesso tr:hover {background-color: #F1E7D8;}     
                    
                    table tr td p, 
                    table tr td  P,
                    table tbody tr td p, 
                    table tbody tr td  P,
                    table tbody tr p, 
                    table tbody tr P,
                    table tr p, 
                    table tr P {text-indent: 0mm; line-height:100%; left:0px; margin: 0 0; font-family:Arial; font-size:8px;}                    
            -->
            </style>";

    $html.=" <page  backtop='25mm' backbottom='30mm' backleft='10mm' backright='10mm' style='font-size: 12pt'>
                    <page_header>
                            <table class='page_header'>
                                    <tr>
                                            <td width=15></td>
                                            <td style='width:100%; text-align: left'>
                                                    <img src=\"" . $baseUrl2 . "/images/logo2.jpg\">
                                            </td>
                                    </tr>
                            </table>
                    </page_header>
                    <page_footer>
                            <table class='page_footer'>
                                    <tr>
                                            <td valign=top style='width:90%; text-align: center; font-size:11px;'>
                                                <i>Missão da Unidade de Auditoria Interna: \"Assegurar, preventinamente, o cumprimento das normas e da legislação pertinente.\" </i> <br>
                                                Relatório gerado por " . $login . " em " . date("d/m/Y") . ".
                                            </td>
                                            <td valign=top style='width:10%; text-align: center; font-size:11px;'>
                                                <span class='page'>[[page_cu]]/[[page_nb]]</span>
                                            </td>
                                    </tr>
                            </table>
                    </page_footer>";

    //$html.="</page><page pageset='old'>";





    $html.="<div align='center' class='labelRelatorio'>RELATÓRIO DE AUDITORIA ACOMPANHAMENTO DA GESTÃO Nº: " . $model->numero_relatorio . "
                                <br>DATA: " . $model->data_relatorio . "</div>                                    
                                <br><br><span class='labelRelatorio'>Do(s) Auditor(es):</span><br>
                                <span class='labelRelatorio2'>";
    $Relatorio_auditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk' => $model->id));
    foreach ($Relatorio_auditor as $vetor) {
        $Auditor = Usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
        $html.="<img src='" . $baseUrl2 . "/images/spacer.gif' height='1' width='40'>" . strtoupper($Auditor->nome_usuario) . "<br>";
    }
    $html.="</span><br><br>
                                <span class='labelRelatorio'>Ao Gerente da Auditoria:</span><br>
                                <span class='labelRelatorio2' >";
    $Relatorio_gerente = RelatorioGerente::model()->findAllByAttributes(array('relatorio_fk' => $model->id));
    foreach ($Relatorio_gerente as $vetor) {
        $Auditor = Usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
        $html.="<img src='" . $baseUrl2 . "/images/spacer.gif' height='1' width='40'>" . strtoupper($Auditor->nome_usuario) . "<br>";
    }

    $html.="</span> <Br><br>
                                             
                                <span class='labelRelatorio'>Senhor Gerente,</span><br>
                                <span class='labelRelatorio2' style='text-align:justify'>" .
            // substitui link da imagem
            str_replace("../../js", $baseUrl2 . "/js", $model->descricao_introducao)
            . "</span><br><br>
                            ";


    // Chama Capítulos - Itens e Recomendações 
    $Capitulos = Capitulo::model()->findAll(array('order' => 'numero_capitulo_decimal', 'condition' => 'relatorio_fk=' . $model->id));
    if (is_array($Capitulos) && sizeof($Capitulos) > 0) {
        foreach ($Capitulos as $vetor_capitulos) {
//            $html.="<span class='labelRelatorio'>" . $vetor_capitulos->numero_capitulo . " - " . $vetor_capitulos->nome_capitulo . "</span><br>
//                                        <span class='labelRelatorio2' style='text-align:justify'>" .
//                    // substitui link da imagem
//                    str_replace("../../js", $baseUrl2 . "/js", $vetor_capitulos->descricao_capitulo)
//                    . "</span><br><br>";

            $Itens = Item::model()->findAll(array('order' => 'numero_item', 'condition' => 'capitulo_fk=' . $vetor_capitulos->id));
            if (is_array($Itens) && sizeof($Itens) > 0) {
                foreach ($Itens as $vetor_itens) {
                    //mostrar somente os itens que o usuário tem acesso.
                    $login = Yii::app()->user->login;
                    $RelatorioAcessoItem = RelatorioAcessoItem::model()->findAll(array('condition' => 'item_fk='.$vetor_itens['id']. " and nome_login='".$login. "'"));
                    if (sizeof($RelatorioAcessoItem)) {
                        $html.="<span class='labelRelatorio'>" . $vetor_itens->numero_item . " - " . $vetor_itens->nome_item . "</span><br>
                                                <span class='labelRelatorio2' style='text-align:justify'>" .
                                // substitui link da imagem
                                str_replace("../../js", $baseUrl2 . "/js", $vetor_itens->descricao_item)
                                . "</span><br><br>";

                        $Recomendacoes = Recomendacao::model()->findAll(array('order' => 'id', 'condition' => 'item_fk=' . $vetor_itens->id));
                        if (is_array($Recomendacoes) && sizeof($Recomendacoes) > 0) {
                            $cont_recomendacao = 1;
                            foreach ($Recomendacoes as $vetor_recomendacoes) {
                                // se é uma recomendação, então imprime número sequencial (ex:1.1)
                                if ($vetor_recomendacoes->recomendacao_tipo_fk == RecomendacaoTipo::model()->find("nome_tipo ilike '%sugestão%'")->id) {
                                    $numero_recomendacao = $vetor_itens->numero_item . "." . $cont_recomendacao;
                                    $cont_recomendacao++;
                                } else {
                                    $numero_recomendacao = "";
                                }
                                // retira <p> e </p> do início e do final da descricao da recomendação
                                $texto_rec = $vetor_recomendacoes->descricao_recomendacao;
                                if (substr($texto_rec, 0, 3) == "<p>") {
                                    $texto_rec = substr($texto_rec, 3, strlen($texto_rec) - 7);
                                }
                                
                                $gravidade_recomendacao = RecomendacaoGravidade::model()->findByPk($vetor_recomendacoes->recomendacao_gravidade_fk);
                                $texto_rec = $texto_rec . " (Gravidade: " . $gravidade_recomendacao[nome_gravidade] . ".)";                                

                                $html.="<span class='labelRecomendacao' style='text-align:justify;'>" . $numero_recomendacao . " " .
                                        // substitui link da imagem
                                        str_replace("../../js", $baseUrl2 . "/js", $texto_rec)
                                        . "</span><br><br>";
                            }
                        }
                    }
                }
            }
        }
    }

    $html.="<br><br>
                                    <span class='labelRelatorio2' style='margin-left:auto;margin-right:auto;'>";
    $Relatorio_auditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk' => $model->id));
    foreach ($Relatorio_auditor as $vetor) {
        $Auditor = Usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
        $html.="<div align='center'>______________________________________________________<br>"
                . strtoupper($Auditor->nome_usuario) . "</div><br><br><br><br>";
    }
    $html.="</span>";

    //se relatório foi pré-finalizado então mostra despacho
    if (($model->nucleo == 't') && (($model->data_pre_finalizado) && ($model->login_pre_finaliza))) {
        $html.="<span class='labelRelatorio'>À Gerência de Auditoria, em " . $model->data_pre_finalizado . "</span><br><br>
                                    <span class='labelRelatorio2' style='text-align:justify'>";
        
        $Despacho = RelatorioDespacho::model()->findByAttributes(array('id' => 1));
        $html.= $Despacho->descricao_pre_finalizado . "</span>";

        $AuditorChefe = Relatorio::model()->RelatorioAuditorChefe($model->id, 'pre_finaliza');
        $html.="<span class='labelRelatorio2'><br><br><br>
                                <div width=100%><div align='center' >______________________________________________________<br>"
                . strtoupper($AuditorChefe[nome]) . "<br>";
        $cargo = "COORDENADOR";//($AuditorChefe[funcao] != "") ? "COORDENADOR" : "COORDENADOR SUBSTITUTO(A)";
        $html.="<span class='labelSugestao'>" . $cargo . "</span></div></div></span><br><br><br>";
    }

    // se relatório foi finalizado então mostra despacho
    if ($model->data_finalizado) {
        $html.="<span class='labelRelatorio'>À Auditoria Interna, em " . $model->data_finalizado . "</span><br><br>
                                    <span class='labelRelatorio2' style='text-align:justify'>";
        $Despacho = RelatorioDespacho::model()->findByAttributes(array('id' => 1));
        $html.= $Despacho->descricao_finalizado . "</span>";

        $AuditorChefe = Relatorio::model()->RelatorioAuditorChefe($model->id, 'finaliza');
        $html.="<span class='labelRelatorio2'><br><br><br>
                                <div width=100%><div align='center' >______________________________________________________<br>"
                . strtoupper($AuditorChefe[nome]) . "<br>";
        $cargo = "GERENTE DE AUDITORIA";//($AuditorChefe[funcao] != "") ? "GERENTE DE AUDITORIA" : "GERENTE DE AUDITORIA SUBSTITUTO(A)";
        $html.="<span class='labelSugestao'>" . $cargo . "</span></div></div></span><br><br><br>";
    }

    // se relatório foi homologado então mostra despacho
    if ($model->data_relatorio) {
        $html.="<span class='labelRelatorio'>Em " . $model->data_relatorio . "</span><br><br>
                                    <span class='labelRelatorio2'>";
        $Despacho = RelatorioDespacho::model()->findByAttributes(array('id' => 1));
        $html.= $Despacho->descricao_homologado . "</span>";

        $AuditorChefe = Relatorio::model()->RelatorioAuditorChefe($model->id, 'homologa');
        $html.="<span class='labelRelatorio' sytle='text-align:center;' align=center><br><br>______________________________________________________<br>"
                . strtoupper($AuditorChefe[nome]) . "<br>";
        $cargo = "CHEFE";//($AuditorChefe[funcao] != "") ? "CHEFE" : "CHEFE SUBSTITUTO(A)";
        $html.="<span class='labelSugestao'>AUDITORIA INTERNA <BR>" . $cargo . "</span></span><br><br><br>";
    }




    /*
      foreach ($dados as $vetor){
      $titulo = str_replace(array("<p>","</p>"),"",$vetor->nome_titulo);
      $titulo = str_replace('"','\"',$titulo);

      //$texto = str_replace("../js",$baseUrl2."/js", $vetor->descricao_texto);

      // retirando espaçamento da imagem
      $texto = str_replace("<img ","<br><img ", $texto);

      // inserindo quebras de página
      $texto = str_replace("<!-- pagebreak -->","<div style='page-break-after:always; clear:both'></div>", $texto);

      // limpa spam com fundo cinza
      $texto = str_replace("<span style=\"background-color: #a0a0a0;\">&nbsp;</span>","", $texto);

      $html.="<div class='nivel{$nivel}'> {$numeracao} - {$titulo} </div><br>
      {$texto}
      ";

      }
     */
    $html .="</page>";
} 
            