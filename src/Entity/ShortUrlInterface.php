<?php
namespace Horoshii\UrlShortenerBundle\Entity;

interface  ShortUrlInterface
{
    public function getId();
    public function getUrl();
    public function setUrl($url);
    public function getHash();
    public function setHash($hash);
    public function getRedirectQuantity(): int;
    public function addRedirectQuantity($inc = 1);
    public function getTimeLifeEnd(): ?\DateTimeInterface;
    public function setTtl(int $ttl);
    public function getTtl():int;
}