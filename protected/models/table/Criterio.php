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

Yii::import('application.models.table._base.BaseCriterio');

class Criterio extends BaseCriterio {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'id' => Yii::t('app', 'ID'),
            'valor_exercicio' => Yii::t('app', 'Exercício'),
            'nome_criterio' => Yii::t('app', 'Critério'),
            'tipo_criterio_fk' => null,
            'valor_peso' => Yii::t('app', 'Peso'),
            'descricao_criterio' => Yii::t('app', 'Descrição'),
            'tipoCriterioFk' => null,
        );
        return array_merge($attribute_default, $attribute_custom);
    }

    // carrega o tipo de critério (relevância estratégica, materialidade, etc)
    // de acordo com o critério
    public function carrega_criterios_subrisco($criterio) {
        $schema = Yii::app()->params['schema'];
        $sql = "SELECT t.nome_criterio, c.valor_exercicio FROM " . $schema . ".tb_tipo_criterio t LEFT JOIN " .
                $schema . ".tb_criterio c ON (t.id=c.tipo_criterio_fk)
                        WHERE ( c.id=" . $criterio . ") order by t.id ASC";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        return ($result->read());
    }

}
