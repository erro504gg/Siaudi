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

Yii::import('application.models.table._base.BaseRelatorioReiniciar');

class RelatorioReiniciar extends BaseRelatorioReiniciar
{
    public static function model($className=__CLASS__) {
            return parent::model($className);
    }

    public function afterFind() {
    parent::afterFind();
    if (isset($this->data))  $this->data = MyFormatter::converteData($this->data);        
    }            
    
    // insere na tabela  relatorio_reiniciar um registro
    // TRUE para que a contagem possa ser reiniciada na próxima
    // homologação
    public  function ReiniciarContagem(){
    $login = Yii::app()->user->login;
    $schema = Yii::app()->params['schema'];
    $data = date("Y-m-d");
    $sql = "INSERT into ". $schema . ".tb_relatorio_reiniciar
            VALUES(true,'".$data."','".$login."')" ;
    $command = Yii::app()->db->createCommand($sql);
    $result = $command->query();  
    }
    
    // desabilita o reinício da contagem (marca
    // todos os 'st_reiniciar' para FALSE
    public  function DesabilitarReiniciarContagem(){
    $schema = Yii::app()->params['schema'];
    $sql = "UPDATE ". $schema . ".tb_relatorio_reiniciar SET st_reiniciar=false" ;
    $command = Yii::app()->db->createCommand($sql);
    $result = $command->query();  
    }    
    
    //Verifica se a contagem de item precisa ser reiniciada
    // (retorna as tuplas com TRUE da tabela tb_relatorio_reiniciar)
    public  function VerificaReiniciarContagem(){
    $login = Yii::app()->user->login;
    $schema = Yii::app()->params['schema'];
    $data = date("Y-m-d");
    $sql = "SELECT * from ". $schema . ".tb_relatorio_reiniciar where st_reiniciar=true" ;
    $command = Yii::app()->db->createCommand($sql);
    $result = $command->query();  
    return ($result->readAll());
    }    
}
