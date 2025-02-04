<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_id;

    public function getId() {
        return $this->_id;
    }

    public function authenticate() {
        return !$this->errorCode;
    }

}
