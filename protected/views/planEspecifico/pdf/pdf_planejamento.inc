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
        $baseUrl2 = "http://".$_SERVER["HTTP_HOST"] . Yii::app()->baseUrl; 

        if (isset($model->id)) {
            $model_acao = Acao::model()->findByAttributes(array('id'=>$model->acao_fk));        
            $model_especie_auditoria = EspecieAuditoria::model()->findByAttributes(array('id'=>$model_acao->especie_auditoria_fk));                    
            $acao_numero = PlanEspecifico::model()->numero_acao_por_sureg($model_acao,$model->valor_sureg);
            $model_plan_especifico_auditor = PlanEspecificoAuditor::model()->findAllByAttributes(array('plan_especifico_fk'=>$model->id));                    
            $model_sureg = UnidadeAdministrativa::model()->findByAttributes(array('id'=>$model->valor_sureg));                                
            $model_sureg_substituicao = UnidadeAdministrativa::model()->findByAttributes(array('id'=>$model->unidade_administrativa_fk));
            $html ="
                
            <style type='text/css'>
            <!--
                    table.page_header {width: 100%; border: none; background-color: white; border-bottom: solid 1mm #FFFFFF; padding: 2mm; margin-bottom:20px; }
                    table.page_footer {width: 100%; border: none; background-color: white; border-top: solid 1px #COCOCO; padding: 2px}
                    h1 {color: #000033}
                    h2 {color: #000055}
                    h3 {color: #000077}

                    .tabela_acao{width: 800px; border: 3px solid #858585; background-color: #E8E8E8; padding: 2mm}
                    .tabela_acao2{width: 800px; border-bottom: 1px solid #000000; border-right: 1px solid #000000;}                    
                    .th_acao {padding:5px; background-color: #EFEFEF; font-size: 11px; vertical-align:middle; text-align:center; font-weight: bold; border-top: 1px solid #000000; border-left: 1px solid #000000;}
                    .td_acao {font-size: 10px; vertical-align:top; text-align:justify;  border-top: 1px solid #000000; border-left: 1px solid #000000;  }
                    .tabela_atributo2 {  margin-left:auto; margin-right:auto; }                    
                    .tabela_atributo {  border: 1px solid #444;  margin-left:auto; margin-right:auto; }
                    p, P {text-indent: 12.5mm; line-height:170%; margin: 0 0; font-family:Arial; font-size:12px; }
            		li {text-indent: 12.5mm; line-height:170%; margin: 0 0; font-family:Arial; font-size:12px; padding:5px;}
            		td p {text-indent: 0mm; line-height:170%; margin: 0 0; font-family:Arial; font-size:12px; }                     
            		.th {background-color: #DBDBDB; padding:5px; font-size: 10px; vertical-align:middle; text-align:center;}
            		
                    .page { font-size: 15px; 
                              margin-top: 5px;
                              font-weight: bold; 
                              }        
                    .nivel0 { font-size: 14px; 
                              color:#0E2F62; 
                              background-color: #CCCCCC; 
                              width:705px; 
                              margin-top: 20px;
                              font-weight: bold;
                              padding:3px;
                              }
 
                    .even {background-color: #E8E8E8; padding:3px; font-size: 10px;   }
                    .even2 {background-color: #F8F8F8; padding:3px; font-size: 10px;   }                    
                    .odd {background-color: #FFFFFF; padding:3px; font-size: 10px;   }
                    
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
            
         $html.=" <page  backtop='30mm' backbottom='30mm' backleft='6mm' backright='10mm' style='font-size: 10pt'>
                    <page_header>
                            <table class='tabela_atributo' align=center>
                                    <tr>
                                            <td style='width:70px; text-align: center;padding:5px;'>
                                                    <img src=\"".$baseUrl2."/images/logo_vertical.jpg\">
                                            </td>
                                            <td class='even2' style='width:600px; text-align: center'><b><span style='font-size:15px'>
                                            PLANEJAMENTO DE AUDITORIA - ". 
                                            strtoupper(strtr($model_especie_auditoria->nome_auditoria,"ã,ç,á,ê,é,õ,ô,ó","Ã,Ç,Á,Ê,É,Õ,Ô,Ó")) ." - ".
                                            strtoupper($model_especie_auditoria->sigla_auditoria)."</span><br><br>
                                           <span style='font-size:14px'>
                                            AÇÃO Nº ". $acao_numero .": ".$model_acao->nome_acao . "
                                            </span></b>
                                            </td>
                                    </tr>
                            </table>
                    </page_header>
                    <page_footer>
                            <table class='page_footer'>
                                    <tr>
                                            <td style='width: 100%; text-align: center'>
                                                    <span class='page'>[[page_cu]]/[[page_nb]]</span>
                                            </td>
                                    </tr>
                            </table>
                    </page_footer>
                    <table border=1>
                            <tr>
                                    <td style='width: 80px; text-align: center; '>Auditores: </td>
                                    <td style='width:500px; text-align: left;padding:5px;'>";
                                    foreach ($model_plan_especifico_auditor as $vetor){
                                       $model_auditor = Usuario::model()->findByAttributes(array('id'=>$vetor->usuario_fk));
                                       $html.=   "&nbsp; &nbsp; &nbsp; &nbsp; ".  $model_auditor->nome_usuario . "<br>";
                                    }
                             



                        $html.=     "</td>
                                    <td style='width: 80px; text-align: center'> {$model->data_log_formatado}</td>
                            </tr>
                    </table><br>
                    <table class='tabela_atributo'>
                            <tr>
                                    <td style='width: 700px; text-align: center; '>";
                                    
                                     if($model->unidade_administrativa_fk) { $html.= $model_sureg_substituicao->nome; }
                                                    else { $html.=  $model_sureg->nome; }
                                                    
                     $html.= " </td>
                            </tr>
                    </table>

                <div class='nivel0'> 1 - APRESENTAÇÃO </div><br>
                <p align=justify>{$model_acao->descricao_apresentacao}</p>

                
                <div class='nivel0'> 2 - OBJETIVO DA AÇÃO</div><br>
                <p align=justify>{$model_acao->descricao_objetivo}</p>
                    

                <div class='nivel0'> 3 - ESCOPO DA AÇÃO</div><br>
                <p align=justify>{$model->escopo_acao}</p>

                <div class='nivel0'> 4 - REPRESENTATIVIDADE E AMPLITUDE</div><br>
                 {$this->FiltrarTextoPDF($model->observacao_representatividade)}

                <div class='nivel0'> 5 - AMOSTRAGEM</div><br>
                 {$this->FiltrarTextoPDF($model->observacao_amostragem)}
                    
                <div class='nivel0'> 6 - QUESTÕES MACRO SOBRE A AÇÃO</div><br>
                 {$this->FiltrarTextoPDF($model->observacao_questoes_macro)}

                <div class='nivel0'> 7 - RESULTADOS ESPERADOS COM A AÇÃO</div><br>
                 {$this->FiltrarTextoPDF($model->observacao_resultados)}
                    
                <div class='nivel0'> 8 - LEGISLAÇÃO</div><br>
                 {$this->FiltrarTextoPDF($model->observacao_legislacao)}
                    
                <div class='nivel0'> 9 - DETALHAMENTO DOS PROCEDIMENTOS DE AUDITORIA</div><br>
                 {$this->FiltrarTextoPDF($model->observacao_detalhamento)}
                    
                <div class='nivel0'> 10 - TÉCNICAS DE AUDITORIA</div><br>
                 {$this->FiltrarTextoPDF($model->observacao_tecnicas_auditoria)}
                    
                <div class='nivel0'> 11 - EXISTEM PENDÊNCIAS JUNTO AOS ÓRGÃOS DE CONTROLE (CGU E TCU)</div><br>
                 {$this->FiltrarTextoPDF($model->observacao_pendencias)}
                    
                <div class='nivel0'> 12 - CUSTOS</div><br>
                 {$this->FiltrarTextoPDF($model->observacao_custos)}

                <div class='nivel0'> 13 - CRONOGRAMA</div><br>
                 {$this->FiltrarTextoPDF($model->observacao_cronograma)}
            <br>
            <table class='tabela_atributo' >
            <tr>
                <td class='th_acao' width=460>ASSINATURA DO(S) AUDITOR(ES)</td>
                <td class='th_acao'  width=200>DATA</td>
            </tr>
            <tr>
                <td class='td_acao'  style='font-size:35px;color:white;'>a</td>
                <td class='td_acao' >&nbsp;</td>
            </tr>            
            </table>
            
            <br><br>
            <table class='tabela_atributo' >
            <tr>
                <td class='th_acao' width=460>ASSINATURA DO GERENTE DA AUDITORIA</td>
                <td class='th_acao'  width=200>DATA</td>
            </tr>
            <tr>
                <td class='td_acao'  style='font-size:35px;color:white;'>a</td>
                <td class='td_acao' >&nbsp;</td>
            </tr>            
            </table>
            
            <br>
            <table class='tabela_atributo' >
            <tr>
                <td class='th_acao' width=460>APROVAÇÃO DO CHEFE DA UNIDADE DE AUDITORIA INTERNA</td>
                <td class='th_acao'  width=200>DATA</td>
            </tr>
            <tr>
                <td class='td_acao'  style='font-size:35px;color:white;'>a</td>
                <td class='td_acao' >&nbsp;</td>
            </tr>            
            </table>
            </page>";
                 
        // limpando tags não aceitas no componente HTML2PDF
            $html = str_replace(array("font-weight: normal;","line-height: 150%","<colgroup>","</colgroup>","<dl>","<dd>","</dl>","</dd>"),"",$html);
            $html = str_replace("<<","<",$html);
            $html = str_replace(">>",">",$html);
            $html = str_replace(array("Times New Roman","times new roman","Arial Narrow"),"Arial",$html);
            

         }