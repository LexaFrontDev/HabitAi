<?php

namespace App\Tests\Controller\ApiControllers\PomodorControllers;

use App\Aplication\Dto\PomodorDto\ReqPomodorDto;
use App\Aplication\UseCase\PomodorUseCases\Commands\PomodorCommandUseCase;
use App\Domain\Entity\Pomdoro\PomodorHistory;
use App\Infrastructure\Controller\ApiControllers\PomodorControllers\PomodorController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class PomodorControllerTest extends TestCase
{
    private PomodorController $controller;
    private $commandUseCase;

    protected function setUp(): void
    {
        $this->commandUseCase = $this->createMock(PomodorCommandUseCase::class);
        $this->controller = new PomodorController($this->commandUseCase);
    }

    public function testCreatePomdor_ReturnsSuccessTrue_WhenSaveIsSuccessful(): void
    {
        // Arrange
        $reqDto = $this->createMock(ReqPomodorDto::class);
        $pomodorHistoryMock = $this->createMock(PomodorHistory::class);

        $this->commandUseCase->expects($this->once())
            ->method('savePomdor')
            ->with($reqDto)
            ->willReturn($pomodorHistoryMock);

        // Act
        $response = $this->controller->createPomdor($reqDto);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['success']);
    }

    public function testCreatePomdor_ReturnsSuccessFalse_WhenSaveFails(): void
    {
        // Arrange
        $reqDto = $this->createMock(ReqPomodorDto::class);

        // Имитация ошибки: метод выбрасывает исключение
        $this->commandUseCase->expects($this->once())
            ->method('savePomdor')
            ->with($reqDto)
            ->willThrowException(new \Exception("Save failed"));

        // Актуально, если контроллер ловит исключения и возвращает 500,
        // иначе тест нужно будет адаптировать под твой контроллер.

        // Act
        $response = null;
        try {
            $response = $this->controller->createPomdor($reqDto);
        } catch (\Exception $e) {
            $this->fail("Exception should be handled inside controller");
        }

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['success']);
        $this->assertEquals("Save failed", $data['error']);
    }
}
