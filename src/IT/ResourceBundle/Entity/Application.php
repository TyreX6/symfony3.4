<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 28/03/2018
 * Time: 23:24
 */

namespace IT\ResourceBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @ExclusionPolicy("all")
 * @SWG\Definition(type="object", @SWG\Xml(name="Application"))
 */
class Application
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @SWG\Property(description="Identifiant de l'application.")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string",nullable=false)
     * @Expose
     * @SWG\Property(property="name",type="string",description="Nom de l'application.")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="IT\ResourceBundle\Entity\Dispositif", inversedBy="apps" ,cascade={"all"})
     * @ORM\JoinColumn(referencedColumnName="id",onDelete="CASCADE")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     **/
    private $dispositif;

    /**
     * Application constructor.
     */
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
    public function getDispositif()
    {
        return $this->dispositif;
    }


    /**
     * @param Dispositif $dispositif
     */
    public function setDispositif(Dispositif $dispositif)
    {
        $this->dispositif = $dispositif;
    }





}