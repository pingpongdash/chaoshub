<?php
/*************************************************************************
Copyright 2020 tendenken Lab. (天神橋電算技術研究所)

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
**************************************************************************/
declare(strict_types=1);

class PathFinder {
    public function findPath(string $directory, array $excludes = []) {
        if (!$directoryHandler = opendir($directory)) {
            throw new \Exception("Cannot open directory: $directory") ;
        }
        set_include_path(get_include_path().PATH_SEPARATOR.$directory) ;
        while (($file = readdir($directoryHandler)) !== false) {
            if ($file === '.' || $file === '..') continue;
            if (in_array($file, $excludes, true)) continue;
            $filePath = $directory . DIRECTORY_SEPARATOR . $file;
            if (is_dir($filePath)) {
                set_include_path(get_include_path() . PATH_SEPARATOR . $filePath);
                $this->findPath($filePath . DIRECTORY_SEPARATOR, $excludes);
            }
        }
        closedir($directoryHandler);
    }
}
