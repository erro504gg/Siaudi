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

Yii::import('application.models.table._base.BaseAvaliacaoCriterio');

class AvaliacaoCriterio extends BaseAvaliacaoCriterio
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'id' => Yii::t('app', 'ID'),
            'numero_questao' => Yii::t('app', 'Nº da questão'),
            'descricao_questao' => Yii::t('app', 'Descrição da questão'),
            'valor_exercicio' => Yii::t('app', 'Exercício'),
            
            
        );
        return array_merge($attribute_default, $attribute_custom);
    }
    
    
    // Verifica se existem critérios de avaliação cadastrados
    // para o exercício atual. Caso não existam, envia e-mail
    // para os gerentes da Auditoria avisando.
    public function VerificaAvaliacaoCriterio(){
        $exercicio = date("Y");
        $Criterios= AvaliacaoCriterio::model()->findAllByAttributes(array('valor_exercicio'=>$exercicio));
        // se não encontrou critérios no exercício, identifica gerentes e chefes de auditoria
        if (sizeof($Criterios)==0){
            //pega gerentes e chefes da AUDIN
            $cargo_gerente = Perfil::model()->findByAttributes(array('nome_interno'=>'SIAUDI_GERENTE'));
            $cargo_chefe = Perfil::model()->findByAttributes(array('nome_interno'=>'SIAUDI_CHEFE_AUDITORIA'));

            $gerentes = Usuario::model()->findAllByAttributes(array('perfil_fk'=>$cargo_gerente->id));
            $chefes = Usuario::model()->findAllByAttributes(array('perfil_fk'=>$cargo_chefe->id)); 
            if(is_array($gerentes)){
                foreach ($gerentes as $vetor){
                    $vetor_email[]=$vetor->nome_login;
                }
            }
            if(is_array($chefes)){
                foreach ($chefes as $vetor){
                    $vetor_email[]=$vetor->nome_login;
                }
            }                 
            // configura parâmetros para enviar o e-mail
            $headers = "Reply-To: SIAUDI - CONAB <siaudi@conab.gov.br>\r\n";
            $headers .= "Return-Path: SIAUDI - CONAB <siaudi@conab.gov.br>\r\n";
            $headers .= "From: FROM SIAUDI <siaudi@conab.gov.br>\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
            $assunto = 'SIAUDI - Cadastrar critérios de avaliação dos auditores';

            // formata texto html
            $mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
            $mensagem.= "Esta é uma mensagem automática. O sistema SIAUDI informa que está sem ";
            $mensagem.= "critérios de avaliação dos auditores, para o exercício de <strong>".$exercicio."</strong>.<br><br>";
            $mensagem.= "É necessário cadastrar tais critérios para que as unidades auditadas possam avaliar ";
            $mensagem.= "seus respectivos auditores. Para tanto, acesse o menu ";
            $mensagem.= " 'Configurações -> Avaliação do Auditor -> Gerenciar Critérios de Avaliação'";
            $mensagem.= "</font></body></html>";    

            // envia e-mails            
            foreach($vetor_email as $vetor){ 
                // verifica se o e-mail já foi enviado para evitar mais 
                // de 1 envio para o mesmo destinarário
                if(!$check_email[$vetor]){
                    $check_email[$vetor]=1;
                    $destinatario = $vetor."@conab.gov.br";
                    $ok = Relatorio::model()->Envia_email($destinatario, $assunto, $mensagem, $headers);
                }
            }
        }
    }
    
    
    /* Copia os critérios de avaliação de um ano para o outro.
     * Se o ano atual não tem critérios cadastrados, verifica
     * se o ano anterior tem, e então, copia para o ano atual. 
     */
    public function CopiaAvaliacaoCriterio(){
        $ano_atual = date("Y");
        $ano_anterior= date("Y")-1;
        $AvaliacaoCriterio = $this->findAll('valor_exercicio='.$ano_atual);
        // se não existem critérios de avaliação no ano atual, 
        // então  verifica se existem no ano anterior para copiar
        if(sizeof($AvaliacaoCriterio)==0){
            $AvaliacaoCriterio2 = $this->findAll('valor_exercicio='.$ano_anterior);
            // se exitem critérios no ano anterior, então copia para o atual
            if(sizeof($AvaliacaoCriterio2)>0){
                foreach($AvaliacaoCriterio2 as $vetor){
                    $Criterio = new AvaliacaoCriterio();
                    $Criterio->descricao_questao = $vetor[descricao_questao];
                    $Criterio->valor_exercicio= $ano_atual;
                    $Criterio->numero_questao = $vetor[numero_questao];
                    $Criterio->save();
                }
            }
        }
    }
}
