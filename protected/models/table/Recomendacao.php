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

Yii::import('application.models.table._base.BaseRecomendacao');

class Recomendacao extends BaseRecomendacao
{
    public $relatorio_fk, $capitulo_fk, $unidade_administrativa_fk, 
            $especie_auditoria_fk, $categoria_fk, $diretoria_fk, $numero_capitulo,
            $numero_item, $numero_recomendacao, $qt_cliente, $qt_auditor, 
            $ultimo_st, $numero_relatorio, $bolRecomendacaoPadrao;
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();
        $attribute_custom = array(
            'id' => Yii::t('app', 'ID da Recomendação'),
            'numero_recomendacao' => Yii::t('app', 'Número'),
            'relatorio_fk' =>  Yii::t('app', 'ID do Relatório'),
            'capitulo_fk' => Yii::t('app', 'Capítulo'),
            'item_fk' => null,
            'recomendacao_tipo_fk' => null,
            'recomendacao_gravidade_fk' => null,
            'recomendacao_categoria_fk' => null,
            'recomendacao_subcategoria_fk' => null,
            'descricao_recomendacao' => Yii::t('app', 'Recomendação'),
            'data_gravacao' => Yii::t('app', 'Data de Gravação'),
            'recomendacaoSubcategoriaFk' => null,
            'recomendacaoCategoriaFk' => null,
            'recomendacaoGravidadeFk' => null,
            'recomendacaoTipoFk' => null,
            'itemFk' => null,
            'capituloFk' => null,
            'relatorioFk' => Yii::t('app', 'ID do Relatório'),
            'unidade_administrativa_fk' => Yii::t('app', 'Unidade Auditada'),
            'especie_auditoria_fk' => Yii::t('app', 'Espécie de Auditoria'),
            'categoria_fk' => Yii::t('app', 'Categoria'),
            'diretoria_fk' => Yii::t('app', 'Diretoria'),
            'numero_capitulo' => Yii::t('app', 'Nº Capítulo'),
            'numero_item' => Yii::t('app', 'Nº Item'),
            'numero_recomendacao' => Yii::t('app', 'Nº Recomendação'),
            'qt_cliente' => Yii::t('app', 'Qtde. Resp. Cliente'),
            'qt_auditor' => Yii::t('app', 'Qtde. Resp. Auditor'),            
            'ultimo_st' => Yii::t('app', 'Último Status'),
            'numero_relatorio' => Yii::t('app', 'Nº Relatório'),            
            
        );
        return array_merge($attribute_default, $attribute_custom);
    }
    
    
    
	public function afterFind() {
        parent::afterFind();
	if (isset($this->data_gravacao))   $this->data_gravacao   = MyFormatter::converteData($this->data_gravacao);
    }      
             
        // pega o capítulo da recomendação
        public function RecomendacaoCapitulo($id_recomendacao,$link=null){
            $recomendacao = Recomendacao::model()->findbyPk($id_recomendacao);            
            $item = Item::model()->findByPk($recomendacao->item_fk);
            $capitulo = Capitulo::model()->findByAttributes(array('id'=>$item->capitulo_fk));
                if($link){ 
                    $retorno="<a href='../capitulo/" . $capitulo->id . "'>" .$capitulo->numero_capitulo . "</a>";
                } else { 
                    $retorno=$capitulo->numero_capitulo;
                }
            return $retorno; 
        }
        

        // pega o relatório da recomendação
        public function RecomendacaoRelatorio($id_recomendacao,$link=null){ 
            $recomendacao = Recomendacao::model()->findbyPk($id_recomendacao);
            $item = Item::model()->findByPk($recomendacao->item_fk);
            $capitulo = Capitulo::model()->findByAttributes(array('id'=>$item->capitulo_fk));
            $relatorio = Relatorio::model()->findByAttributes(array('id'=>$capitulo->relatorio_fk));                        
            $especie_auditoria = EspecieAuditoria::model()->findByAttributes(array('id'=>$relatorio->especie_auditoria_fk));
            $relatorio_nome = $relatorio->id ." - ". $especie_auditoria->sigla_auditoria;
                if($link){ 
                    $retorno="<a href='../relatorio/" . $relatorio->id . "'>" .$relatorio_nome. "</a>";
                } else { 
                    $retorno=$relatorio_nome;
                }
            return $retorno; 
        }        
    
        
	public function search() {
                $schema = Yii::app()->params['schema'];            
		$criteria = new CDbCriteria;
                $criteria->select = 'relatorio.*,capitulo.*,item.*,recomendacao.*, recomendacao.id as id';
                $criteria->alias = 'recomendacao';
                $join='LEFT JOIN '.$schema.'.tb_item item ON (item.id=recomendacao.item_fk)
                       LEFT JOIN '.$schema.'.tb_capitulo capitulo ON (capitulo.id=item.capitulo_fk)
                       LEFT JOIN '.$schema.'.tb_relatorio relatorio ON (relatorio.id=capitulo.relatorio_fk)';
                if ($this->diretoria_fk){
                    $join.='LEFT JOIN '.$schema.'.tb_relatorio_diretoria RD ON (RD.relatorio_fk=relatorio.id)';
                    $criteria->addcondition('RD.diretoria_fk='. $this->diretoria_fk); 
                    $criteria->distinct='manifestacao.id';
                }
                
                $criteria->join=$join;
		$criteria->compare('relatorio.id', $this->relatorio_fk);
		$criteria->compare('relatorio.especie_auditoria_fk', $this->especie_auditoria_fk);
                $criteria->compare('recomendacao.unidade_administrativa_fk', $this->unidade_administrativa_fk);  
                $criteria->compare('recomendacao.item_fk', $this->item_fk);  
                $criteria->compare('recomendacao.id', $this->id);  
                if($this->numero_capitulo) {
                        $criteria->addCondition('capitulo.numero_capitulo=\''.strtoupper($this->numero_capitulo)."'");
                }
		$criteria->compare('relatorio.categoria_fk', $this->categoria_fk);
                $criteria->addCondition('relatorio.data_relatorio IS NULL');                
                $criteria->order = 'relatorio.id DESC, capitulo.numero_capitulo_decimal ASC';

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria )
		);
        }          
        
		public function RetornaNumeroRecomendacao($numero_item, $numero_recomendacao){
			if (($numero_item) || ($numero_recomendacao)){
				return $numero_item . "." . $numero_recomendacao;
			} else {
				return "";
			}
		}
        
        
        // Retorna o número de recomendações de um relatório específico.
        // parâmetro de entrada: ID do relatório
        public function RecomendacaoporRelatorio($id_relatorio){
            $schema = Yii::app()->params['schema'];                        
            $sql = "SELECT recomendacao.id
                        FROM ". $schema . ".tb_relatorio relatorio INNER JOIN
                             ". $schema . ".tb_capitulo capitulo ON capitulo.relatorio_fk=relatorio.id INNER JOIN
                             ". $schema . ".tb_item item ON item.capitulo_fk=capitulo.id INNER JOIN
                             ". $schema . ".tb_recomendacao recomendacao ON recomendacao.item_fk=item.id
                        WHERE  (recomendacao.recomendacao_tipo_fk=(SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomendação%' LIMIT 1)  /* Recomendação */ and
                                relatorio.id=".$id_relatorio.")";
            $command = Yii::app()->db->createCommand($sql);            
            $result = $command->query();
            return ($result->readAll());
        }             
        
}
