<?php

namespace App\Domain\ValueObject\Settings;

class SettingsUsersValueObject
{
    public function __construct(
        public string $color_background_color,
    ) {
    }
}
