<?php

namespace App\Tests\InfrastructureTest\RepositoryTests;

use App\Domain\Repository\Language\LanguageInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LanguagePageTranslationRepositoryTest extends KernelTestCase
{
    private LanguageInterface $languageService;




    protected function setUp(): void
    {
        self::bootKernel();

        /** @var LanguageInterface $languageService */
        $languageService = static::getContainer()->get(LanguageInterface::class);
        $this->languageService = $languageService;
    }



    public function testGetPageTranslateByLangIdReturnsCorrectData(): void
    {
        $page = 'landing';
        $langPrefix = 'ru';

        $result = $this->languageService->getPageTranslateByLangId($page, $langPrefix);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('translate', $result);
        $this->assertArrayHasKey('lang', $result);

        $this->assertEquals($langPrefix, $result['lang']);

        $this->assertArrayHasKey('login_button_text', $result['translate']);
        $this->assertArrayHasKey('register_button_text', $result['translate']);
        $this->assertArrayHasKey('logo_head_text', $result['translate']);
        $this->assertArrayHasKey('logo_desc_text', $result['translate']);
        $this->assertArrayHasKey('begin_button_text', $result['translate']);
        $this->assertArrayHasKey('feedback_text', $result['translate']);
        $this->assertArrayHasKey('privacy_policy_text', $result['translate']);
    }


    public function testGetPageTranslateByLangIdReturnsCorrectDataForEnglish(): void
    {
        $result = $this->languageService->getPageTranslateByLangId('landing', 'en');

        $this->assertIsArray($result);
        $this->assertEquals('en', $result['lang']);

        $this->assertArrayHasKey('login_button_text', $result['translate']);
        $this->assertEquals('Login', $result['translate']['login_button_text']);
    }

    public function testGetPageTranslateByLangIdReturnsCorrectDataForKazakh(): void
    {
        $result = $this->languageService->getPageTranslateByLangId('landing', 'kz');

        $this->assertIsArray($result);
        $this->assertEquals('kz', $result['lang']);

        $this->assertArrayHasKey('register_button_text', $result['translate']);
        $this->assertEquals('Тіркелу', $result['translate']['register_button_text']);
    }

    public function testGetPageTranslateByLangIdReturnsNullForInvalidLangPrefix(): void
    {
        $result = $this->languageService->getPageTranslateByLangId('landing', 'xx');

        $this->assertNull($result);
    }

    public function testGetPageTranslateByLangIdReturnsNullForEmptyPage(): void
    {
        $result = $this->languageService->getPageTranslateByLangId('', 'ru');

        $this->assertNull($result);
    }


    public function testGetPageTranslateByLangIdReturnsNullIfNotFound(): void
    {
        // Act
        $result = $this->languageService->getPageTranslateByLangId('non_existent_page', 'ru');

        // Assert
        $this->assertNull($result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->languageService);
    }
}
