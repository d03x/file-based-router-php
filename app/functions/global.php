<?php
function view($view, $data)
{
    echo $GLOBALS['registry']->template->render($view, $data);
}
