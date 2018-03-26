<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 21/02/2018
 * Time: 12:36
 */

namespace IT\UserBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ExclusionPolicy("all")
 * @ORM\Table(name="LdapConfig")
 */
class LdapConfig
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Ip(
     *     message="Le champ doit étre une adresse IP."
     * )
     * @Expose
     */
    protected $host;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your Base DN must be at least {{ limit }} characters long",
     *      maxMessage = "Your Base DN cannot be longer than {{ limit }} characters"
     * )
     * @Expose
     */
    protected $baseDn;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 100,
     *      max = 9999,
     *      minMessage = "Le port doit étre supérieur à 100",
     *      maxMessage = "Le port doit étre inférieur à 9999"
     * )
     * @Expose
     */
    protected $port;

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    public function __construct()
    {
        // your own logic
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getBaseDn()
    {
        return $this->baseDn;
    }

    /**
     * @param mixed $baseDn
     */
    public function setBaseDn($baseDn)
    {
        $this->baseDn = $baseDn;
    }



}