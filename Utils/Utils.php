<?php

if (!function_exists('dd')) {
    /**
     * Dump and die. It is a helper function to debug data
     * 
     * @param mixed $data
     * 
     * @return void
     */
    function dd(mixed $data)
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die();
    }
}

if (!function_exists('camelCaseToSnakeCase')) {
    /**
     * Convert camelCase to snake_case
     * 
     * @param string $string
     * 
     * @return string
     */
    function camelCaseToSnakeCase(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }
}

if (!function_exists('snakeCaseToCamelCase')) {
    /**
     * Convert snake_case to camelCase
     * 
     * @param string $string
     * 
     * @return string
     */
    function snakeCaseToCamelCase(string $string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }
}

if (!function_exists('parseGetParams')) {
    /**
     * Parse GET parameters
     * 
     * @param string $params
     * 
     * @return array
     */
    function parseGetParams(string $params): array
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
    /**
     * Set environment variables from .env file.
     * 
     * @param string $envPath
     * 
     * @throws Exception
     * 
     * @return void
     */
    function setEnvVars(string $envPath): void
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
    /**
     * Return an array with camelCase keys
     * 
     * @param array $data
     * 
     * @return array
     */
    function returnCamelCaseKeys(array $data): array
    {
        $newData = [];

        foreach ($data as $key => $value) {
            $newData[snakeCaseToCamelCase($key)] = $value;
        }

        return $newData;
    }
}