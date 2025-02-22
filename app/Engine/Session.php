<?php

namespace SkeepTalk\Platform\Engine;

class Session
{
    public $sesion_name = "session_data";

    public function alerdyStart()
    {
        return session_id();
    }
    private function settingSession()
    {
        ini_set('session.hash_function', 'sha256'); // Ganti algoritma hash session ID
        ini_set('session.hash_bits_per_character', 6); // Gunakan lebih banyak karakter unik
        session_name("skeeptalk");
        session_id(hash('sha256', uniqid(mt_rand(), true)));
        session_set_cookie_params([
            'secure' => true,
        ]);
    }
    public function start()
    {
        $this->settingSession();
            session_start();
            session_regenerate_id(true);

      
    }
}
