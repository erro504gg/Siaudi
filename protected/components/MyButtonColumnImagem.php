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

// unset array[x] - brise element polja

class MyButtonColumnImagem extends CButtonColumn {

    /**
     * Initializes the column.
     * This method registers necessary client script for the button column.
     * @param CGridView the grid view instance
     */
    public function init() {
        $this->afterDelete = 'function(link,success,data){ if(success) $("#mensagens").html(data); $(".sucesso").animate({opacity: 1.0}, 3000).fadeOut("slow"); }';
        
        $this->buttons['delete']['click'] = 'function( e ){
                                  e.preventDefault();
                                  viewDelete( $(this).attr("href") , $(this)  );
                              }';
        
        $this->buttons['view']['click'] = 'function( e ){
                                  e.preventDefault();
                                  carregaView( $(this).attr("href"));
                              }';

        parent::init();

        $this->grid->ajaxUpdate = true;
        $this->grid->itemsCssClass = 'tabelaListagemItensYii';

        if (!Yii::app()->user->verificaPermissao($this->grid->id, "admin"))
            $this->buttons['update']['visible'] = false;

        if (!Yii::app()->user->verificaPermissao($this->grid->id, "delete"))
            $this->buttons['delete']['visible'] = false;

//        $this->buttons['view']['imageUrl'] = Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . '/img/lupa.png';
//        $this->buttons['update']['imageUrl'] = Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . '/img/alterar.gif';
//        $this->buttons['delete']['imageUrl'] = Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . '/img/excluir.gif';
      //  $this->buttons['delete']['url'] = '$data->primaryKey'; 
    }

    protected function renderButton($id, $button, $row, $data) {

        if (!isset($button['visible']) || $button['visible'] === null) {
            parent::renderButton($id, $button, $row, $data);
        } else {
            if (is_string($button['visible']))
                $button['visible'] = $this->evaluateExpression("'" . $button['visible'] . "'", array('data' => $data, 'row' => $row));
        }
        if ($button['visible']) {
            parent::renderButton($id, $button, $row, $data);
        }
    }

}
