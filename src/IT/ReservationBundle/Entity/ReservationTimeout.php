<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 10/03/2018
 * Time: 23:11
 */

namespace IT\ReservationBundle\Entity;
use Doctrine\ORM\Mapping as ORM;


class ReservationTimeout extends Regles
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $dureeTimeout;

    /**
     * @return int
     */
    public function getDureeTimeout()
    {
        return $this->dureeTimeout;
    }

    /**
     * @param int $dureeTimeout
     */
    public function setDureeTimeout($dureeTimeout)
    {
        $this->dureeTimeout = $dureeTimeout;
    }

}