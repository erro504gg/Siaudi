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

Yii::import('application.models.table._base.BaseManifestacao');

class Manifestacao extends BaseManifestacao
{
        public $tipo_relatorio;
        
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();
        $attribute_custom = array(
            'id' => Yii::t('app', 'ID Relatório'),
            'relatorio_fk' => null,
            'nome_login' => Yii::t('app', 'Usuário'),
            'data_manifestacao' => Yii::t('app', 'Data da Manifestação'),
            'descricao_manifestacao' => Yii::t('app', 'Justificativa'),
            'status_manifestacao' => Yii::t('app', 'Status'),
            'descricao_resposta' => Yii::t('app', 'Resposta à Manifestação'),
            'data_resposta' => Yii::t('app', 'Data da Resposta'),
            'unidade_administrativa_fk' => Yii::t('app', 'Unidade Auditada'),
            'relatorioFk' => null,
        );
        return array_merge($attribute_default, $attribute_custom);
    }      
           
    // verifica quais relatórios estão disponíveis
    // para manifestação, de acordo com a SUREG do usuário
    public function RelatorioManifestacao(){
        $login = Yii::app()->user->login;

        $schema = Yii::app()->params['schema'];        
        $sql = "SELECT DISTINCT relatorio.id, especie_auditoria.sigla_auditoria, relatorio_sureg.sureg_secundaria
                FROM ". $schema . ".tb_relatorio relatorio INNER JOIN 
                    ". $schema . ".tb_especie_auditoria especie_auditoria ON relatorio.especie_auditoria_fk= especie_auditoria.id INNER JOIN
                    ". $schema . ".tb_relatorio_acesso relatorio_acesso ON relatorio.id = relatorio_acesso.relatorio_fk LEFT OUTER JOIN
                    ". $schema . ".tb_manifestacao manifestacao ON relatorio.id=manifestacao.relatorio_fk INNER JOIN 
                    ". $schema . ".tb_relatorio_sureg relatorio_sureg ON relatorio.id=relatorio_sureg.relatorio_fk INNER JOIN 
                    ". $schema . ".tb_unidade_administrativa sureg ON relatorio_sureg.unidade_administrativa_fk=sureg.id
                WHERE (relatorio.numero_relatorio IS NULL)
                AND (relatorio.st_libera_homologa IS NULL) 
                AND (data_finalizado IS NOT NULL) 
                AND (relatorio_acesso.nome_login= '".$login."')
                AND relatorio_sureg.sureg_secundaria = false";
        	
            $command = Yii::app()->db->createCommand($sql);
            $result = $command->query();
            return($result->readAll());
    }
    
    
    // verifica dentre os relatórios disponíveis para o cliente,
    // quais estão no prazo.
    public function RelatorioManifestacaoPrazo(){
        $cont=0;
        $relatorios = $this->RelatorioManifestacao();
        if (is_array($relatorios)){
            foreach ($relatorios as $vetor){
                $relatorio = Relatorio::model()->findByAttributes(array('id'=>$vetor[id]));
                $prazo_manifestacao = Feriado::model()->DiasUteis($relatorio->data_finalizado,5);
                $prazo_manifestacao = explode("/",$prazo_manifestacao);
                $prazo_manifestacao = $prazo_manifestacao[2].$prazo_manifestacao[1].$prazo_manifestacao[0];
                // se prazo não expirou, aparece na combo
                if(date("Ymd")<=$prazo_manifestacao){ 
                    $combo[$cont][id] = $vetor[id];
                    $combo[$cont][sigla_auditoria] = $vetor[id] ." - " . $vetor[sigla_auditoria];
                    $combo[$cont][sureg_secundaria] = $vetor[sureg_secundaria];
                    $cont++;
                }
                
            }
        }
        if(sizeof($combo)==0){
            $combo[0][id] = 0;
            $combo[0][sigla_auditoria] = 'Sem relatórios para manifestação.';
            $cont++;            
        }
        return($combo);
    }    


    // busca as manifestações negativas e não respondidas
    // e retorna seus respectivos relatórios
    public function RelatorioManifestacaoResponder(){
        $cont=0;

        $schema = Yii::app()->params['schema'];        
        $sql = "SELECT manifestacao.id as id_manifestacao, relatorio.id as id_relatorio, especie_auditoria.sigla_auditoria, sureg.sigla
                FROM ". $schema . ".tb_relatorio relatorio INNER JOIN 
                     ". $schema . ".tb_especie_auditoria especie_auditoria ON relatorio.especie_auditoria_fk= especie_auditoria.id INNER JOIN
                     ". $schema . ".tb_manifestacao manifestacao ON manifestacao.relatorio_fk = relatorio.id  INNER JOIN
                     ". $schema . ".tb_unidade_administrativa sureg ON manifestacao.unidade_administrativa_fk = sureg.id
                         
                WHERE (relatorio.numero_relatorio IS NULL)
                AND (relatorio.st_libera_homologa IS NULL) 
                AND (relatorio.data_finalizado IS NOT NULL) 
                AND (manifestacao.status_manifestacao=0)
                AND (manifestacao.descricao_resposta IS NULL)                
                ";
            $command = Yii::app()->db->createCommand($sql);
            $result = $command->query();
        $relatorios=($result->readAll());
            
        if (is_array($relatorios)){
            foreach ($relatorios as $vetor){
                    $combo[$cont][id] = $vetor[id_manifestacao];
                    $combo[$cont][sigla_auditoria] = $vetor[id_relatorio] ." - " . $vetor[sigla_auditoria] ." / " . $vetor[sigla];
                    $cont++;                
            }
        }
        if(sizeof($combo)==0){
            $combo[0][id] = 0;
            $combo[0][sigla_auditoria] = 'Sem manifestações para responder.';
            $cont++;            
        }
        return($combo);
    }        

    
    
    // verifica se a manifestação enviada pode liberar
    // o relatório para homologação e envia e-mails
    // às partes interessadas de acordo com o tipo
    // da manifestação (positiva ou negativa).
    // Parâmetros de entrada: id do relatório,
    // manifestação => ('positiva', 'negativa', 'tacita', 'resposta')
    // unidade_administrativa_fk => unidade auditada (quando apenas 1 unidade específica se manifestar)
    public function VerificaManifestacao($id_relatorio,$manifestacao,$unidade_administrativa_fk=null){ 
            $Relatorio = Relatorio::model()->findByAttributes(array('id'=>$id_relatorio));
            $schema = Yii::app()->params['schema'];                            
            // se manifestação positiva
            if($manifestacao=="positiva"){
                // --- Partes interessadas para enviar e-mail ---
                //PORTAL_SPB e-mail para um grupo
                if(Yii::app()->params['emailGrupoAuditoria'] != '')
                    $vetor_email=Yii::app()->params['emailGrupoAuditoria'];
                 
                 // verificar  se número de manifestações POSITIVAS igual a  
                 // número de unidades auditadas e, neste caso, liberar para homologação
                $Manifestacoes = Manifestacao::model()->findAllbyAttributes(array('relatorio_fk'=>$id_relatorio,'status_manifestacao'=>1));
                $RelatorioSureg = RelatorioSureg::model()->findAllbyAttributes(array('relatorio_fk'=>$id_relatorio, 'sureg_secundaria' => false));
                
                if(sizeof($Manifestacoes) == sizeof($RelatorioSureg)){
                   $libera=Relatorio::model()->LiberaHomologa($id_relatorio);       
                   $this->manifestacao_email($id_relatorio,$vetor_email,$manifestacao);
                } 
            }

            // se manifestação negativa
            if($manifestacao=="tacita"){
                // --- Partes interessadas para enviar e-mail ---
                //PORTAL_SPB e-mail para um grupo
                if(Yii::app()->params['emailGrupoAuditoria'] != '')
                    $vetor_email[]=Yii::app()->params['emailGrupoAuditoria'];

                //PORTAL_SPB alterada a forma de gerar e-mail                
                //pega os auditados do relatório
                 $RelatorioAcesso = RelatorioAcesso::model()->findAllbyAttributes(array('relatorio_fk'=>$id_relatorio));
                 if (is_array($RelatorioAcesso)){
                    foreach ($RelatorioAcesso as $vetor){
                        $vetor_email[] = $vetor->email;
                    }
                 }
                
                // pega os gerentes do relatório
                $relatorio_gerente = RelatorioGerente::model()->findAllByAttributes(array('relatorio_fk'=>$id_relatorio));
                foreach ($relatorio_gerente as $vetor){
                    $gerente= Usuario::model()->findByAttributes(array('id'=>$vetor->usuario_fk));
                    $vetor_email[]=$gerente->email;
                }                           
                $this->manifestacao_email($id_relatorio,$vetor_email,$manifestacao);
            }             
            
            // se manifestação negativa
            if($manifestacao=="negativa"){                
                // --- Partes interessadas para enviar e-mail ---
                // pega os auditores do relatório            
                $relatorio_auditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk'=>$id_relatorio));
                foreach ($relatorio_auditor as $vetor){
                    $auditor= Usuario::model()->findByAttributes(array('id'=>$vetor->usuario_fk));
                    $vetor_email[]=$auditor->email;
                }

                // pega os gerentes do relatório
                $relatorio_gerente = RelatorioGerente::model()->findAllByAttributes(array('relatorio_fk'=>$id_relatorio));
                foreach ($relatorio_gerente as $vetor){
                    $gerente= Usuario::model()->findByAttributes(array('id'=>$vetor->usuario_fk));
                    $vetor_email[]=$gerente->email;
                }                           
                $this->manifestacao_email($id_relatorio,$vetor_email,$manifestacao);
            }
            
            if($manifestacao=="resposta"){
                // --- Partes interessadas para enviar e-mail ---
                // pega o cliente da unidade auditada
                $relatorio_auditor = RelatorioAcesso::model()->findAllByAttributes(array('relatorio_fk'=>$id_relatorio,'unidade_administrativa_fk'=>$unidade_administrativa_fk));                
                foreach ($relatorio_auditor as $vetor){
                    $vetor_email[]=$vetor->email;
                }
                
                // pega os auditores do relatório            
                $relatorio_auditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk'=>$id_relatorio));
                foreach ($relatorio_auditor as $vetor){
                    $auditor= Usuario::model()->findByAttributes(array('id'=>$vetor->usuario_fk));
                    $vetor_email[]=$auditor->email;
                }
                $this->manifestacao_email($id_relatorio,$vetor_email,$manifestacao);                
                 
                 // verificar  se número de manifestações (positivas + negativas respondidas) igual a  
                 // número de unidades auditadas e, neste caso, liberar para homologação
                $Manifestacoes_positivas = Manifestacao::model()->findAllbyAttributes(array('relatorio_fk'=>$id_relatorio,'status_manifestacao'=>1));
                $Manifestacoes_negativas_respondidas = Manifestacao::model()->findAll('relatorio_fk='.$id_relatorio.' and status_manifestacao=0 and descricao_resposta IS NOT NULL');
                $RelatorioSureg = RelatorioSureg::model()->findAllbyAttributes(array('relatorio_fk'=>$id_relatorio));
                
                if(sizeof($RelatorioSureg) == sizeof($Manifestacoes_positivas) + sizeof($Manifestacoes_negativas_respondidas)){
                   $vetor_email=null; // limpa vetor com e-mails já enviados para avisar sobre a resposta
                   //PORTAL_SPB e-mail para um grupo
                   if(Yii::app()->params['emailGrupoAuditoria'] != '')
                       $vetor_email[]=Yii::app()->params['emailGrupoAuditoria'];


                   $libera=Relatorio::model()->LiberaHomologa($id_relatorio);       
                   $this->manifestacao_email($id_relatorio,$vetor_email,'positiva');
                } 
            }
            
    }
            

            
       // Envia e-mails, após manifestação do relatório.
       // Parâmetros de entrada: ID do relatório, e-mails (logins)
       // e manifestação ('positiva', 'negativa', 'tacita' ou 'resposta')
        public function manifestacao_email($id_relatorio,$emails,$manifestacao) {          
            // pega o título do relatório
            $model_relatorio = Relatorio::model()->findByAttributes(array('id'=>$id_relatorio));
            $especie_auditoria = EspecieAuditoria::model()->findByAttributes(array('id'=>$model_relatorio->especie_auditoria_fk));
            $titulo_relatorio = $id_relatorio. " - " .$especie_auditoria->sigla_auditoria;
            // configura parâmetros para enviar o e-mail
            $headers = "Reply-To: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
            $headers .= "Return-Path: SIAUDI<".Yii::app()->params['adminEmail'].">\r\n";
            $headers .= "From: Auditoria <".Yii::app()->params['adminEmail'].">\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html;charset=iso-8859-1\r\n";

            $mensagem = "<html><head></head><body><font face='Verdana' size='2'>";            

            if($manifestacao=="tacita"){
                $assunto = 'SIAUDI - Manifestação Tácita - Relatório liberado para homologação';
                $mensagem .= "Informamos que, pelo decurso de prazo de manifestação, os fatos relatados no <strong>Relatório de Auditoria Id Relatório: ".$titulo_relatorio."</strong> foram considerados pertinentes aos trabalhos de campo realizados pela equipe de auditores internos.";
            }
            
            if($manifestacao=="positiva"){
                $assunto = 'SIAUDI - Relatório liberado para homologação';
                $mensagem .= "O <strong>Relatório de Auditoria Id Relatório: ".$titulo_relatorio."</strong> encontra-se liberado para homologação.";
            }
            
            if($manifestacao=="negativa"){
                $assunto = 'SIAUDI - Manifestação Contrária';
                $mensagem .= "Houve manifestação contrária à adequação do <strong>Relatório de Auditoria Id Relatório: ".$titulo_relatorio."</strong>. Visualize no Relatório de Manifestações do SIAUDI.";
            }            
            
            if($manifestacao=="resposta"){
                $assunto = 'SIAUDI - Resposta à Manifestação Contrária';
                $mensagem .= "Houve resposta à manifestação contrária à adequação do <strong>Relatório de Auditoria Id Relatório: ".$titulo_relatorio."</strong>. Visualize no Relatório de Manifestações do SIAUDI.";
            }              
            
            $mensagem .= "</font></body></html>";            
            // formata texto html
            
            // envia e-mails            
            //PORTAL_SPB alterada a forma de gerar e-mail
            foreach($emails as $vetor){ 
                // verifica se o e-mail para este relatório já foi enviado
                // para evitar mais de 1 envio do mesmo relatório para o mesmo
                // destinarário
                if(!$check_email[$vetor]){
                    $check_email[$vetor]=1;
                    $destinatario = $vetor;//.Yii::app()->params['dominioEmail'];
                    $ok = Relatorio::model()->Envia_email($destinatario, $assunto, $mensagem, $headers);
                }
            }
        }               
        
        
        // Verifica se o auditado já respondeu a manifestação
        // de acordo com o id do relatório e da Unidade Regional do auditado
        public function  VerificaManifestacaoAuditado($id_relatorio){
        $login = Yii::app()->user->login;

        $schema = Yii::app()->params['schema'];        
        $sql = "SELECT manifestacao.*   
                FROM ". $schema . ".tb_manifestacao manifestacao
                WHERE (manifestacao.relatorio_fk=".$id_relatorio.")
                AND (manifestacao.nome_login='".$login."')";
            $command = Yii::app()->db->createCommand($sql);
            $result = $command->query();
            return($result->rowCount); 
        }
        
	public function afterFind() {
        parent::afterFind();
        if (isset($this->data_manifestacao))  $this->data_manifestacao = MyFormatter::converteData($this->data_manifestacao);
        if (isset($this->data_resposta))      $this->data_resposta     = MyFormatter::converteData($this->data_resposta);
    }      
            
    
    // recebe o tipo de relatório  (1=> Não homologado, 2=> Homologado)
    // para carregar combo de Relatório de Manifestação    
    public function CarregaManifestacaoSaida($tipo_relatorio){
        $login = strtolower(Yii::app()->user->login);                
        $perfil = strtolower(Yii::app()->user->role);        
        $perfil = str_replace("siaudi2","siaudi",$perfil);
        $schema = Yii::app()->params['schema'];         
        $tipo_relatorio = ($tipo_relatorio==1)? "IS NULL":"IS NOT NULL";
        // consulta padrão para perfil siaudi_auditor (mostra todas as manifestações) 
        $sql = "SELECT manifestacao.id as manifestacao_id, relatorio.id as relatorio_id, e.sigla_auditoria, sureg.sigla as sureg_sigla,
                       relatorio.data_relatorio as data_relatorio, relatorio.numero_relatorio as numero_relatorio            
                FROM ". $schema . ".tb_relatorio relatorio 
                    LEFT JOIN  ". $schema . ".tb_especie_auditoria e ON relatorio.especie_auditoria_fk = e.id 
                    LEFT JOIN  ". $schema . ".tb_manifestacao manifestacao ON manifestacao.relatorio_fk=relatorio.id
                    LEFT JOIN  ". $schema . ".tb_unidade_administrativa sureg ON sureg.id=manifestacao.unidade_administrativa_fk
                WHERE relatorio.data_relatorio " .$tipo_relatorio ."  and manifestacao.id IS NOT NULL and manifestacao.status_manifestacao=0
                ORDER BY relatorio.id ASC";

        // consulta de relatórios para perfil siaudi_cliente (mostra
        // somente os relatórios onde o auditado consta na relatorio_acesso
        if($perfil=="siaudi_cliente"){
            $sql = "SELECT manifestacao.id as manifestacao_id, relatorio.id as relatorio_id, e.sigla_auditoria,
                           relatorio.data_relatorio as data_relatorio, relatorio.numero_relatorio as numero_relatorio                
                    FROM ". $schema . ".tb_relatorio relatorio 
                        LEFT JOIN  ". $schema . ".tb_especie_auditoria e ON relatorio.especie_auditoria_fk = e.id 
                        LEFT JOIN  ". $schema . ".tb_manifestacao manifestacao ON manifestacao.relatorio_fk=relatorio.id
                        LEFT JOIN  ". $schema . ".tb_unidade_administrativa sureg ON sureg.id=manifestacao.unidade_administrativa_fk                            
                    WHERE   manifestacao.id IS NOT NULL AND
                            manifestacao.status_manifestacao=0  AND
                            data_relatorio " .$tipo_relatorio ." AND EXISTS (select 1 from ". $schema . ".tb_relatorio_acesso ra where
                                                                                    ra.relatorio_fk = relatorio.id and 
                                                                                    ra.nome_login='".$login."')
                    ORDER BY relatorio.id DESC";  
        }

        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        return($result->readAll());
    }
    
    
    
    // recebe o tipo de relatório  (1=> Não homologado, 2=> Homologado)
    // para carregar combo de Relatório de Manifestação para o Gerente
    // que pode ver TODOS os relatórios de manifestação 
    public function CarregaManifestacaoGerenteSaida($tipo_relatorio){
        $schema = Yii::app()->params['schema'];         
        $tipo_relatorio = ($tipo_relatorio==1)? "IS NULL":"IS NOT NULL";
        
        $sql = "SELECT manifestacao.id as manifestacao_id, 
                       relatorio.id as relatorio_id, 
                       relatorio.data_relatorio as data_relatorio,
                       relatorio.numero_relatorio as numero_relatorio,
                       e.sigla_auditoria, 
                       sureg.sigla as sureg_sigla
        FROM ". $schema . ".tb_relatorio relatorio 
            LEFT JOIN  ". $schema . ".tb_especie_auditoria e ON relatorio.especie_auditoria_fk = e.id 
            LEFT JOIN  ". $schema . ".tb_manifestacao manifestacao ON manifestacao.relatorio_fk=relatorio.id
            LEFT JOIN  ". $schema . ".tb_unidade_administrativa sureg ON sureg.id=manifestacao.unidade_administrativa_fk
        WHERE relatorio.data_relatorio " .$tipo_relatorio ."  and manifestacao.id IS NOT NULL and manifestacao.status_manifestacao=0
        ORDER BY relatorio.id ASC";

        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        return($result->readAll());
    }    
    
    
}
