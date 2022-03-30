<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function findActiveQuery(): Query
    {
        return $this->createQueryBuilder('news')
            ->where('news.startDate < CURRENT_TIMESTAMP()')
            ->andWhere('news.endDate is null or news.endDate > CURRENT_TIMESTAMP()')
            ->orderBy('news.startDate', 'desc')
            ->getQuery();
    }

    /**
     * @return News[]
     */
    public function findActive(): array
    {
        return $this->findActiveQuery()->getResult();
    }

    /**
     * Récupérer la prochaine actualité (vers moins récente)
     */
    public function findNext(News $news): ?News
    {
        return $this->createQueryBuilder('news')
            ->where('news.startDate > :start_date or (news.startDate = :start_date and news.id < :current)')
            ->andWhere('news != :current')
            ->andWhere('news.startDate < CURRENT_TIMESTAMP()')
            ->andWhere('news.endDate is null or news.endDate > CURRENT_TIMESTAMP()')
            ->setParameter('current', $news)
            ->setParameter('start_date', $news->getStartDate())
            ->orderBy('news.startDate', 'asc')
            ->addOrderBy('news.id', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Récupérer la précédente actualité (vers plus récente)
     */
    public function findPrevious(News $news): ?News
    {
        return $this->createQueryBuilder('news')
            ->where('news.startDate < :start_date or (news.startDate = :start_date and news > :current)')
            ->andWhere('news != :current')
            ->andWhere('news.startDate < CURRENT_TIMESTAMP()')
            ->andWhere('news.endDate is null or news.endDate > CURRENT_TIMESTAMP()')
            ->setParameter('current', $news)
            ->setParameter('start_date', $news->getStartDate())
            ->orderBy('news.startDate', 'desc')
            ->addOrderBy('news.id', 'asc')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
