<?php
/**
 * CTreeGridView class file.
 *
 * Used:
 * YiiExt - http://code.google.com/p/yiiext/
 * treeTable - http://plugins.jquery.com/project/treeTable
 * jQuery ui - http://jqueryui.com/
 *
 * @author quantum13
 * @link http://quantum13.ru/
 */


Yii::import('zii.widgets.grid.CGridView');


class CQTreeGridView extends CGridView {
/**
 * @var string the base script URL for all treeTable view resources (e.g. javascript, CSS file, images).
 * Defaults to null, meaning using the integrated grid view resources (which are published as assets).
 */
    public $baseTreeTableUrl;

    /**
     * @var string the base script URL for jQuery ui draggable and droppable.
     * Defaults to null, meaning using the integrated grid view resources (which are published as assets).
     */
    public $baseJuiUrl;


    /**
     * Initializes the tree grid view.
     */
    public function init() {
        parent::init();
        if($this->baseTreeTableUrl===null)
            $this->baseTreeTableUrl=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.common.extensions.QTreeGridView.treeTable'));


    }

    /**
     * Registers necessary client scripts.
     */
    public function registerClientScript() {
        parent::registerClientScript();

        $cs=Yii::app()->getClientScript();
        $cs->registerScriptFile($this->baseTreeTableUrl.'/javascripts/jquery.treeTable.js',CClientScript::POS_END);
        $cs->registerCssFile($this->baseTreeTableUrl.'/stylesheets/jquery.treeTable.css');

        $cs->registerScript('treeTable', '
            $(document).ready(function()  {
              $("#'.$this->getId().' .items").treeTable();
               
    
    function checkChildNodes($node, checked) {
        $(".items .child-of-" + $node.attr("id")).each(function() {
            var $checkbox = $(this).find("input:checkbox");
            if (checked)
                $checkbox.attr("checked", "checked");
            else
                $checkbox.removeAttr("checked");
        
            checkChildNodes($(this), checked);
        });
    }   
            });
            
            ');

    }

    /**
     * Renders the data items for the grid view.
     */
    public function renderItems() {

        if(Yii::app()->user->hasFlash('CQTeeGridView')) {
            print '<div style="background-color:#ffeeee;padding:7px;border:2px solid #cc0000;">'. Yii::app()->user->getFlash("CQTeeGridView") . '</div>';
        }
        parent::renderItems();
    }


    /**
     * Renders the table body.
     */
    public function renderTableBody() {
        $data=$this->dataProvider->getData();

        $n=count($data);

        echo "<tbody>\n";
        //print_r($this->dataProvider->data); exit;
        if($n>0) {
            for($row=0;$row<$n;++$row)
                $this->renderTableRow($row);
        }
        else {
            echo '<tr><td colspan="'.count($this->columns).'">';
            $this->renderEmptyText();
            echo "</td></tr>\n";
        }
        echo "</tbody>\n";
       // exit;
    }

    public function renderTableRow($row) {
        $model=$this->dataProvider->data[$row];
        $parentClass = $model->id_pai
            ?'child-of-'.$model->id_pai.' '
            :'';
        if($row==0){
            $parentClass = '';
        }
        
        echo '<tr style="display:none;" class="before" id="before-'.$model->getPrimaryKey().'"><td style="padding:0;"><div style="height:3px;"></div></td></tr>';
    
        if($this->rowCssClassExpression!==null) {
            
//             echo '<tr ultimo_filho="'.$model->cod_ultimo_filho.'" nivel="'.$model->nivel.'" ultimo="'.$model->cod_ultimo.'" codigo="'.$model->codigo.'" id="'.$model->getPrimaryKey().'" class="'.$parentClass.$this->evaluateExpression($this->rowCssClassExpression,array('row'=>$row,'data'=>$model)).'">';
            echo '<tr ultimo_filho="'.$model->cod_ultimo_filho.'" ultimo_neto="'.$model->cod_ultimo_neto.'" ultimo_bisneto="'.$model->cod_ultimo_bisneto.'" nivel="'.$model->nivel.'" ultimo="'.$model->cod_ultimo.'" codigo="'.$model->codigo.'" id="'.$model->getPrimaryKey().'" class="'.$parentClass.$this->evaluateExpression($this->rowCssClassExpression,array('row'=>$row,'data'=>$model)).'">';
        }
        elseif(is_array($this->rowCssClass) && ($n=count($this->rowCssClass))>0) {
            echo '<tr ultimo_filho="'.$model->cod_ultimo_filho.'" ultimo_neto="'.$model->cod_ultimo_neto.'" ultimo_bisneto="'.$model->cod_ultimo_bisneto.'" nivel="'.$model->nivel.'"  ultimo="'.$model->cod_ultimo.'" codigo="'.$model->codigo.'" id="'.$model->getPrimaryKey().'" class="'.$parentClass.$this->rowCssClass[$row%$n].'">';
        }else {
            
            echo '<tr ultimo_filho="'.$model->cod_ultimo_filho.'" ultimo_neto="'.$model->cod_ultimo_neto.'" ultimo_bisneto="'.$model->cod_ultimo_bisneto.'" nivel="'.$model->nivel.'" ultimo="'.$model->cod_ultimo.'" codigo="'.$model->codigo.'" id="'.$model->getPrimaryKey().'" class="'.$parentClass.'">';
        }
        foreach($this->columns as $column) {
            $column->renderDataCell($row);
        }

        echo "</tr>\n";
        echo '<tr style="display:none;" class="after" id="after-'.$model->getPrimaryKey().'"><td style="padding:0;"><div style="height:3px;"></div></td></tr>';

    }
}

