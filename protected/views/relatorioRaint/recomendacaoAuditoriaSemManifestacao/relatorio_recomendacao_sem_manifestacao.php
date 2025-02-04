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
foreach ($dados as $relatorio_fk=>$vetor) {
	foreach($vetor as $unidade_administrativa_fk=>$vetorDados){
	
	$RelatorioAuditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk' => $relatorio_fk));
	$Relatorio = Relatorio::model()->findAllByAttributes(array('id' => $relatorio_fk));
    $UnidadeAdministrativa = UnidadeAdministrativa::model()->findbyAttributes(array('id' => $unidade_administrativa_fk));
//    $RelatorioSureg = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk' => $vetorDados['relatorio_fk']));
    ?>
    <div class="tabelaListagemItensWrapper">
        <div class="tabelaListagemItens">
            <div class="tabelaListagemItens">
                <table border="0" cellpadding="0" cellpadding="0" align="center">
                    <tr>
                        <th align="center" bgcolor="#FFFFFF" rowspan="1">Nº do Relatório</th>
                        <th align="center" bgcolor="#FFFFFF" rowspan="1">Auditores</th>
                        <th align="center" bgcolor="#FFFFFF" rowspan="1">Nº da Recomendação</th>
                        <th align="center" bgcolor="#FFFFFF" rowspan="1">Unidade Auditada</th>
                        <th align="center" bgcolor="#FFFFFF" rowspan="1">Dias Úteis</th>
                    </tr>
                    <tr>
                        <td align="center" rowspan="<?php echo sizeof($vetorDados); ?>">
                            <?php
                            echo $Relatorio[0]->numero_relatorio . ' de ' . $Relatorio[0]->data_relatorio;
                            ?>
                        </td>
                        <td align="center" rowspan="<?php echo sizeof($vetorDados); ?>"><?php
                            foreach ($RelatorioAuditor as $vetor) {
                                $Auditor = Usuario::model()->findByPk($vetor[usuario_fk]);
                                echo $Auditor->nome_usuario . '<br>';
                            }
                            ?>
                        </td>
                        
                        <?php
                        	$iteracao_rowspan=1; 
                        	foreach($vetorDados as $recomendacao):
                        		if ($iteracao_rowspan==1):
                        ?>
	                        <td align="center">
	                        	<?php
	                            	echo $recomendacao['numero_item'] .".". $recomendacao['numero_recomendacao'];
	                            ?>
	                        </td>                        
							<td align="center">
	                            <?php
	                            echo $UnidadeAdministrativa->sigla;
	                            ?>
	                        </td> 
	                        <td align="center">
							    <?php
							    	echo Feriado::model()->CalculaQtdeDiasUteis(MyFormatter::converteData($recomendacao['data_resposta']), date('d/m/y'));
							    ?>
	                        </td>
	                    <?php else: ?>
                        <tr>
	                        <td align="center">
	                        	<?php
	                            	echo $recomendacao['numero_item'] .".".$recomendacao['numero_recomendacao'];
	                            ?>
	                        </td>                        
							<td align="center">
	                            <?php
	                            echo $UnidadeAdministrativa->sigla;
	                            ?>
	                        </td>
	                        <td align="center">
							    <?php
							    	echo Feriado::model()->CalculaQtdeDiasUteis(MyFormatter::converteData($recomendacao['data_resposta']), date('d/m/y'));
							    ?>
	                        </td>
	                    </tr>	                    
	                    <?php endif; $iteracao_rowspan++; ?>
                        <?php endforeach; ?>
                        </tr>
                </table>
            </div>
        </div>
    </div>
    <?php
	}
}
?>

<br><br>
<center>
    <a href="./RecomendacaoAuditoriaSemManifestacaoAjax" class="imitacaoBotao">Voltar</a>
</center>
