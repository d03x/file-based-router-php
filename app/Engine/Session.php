<?php

namespace SkeepTalk\Platform\Engine;

class Session
{
    public $sesion_name = "session_data";

    public function alerdyStart()
    {
        return session_id() && !session_status(PHP_SESSION_NONE);
    }
    private function settingSession()
    {
        session_set_cookie_params([
            'secure' => true,
        ]);
    }
    public function start()
    {
        $this->settingSession();
        if (!$this->alerdyStart()) {
            session_start();
        }
    }
}
