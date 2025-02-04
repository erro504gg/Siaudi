<?php
/********************************************************************************
*  Copyright 2015 Conab - Companhia Nacional de Abastecimento                   *
*                                                                               *
*  Este arquivo � parte do Sistema SIAUDI.                                      *
*                                                                               *
*  SIAUDI  � um software livre; voc� pode redistribui-lo e/ou                   *
*  modific�-lo sob os termos da Licen�a P�blica Geral GNU conforme              *
*  publicada pela Free Software Foundation; tanto a vers�o 2 da                 *
*  Licen�a, como (a seu crit�rio) qualquer vers�o posterior.                    *
*                                                                               *
*  SIAUDI � distribu�do na expectativa de que seja �til,                        *
*  por�m, SEM NENHUMA GARANTIA; nem mesmo a garantia impl�cita                  *
*  de COMERCIABILIDADE OU ADEQUA��O A UMA FINALIDADE ESPEC�FICA.                *
*  Consulte a Licen�a P�blica Geral do GNU para mais detalhes em portugu�s:     *
*  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt                     *
*                                                                               *
*  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral do GNU             *
*  junto com este programa; se n�o, escreva para a Free Software                *
*  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    *
*                                                                               *
*  Sistema   : SIAUDI - Sistema de Auditoria Interna                            *
*  Data      : 05/2015                                                          *
*                                                                               *
********************************************************************************/
?>
<?php
$this->pageTitle = Yii::app()->name;
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/index.js');
$avaliacaoCriterio = AvaliacaoCriterio::model()->CopiaAvaliacaoCriterio();
?>

<center>
    <font size="5">Bem-vindo <i><?php echo Yii::app()->user->login; ?></i></font>
    <br><br>

    <?php
    $perfil = Yii::app()->user->role;
    $perfil = str_replace("siaudi2", "siaudi", $perfil);
    $tipo_pendentes = $_GET["tipo_pendencias"];
    if ($perfil == "siaudi_auditor") {
        ?>
        <table class="labelCampo" align="center">
            <tr>
                <td><b>Visualizar:</b> </td>
                <td>
                    <form name="index">
                        <select name="tipo_pendencias" onchange="valida()">
                            <option value="1" <?= ($tipo_pendentes == "1") ? "selected" : "" ?> >Todas as pendências</option>
                            <option value="2" <?= ($tipo_pendentes == "2" || $tipo_pendentes == "") ? "selected" : "" ?> >Pendências dos meus clientes</option>
                        </select>
                    </form>
                </td>
            </tr>
            <tr>
                <td colspan="2" height="20"></td>
            </tr>
        </table>
    </center>
    <?php
}


// exibe mensagem de dias restantes para o pr�ximo rein�cio da contagem dos itens 
// (somente para perfil de chefe de auditoria - coordenador)
$relatorio_reiniciar = RelatorioReiniciar::model()->find('st_reiniciar=false order by data DESC');
$data_reiniciar = $relatorio_reiniciar->data;
if ($data_reiniciar && $perfil == "siaudi_chefe_auditoria") {
    //trocar data do formato brasileiro para americano
    $data_reiniciar = explode("/", $data_reiniciar);
    $data_inicio = date('Y-m-d'); //hoje
    // aumenta a data final em 5 anos
    $data_final = ($data_reiniciar[2] + 5) . "-" . $data_reiniciar[1] . "-" . $data_reiniciar[0];
    $diferenca_entre_datas = Feriado::model()->diferenca_entre_datas($data_inicio, $data_final);

    if ($diferenca_entre_datas <= 30) {
        $style_spam = "style='font-size:14px;font-weight:bold;'";
        $style_dias = ($diferenca_entre_datas > 0) ? "green" : "red";
    }
    echo "<span $style_spam>A contagem dos itens do Relatório foi 
              reiniciada pela ùltima vez em " . $relatorio_reiniciar->data . ". 
              Faltam <span style='color:$style_dias'>" . $diferenca_entre_datas . " dias </span> para o pr�ximo rein�cio.</span><br><br>";
}




// pega relat�rios pendentes
$relatorios = Relatorio::model()->Relatorio_pendentes_index($tipo_pendentes);

if (sizeof($relatorios) > 0) {
    foreach ($relatorios as $vetor_relatorio) {
        // para cada relat�rio encontrado, verifica pend�ncias
        $recomendacao = Relatorio::model()->Relatorio_pendentes_index2($vetor_relatorio[id]);
        if ($recomendacao[0][numero_capitulo]) {

            $data_relatorio = MyFormatter::converteData($vetor_relatorio[data_relatorio]);
            $prazo = $vetor_relatorio[valor_prazo];
            $relatorio_sureg = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk' => $vetor_relatorio[id]));
            $dias_restantes = Feriado::model()->dias_uteis_restantes($vetor_relatorio[data_relatorio], $prazo);
            ?>



            <?php
            $imprimiuCabecalho = false;

            foreach ($recomendacao as $vetor_recomendacao) {
                $ultimo_status = Resposta::model()->FollowUp_Ultimo_Status($vetor_recomendacao[id], 1);
                $ultimo_status_descricao = TipoStatus::model()->FindByPk($ultimo_status);
                $ultimo_status_cor = ($ultimo_status==1)? "#FF3333":"#FF9933";
               
                if ($ultimo_status==1 || $ultimo_status==2) {
                    $pendencias_total++;
                    if (!$imprimiuCabecalho) {
                         $pendencias_total=1;
                        $imprimiuCabecalho = true;
                        ?>
                        <div class="tabelaListagemItensWrapper">
                            <div align="center" class="labelCampo">
                                <table border="0" class="labelCampo" width="450">
                                    <tr>
                                        <td><b>No Relatório:</b> <?= $vetor_relatorio[numero_relatorio]; ?></td>
                                        <td><b>Data:</b> <?= $data_relatorio; ?></td>
                                        <td><b><?= $vetor_relatorio[nome_auditoria]; ?></b></td>
                                    </tr>
                                    <tr>
                                        <td valign="top"><table cellpadding="0" cellspacing="0">
                                                <tr> 
                                                    <td valign="top" align="right"><b>Unidade Auditada: </b></td>
                                                    <td align="left"><?php
                                                        foreach ($relatorio_sureg as $vetor_sureg) {
                                                            $sureg = UnidadeAdministrativa::model()->findByAttributes(array('id' => $vetor_sureg->unidade_administrativa_fk));
                                                            echo "&nbsp;" . $sureg->sigla . "/" . $sureg->uf_fk . "<br>";
                                                        }
                                                        ?>                        
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>

                                        <td colspan="2" valign="top"><b>Prazo: </b>  
                                            <?= $prazo; ?> (dias úteis)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Término do Prazo:</b> 
                                            <?php echo Feriado::model()->DiasUteis($data_relatorio, $prazo); ?>
                                        </td>
                                        <td colspan="2">
                                            <b>Prazo Restante: </b>
                                            <span <?= ($dias_restantes <= "0") ? "style=color:#FF3333;font-weight:bold" : "" ?>>
                                                <?= $dias_restantes; ?> (dias úteis)
                                            </span>                                            
                                        </td>
                                    </tr>
                                </table>

                                <div class="tabelaListagemItens">
                                    <table border="0" cellpadding="10" align="center">
                                        <tr>
                                            <th nowrap>No Capítulo</th>
                                            <th nowrap>No Item</th>
                                            <th nowrap>No Recomendação</th>
                                            <th>Recomendação</th>
                                            <th>Status</th>
                                            <th>Última Resposta</th>
                                            <th>&nbsp;</th>                                                
                                        </tr>
                                    <? } ?>
                                    <tr>
                                        <td align=center>
                                            <?= $vetor_recomendacao[numero_capitulo]; ?>
                                        </td>

                                        <td align=center>
                                            <?= $vetor_recomendacao[numero_item]; ?>
                                        </td>

                                        <td align=center>
                                            <?= $vetor_recomendacao[numero_item] . "." . $vetor_recomendacao[numero_recomendacao]; ?>
                                        </td>                                    

                                        <td align=left>
                                            <?
                                            $recomendacao = htmlspecialchars_decode(strip_tags($vetor_recomendacao[descricao_recomendacao]));
                                            $recomendacao2 = (strlen($recomendacao) > 100) ? substr($recomendacao, 0, 100) . " ..." : $recomendacao;
                                            echo $recomendacao2;
                                            ?>
                                        </td>

                                        <td align="center">
                                            <font color='<?=$ultimo_status_cor;?>'><?php echo $ultimo_status_descricao->descricao_status;?></font>
                                        </td>

                                        <td align="center">
                                            <?php
                                            $ultimo_id = Resposta::model()->FollowUp_UltimoID($vetor_recomendacao['id']);
                                            if (sizeof($ultimo_id)) :
                                                $resposta = Resposta::model()->findByPk($ultimo_id);
                                                $ultimo_usuario = Usuario::model()->findByAttributes(array('nome_login' => $resposta->id_usuario_log));
                                                $perfil_ultimo_usuario = Perfil::model()->findByPk($ultimo_usuario->perfil_fk);
                                                if ((strtolower($perfil_ultimo_usuario->nome_interno) == "siaudi_auditor") ||
                                                    (strtolower($perfil_ultimo_usuario->nome_interno) == "siaudi_gerente_nucleo")  ||
                                                    (strtolower($perfil_ultimo_usuario->nome_interno) == "siaudi_chefe_auditoria")  ||
                                                    (strtolower($perfil_ultimo_usuario->nome_interno) == "siaudi_cgu")  ||
                                                    (strtolower($perfil_ultimo_usuario->nome_interno) == "siaudi_gerente")):
                                                    ?>
                                                    <font>Auditoria</font>
                                                <? elseif((strpos(strtolower($perfil_ultimo_usuario->nome_interno),"siaudi_diretor")) ||
                                                          (strtolower($perfil_ultimo_usuario->nome_interno) == "siaudi_gabin")): ?>
                                                    <font color='FF3333'>Diretoria</font>
                                                <? else: ?>
                                                    <font color='FF3333'>Auditado</font>
                                                <? endif; ?>
                                            <? else: ?>
                                                    <font> - </font>
                                            <? endif; ?>

                                        </td>

                                        <td align="center" width="20">
                                            <a href="Resposta/admin/<?= $vetor_recomendacao[id]; ?>">
                                                <img src="<?= Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . "/img/alterar.gif"; ?>" border="0" title="Follow up"></a>
                                        </td>   
                                    </tr>
                                <?php }
                                ?>


                            <? }
                            ?>
                        </table>
                        <? echo "<p align=left> $pendencias_total pend�ncia(s) encontrada(s)</p>"; ?>
                    </div>
                </div>
            </div><br>
            <?php
        }
    }
}

?>
