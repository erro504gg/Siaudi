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
    $perfil = strtolower(Yii::app()->user->role); 
    $perfil = str_replace("siaudi2","siaudi",$perfil);    
    if($perfil!="siaudi_chefe_auditoria" && $perfil!="siaudi_gerente"){
        echo "<br><br><b><center>";
        echo "Funcionalidade disponível apenas para o Gerente e o Chefe de Auditoria";        
        echo "</center></b>";
    } else {
        
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();    
    $cs->registerScriptFile($baseUrl.'/js/Relatorio.js');        
    $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
        'id' =>'reiniciar_contagem'
            ));
        

?>
<div class="formulario" Style="width: 70%;">
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Reiniciar Contagem </legend>
        <div class='row'><font size="2">
            <?php if (!$_POST[reiniciar]){ ?>
                    Clicando no botão abaixo, a contagem dos itens do Relatório será reiniciada, <br>
                    começando do nº 1, a partir do <b>PRÓXIMO</b> Relatório que for homologado. 
                        </font><br><br>
                    <p align="center">
                        <input type="hidden" name="reiniciar" value="1">
                    <a class="imitacaoBotao" href="javascript:valida_reiniciarContagem();">Confirmar</a>            
                    </p>
                    <br><br>
            <?php } else { ?>
                    <br>   
                    Solicitação efetuada com sucesso! O <b>PRÓXIMO</b> Relatório de Auditoria <br>
                    que for homologado, terá a contagem dos itens reiniciada, começando do nº 1.<br><br>
            <?php } ?>
       </div>      
    </fieldset>
    <div style="height:5px;"></div>
    <div class="rowButtonsN1">
        <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Site/index'), array('class' => 'imitacaoBotao')); ?>
    </div>    
 
<?php 
$relatorio_reiniciar = RelatorioReiniciar::model()->findAll('st_reiniciar=false order by data DESC');
if(sizeof($relatorio_reiniciar)>0 && !$_POST[reiniciar] ){?>
    <br>
    <table class="tabelaListagemItensYii" style="width:200px;">
           <thead>
           <tr><th id="criterio-grid_c0" colspan="2">Solicitações anteriores </th></tr>
           <tr><th id="criterio-grid_c0">Data</th><th>Login</th></tr>           
           </thead>
           <tbody>
               <?php foreach($relatorio_reiniciar as $vetor){
                        $class=($class=="odd")? "even":"odd";
                        echo "<tr class='".$class."'>
                        <td width='30%' align=center>".$vetor->data."</td>
                        <td>".$vetor->login."</td>                
                        </tr>";         
                    } ?>
           </tbody>
   </table>
</div>    
<?php } ?>
</div>

<?php 
 $this->endWidget(); 

} ?>
