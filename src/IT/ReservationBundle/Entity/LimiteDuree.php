<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 10/03/2018
 * Time: 23:11
 */

namespace IT\ReservationBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


class LimiteDuree extends Regles
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $limDurRes;

    /**
     * @return int
     */
    public function getLimDurRes()
    {
        return $this->limDurRes;
    }

    /**
     * @param int $limDurRes
     */
    public function setLimDurRes($limDurRes)
    {
        $this->limDurRes = $limDurRes;
    }




}