
<?php

// unset array[x] - brise element polja

class MyButtonColumnSemAjax extends CButtonColumn {

    /**
     * Initializes the column.
     * This method registers necessary client script for the button column.
     * @param CGridView the grid view instance
     */
    public function init() {
       $this->afterDelete = 'function(link,success,data){ if(success) $("#mensagens").html(data); }';

        parent::init();

        $this->grid->ajaxUpdate = true;
        $this->grid->itemsCssClass = 'tabelaListagemItensYii';
        if (!Yii::app()->user->verificaPermissao($this->grid->id, "admin"))
            $this->buttons['update']['visible'] = false;

        if (!Yii::app()->user->verificaPermissao($this->grid->id, "delete"))
            $this->buttons['delete']['visible'] = false;

        $this->buttons['view']['imageUrl'] = Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . '/img/lupa.png';
        $this->buttons['update']['imageUrl'] = Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . '/img/alterar.gif';
        $this->buttons['delete']['imageUrl'] = Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . '/img/excluir.gif';

    }

    protected function renderButton($id, $button, $row, $data) {

        if (!isset($button['visible']) || $button['visible'] === null) {
            parent::renderButton($id, $button, $row, $data);
        } else {
            if (is_string($button['visible']))
                $button['visible'] = $this->evaluateExpression($button['visible'], array('data' => $data, 'row' => $row));

            if ($button['visible'])
                parent::renderButton($id, $button, $row, $data);
        }
    }

}
