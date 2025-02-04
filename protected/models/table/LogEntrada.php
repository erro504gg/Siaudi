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

Yii::import('application.models.table._base.BaseLogEntrada');

class LogEntrada extends BaseLogEntrada
{
    public $data_entrada2;
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
        // insere o usuário, ip, data e hora na tabela de logs.
        public function SalvaLog($id_relatorio=null,$id_item=null){
            $schema = Yii::app()->params['schema'];
            $login = Yii::app()->user->login;
            $ip = $_SERVER["REMOTE_ADDR"];
            $relatorio_fk = ($id_relatorio)? $id_relatorio:"null";
            $item_fk = ($id_item)? $id_item:"null";
            
            $sql = "INSERT INTO ".$schema.".tb_log_entrada(nome_login, data_entrada, valor_ip, relatorio_fk, item_fk)
                    VALUES ('".$login."', date_trunc('second', TIMESTAMP 'now'),'".$ip."',".$relatorio_fk.",".$item_fk.");" ;
            $command = Yii::app()->db->createCommand($sql);
            $result = $command->query();          
        }
}
