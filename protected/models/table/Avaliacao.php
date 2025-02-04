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

Yii::import('application.models.table._base.BaseAvaliacao');

class Avaliacao extends BaseAvaliacao
{
    public $observacao,$nota, $valor_exercicio;
    
    public static function model($className=__CLASS__) {
            return parent::model($className);
    }

    // Verifica se o auditado (unidade administrativa ou lotação) tem algum usuario para avaliar 
    // (de acordo com os usuarioes envolvidos no relatório). Caso haja, retorna o ID 
    // do usuario. Caso não haja, retorna null (e segue o fluxo para a manifestação do auditado
    // ou visualização do relatório).
    // Parâmetros de entrada: relatorio (id do relatório), sureg (id da sureg)
    public function VerificaAvaliacao($relatorio,$sureg){
        // verifica se relatório é do ano de 2014 ou maior 
        // (avaliação não deve ser feita para anos anteriores)
        $Relatorio_completo = Relatorio::model()->findByPk($relatorio);
        $data_gravacao = explode("/",$Relatorio_completo->data_gravacao);
        $ano_gravacao = $data_gravacao[2];
        // pega os auditores do relatório            
        $relatorio_auditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk'=>$relatorio));
        foreach ($relatorio_auditor as $vetor){
            // para cada auditor, verifica se existe avaliação
            $avaliacao = $this->findByAttributes(array('relatorio_fk'=> $relatorio,
                                                       'unidade_administrativa_fk'    => $sureg,
                                                       'usuario_fk'  => $vetor->usuario_fk));
            // se não existe avaliação para este auditor então retorna o ID do auditor
            if (sizeof($avaliacao)==0){
                return $vetor->usuario_fk;
            }
        }    
    }
    
    // Recebe o ID da avaliação previamente salva e insere
    // as respectivas notas na tabela tb_avaliacao_nota
    // Parâmetros de entrada: avaliacao (id da avaliação) e
    // notas (array com os IDs dos critérios e respectivas notas)
    public function SalvaNotas($avaliacao,$notas){
        $schema = Yii::app()->params['schema'];        
        foreach ($notas as $key => $value) {
            $sql_values.="(".$avaliacao.",".$key.",".$value."),";
        }
        $sql_values = substr($sql_values,0,-1);
        
        $sql = "INSERT INTO " . $schema . ".tb_avaliacao_nota
                (avaliacao_fk, avaliacao_criterio_fk, nota) 
                VALUES ".$sql_values." ;";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute();        
    }
    

    
    // Recebe o ID da avaliação previamente salva e insere
    // a observacao na tabela tb_avaliacao_observacao
    // Parâmetros de entrada: avaliacao (id da avaliação) e
    // observacao
    public function SalvaObservacao($avaliacao,$observacao){
        if ($observacao){
            $observacao = str_replace("'",'"',$observacao);
            $schema = Yii::app()->params['schema'];        
            $sql = "INSERT INTO " . $schema . ".tb_avaliacao_observacao
                    (avaliacao_fk, ds_observacao) 
                    VALUES (".$avaliacao.",'".$observacao."'); ";
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();        
        }
    }    
    
    
    /* verifica se o usuário é do tipo "cliente" (auditado),
     * e, neste caso, se as avaliações dos auditores foram preenchidas.
     * Em caso positivo, abre o relatório em PDF. Em caso negativo,
     * abre as avaliação para ser preenchida.
     * 
     * @Relatorio (objeto):  Objeto da classe Relatorio().
     */
    public function VerificaAvaliacaoRelatorio($Relatorio){
        $perfil = strtolower(Yii::app()->user->role);  
        $perfil = str_replace("siaudi2","siaudi",$perfil);

        $login= Yii::app()->user->login;
        $RelatorioAcesso=  RelatorioAcesso::model()->findByAttributes(array('relatorio_fk'=>$Relatorio->id,'nome_login'=>$login));
        $sureg = $RelatorioAcesso->unidade_administrativa_fk;        
        
        //Se for sureg secundaria o cliente não avalia o auditor
        $relatorio_sureg = RelatorioSureg::model()->findByAttributes(array('relatorio_fk'=>$Relatorio->id,'unidade_administrativa_fk'=>$sureg));
        
        if (!$relatorio_sureg->sureg_secundaria){
	        if ($Relatorio->data_relatorio && $perfil=="siaudi_cliente"){
	            // Verifica se existe alguma avaliação de auditor
	            // a ser feita antes de realizar a manifestação
	              $avaliacao_auditor = Avaliacao::model()->VerificaAvaliacao($Relatorio->id,$sureg);
	              // variável $avaliacao_auditor contém o ID do auditor a 
	              // ser avaliado ou vazio caso todas as avaliações já tenham
	              // sido realizadas
	              if ($avaliacao_auditor) {
	                  $confirma = ($_GET["confirma"]) ? "&confirma=1":"";
	                  header("Location:../../Avaliacao/admin/?relatorio={$Relatorio->id}&sureg={$sureg}&auditor={$avaliacao_auditor}{$confirma}&visualizar_pdf=1");
	                  exit; 
	              }             
	            
	        }
        }
    }
    
    
    /* Recuperar Avalição do Auditor 
     * -------------
     * 1 - Obter a avaliação de um determinado auditor em um único exercício. 
     * -------------
     * @exercício (int): ano do exercício. 
     * @usuario_fk (int): identificação (id) do auditor na base de dados.
     */
    public function RecuperaAvaliacaoAuditor($exercicio, $usuario_fk) {
        $schema = Yii::app()->params['schema'];       
        $sql = "select usuario.nome_usuario, avaliacao_criterio.numero_questao,  
                       avaliacao_criterio.descricao_questao, avg(avaliacao_nota.nota) as nota_media 
                  from ". $schema .".tb_avaliacao as avaliacao 
                 inner join ". $schema .".tb_usuario as usuario on 
                    usuario.id = avaliacao.usuario_fk 
                 inner join ". $schema .".tb_avaliacao_nota as avaliacao_nota on  
                    avaliacao_nota.avaliacao_fk = avaliacao.id 
                 inner join ". $schema .".tb_avaliacao_criterio as avaliacao_criterio on 
                    avaliacao_criterio.id = avaliacao_nota.avaliacao_criterio_fk 
                 where avaliacao.usuario_fk = ". $usuario_fk ." 
                   and date_part('year',avaliacao.data) = '". $exercicio ."' 
                 group by usuario.nome_usuario, avaliacao_criterio.numero_questao,  
                          avaliacao_criterio.descricao_questao 
                 order by avaliacao_criterio.numero_questao";
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        
        
        return $dados->readAll();
    }
    
    /* Recuperar Quantidade de Relatórios Homologados 
     * -------------
     * 1 - Obter a quantidade de relatórios homologados de um determinado auditor em um único exercício. 
     * -------------
     * @exercício (int): ano do exercício. 
     * @usuario_fk (int): identificação (id) do auditor na base de dados.
     */
    public function RecuperaQtdeDeRelHomologados($usuario_fk, $exercicio) {
        $schema = Yii::app()->params['schema'];       
        $sql = "SELECT COUNT(1) as total_relatorio_homologado 
                  FROM ".$schema.".tb_relatorio as relatorio  
                 INNER JOIN ".$schema.".tb_relatorio_auditor as relatorio_auditor on 
                       relatorio_auditor.relatorio_fk = relatorio.id 
                 WHERE numero_relatorio is not null 
                   AND data_relatorio is not null 
                   AND date_part('year', data_relatorio) = ".$exercicio." 
                   AND relatorio_auditor.usuario_fk = ".$usuario_fk;  
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        
        return $dados->readAll();
    }
    
    /* Recuperar Quantidade de Relatórios Homologados avaliados
     * -------------
     * 1 - Obter a quantidade de relatórios homologados avaliados de um determinado auditor em um único exercício. 
     * -------------
     * @exercício (int): ano do exercício. 
     * @usuario_fk (int): identificação (id) do auditor na base de dados.
     */
    public function RecuperaQtdeDeRelHomologadosAvaliados($usuario_fk, $exercicio) {
        $schema = Yii::app()->params['schema'];       
        $sql = "SELECT COUNT(1) as total_relatorio_homologado_avaliado
                  FROM ".$schema.".tb_relatorio as relatorio 
                 INNER JOIN ".$schema.".tb_avaliacao as avaliacao on
                       avaliacao.relatorio_fk = relatorio.id
                 INNER JOIN ".$schema.".tb_relatorio_auditor as relatorio_auditor on 
                       relatorio_auditor.relatorio_fk = relatorio.id and 
                       relatorio_auditor.usuario_fk = avaliacao.usuario_fk
                 WHERE numero_relatorio is not null 
                   AND data_relatorio is not null 
                   AND date_part('year', data_relatorio) = ".$exercicio." 
                   AND relatorio_auditor.usuario_fk = ".$usuario_fk;  
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        
        return $dados->readAll();
    }
    
    /* Recuperar Quantidade de Relatórios Finalizados avaliados
     * -------------
     * 1 - Obter a quantidade de relatórios finalizados avaliados de um determinado auditor em um único exercício. 
     * -------------
     * @exercício (int): ano do exercício. 
     * @usuario_fk (int): identificação (id) do auditor na base de dados.
     */
    public function RecuperaQtdeDeRelFinalizadosAvaliados($usuario_fk, $exercicio) {
        $schema = Yii::app()->params['schema'];       
        $sql = "SELECT COUNT(1) as total_relatorio_finalizado_avaliado  
                  FROM ".$schema.".tb_relatorio relatorio 
                 INNER JOIN ".$schema.".tb_avaliacao as avaliacao on
                       avaliacao.relatorio_fk = relatorio.id
                 INNER JOIN ".$schema.".tb_relatorio_auditor as relatorio_auditor on 
                       relatorio_auditor.relatorio_fk = relatorio.id and 
                       relatorio_auditor.usuario_fk = avaliacao.usuario_fk
                 WHERE valor_prazo is not null
                   AND data_finalizado is not null
                   AND date_part('year', data_finalizado) = ".$exercicio."
                   AND relatorio_auditor.usuario_fk = ".$usuario_fk;  

        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        
        return $dados->readAll();
    }
    
    /* Recuperar Quantidade de Relatórios Finalizados
     * -------------
     * 1 - Obter a quantidade de relatórios finalizados de um determinado auditor em um único exercício. 
     * -------------
     * @exercício (int): ano do exercício. 
     * @usuario_fk (int): identificação (id) do auditor na base de dados.
     */
    public function RecuperaQtdeDeRelFinalizados($usuario_fk, $exercicio) {
        $schema = Yii::app()->params['schema'];       
        $sql = "SELECT COUNT(1) as total_relatorio_finalizado  
                  FROM ".$schema.".tb_relatorio as relatorio 
                 INNER JOIN ".$schema.".tb_relatorio_auditor as relatorio_auditor on 
                       relatorio_auditor.relatorio_fk = relatorio.id 
                 WHERE valor_prazo is not null 
                   AND data_finalizado is not null 
                   AND date_part('year', data_finalizado) = ".$exercicio."
                   AND relatorio_auditor.usuario_fk = ".$usuario_fk;  
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }
    
    
    /* Recuperar Quantidade de Avalições 
     * -------------
     * 1 - Obter a quantidade de avaliações de um determinado auditor em um único exercício. 
     * -------------
     * @exercício (int): ano do exercício. 
     * @usuario_fk (int): identificação (id) do auditor na base de dados.
     */
    public function RecuperaQtdeDeAvaliacoes($usuario_fk, $exercicio) {
        $schema = Yii::app()->params['schema'];       
        $sql = "SELECT COUNT(1) as total_avaliacao
                  FROM ".$schema.".tb_relatorio relatorio 
                 INNER JOIN ".$schema.".tb_avaliacao as avaliacao on
                       avaliacao.relatorio_fk = relatorio.id
                 WHERE date_part('year', data) = ".$exercicio."
                   AND avaliacao.usuario_fk = ".$usuario_fk;  
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }
    
    
}
