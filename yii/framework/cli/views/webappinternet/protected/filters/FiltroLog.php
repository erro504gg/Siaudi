<?php


class FiltroLog extends CFilter {

    protected function preFilter($filterChain) {
        $idUser = Yii::app()->user->id;
        $ip = $this->getRealIpAddr();

        $sql = "SELECT auditoria.fc_log_on('$idUser','$ip',3)";

        $command = Yii::app()->db->createCommand($sql)->queryRow();
        return true;
    }

    protected function postFilter($filterChain) {
        // logic being applied after the action is executed
    }

    function getRealIpAddr() {

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ipString = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $addr = explode(",", $ipString);
            $ip = trim($addr[0]);
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}
