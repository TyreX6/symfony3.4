<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 10/03/2018
 * Time: 23:11
 */

namespace IT\ReservationBundle\Entity;
use Doctrine\ORM\Mapping as ORM;


class LimiteParSemaine extends Regles
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $nbrLimiteParSemaine;

    /**
     * @return int
     */
    public function getNbrLimiteParSemaine()
    {
        return $this->nbrLimiteParSemaine;
    }

    /**
     * @param int $nbrLimiteParSemaine
     */
    public function setNbrLimiteParSemaine($nbrLimiteParSemaine)
    {
        $this->nbrLimiteParSemaine = $nbrLimiteParSemaine;
    }



}