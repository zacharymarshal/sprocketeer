<?php

namespace Sprocketeer;

use Exception;

class Parser
{
    protected $paths;
    protected $debug = true;

    public function __construct($paths)
    {
        $this->paths = $paths;
    }

    public function getPathInfoFromManifest($manifest)
    {
        list($search_path_name, $filename) = explode('/', $manifest, 2);
        $path_info = $this->getPathInfo($search_path_name, $filename);
        $absolute_path = $path_info['absolute_path'];
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
            return array($path_info);
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
                    $sub_files = $this->getPathInfoFromManifest(
                        dirname($manifest) . '/' . $require_manifest
                    );
                    $files = array_merge($files, $sub_files);
                    break;
                case 'require_self':
                    $files[] = $path_info;
                    $self_required = true;
                    break;
            }
        }

        if (!$self_required) {
            $files[] = $path_info;
        }

        return $files;
    }

    public function getJsWebPaths($manifest, $prefix = '/assets')
    {
        $web_paths = array();
        foreach ($this->getPathInfoFromManifest($manifest) as $path_info) {
            $absolute_path = $path_info['absolute_path'];
            foreach ($this->paths as $path) {
                // Strip off the path if it is found in the absolute path
                if (substr($absolute_path, 0, strlen($path)) == $path) {
                    $absolute_path = substr($absolute_path, strlen($path));
                    $web_paths[] = $prefix . $absolute_path;
                }
            }
        }

        return $web_paths;
    }

    public function getPathInfo($search_path_name, $filename)
    {
        if (!isset($this->paths[$search_path_name])) {
            throw new Exception("Unknown search path name: '{$search_path_name}'.");
        }

        $search_path = $this->paths[$search_path_name];
        $full_path   = "{$search_path}/{$filename}";
        if (!file_exists($full_path)) {
            throw new Exception("File could not be found: {$full_path}");
        }

        $real_absolute_path  = realpath($full_path);
        $real_search_path    = realpath($search_path);
        $real_requested_path = ltrim(
            str_replace($real_search_path, '', $real_absolute_path),
            '/'
        );

        return array(
            'absolute_path'    => $real_absolute_path,
            'search_path_name' => $search_path_name,
            'search_path'      => $real_search_path,
            'requested_asset'  => $real_requested_path,
            'canonical_path'   => $search_path_name . '/' . $real_requested_path,
        );
    }
}
