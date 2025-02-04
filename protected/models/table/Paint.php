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

Yii::import('application.models.table._base.BasePaint');

class Paint extends BasePaint
{
    public $valor_exercicio; 
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
			'id' => Yii::t('app', 'ID'),
			'nome_titulo' => Yii::t('app', 'Título'),
			'descricao_texto' => Yii::t('app', 'Texto'),
			'numero_sequencia' => Yii::t('app', 'Sequência'),
			'anexo_id' => null,
			'numero_item_pai' => null,
			'numeroItemPai' => null,
			'anexo' => null,
			'valor_exercicio' => Yii::t('app', 'Exercício'),
        );
        return array_merge($attribute_default, $attribute_custom);
    }   
    

    


    // carrega os itens do paint do banco para montar o json
    public function carrega_paint($exercicio) {

                $schema = Yii::app()->params['schema'];        
                $sql = "SELECT t.id, t.nome_titulo, t.descricao_texto, t.numero_sequencia, t.numero_item_pai
                        FROM ". $schema . ".tb_paint t
                        WHERE (t.valor_exercicio=".$exercicio." )
                        ORDER BY t.id ASC";
                $command = Yii::app()->db->createCommand($sql);
                $result = $command->query();
                
                               
                // monta somente o vetor com os fihos
                $arvore= $result->readAll();
                
                //$vetor_arvore[] = array(); 
                foreach ($arvore as $vetor) {
                    $pai = ($vetor['numero_item_pai'])? $vetor['numero_item_pai'] : "0";
                        $vetor_arvore[] = array("id" => $vetor['id'],
                            "text" => strip_tags($vetor['nome_titulo']),
                            "attributes" => array(
                                "titulo" => $vetor['nome_titulo'],
                                "texto" => $vetor['descricao_texto'],
                                "sequencia" => $vetor['numero_sequencia']
                            ),
                            "numero_item_pai" => $pai
                        );
                }
                
                $vetor_completo = $vetor_arvore;

                if (is_array($vetor_completo)!=true){
                    $vetor_completo=""; 
                    }
               return ($vetor_completo);              
    }
    
   
 

    
    
    
    // Exclui os itens do PAINT salvos deste exercício
    // e inclui os itens novos da lista 
    public function prepara_paint() {
        $this->deleteAll("valor_exercicio=" . $_POST["exercicio"]);
        
       $paint = $_POST["paint"];

       // limpa aspas e quebra de linha para o JSON_DECODE funcionar
       $paint = str_replace("\\\"","&aspasduplas",$paint);
       $paint = str_replace(array("'","\n","\r"),array("\"","&quebradelinha",""),$paint);              
       $paint = str_replace("\n","",$paint);                     


       $paint=preg_replace('/,\s*([\]}])/m', '$1', $paint );
       $itens_paint =json_decode(utf8_encode($paint));
        $this->salvar($itens_paint);        
    }    

    
    
    // salva os itens do PAINT de
    // acordo com a árvore de pais e filhos
    public function salvar($arvore,$pai=null){
       $schema = Yii::app()->params['schema'];        
       $exercicio = $_POST["exercicio"];

       
       if (is_array($arvore)){
                foreach ($arvore as $vetor ) { 
                    

                    // coloca novamente as aspas  e quebra de linha para enviar ao banco
                   $texto= str_replace(array("&aspasduplas","&quebradelinha"),array("\"","\n"),$vetor->attributes->texto);
                   $numero_pdf = $vetor->attributes->numeracao;
       
                    $pai = ($pai==null)? "null" : $pai;             
                    $sql = "INSERT INTO " . $schema . ".tb_paint
                            (nome_titulo, descricao_texto, numero_sequencia, numero_item_pai, valor_exercicio, numero_pdf, data_gravacao) 
                            VALUES ('". $vetor->attributes->titulo . "','". $texto . "',".
                            $vetor->attributes->sequencia .",". $pai. ",". $exercicio .",'" . $numero_pdf ."','".date("Y-m-d")."');";                         
                    $command = Yii::app()->db->createCommand($sql);
                    $command->execute();

                   // pega último id inserido
                    $sql = "SELECT currval('". $schema .".tb_paint_id_seq')"; 
                    $command = Yii::app()->db->createCommand($sql);
                    $command->execute();            
                    $result = $command->query();
                    $last_id = $result->read();
                    $pai2=$last_id['currval'];
                     

                    // se tem filhos, insere os filhos com o id do pai
                    if (is_array($vetor->children)){               
                       $this->salvar($vetor->children,$pai2);
                    }
              }
      }
    }
         
           

    // Verifica se usuário pode acessar o PAINT para edição.
    // Pela regra atual, somente os perfis de Gerente e Chefe
    // de Auditoria podem altera-lo. 
    public function acesso_paint() {
        $perfil = strtolower(Yii::app()->user->role);
        $perfil = str_replace("siaudi2", "siaudi", $perfil);              

        if (!($perfil=="siaudi_gerente" || $perfil=="siaudi_chefe_auditoria")) {
            return 0;
        }
        
        return 1; 
    }       
    
    
    // Converte título da ação de auditoria para maiúsculo
    public function acao_strtoupper($titulo){
        $vetor_minuscula = array('ç','ã','õ','á','é','ó','â','ê','ô','í','ú','à'); 
        $vetor_maiuscula = array('Ç','Ã','Õ','Á','É','Ó','Â','Ê','Ô','Í','Ú','À');         
        $titulo = strtoupper($titulo);
        $titulo = str_replace($vetor_minuscula, $vetor_maiuscula, $titulo);
        return $titulo; 
    }
        
    
    
    // gera a página no formato paisagem ou retrato.
    //Parâmetros de entrada: url padrão do sistema, formato ("P" => portrait/retrato, "L" => landscape/paisagem)
    function paint_gerar_pagina($url,$formato){
        
        $retorno= "<page  backtop='25mm' backbottom='30mm' backleft='10mm' backright='10mm' style='font-size: 12pt' orientation='$formato'>
                    <page_header>
                            <table class='page_header'>
                                    <tr>
                                            <td style='width:100%; text-align: center'>
                                                    <img src=\"".$url."/images/logo.jpg\">
                                            </td>
                                    </tr>
                            </table>
                    </page_header>
                    <page_footer>
                            <table class='page_footer'>
                                    <tr>
                                            <td style='width: 100%; text-align: center'>
                                                <i>Plano Anual de Atividades da Auditoria Interna - PAINT - exercício ".
                                                    $_GET['exercicio'] . "</i> <br>
                                                    <span class='page'>[[page_cu]]</span>
                                            </td>
                                    </tr>
                            </table>
                    </page_footer>";
        return $retorno; 
    }

    
}
