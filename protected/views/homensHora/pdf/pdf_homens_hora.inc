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
        $exercicio = $_GET["exercicio"];
        $model_homens_hora = HomensHora::model()->busca_tabela($exercicio);
        $model_homens_hora_conf = HomensHoraConf::model()->findByAttributes(array('valor_exercicio'=>$exercicio));        

           if (sizeof($model_homens_hora) > 0) {
               
                $html.=" <div><br>
                            <table>
                            <tr>
                            <td style='text-align:center;width:700px;'>
                             <b>Anexo 6 - IDENTIFICAÇÃO DE HOMENS / HORAS - AUDITORIA INTERNA</b>
                            </td>
                            </tr>
                            </table><br>
                            
                            <table class='tabela_atributo'>
                           <thead><tr>
                           <th class='th' width=230 rowspan=2>EMPREGADOS</th>
                           <th class='th' width=45 rowspan=2>HORAS /<BR> HOMEM (A)</th>                           
                           <th class='th'  colspan=4>IMPRODUTIVAS</th>
                           <th class='th' width=60 rowspan=2>TOTAL HORAS PRODUTIVAS <BR>(A-B)</th>
                           </tr><tr>
                           <th class='th' width=45 >FÉRIAS</th>
                           <th class='th' width=45 >LIC.PRÊM.</th>
                           <th class='th' width=45 >OUTROS</th>
                           <th class='th' width=45 >SUBTOTAL (B)</th>                           
                           </tr>
                           </thead>
                           <tbody>";                           
                    
                
                       $total_horas_homem = 0;
                       $total_ferias = 0;
                       $total_lic_premio = 0;
                       $total_outros = 0;
                       $total_total = 0;
                       
            foreach ($model_homens_hora as $vetor) {
                        $class= ($class=='odd')? "even":"odd";
                        $cargo = Cargo::model()->FindByAttributes(array('id'=>$vetor[perfil_fk]));

                $html.= "
                       <tr>
                       <td class='{$class}'  width=230>{$vetor[nome_usuario]}{$vetor[valor_asterisco]} </td>
                       <td class='{$class}'  width=45  style='text-align:center;'> {$vetor[valor_horas_homem]}</td>
                       <td class='{$class}'  width=45 style='text-align:center;'> {$vetor[valor_ferias]}</td>
                       <td class='{$class}'  width=45 style='text-align:center;'> {$vetor[valor_lic_premio]}</td>
                       <td class='{$class}'  width=45 style='text-align:center;'> {$vetor[valor_outros]}</td>
                       <td class='{$class}'  width=55 style='text-align:center;'> {$vetor[subtotal]}</td>
                       <td class='{$class}'  width=60  style='text-align:center;'> {$vetor[total]}</td>
                       </tr>";
                       
                       $total_horas_homem += $vetor[valor_horas_homem];
                       $total_ferias += $vetor[valor_ferias];
                       $total_lic_premio += $vetor[valor_lic_premio];
                       $total_outros +=  $vetor[valor_outros];
                       $total_subtotal +=  $vetor[subtotal];                       
                       $total_total +=$vetor[total];
            }

            $html.= "<tr>
                           <th class='th'></th>
                           <th class='th'>{$total_horas_homem}</th>
                           <th class='th'>{$total_ferias}</th>
                           <th class='th'>{$total_lic_premio}</th>
                           <th class='th'>{$total_outros}</th>
                           <th class='th'>{$total_subtotal}</th>                               
                           <th class='th'>{$total_total}</th>                           
                           </tr>                
                
                        </tbody>
                       </table><br>
                    </div> <br>";            
           }