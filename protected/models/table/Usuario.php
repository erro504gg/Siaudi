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

Yii::import('application.models.table._base.BaseUsuario');

class Usuario extends BaseUsuario {

    public $bolDeAcordo;
    public $afterFind=true;
    public $senhaAtual;
    public $confirmaEmail;
    public $confirmaSenha;


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function label($n = 1) {
            return Yii::t('app', 'Usuário|Usuários', $n);
    }
    
    public function rules() {
        return array(
            array('nome_login, nome_usuario, cpf, funcao_fk, cargo_fk, perfil_fk, nucleo_fk, unidade_administrativa_fk', 'required', 'on' => 'inclusao, update'),
            array('nome_login', 'length', 'max' => 60),
            array('nome_usuario', 'length', 'max' => 400),
            //array('cpf', 'length', 'max' => 14),
            array('cpf', 'validaCpf', 'on' => 'inclusao, update'),
            array('email', 'length', 'max' => 100),
            
            array('email, confirmaEmail', 'required', 'on' => 'inclusao, update'),//'except' => 'alteraSenha'
            
            array('email, confirmaEmail', 'email','message' => 'O campo {attribute} é inválido.'),
            array('confirmaEmail', 'validaEmail', 'on' => 'inclusao, update'),
            
            array('senha, confirmaSenha', 'required', 'on' => 'inclusao, alteraSenha, alteraMinhaSenha'),
            array('senhaAtual', 'required', 'on' => 'alteraMinhaSenha'),
            
            array('confirmaSenha', 'validaSenha', 'on' => 'inclusao, alteraMinhaSenha'),
            array('senhaAtual', 'validaSenhaAtual', 'on' => 'inclusao, alteraMinhaSenha'),
            
            array('senha', 'length', 'max' => 255),
            //array('substituto_fk', 'safe'),
            array('perfil_fk, nucleo_fk, unidade_administrativa_fk, cargo_fk, funcao_fk, substituto_fk', 'numerical', 'integerOnly' => true),
            array('nome_login, id, nome_usuario, perfil_fk, nucleo_fk, unidade_administrativa_fk, cargo_fk, substituto_fk, funcao_fk', 'safe', 'on' => 'search'),
        );
    }    

    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'nome_login' => Yii::t('app', 'Login '),
            'id' => Yii::t('app', 'ID'),
            'nome_usuario' => Yii::t('app', 'Nome'),
            'perfil_fk' => Yii::t('app', 'Perfil'),
            'nucleo_fk' => Yii::t('app', 'Núcleo'),
            'cargo_fk' => Yii::t('app', 'Cargo'),
            'funcao_fk' => Yii::t('app', 'Função'),
            'unidade_administrativa_fk' => Yii::t('app', 'Unidade Administrativa (Lotação)'),
            'nucleoFk' => Yii::t('app', 'Núcleo'),
            'substituto_fk' => Yii::t('app', 'Substituto'),
            'substitutoFk' => Yii::t('app', 'Substituto'),
            'confirmaEmail' => Yii::t('app', 'Confirmar e-mail'),
            'confirmaSenha' => Yii::t('app', 'Confirmar senha'),
            'senhaAtual' => Yii::t('app', 'Senha atual'),
        );
        return array_merge($attribute_default, $attribute_custom);
    }

    /**
     * Validar um número como sendo CNPJ ou CPF
     * @param type $attribute possui o valor do campo
     * @param type $params parâmetro padrão de métodos de validação da model
     */
    public function validaCpf($attribute, $params) {
        if ($this->$attribute) {
            $numero = CMask::removeMascara($this->$attribute);
            if (strlen($numero) == 11) {
                if (!CMask::validaCPF($numero))
                    $this->addError($attribute, 'O CPF informado é inválido.');
            } else
                $this->addError($attribute, 'O número do CPF informado é inválido.');
        }
    }
    
    /**
     * Valida se a confirmação do e-mail é igual ao e-mail informado
     * @param type $attribute
     * @param type $params
     */

    public function validaEmail($attribute, $params) {
        if (($this->email != $this->$attribute)) {
            $this->addError($attribute, 'O e-mail e a confirmação do e-mail não são iguais.');
        }
    }

    /**
     * Valida se a confirmação da senha é igual à senha informada
     * @param type $attribute
     * @param type $params
     */

    public function validaSenha($attribute, $params) {
        if (($this->senha != md5($this->$attribute))) {
            $this->addError($attribute, 'A senha e a confirmação da senha não são iguais.');
        }
    }

    /**
     * Confirma a senha atual digitada pelo usuário
     * @param type $attribute
     * @param type $params
     */
    public function validaSenhaAtual($attribute, $params) {
        if ($this->$attribute) {
            $usuario = Usuario::model()->findByAttributes(array('id' => Yii::app()->user->id));
            if (sizeof($usuario) > 0){
              if($usuario->senha !=  md5($this->$attribute)){
                  $this->addError($attribute, 'A senha atual não confere.');
              }
            }else{
                $this->addError($attribute, 'Usuário não encontrado.');
            }

            
            
        }
    }
    
    /**
     * Método executado antes de salvar informações no banco
     * Normalmente realiza ou desfaz formatações de campos específicas para correto
     * armazenamento no BD
     * @return type
     */
    public function beforeSave() {
        if (isset($this->cpf)) {
            $this->cpf = CMask::removeMascara($this->cpf);
        }
        if (isset($this->email)) {
            $this->email = trim($this->email);
        }
        return parent::beforeSave();
    }
    
    public function afterFind() {
        parent::afterFind();
        if ($this->afterFind) {
            $this->nome_usuario = rtrim($this->nome_usuario);
            if (strlen($this->nome_usuario) > 70) {
                $this->nome_usuario = substr($this->nome_usuario, 0, 70) . "...";
            }
        }
    }

    // monta a combo de gerentes do relatório,
    // que receberá todos os gerentes atuais da Auditoria,
    // além do(s) gerente(s) que já estava(m) gravado(s) (caso este(s)
    // não seja(m) mais gerente(s)), para que não haja
    // perda de dados no histórico
    public function listaGerentes($id_gerentes = null) {
        $schema = Yii::app()->params['schema'];
        // mantem os gerentes previamente gravados na lista
        $sql_where = "";
        if (is_array($id_gerentes)) {
            foreach ($id_gerentes as $vetor) {
                $sql_where .=" or usuario.id=" . $vetor->usuario_fk;
            }
        }
        $sql = "SELECT usuario.id, usuario.nome_usuario FROM " . $schema . ".tb_usuario usuario
                        WHERE (usuario.perfil_fk=(SELECT id FROM " . $schema . ".tb_perfil WHERE nome_interno = 'SIAUDI_GERENTE') {$sql_where}) order by usuario.nome_usuario ASC";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        return ($result->readAll());
    }

    // recebe o login do usuario retorna o link com o nome
    public function usuario_por_login($login) {
        // $baseUrl2 = "http://".$_SERVER["HTTP_HOST"] . Yii::app()->baseUrl;
        $baseUrl2 = "..";
        $usuario = Usuario::model()->findByAttributes(array('nome_login' => trim($login)));
        if (sizeof($usuario) > 0) {
            $retorno = $usuario->nome_usuario;
            //$retorno = "<a href='{$baseUrl2}/usuario/view/{$usuario->id}'>{$usuario->nome_usuario}</a>";
        } else {
            $retorno = trim($login);
        }
        return $retorno;
    }

    public function RevogaPermissaoRelatorioAcesso($login, $unidade_administrativa_fk) {
        $schema = Yii::app()->params['schema'];
        $sql = "UPDATE " . $schema . ".tb_relatorio_acesso
                   SET nome_login = ''  
                 WHERE (nome_login = '" . $login . "' and unidade_administrativa_fk = " . $unidade_administrativa_fk . ") ";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        $resultado = $result->read();
    }

    public function SubstituiUsuarioCargo($novo_login, $unidade_administrativa_fk, $cargo_fk) {
        $schema = Yii::app()->params['schema'];
        $usuario_vigente = Usuario::model()->findAllByAttributes(array('unidade_administrativa_fk' => $unidade_administrativa_fk, 'cargo_fk' => $cargo_fk));
        if (sizeof($usuario_vigente)) {
            $sql = "UPDATE " . $schema . ".tb_relatorio_acesso
                   SET nome_login = ''  
                 WHERE (nome_login = '" . $usuario_vigente[0]['nome_login'] . "' and unidade_administrativa_fk = " . $unidade_administrativa_fk . ") ";
            $command = Yii::app()->db->createCommand($sql);
            $result = $command->query();
        }

        $sql = "UPDATE " . $schema . ".tb_relatorio_acesso
                   SET nome_login = '" . $novo_login . "'  
                 WHERE (nome_login = '' and unidade_administrativa_fk = " . $unidade_administrativa_fk . ") ";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        $resultado = $result->read();

        $sql = "UPDATE " . $schema . ".tb_usuario
                   SET cargo_fk = null,
                       unidade_administrativa_fk = null
                 WHERE (nome_login = '" . $usuario_vigente[0]['nome_login'] . "' and unidade_administrativa_fk = " . $unidade_administrativa_fk . ") ";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        $resultado = $result->read();
    }

    public function ObtemUsuariosSubordinados() {
        $schema = Yii::app()->params['schema'];
        $dados_usuario_logado = Usuario::model()->findByAttributes(array('nome_login' => Yii::app()->user->login));
        $unidade_subordinante = $dados_usuario_logado->unidade_administrativa_fk;

        $subordinantes_fk = UnidadeAdministrativa::model()->findAllByAttributes(array('subordinante_fk' => $unidade_subordinante));
        $clausula_in = "";

        if (sizeof($subordinantes_fk)) {
            foreach ($subordinantes_fk as $vetor) {
                $clausula_in .= $vetor['id'] . ",";
                $clausula_in .= Usuario::ObtemSubordinantes($vetor['id']);
            }
        }

        $clausula_in .= $dados_usuario_logado->unidade_administrativa_fk;

        $sql = "SELECT ID, NOME_USUARIO 
                  FROM " . $schema . ".TB_USUARIO 
                 WHERE UNIDADE_ADMINISTRATIVA_FK IN (" . $clausula_in . ") 
                   AND NOME_LOGIN <> '" . $dados_usuario_logado->nome_login . "' 
                 ORDER BY NOME_USUARIO";

        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        return ($result->readAll());
    }

    public function ObtemUnidadesAdministrativasSubordinadas() {
        $schema = Yii::app()->params['schema'];
        $dados_usuario_logado = Usuario::model()->findByAttributes(array('nome_login' => Yii::app()->user->login));

        $subordinantes_fk = UnidadeAdministrativa::model()->findAllByAttributes(array('subordinante_fk' => $dados_usuario_logado->unidade_administrativa_fk));

        $clausula_in = "";

        if (sizeof($subordinantes_fk)) {
            foreach ($subordinantes_fk as $vetor) {
                $clausula_in .= $vetor['id'] . ",";
                $clausula_in .= Usuario::ObtemSubordinantes($vetor['id']);
            }
        }

        $clausula_in .= $dados_usuario_logado->unidade_administrativa_fk;

        $sql = "SELECT ID, SIGLA 
                  FROM " . $schema . ".TB_UNIDADE_ADMINISTRATIVA 
                 WHERE ID IN (" . $clausula_in . ") 
                 ORDER BY SIGLA";

        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        return ($result->readAll());
    }

    public function ObtemSubordinantes($unidade_administrativa_fk) {
        $subordinantes_fk = UnidadeAdministrativa::model()->findAllByAttributes(array('subordinante_fk' => $unidade_administrativa_fk));
        $subordinantes = "";

        if (sizeof($subordinantes_fk)) {
            foreach ($subordinantes_fk as $vetor) {
                $subordinantes .= $vetor['id'] . ",";
                $subordinantes .= Usuario::ObtemSubordinantes($vetor['id']);
            }
        }
        return $subordinantes;
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('nome_login', $this->nome_login, true);
        $criteria->compare('id', $this->id, true);
        $criteria->compare('nome_usuario', str_replace("...", "", $this->nome_usuario), true);
        $criteria->compare('perfil_fk', $this->perfil_fk);
        $criteria->compare('nucleo_fk', $this->nucleo_fk);
        $criteria->compare('unidade_administrativa_fk', $this->unidade_administrativa_fk);
        $criteria->compare('cargo_fk', $this->cargo_fk);
        $criteria->compare('funcao_fk', $this->funcao_fk);
        $criteria->compare('substituto_fk', $this->substituto_fk);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /* Carrega o nome do usuário completo,
     * pois o after find corta o nome caso tenha 
     * um tamanho maior que 70 caracteres
     *  @id (int): id do usuário
     */

    public function nome_usuario_completo($id) {
        $schema = Yii::app()->params['schema'];
        $sql = "SELECT NOME_USUARIO 
                  FROM " . $schema . ".TB_USUARIO 
                 WHERE id=$id";

        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        return ($result->read());
    }

}
