<?php

class home extends controller
{
   public function index()
   {
      $this->vista->obtenerVista($this, "index");
   }
}
