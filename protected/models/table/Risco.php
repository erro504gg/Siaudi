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

Yii::import('application.models.table._base.BaseRisco');

class Risco extends BaseRisco
{
    public $exercicio; 
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
        // busca na tabela de riscos se
        // existe alguma ação com o exercício passado
	public function busca_tabela($exercicio) {
                $schema = Yii::app()->params['schema'];        

                $sql = "select p.valor_exercicio from ". 
                        $schema . ".tb_risco r  LEFT JOIN ".
                        $schema .".tb_processo p ON (r.processo_fk = p.id)
                        WHERE ( p.valor_exercicio='".$exercicio."')
                        group by p.valor_exercicio";

                $command = Yii::app()->db->createCommand($sql);
                $result = $command->query();
               return ($result->readAll());
    }
        

        
        // busca nas tabelas de subriscos se
        // existe algum processo com o exercício passado
	public function RecuperaNota($processo,$criterio) {
                $schema = Yii::app()->params['schema'];        

                // para cada critério, pega os subcritérios e pesos
                $sql = "select id, valor_peso from ". 
                        $schema . ".tb_subcriterio
                        WHERE (criterio_fk=".$criterio.")";

                $command = Yii::app()->db->createCommand($sql);
                $result = $command->query();
                $subcriterios = $result->readAll(); 
                
                
                foreach($subcriterios as $vetor){
                    // soma os pesos do subcritérios para fazer média composta                    
                    $subcriterios_peso +=$vetor['valor_peso']; 
                    
                    // procura pelas notas na tabela subrisco com o subcritério
                    $sql = "select numero_nota from ". 
                            $schema . ".tb_subrisco
                            WHERE (subcriterio_fk=".$vetor['id']." and 
                                    processo_fk =".$processo.")";     
                    $command = Yii::app()->db->createCommand($sql);
                    $result = $command->query();
                    $rs= $result->read();
                    $nota +=$rs[numero_nota]*$vetor['valor_peso'];
                }
                if ($subcriterios_peso > 0){
                	$nota = $nota /$subcriterios_peso;
                }              
               return ($nota);
    }
}
