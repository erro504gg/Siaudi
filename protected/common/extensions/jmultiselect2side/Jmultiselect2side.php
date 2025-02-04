<?php

class Jmultiselect2side extends CWidget{

    /**
     * Extension for jmultiselect2side
     * Options:
     * Parameters:
     * selectedPosition - position of selected elements - default 'right'
     * moveOptions - show move options - default true
     * labelTop - label of top buttom - default 'Top'
     * labelBottom - label of bottom buttom - default 'Bottom'
     * labelUp - label of up buttom - default 'Up'
     * labelDown - label of down buttom - default 'Down'
     * labelSort - label of sort buttom - default 'Sort'
     * maxSelected - number of max selectable options
     * labelsx: Left label - default '* Selected *'
     * labeldx: Right label - default '* Available *'
     * autoSort: Automatic sort of selected options - default true
     * search: Search/Filter
     */

        /**
         * position of selected elements - default 'right'
         * @var string
         */
        public $selectedPosition;

        /**
         * show move options - default true
         * @var boolean
         */
        public $moveOptions;

        /**
         * label of top buttom - default 'Top'
         * @var string
         */
        public $labelTop;

        /**
         * label of bottom buttom - default 'Bottom'
         * @var string
         */
        public $labelBottom;

        /**
         * label of up buttom - default 'Up'
         * @var string
         */
        public $labelUp;

        /**
         * label of down buttom - default 'Down'
         * @var string
         */
        public $labelDown;

        /**
         * label of sort buttom - default 'Sort'
         * @var string
         */
        public $labelSort;

        /**
         * number of max selectable options
         * @var int
         */
        public $maxSelected;

        /**
         * Left label - default '* Selected *'
         * @var string
         */
        public $labelsx;

        /**
         * label of sort buttom - default 'Sort'
         * @var string
         */
        public $labeldx;

        /**
         * Automatic sort of selected options - default true
         * @var boolean
         */
        public $autoSort;

        /**
         * name for the selected select
         * @var string
         */
        public $name;

        /**
         * data for generating the first list options .suport  (value=>display) and string.
         * require
         * @var array
         */
        public $list;


        /**
         * DHL - add unique count so that multiple instances can exist on one form
         * @staticvar unique identifier
         */
        public static $idCount = 0;

        /**
         * DHL - automatically format the name properly for the model and attribute
         * @var CModel
         */
        public $model;

        /**
         * DHL -automatically format the name properly for the model and attribute
         * @var string The attribute of the model in question
         */
        public $attribute;

       /**
         * search capability
         * @var string
         */
        public $search;

        //***************************************************************************
        // register clientside widget files
        //***************************************************************************
        protected function registerClientScript(){
                $basePath = dirname(__FILE__).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR;
                $baseUrl = Yii::app()->getAssetManager()->publish ($basePath,false,1,YII_DEBUG);

                $cs=Yii::app()->clientScript;
                $cs->registerCoreScript('jquery');
                $cs->registerScriptFile($baseUrl.'/jquery.multiselect2side.js');
                $cs->registerCssFile($baseUrl.'/jquery.multiselect2side.css');
        }

        //***************************************************************************
        // Initializes the widget
        //***************************************************************************
        public function init(){
                if(!isset($this->name)){
                    if (!isset($this->model) && !isset($this->attribute))
                        throw new CHttpException(500,'"name" have to be set!');
                    else
                        $this->name = get_class($this->model)."[".$this->attribute."][]";
                }
                if(!isset($this->list)){
                  throw new CHttpException(500,'"list" have to be set!');
                }
                parent::init();
        }

        //***************************************************************************
        // Run the widget
        //***************************************************************************
        public function run(){
            $id = "seleccion".self::$idCount++;

                if ( $this->model !== null )
                    echo CHtml::activeListBox($this->model, $this->attribute,
                          $this->list,
                          array('multiple'=>true, 'id'=>$id));
                else
                    echo CHtml::listBox($this->name, null, $this->list, array('multiple'=>true, 'id'=>$id));

        $this->registerClientScript();
        $str = " $('#{$id}').multiselect2side({".PHP_EOL;

        if (isset($this->moveOptions) && ! $this->moveOptions){
             $str.= "moveOptions: false,".PHP_EOL;
        }
       foreach(array('selectedPosition','labelTop','labelBottom','labelUp','labelDown','labelSort','maxSelected','labelsx','labeldx','autoSort','search') as $p)
       {
            if(isset($this->$p)) $str.="{$p}:'{$this->$p}',".PHP_EOL;
       }

       $str.= "});".PHP_EOL;

                Yii::app()->clientScript->registerScript('multiselect'.$id, $str, CClientScript::POS_READY);
        }

        protected function renderContent(){
        }

}

?>
