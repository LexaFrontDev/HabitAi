<?php

namespace App\Tests\Repository\Pomodoro;

use App\Domain\Repository\Pomodor\PomodorHistoryRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PomodoroRepositoryTest extends KernelTestCase
{
    private PomodorHistoryRepositoryInterface $pomodorHistoryRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->pomodorHistoryRepository = self::getContainer()->get(PomodorHistoryRepositoryInterface::class);
    }



    public function testFindAll()
    {

    }
}