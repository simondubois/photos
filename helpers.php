<?php

use Illuminate\Support\Str;

if (! function_exists('mix')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param  string  $path
     * @param  string  $manifestDirectory
     * @return string
     *
     * @throws \Exception
     */
    function mix($path, $manifestDirectory = '')
    {
        static $manifests = [];

        $path = Str::start($path, '/');

        if ($manifestDirectory && ! starts_with($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }

        if (file_exists(base_path('public/' . $manifestDirectory . '/hot'))) {
            $url = rtrim(file_get_contents(base_path('public/' . $manifestDirectory . '/hot')));

            if (starts_with($url, ['http://', 'https://'])) {
                return str_after($url, ':') . $path;
            }

            return "//localhost:8080{$path}";
        }

        $manifestPath = base_path('public/' . $manifestDirectory . '/mix-manifest.json');

        if (! isset($manifests[$manifestPath])) {
            if (! file_exists($manifestPath)) {
                throw new Exception('The Mix manifest does not exist.');
            }

            $manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true);
        }

        $manifest = $manifests[$manifestPath];

        if (! isset($manifest[$path])) {
            throw new Exception("Unable to locate Mix file: {$path}.");
        }

        return $manifestDirectory . $manifest[$path];
    }
}
