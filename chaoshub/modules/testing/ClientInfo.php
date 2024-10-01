<?php
declare(strict_types=1);
/*************************************************************************
    Copyright 2020  HALCYON CYBERNETICS

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

class ClientInfo  {

    public function __construct(?array $permissions) {
    }

    public function respond() {
        $headers = apache_request_headers();
        $browserData = get_browser(null, true);
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Key</th><th>Value</th></tr>";
        foreach ($browserData as $key => $value) {
            echo "<tr><td>{$key}</td><td>" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "</td></tr>";
        }
        foreach ($headers as $key => $value) {
            echo "<tr><td>{$key}</td><td>{$value}</td></tr>";
        }
        echo "</table>";
        exit ;
    }
    public function setClient($client){}
}
