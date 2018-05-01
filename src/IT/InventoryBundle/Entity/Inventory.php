<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 05/07/2017
 * Time: 17:12
 */

namespace IT\InventoryBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Inventory
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IT\InventoryBundle\Repository\inventairesRepository")
 */
class Inventory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\OneToMany(targetEntity="IT\InventoryBundle\Entity\LineInventory", mappedBy="inventaire" ,cascade="persist")
     **/
    private $ligneInventaire;

    /**
     * @var bool
     * @ORM\Column(name="etat_inventaire", type="boolean")
     */
    private $etat ;

    /**
     * @var DateTime
     * @ORM\Column(name="date_inventaire", type="datetime")
     */
    private $dateInventaire ;

    /**
     * @var DateTime
     * @ORM\Column(name="date_cloture", type="datetime",nullable=true)
     */
    private $dateCloture ;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLigneInventaire()
    {
        return $this->ligneInventaire;
    }

    /**
     * @param mixed $ligneInventaire
     */
    public function setLigneInventaire($ligneInventaire)
    {
        $this->ligneInventaire = $ligneInventaire;
    }

    /**
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param string $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    /**
     * @return DateTime
     */
    public function getDateInventaire()
    {
        return $this->dateInventaire;
    }

    /**
     * @param \DateTime $dateInventaire
     */
    public function setDateInventaire($dateInventaire)
    {
        $this->dateInventaire = $dateInventaire;
    }

    /**
     * @return DateTime
     */
    public function getDateCloture()
    {
        return $this->dateCloture;
    }

    /**
     * @param \DateTime $dateInventaire
     */
    public function setDateCloture($dateCloture)
    {
        $this->dateCloture = $dateCloture;
    }


}