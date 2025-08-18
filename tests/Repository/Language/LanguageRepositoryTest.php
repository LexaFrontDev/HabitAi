<?php

namespace App\Tests\Repository\Language;

use App\Domain\Repository\Language\LanguageInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LanguageRepositoryTest extends KernelTestCase
{
    private LanguageInterface $language;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->language = self::getContainer()->get(LanguageInterface::class);
    }

    public function testGetPagesTranslations(): void
    {

        // Arrange
        $page = 'v1_translate_site';

        $prefix = 'ru';

        // act
        $result = $this->language->getPageTranslateByLangId($page, $prefix);

        fwrite(STDOUT, print_r($result, true));
        // assert
        $this->assertIsArray($result->translate);
        $this->assertIsString($result->prefix);
    }

    public function testGetPagesTranslationsInvalid(): void
    {

        // Arrange
        $page = 'notHave';
        $prefix = 'ru';

        // act
        $result = $this->language->getPageTranslateByLangId($page, $prefix);


        // assert
        $this->assertNull($result);
    }
}
