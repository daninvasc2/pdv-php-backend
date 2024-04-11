<?php

if (!function_exists('dd')) {
    function dd($data)
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die();
    }
}

if (!function_exists('camelCaseToSnakeCase')) {
    function camelCaseToSnakeCase($string)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }
}

if (!function_exists('snakeCaseToCamelCase')) {
    function snakeCaseToCamelCase($string)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }
}

if (!function_exists('parseGetParams')) {
    function parseGetParams($params)
    {
        $params = explode('&', $params);
        $parsedParams = [];

        foreach ($params as $param) {
            $param = explode('=', $param);
            $parsedParams[$param[0]] = $param[1];
        }

        return $parsedParams;
    }
}

if (!function_exists('setEnvVars')) {
    function setEnvVars($envPath)
    {
        if (!file_exists($envPath)) {
            throw new Exception('Arquivo .env não encontrado');
        }

        $env = file_get_contents($envPath);

        $lines = explode("\n", $env);

        if (count($lines) > 0) {
            foreach ($lines as $line) {
                $line = explode('=', $line);

                if (count($line) < 2) {
                    continue;
                }

                $key = trim($line[0]);
                $value = trim($line[1]);

                putenv("$key=$value");
            }
        }
    }
}

if (!function_exists('returnCamelCaseKeys')) {
    function returnCamelCaseKeys($data)
    {
        $newData = [];

        foreach ($data as $key => $value) {
            $newData[snakeCaseToCamelCase($key)] = $value;
        }

        return $newData;
    }
}