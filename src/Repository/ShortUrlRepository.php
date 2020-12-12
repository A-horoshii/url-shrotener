<?php

namespace Horoshii\UrlShortenerBundle\Repository;

use Horoshii\UrlShortenerBundle\Entity\ShortUrl;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @method ShortUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShortUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShortUrl[]    findAll()
 * @method ShortUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShortUrlRepository extends EntityRepository
{
    private $shortUrlFields = [
        'url',
        'code',
    ];

    private  $shortUrlFieldsInt = [
        'id',
        'redirectQuantity'
    ];
    protected $container;

    public function getShortUrls(array $requestParams)
    {
        $qb = $this->createQueryBuilder('su');
        $this->setOrderBy($requestParams, $qb);
        if (array_key_exists('limit', $requestParams)) {
            $qb->setMaxResults($requestParams['limit']);
        }
        if (array_key_exists('offset', $requestParams)) {
            $qb->setFirstResult($requestParams['offset']);
        }
        if (isset($requestParams['filter']) && is_array($requestParams['filter']) && count($requestParams['filter'])>0) {
            $this->setFilters($requestParams['filter'], $qb);
        }

        return $qb->getQuery()->getResult();
    }

    public function getShortUrlsCount(array $requestParams)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('COUNT(DISTINCT su.id)');
        if (isset($requestParams['filter']) && is_array($requestParams['filter']) && count($requestParams['filter'])>0) {
            $this->setFilters($requestParams['filter'], $qb);
        }
        return $qb->getQuery()->getSingleScalarResult();
    }

    private function setOrderBy(array $requestParams, QueryBuilder $qb)
    {
        if (isset($requestParams['orderBy'])) {
            $orderArray = explode(" ", $requestParams['orderBy']);
            $orderBy = isset($orderArray[0])?$orderArray[0]:null;
            $orderType = (isset($orderArray[1]) && ($orderArray[1]  == 'ASC' || $orderArray[1]  == 'DESC')) ? $orderArray[1]: 'DESC';
            if (in_array($orderBy, $this->shortUrlFields) || in_array($orderBy, $this->shortUrlFieldsInt)) {
                $qb->orderBy('su.'.$orderBy, $orderType);
            }
        } else {
            $qb->orderBy('su.id', 'DESC');
        }
    }

    private function setFilters(array $filterArray, QueryBuilder $qb)
    {
        foreach ($filterArray as $key=>$value) {
            $this->setFilter($key, $value, $qb);
        }
    }

    private function setFilter($key, $value, QueryBuilder $qb)
    {
        if (empty($value) && !is_numeric($value)) {
            return;
        }
        if (in_array($key, $this->shortUrlFields)) {
            $qb->andWhere("su.".$key." LIKE :value".$key);
            $qb->setParameter('value'.$key, $value.'%');
        } elseif (in_array($key, $this->shortUrlFieldsInt)) {
            $qb->andWhere("su.".$key." = :valueInt".$key);
            $qb->setParameter('valueInt'.$key, (int)$value);
        }
    }

    public function findOneByHash(string $hash)
    {
        $qb = $this->createQueryBuilder('su');
        $qb->where('su.hash = :hash');
        $qb->setParameter('hash', $hash);
        $qb->setMaxResults(1);
        return $qb->getQuery()->getOneOrNullResult();
    }
}
