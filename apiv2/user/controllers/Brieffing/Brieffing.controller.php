<?php

require_once MODELS . '/Brieffing/Brieffing.class.php';


class BrieffingController extends  Brieffing {

    public function __construct() {

        $this->model = New  Brieffing();
    }
   
    public function iniciar() {
        $this->model->iniciar($this->model);
    }     
    public function delete() {
        $this->model->delete($this->model);
    }  
    
    
}
