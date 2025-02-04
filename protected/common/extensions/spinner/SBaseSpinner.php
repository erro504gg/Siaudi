<?php
/*
 * SbaseSpinner is the base class for creating spinners
 * Spinners data types can be Numbers(integers or floats) , Strings or time
 * For the integer, float and time type min , max and step values must be provided or
 * the defaults will be used. If one of the min, max or step values is a float
 * this will be the type of the spinner, else it will be of an integer type.
 * For the time spinner min, max and value must be in the form of HH:MM
 * For the string type an array with the strings must be provided.
 *
 * Usage is :
 * $this->widget('application.extensions.spinner.SXXXSpinner',array($params));
 * XXX = Numeric, Text, Time
 *
 * $parameters may be:
 * (For a DB model):
 * model : the model of the input textfield
 * attribute : the model's attribute
 *
 * (For a form model):
 * name : The name of the textfield
 *
 * width : The width of the widget in pixels (default 22px)
 * height : The height of the widget in pixels (default 30px)
 * bgColor : The bgColor of the spinner's buttons (default white)
 * data : an array with the spinners data.
 * data array's elements might be:
 * list : an array with the spinner's list of values (only for a string type spinner)
 * value : The selected value (if value is not set the first element of the list
 * is used for a string type spinner.For a numeric spinner the minimum value is used.
 * Also if the value is not in the range of min-max, the minimum value is used too.
 * min : The minimum value of the spinner (numeric type : default 0, time type : default 00:00)
 * max : The minimum value of the spinner (numeric type : default 20, time type : default 23:30)
 * step : The step value of the spinner (numeric type : default 1, time type : default 30 minutes )
 * precision : The number of decimal digits (default 0)
 * text : A text to be displayed next to the spinner
 * delay : The delay of the spinner's values update in ms when the mouse is kept
 * pressed on up/down button (Default is 100).Small delays means faster spinner speed
 * acceleration : Spinner's speed acceleration when the mouse is kept pressed on
 * up/down button.Valid values are none, slow, normal, fast. Default is none
 * 
 * @author : Spyros Soldatos
 * @link :
 * @package : spinner
 * @since : 1.3
 */
abstract class SBaseSpinner extends CInputWidget {
  protected $_type = "";
  protected $_list;
  protected $_width = 30;
  protected $_height = 22;
  public $bgColor = "white";
  public $text;
  protected $_delay =100;
  protected $_acceleration = "none";
  protected $_accelerations = array("none","slow","normal","fast");
  public $data = array();

  /**
   * Sets the delay of the spinner value change when mouse is kept pressed
   * @param integer $precision
   */
  public function setDelay($delay) {
    if(is_integer($delay)) {
      $this->_delay = $delay;
    } else {
      throw new CException("Delay should be an integer");
    }
  }


  /**
   * Gets the speed acceleration of the spinner
   */
  public function getAcceleration() {
    switch ($this->_acceleration) {
      case "none":
        return 0;
        break;
      case "slow":
        return 2;
        break;
      case "normal":
        return 3;
        break;
      case "fast":
        return 4;
        break;
      default:
        return 0;
        break;
    }
  }

  /**
   * Sets the speed acceleration of the spinner
   * @param boolean $accelerate
   */
  public function setAcceleration($acceleration) {
    if (array_search($acceleration,$this->_accelerations)) {
      $this->_acceleration = $acceleration;
    } else {
      $this->_acceleration = "none";
    }
  }

  /**
   * Gets the delay of the spinner value change when mouse is kept pressed
   * @return integer
   */
  public function getDelay() {
    return $this->_delay;
  }

  /**
   * Sets the width of the widget in pixels
   * @param integer $width
   */
  public function setWidth($width) {
    if(is_integer($width)) {
      $this->_width = $width;
    } else {
      throw new CException("Width of the widget should be an integer");
    }
  }

  /**
   * Gets the width of the widget
   * @return integer
   */
  public function getWidth() {
    return $this->_width;
  }

  /**
   * Sets the height of the widget in pixels
   * @param integer $width
   */
  public function setHeight($height) {
    if(is_integer($height)) {
      $this->_height = $height;
    } else {
      throw new CException("Width of the widget should be an integer");
    }
  }

  /**
   * Gets the height of the widget
   * @return integer
   */
  public function getHeight() {
    return $this->_height;
  }

  /**
   * Registers the clientside widget files (css & js)
   */
  public function registerClientScripts() {
  //Yii::app()->clientScript->registerCoreScript('jquery');
  // Get the resources path
    $resources = dirname(__FILE__).DIRECTORY_SEPARATOR.'resources';

    // publish the files
    $baseUrl = Yii::app()->assetManager->publish($resources);
    Yii::app()->assetManager->publish($resources.'/spinner.js');

    // register the files
    Yii::app()->clientScript->registerScriptFile($baseUrl.'/spinner.js');


    // register the Stylesheet
    Yii::app()->clientScript->registerCssFile($baseUrl.'/spinner.css');

  }

  public abstract function createMarkup();




}
?>
