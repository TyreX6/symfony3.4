<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 29/03/2018
 * Time: 16:24
 */

namespace IT\ResourceBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @SWG\Definition(type="object", @SWG\Xml(name="Categorie"))
 */
class Categorie
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"categories","resources"})
     * @SWG\Property(description="Identifiant du categorie.")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string",nullable=false)
     * @Serializer\Groups({"categories","resources"})
     * @SWG\Property(property="name",type="string",description="Nom de l'application.")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="IT\ResourceBundle\Entity\Ressource", mappedBy="category",cascade="persist")
     * @Serializer\Groups({"categories"})
     **/
    private $ressource;


    public function __construct()
    {
    }



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getRessource()
    {
        return $this->ressource;
    }

    /**
     * @param mixed $ressource
     */
    public function setRessource($ressource)
    {
        $this->ressource = $ressource;
    }

    public function __toString()
    {
        return $this->name;
    }



}