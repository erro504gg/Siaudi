<?php
require_once(dirname(__FILE__).'/SNumericSpinner.php');

/**
 * This creates a time sspinner field
 * @author : Spyros Soldatos
 * @link :
 * @package : spinner
 * @since : 1.3
 */
class STimeSpinner extends SNumericSpinner {
  public $data = array();
  private $_step = 30;
  private $_min = "00:00";
  private $_max = "23:59";



  /**
   * Sets the step of the spinner.Should be an integer or a float
   * @param numeric $step
   */
  public function setStep($step) {
    if(is_numeric($step)) {
      $this->_step = $step;
    } else {
      throw new CException("Step should be an integer");
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
   * Sets the minimum value of the spinner
   * @param numeric $min
   */
  public function setMin($min) {
    $minimums = split(":", $min);
    if(count($minimums) != 2) {
      throw new CException("Minimum value should be in the form of 'HH:MM'");
    }
    if (!is_numeric($minimums[0]) || !is_numeric($minimums[1]) ||
      $minimums[0] < 0 || $minimums[0] > 23 || $minimums[1] < 0 ||
      $minimums[1] > 59 ) {
      throw new CException("Hours value should be in the range of 0 - 23 and
                            minutes value should be in the range of 0 - 59.");
    }

    $this->_min = $min;

  }

  /**
   * Gets the minimum value of the spinner
   * @return numeric
   */
  public function getMin() {
    return $this->_min;
  }

  /**
   * Sets the minimum value of the spinner
   * @param numeric $min
   */
  public function setMax($max) {
    $maximums = split(":", $max);
    if(count($maximums) != 2) {
      throw new CException("Maximum value should be in the form of 'HH:MM'");
    }
    if (!is_numeric($maximums[0]) || !is_numeric($maximums[1]) ||
      $maximums[0] < 0 || $maximums[0] > 23 || $maximums[1] < 0 ||
      $maximums[1] > 59 ) {
      throw new CException("Hours value should be in the range of 0 - 23 and
                            minutes value should be in the range of 0 - 59.");
    }

    $this->_max = $max;

  }

  /**
   * Gets the minimum value of the spinner
   * @return numeric
   */
  public function getMax() {
    return $this->_max;
  }

  /**
   * Gets the original value of the spinner
   * @return mixed
   */
  public function getValue() {
    if($this->value == "") {
      return "00:00";
    }
    $value = split(":", $this->value);
    if(count($value) != 2) {
      throw new CException("Value should be in the form of 'HH:MM'");
    }
    if (!is_numeric($value[0]) || !is_numeric($value[1]) ||
      $value[0] < 0 || $value[0] > 23 || $value[1] < 0 ||
      $value[1] > 59 ) {
      throw new CException("Hours value should be in the range of 0 - 23 and
                            minutes value should be in the range of 0 - 59.");
    }
    $h = strlen($value[0]) == 2 ? $value[0] : "0".$value[0];
    $m = strlen($value[1]) == 2 ? $value[1] : "0".$value[1];
    $minimums = split(":",$this->getMin());
    $maximums = split(":",$this->getMax());
    if (($h*60+$m < $minimums[0]*60 + $minimums[1]) ||
      (($h*60+$m > $maximums[0]*60 + $maximums[1]))) {
      return $this->getMin();
    }
    return $h.":".$m;
  }

  /**
   * Execute the widget
   */
  public function run() {
    $this->_type = "time";
    $this->parseData();
    $this->registerClientScripts();
    $this->createMarkup();
  }

  /**
   * Parses the data of the spinner
   */
  public function parseData() {

    extract($this->data);
    $this->setStep(isset ($step) ? $step : $this->_step);
    $this->setMin(isset ($min) ? $min : $this->_min);
    $this->setMax(isset ($max) ? $max : $this->_max);
    $this->value = isset($value) ? $value : "00:00";


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
                ,'<?php echo $this->getMax()?>','<?php echo $this->getStep()?>',
              <?php echo "0"?>,<?php echo $this->getDelay()?>,
                '<?php echo $this->getAcceleration()?>');"
          onmouseup="setMouseUp();" onmouseout="setMouseUp();">
      </td>
    </tr>
    <tr>
      <td class="button_down" style="background-color:<?php echo $this->bgColor?>"
          onmousedown="javascript:setMouseDown('down','<?php echo $id?>','<?php echo $this->_type?>',
              <?php echo $id."_list"?>,
                '<?php echo $this->getMin()?>','<?php echo $this->getStep()?>',
              <?php echo "0"?>,<?php echo $this->getDelay()?>,
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
