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

Yii::import('application.models.table._base.BaseRelatorioAcesso');

class RelatorioAcesso extends BaseRelatorioAcesso
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
       
    // Insere os siaudi_clientes na tabela de relatorio acesso
    public function inserir($id_relatorio, $login, $unidade_administrativa_fk) {
            $schema = Yii::app()->params['schema'];
            $sql = 'INSERT INTO ' . $schema . '.tb_relatorio_acesso 
                    (relatorio_fk, nome_login, unidade_administrativa_fk) 
                    VALUES ('.$id_relatorio.",'".$login."',".$unidade_administrativa_fk.')';
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();
    }        
    
    // Verifica se o usuário auditado está cadastrado no corporativo.
    // Se não estiver, insere. Se já estiver, verifica também
    // se o perfil está cadastrado como cliente. 
    // Parâmetro de entrada: login do usuário (ex: osvaldo.pateiro ) 
    public function VerificaAuditadoCorporativo($login) {       
        $id_aplicacao = Yii::app()->getParams()->id_aplicacao; 
        
        // pega ID do sistema 
        $sql_usuario = "SELECT id  FROM tb_sistema WHERE (upper(nome) = '" . strtoupper($id_aplicacao) . "') ";
        $command2 = Yii::app()->db->createCommand($sql_usuario);
        $result2 = $command2->query();
        $result2 = $result2->read();
        $sistema_id = $result2[id];
        
            // Consulta se usuário existe no corporativo, associado ao SIAUDI
            $sql = "SELECT usuario.id as idusuario, usuario.login, perfil.id as idperfil, perfil.nome
                  FROM tb_usuario usuario INNER JOIN 
                   tb_usuario_perfil usuario_perfil ON usuario.id = usuario_perfil.usuario_fk INNER JOIN 
                   tb_perfil perfil ON usuario_perfil.perfil_fk = perfil.id INNER JOIN 
                   tb_sistema sistema ON sistema.id = perfil.sistema_fk
                  WHERE (sistema.id = " . $sistema_id . ") AND (usuario.login = '" . $login . "') ";

            $command = Yii::app()->db->createCommand($sql);
            $result = $command->query();
            $resultados = $result->read();
            // Se usuário não existe no SIAUDI, então insere
            if(!$result->rowCount){
                // pega ID do usuário
                $sql_usuario = "SELECT id  FROM tb_usuario WHERE (login = '".$login."') ";
                $command2 = Yii::app()->db->createCommand($sql_usuario);
                $result2 = $command2->query();
                $result2 = $result2->read();
                $usuario_id = $result2[id];
                
                //Seleciona o ID do perfil (CLIENTE)
                $sql_perfil = "SELECT id FROM tb_perfil WHERE (nome = 'SIAUDI_CLIENTE') and sistema_fk = " . $sistema_id;            
                $command3 = Yii::app()->db->createCommand($sql_perfil);
                $result3 = $command3->query();
                $result3 = $result3->read();
                $perfil_id = $result3[id];                
                
            	//Seleciona o último ID cadastrado na tabela tb_usuario_perfil, pois o campo id não é auto-incremento!
		$sql_ultimoid = "SELECT setval('tb_usuario_perfil_seq',(select max(id) from tb_usuario_perfil))";
                $command4 = Yii::app()->db->createCommand($sql_perfil);
                $result4 = $command4->query();
                $result4 = $result4->read();
                $ultimo_id= $result4[id];    
                
                // Insere o usuário associado ao perfil 
                $sql_inc_userperfil = "INSERT INTO tb_usuario_perfil (id, usuario_fk, perfil_fk)
                                                VALUES (nextval('tb_usuario_perfil_seq'), 
                                                        '".$usuario_id."','".$perfil_id."') ";
                                
                $command5 = Yii::app()->db->createCommand($sql_inc_userperfil);
                $result5 = $command5->query();                
                
            // Se usuário já existe no SIAUDI, verificar 
            // se o perfil atual é como CLIENTE
            }else {                
                $usuario_id = $resultados[idusuario];
                $usuario_login = $resultados[login];
                $perfil_id = $resultados[idperfil];
                $perfil_nome = strtoupper($resultados[nome]);
                
                if ($perfil_nome!="SIAUDI_CLIENTE"){
                    $sql_perfil = "SELECT id FROM tb_perfil WHERE (nome = 'SIAUDI_CLIENTE') and sistema_fk = " . $sistema_id;         
                    $command6 = Yii::app()->db->createCommand($sql_perfil);
                    $result6 = $command6->query();
                    $result6 = $result6->read();
                    $perfil_id_novo = $result6[id]; 

                    $sql_alt_userperfil = "UPDATE tb_usuario_perfil 
                                           SET perfil_fk = '".$perfil_id_novo."'
                                           WHERE (usuario_fk = '".$usuario_id."') 
                                             AND (perfil_fk = '".$perfil_id."') ";
                    $command7 = Yii::app()->db->createCommand($sql_alt_userperfil);
                    $result7 = $command7->query();                    
                }
            }
    }        
}
