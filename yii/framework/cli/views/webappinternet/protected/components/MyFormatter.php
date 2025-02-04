<?php

class MyFormatter extends CFormatter {

    public function formatBoolean($value) {
        return $value ? Yii::t('app', 'Sim') : Yii::t('app', 'Não');
    }

    public function formatAcao($value) {
        switch ($value) {
            case 0:
                return 'Recebido';
                break;
            case 1:
                return 'Recusado';
                break;
            case 2:
                return 'Enviado';
                break;
            default:
                break;
        }
    }

    public function formatCnpj($value) {
        return CMask::getFormataCNPJ($value);
    }
	public function converteData($data){
		return (preg_match('/\//',$data)) ? implode('-', array_reverse(explode('/', $data))) : implode('/', array_reverse(explode('-', $data)));
	}

}
