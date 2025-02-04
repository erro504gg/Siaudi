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

Yii::import('application.models.table._base.BaseSubrisco');

class Subrisco extends BaseSubrisco
{
    public $criterio_fk; 
    public $exercicio, $valor_exercicio;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        

    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
                'id' => Yii::t('app', 'ID'),
                'subcriterio_fk' => null,
                'processo_fk' => null,
                'numero_nota' => Yii::t('app', 'Nota'),
                'data_log' => Yii::t('app', 'Data'),
                'id_usuario' => Yii::t('app', 'Id do Usuário'),
                'processoFk' => null,
                'subcriterioFk' => null,
                'criterio_fk' => Yii::t('app', 'Critério Principal'),
                'valor_exercicio' => Yii::t('app', 'Exercício'),
        );
        return array_merge($attribute_default, $attribute_custom);
    }
                
        
    // carrega o tipo de critério (relevância estratégica, materialidade, etc)
    // de acordo com o critério
    public function RecuperaNota($processo,$criterio) {
                $schema = Yii::app()->params['schema'];        
                $sql = "SELECT s.numero_nota FROM ". $schema . ".tb_subrisco s
                        WHERE ( s.subcriterio_fk=".$criterio." AND 
                                s.processo_fk = ".$processo.")";
                $command = Yii::app()->db->createCommand($sql);
                $result = $command->query();
               return ($result->readAll());
    }
    
    // carrega o tipo de critério (relevância estratégica, materialidade, etc)
    // de acordo com o ano de exercicio 
    public function RecuperaCriterioPorExercicio($ano_exercicio) {
        $schema = Yii::app()->params['schema'];        
//        $sql = "SELECT criterio.*, tipo_criterio.*, criterio.nome_criterio as nome_criterio 
//                  FROM ".$schema.".tb_subrisco as subrisco 
//                 INNER JOIN ".$schema.".tb_processo as processo ON 
//                    subrisco.processo_fk = processo.id 
//                 INNER JOIN ".$schema.".tb_subcriterio as subcriterio ON 
//                    subrisco.subcriterio_fk = subcriterio.id 
//                 INNER JOIN ".$schema.".tb_criterio as criterio ON 
//                    criterio.id = subcriterio.criterio_fk
//                 INNER JOIN ".$schema.".tb_tipo_criterio as tipo_criterio ON 
//                    tipo_criterio.id = criterio.tipo_criterio_fk                    
//                 WHERE processo.valor_exercicio = ".$ano_exercicio.
//                 "ORDER BY criterio.nome_criterio";
		$sql = "SELECT criterio.id, criterio.nome_criterio as nome_criterio 
				  FROM ".$schema.".tb_criterio as criterio
				 INNER JOIN ".$schema.".tb_subcriterio as subcriterio ON 
				    criterio.id = subcriterio.criterio_fk             
				 WHERE criterio.valor_exercicio = ".$ano_exercicio."
				 group by criterio.id, criterio.nome_criterio
				 ORDER BY criterio.nome_criterio";
        
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        return ($result->readAll());
        
    }
    
    
    
    // busca na tabela de subriscos se
    // existe alguma ação com o exercício passado
    public function salvar_tabela_risco($dados) {
            $schema = Yii::app()->params['schema'];        

            $vetor_acao = (array_keys($dados)) ;
            $cont_acao=0; 
            foreach ($dados as $vetor_criterio){
                $vetor_criterio2 = (array_keys($vetor_criterio));
                $cont_criterio=0;
                foreach ($vetor_criterio as $criterios){
                    $id_acao = $vetor_acao[$cont_acao];                        
                    $id_criterio = $vetor_criterio2[$cont_criterio];
                    $nota = $criterios;
                    $this->processo_fk = $id_acao; 
                    $this->subcriterio_fk = $id_criterio;
                    $this->numero_nota = str_replace(",",".",$nota); 
                    $this->data_log = date("Y-m-d H:i:s");
                    $this->id_usuario = Yii::app()->user->id;
                    //$this->save();
                    
                    // procura se existe o registro na tabela
                    $sql = "select * from ". 
                            $schema . ".tb_subrisco
                            WHERE (processo_fk=". $this->processo_fk ." and
                            subcriterio_fk=".$this->subcriterio_fk.")";
                    $command = Yii::app()->db->createCommand($sql);
                    $result = $command->query();                 
                    
                    
                    if ($this->numero_nota==""){
                        // acao = delete
                         $this->deleteAll('processo_fk=:processo and subcriterio_fk=:subcriterio',  array('processo' =>$this->processo_fk, 'subcriterio' => $this->subcriterio_fk));
                    } else {
                    if ($result->readAll()) { 
                        // acao = update
                        $sql = "UPDATE ". 
                                $schema . ".tb_subrisco
                                SET 
                                numero_nota={$this->numero_nota},
                                data_log='{$this->data_log}', 
                                id_usuario={$this->id_usuario} 
                                WHERE (processo_fk=". $this->processo_fk ." and
                                subcriterio_fk=".$this->subcriterio_fk.")";
                        $command = Yii::app()->db->createCommand($sql);
                        $result = $command->query();                             
                        
                     } else {
                       // acao = insert
                        $sql = "INSERT INTO ". 
                                $schema . ".tb_subrisco
                                (processo_fk, subcriterio_fk, numero_nota, data_log, id_usuario)
                                VALUES ({$this->processo_fk}, 
                                {$this->subcriterio_fk}, 
                                {$this->numero_nota},
                                '{$this->data_log}',
                                {$this->id_usuario} )";
                        $command = Yii::app()->db->createCommand($sql);
                        $result = $command->query();                                  
                     }
                    }
                    $cont_criterio++;
                }
                $cont_acao++;
            }
    }    
                
}
