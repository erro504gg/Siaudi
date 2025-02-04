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

Yii::import('application.models.table._base.BaseItem');

class Item extends BaseItem
{
        public $relatorio_fk, $unidade_administrativa_fk, $nome_capitulo,
               $especie_auditoria_fk, $categoria_fk, $diretoria_fk, $numero_capitulo;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'id' => Yii::t('app', 'ID do Item'),
            'capitulo_fk' => null,
            'numero_item' => Yii::t('app', 'Número'),
            'nome_item' => Yii::t('app', 'Título do Item'),
            'nome_capitulo' => Yii::t('app', 'Título do Capítulo'),
            'descricao_item' => Yii::t('app', 'Descrição'),
            'data_gravacao' => Yii::t('app', 'Data de Gravação'),
            'valor_reais' => Yii::t('app', 'Valor - R$'),
            'capituloFk' => null,
            'relatorio_fk' => Yii::t('app', 'ID do Relatório'),
            'unidade_administrativa_fk' => Yii::t('app', 'Unidade Auditada'),
            'especie_auditoria_fk' => Yii::t('app', 'Espécie de Auditoria'),
            'categoria_fk' => Yii::t('app', 'Categoria'),
            'diretoria_fk' => Yii::t('app', 'Diretoria'),
            'numero_capitulo' => Yii::t('app', 'Número do Capítulo'),
        	'valor_nao_se_aplica' => Yii::t('app', 'Não se aplica'),
        );
        return array_merge($attribute_default, $attribute_custom);
    }      
    
//     public function beforeSave() {
// //     	$this->isNewRecord = true;
//     	if ($this->valor_nao_se_aplica){
//     		$this->valor_reais = null;
//     	}
//     	parent::beforeSave();
//     }    
    
    
	public function afterFind() {
        parent::afterFind();
	if (isset($this->data_gravacao)) $this->data_gravacao   = MyFormatter::converteData($this->data_gravacao);
        if (isset($this->valor_reais))   $this->valor_reais     = MyFormatter::formataMoeda($this->valor_reais);
        
    }     
    
        // pega o relatório do item
        public function ItemRelatorio($id_item,$link=null,$homologados=null){
            $item = $this->findByAttributes(array('id'=>$id_item));
            $capitulo = Capitulo::model()->findByAttributes(array('id'=>$item->capitulo_fk));
            $relatorio = Relatorio::model()->findByAttributes(array('id'=>$capitulo->relatorio_fk));                        
            $especie_auditoria = EspecieAuditoria::model()->findByAttributes(array('id'=>$relatorio->especie_auditoria_fk));
            
            $relatorio_nome = $relatorio->id ." - ". $especie_auditoria->sigla_auditoria;
            
            // se homologados, então testa se o relatório foi homolgoado para pegar seu número            
            if ($homologados) {
                if ($relatorio->numero_relatorio) {
                    $ano = explode("/",$relatorio->data_relatorio);
                    $ano = $ano[2];
                    $relatorio_nome = $relatorio->numero_relatorio . "/" . $ano ." - " .$especie_auditoria->sigla_auditoria;
                } else {
                    $relatorio_nome = "ID " . $relatorio->id . " - " . $especie_auditoria->sigla_auditoria;
                }
            }
                if($link){ 
                    $retorno="<a href='../relatorio/" . $relatorio->id . "'>" .$relatorio_nome. "</a>";
                } else { 
                    $retorno=$relatorio_nome;
                }
            return $retorno; 
        }        
        
        // pega somente o ID do relatório do item
        public function ItemRelatorioID($id_item){
            $item = $this->findByAttributes(array('id'=>$id_item));
            $capitulo = Capitulo::model()->findByAttributes(array('id'=>$item->capitulo_fk));
            $relatorio = Relatorio::model()->findByAttributes(array('id'=>$capitulo->relatorio_fk));                        
            return $relatorio->id;
        }             
        
        
	public function search() {

                $schema = Yii::app()->params['schema'];            
		$criteria = new CDbCriteria;
                $criteria->select = 'relatorio.*,item.*,capitulo.*, item.id as id';
                $criteria->alias = 'item';
                $join='LEFT JOIN '.$schema.'.tb_capitulo capitulo ON (item.capitulo_fk=capitulo.id)
                       LEFT JOIN '.$schema.'.tb_relatorio relatorio ON (capitulo.relatorio_fk=relatorio.id)';
                if ($this->diretoria_fk || $this->unidade_administrativa_fk){
                    $join.='LEFT JOIN '.$schema.'.tb_relatorio_diretoria RD ON (RD.relatorio_fk=relatorio.id) 
                            LEFT JOIN '.$schema.'.tb_relatorio_sureg RS ON (RS.relatorio_fk=relatorio.id)';
                    if ($this->diretoria_fk){ $criteria->addcondition('RD.diretoria_fk='. $this->diretoria_fk); }
                    if ($this->unidade_administrativa_fk){ $criteria->addcondition('RS.unidade_administrativa_fk='. $this->unidade_administrativa_fk); }                    
                   $criteria->distinct='item.id';
                }
                
                $criteria->join=$join;
		$criteria->compare('relatorio.id', $this->relatorio_fk);
		$criteria->compare('relatorio.especie_auditoria_fk', $this->especie_auditoria_fk);
                $criteria->compare('capitulo.id', $this->capitulo_fk);                
                if($this->numero_capitulo) {
                        $criteria->addCondition('capitulo.numero_capitulo=\''.strtoupper($this->numero_capitulo)."'");
                }
		$criteria->compare('relatorio.categoria_fk', $this->categoria_fk);
                $criteria->addCondition('relatorio.data_relatorio IS NULL');                
                
                $criteria->order = 'relatorio.id DESC, capitulo.numero_capitulo_decimal ASC, item.id ASC';
                //$criteria->group='capitulo.id,capitulo.numero_capitulo,capitulo.relatorio_fk,capitulo.nome_capitulo,capitulo.descricao_capitulo,capitulo.data_gravacao,capitulo.numero_capitulo_decimal';
                
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria )
		);
        }                
}
