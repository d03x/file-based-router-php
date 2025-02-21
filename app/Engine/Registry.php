<?php
namespace SkeepTalk\Platform\Engine;
class Registry
{
    public static $instance = null;
    public $registry = [];

    public static function instance(){
        if(self::$instance===null){
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function set(string $key, mixed $value)
    {
        $this->registry[$key]  = $value;
    }
    public function get($key)
    {
        return $this->registry[$key] ?? null;
    }
    public function __get($name)
    {
        return $this->get($name);
    }
    public function __set($name, $value)
    {
        $this->set($name,$value);
    }
}
