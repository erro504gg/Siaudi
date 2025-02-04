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
Yii::import('application.models.table._base.BaseCapitulo');

class Capitulo extends BaseCapitulo
{
    public $unidade_administrativa_fk, $especie_auditoria_fk, $categoria_fk, $diretoria_fk;
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'id' => Yii::t('app', 'ID'),
            'relatorio_fk' => Yii::t('app', 'ID do Relatório'),
            'numero_capitulo' => Yii::t('app', 'Número'),
            'numero_capitulo2' => Yii::t('app', 'Número do Capítulo'),
            'nome_capitulo' => Yii::t('app', 'Título do Capítulo'),
            'descricao_capitulo' => Yii::t('app', 'Descrição'),
            'data_gravacao' => Yii::t('app', 'Data de Gravação'),
            'numero_capitulo_decimal' => Yii::t('app', 'Número Decimal'),
            'unidade_administrativa_fk' => Yii::t('app', 'Unidade Auditada'),
            'relatorioFk' => null,
            'especie_auditoria_fk' => Yii::t('app', 'Espécie de Auditoria'),
            'categoria_fk' => Yii::t('app', 'Categoria'),
            'diretoria_fk' => Yii::t('app', 'Diretoria'),
        );
        return array_merge($attribute_default, $attribute_custom);
    }     
    
    
	public function afterFind() {
        parent::afterFind();
	if (isset($this->data_gravacao))   $this->data_gravacao   = MyFormatter::converteData($this->data_gravacao);
    }      
        
    
    
    // recebe o ID do capítulo e retorna seu título para view 
    public function capitulo_titulo($id_capitulo){     
       $capitulo = $this->model()->findByAttributes(array('id'=>trim($id_capitulo)));
       return $capitulo->nome_capitulo;
    }      
    
    
        // pega o relatório do capítulo
        public function CapituloRelatorio($id_capitulo,$link=null){
            $capitulo = Capitulo::model()->findByAttributes(array('id'=>$id_capitulo));
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
                $criteria->select = 'relatorio.*, capitulo.*, capitulo.id as id';
                $criteria->alias = 'capitulo';
                $join='LEFT JOIN '.$schema.'.tb_relatorio relatorio ON (capitulo.relatorio_fk=relatorio.id)';
                if ($this->diretoria_fk || $this->unidade_administrativa_fk){
                    $join.='LEFT JOIN '.$schema.'.tb_relatorio_diretoria RD ON (RD.relatorio_fk=relatorio.id) 
                            LEFT JOIN '.$schema.'.tb_relatorio_sureg RS ON (RS.relatorio_fk=relatorio.id)';
                    if ($this->diretoria_fk){ $criteria->addcondition('RD.diretoria_fk='. $this->diretoria_fk); }
                    if ($this->unidade_administrativa_fk){ $criteria->addcondition('RS.unidade_administrativa_fk='. $this->unidade_administrativa_fk); }                    
                   $criteria->distinct='capitulo.id';                    
                }
                
                $criteria->join=$join;
		$criteria->compare('relatorio.id', $this->relatorio_fk);
		$criteria->compare('relatorio.especie_auditoria_fk', $this->especie_auditoria_fk);
                if($this->numero_capitulo) {
                        $criteria->addCondition('capitulo.numero_capitulo=\''.strtoupper($this->numero_capitulo)."'");
                }
		$criteria->compare('relatorio.categoria_fk', $this->categoria_fk);
                $criteria->addCondition('relatorio.data_relatorio IS NULL');                
                
                $criteria->order = 'relatorio.id ASC, capitulo.numero_capitulo_decimal ASC';
                //$criteria->group='capitulo.id,capitulo.numero_capitulo,capitulo.relatorio_fk,capitulo.nome_capitulo,capitulo.descricao_capitulo,capitulo.data_gravacao,capitulo.numero_capitulo_decimal';
                
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria )
		);
        }        
        
}
