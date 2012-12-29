<?php
/****** the interface defined all method of class SysStatus*****/


interface _sys_info
{
    public function cpu();

    public function memory();

    public function disk();

    public function network();

    public function os();

}
