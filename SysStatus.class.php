<?php

class SysStatus implements  _sys_info {
    public function __construct(){
        $_os = getenv('os');
        if($_os){
            $this->sysInstance = new _win_sys_info();

        }else{
               $this->sysInstance = new _linux_sys_info();
        }
        //var_dump($this->sysInstance->cpu);

    }

    public function __call($funcName,$args){
        if(!method_exists($this->sysInstance,$funcName)){
            return false;
        }
        $this->sysInstance->$funcName();
    }
    public function __get($var){
        if(!$this->$var){
               if(method_exists($this->sysInstance,$var)){
                   $this->sysInstance->$var();

               }else{
                   //return false;

               }
        }
        return $this->sysInstance->$var;
    }

    public function cpu(){
        $this->sysInstance->cpu();

    }

    public function memory(){
        $this->sysInstance->memory();
    }

    public function disk(){
        $this->sysInstance->disk();
    }

    public function network(){
        $this->sysInstance->network();
    }

    public function os(){
        $this->sysInstance->os();
    }

    protected $sysInstance;
    protected $os;
    protected $cpu;
    protected $disk;
    protected $network;
    protected $memory;
}
