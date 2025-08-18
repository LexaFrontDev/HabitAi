<?php

namespace App\Infrastructure\Repository\Language;

use App\Aplication\Dto\LangPageTranslate\ReturnPrefiixs;
use App\Domain\Entity\Language\Language;
use App\Domain\Repository\Language\LanguagePrefixInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Language>
 */
class Languages extends ServiceEntityRepository implements LanguagePrefixInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Language::class);
    }

    /**
     * @return ReturnPrefiixs[]
     */
    public function getPrefixes(): array
    {
        $prefixes = $this->createQueryBuilder('l')
            ->select('l.prefix, l.name')
            ->getQuery()
            ->getResult();


        return array_map(fn ($item) => new ReturnPrefiixs(
            lang_label: $item['name'],
            prefix: $item['prefix']
        ), $prefixes);
    }
}
