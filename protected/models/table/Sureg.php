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

Yii::import('application.models.table._base.BaseSureg');

class Sureg extends BaseSureg {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'id' => Yii::t('app', 'ID'),
            'nome' => Yii::t('app', 'Nome'),
            'sigla' => Yii::t('app', 'Sigla'),
            'uf_fk' => Yii::t('app', 'Uf'),
            'subordinante_fk' => Yii::t('app', 'Subordinante'),
        );
        return array_merge($attribute_default, $attribute_custom);
    }

    // recebe o ID da Unidade Regional e retorna o nome
    // (método usado para a buscada funcionalidade
    // Planejamento Específico)
    public function sureg_por_id($id, $sigla = null) {
        $suregs = UnidadeAdministrativa::model()->findByAttributes(array('id' => $id));
        if ($sigla) {
            return $suregs->sigla;
        }
        return $suregs->nome;
    }

    // recebe o ID do Item e retorna as Unidades Regionais associadas
    public function sureg_por_item($id_item) {

        $schema = Yii::app()->params['schema'];
        if ($id_item) {
            $sql = "SELECT sureg.id, sureg.sigla
                        FROM " . $schema . ".tb_unidade_administrativa sureg,
                            " . $schema . ".tb_relatorio_sureg relatorio_sureg,
                            " . $schema . ".tb_relatorio relatorio,
                            " . $schema . ".tb_capitulo capitulo,
                             " . $schema . ".tb_item item
                        WHERE (relatorio_sureg.unidade_administrativa_fk =sureg.id AND 
                               relatorio_sureg.relatorio_fk = relatorio.id AND
                               relatorio.id = capitulo.relatorio_fk AND
                               capitulo.id = item.capitulo_fk AND
                               item.id={$id_item})     
                        ORDER BY sureg.sigla ASC";

            $command = Yii::app()->db->createCommand($sql);
            $result = $command->query();
            return ($result->readAll());
        } else {
            return array("vazio");
        }
    }

}
