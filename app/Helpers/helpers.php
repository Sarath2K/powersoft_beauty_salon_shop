<?php

/**
 * @param $location
 * @param $errorMessage
 * @param $params
 * @param $errorTrace
 * @param $message
 * @return void
 */
function commonLogError($location, $errorMessage, $params, $errorTrace = [], $message = 'error')
{
    \Log::error([
        $message => [
            'location' => $location,
            'message' => $errorMessage,
            'params' => $params,
            'trace' => $errorTrace
        ]
    ]);
}

/**
 * @param Exception $error
 * @param $message
 * @param $location
 * @param array $params
 * @return void
 */
function logError(Exception $error, $message, $location, array $params = [])
{
    commonLogError($location, $error->getMessage(), $params, $error->getTraceAsString(), $message);
}
