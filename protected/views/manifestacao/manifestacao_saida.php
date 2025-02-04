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
<?php echo $this->renderPartial('/layouts/_dialogo_view'); ?>

<?php
    // carrega tela de consulta conforme perfil do usuário 
    if (!$id){
        $perfil = strtolower(Yii::app()->user->role);
        $perfil = str_replace("siaudi2","siaudi",$perfil);
        $arquivo = "_search_manifestacao_saida_auditor"; // arquivo padrão; 
        if ($perfil=="siaudi_cliente") { $arquivo = "_search_manifestacao_saida_cliente"; }
        if ($perfil=="siaudi_gerente" || $perfil=="siaudi_chefe_auditoria") { $arquivo = "_search_manifestacao_saida_gerente"; }        
        $this->renderPartial($arquivo, array('model' => $model,));
        
    // carrega tela com o relatório
    }else {

    // pega dados sobre a manifestação, relatório, perfil e login do usuário
    $Manifestacao = Manifestacao::model()->findByAttributes(array('id'=>$id));
    $Relatorio= Relatorio::model()->findByAttributes(array('id'=>$Manifestacao->relatorio_fk));
    $Especie_auditoria = EspecieAuditoria::model()->findByAttributes(array('id'=>$Relatorio->especie_auditoria_fk));
    $UnidadeAdministrativa = UnidadeAdministrativa::model()->findByAttributes(array('id'=>$Manifestacao->unidade_administrativa_fk)); 
    
    $perfil = strtolower(Yii::app()->user->role);      
    $perfil = str_replace("siaudi2","siaudi",$perfil);    
    $login = strtolower(Yii::app()->user->login);  
    
    
    // Verifica se manifestação (id) existe. 
   if(sizeof($Manifestacao)==0){
        echo "<br><br><div align=center><b>Manifestação não encontrada. </b></div>";            
        $manifestacao_erro=1; 
   }       

   // Verifica se usuário tem permissão para acessar este relatório de acordo com perfil 
   
   // regras para clientes
   if($perfil=="siaudi_cliente"){    
        $RelatorioAcesso = RelatorioAcesso::model()->findByAttributes(array('relatorio_fk'=>$Relatorio->id,'nome_login'=>$login));
        if(sizeof($RelatorioAcesso)>0){       
             $autorizado=1;       
        }           
    }  
            
    //regras de acesso para auditores
   if($perfil=="siaudi_auditor"){    
         // criar regras
         $autorizado=1;       
   }

    //regras de acesso para gerentes
   if($perfil=="siaudi_gerente" || $perfil=="siaudi_chefe_auditoria"){    
         // criar regras
         $autorizado=1;       
   }   
    
    if(!$autorizado){ echo "<br><br><center><font size=2 face=Verdana><b>Acesso negado.</b></font></center>"; }
       
    if(!$manifestacao_erro && $autorizado){   
           
     // exibe relatório  de manifestação
?>

<div class="formulario" Style="width:50%;margin-left:25%;">
            <fieldset class="visivel">
                <legend class="legendaDiscreta">Dados do relatório</legend>
                <table align="center">
                    <?php if ($Relatorio->data_relatorio) {
                        echo "<tr>
                            <td align='right'><b>Nº Relatório: </b></td>
                            <td>" . $Relatorio->numero_relatorio." - ". $Especie_auditoria->nome_auditoria . "</td>            
                            </tr>";
                    }else {
                        echo "<Tr>
                              <td align='right'><b>Relatório ID: </b></td>
                              <td>".$Relatorio->id ." -  " . $Especie_auditoria->nome_auditoria."</td>            
                              </tr>";                       
                    } ?>
                       <Tr>
                           <td align="right"><b>Unidade Auditada: </b></td>
                           <td><?php echo $UnidadeAdministrativa->nome;?></td>            
                       </tr>        
                   </table>
            </fieldset>

    <br><br>
            <fieldset class="visivel">
                <legend class="legendaDiscreta">Manifestacao de <?php echo $Manifestacao->nome_login;?> em  <?php echo $Manifestacao->data_manifestacao;?></legend>
            <?php echo $Manifestacao->descricao_manifestacao;?>
            </fieldset>
   
<?php if ($Manifestacao->descricao_resposta){ ?>
        <br><br>
            <fieldset class="visivel">
                <legend class="legendaDiscreta">Resposta de <?php echo $Manifestacao->nome_login_resposta;?> em  <?php echo $Manifestacao->data_resposta;?></legend>
            <?php echo $Manifestacao->descricao_resposta;?>
            </fieldset>
<?php } ?>        
        <br>
        <center>
        <?php echo CHtml::link(Yii::t('app', 'Voltar'), $this->createUrl('/Manifestacao/ManifestacaoSaidaAjax'), array('class' => 'imitacaoBotao')); ?>
        </center>
</div>
<?php
    }
  }
?>
