<?php

namespace App\Tests\InfrastructureTest\RepositoryTests\Matric;

use App\Domain\Entity\Matric\MatricColumn;
use App\Domain\Repository\Matric\MatricColumnInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Aplication\Dto\Matric\RenameColumnMatric;
class MatricColumnRepositoryTest extends KernelTestCase
{
    private MatricColumnInterface $matricColumnRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->matricColumnRepository = static::getContainer()
            ->get(MatricColumnInterface::class);
    }


    public function testRenameMatricUpdate(): void
    {
        // === Arrange ===
        $userId = 9999;
        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $repo = $entityManager->getRepository(MatricColumn::class);

        $original = new MatricColumn();
        $original->setUserId($userId);
        $original->setFirstColumnName('Old 1');
        $original->setSecondColumnName('Old 2');
        $original->setThirdColumnName('Old 3');
        $original->setFourthColumnName('Old 4');
        $entityManager->persist($original);
        $entityManager->flush();

        $dto = new RenameColumnMatric(
            userId: $userId,
            firstColumnName: 'New 1',
            secondColumnName: 'New 2',
            thirdColumnName: 'New 3',
            fourthColumnName: 'New 4'
        );

        // === Act ===
        $result = $this->matricColumnRepository->renameMatricColumn($dto);

        // === Assert ===
        $this->assertTrue($result);

        $updated = $repo->findOneBy(['userId' => $userId]);

        $this->assertSame('New 1', $updated->getFirstColumnName());
        $this->assertSame('New 2', $updated->getSecondColumnName());
        $this->assertSame('New 3', $updated->getThirdColumnName());
        $this->assertSame('New 4', $updated->getFourthColumnName());

        $entityManager->remove($updated);
        $entityManager->flush();
    }



}
