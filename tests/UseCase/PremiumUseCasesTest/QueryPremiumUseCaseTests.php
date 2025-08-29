<?php

namespace App\Tests\UseCase\PremiumUseCasesTest;

use App\Aplication\Dto\BenefistDto\BenefitsDtoRes;
use App\Aplication\Dto\FaqsDto\FaqDtoRes;
use App\Aplication\Dto\PremiumDto\PremiumPlansRes;
use App\Aplication\UseCase\Diffrent\BenefitsUseCases\QueryBenefitsUseCases;
use App\Aplication\UseCase\Diffrent\FaqUseCase\QueryFaqUseCases;
use App\Aplication\UseCase\PremiumUseCases\QueryPremiumUseCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class QueryPremiumUseCaseTests extends KernelTestCase
{
    private QueryPremiumUseCase $queryPremiumUseCase;
    private QueryFaqUseCases $queryFaqUseCases;
    private QueryBenefitsUseCases $queryBenefitsUseCases;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->queryPremiumUseCase = self::getContainer()->get(QueryPremiumUseCase::class);
        $this->queryFaqUseCases =  self::getContainer()->get(QueryFaqUseCases::class);
        $this->queryBenefitsUseCases = self::getContainer()->get(QueryBenefitsUseCases::class);
    }

    public function testQueryPremium(): void
    {
        // act
        $result = $this->queryPremiumUseCase->getAllPremiumPlans();

        // assert
        $this->assertIsArray($result, 'Result should be an array');
        $this->assertNotEmpty($result, 'Result array should not be empty');

        foreach ($result as $item) {
            $this->assertInstanceOf(PremiumPlansRes::class, $item, 'Each item should be PremiumPlansRes DTO');
        }
    }

    public function testQueryBenefits(): void
    {
        // act
        $result = $this->queryBenefitsUseCases->getAllBenefits();

        // assert
        $this->assertIsArray($result, 'Result should be an array');
        $this->assertNotEmpty($result, 'Result array should not be empty');

        foreach ($result as $item) {
            $this->assertInstanceOf(BenefitsDtoRes::class, $item, 'Each item should be PremiumPlansRes DTO');
        }
    }

    public function testQueryFaq(): void
    {
        // act
        $result = $this->queryFaqUseCases->getAllFaqs();

        // assert
        $this->assertIsArray($result, 'Result should be an array');
        $this->assertNotEmpty($result, 'Result array should not be empty');

        foreach ($result as $item) {
            $this->assertInstanceOf(FaqDtoRes::class, $item, 'Each item should be PremiumPlansRes DTO');
        }
    }
}
