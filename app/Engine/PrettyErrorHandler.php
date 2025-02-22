<?php

namespace SkeepTalk\Platform\Engine;

class PrettyErrorHandler
{
    public static function run()
    {
        $run = new \Whoops\Run();
        $handler = new \Whoops\Handler\PrettyPageHandler;
        $handler->setPageTitle("Ada masalah");
        $handler->addDataTable('Skeeptak Info',[
            'url' => "Logue"
        ]);
        $handler->setEditor('vscode');
        $run->pushHandler($handler);

        if (\Whoops\Util\Misc::isAjaxRequest()) {
            $run->pushHandler(new \Whoops\Handler\JsonResponseHandler);
        }
        $run->register();
    }
}
