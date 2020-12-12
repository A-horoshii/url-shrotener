<?php
namespace AHoroshii\UrlShortenerBundle\Entity;

interface  ShortUrlInterface
{
    public function getId();
    public function getUrl();
    public function setUrl($url);
    public function getHash();
    public function setHash($hash);
    public function getRedirectQuantity(): int;
    public function addRedirectQuantity($inc = 1);
    public function getTimeLifeEnd();
    public function setTtl($ttl);
    public function getTtl();
}