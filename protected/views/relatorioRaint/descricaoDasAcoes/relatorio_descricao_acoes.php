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
<div class="tabelaListagemItensWrapper">
    <div class="tabelaListagemItens">
        <table border="0" cellpadding="0" cellpadding="0" align="center">
            <tr>
                <th align="center" bgcolor="#FFFFFF" rowspan="2">Nº do Relatório</th>
                <th align="center" bgcolor="#FFFFFF" rowspan="1" colspan="2">
            <div align="center">Cronograma Executado</div>
            </th>
            <th align="center" bgcolor="#FFFFFF" rowspan="2">Unidade</th>
            <th align="center" bgcolor="#FFFFFF" rowspan="2">Área</th>
            <th align="center" bgcolor="#FFFFFF" rowspan="2">Setores</th>
            <th align="center" bgcolor="#FFFFFF" rowspan="2">Escopo dos Trabalhos</th>
            <th align="center" bgcolor="#FFFFFF" rowspan="2">Observação</th>
            </tr>
            <tr>
                <th align="center" bgcolor="#FFFFFF" colspan="1">Início</th>
                <th align="center" bgcolor="#FFFFFF" colspan="1">Fim</th>
            </tr>
            <?php foreach ($dados as $vetor) :
                $categoria = ($vetor['categoria_fk']==2)? "  ". $vetor['descricao_categoria']:"";
                ?>
                <tr>
                    <td valign=middle align=center rowspan="1"> <?= $vetor['numero_relatorio']. $categoria; ?></td>
                    <td valign=middle align=center rowspan="1"> <?= MyFormatter::converteData($vetor['data_inicio_atividade']); ?></td>
                    <td valign=middle align=center rowspan="1"> <?= MyFormatter::converteData($vetor['data_relatorio']); ?></td>
                    <td valign=middle align=center rowspan="1"> 
                        <?
                        $relatorio_unidade = RelatorioSureg::model()->findAll('relatorio_fk=:relatorio_fk', array(':relatorio_fk' => $vetor['relatorio_fk']));
                        foreach ($relatorio_unidade as $vetor_unidade) {
                            $unidade_administrativa = UnidadeAdministrativa::model()->findByPk($vetor_unidade['unidade_administrativa_fk']);
                            echo $unidade_administrativa . "<br>";
                        }
                        ?>  
                    </td>
                    <td valign=middle align=center rowspan="1">
                        <?php
                        $relatorio_area = RelatorioArea::model()->findAll('relatorio_fk=:relatorio_fk', array(':relatorio_fk' => $vetor['relatorio_fk']));
                        foreach ($relatorio_area as $vetor_area) {
                            $area = UnidadeAdministrativa::model()->findByPk($vetor_area['unidade_administrativa_fk']);
                            echo $area . "<br>";
                        }
                        ?>
                    </td>
                    <td valign=middle  align=center rowspan="1">
                        <?php
                        $relatorio_setor = RelatorioSetor::model()->findAll('relatorio_fk=:relatorio_fk', array(':relatorio_fk' => $vetor['relatorio_fk']));
                        foreach ($relatorio_setor as $vetor_setor) {
                            $setor = UnidadeAdministrativa::model()->findByPk($vetor_setor['unidade_administrativa_fk']);
                            echo $setor . "<br>";
                        }
                        ?>
                    </td>
                    <td valign=middle align=center rowspan="1"> <?= $vetor['nome_objeto']; ?></td>
                    <td valign=middle align=center rowspan="1"></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<br><br>
<center>
    <a href="./DescricaoDasAcoesAjax" class="imitacaoBotao">Voltar</a>
</center>





