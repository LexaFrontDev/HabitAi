<?php

namespace App\Tests\UseCaseTests\PomodorUseCaseTests\CommandUseCaseTests;

use App\Aplication\Dto\PomodorDto\RepPomodorDto;
use App\Aplication\Dto\PomodorDto\ReqPomodorDto;
use App\Aplication\UseCase\PomodorUseCases\Commands\PomodorCommandUseCase;
use App\Domain\Entity\Pomdoro\PomodorHistory;
use App\Domain\Repository\Pomodor\PomodorHistoryRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PomodorUseCaseTests extends TestCase
{
    private MockObject $pomodorHistoryRepository;

    protected function setUp(): void
    {
        $this->pomodorHistoryRepository = $this->createMock(PomodorHistoryRepositoryInterface::class);
    }

    public function testCalculatePeriodLabel_WithLessThanOneHour_ReturnsMinutesLabel()
    {
        $useCase = new PomodorCommandUseCase($this->pomodorHistoryRepository);
        $seconds = 55 * 60;

        $label = $useCase->calculatePeriodLabel($seconds);
        $this->assertEquals('55m', $label);
    }

    public function testCalculatePeriodLabel_WithExactlyOneHour_ReturnsHourLabel()
    {
        $useCase = new PomodorCommandUseCase($this->pomodorHistoryRepository);
        $seconds = 60 * 60;

        $label = $useCase->calculatePeriodLabel($seconds);
        $this->assertEquals('1H', $label);
    }

    public function testCalculatePeriodLabel_WithOneHourAndFifteenMinutes_ReturnsHoursAndMinutesLabel()
    {
        $useCase = new PomodorCommandUseCase($this->pomodorHistoryRepository);
        $seconds = (60 + 15) * 60;

        $label = $useCase->calculatePeriodLabel($seconds);
        $this->assertEquals('1H 15m', $label);
    }

    public function testSavePomdor_CreatesAndReturnsPomodorHistory()
    {
        // 🅰️ Arrange
        $reqDto = new ReqPomodorDto(
            1,
            3600,
            1000,           // int
            4600,           // int
            time()           // int
        );
        $expectedLabel = '1H';
        $expectedRepDto = new RepPomodorDto(
            $reqDto->getUserId(),
            $reqDto->getTimeFocus(),
            $reqDto->getTimeStart(),
            $reqDto->getTimeEnd(),
            $reqDto->getCreatedDate(),
            $expectedLabel
        );
        $expectedPomodorHistory = $this->createMock(PomodorHistory::class);

        $useCase = $this->getMockBuilder(PomodorCommandUseCase::class)
            ->setConstructorArgs([$this->pomodorHistoryRepository])
            ->onlyMethods(['calculatePeriodLabel'])
            ->getMock();

        $useCase->expects($this->once())
            ->method('calculatePeriodLabel')
            ->with($reqDto->getTimeFocus())
            ->willReturn($expectedLabel);

        $this->pomodorHistoryRepository->expects($this->once())
            ->method('cretePomodorHistory')
            ->with($this->callback(function ($arg) use ($expectedRepDto) {
                return $arg->getUserId() === $expectedRepDto->getUserId()
                    && $arg->getTimeFocus() === $expectedRepDto->getTimeFocus()
                    && $arg->getTimeStart() === $expectedRepDto->getTimeStart()
                    && $arg->getTimeEnd() === $expectedRepDto->getTimeEnd()
                    && $arg->getCreatedDate() === $expectedRepDto->getCreatedDate()
                    && $arg->getPeriodLabel() === $expectedRepDto->getPeriodLabel();
            }))
            ->willReturn($expectedPomodorHistory);

        // 🅰️ Act
        $result = $useCase->savePomdor($reqDto);

        // ✅ Assert
        $this->assertSame($expectedPomodorHistory, $result);
    }


}
