<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 23/04/2018
 * Time: 09:05
 */

namespace IT\UserBundle\Service;


class PingService
{
    function serviceping($host, $port, $timeout = 7)
    {
        $op = 0;
        try {
            $op = fsockopen($host, $port, $errno, $errstr, $timeout);
        } catch (\Exception $er) {
            return 0;
        }
        if (!$op) return 0; //DC is N/A
        else {
            fclose($op); //explicitly close open socket connection
            return 1; //DC is up & running, we can safely connect with ldap_connect
        }
    }
}