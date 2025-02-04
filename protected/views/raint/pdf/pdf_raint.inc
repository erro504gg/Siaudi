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
$model = new Raint('search');
        $baseUrl2 = "http://".$_SERVER["HTTP_HOST"] . Yii::app()->baseUrl;              
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['exercicio'])) {
            $dados = $model->findAllByAttributes(array("valor_exercicio" => $_GET['exercicio']));

            $pagina_retrato = Raint::model()->raint_gerar_pagina($baseUrl2,"P");
            $pagina_paisagem = Raint::model()->raint_gerar_pagina($baseUrl2,"L");
            
            $html ="
                
            <style type='text/css'>
            <!--
                    table.page_header {width: 100%; border: none; background-color: white; border-bottom: solid 1mm #FFFFFF; padding: 2mm }
                    table.page_footer {width: 100%; border: none; background-color: white; border-top: solid 1mm #COCOCO; padding: 2mm}
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
                    .page { font-size: 15px; 
                              margin-top: 5px;
                              font-weight: bold; 
                              }        
                    .nivel0 { font-size: 20px; 
                              color:#0E2F62; 
                              background-color: #CCCCCC; 
                              width:100%; 
                              margin-top: 20px;
                              font-weight: bold; 
                              }
                    .nivel1 { font-size: 15px; 
                              color:#0E2F62; 
                              margin-top: 20px;
                              font-weight: bold;                                   
                              }     
                    p, P {text-indent: 12.5mm; line-height:170%; margin: 0 0; font-family:Arial; font-size:12px; }
                    .th {background-color: #DBDBDB; padding:5px; font-size: 10px; vertical-align:middle; text-align:center;}
                    .even {background-color: #E8E8E8; padding:3px; font-size: 10px;   }                   
                    .odd {background-color: #FFFFFF; padding:3px; font-size: 10px;   }                           
                    .risco_irrelevante {
                        background-color: #86B686;
                        height:16px;  
                        font-size: 10px;
                    }

                    .risco_baixo {
                        background-color: #B7D4B7;
                        height:16px; 
                        font-size: 10px;
                    }

                    .risco_medio {
                        background-color: #FFEDA5;
                        height:16px;
                        font-size: 10px;
                    }

                    .risco_alto {
                        background-color: #FFD089;
                        height:16px;
                        font-size: 10px;
                    }

                    .risco_critico {
                        background-color: #CB7C7C;
                        height:16px;
                        font-size: 10px;
                    }          
                    
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
            $html.="<page  backtop='10mm' backbottom='0mm' backleft='5mm' backright='0mm' style='font-size: 12pt'>
            
                <div style='left: 0px; top: 0px; position: absolute; visibility: visible; z-index:10;'>               
                <img src=\"".$baseUrl2."/images/capa_raint.jpg\">
                </div>
                <div style='height: 50px; width: 50px; left: 155px; top: 48px; position: relative; visibility: visible; z-index:20;font-size:25pt;font-weight:bold;'>".$_GET['exercicio']."</div>
                <div style='height: 350px; width: 550px; left: 180px; top: 300px; position: relative; visibility: visible; z-index:30;font-size:30pt;font-weight:bold;text-align:center;color:#365F91'>RELATÓRIO ANUAL DE  <BR>
                    ATIVIDADES DA <BR>
                    AUDITORIA INTERNA <BR>
                    RAINT<BR></div>


            </page>";
            // conta o número de itens para gerar as páginas 
            // sem rodapé (limite de 37 itens por página)
            $numero_itens = sizeof($dados);
            if ($numero_itens>0){
                $paginas_indice = $numero_itens/37;
                $paginas_indice = (is_float($paginas_indice)) ? (int)$paginas_indice+1:$paginas_indice;
            }
          for( $i=1; $i<=$paginas_indice;$i++)  {
         $html.="<page pageset='old' >
                     <page_header>
                            <table class='page_header'>
                                    <tr>
                                            <td style='width:100%; text-align: center'>
                                                    <img src=\"".$baseUrl2."/images/logo.jpg\">
                                            </td>
                                    </tr>
                            </table>
                    </page_header>
             </page>";
          }            
          
        $html.= $pagina_retrato;
        
                foreach ($dados as $vetor){ 
                    $titulo = str_replace(array("<p>","</p>"),"",$vetor->nome_titulo); 
                    $titulo = str_replace('"','\"',$titulo);
                    
                    $texto = str_replace("../js",$baseUrl2."/js", $vetor->descricao_texto); 
                    
                    // retirando espaçamento da imagem
                    $texto = str_replace("<img ","<br><img ", $texto);                     
                    
                    // inserindo quebras de página
                    $texto = str_replace("<!-- pagebreak -->","<div style='page-break-after:always; clear:both'></div>", $texto);
                    
                    //trocando orientação da página: retrato / paisagem
                    $texto = str_replace("<p><span style=\"background-color: #a0a0a0;\">((Inserir_pagina_retrato))</span>&nbsp;</p>","</page>$pagina_retrato", $texto);
                    $texto = str_replace("<p><span style=\"background-color: #a0a0a0;\">((Inserir_pagina_paisagem))</span>&nbsp;</p>","</page>$pagina_paisagem", $texto);
                                          
                    // removendo fontes Andale Mono e Calibri
                    $texto = str_replace(array("andale mono,","calibri,","Calibri,","calibri","Calibri","sans-serif","sans-serif,"),"", $texto);         

                    $numeracao= $vetor->numero_pdf;     
                    $nivel = substr_count($numeracao, "."); 

                        $html.=   "
                                <bookmark title='{$numeracao} - {$titulo}' level='{$nivel}' ></bookmark>
                                <div class='nivel{$nivel}'> {$numeracao} - {$titulo} </div><br>
                                {$texto} 
                                ";

                }
                $html .="</page>";
                //echo $html; 
                }