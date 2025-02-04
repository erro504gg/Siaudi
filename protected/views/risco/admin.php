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
<div class="formulario" Style="width: 70%;">
    <?php
    $form = $this->beginWidget('GxActiveForm', array(
        'id' => 'risco-form',
        'enableAjaxValidation' => false,
            ));
    ?>    
    <?php echo $form->errorSummary($model); ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta"> <?php 
         if ($_GET["consultar"]) { echo "Consultar"; } 
         else{ 
            echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); 
         } ?> 
            - Risco</legend>


        <div class='row'>
            <div class='label'><?php echo $form->label($model_processo, 'valor_exercicio'); ?></div>
            <div class='field'>
            <?php           
            if (!$_GET["exercicio"]){
               echo "<form action='./'>";
               $form->widget('CMaskedTextField', array(
               'model' => $model,
               'attribute' => 'exercicio',
               'mask' => '9999',
               'placeholder' => '_',
               'htmlOptions' => array('maxlength' => 4,
                                      'value'     => $_GET['exercicio'])
               ));

               echo GxHtml::submitButton(Yii::t('app', 'Validar'), array('class' => 'botao')); 
               echo "</form>";
            } else {  echo $_GET["exercicio"]. "&nbsp; &nbsp;  &nbsp;  &nbsp; "; }
            ?>              
            </div>
            
        </div><!-- row -->
        <div style="height:10px;"></div>
        <div class='row'>

            <?
            // testa se existe ações
            // e critérios cadastrados
            // para este ano 
           if ($_GET["exercicio"]) {
                if (!empty($model_criterio_dados)) {
                    $criterio_ok = 1;
                } else {
                    echo "<b>Nenhum critério cadastrado para este exercício.</b><br>";
                }
                
                if (!empty($model_processo_dados)) {
                    $acao_ok = 1;
                } else {
                    echo "<b>Nenhum processo cadastrado para este exercício.</b><br>";
                }                
            }
            
            
            if ($criterio_ok and $acao_ok) {
                
                // conta os tipos de ação 
                // para gerar o rowspan
                foreach($model_processo_dados  as $vetor){
                    $tipos_de_acao[$vetor[tipo_processo_fk]]++;
                }
 
            // calcula soma dos pesos para fazer média composta
            $peso_total=0;
           if (is_array($model_criterio_dados)){
                    foreach ($model_criterio_dados as $vetor){
                        $peso_total +=$vetor->valor_peso;
                    }                    
           }                 
                
                // gera javascript dinâmico para somar campos
               ?> 
             <script language='JavaScript'>
                 function risco (id, total){
                    var div = "risco" + id; 
                    var legenda; 

                   if(total>=0 && total<1){ legenda="Irrelevante"; estilo="irrelevante"; }
                   if(total>=1 && total<2){ legenda="Baixo"; estilo="baixo";}
                   if(total>=2 && total<3){ legenda="Médio"; estilo="medio";}
                   if(total>=3 && total<4){ legenda="Alto"; estilo="alto";}
                   if(total>=4){ legenda="Crítico"; estilo="critico";}
                 
                    document.getElementById(div).innerHTML=legenda; 
                    document.getElementById(div).className="risco_" + estilo;
                 }
                 
		function getMoney( el ){  
		    var money= document.getElementById( el );	
		    var money = money.value.replace( '.', '' );	
		    var money = money.replace( ',', '.' );  
		    var money = parseFloat(money); 
		   if (isNaN(money)==true){  
			   	return 0; } 
		   else {
		    return money;
		   }  
		}                   
             <?
                   
                $contador=1;
                   foreach ($model_processo_dados as $vetor){
                       echo "function soma{$contador}(){ \n";
                        echo   "var total=";                     
                        foreach ($model_criterio_dados as $vetor_criterio){                       
                          echo "(getMoney('Nota_Acao_Criterio[{$vetor[id]}][{$vetor_criterio->id}]')*$vetor_criterio->valor_peso) +";
                        }
                       echo " 0; \n";
			echo "var total = ((Math.round(total*100))/100); \n";
                        echo "var total = total/".$peso_total ."; \n";
                        echo "var total = total.toFixed(2);";
                        
                       echo "d = document.getElementById('total{$contador}').innerHTML=total; \n";                                                
                       //echo "d = document.all.total{$contador}.innerHTML=total; \n";
                       echo "risco({$contador},total);} \n";                       
                       echo "soma{$contador}(); \n";
                        $contador++;
                    }
                 echo "</script>";
                 
            // monta cabeçalho da tabela                  
                ?>

           
            <div class="tabelaListagemItensWrapper">
                    <table class="tabelaListagemItensYii" width="100%">
                    <thead>
                    <tr>
                    <th id="criterio-grid_c0" width="180">Tipo de Processo </th>
                    <th width="230">Processo</th>
                    <?php 
                    foreach ($model_criterio_dados as $vetor){
                        echo " <th>Aval.  ".$vetor->nome_criterio." </th>";
                    }                    
                    ?>
                    <th width="40">Total</th>                                        
                    <th width="100">Grau de Risco</th>                    
                    </tr>
                    </thead>
                    
                    <tbody>
                        <?php 
                       // gera linhas com as ações 
                                    $contador=1;   
                                    $encontrou_value=0;                                    
                                foreach ($model_processo_dados as $vetor){
                                    $class_bg=($class_bg=="even")? "odd":"even";
                                     echo"<tr class='".$class_bg."'>";
                                                if (!$imprime[$vetor[tipo_processo_fk]]) { 
                                                    echo "<td rowspan=".$tipos_de_acao[$vetor[tipo_processo_fk]].">". $vetor[nome_tipo_processo]."</td>";
                                                    $imprime[$vetor[tipo_processo_fk]]=1;
                                                } 
                                            echo "<td>".$vetor[nome_processo]."</td>";
                                     
                                            // gera colunas com os critérios

                                            foreach ($model_criterio_dados as $vetor_criterio){
                                                $asterisco = ($_GET["consultar"])? "":"*";
                                                $value = Risco::model()->RecuperaNota($vetor[id],$vetor_criterio->id);
                                                $value = str_replace(".",",",$value);
                                                $value=($value)? $value:"";
                                                if ($value && $encontrou_value==0){ $encontrou_value=1; }
                                                echo " <td align=center>
                                                       <div class='field'>
                                                         <input maxlength='4' size='5' name='Nota_Acao_Criterio[".$vetor[id]."][".$vetor_criterio->id."]'  
                                                         id='Nota_Acao_Criterio[".$vetor[id]."][".$vetor_criterio->id."]'   type='text' 
                                                        onkeyup='this.value=this.value.replace(\".\",\",\")'  onblur='soma{$contador}()'  readonly
                                                         value='{$value}'    /> {$asterisco}
                                                       </div>
                                                      </td>";
                                            } 
                                     echo 
                                         "<td align=center><div name='total{$contador}' id='total{$contador}'></div></td>" . // total
                                         "<td align=center><div name='risco{$contador}' id='risco{$contador}' class=''></div></td>" . // critério
                                         "</tr>";   
                                     $contador++;                                         
                                }                    
                         ?>
                    </tbody>
                    </table>

            </div>                
                                          
          <?php  }  ?>
            
  </div>
  <div style="height:10px;"></div>        
       
    </fieldset>
    
    <?php if ($criterio_ok and $acao_ok) { 
    /*    
    if (!$_GET["consultar"]){ ?>
    <p class="note">
        <?php echo Yii::t('app', 'Fields with'); ?>
        <span class="required">*</span>
        <?php echo Yii::t('app', 'are required'); ?>.
    </p>
    <div class="rowButtonsN1">
        <?php echo GxHtml::submitButton(Yii::t('app', 'Confirm'), array('class' => 'botao')); ?>
        <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Risco/index'), array('class' => 'imitacaoBotao')); ?>
    </div>
    <? }*/ ?> 
      
    
    <br><br>
    
<div class="tabelaListagemItensWrapper">

            <Table width="90%"><tr><td width="50%" align="center" valign="top">
        <table class="tabelaListagemItensYii" style="width:300px;">
        <thead>
        <tr>
        <th id="criterio-grid_c0">Pesos atribuídos por critério </th><th>Pesos</th></tr>
        </thead>
        <tbody>
            <?php 
             if (is_array($model_criterio_dados)){
                    foreach ($model_criterio_dados as $vetor){
                        $class_bg=($class_bg=="even")? "odd":"even";
                        $TipoCriterio = GxHtml::listDataEx(TipoCriterio::model()->findAllByAttributes(array('id'=>$vetor->tipo_criterio_fk)));
                         echo "<tr class='".$class_bg."'><td>".
                               $vetor->nome_criterio." - ".
                               $TipoCriterio[$vetor->tipo_criterio_fk].
                              "</td><td align=center>".$vetor->valor_peso."</td></tr>";
                    }                    
                }
             ?>
        </tbody>
        </table>
                    </td>
                   
            <td width="50%" align="center"  valign="top">
                    <table class="tabelaListagemItensYii" style="width:200px;">
        <thead>
        <tr>
        <th id="criterio-grid_c0" colspan="2">Grau de Risco </th></tr>
        </thead>
        <tbody>
            <tr class="risco_irrelevante">
                <td width="30%" align=center>0--0,99</td>
                <td>Irrelevante</td>                
            </tr>
            <tr class="risco_baixo">
                <td align=center>1---1,99</td>
                <td>Baixo</td>                
            </tr>
            <tr class="risco_medio">
                <td align=center>2---2,99</td>
                <td>Médio</td>                
            </tr>
            <tr class="risco_alto">
                <td align=center>3--3,99</td>
                <td>Alto</td>                
            </tr>
            <tr class="risco_critico">
                <td align=center>4---5</td>
                <td>Crítico</td>                
            </tr>            
        </tbody>
        </table>
                
            </td></tr></table>

</div>

    
    
    
    
    
    
    <?php 
            // carrega os totais e legendas

                if ($_GET["consultar"] || $encontrou_value){
                echo "<script language='Javascript'> \n";
                $contador=1;
                   foreach ($model_processo_dados as $vetor){
                       echo "soma{$contador}(); \n";
                        $contador++;
                    }
                 echo "</script>";  
            }
    
    
    } ?>
    <?php $this->endWidget(); ?>
</div><!-- form -->
