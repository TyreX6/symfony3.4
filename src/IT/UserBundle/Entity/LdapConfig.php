<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 21/02/2018
 * Time: 12:36
 */

namespace IT\UserBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="LdapConfig")
 */
class LdapConfig
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    protected $host;

    /**
     * @ORM\Column(type="string")
     */
    protected $baseDn;

    /**
     * @ORM\Column(type="integer")
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