<?php
namespace Horoshii\UrlShortenerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="short_urls")
 * @ORM\Entity(repositoryClass="Horoshii\UrlShortenerBundle\Repository\ShortUrlRepository")
 */
class ShortUrl implements ShortUrlInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="hash", type="string", unique=true, nullable=true)
     */
    protected $hash;

    /**
     * @var string
     * @ORM\Column(name="url", type="text", length=2300)
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    protected $url;

    /**
     * @var int
     * @ORM\Column(name="redirect_quantity", type="integer")
     */
    protected $redirectQuantity = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var int
     * @Assert\GreaterThan(0)
     */
    private $ttl = 60;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $timeLifeEnd;

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
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     *
     * @return $this
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getTimeLifeEnd(): ?\DateTimeInterface
    {
        return $this->timeLifeEnd;
    }

    /**
     * @param \DateTimeInterface $timeLifeEnd
     *
     * @return $this
     */
    public function setTimeLifeEnd(?\DateTimeInterface $timeLifeEnd): self
    {
        $this->timeLifeEnd = $timeLifeEnd;

        return $this;
    }

    /**
     * @return int
     */
    public function getRedirectQuantity():int
    {
        return $this->redirectQuantity;
    }

    /**
     * @param int $redirectQuantity
     *
     * @return $this
     */
    public function setRedirectQuantity(int $redirectQuantity)
    {
        $this->redirectQuantity = $redirectQuantity;

        return $this;
    }

    /**
     * @return int
     */
    public function getTtl():int
    {
        return $this->ttl;
    }

    /**
     * @param int $ttl
     *
     * @return $this
     */
    public function setTtl(int $ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * @param int $inc
     *
     * @return $this
     */
    public function addRedirectQuantity($inc = 1)
    {
        $this->redirectQuantity += $inc;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}