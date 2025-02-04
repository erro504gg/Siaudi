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

Yii::import('application.models.table._base.BaseRelatorioAcessoItem');

class RelatorioAcessoItem extends BaseRelatorioAcessoItem {

    public $relatorio_fk, $capitulo_fk, $liberar_todos_itens, $auditor_fk, $usuario_fk, $id_login;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'id' => Yii::t('app', 'ID'),
            'item_fk' => null,
            'nome_login' => Yii::t('app', 'Usuário'),
            'unidade_administrativa_fk' => Yii::t('app', 'Unidade Auditada'),
            'itemFk' => null,
            'relatorio_fk' => Yii::t('app', 'Nº Relatório'),
            'capitulo_fk' => Yii::t('app', 'Nº Capítulo'),
            'liberar_todos_itens' => Yii::t('app', 'Desejar liberar todos os itens deste relatório')
        );
        return array_merge($attribute_default, $attribute_custom);
    }

    // mostra os resultados da consulta do Acesso Item
    // com os relatórios homologados
    public function RelatorioHomologado($id_item) {
        $item = Item::model()->findByPk($id_item);
        $capitulo = Capitulo::model()->findByPk($item->capitulo_fk);
        $relatorio = Relatorio::model()->findByPk($capitulo->relatorio_fk);

        $relatorio_nome = $relatorio->numero_relatorio . " de " . $relatorio->data_relatorio;
        return $relatorio_nome;
    }

//    Recupera os itens do login informado. 
    public function BuscaRegistro($id_login, $unidade_administrativa_fk) {
        $schema = Yii::app()->params['schema'];
        $sql = "select relatorio.numero_relatorio, relatorio.data_relatorio, 
                       acesso_item.id, acesso_item.nome_login, acesso_item.unidade_administrativa_fk, acesso_item.nome_login_cliente,
                       acesso_item.item_fk , item.capitulo_fk, capitulo.relatorio_fk
                  from " . $schema . ".tb_relatorio_acesso_item acesso_item 
                 inner join " . $schema . ".tb_item item on 
                       item.id = acesso_item.item_fk 
                 inner join " . $schema . ".tb_capitulo capitulo on 
                       capitulo.id = item.capitulo_fk 
                 inner join " . $schema . ".tb_relatorio relatorio on 
                       relatorio.id = capitulo.relatorio_fk
                 where 1=1";
        if ($id_login > 0) {
            $auditor = Usuario::model()->findByPk($id_login);
            $sql .= " and upper(nome_login) = '" . strtoupper($auditor['nome_login']) . "'";
        }
        if ($unidade_administrativa_fk > 0) {
            $sql .= " and unidade_administrativa_fk = " . $unidade_administrativa_fk;
        }
        
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }

    /*
     * Método para inserir registros na tabela tb_relatorio_acesso_item
     */

    public function InserirRegistros($dados) {
        $dados_auditor = $dados['RelatorioAcessoItem']['auditor_fk'];
        if (is_array($dados_auditor)) {
            foreach ($dados_auditor as $auditor_fk) {
                $auditor = Usuario::model()->findByPk($auditor_fk);
                //$this->VerificaAuditadoCorporativo($auditor['nome_login']);

                /* Deleta os registros anteriores deste relatório na  tb_relatorio_acesso_item
                 * para o cliente selecionado. 
                 */
                $this->exclui_itens_relatorio($dados['relatorio_fk'],$auditor['nome_login']);
                
                /*
                 * Obtem o usuário logado para salvar na tabela 
                 * tb_relatorio_acesso_item. Pois, somente este
                 * usuário terá permissão para deletar os registros
                 * inseridos neste momento. 
                 */
                $usuario_logado = Yii::app()->user->login;
                $relatorioAcessoItem = $dados['RelatorioAcessoItem'];
                $schema = Yii::app()->params['schema'];

                if ($relatorioAcessoItem['liberar_todos_itens']) {
                    $dados_capitulo = Capitulo::model()->findAll('relatorio_fk=' . $dados['relatorio_fk']);
                    foreach ($dados_capitulo as $vetor_capitulo) {
                        $itens = Item::model()->findAll('capitulo_fk=' . $vetor_capitulo['id']);
                        if (sizeof($itens)) {
                            foreach ($itens as $vetor_item) {
                                $dados_itens[] = $vetor_item['id'];
                            }
                        }
                    }
                } else {
                    $dados_itens = $relatorioAcessoItem['item_fk'];
                }

                $RelatorioAcesso = RelatorioAcesso::model()->findbyAttributes(array('nome_login' => $usuario_logado, 'relatorio_fk' => $dados['relatorio_fk']));
                foreach ($dados_itens as $vetor_item) {
                    $sql = "INSERT INTO " . $schema . '.tb_relatorio_acesso_item (Item_fk, nome_login, unidade_administrativa_fk, nome_login_cliente, data_liberacao) 
                    VALUES (' . $vetor_item . ",'" . $auditor['nome_login'] . "'," . $RelatorioAcesso->unidade_administrativa_fk . ", '" . $usuario_logado . "', '" . date("Y-m-d H:i:s") . "')";
                    
                    $command = Yii::app()->db->createCommand($sql);
                    $result = $command->query();
                }

                /*
                 * Notifica o auditor, gerente do relatório e o chefe de auditoria 
                 * a liberação do(s) iten(s) do relatório.  
                 */
                //PORTAL_SPB alterada a forma de gerar e-mail
                $this->envia_email_notificacao($dados['relatorio_fk'], $auditor['email'], $dados_itens);
            }
        }
    }

    
    /* Exclui itens da tb_relatorio_acesso_item, de acorco com 
     * o ID do relatório e o login do cliente_item
     */
    public function exclui_itens_relatorio ($id_relatorio,$login){
        $schema = Yii::app()->params['schema'];
        $sql="DELETE FROM $schema.tb_relatorio_acesso_item 
                    WHERE nome_login='$login' and 
                          item_fk IN (select item.id from siaudi.tb_item item 
                                        inner join $schema.tb_capitulo capitulo on item.capitulo_fk=capitulo.id
                                        inner join $schema.tb_relatorio relatorio on capitulo.relatorio_fk=relatorio.id
                                        where relatorio.id=$id_relatorio)";

        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();    
    }
    
    
    
    /* Envia e-mails, após liberar acesso aos itens do relatório.
     * @id_relatorio (int): ID do relatório
     * @e-mails (array): login para enviar o e-mail
     */

    public function envia_email_notificacao($id_relatorio, $email, $itens) {
        //auditor que recebeu acesso ao item
        $vetor_email[] = $email;

        // pega os gerentes do relatório
        //PORTAL_SPB alterada a forma de gerar e-mail
        $relatorio_gerente = RelatorioGerente::model()->findAllByAttributes(array('relatorio_fk' => $id));
        foreach ($relatorio_gerente as $vetor) {
            $gerente = Usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
            $vetor_email[] = $gerente->email;
        }

        // paga o e-mail do chefe de auditoria 
        //PORTAL_SPB obter o usuário que é chefe de auditoria
//        $cargo_chefe_auditoria = Cargo::model()->findAllByAttributes(
//                array(), $condition = "lower(nome_cargo) = 'chefe de auditoria'");
        $chefe_auditoria = Usuario::model()->findAllByAttributes(array(), $condition = "perfil_fk = :perfil_fk", $param = array(':perfil_fk' => 150));

        //adicionando o chefe de auditoria na lista de e-mails
        $vetor_email[] = $chefe_auditoria[0]['email'];

        //retirando os e-mails duplicados, caso exista
        $vetor_email = array_unique($vetor_email);

        // pega o título do relatório
        $model_relatorio = Relatorio::model()->findByAttributes(array('id' => $id_relatorio));
        $especie_auditoria = EspecieAuditoria::model()->findByAttributes(array('id' => $model_relatorio->especie_auditoria_fk));
        $titulo_relatorio = $id_relatorio . " - " . $especie_auditoria->sigla_auditoria;

        // configura parâmetros para enviar o e-mail
        $headers = "Reply-To: SIAUDI<".Yii::app()->params['adminEmail'].">\r\n";
        $headers .= "Return-Path: SIAUDI<".Yii::app()->params['adminEmail'].">\r\n";
        $headers .= "From: Unidade de Auditoria Interna <".Yii::app()->params['adminEmail'].">\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
        $assunto = 'SIAUDI - Notificação de Acesso a(os) Iten(s) do Relatório';

        // formata texto html                
        $mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
        $mensagem .= "Informamos que foi liberado acesso para o login " . ucwords(str_replace(".", " ", $email)) . " a(os) seguinte(s) iten(s) do <strong>Relatório de Auditoria Id Relatório: " . $titulo_relatorio . "</strong>:<br>";
        foreach ($itens as $vetor_item) {
            $dados_itens = Item::model()->findByPk($vetor_item);
            $mensagem .= $dados_itens['numero_item'] . " - " . $dados_itens['nome_item'] . "<br>";
        }
        $mensagem .= "</font></body></html>";

        // envia e-mails            
        //PORTAL_SPB alterada a forma de gerar e-mail
        foreach ($vetor_email as $vetor) {
            // verifica se o e-mail para este relatório já foi enviado
            // para evitar mais de 1 envio do mesmo relatório para o mesmo
            // destinarário
            if (!$check_email[$vetor]) {
                $check_email[$vetor] = 1;
                $destinatario = $vetor;// . Yii::app()->params['dominioEmail'];
                $ok = Relatorio::model()->Envia_email($destinatario, $assunto, $mensagem, $headers);
            }
        }
    }

    // Verifica se o usuário auditado está cadastrado no corporativo.
    // Se não estiver, insere. Se já estiver, verifica também
    // se o perfil está cadastrado como cliente_item. 
    // Parâmetro de entrada: login do usuário (ex: osvaldo.pateiro ) 
    public function VerificaAuditadoCorporativo($login) {
        $id_aplicacao = Yii::app()->getParams()->id_aplicacao;

        // pega ID do sistema 
        $sql_usuario = "SELECT id  FROM tb_sistema WHERE (upper(nome) = '" . strtoupper($id_aplicacao) . "') ";
        $command2 = Yii::app()->db->createCommand($sql_usuario);
        $result2 = $command2->query();
        $result2 = $result2->read();
        $sistema_id = $result2[id];

        $sql = "SELECT usuario.id 
                  FROM tb_usuario usuario 
                 WHERE (usuario.nome_login = '" . $login . "')";

        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        $resultados = $result->read();

        if ($result->rowCount) {

            // Consulta se usuário existe no corporativo, associado ao SIAUDI
            $sql = "SELECT usuario.id as idusuario, usuario.nome_login, perfil.id as idperfil, perfil.nome
                  FROM tb_usuario usuario INNER JOIN 
                   vw_usuario_perfil usuario_perfil ON usuario.id = usuario_perfil.usuario_fk INNER JOIN 
                   tb_perfil perfil ON usuario_perfil.perfil_fk = perfil.id INNER JOIN 
                   tb_sistema sistema ON sistema.id = perfil.sistema_fk
                  WHERE (sistema.id = " . $sistema_id . ") AND (usuario.nome_login = '" . $login . "') ";

            $command = Yii::app()->db->createCommand($sql);
            $result = $command->query();
            $resultados = $result->read();

            // Se usuário não existe no SIAUDI, então insere
            if (!$result->rowCount) {
                // pega ID do usuário
                $sql_usuario = "SELECT id  FROM tb_usuario WHERE (nome_login = '" . $login . "') ";
                
                $command2 = Yii::app()->db->createCommand($sql_usuario);
                $result2 = $command2->query();
                $result2 = $result2->read();
                $usuario_id = $result2[id];

                //Seleciona o ID do perfil (cliente_item)
                $sql_perfil = "SELECT id FROM tb_perfil WHERE (upper(nome) = 'SIAUDI_CLIENTE_ITEM') and sistema_fk = " . $sistema_id;
                $command3 = Yii::app()->db->createCommand($sql_perfil);
                $result3 = $command3->query();
                $result3 = $result3->read();
                $perfil_id = $result3[id];

                //Seleciona o último ID cadastrado na tabela vw_usuario_perfil, pois o campo id não é auto-incremento!
                $sql_ultimoid = "SELECT setval('vw_usuario_perfil_seq',(select max(id) from vw_usuario_perfil))";
                $command4 = Yii::app()->db->createCommand($sql_perfil);
                $result4 = $command4->query();
                $result4 = $result4->read();
                $ultimo_id = $result4[id];

                // Insere o usuário associado ao perfil 
                $sql_inc_userperfil = "INSERT INTO vw_usuario_perfil (id, usuario_fk, perfil_fk)
                                                VALUES (nextval('vw_usuario_perfil_seq'), 
                                                        '" . $usuario_id . "','" . $perfil_id . "') ";

                $command5 = Yii::app()->db->createCommand($sql_inc_userperfil);
                $result5 = $command5->query();

                // Se usuário já existe no SIAUDI, verificar 
                // se o perfil atual é como cliente_item
            }
        }
//        else {
//            $usuario_id = $resultados[idusuario];
//            $usuario_login = $resultados[login];
//            $perfil_id = $resultados[idperfil];
//            $perfil_nome = strtoupper($resultados[nome]);
//
//            if ($perfil_nome != "SIAUDI_CLIENTE_ITEM") {
//                $sql_perfil = "SELECT id FROM tb_perfil WHERE (upper(nome) = 'SIAUDI_CLIENTE_ITEM') and sistema_fk =" . $sistema_id;
//                $command6 = Yii::app()->db->createCommand($sql_perfil);
//                $result6 = $command6->query();
//                $result6 = $result6->read();
//                $perfil_id_novo = $result6[id];
//
//                $sql_alt_userperfil = "UPDATE vw_usuario_perfil 
//                                           SET perfil_fk = '" . $perfil_id_novo . "'
//                                           WHERE (usuario_fk = '" . $usuario_id . "') 
//                                             AND (perfil_fk = '" . $perfil_id . "') ";
//                $command7 = Yii::app()->db->createCommand($sql_alt_userperfil);
//                $result7 = $command7->query();
//            }
//        }
    }

}
