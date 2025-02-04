<?php
/********************************************************************************
*  Copyright 2015 Conab - Companhia Nacional de Abastecimento                   *
*                                                                               *
*  Este arquivo � parte do Sistema SIAUDI.                                      *
*                                                                               *
*  SIAUDI  � um software livre; voc� pode redistribui-lo e/ou                   *
*  modific�-lo sob os termos da Licen�a P�blica Geral GNU conforme              *
*  publicada pela Free Software Foundation; tanto a vers�o 2 da                 *
*  Licen�a, como (a seu crit�rio) qualquer vers�o posterior.                    *
*                                                                               *
*  SIAUDI � distribu�do na expectativa de que seja �til,                        *
*  por�m, SEM NENHUMA GARANTIA; nem mesmo a garantia impl�cita                  *
*  de COMERCIABILIDADE OU ADEQUA��O A UMA FINALIDADE ESPEC�FICA.                *
*  Consulte a Licen�a P�blica Geral do GNU para mais detalhes em portugu�s:     *
*  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt                     *
*                                                                               *
*  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral do GNU             *
*  junto com este programa; se n�o, escreva para a Free Software                *
*  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    *
*                                                                               *
*  Sistema   : SIAUDI - Sistema de Auditoria Interna                            *
*  Data      : 05/2015                                                          *
*                                                                               *
********************************************************************************/
?>
<?php

Yii::import('application.models.table._base.BaseResposta');

class Resposta extends BaseResposta
{
    public $relatorio_fk, $numero_capitulo, $numero_item, $numero_recomendacao, $anexo, $valor_exercicio;
        
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'id' => Yii::t('app', 'ID'),
            'tipo_status_fk' => Yii::t('app', 'Status'),
            'recomendacao_fk' => Yii::t('app', 'Recomendacao Fk'),
            'data_resposta' => Yii::t('app', 'Data Resposta'),
            'id_usuario_log' => Yii::t('app', 'Id Usuario Log'),
            'descricao_resposta' => Yii::t('app', 'Resposta'),
            'tipoStatusFk' => null,
            'relatorio_fk' => Yii::t('app', 'N� Relat�rio'),
            'numero_capitulo' => Yii::t('app', 'N� Cap�tulo'),
            'numero_item' => Yii::t('app', 'N� Item'),
            'numero_recomendacao' => Yii::t('app', 'N� Recomenda��o'),
            'qt_cliente' => Yii::t('app', 'Qtde. Resp. Cliente'),
            'qt_auditor' => Yii::t('app', 'Qtde. Resp. Auditor'),            
            'ultimo_st' => Yii::t('app', '�ltimo Status'),
            'numero_relatorio' => Yii::t('app', 'N� Relat�rio'),
            'anexo' => Yii::t('app', 'Anexo'),
        	'valor_exercicio' => Yii::t('app', 'Exerc�cio'),
        );
        return array_merge($attribute_default, $attribute_custom);
    }
    
    

    /* Monta a combo de relat�rios, para carregar o FollowUp
     * de acordo com o perfil de usu�rio.
     * @param void
     * @return array com relat�rios (id=> "numero" de "data")
     */
    public function FollowUp_combo($valor_exercicio = null) {  
        $id_und_adm = Yii::app()->user->id_und_adm;
        $perfil = strtolower(Yii::app()->user->role); 
        $perfil = str_replace("siaudi2","siaudi",$perfil);   

        $usuario = strtolower(Yii::app()->user->login);
        $schema = Yii::app()->params['schema'];   
            if ($perfil=="siaudi_cliente"){
                    $sql_from = " INNER JOIN ". $schema . ".tb_relatorio_acesso ON tb_relatorio.id=tb_relatorio_acesso.relatorio_fk";

                    $sql_where = " AND (tb_relatorio_acesso.nome_login = '".$usuario."') ";

            }else if ($perfil=="siaudi_cliente_item"){ 
                    $sql_from = "INNER JOIN ". $schema . ".tb_capitulo ON tb_relatorio.id= tb_capitulo.relatorio_fk
                                 INNER JOIN ". $schema . ".tb_item ON tb_capitulo.id = tb_item.capitulo_fk
                                 LEFT OUTER JOIN ". $schema . ".tb_relatorio_acesso ON tb_relatorio_acesso.relatorio_fk=tb_relatorio.id
                                 INNER JOIN ". $schema . ".tb_relatorio_acesso_item tb_acesso_item ON tb_acesso_item.item_fk=tb_item.id";
                    $sql_where = " AND (tb_acesso_item.nome_login = '".$usuario."') "; 

            }else if ((string) strpos($perfil, "siaudi_diretor") === (string) 0){
                    $sql_from = " INNER JOIN ". $schema . ".tb_relatorio_diretoria ON tb_relatorio.id=tb_relatorio_diretoria.relatorio_fk";
                    $sql_where = " AND (tb_relatorio_diretoria.diretoria_fk = $id_und_adm)"; 
            }else if($perfil =="siaudi_gerente_nucleo") {
                $sql_where = " AND tb_relatorio.nucleo is true ";
            }
            if ($valor_exercicio) {
            	$sql_where = $sql_where . " AND date_part('year',data_relatorio) = " . $valor_exercicio;
            }

            $sql = " SELECT DISTINCT tb_relatorio.id, tb_relatorio.numero_relatorio, tb_relatorio.data_relatorio, date_part('year',data_relatorio)
                    FROM ". $schema . ".tb_relatorio ". 
                    $sql_from."
                     WHERE (tb_relatorio.numero_relatorio IS NOT NULL) ".
                    $sql_where. 
                    " ORDER BY date_part('year',data_relatorio) DESC, tb_relatorio.numero_relatorio DESC ";

            $command = Yii::app()->db->createCommand($sql);
            $result = $command->query();
            $resultado = $result->readAll();
            if(sizeof($resultado)>0){
                foreach ($resultado as $vetor){
                    $vet_resultado[$vetor[id]]=$vetor[numero_relatorio] . " de " . MyFormatter::converteData($vetor[data_relatorio]);
                }
            }else {
                $vet_resultado=array(0=>"Sem relat�rios");
            }
            return ($vet_resultado);
    }            
  
    
    /* Verifica se o cliente_item tem acesso ao item
     * que est� sendo acessado no follow up
     */
    public function Item_autorizado($id_item) {    
        $usuario = strtolower(Yii::app()->user->login);        
        $schema = Yii::app()->params['schema'];
        $sql = "select *
                  from " . $schema . ".tb_relatorio_acesso_item 
                 where upper(nome_login) = '" . strtoupper($usuario)  . "'
                   and item_fk = " . $id_item;
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        return (sizeof($result->readAll()));        
    }
    
    
    public function validaAcessoAoRelatorio($relatorio_fk, $login) {
        $schema = Yii::app()->params['schema'];
        $sql = "select 1 
                  from " . $schema . ".tb_relatorio_acesso_item acesso_item 
                 inner join " . $schema . ".tb_item item on 
                       item.id = acesso_item.item_fk 
                 inner join " . $schema . ".tb_capitulo capitulo on 
                       capitulo.id = item.capitulo_fk 
                 inner join " . $schema . ".tb_relatorio relatorio on 
                       relatorio.id = capitulo.relatorio_fk
                 inner join " . $schema . ".tb_recomendacao recomendacao on
                 recomendacao.item_fk = item.id
                 where upper(nome_login) = '" . strtoupper($login)  . "'
                   and relatorio.id = " . $relatorio_fk;
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        return (sizeof($result->readAll()));
    }
    

    public function search($id = null) {
        $schema = Yii::app()->params['schema'];
        $criteria = new CDbCriteria;
        $criteria->alias = 'recomendacao';
        $criteria->select = "relatorio.numero_relatorio, 
                             relatorio.data_relatorio,
                             capitulo.numero_capitulo,
                             item.numero_item,
                             recomendacao.id,
                             recomendacao.numero_recomendacao";
        $criteria->join = "JOIN $schema.tb_item item ON recomendacao.item_fk=item.id ";
        $criteria->join .= "JOIN $schema.tb_capitulo capitulo ON item.capitulo_fk=capitulo.id ";
        $criteria->join .= "JOIN $schema.tb_relatorio  relatorio ON capitulo.relatorio_fk=relatorio.id ";
    
        $login = Yii::app()->user->login;
        $perfil = Yii::app()->user->role;
        $perfil = str_replace("siaudi2", "siaudi", $perfil);
    
        if ($perfil == 'siaudi_cliente_item') {
            $criteria->join .= "JOIN $schema.tb_relatorio_acesso_item acesso_item ON acesso_item.item_fk = item.id and acesso_item.nome_login = '" . $login . "' ";
        }
    
        $criteria->addCondition("relatorio.numero_relatorio IS NOT NULL");
    
        if ($id !== null) {
            $criteria->addCondition("relatorio.id=" . $id);
        }
    
        $criteria->addCondition("recomendacao.recomendacao_tipo_fk=(SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomenda��o%')"); 
    
        $criteria->order = 'relatorio.numero_relatorio ASC, capitulo.numero_capitulo ASC, item.numero_item ASC, recomendacao.numero_recomendacao ASC';
    
        $results = Recomendacao::model()->findAll($criteria);
    
        $arrayDataProvider = new CArrayDataProvider($results, array(
            'id' => 'Recomendacao',
            'keyField' => 'id',
            'sort' => array(
                'modelClass' => 'Recomendacao',
                'attributes' => array('id'),
            ),
            'pagination' => array('pageSize' => 10),
        ));
    
        return $arrayDataProvider;
    }
    
    
    
    /* Calcula a quantidade de respostas de 
     * auditores e clientes para montar a tela de Follow UP     
     * @param int id_recomendacao (id da recomenda��o)
     * @param string tipo (auditor, cliente)
     * @return int com quantidade de respostas
     */
    public function FollowUp_QT($id_recomendacao,$tipo) {   
        $schema = Yii::app()->params['schema'];   
        $sql_where="1=1";
        if ($tipo=="auditor"){
            $sql_where = "perfil.nome_interno ilike '%Auditor%' or perfil.nome_interno ilike '%Gerente%' or perfil.nome_interno ilike '%Coordenador%'";
        }
        if ($tipo=="cliente"){
            $sql_where = "perfil.nome_interno ilike '%Cliente%'";
        }        
        
        $sql=" SELECT count(1) as total 
                FROM ". $schema . ".tb_resposta resposta 
                    inner join ". $schema . ".tb_usuario usuario ON resposta.id_usuario_log=usuario.nome_login
                    inner join ". $schema . ".tb_perfil perfil ON usuario.perfil_fk=perfil.id
                WHERE recomendacao_fk=".$id_recomendacao." and (".$sql_where.")";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        $resultado = $result->read();        
        return $resultado[total];

    }
    
    /* Obtem o maior ID, representando assim a �ltima mensagem.
     * @param int id_recomendacao (id da recomenda��o)
     * @return int com o �ltimo ID
     */
    public function FollowUp_UltimoID($id_recomendacao) {   
        $schema = Yii::app()->params['schema'];   
        $sql=" select max(resposta.id) as maxID 
                from ". $schema .".tb_resposta resposta
               inner join ". $schema .".tb_recomendacao recomendacao on 
                     recomendacao.id = resposta.recomendacao_fk
               where recomendacao_fk= " . $id_recomendacao;
        
        
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        $resultado = $result->read();
        return $resultado[maxid];

    }
    
    /* Verificar se tem anexo (tanto no sistema novo quanto
     * no legado, onde os arquivos ficavam na tb_imagem)
     * @param int id_recomendacao (id da recomenda��o)
     * @return bool se tem anexo
     */
    public function FollowUp_TemAnexo($id_recomendacao) {   
        $schema = Yii::app()->params['schema'];   
        // verifica na tabela nova que armazena os arquivos
        $sql=" select tipo_arquivo
                from ". $schema .".tb_resposta resposta
               inner join ". $schema .".tb_recomendacao recomendacao on 
                     recomendacao.id = resposta.recomendacao_fk
               where
                 tipo_arquivo is not null
                 and recomendacao_fk= " . $id_recomendacao;
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        $resultado = $result->read();
        
        // verifica na tabela do sistema legado
        $anexo_legado = Imagem::model()->findAllByAttributes(array("recomendacao_fk" => $id_recomendacao));          
        
        return ($resultado[tipo_arquivo] || sizeof($anexo_legado)>0) ? "Sim" : "N�o";
    }
   
    
    /* Retorna o Status do �ltimo follow-up    
     * @param int id_recomendacao (id da recomenda��o)
     * @param int retorno_status (se 1, ent�o retorna somente o id do �tlimo status);                
     * @return string (com �ltimo status formatado) ou inteiro (com valor do �ltimo status)
     */
    public function FollowUp_Ultimo_Status($id_recomendacao,$retorno_status=0) {   
        $schema = Yii::app()->params['schema'];   
        $tipo_status = ($tipo=="auditor")? "IS NOT NULL":"IS NULL";
        $sql=" select tipo_status.id,
                      tipo_status.descricao_status
               from ". $schema . ".tb_tipo_status tipo_status 
                    INNER JOIN ". $schema . ".tb_resposta resposta ON tipo_status.id=resposta.tipo_status_fk
              where resposta.id=(select MAX(id) 
                                     from ". $schema . ".tb_resposta 
                                     where tipo_status_fk IS NOT NULL and recomendacao_fk=".$id_recomendacao.")";
        
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        $resultado = $result->read();        
            $status = $resultado[id];
            if ($status == "1") {//pendente
                    $color = "#FF3333";
            }
            if ($status == "2") {//em implementa��o
                    $color = "#FF9933";
            }            

            if ($status == "3"){//solucionado
                    $color = "#009933";
            }

            if ($status == "4"){//baixado
                    $color = "#0000CC";
            }

            if ($status == ""){//sem status
                    $color = "#FF3333";
                    $status = "1";
            }
            $descricao = ($resultado[descricao_status])? $resultado[descricao_status]:"Pendente";
            $retorno = "<font color='".$color."'>".$descricao."</font>";
            
            if($retorno_status==1){
                $retorno=$status;
            } 
       return $retorno;
    }    
  
    
    public function afterFind() {
        parent::afterFind();
        if (isset($this->data_resposta))  $this->data_resposta  = MyFormatter::converteData($this->data_resposta);
    }     
    
    
    
    /* Ap�s a inser��o de uma resposta no follow-up
     * envia e-mail �s partes interessadas. 
     * @param string numero_recomendacao (n� da recomenda��o, ex: 1.1)
     * @param int relatorio_id (id do relat�rio)
     */
    public function FollowUp_email($numero_recomendacao,$relatorio_id, $recomendacao_fk = 0){
        $login  = strtolower(Yii::app()->user->login);
        $perfil = strtolower(Yii::app()->user->role);
        $perfil = str_replace("siaudi2","siaudi",$perfil);
        $Relatorio=Relatorio::model()->findbyPk($relatorio_id);

        // se a resposta foi enviada por um gerente, auditor ou chefe,
        // ent�o envia e-mail somente aos auditados
        //PORTAL_SPB alterada a forma de gerar e-mail
        if($perfil=="siaudi_gerente" || $perfil=="siaudi_auditor" || $perfil=="siaudi_chefe_auditoria"){
            // procura unidades auditadas do relat�rio
                $Relatorio_sureg = RelatorioSureg::model()->findAllByAttributes(array("relatorio_fk"=>$relatorio_id));
                if(sizeof($Relatorio_sureg)>0){
                    foreach($Relatorio_sureg as $vetor){
                        // Pega e-mails dos logins afetos �s Unidades Regionais encontradas
                            $sureg = $vetor->unidade_administrativa_fk;
                            $RelatorioAcesso=RelatorioAcesso::model()->findAllByAttributes(array("relatorio_fk"=>$relatorio_id,"unidade_administrativa_fk"=>$sureg)); 
                        if(sizeof($RelatorioAcesso)>0){
                            foreach($RelatorioAcesso as $vetor2){
                                $vetor_email[]=Usuario::model()->find("nome_login = '".$vetor2->nome_login."'")->email;                
                            }
                        }
                    }
                }
            // formata texto html
            $mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
            $mensagem .= "A resposta oferecida pelo auditado para a recomenda��o <strong>".$numero_recomendacao."</strong> do <strong>Relat�rio de Auditoria N� ".$Relatorio->numero_relatorio." de ".$Relatorio->data_relatorio."</strong> foi avaliada pelo Auditor.";
            $mensagem .= "</font></body></html>";            
            
        // se a resposta foi enviada por um cliente, ent�o formata a resposta
        }elseif($perfil=="siaudi_cliente_item") {
            $recomendacao = Recomendacao::model()->findByAttributes(array('id'=>$recomendacao_fk));
            $item = Item::model()->findByAttributes(array('id'=>$recomendacao->item_fk));
            $relatorioAcessoItem = RelatorioAcessoItem::model()->findByAttributes(array('item_fk'=>$item->id));
            $UnidadeAdministrativa = UnidadeAdministrativa::model()->find('id='.$relatorioAcessoItem->unidade_administrativa_fk);
            
            // formata texto html
            $mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
            $mensagem .= "A recomenda��o <strong>".$numero_recomendacao."</strong> do <strong>Relat�rio de Auditoria N� ".$Relatorio->numero_relatorio." de ".$Relatorio->data_relatorio."</strong> foi respondida pelo auditado <strong>$UnidadeAdministrativa->sigla</strong>.";
            $mensagem .= "</font></body></html>";
        } 
        else {
            // procura unidade auditada do usu�rio
            $RelatorioAcesso=RelatorioAcesso::model()->findByAttributes(array("relatorio_fk"=>$relatorio_id,"nome_login"=>$login));        
            $UnidadeAdministrativa = UnidadeAdministrativa::model()->find('id='.$RelatorioAcesso->unidade_administrativa_fk);
            // formata texto html
            $mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
            $mensagem .= "A recomenda��o <strong>".$numero_recomendacao."</strong> do <strong>Relat�rio de Auditoria N� ".$Relatorio->numero_relatorio." de ".$Relatorio->data_relatorio."</strong> foi respondida pelo auditado <strong>$UnidadeAdministrativa->sigla</strong>.";
            $mensagem .= "</font></body></html>";            
        }        

            // Enviar e-mails aos auditores afetos e aos
            // Gerentes de auditoria

            // procura os auditores afetos ao relat�rio
            $RelatorioAuditor = RelatorioAuditor::model()->findAll(array('condition'=>'relatorio_fk='.$relatorio_id));
            if(sizeof($RelatorioAuditor)>0){            
                foreach($RelatorioAuditor as $vetor){
                    $Auditor_id = $vetor->usuario_fk;
                    $Auditor = Usuario::model()->find(array('condition'=>'id='.$Auditor_id));
                    $vetor_email[]=$Auditor->email;                
                }
            }                      
            
            // procura todos os gerentes de auditoria
            $Gerentes = Usuario::model()->findAll(array('condition'=>'perfil_fk=2'));
            if(sizeof($Gerentes)>0){            
                foreach($Gerentes as $vetor){
                    $vetor_email[]=$vetor->email;                
                }
            }        
        
            // configura par�metros para enviar o e-mail
            $headers = "Reply-To: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
            $headers .= "Return-Path: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
            $headers .= "From: Unidade de Auditoria Interna <".Yii::app()->params['adminEmail'].">\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
            $assunto = 'SIAUDI - Follow-Up - Recomenda��o Respondida';

            // envia e-mails        
            //PORTAL_SPB alterada a forma de gerar e-mail
            if(sizeof($vetor_email)>0){
                foreach($vetor_email as $vetor){ 
                    // verifica se o e-mail j� foi enviado a este usu�rio
                    // para evitar envios duplicados
                    if(!$check_email[$vetor]){
                        $check_email[$vetor]=1;
                        $destinatario = $vetor;//.Yii::app()->params['dominioEmail'];
                        $ok = Relatorio::model()->Envia_email($destinatario, $assunto, $mensagem, $headers);
                    }
                }
            }
    }
    
}
