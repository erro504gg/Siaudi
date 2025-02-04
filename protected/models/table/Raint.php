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

Yii::import('application.models.table._base.BaseRaint');

class Raint extends BaseRaint {

    public $file_name, $file_type, $file_size, $file_content, $unidade_administrativa_fk, $objeto_fk,
            $valor_exercicio, $bolInseriDescRecomendacao;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'id' => Yii::t('app', 'ID'),
            'nome_titulo' => Yii::t('app', 'Título'),
            'descricao_texto' => Yii::t('app', 'Texto'),
            'numero_sequencia' => Yii::t('app', 'Sequência'),
            'anexo_id' => null,
            'numero_item_pai' => null,
            'numeroItemPai' => null,
            'anexo' => null,
            'valor_exercicio' => Yii::t('app', 'Exercício'),
            'bolInseriDescRecomendacao' => Yii::t('app', 'Exibir descrição das recomendações'),
            'objeto_fk' => Yii::t('app', 'Objeto'),
            'unidade_administrativa_fk' => Yii::t('app', 'Unidade Auditada'),
        );
        return array_merge($attribute_default, $attribute_custom);
    }

    public function afterFind() {
        parent::afterFind();
        if (isset($this->data_gravacao))
            $this->data_gravacao = MyFormatter::converteData($this->data_gravacao);
    }

    /* Recuperar Quantidade de Recomendações por Gravidade 
     * -------------
     * 1 - Obter a quantidade de recomendações por gravidade
     * -------------
     */

    public function ObtemTotalDeRecomendacaoesPorGravidade($valor_exercicio, $unidade_administrativa_fk, $objeto_fk) {
        $schema = Yii::app()->params['schema'];
        $sql = "select DISTINCT gravidade.id, gravidade.nome_gravidade, count(1) as total_gravidade,  
                        (select count(1)  
                           from " . $schema . ".tb_recomendacao_gravidade as gravidade 
                          inner join " . $schema . ".tb_recomendacao as recomendacao on  
                                gravidade.id = recomendacao.recomendacao_gravidade_fk 
                          inner join " . $schema . ".tb_item as item on 
                                recomendacao.item_fk = item.id  
                          inner join " . $schema . ".tb_objeto as objeto on  
                                item.objeto_fk = objeto.id 
                          inner join " . $schema . ".tb_capitulo as capitulo on 
                                item.capitulo_fk = capitulo.id 
                          inner join " . $schema . ".tb_relatorio as relatorio on 
                                capitulo.relatorio_fk = relatorio.id                                    
                          where recomendacao_tipo_fk = (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomendação%' LIMIT 1) ";
        if (($valor_exercicio != null) || ($valor_exercicio != 0)) {
        	$sql = $sql . " and date_part('year', data_relatorio) = " . $valor_exercicio;
        }
        if ($unidade_administrativa_fk != "Todas") {
            $sql = $sql . " and recomendacao.unidade_administrativa_fk = " . $unidade_administrativa_fk . " ";
        }
        if ($objeto_fk != "Todos") {
            $sql = $sql . " and objeto.id = " . $objeto_fk . " ";
        }
        $sql = $sql . " and data_relatorio is not null 
                        and numero_relatorio is not null) as total
                  from " . $schema . ".tb_recomendacao_gravidade as gravidade
                 inner join " . $schema . ".tb_recomendacao as recomendacao on 
                       gravidade.id = recomendacao.recomendacao_gravidade_fk 
                 inner join " . $schema . ".tb_item as item on 
                       recomendacao.item_fk = item.id 
                 inner join " . $schema . ".tb_objeto as objeto on 
                       item.objeto_fk = objeto.id 
                 inner join " . $schema . ".tb_capitulo as capitulo on 
                       item.capitulo_fk = capitulo.id 
                 inner join " . $schema . ".tb_relatorio as relatorio on 
                       capitulo.relatorio_fk = relatorio.id                        
                 where recomendacao_tipo_fk = (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomendação%' LIMIT 1) ";
        if ($unidade_administrativa_fk != "Todas") {
            $sql = $sql . " and recomendacao.unidade_administrativa_fk = " . $unidade_administrativa_fk . " ";
        }
        if ($objeto_fk != "Todos") {
            $sql = $sql . " and objeto.id = " . $objeto_fk . " ";
        }
        if (($valor_exercicio != null) || ($valor_exercicio != 0)) {
        	$sql = $sql . " and date_part('year', data_relatorio) = " . $valor_exercicio;
        }        
        
        $sql = $sql . " and data_relatorio is not null 
                        and numero_relatorio is not null
                      group by gravidade.id, gravidade.nome_gravidade";
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }

    public function ObtemTotalDeRecomendacoesSolucionadas($valor_exercicio, $unidade_administrativa_fk) {
        $schema = Yii::app()->params['schema'];
        $sql = "select (relatorio.numero_relatorio || ' de ' || to_char(relatorio.data_relatorio, 'dd/MM/yyyy')) as relatorio,
                        sureg.sigla,
                        count(1) as total_recomendacao,
                       (select count(1) 
                          from " . $schema . ".tb_resposta as respostaAux 
                         inner join " . $schema . ".tb_recomendacao as recomendacaoAux on 
                               recomendacaoAux.id = respostaAux.recomendacao_fk 
                         inner join " . $schema . ".tb_item as itemAux on
                               itemAux.id = recomendacaoAux.item_fk 
                         inner join " . $schema . ".tb_capitulo as capituloAux on 
                               capituloAux.id = itemAux.capitulo_fk
                         inner join " . $schema . ".tb_relatorio as relatorioAux on 
                               relatorioAux.id = capituloAux.relatorio_fk and
                               relatorioAux.numero_relatorio = relatorio.numero_relatorio and
                               relatorioAux.especie_auditoria_fk = relatorio.especie_auditoria_fk
                         where respostaAux.tipo_status_fk = 4) as total_solucionadas
                    from " . $schema . ".tb_recomendacao as recomendacao 
                   inner join " . $schema . ".tb_unidade_administrativa as sureg on 
                         sureg.id = recomendacao.unidade_administrativa_fk 
                   inner join " . $schema . ".tb_item as item on 
                         item.id = recomendacao.item_fk 
                   inner join " . $schema . ".tb_capitulo as capitulo on 
                         capitulo.id = item.capitulo_fk 
                   inner join " . $schema . ".tb_relatorio as relatorio on 
                         relatorio.id = capitulo.relatorio_fk  
           where recomendacao.recomendacao_tipo_fk IN (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomendação%')
             and numero_relatorio is not null 
             and data_relatorio is not null ";
        if (($valor_exercicio != null) || ($valor_exercicio != 0)) {
            $sql = $sql . " and date_part('year', data_relatorio) = " . $valor_exercicio;
        }
        if ($unidade_administrativa_fk != 'Todas') {
            $sql = $sql . " and sureg.id = " . $unidade_administrativa_fk;
        }
        $sql = $sql . " group by relatorio.numero_relatorio, relatorio.data_relatorio, relatorio.especie_auditoria_fk, sureg.sigla
                        order by relatorio.numero_relatorio, relatorio.data_relatorio, sureg.sigla";

        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }

    public function ObtemRiscoPreIdentificados($valor_exercicio, $objeto_fk) {
        $schema = Yii::app()->params['schema'];
        $sql = "SELECT risco_pre.id, risco_pre.descricao_impacto, risco_pre.nome_risco, risco_pre.descricao_mitigacao
                  FROM " . $schema . ".tb_plan_especifico as plan_especifico 
                 INNER JOIN " . $schema . ".tb_acao as acao on 
                       acao.id = plan_especifico.acao_fk
                 INNER JOIN " . $schema . ".tb_processo_risco_pre as acao_risco_pre on 
                       acao_risco_pre.processo_fk = acao.processo_fk                       		
                 INNER JOIN " . $schema . ".tb_risco_pre as risco_pre on 
                       risco_pre.id = acao_risco_pre.risco_pre_fk
                 WHERE acao.valor_exercicio = " . $valor_exercicio . " ";
        If (strtolower($objeto_fk) != 'todos') {
            $sql .= "AND plan_especifico.objeto_fk = " . $objeto_fk . " ";
        }
        $sql .=" GROUP BY risco_pre.id, risco_pre.descricao_impacto, risco_pre.nome_risco, risco_pre.descricao_mitigacao"; //, item.id
        $sql .=" ORDER BY risco_pre.nome_risco"; //, item.id

        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }

    public function ObtemRiscoPosIdentificados($valor_exercicio, $objeto_fk) {
        $schema = Yii::app()->params['schema'];
        $sql = "SELECT risco_pos.id, risco_pos.descricao_impacto, risco_pos.nome_risco, risco_pos.descricao_mitigacao
                  FROM " . $schema . ".tb_relatorio_risco_pos relatorio_risco_pos 
                 INNER JOIN " . $schema . ".tb_relatorio relatorio on 
                       relatorio.id = relatorio_risco_pos .relatorio_fk 
                 INNER JOIN " . $schema . ".tb_risco_pos risco_pos on
                       risco_pos.id = relatorio_risco_pos.risco_pos_fk 
             	 INNER JOIN " . $schema . ".tb_plan_especifico as plan_especifico on 
             	 	   plan_especifico.id = relatorio.plan_especifico_fk 
                 WHERE numero_relatorio is not null 
                   AND data_relatorio is not null
                   AND date_part('year', data_relatorio) = " . $valor_exercicio . " ";
        If (strtolower($objeto_fk) != 'todos') {
            $sql .= "AND plan_especifico.objeto_fk = " . $objeto_fk . " ";
        }
        $sql .=" GROUP BY risco_pos.id, risco_pos.descricao_impacto, risco_pos.nome_risco, risco_pos.descricao_mitigacao"; //, item.id
        $sql .=" ORDER BY risco_pos.nome_risco"; //, item.id
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }

    public function ObtemTotalDeRecomendacaoPorCategoria($valor_exercicio, $unidade_administrativa_fk, $objeto_fk) {
        $schema = Yii::app()->params['schema'];
        $sql = "SELECT categoria.id, categoria.nome_categoria, count(1) as total_categoria
                  FROM " . $schema . ".tb_recomendacao as recomendacao
                 INNER JOIN " . $schema . ".tb_recomendacao_categoria as categoria on 
                       categoria.id = recomendacao.recomendacao_categoria_fk
                 INNER JOIN " . $schema . ".tb_item as item on 
                       item.id = recomendacao.item_fk 
                 INNER JOIN " . $schema . ".tb_capitulo as capitulo on 
                       capitulo.id = item.capitulo_fk 
                 INNER JOIN " . $schema . ".tb_relatorio as relatorio on 
                       relatorio.id = capitulo.relatorio_fk      
                 INNER JOIN " . $schema . ".tb_unidade_administrativa as sureg on 
                       sureg.id = recomendacao.unidade_administrativa_fk                                
                 INNER JOIN " . $schema . ".tb_objeto as objeto on 
                       objeto.id = item.objeto_fk
                 WHERE numero_relatorio is not null 
                   AND data_relatorio is not null 
                   AND recomendacao_tipo_fk = (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomendação%' LIMIT 1) 
                   AND date_part('year', data_relatorio) = " . $valor_exercicio . " ";
        If (strtolower($unidade_administrativa_fk) != 'todas') {
            $sql .= "and unidade_administrativa_fk = " . $unidade_administrativa_fk . " ";
        }
        If (strtolower($objeto_fk) != 'todos') {
            $sql .= "and item.objeto_fk = " . $objeto_fk . " ";
        }
        $sql .="    GROUP BY categoria.id, categoria.nome_categoria
                    ORDER BY categoria.id"; //, item.id
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }

    public function ObtemTotalDeRecomendacaoPorSubCategoria($valor_exercicio, $unidade_administrativa_fk, $objeto_fk) {
        $schema = Yii::app()->params['schema'];
        $sql = " select subcategoria.recomendacao_categoria_fk, subcategoria.nome_subcategoria, count(1) as total_subcategoria
                   from " . $schema . ".tb_relatorio as relatorio
                  inner join " . $schema . ".tb_capitulo as capitulo on
                        capitulo.relatorio_fk = relatorio.id
                  inner join " . $schema . ".tb_item as item on 
                        item.capitulo_fk = capitulo.id 
                  inner join " . $schema . ".tb_recomendacao as recomendacao on 
                        recomendacao.item_fk = item.id
                  inner join " . $schema . ".tb_recomendacao_subcategoria as subcategoria on 
                        subcategoria.id = recomendacao.recomendacao_subcategoria_fk and
                        subcategoria.recomendacao_categoria_fk = recomendacao.recomendacao_categoria_fk
                  WHERE numero_relatorio is not null 
                    AND data_relatorio is not null 
                    AND recomendacao_tipo_fk = (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomendação%' LIMIT 1) 
                    AND date_part('year', data_relatorio) = " . $valor_exercicio . " ";
        If (strtolower($unidade_administrativa_fk) != 'todas') {
            $sql .= "and unidade_administrativa_fk = " . $unidade_administrativa_fk . " ";
        }
        If (strtolower($objeto_fk) != 'todos') {
            $sql .= "and item.objeto_fk = " . $objeto_fk . " ";
        }
        $sql .=" group by subcategoria.recomendacao_categoria_fk, subcategoria.nome_subcategoria 
                 order by subcategoria.recomendacao_categoria_fk";
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }

    public function ObtemDescRecomendacaoPorCategoria($valor_exercicio, $unidade_administrativa_fk, $objeto_fk) {
        $schema = Yii::app()->params['schema'];
        $sql = "SELECT categoria.nome_categoria, recomendacao.descricao_recomendacao
                  FROM " . $schema . ".tb_recomendacao as recomendacao
                 INNER JOIN " . $schema . ".tb_recomendacao_categoria as categoria on 
                       categoria.id = recomendacao.recomendacao_categoria_fk
                 inner join " . $schema . ".tb_item as item on 
                       item.id = recomendacao.item_fk 
                 inner join " . $schema . ".tb_capitulo as capitulo on 
                       capitulo.id = item.capitulo_fk 
                 inner join " . $schema . ".tb_relatorio as relatorio on 
                       relatorio.id = capitulo.relatorio_fk
                 inner join " . $schema . ".tb_unidade_administrativa as sureg on 
                       sureg.id = recomendacao.unidade_administrativa_fk         
                 inner join " . $schema . ".tb_objeto as objeto on 
                       objeto.id = item.objeto_fk
                 WHERE numero_relatorio is not null 
                   AND data_relatorio is not null 
                   AND recomendacao_tipo_fk = (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomendação%' LIMIT 1) 
                   AND date_part('year', data_relatorio) = " . $valor_exercicio . " ";
        If (strtolower($unidade_administrativa_fk) != 'todas') {
            $sql .= "and unidade_administrativa_fk = " . $unidade_administrativa_fk . " ";
        }
        If (strtolower($objeto_fk) != 'todos') {
            $sql .= "and item.objeto_fk = " . $objeto_fk . " ";
        }
        $sql .= " group by categoria.nome_categoria, recomendacao.descricao_recomendacao";
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }

    public function ObtemRecomendacoesSemManifestacao($valor_exercicio) {
        $schema = Yii::app()->params['schema'];
//         $sql = "select relatorio.id as relatorio_fk, relatorio.numero_relatorio, relatorio.data_relatorio, resposta.data_resposta, resposta.descricao_resposta, recomendacao.unidade_administrativa_fk
//                 from " . $schema . ".tb_relatorio as relatorio
//                inner join " . $schema . ".tb_capitulo as capitulo on 
//                      capitulo.relatorio_fk = relatorio.id 
//                inner join " . $schema . ".tb_item as item on 
//                      item.capitulo_fk = capitulo.id
//                inner join " . $schema . ".tb_recomendacao as recomendacao on 
//                      recomendacao.item_fk = item.id 
//                inner join " . $schema . ".tb_resposta as resposta on 
//                      resposta.recomendacao_fk = recomendacao.id
//                inner join " . $schema . ".tb_unidade_administrativa as sureg on 
//                      sureg.id = recomendacao.unidade_administrativa_fk
//                inner join " . $schema . ".tb_usuario as auditor on 
//                      auditor.nome_login = resposta.id_usuario_log
//                where relatorio.numero_relatorio is not null 
//                  and relatorio.data_relatorio is not null 
//                  and resposta.tipo_status_fk in (1, 2)
//                  and recomendacao.recomendacao_tipo_fk = 1
//                  and date_part('year', relatorio.data_relatorio) = " . $valor_exercicio . "
//                  and resposta.id = (select max(id) 
//                                       from " . $schema . ".tb_resposta as aux 
//                                       where aux.recomendacao_fk = resposta.recomendacao_fk)";
        
        
        $sql = "select r.id as relatorio_fk, r.numero_relatorio, r.data_relatorio, 
					   coalesce(rp.data_resposta,r.data_relatorio) as  data_resposta,
					   rp.descricao_resposta, rc.unidade_administrativa_fk, rc.numero_recomendacao,i.numero_item
				  from " . $schema . ".tb_relatorio as r 
				  join " . $schema . ".tb_capitulo as c on 
					   c.relatorio_fk = r.id 
				  join " . $schema . ".tb_item as i on 
				       i.capitulo_fk = c.id 
				  join " . $schema . ".tb_recomendacao as rc on 
				       rc.item_fk = i.id 
				  left join " . $schema . ".tb_resposta as rp on 
				       rp.recomendacao_fk = rc.id and 
				       rp.tipo_status_fk in (1, 2) 
				  join " . $schema . ".tb_unidade_administrativa as a on 
				       a.id = rc.unidade_administrativa_fk 
				 where r.numero_relatorio is not null 
				   and r.data_relatorio is not null 
				   and rc.recomendacao_tipo_fk = (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomendação%' LIMIT 1) 
				   and date_part('year', r.data_relatorio) = " . $valor_exercicio . "
				   and (select count(rp.id) 
        		          from " . $schema . ".tb_resposta as rp 
						  join " . $schema . ".tb_usuario as u on 
        					   rp.id_usuario_log=u.nome_login 
				          join " . $schema . ".tb_perfil p on 
        					   u.perfil_fk=p.id
				         where rp.recomendacao_fk=rc.id 
							and p.id in(5,6))=0
                 group by r.id, r.numero_relatorio, r.data_relatorio, 
                          rp.data_resposta, rp.descricao_resposta, rc.unidade_administrativa_fk, rc.numero_recomendacao,i.numero_item 
                 order by r.data_relatorio desc, i.numero_item, rc.numero_recomendacao";
        
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        $dados = $dados->readAll();
        foreach ($dados as $vetor){
        	$retorno[$vetor['relatorio_fk']][$vetor['unidade_administrativa_fk']][] = $vetor;
        }
        return $retorno;
    }

    public function ObtemRecomendacoesNaoAvaliadasPeloAuditor($valor_exercicio) {
        $schema = Yii::app()->params['schema'];
        
            $sql = "
            SELECT relatorio.id as relatorio_fk, relatorio.numero_relatorio, relatorio.data_relatorio, resposta.data_resposta, resposta.descricao_resposta, recomendacao.unidade_administrativa_fk,recomendacao.numero_recomendacao, item.numero_item, perfil.nome_interno
            FROM  " . $schema . ".tb_recomendacao recomendacao 
                inner join " . $schema . ".tb_resposta resposta on resposta.recomendacao_fk=recomendacao.id
                inner join " . $schema . ".tb_usuario usuario on usuario.nome_login=resposta.id_usuario_log
                inner join " . $schema . ".tb_perfil perfil on usuario.perfil_fk=perfil.id
                inner join " . $schema . ".tb_item item on recomendacao.item_fk=item.id
                inner join " . $schema . ".tb_capitulo capitulo on item.capitulo_fk=capitulo.id
                inner join " . $schema . ".tb_relatorio relatorio on capitulo.relatorio_fk=relatorio.id
                inner join " . $schema . ".tb_unidade_administrativa as sureg on sureg.id = recomendacao.unidade_administrativa_fk
            WHERE
                relatorio.numero_relatorio is not null 
                and relatorio.data_relatorio is not null 
                and recomendacao.recomendacao_tipo_fk = (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomendação%' LIMIT 1) 
                and date_part('year', relatorio.data_relatorio) = " . $valor_exercicio . "
                and resposta.id = (select max(aux.id)
                                    from " . $schema . ".tb_resposta aux
                                    where aux.recomendacao_fk=resposta.recomendacao_fk
                                    and aux.tipo_status_fk is null
                                    )
                and UPPER(perfil.nome_interno) not in ('SIAUDI_GERENTE', 'SIAUDI_AUDITOR', 'SIAUDI_CHEFE_AUDITORIA', 'SIAUDI_GERENTE_NUCLEO') 
            ORDER BY relatorio.data_relatorio desc, item.numero_item, recomendacao.numero_recomendacao";
        
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }

    public function ObtemRecomendacoesPendenteRespostaAuditado($valor_exercicio) {
        $schema = Yii::app()->params['schema'];
//         $sql = "select relatorio.id as relatorio_fk, relatorio.numero_relatorio, relatorio.data_relatorio, recomendacao.unidade_administrativa_fk,
//                        resposta.data_resposta, resposta.descricao_resposta, item.numero_item, recomendacao.numero_recomendacao,
//                        (select aux.data_resposta
//                           from " . $schema . ".tb_resposta as aux
//                          inner join " . $schema . ".tb_usuario as auditor on
//                                auditor.nome_login = aux.id_usuario_log
//                          where aux.tipo_status_fk in (1, 2)
//                            and aux.recomendacao_fk = resposta.recomendacao_fk
//                          order by resposta.id desc
//                          LIMIT 1) as data_resposta_auditor         
//                     from " . $schema . ".tb_relatorio as relatorio
//                    inner join " . $schema . ".tb_capitulo as capitulo on 
//                          capitulo.relatorio_fk = relatorio.id 
//                    inner join " . $schema . ".tb_item as item on 
//                          item.capitulo_fk = capitulo.id
//                    inner join " . $schema . ".tb_recomendacao as recomendacao on 
//                          recomendacao.item_fk = item.id 
//                    inner join " . $schema . ".tb_resposta as resposta on 
//                          resposta.recomendacao_fk = recomendacao.id
//                    inner join " . $schema . ".tb_unidade_administrativa as sureg on 
//                          sureg.id = recomendacao.unidade_administrativa_fk
//                    where relatorio.numero_relatorio is not null 
//                      and relatorio.data_relatorio is not null 
//                      and recomendacao.recomendacao_tipo_fk = 1
//                      and date_part('year', relatorio.data_relatorio) = " . $valor_exercicio . "
//                      and resposta.id = (select max(id) 
//                                           from " . $schema . ".tb_resposta as aux 
//                                          where aux.recomendacao_fk = resposta.recomendacao_fk)
//                      and not exists (select 1
// 		                       from " . $schema . ".tb_usuario as auditor 
//                                       where auditor.nome_login = resposta.id_usuario_log)";
        $sql = "select relatorio.id as relatorio_fk, relatorio.numero_relatorio, relatorio.data_relatorio, 
      				   recomendacao.unidade_administrativa_fk, resposta.data_resposta, resposta.descricao_resposta, 
                       item.numero_item, recomendacao.numero_recomendacao, resposta.recomendacao_fk, resposta.data_resposta as data_resposta_auditor
                  from " . $schema . ".tb_relatorio as relatorio 
                 inner join " . $schema . ".tb_capitulo as capitulo on 
       				   capitulo.relatorio_fk = relatorio.id 
                 inner join " . $schema . ".tb_item as item on 
                       item.capitulo_fk = capitulo.id 
                 inner join " . $schema . ".tb_recomendacao as recomendacao on 
                       recomendacao.item_fk = item.id 
 				 inner join " . $schema . ".tb_resposta as resposta on  
       				   resposta.recomendacao_fk = recomendacao.id 
 				 inner join " . $schema . ".tb_unidade_administrativa as sureg on 
       				   sureg.id = recomendacao.unidade_administrativa_fk 
 				 inner join " . $schema . ".tb_usuario usuario on 
       				   usuario.nome_login = resposta.id_usuario_log
 				 inner join " . $schema . ".tb_perfil perfil on 
       				   perfil.id = usuario.perfil_fk
 				 where relatorio.numero_relatorio is not null 
   				   and relatorio.data_relatorio is not null 
   				   and recomendacao.recomendacao_tipo_fk = (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomendação%' LIMIT 1) 
   				   and date_part('year', relatorio.data_relatorio) = " . $valor_exercicio . "
   				   and perfil.id in (148,160,161,150)
   				   and resposta.id in (select max(r.id) 
		          					     from " . $schema . ".tb_resposta r 
		         					    where r.recomendacao_fk = resposta.recomendacao_fk
		      						  )	
   				  and (select count(1) 
          		         from " . $schema . ".tb_usuario u
	 				    inner join siaudi.tb_perfil p on 
	       				      p.id = u.perfil_fk
	 					inner join " . $schema . ".tb_resposta r on 
	       					  r.recomendacao_fk = resposta.recomendacao_fk	
	 					where u.nome_login = r.id_usuario_log
	   					  and p.id in (151,152,156,157,158,155,149,159)) > 0
						order by relatorio_fk, data_relatorio, recomendacao_fk";
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }

    // carrega os itens do raint do banco para montar o json
    public function carrega_raint($exercicio) {

        $schema = Yii::app()->params['schema'];
        $sql = "SELECT t.id, t.nome_titulo, t.descricao_texto, t.numero_sequencia, t.numero_item_pai
                        FROM " . $schema . ".tb_raint  t
                        WHERE (t.valor_exercicio=" . $exercicio . " )
                        ORDER BY t.id ASC";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();


        // monta somente o vetor com os fihos
        $arvore = $result->readAll();

        //$vetor_arvore[] = array(); 
        foreach ($arvore as $vetor) {
            $pai = ($vetor['numero_item_pai']) ? $vetor['numero_item_pai'] : "0";
            $vetor_arvore[] = array("id" => $vetor['id'],
                "text" => strip_tags($vetor['nome_titulo']),
                "attributes" => array(
                    "titulo" => $vetor['nome_titulo'],
                    "texto" => $vetor['descricao_texto'],
                    "sequencia" => $vetor['numero_sequencia']
                ),
                "numero_item_pai" => $pai
            );
        }

        $vetor_completo = $vetor_arvore;

        if (is_array($vetor_completo) != true) {
            $vetor_completo = "";
        }
        return ($vetor_completo);
    }

    // Exclui os itens do RAINT salvos deste exercício
    // e inclui os itens novos da lista 
    public function prepara_raint() {
        $this->deleteAll("valor_exercicio=" . $_POST["exercicio"]);

        $raint = $_POST["raint"];

        // limpa aspas e quebra de linha para o JSON_DECODE funcionar
        $raint = str_replace("\\\"", "&aspasduplas", $raint);
        $raint = str_replace(array("'", "\n", "\r"), array("\"", "&quebradelinha", ""), $raint);
        $raint = str_replace("\n", "", $raint);


        $raint = preg_replace('/,\s*([\]}])/m', '$1', $raint);
        $itens_raint = json_decode(utf8_encode($raint));
        $this->salvar($itens_raint);
    }

    // salva os itens do RAINT de
    // acordo com a árvore de pais e filhos
    public function salvar($arvore, $pai = null) {
        $schema = Yii::app()->params['schema'];
        $exercicio = $_POST["exercicio"];


        if (is_array($arvore)) {
            foreach ($arvore as $vetor) {

                // coloca novamente as aspas  e quebra de linha para enviar ao banco
                $texto = str_replace(array("&aspasduplas", "&quebradelinha"), array("\"", "\n"), $vetor->attributes->texto);
                $numero_pdf = $vetor->attributes->numeracao;


                $pai = ($pai == null) ? "null" : $pai;
                $sql = "INSERT INTO " . $schema . ".tb_raint
                            (nome_titulo, descricao_texto, numero_sequencia, numero_item_pai, valor_exercicio, numero_pdf, data_gravacao) 
                            VALUES ('" . $vetor->attributes->titulo . "','" . $texto . "'," .
                        $vetor->attributes->sequencia . "," . $pai . "," . $exercicio . ",'" . $numero_pdf . "','" . date("Y-m-d") . "');";
                $command = Yii::app()->db->createCommand($sql);
                $command->execute();

                // pega último id inserido
                $sql = "SELECT currval('" . $schema . ".tb_raint_id_seq')";
                $command = Yii::app()->db->createCommand($sql);
                $command->execute();
                $result = $command->query();
                $last_id = $result->read();
                $pai2 = $last_id['currval'];

                // se tem filhos, insere os filhos com o id do pai
                if (is_array($vetor->children)) {
                    $this->salvar($vetor->children, $pai2);
                }
            }
        }
    }

    public function ObtemRecomendacaoPorAcao($valor_exercicio) {
        $schema = Yii::app()->params['schema'];
        $sql = "select objeto.nome_objeto, count(1) as total_por_objeto
                  from " . $schema . ".tb_relatorio as relatorio
                 inner join " . $schema . ".tb_capitulo as capitulo on
                       capitulo.relatorio_fk = relatorio.id
                 inner join " . $schema . ".tb_item as item on 
                       item.capitulo_fk = capitulo.id 
                 inner join " . $schema . ".tb_objeto as objeto on 
                       objeto.id = item.objeto_fk                       
                 inner join " . $schema . ".tb_recomendacao as recomendacao on 
                       recomendacao.item_fk = item.id
                 where relatorio.numero_relatorio is not null 
                   and relatorio.data_relatorio is not null
                   and recomendacao.recomendacao_tipo_fk = (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomendação%' LIMIT 1) 
                   and date_part('year', relatorio.data_relatorio) = " . $valor_exercicio . "
                 group by objeto.nome_objeto"; 

        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }

    public function ObtemRecomendacaoPorSubcategoria($valor_exercicio) {
        $schema = Yii::app()->params['schema'];
        $sql = "select objeto.nome_objeto, count(1) as total_por_objeto
                  from " . $schema . ".tb_relatorio as relatorio
                 inner join " . $schema . ".tb_objeto as objeto on 
                       objeto.id = relatorio.objeto_fk
                 inner join " . $schema . ".tb_capitulo as capitulo on
                       capitulo.relatorio_fk = relatorio.id
                 inner join " . $schema . ".tb_item as item on 
                       item.capitulo_fk = capitulo.id and
                       item.objeto_fk = relatorio.objeto_fk
                 inner join " . $schema . ".tb_recomendacao as recomendacao on 
                       recomendacao.item_fk = item.id
                 where relatorio.numero_relatorio is not null 
                   and relatorio.data_relatorio is not null
                   and recomendacao.recomendacao_tipo_fk = (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomendação%' LIMIT 1) 
                   and date_part('year', relatorio.data_relatorio) = " . $valor_exercicio . "
                 group by objeto.nome_objeto";
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }

    public function ObtemDescricaoDasAcoes($valor_exercicio) {
        $schema = Yii::app()->params['schema'];
        $sql = "select relatorio.id as relatorio_fk, relatorio.numero_relatorio, plan_especifico.data_inicio_atividade,
                       relatorio.data_relatorio, objeto.nome_objeto, categoria.descricao_categoria, categoria.id as categoria_fk
                  from " . $schema . ".tb_relatorio relatorio
                 inner join " . $schema . ".tb_plan_especifico plan_especifico on
                       plan_especifico.id = relatorio.plan_especifico_fk and  
                       plan_especifico.valor_exercicio = date_part('year', relatorio.data_relatorio)
                 inner join " . $schema . ".tb_objeto objeto on  
                       objeto.id = plan_especifico.objeto_fk 
                 inner join " . $schema . ".tb_categoria categoria on 
                       categoria.id = relatorio.categoria_fk
                 where plan_especifico.valor_exercicio = " . $valor_exercicio;
        
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }

    // Verifica se usuário pode acessar o RAINT para edição.
    // Pela regra atual, somente os perfis de Gerente e Chefe
    // de Auditoria podem altera-lo. 
    public function acesso_raint() {
        $perfil = strtolower(Yii::app()->user->role);
        $perfil = str_replace("siaudi2", "siaudi", $perfil);

        if (!($perfil == "siaudi_gerente" || $perfil == "siaudi_chefe_auditoria")) {
            return 0;
        }

        return 1;
    }
    
    // gera a página no formato paisagem ou retrato.
    //Parâmetros de entrada: url padrão do sistema, formato ("P" => portrait/retrato, "L" => landscape/paisagem)
    function raint_gerar_pagina($url,$formato){
        
        $retorno= "<page  backtop='25mm' backbottom='30mm' backleft='10mm' backright='10mm' style='font-size: 12pt' orientation='$formato'>
                    <page_header>
                            <table class='page_header'>
                                    <tr>
                                            <td style='width:100%; text-align: center'>
                                                    <img src=\"".$url."/images/logo.jpg\">
                                            </td>
                                    </tr>
                            </table>
                    </page_header>
                    <page_footer>
                            <table class='page_footer'>
                                    <tr>
                                            <td style='width: 100%; text-align: center'>
                                                <i>Relatório Anual de Atividades da Auditoria Interna - RAINT - exercício ".
                                                    $_GET['exercicio'] . "</i> <br>
                                                    <span class='page'>[[page_cu]]</span>
                                            </td>
                                    </tr>
                            </table>
                    </page_footer>";
        return $retorno; 
    }    

}
