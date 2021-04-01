<?php

require_once MODELS . '/Departamentos/Departamentos.class.php';


class DepartamentosController extends Departamentos {

    public function __construct() {

        $this->model = New Departamentos();
    }
   
    public function adicionar() {
        $this->model->save($this->model);
    }     
    public function listall() {
        $this->model->listAll($this->model);
    }   
    public function update() {
        $this->model->update($this->model);
    }     
    public function delete() {
        $this->model->delete($this->model);
    }     
   
}
