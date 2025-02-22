<?php

namespace SkeepTalk\Platform\Engine;

use Exception;

class Template
{
    public $layout = null;
    public $sections = [];
    public $data = [];
    public $sectionStack = [];
    public function startSection(string $name)
    {
        $this->sectionStack[] = $name;
        ob_start();
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

    public function compileFileNameAnotation(string $string)
    {
        $path_with_anotaion = "";
        foreach (array_filter(explode('.', $string)) as $key => $value) {
            $path_with_anotaion .= DS . $value;
        }
        return $path_with_anotaion;
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
    public function extend(string $filename)
    {
        $this->layout = $this->compileFileNameAnotation($filename);
    }
    /**
     * @param $view
     */
    public function render(string $view, $data = [])
    {
        $start = microtime(true);
        $this->data = array_merge($this->data, $data);
        $view = $this->compileFileNameAnotation($view);


        $filename = ROOT_DIR . DS . "resources" . DS . "views" . DS . $view . ".view.php";
        if (file_exists($filename)) {
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
        } else {
            throw new Exception($filename . " not found");
        }
        $this->data = null;
        $executeTime =  (microtime(true) - $start);
        $output = "<!--- $executeTime --->" . $output;
        return $output;
    }
}
