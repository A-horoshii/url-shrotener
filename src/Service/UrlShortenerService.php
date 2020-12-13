<?php

namespace Horoshii\UrlShortenerBundle\Service;

use Doctrine\ORM\EntityManager;
use Hashids\Hashids;
use Horoshii\UrlShortenerBundle\Entity\ShortUrl;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UrlShortenerService
{

    /** @var Hashids */
    private $hashids;

    /** @var EntityManager */
    private $em;

    /** @var ValidatorInterface */
    private $validator;

    /** @var bool */
    private $checkVisitorInfo = false;

    /** @var int */
    private $defaultTtl = 60;
    /**
     * @param Hashids $hashids
     * @param EntityManager $em
     * @param ValidatorInterface $validator
     * @param bool $checkVisitorInfo
     * @param int $defaultTtl
     */
    public function __construct(Hashids $hashids, EntityManager $em, ValidatorInterface $validator, bool $checkVisitorInfo = null, int $defaultTtl = null)
    {
        $this->hashids = $hashids;
        $this->em = $em;
        $this->validator = $validator;
        if ($checkVisitorInfo) {
            $this->checkVisitorInfo = $checkVisitorInfo;
        }
        if ($defaultTtl) {
            $this->defaultTtl = $defaultTtl;
        }
    }

    /**
     * @param $url
     * @param int|null $ttl
     * @return ShortUrl|ConstraintViolationListInterface
     * @throws \Exception
     */
    public function createShortUrl(string $url = null, int $ttl = null)
    {
        $nowDateTime = new \DateTime();
        $shortUrl = new ShortUrl();
        $shortUrl->setUrl($url);
        $shortUrl->setCreatedAt(new \DateTime());
        $shortUrl->setTtl($ttl);
        $errors = $this->validator->validate($shortUrl);
        if ($errors->count()>0) {
            return $errors;
        } else {
            $shortUrl->setTimeLifeEnd($nowDateTime->modify('+ '. $shortUrl->getTtl() .' minutes'));
            $this->em->persist($shortUrl);
            $this->em->flush();
            $hash = $this->hashids->encode($shortUrl->getId());
            $shortUrl->setHash($hash);
            $this->em->persist($shortUrl);
            $this->em->flush();
            return $shortUrl;
        }
    }

    /**
     * @param $hash
     * @param Request $request
     * @return null|RedirectResponse
     * @throws \Exception
     */
    public function getRedirectResponse($hash, Request $request = null)
    {
        $shortUrl = $this->em->getRepository(ShortUrl::class)->findOneByHash($hash);
        $nowDataTime = new \DateTime();
        if ($shortUrl instanceof ShortUrl && $shortUrl->getTimeLifeEnd() > $nowDataTime) {
            $shortUrl->addRedirectQuantity();
            if ($this->checkVisitorInfo && $request) {
                //todo: Create Visitor Info from RequestStack
            }
            $this->em->persist($shortUrl);
            $this->em->flush();
            $targetUrl = $shortUrl->getUrl();
            return new RedirectResponse($targetUrl);
        } else {
            return null;
        }
    }
}
