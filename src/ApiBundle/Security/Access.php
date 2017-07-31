<?php
/**
 * Created by PhpStorm.
 * User: Богдан
 * Date: 31.07.2017
 * Time: 0:44
 */

namespace ApiBundle\Security;

class Access
{
    /**
     * @param $method
     * @param $uri
     * @param $body
     * @param $timestamp
     * @param $secretKey
     * @return string
     */
    function signRequest($method, $uri, $body, $timestamp, $secretKey)
    {
        $string = implode("\n", [
            $method,
            $uri,
            $body,
            $timestamp,
        ]);

        return hash_hmac('sha256', $string, $secretKey);
    }
}