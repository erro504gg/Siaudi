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

Yii::import('application.models.table._base.BaseProcesso');

class Processo extends BaseProcesso
{
    public $especie_auditoria; 
    public $valor_exercicio;
    public $repetir_todo_ano;   
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
			'id' => Yii::t('app', 'ID'),
			'nome_processo' => Yii::t('app', 'Processo'),
			'tipo_processo_fk' => null,
			'valor_exercicio' => Yii::t('app', 'Exercício'),
			'processo' => Yii::t('app', 'Processo'),
        	'repetir_todo_ano' => Yii::t('app', 'Repetir todo ano'),
			'tipoProcessoFk' => null,
        );
        return array_merge($attribute_default, $attribute_custom);
    }
    

    // carrega todas os processos (e tipo vinculados)
    // para montar a tabela de risco
    public function carrega_tabela_risco($exercicio) {
                $schema = Yii::app()->params['schema'];        
                $sql = "SELECT p.*, t.nome_tipo_processo FROM ". $schema . ".tb_processo p LEFT JOIN ".
                        $schema .".tb_tipo_processo t ON (p.tipo_processo_fk=t.id)
                        WHERE ( p.valor_exercicio='".$exercicio."')
                        ORDER BY t.nome_tipo_processo DESC, p.nome_processo ASC ";
                $command = Yii::app()->db->createCommand($sql);
                $result = $command->query();
               return ($result->readAll());
    }

    
    // carrega todos os processos (e tipo de ações vinculadas)
    // para montar a tabela de subrisco
    public function carrega_tabela_subrisco($criterio) {
                $schema = Yii::app()->params['schema'];        
                $sql = "SELECT p.*, t.nome_tipo_processo FROM ". $schema . ".tb_processo p LEFT JOIN ".
                        $schema .".tb_tipo_processo t ON (p.tipo_processo_fk=t.id)
                        WHERE ( p.valor_exercicio=(SELECT valor_exercicio FROM ".$schema.".tb_criterio where id=".$criterio.") )
                        ORDER BY t.nome_tipo_processo DESC, p.nome_processo ASC ";
                $command = Yii::app()->db->createCommand($sql);
                $result = $command->query();
               return ($result->readAll());
    }
    
    //    Atualiza os processos que estão marcados para repetir todo os anos.
    public function AtualizaProcessosAutomaticamente() {
    	$schema = Yii::app()->params['schema'];
    	$processos = Processo::model()->findAll("repetir_todo_ano = 't'");
    	foreach ($processos as $vetor_processo) {
    
    		$model_processo = new Processo();
    		$model_processo->valor_exercicio = ((int)$vetor_processo->valor_exercicio) + 1;
    		$model_processo->nome_processo = $vetor_processo->nome_processo;
    		$model_processo->tipo_processo_fk =$vetor_processo->tipo_processo_fk;
    		$model_processo->repetir_todo_ano =$vetor_processo->repetir_todo_ano;
    		
    		$vetor_processo->repetir_todo_ano = 0;
    		$vetor_processo->save();
    		
    		$model_processo->save();
    		
    		$novo_id = Yii::app()->db->getLastInsertID($schema.".tb_processo_id_seq");
    		
    		$processo_risco_pre = ProcessoRiscoPre::model()->findAll("processo_fk=". $vetor_processo->id);
    		if (sizeof($processo_risco_pre)){
    			foreach($processo_risco_pre as $vetor_processo_risco_pre){
    				$model_processo_risco_pre = new ProcessoRiscoPre();
    				$model_processo_risco_pre->processo_fk = $novo_id;
    				$model_processo_risco_pre->risco_pre_fk = $vetor_processo_risco_pre->risco_pre_fk;
    				$model_processo_risco_pre->save();
    			}
    		}
    		
    	}
    }    
    
        
}
