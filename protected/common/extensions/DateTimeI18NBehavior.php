<?php

/*
 * DateTimeI18NBehavior
 * Automatically converts date and datetime fields to I18N format
 * 
 * Author: Ricardo Grana <rickgrana@yahoo.com.br>, <ricardo.grana@pmm.am.gov.br>
 * Version: 1.1
 * Requires: Yii 1.0.9 version 
 */

class DateTimeI18NBehavior extends CActiveRecordBehavior {

    public $dateOutcomeFormat = 'Y-m-d';
    public $dateTimeOutcomeFormat = 'Y-m-d H:i:s';
    public $dateIncomeFormat = 'yyyy-MM-dd';
    public $dateTimeIncomeFormat = 'yyyy-MM-dd hh:mm:ss';

    public function beforeSave($event) {

        //search for date/datetime columns. Convert it to pure PHP date format
        foreach ($event->sender->tableSchema->columns as $columnName => $column) {

            $strDate = strpos($column->dbType, 'date');
            $strTime = strpos($column->dbType, 'time');
            
            if (($strDate === false) and ($strTime === false))
                continue;

            if (!strlen($event->sender->$columnName)) {
                $event->sender->$columnName = null;
                continue;
            }
            $flag = (strpos($column->dbType, '/') === false);
           
            if ($strDate !== false  && $flag) {
                /*$event->sender->$columnName = date($this->dateOutcomeFormat, CDateTimeParser::parse(
                        $event->sender->$columnName, Yii::app()->locale->dateFormat)
                        );*/
                
                $event->sender->$columnName = CMask::addMaskBdDatePG($event->sender->$columnName);
                
            } elseif($strTime !== false  && $flag) {
                /*$event->sender->$columnName = date($this->dateTimeOutcomeFormat, CDateTimeParser::parse($event->sender->$columnName, strtr(Yii::app()->locale->dateTimeFormat, array("{0}" => Yii::app()->locale->timeFormat,
                                    "{1}" => Yii::app()->locale->dateFormat))));*/
                $event->sender->$columnName = CMask::addMaskBdDatePG($event->sender->$columnName, true);
            }
             
                 
        }

        return true;
    }

    public function afterFind($event) {

        foreach ($event->sender->tableSchema->columns as $columnName => $column) {

            $strDate = strpos($column->dbType, 'date');
            $strTime = strpos($column->dbType, 'time');
            
            if (($strDate === false) and ($strTime === false))
                continue;

            if (!strlen($event->sender->$columnName)) {
                $event->sender->$columnName = null;
                continue;
            }

            if ($strDate !== false) {
                /*$event->sender->$columnName = Yii::app()->dateFormatter->formatDateTime(
                        CDateTimeParser::parse($event->sender->$columnName, $this->dateIncomeFormat), 'medium', null);*/
                    $event->sender->$columnName = CMask::addMaskUserDate($event->sender->$columnName, false);
            } elseif($strTime !== false) {
                $event->sender->$columnName = CMask::addMaskUserDate($event->sender->$columnName);
                /*$event->sender->$columnName =
                        Yii::app()->dateFormatter->formatDateTime(
                        CDateTimeParser::parse($event->sender->$columnName, $this->dateTimeIncomeFormat), 'medium', 'medium');*/
            }
        }
        return true;
    }

}
