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

class UsuarioController extends GxController {

    public $titulo = 'Gerenciar Usuários';
    
    public function init() {
        parent::init();
        
        if (!Yii::app()->user->verificaPermissao("usuario", "admin")) {
            $this->redirect(array('site/acessoNegado')); 
            exit;
        }
        
        $this->defaultAction = 'index';

        $this->menu_acao = array(
        	array('label' => 'Consultar', 'url' => array('Usuario/index')),
            array('label' => 'Incluir', 'url' => array('Usuario/admin')),
        );
    }

    public function actionAdmin($id = 0, $cenario = '') {
        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id, 'Usuario');
            if($cenario == 'p'){
                $model->scenario = 'alteraSenha';
                $model->senha = '';
            }
            $model->afterFind = false;
        } else {
            $this->subtitulo = 'Inserir';
            $model = new Usuario('inclusao');
//            $model->isNewRecord = 1;
        }
        
//        if ($msgInconsistencia == '') {
        if (isset($_POST['Usuario'])) {
            // var_dump($_POST['Usuario']); exit();
            if (!($this->VerificaSeLoginExiste($id))) {
                $model->attributes = $_POST['Usuario'];
                if (empty($model->substituto_fk)) {
                    $model->substituto_fk = null;
                }
                // var_dump($model->attributes); exit();
                if ($id) {
                    if ($cenario = 'p') {
                        $model->senha = md5($model->senha);
                    }
                    if (isset($_POST['Usuario']['bolDeAcordo'])) {
                        if ($_POST['Usuario']['bolDeAcordo']) {
                            $perfil = Perfil::model()->findByPk($_POST['Usuario']['perfil_fk']);
                            if (strtoupper($perfil->nome_interno) == 'SIAUDI_CLIENTE') {
                                $model->SubstituiUsuarioCargo($_POST['Usuario']['nome_login'], $_POST['Usuario']['unidade_administrativa_fk'], $_POST['Usuario']['cargo_fk']);
                            } else {
                                $model->RevogaPermissaoRelatorioAcesso($_POST['Usuario']['nome_login'], $_POST['Usuario']['unidade_administrativa_fk']);
                            }
                        }
                    }
                } else {
                    $model->senha = md5($model->senha);
                }
                                
                if ($model->save()) {
                    $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                    $this->redirect(array('index?' . $_SERVER['QUERY_STRING']));
                } 
                
            } else {
                $model->addError("nome_login", "O login informado já está cadastrado. Favor informar outro login.");
            }
        }
//        } else {
//            $model->addError('', "Atenção, os dados não foram gravados devido a restrição abaixo.");
//        }

        $this->render('admin', array(
            'model' => $model,
            'titulo' => $this->titulo,
            'msgInconsistencia' => $msgInconsistencia
        ));
    }

    public function actionDelete($id) {
        $this->layout = false;
        if (Yii::app()->getRequest()->getIsPostRequest()) {

            $model = new Usuario;
            $usuario = Usuario::model()->findByPk($id);
            $model->RevogaPermissaoRelatorioAcesso($usario->nome_login, $usuario->unidade_administrativa_fk);
            
            $this->loadModel($id, 'Usuario')->delete();

            if (Yii::app()->getRequest()->getIsAjaxRequest()) {
                $this->setFlashSuccesso('excluir');
                echo $this->getMsgSucessoHtml();
            } else {
                $this->setFlashError('excluir');
                $this->redirect(array('admin'));
            }
        }
        else
            throw new CHttpException(400, Yii::t('app', 'Sua requisição é inválida.'));
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new Usuario('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Usuario'])) {

            $model->attributes = $_GET['Usuario'];
            $dados = $model->search();
        }

        $this->render('index', array(
            'model' => $model,
            'dados' => $dados,
            'titulo' => $this->titulo,
        ));
    }

    public function actionView($id) {
        $this->layout = false;
        $this->render('view', array(
            'model' => $this->loadModel($id, 'Usuario'),
        ));
    }

    private function VerificaSeLoginExiste($id) {
        $login_informado = $_POST['Usuario']['nome_login'];
        $dados_login_informado = Usuario::model()->findAllByAttributes(array('nome_login' => $login_informado));
        $retorno = sizeof($dados_login_informado);

        if ($id) {
            $dados_login_cadastrado = Usuario::model()->findByPk($id);
            if (sizeof($dados_login_cadastrado)) {
                if (strtolower($dados_login_cadastrado->nome_login) == strtolower($login_informado))
                    $retorno = false;
            }
        }
        return $retorno;
    }
    /**
     * Verificar se o login informado já está cadastrado
     * Se for siaudi_cliente revoga os acessos do antigo siaudi_cliente
     * e dá acesso ao novo a esses relatórios
     * @return string
     */
    private function ValidaUsuarioJaCadastrado() {
        $msg = '';
        $msgPerfil = '';
        $msgFuncao = '';
        $id = $_POST['id'];

        if (!empty($id)) {
            //dados do usuário antes da alteração
            $usuario_cadastro_base = Usuario::model()->findByPk($id);
            $unidade_administrativa_cadastro_base = UnidadeAdministrativa::model()->findByPk($usuario_cadastro_base->unidade_administrativa_fk);

            //dados do usuário informado da tela
            $unidade_administrativa_fk_cadastro = $_POST['unidade_administrativa_fk'];
            $perfil_fk_cadastro = $_POST['id_perfil_selecionado'];
            $nome_usuario = $_POST['nome_usuario'];

            //verificar se houve alteração das informações que possam gerar revogação de acesso
            //Primeira verificação: verifica se o usuário perdeu o perfil de cliente
            $perfil = Perfil::model()->findByPk($usuario_cadastro_base->perfil_fk);
            if (($usuario_cadastro_base->perfil_fk != $perfil_fk_cadastro) && (trim(strtolower($perfil->nome_interno)) == "siaudi_cliente")) {

                $msgPerfil = $this->ObtemMsgRelatorioAcesso($_POST['nome_login'], $_POST['unidade_administrativa_fk']);

                if ($msgPerfil) {
                    $msgPerfil = "<br>Atenção, o usuário " . trim($_POST['nome_usuario']) . " da unidade " . trim($unidade_administrativa_cadastro_base->sigla) . " terá seu acesso revogado, como auditado, aos relatórios abaixo: <br><br>" . $msgPerfil;
                }
            } //fim verificação do perfil
            //Segunda verificação: verifica se o usuário passou a ser SIAUDI_CLIENTE
            $perfil_tela = Perfil::model()->findByPk($perfil_fk_cadastro);
            //PORTAL_SPB
            if ((($usuario_cadastro_base->perfil_fk != $perfil_fk_cadastro) && (trim(strtolower($perfil_tela->nome_interno)) == "siaudi_cliente"))  ||
                    (($usuario_cadastro_base->unidade_administrativa_fk != $unidade_administrativa_fk_cadastro) && (trim(strtolower($perfil_tela->nome_interno)) == "siaudi_cliente"))) {

                //Verifica se existe SIAUDI_CLIENTE na Unidade Administrativa informada 
                $usuario_atual = Usuario::model()->findByAttributes(array('perfil_fk' => $perfil_tela->id,
                    'unidade_administrativa_fk' => $unidade_administrativa_fk_cadastro));


                //caso exista, informamos para o operador que se ele continuar com a operação o siaudi_cliente/substituto atual será susbstituido 
                //pelo usuário que ele esta informando e o mesmo receberá as permissões aos relatórios que o atual siaudi_cliente/substituto tem acesso.
                if (sizeof($usuario_atual)) {
                    //Obtem mensagem com os relatórios que o atual siaudi_cliente/substituto possui acesso já formatado.
                    $msgFuncao = $this->ObtemMsgRelatorioAcesso($usuario_atual->nome_login, $usuario_atual->unidade_administrativa_fk);

                    //caso o siaudi_cliente/substituto tenha acesso, complementamos a mensagem de retorno.
                    if ($msgFuncao) {
                        $unidade_administrativa = UnidadeAdministrativa::model()->findByPk($usuario_atual->unidade_administrativa_fk);
                        $msgFuncao = "Atenção, o usuário ". trim($usuario_atual->nome_usuario) . ", da unidade " . trim($unidade_administrativa->sigla) . ", terá seu acesso revogado aos relatórios abaixo, sendo transferidos para o usuário " . trim($nome_usuario) . ":<br><br>" . $msgFuncao;
                        $msgFuncao .= '<br><br><strong>O usuário '.trim($nome_usuario).' terá acesso somente a estes relatórios descritos, tendo o acesso revogado a relatórios anteriores.</strong><br><br>';
                    }
                }
            } //fim verificação da função/unidade_administrativa
            
            //PORTAL_SPB
            if ((($usuario_cadastro_base->perfil_fk != $perfil_fk_cadastro) && (trim(strtolower($perfil->nome_interno)) == "siaudi_cliente")) ||
                    (($usuario_cadastro_base->unidade_administrativa_fk != $unidade_administrativa_fk_cadastro) && (trim(strtolower($perfil->nome_interno)) == "siaudi_cliente"))) {

                if ($usuario_cadastro_base->unidade_administrativa_fk != $unidade_administrativa_fk_cadastro) {
                    $msgPerfil = $this->ObtemMsgRelatorioAcesso($_POST['nome_login'], $usuario_cadastro_base->unidade_administrativa_fk);
                } else {
                    $msgPerfil = $this->ObtemMsgRelatorioAcesso($_POST['nome_login'], $_POST['unidade_administrativa_fk']);
                }

                if ($msgPerfil) {
                    $msgPerfil = "<br>Atenção, o usuário " . trim($_POST['nome_usuario']) . " da unidade " . trim($unidade_administrativa_cadastro_base->sigla) . " terá seu acesso revogado, como auditado, aos relatórios abaixo: <br><br>" . $msgPerfil;
                }
            } //fim verificação do perfil            
        } //fim verificação id

        $msg = $msgPerfil . $msgFuncao;
        return $msg;
    }

    public function actionVerificaRelatorioAcessoAjax() {
        $msg = '';
        $bol_novo_registro = $_POST['novo_registro'];
        if ($bol_novo_registro) {
            $msg = $this->ValidaNovoUsuario();
        } else {
            $msg = $this->ValidaUsuarioJaCadastrado();
        }
        echo $msg;
    }

    private function ValidaNovoUsuario() {
        $msg = '';
        $nome_usuario = $_POST['nome_usuario'];
        $unidade_administrativa_fk = $_POST['unidade_administrativa_fk'];

        //PORTAL_SPB
        $perfil_fk = $_POST['perfil_fk'];

        //Verifica se existe siaudi_cliente ou substituto 
        $usuario_atual = Usuario::model()->findByAttributes(array('perfil_fk' => $perfil_fk,
            'unidade_administrativa_fk' => $unidade_administrativa_fk));

        //caso exista, informamos para o operador que se ele continuar com a operação o siaudi_cliente/substituto atual será susbstituido 
        //pelo usuário que ele esta informando e o mesmo receberá as permissões aos relatórios que o atual siaudi_cliente/substituto tem acesso.
        if (sizeof($usuario_atual)) {
            //Obtem mensagem com os relatórios que o atual siaudi_cliente/substituto possui acesso já formatado.
            $msg = $this->ObtemMsgRelatorioAcesso($usuario_atual->nome_login, $usuario_atual->unidade_administrativa_fk);

            //caso o usuário tenha acesso, complementamos a mensagem de retorno.
            if ($msg) {
                $unidade_administrativa = UnidadeAdministrativa::model()->findByPk($usuario_atual->unidade_administrativa_fk);
                $msg = "Atenção, o usuário ". trim($usuario_atual->nome_usuario) . ", da unidade " . trim($unidade_administrativa->sigla) . ", terá seu acesso revogado aos relatórios abaixo, sendo transferidos para o usuário " . trim($nome_usuario) . ":<br><br>" . $msg;
                $msg .= '<br><br><strong>O usuário '.trim($nome_usuario).' terá acesso somente a estes relatórios descritos, tendo o acesso revogado a relatórios anteriores.</strong><br><br>';
            }
        }

        return $msg;
    }

    private function ObtemMsgRelatorioAcesso($nome_login, $unidade_administrativa_fk) {
        $msgHomologados = '';
        $msgNaoHomologados = '';
        //obtem os relatórios que o usuário possui acesso.
        $dados_relatorio_acesso = RelatorioAcesso::model()->findAllByAttributes(array('nome_login' => $nome_login,
            'unidade_administrativa_fk' => $unidade_administrativa_fk), array('order' => 'relatorio_fk'));

        //monta mensagem com os relatórios
        if (sizeof($dados_relatorio_acesso)) {
            foreach ($dados_relatorio_acesso as $vetor) {
                $dados_relatorio = Relatorio::model()->findByPk($vetor['relatorio_fk']);
                if ($dados_relatorio->data_relatorio) {
                    $msgHomologados .= "Relatório " . $dados_relatorio->numero_relatorio . ", de " . $dados_relatorio->data_relatorio . "<br>";
                } else {
                    $msgNaoHomologados .= "Relatório ID " . $dados_relatorio->id . "<br>";
                }
            }
        }
        return $msgNaoHomologados . $msgHomologados;
    }
}
