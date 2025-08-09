<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Language;

use App\Aplication\Dto\LangPageTranslate\LangPageReturn;
use App\Aplication\Dto\LangPageTranslate\TranslateNames;
use App\Domain\Entity\Language\LanguagePageTranslation;
use App\Domain\Repository\Language\LanguageInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LanguagePageRepositoru extends ServiceEntityRepository implements LanguageInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LanguagePageTranslation::class);
    }

    /**
     * @param string $version
     * @param string $prefix
     * @return LangPageReturn
     */
    public function getPageTranslateByLangId(string $version, string $prefix): ?LangPageReturn
    {
        $qb = $this->createQueryBuilder('lpt')
            ->join('lpt.language', 'lang')
            ->where('lpt.pageName = :page')
            ->andWhere('lang.prefix = :prefix')
            ->andWhere('lpt.is_delete = 0')
            ->andWhere('lang.is_delete = 0')
            ->setParameter('page', $version)
            ->setParameter('prefix', $prefix)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$qb) {
            return null;
        }

        $rawTranslate = $qb->getPageTranslate();
        $translateArray = json_decode(json_encode($rawTranslate), true);

        return new LangPageReturn(
            translate: $translateArray,
            prefix: $qb->getLanguage()->getPrefix(),
        );
    }





}
