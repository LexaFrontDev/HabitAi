<?php

namespace App\Tests\UseCaseTests\PomodorUseCaseTests\QueryUseCases;

use App\Aplication\Dto\PomodorDto\ResPomdorCountStatistic;
use App\Aplication\Dto\UsersDto\UsersInfoForToken;
use App\Aplication\UseCase\PomodorUseCases\Query\QueryPomodorUseCase;
use App\Domain\Repository\Pomodor\PomodorHistoryRepositoryInterface;
use App\Domain\Service\JwtServicesInterface;
use App\Domain\Service\Tokens\AuthTokenServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class QueryPomodorUseCaseTest extends TestCase
{
    public function testGetCountAndPeriodLabel_ReturnsDto_WhenEntriesExist()
    {
        // Arrange
        $authTokenService = $this->createMock(AuthTokenServiceInterface::class);
        $jwtServices = $this->createMock(JwtServicesInterface::class);
        $pomodoroRepo = $this->createMock(PomodorHistoryRepositoryInterface::class);

        $userInfo = new UsersInfoForToken(123, 'TestUser', 'test@example.com');

        $request = new Request();

        $authTokenService->method('getTokens')
            ->with($request)
            ->willReturn(['access_token' => 'fake_token']);
        $jwtServices->method('getUserInfoFromToken')
            ->with('fake_token')
            ->willReturn($userInfo);

        $entry1 = $this->getMockBuilder(\stdClass::class)->addMethods(['getPeriodLabel'])->getMock();
        $entry1->method('getPeriodLabel')->willReturn('1H');

        $entry2 = $this->getMockBuilder(\stdClass::class)->addMethods(['getPeriodLabel'])->getMock();
        $entry2->method('getPeriodLabel')->willReturn('30m');

        $pomodoroRepo->method('getDataByUserIdAndPeriod')->willReturn([$entry1, $entry2]);

        $useCase = new QueryPomodorUseCase($authTokenService, $jwtServices, $pomodoroRepo);

        // Act
        $result = $useCase->getCountAndPeriodLabel('week', $request);

        // Assert
        $this->assertInstanceOf(ResPomdorCountStatistic::class, $result);
        $this->assertEquals('week', $result->getTarget());
        $this->assertEquals(2, $result->getCount());
        $this->assertEquals('1H 30m', $result->getPeriodLabel());
    }

    public function testGetCountAndPeriodLabel_ReturnsFalse_OnException()
    {
        // Arrange
        $authTokenService = $this->createMock(AuthTokenServiceInterface::class);
        $jwtServices = $this->createMock(JwtServicesInterface::class);
        $pomodoroRepo = $this->createMock(PomodorHistoryRepositoryInterface::class);

        $authTokenService->method('getTokens')
            ->will($this->throwException(new \Exception()));
        $useCase = new QueryPomodorUseCase($authTokenService, $jwtServices, $pomodoroRepo);

        $request = new Request();

        // Act
        $result = $useCase->getCountAndPeriodLabel('week', $request);

        // Assert
        $this->assertFalse($result);
    }
}
