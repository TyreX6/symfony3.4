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

/**
 * Materiel
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class LineInventory
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
     * @ORM\ManyToOne(targetEntity="IT\DispositifBundle\Entity\Ressource")
     **/
    private $resource;

    /**
     * @ORM\ManyToOne(targetEntity="IT\InventoryBundle\Entity\Inventory", inversedBy="ligneInventaire" ,cascade="persist")
     **/
    private $inventaire;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=20)
     */
    private $etat;

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

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
    public function getInventaire()
    {
        return $this->inventaire;
    }

    /**
     * @param mixed $inventaire
     */
    public function setInventaire($inventaire)
    {
        $this->inventaire = $inventaire;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource): void
    {
        $this->resource = $resource;
    }



}