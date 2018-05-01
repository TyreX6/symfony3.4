<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 26/04/2018
 * Time: 12:26
 */

namespace IT\ReservationBundle\Service;

use Psr\Log\LoggerInterface;

class LoggerService
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function logInfo($msg, $var)
    {
        $this->logger->info($msg, $var);
    }
}
