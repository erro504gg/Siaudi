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

Yii::import('application.models.table._base.BaseHomensHora');

class HomensHora extends BaseHomensHora
{
    public $subtotal, $total; 
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'id' => Yii::t('app', 'ID'),
            'valor_exercicio' => Yii::t('app', 'Exercício'),
            'valor_asterisco' => Yii::t('app', 'Asterisco(s)'),
            'usuario_fk' => null,
            'valor_horas_homem' => Yii::t('app', 'Horas / Homem'),
            'valor_ferias' => Yii::t('app', 'Férias'),
            'valor_lic_premio' => Yii::t('app', 'Licença Prêmio'),
            'valor_outros' => Yii::t('app', 'Outros'),
            'usuarioFk' => null,
        );
        return array_merge($attribute_default, $attribute_custom);
    }           
    
    
        // busca na tabela de homens/horas
        // para inserir no paint. Parâmetro passado
        // é o exercício do paint, logo o da tabela é o exercício
        // anterior 
	public function busca_tabela($exercicio) {
                $schema = Yii::app()->params['schema'];        

                $sql = "select a.nome_usuario, a.perfil_fk, hh.valor_asterisco, hh.valor_horas_homem,
                                hh.valor_ferias, hh.valor_lic_premio, hh.valor_outros,
                                (coalesce(hh.valor_ferias,0) + coalesce(hh.valor_lic_premio,0) + coalesce(hh.valor_outros,0) ) as subtotal, 
                                (coalesce(hh.valor_horas_homem ,0) - coalesce(hh.valor_ferias,0) + coalesce(hh.valor_lic_premio,0) - coalesce(hh.valor_outros,0) ) as total 
                    from ". 
                        $schema . ".tb_usuario a  LEFT JOIN ".
                        $schema .".tb_homens_hora hh ON (a.id = hh.usuario_fk)
                        WHERE ( hh.valor_exercicio='".$exercicio."')
                        ORDER BY a.nome_usuario ASC";

                $command = Yii::app()->db->createCommand($sql);
                $result = $command->query();
               return ($result->readAll());
    }    
}
