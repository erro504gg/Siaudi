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

Yii::import('application.models.table._base.BaseEspecieAuditoria');

class EspecieAuditoria extends BaseEspecieAuditoria
{

    public $especie_auditoria; 

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'id' => Yii::t('app', 'ID'),
            'nome_auditoria' => Yii::t('app', 'Nome'),
            'sigla_auditoria' => Yii::t('app', 'Sigla'),
            'especie_auditoria' => Yii::t('app', 'Espécie de Auditoria'),
        );
        return array_merge($attribute_default, $attribute_custom);
    }
    
    
    // recebe ID do Relatório e retorna a espécie da auditoria
    public function EspecieAuditoria_Relatorio($id_relatorio) {
        $relatorio = Relatorio::model()->find('id='.$id_relatorio);
        $especie_auditoria=$this->find('id='.$relatorio->especie_auditoria_fk);
        return ($especie_auditoria->nome_auditoria);
    }
    
    // recebe ID do capitulo e retorna a espécie da auditoria
    public function EspecieAuditoria_Capitulo($id_capitulo) {
        $capitulo = Capitulo::model()->find('id='.$id_capitulo); 
        $relatorio = Relatorio::model()->find('id='.$capitulo->relatorio_fk);
        $especie_auditoria=$this->find('id='.$relatorio->especie_auditoria_fk);
        return ($especie_auditoria->nome_auditoria);
    }
            
        
}
