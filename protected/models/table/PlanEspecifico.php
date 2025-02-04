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

Yii::import('application.models.table._base.BasePlanEspecifico');

class PlanEspecifico extends BasePlanEspecifico
{
    public $data_log_formatado; 
    public $numero_acao; 
    public $plan_auditor;
    public $display_acao;
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
        'id' => Yii::t('app', 'ID'),
        'valor_exercicio' => Yii::t('app', 'Exercício'),
        'observacao_representatividade' => Yii::t('app', 'Representatividade e Amplitude'),
        'observacao_amostragem' => Yii::t('app', 'Amostragem'),
        'data_log' => Yii::t('app', 'Data'),
        'data_log_formatado' => Yii::t('app', 'Data'),
        'id_usuario_log' => Yii::t('app', 'Id Usuario'),
        'valor_sureg' => Yii::t('app', 'Sureg'),
       	'escopo_acao' => Yii::t('app', 'Escopo da Ação'),
        'observacao_questoes_macro' => Yii::t('app', 'Questões Macro'),
        'observacao_resultados' => Yii::t('app', 'Resultados Esperados'),
        'observacao_legislacao' => Yii::t('app', 'Legislação'),
        'observacao_detalhamento' => Yii::t('app', 'Detalhamento dos Procedimentos'),
        'observacao_tecnicas_auditoria' => Yii::t('app', 'Técnicas de Auditoria'),
        'observacao_pendencias' => Yii::t('app', 'Pendências junto à CGU e TCU'),
        'observacao_custos' => Yii::t('app', 'Custos'),
        'observacao_cronograma' => Yii::t('app', 'Cronograma'),
        'acao_fk' => null,
        'acaoFk' => null,
        'numero_acao' => Yii::t('app', 'Nº da Ação'),
        'plan_auditor' => Yii::t('app', 'Auditores'),
        'unidade_administrativa_fk' => Yii::t('app', 'Sureg de Substituição'),
        'data_inicio_atividade' => Yii::t('app', 'Data de Início das Atividades'),
		'objetoFk' => Yii::t('app', 'Objeto'),
        );
        return array_merge($attribute_default, $attribute_custom);
    }
    
    
    
    // recebe o ID da acao e da Unidade Regional
    // e calcula o número da ação. Ex: 7.1
        public function numero_acao_por_sureg($acao_id,$sureg_id){
           $suregs = AcaoSureg::model()->findAllByAttributes(array('acao_fk'=>$acao_id->id));
           $contador==0; 
           foreach ($suregs as $vetor){
               $contador++;               
               if ($vetor->unidade_administrativa_fk==$sureg_id) { 
                   $numero_acao = $acao_id->numero_acao. "." . $contador; 
                   return ($numero_acao);
               }
           }
        }    
    

    	public function afterFind() {
    	parent::afterFind();
        $this->data_log_formatado = MyFormatter::converteTimeStamp($this->data_log);
        if (isset($this->data_inicio_atividade)) $this->data_inicio_atividade = MyFormatter::converteData($this->data_inicio_atividade);
        $this->numero_acao = $this->numero_acao_por_sureg($this->acaoFk,$this->valor_sureg);
        if ($this->valor_exercicio != date(Y)){
        	$this->display_acao = $this->numero_acao . " - " . $this->acaoFk->nome_acao . ' - ' . $this->valor_exercicio;
        } else {
        	$this->display_acao = $this->numero_acao . " - " . $this->acaoFk->nome_acao;
        }
    }    
 
    
    // Verifica se usuário pode acessar o Planejamento Específico para edição.
    // Pela regra atual, somente os perfis de Gerente e Auditor
    // podem altera-lo. 
    public function acesso_PlanEspecifico() {
        $perfil = strtolower(Yii::app()->user->role);
        $perfil = str_replace("siaudi2", "siaudi", $perfil);              

        if (!($perfil=="siaudi_gerente" || $perfil=="siaudi_auditor" || $perfil=="siaudi_gerente_nucleo")) {
            return 0;
        }
        
        return 1; 
    }       
        
}
