<?php
require_once(dirname(__FILE__).'/SBaseSpinner.php');
/**
 * This creates a string sspinner input field
 * @author : Spyros Soldatos
 * @link :
 * @package : spinner
 * @since : 1.0
 */
class STextSpinner extends SBaseSpinner {


  /**
   * Gets the original value of the spinner
   * @return mixed
   */
  public function getValue() {
    if(is_numeric($this->value)) {
      if ($this->value < sizeof($this->_list) && $this->value > -1) {
        return $this->_list[$this->value];
      } else {
        return $this->_list[0] ;
      }
    } else if($this->value != "") {
      return $this->_list[array_search($this->value, $this->_list)];
    } else {
      return  $this->_list[0];
    }
  }

  /**
   * Parses the data of the spinner
   */
  public function parseData() {
    extract($this->data);
    $this->_list = $list;
    $this->value = $value;


    $ex = "";
    if($this->_type == "") {
      $ex.= "SSpinner type is not set.\n";
    }
    if(($this->_type == "array") && (sizeof($this->data)==0)) {
      $ex.= "SSpinner data is not set.\n";
    }
    if($ex != "") {
      throw new CException($ex);
    }
  }

  /**
   * Execute the widget
   */
  public function run() {
    $this->_type = "string";
    $this->parseData();
    $this->registerClientScripts();
    $this->createMarkup();
  }


  /**
   * Creates markup required for the current mode.
   */
  public function createMarkup() {
    list($name,$id)=$this->resolveNameID();

    $this->htmlOptions["name"]= $name;
    $this->htmlOptions["id"]= $id;
     if(!$this->hasModel()) {
      $this->htmlOptions["value"]= $this->getValue();
    } else {
      $this->htmlOptions["value"]= $this->model->{$this->attribute} != "" ? $this->model->{$this->attribute} :
        $this->getValue();
    }
    $this->htmlOptions["style"]= "width:".($this->getWidth()-13)."px";
    $this->htmlOptions["class"]= "textfield";
    $this->htmlOptions["readonly"]= "true";
    $field = $this->hasModel()  ?
      Chtml::activeTextField($this->model,$this->attribute,$this->htmlOptions) :
      Chtml::TextField($this->name,$this->getValue(),$this->htmlOptions);
    ?>

<script>
  var <?php echo $id."_list"?> = new Array();
    <?php if (count($this->_list) > 0) { ?>
      <?php foreach ($this->_list as $i=>$v) { ?>
        <?php echo $id."_list"?>.push('<?php echo addslashes($v)?>');
        <?php }
    }?>
</script>
<div class='spinner' style="height:<?php echo $this->getHeight()?>px">
  <table cellspacing=0 cellpadding=0 align="left">
    <tr>
      <td rowspan='2'>
            <?php echo $field ?>
      </td>
      <td class="button_up" style="background-color:<?php echo $this->bgColor?>"
          onmousedown="javascript:setMouseDown('up','<?php echo $id?>','<?php echo $this->_type?>',
              <?php echo $id."_list"?>
                ,<?php echo "0"?>,<?php echo "0"?>,
              <?php echo "0"?>,<?php echo $this->getDelay()?>,
                '<?php echo $this->getAcceleration()?>');"
          onmouseup="setMouseUp();" onmouseout="setMouseUp();">
      </td>
    </tr>
    <tr>
      <td class="button_down" style="background-color:<?php echo $this->bgColor?>"
          onmousedown="javascript:setMouseDown('down','<?php echo $id?>','<?php echo $this->_type?>',
              <?php echo $id."_list"?>,
              <?php echo "0"?>,<?php echo "0"?>,
              <?php echo "0"?>,<?php echo $this->getDelay()?>,
                '<?php echo $this->getAcceleration()?>')"
          onmouseup="setMouseUp();" onmouseout="setMouseUp();">
      </td>
    </tr>


  </table>&nbsp;
  <span class="text"><?php echo  $this->text?></span>
</div>
    <?
  }

}
?>
