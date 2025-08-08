<?php

namespace App\Tests\AplicationTest\UseCase\LanguageUseCases;

use App\Aplication\Service\Serialaizer\PageTranslateDtoSerializer;
use App\Aplication\UseCase\LanguageUseCases\QueryLanguageUseCases;
use App\Domain\Repository\Language\LanguageInterface;
use PHPUnit\Framework\TestCase;

class QueryLanguageUseCasesTest extends TestCase
{
    private QueryLanguageUseCases $queryLanguageUseCases;
    private PageTranslateDtoSerializer $pageTranslateDtoSerializer;
    private LanguageInterface $languageRepository;

    protected function setUp(): void
    {
        $this->pageTranslateDtoSerializer = $this->createMock(PageTranslateDtoSerializer::class);
        $this->languageRepository = $this->createMock(LanguageInterface::class);

        $this->queryLanguageUseCases = new QueryLanguageUseCases(
            $this->pageTranslateDtoSerializer,
            $this->languageRepository
        );
    }

    public function testGetTranslatePageByLangSuccess(): void
    {
        // Arrange
        $page = 'login';
        $lang = 'en';
        $translateData = [
            'translate' => '{"title":"Login","email":"Email","password":"Password"}'
        ];
        $expectedTranslations = (object)[
            'title' => 'Login',
            'email' => 'Email',
            'password' => 'Password'
        ];

        $this->languageRepository
            ->expects($this->once())
            ->method('getPageTranslateByLangId')
            ->with($page, $lang)
            ->willReturn($translateData);

        $this->pageTranslateDtoSerializer
            ->expects($this->once())
            ->method('deserializeToDto')
            ->with($page, $translateData['translate'])
            ->willReturn($expectedTranslations);

        // Act
        $result = $this->queryLanguageUseCases->getTranslatePageByLang($page, $lang);

        // Assert
        $this->assertEquals($expectedTranslations, $result);
    }

    public function testGetTranslatePageByLangWithNullResult(): void
    {
        // Arrange
        $page = 'register';
        $lang = 'ru';

        $this->languageRepository
            ->expects($this->once())
            ->method('getPageTranslateByLangId')
            ->with($page, $lang)
            ->willReturn(['translate' => '{"title":"Login","email":"Email","password":"Password"}']);

        // Act
        $result = $this->queryLanguageUseCases->getTranslatePageByLang($page, $lang);

        // Assert
        $this->assertNull($result);
    }

    public function testGetTranslatePageByLangWithRepositoryException(): void
    {
        // Arrange
        $page = 'profile';
        $lang = 'de';

        $this->languageRepository
            ->expects($this->once())
            ->method('getPageTranslateByLangId')
            ->with($page, $lang)
            ->willThrowException(new \Exception('Repository Error'));

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Repository Error');
        
        $this->queryLanguageUseCases->getTranslatePageByLang($page, $lang);
    }

    public function testGetTranslatePageByLangWithDifferentLanguages(): void
    {
        // Arrange
        $page = 'dashboard';
        $lang = 'it';
        $translateData = [
            'translate' => '{"welcome":"Benvenuto","dashboard":"Pannello di controllo"}'
        ];
        $expectedTranslations = (object)[
            'welcome' => 'Benvenuto',
            'dashboard' => 'Pannello di controllo'
        ];

        $this->languageRepository
            ->expects($this->once())
            ->method('getPageTranslateByLangId')
            ->with($page, $lang)
            ->willReturn($translateData);

        $this->pageTranslateDtoSerializer
            ->expects($this->once())
            ->method('deserializeToDto')
            ->with($page, $translateData['translate'])
            ->willReturn($expectedTranslations);

        // Act
        $result = $this->queryLanguageUseCases->getTranslatePageByLang($page, $lang);

        // Assert
        $this->assertEquals($expectedTranslations, $result);
    }

    public function testGetTranslatePageByLangWithDifferentPages(): void
    {
        // Arrange
        $page = 'Tasks';
        $lang = 'pt';
        $translateData = [
            'translate' => '{"add_task":"Adicionar tarefa","edit_task":"Editar tarefa","delete_task":"Excluir tarefa"}'
        ];
        $expectedTranslations = (object)[
            'add_task' => 'Adicionar tarefa',
            'edit_task' => 'Editar tarefa',
            'delete_task' => 'Excluir tarefa'
        ];

        $this->languageRepository
            ->expects($this->once())
            ->method('getPageTranslateByLangId')
            ->with($page, $lang)
            ->willReturn($translateData);

        $this->pageTranslateDtoSerializer
            ->expects($this->once())
            ->method('deserializeToDto')
            ->with($page, $translateData['translate'])
            ->willReturn($expectedTranslations);

        // Act
        $result = $this->queryLanguageUseCases->getTranslatePageByLang($page, $lang);

        // Assert
        $this->assertEquals($expectedTranslations, $result);
    }

    public function testGetTranslatePageByLangWithEmptyTranslate(): void
    {
        // Arrange
        $page = 'home';
        $lang = 'fr';
        $translateData = [
            'translate' => '{}'
        ];
        $expectedTranslations = (object)[];

        $this->languageRepository
            ->expects($this->once())
            ->method('getPageTranslateByLangId')
            ->with($page, $lang)
            ->willReturn($translateData);

        $this->pageTranslateDtoSerializer
            ->expects($this->once())
            ->method('deserializeToDto')
            ->with($page, $translateData['translate'])
            ->willReturn($expectedTranslations);

        // Act
        $result = $this->queryLanguageUseCases->getTranslatePageByLang($page, $lang);

        // Assert
        $this->assertEquals($expectedTranslations, $result);
    }
}
