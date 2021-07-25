<?php 

class Connexion extends PDO {
    public function __construct() {

        parent::__construct("mysql:dbname=emploi;host=localhost;charset=utf8", "root", "");

            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);   
    }
}
