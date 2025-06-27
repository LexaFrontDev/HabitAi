<?php

namespace App\Aplication\Dto\DatesDto;

class DatesDailyForSaveDto
{

    public function __construct(
        private bool $mon = true,
        private bool $tue = true,
        private bool $wed = true,
        private bool $thu = true,
        private bool $fri = true,
        private bool $sat = true,
        private bool $sun = true,
    ){}

    /**
     * @return bool
     */
    public function isMon(): bool
    {
        return $this->mon;
    }


    /**
     * @return bool
     */
    public function isTue(): bool
    {
        return $this->tue;
    }


    /**
     * @return bool
     */
    public function isWed(): bool
    {
        return $this->wed;
    }

    /**
     * @return bool
     */
    public function isThu(): bool
    {
        return $this->thu;
    }

    /**
     * @return bool
     */
    public function isFri(): bool
    {
        return $this->fri;
    }

    public function isSat(): bool
    {
        return $this->sat;
    }


    /**
     * @return bool
     */
    public function isSun(): bool
    {
        return $this->sun;
    }

}