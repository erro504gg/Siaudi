<?php
require_once(dirname(__FILE__).'/SBaseSpinner.php');

/**
 * This creates a numeric sspinner field
 *
 * @author : Spyros Soldatos
 * @link :
 * @package : spinner
 * @since : 1.0
 */

class SNumericSpinner extends SBaseSpinner {
  private $_min = 0;
  private $_max = 20;
  private $_step = 1;
  private $_precision = 0;
 


  /**
   * Sets the step of the spinner.Should be an integer or a float
   * @param numeric $step
   */
  public function setStep($step) {
    if(is_numeric($step)) {
      $this->_step = $step;
    } else {
      throw new CException("Step should be an integer or float");
    }
  }
  /**
   * Gets the step of the spinner
   * @return number
   */
  public function getStep() {
    return $this->_step;
  }

  /**
   * Sets the precision for the float type spinner
   * @param integer $precision
   */
  public function setPrecision($precision) {
    if(is_integer($precision)) {
      $this->_precision = $precision;
    } else {
      throw new CException("Precision should be an integer");
    }
  }

  /**
   * Gets the precision of the float type spinner
   * @return integer
   */
  public function getPrecision() {
    return $this->_precision;
  }






  /**
   * Sets the maximum value of the spinner
   * @param numeric $max
   */
  public function setMax($max) {
    if(is_numeric($max)) {
      $this->_max = $max;
    } else {
      throw new CException("Maximum value should be an integer or float");
    }
  }

  /**
   * Gets the maximum value of the spinner
   * @return numeric
   */
  public function getMax() {
    return $this->_max;
  }

  /**
   * Sets the minimum value of the spinner
   * @param numeric $min
   */
  public function setMin($min) {
    if(is_numeric($min)) {
      $this->_min = $min;
    } else {
      throw new CException("Minimum value should be an integer or float");
    }
  }

  /**
   * Gets the minimum value of the spinner
   * @return numeric
   */
  public function getMin() {
    return $this->_min;
  }


  /**
   * Gets the original value of the spinner
   * @return mixed
   */
  public function getValue() {
    if($this->value == null || $this->value < $this->getMin() || $this->value > $this->getMax()) {
      return $this->getMin();
    } else {
      return $this->value;
    }
  }

  /**
   * Parses the data of the spinner
   */
  public function parseData() {

    extract($this->data);
    $this->setStep(isset ($step) ? $step : $this->_step);
    $this->setMin(isset ($min) ? $min : $this->_min);
    $this->setMax(isset ($max) ? $max : $this->_max);
    $this->setPrecision(isset ($precision) ? $precision : $this->_precision);
    $this->value = isset($value) ? $value : 0;


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
    $this->_type = "numeric";
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
      $this->htmlOptions["value"]= number_format($this->getValue(),$this->getPrecision());
    } else {
      $this->htmlOptions["value"]= $this->model->{$this->attribute} != "" ? $this->model->{$this->attribute} :
        number_format($this->getValue(),$this->getPrecision());
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
                ,<?php echo $this->getMax()?>,<?php echo $this->getStep()?>,
              <?php echo $this->getPrecision()?>,<?php echo $this->getDelay()?>,
                '<?php echo $this->getAcceleration()?>');"
          onmouseup="setMouseUp();" onmouseout="setMouseUp();">
      </td>
    </tr>
    <tr>
      <td class="button_down" style="background-color:<?php echo $this->bgColor?>"
          onmousedown="javascript:setMouseDown('down','<?php echo $id?>','<?php echo $this->_type?>',
              <?php echo $id."_list"?>,
              <?php echo $this->getMin()?>,<?php echo $this->getStep()?>,
              <?php echo $this->getPrecision()?>,<?php echo $this->getDelay()?>,
                '<?php echo $this->getAcceleration()?>')"
          onmouseup="setMouseUp();" onmouseout="setMouseUp();">
      </td>
    </tr>


  </table>&nbsp;
  <span class="text"><?php echo  $this->text?></span>
</div>
  <?php
  }



}
?>
