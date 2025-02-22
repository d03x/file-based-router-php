<?php

namespace SkeepTalk\Platform\Engine;

use Exception;

class View
{
    public $layout = null;
    public $sections = [];
    public $extensions = [
        '.view.php',
        '.php',
        '.blade.php',
        '.tpl.php'
    ];
    public $data = [];
    public $sectionStack = [];
    public function startSection(string $name)
    {
        $this->sectionStack[] = $name;
        ob_start();
    }

    public function find($view)
    {
        $view = str_replace('.', DS, $view);

        foreach ($this->extensions as $file) {
            $fullpath = $this->getViewPath() . DS . $view . $file;
            if (file_exists($fullpath)) {
                return $fullpath;
            }
        }
        throw new Exception("File {$view} Not found");
    }

    public function endSection()
    {
        $content = ob_get_clean();

        $section = array_pop($this->sectionStack);
        if (!array_key_exists($section, $this->sections)) {
            $this->sections[$section] = [];
        }
        $this->sections[$section][] = $content;
    }

    public function renderSection(string $name)
    {
        if (!isset($this->sections[$name])) {
            throw new Exception("$name not found in stack");
        }

        $output =  '';
        foreach ($this->sections[$name] as $key => $value) {
            if (!empty($value)) {
                $output .= "<!-- begin:section-$name-$key -->\n" . $value . "<!-- end:section-$name-$key -->\n";
            }
        }
        return $output;
    }
    public function getViewPath()
    {
        return ROOT_DIR . DS . "resources" . DS . "views";
    }
    public function extend(string $filename)
    {
        $this->layout = $filename;
    }
    /**
     * @param $view
     */
    public function render(string $view, $data = [])
    {
        $this->data = array_merge($this->data, $data);
        $filename = $this->find($view);
        $output = (function () use ($filename) {
            extract($this->data);
            ob_start();
            include $filename;
            return ob_get_clean();
        })();

        if ($this->layout && $this->sectionStack === []) {
            $layoutview = $this->layout;
            $this->layout = null;
            $output = $this->render($layoutview, $data);
        }
        $this->data = null;
        return $output;
    }
}
