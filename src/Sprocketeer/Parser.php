<?php

namespace Sprocketeer;

class Parser
{
    protected $paths;
    protected $debug = true;

    public function __construct($paths)
    {
        $this->paths = $paths;
    }

    public function getJsFiles($manifest)
    {
        $absolute_path = $this->getAbsolutePath($manifest);
        // Get only the header, we don't want any requires after that
        preg_match(
            "/^(
                (\s*) (
                    \* .* |
                    \/\/ .* |
                    \# .*
                )
            )+/mx",
            file_get_contents($absolute_path),
            $header
        );

        if (!$header) {
            return array($absolute_path);
        }

        $lines = explode("\n", $header[0]);

        $files = array();
        $self_required = false;
        foreach ($lines as $line) {
            if (!preg_match("/^\W*=\s*(\w+)\s*(.*)$/", $line, $line_matches)) {
                continue;
            }
            $directive = $line_matches[1];
            $require_manifest = $line_matches[2];
            switch ($directive) {
                case 'require':
                    $files = array_merge($files, $this->getJsFiles($require_manifest));
                    break;
                case 'require_self':
                    $files[] = $absolute_path;
                    $self_required = true;
                    break;
            }
        }

        if (!$self_required) {
            $files[] = $absolute_path;
        }

        return $files;
    }

    public function getJsWebPaths($manifest, $prefix = '/assets')
    {
        $web_paths = array();
        foreach ($this->getJsFiles($manifest) as $absolute_path) {
            foreach ($this->paths as $path) {
                $web_paths[] = "{$prefix}" . $this->stripFromBeginning($path, $absolute_path);
            }
        }

        return $web_paths;
    }

    protected function stripFromBeginning($prefix, $str)
    {
        if (substr($str, 0, strlen($prefix)) == $prefix) {
            $str = substr($str, strlen($prefix));
        }

        return $str;
    }

    protected function getAbsolutePath($filename)
    {
        foreach ($this->paths as $path) {
            $files = glob("{$path}/{$filename}*");
            if (isset($files[0])) {
                return $files[0];
            }
        }
        // throw an exception?
    }
}
