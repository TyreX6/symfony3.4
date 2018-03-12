<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 10/03/2018
 * Time: 23:11
 */

namespace IT\ReservationBundle\Entity;
use Doctrine\ORM\Mapping as ORM;


class LimiteParJour extends Regles
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $nbrLimiteParJour;

    /**
     * @return int
     */
    public function getNbrLimiteParJour()
    {
        return $this->nbrLimiteParJour;
    }

    /**
     * @param int $nbrLimiteParJour
     */
    public function setNbrLimiteParJour($nbrLimiteParJour)
    {
        $this->nbrLimiteParJour = $nbrLimiteParJour;
    }



}