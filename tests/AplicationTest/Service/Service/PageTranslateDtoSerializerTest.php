<?php

namespace App\Tests\AplicationTest\Service\Service;

use App\Aplication\Dto\LangPageTranslate\LandingPageTranslateDto;
use App\Aplication\Service\Serialaizer\PageTranslateDtoSerializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;
use InvalidArgumentException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PageTranslateDtoSerializerTest extends TestCase
{
    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        $nameConverter = new CamelCaseToSnakeCaseNameConverter();
        $normalizer = new ObjectNormalizer(null, $nameConverter);
        $this->serializer = new Serializer([$normalizer]);
    }

    public function testDeserializeLandingPage(): void
    {
        $translateData = [
            'loginButtonText' => 'Login',
            'registerButtonText' => 'Register',
            'logoHeadText' => 'Head',
            'logoDescText' => 'Desc',
            'beginButtonText' => 'Start',
            'feedbackText' => 'Feedback',
            'privacyPolicyText' => 'Policy',
        ];

        $service = new PageTranslateDtoSerializer($this->serializer);

        $dto = $service->deserializeToDto('landing', $translateData);

        $this->assertInstanceOf(LandingPageTranslateDto::class, $dto);
        $this->assertSame('Login', $dto->loginButtonText);
        $this->assertSame('Policy', $dto->privacyPolicyText);
    }

    public function testDeserializeLandingPageWithSnakeCaseKeys(): void
    {
        $translateData = [
            'login_button_text' => 'Login',
            'register_button_text' => 'Register',
            'logo_head_text' => 'Head',
            'logo_desc_text' => 'Desc',
            'begin_button_text' => 'Start',
            'feedback_text' => 'Feedback',
            'privacy_policy_text' => 'Policy',
        ];

        $service = new PageTranslateDtoSerializer($this->serializer);

        $dto = $service->deserializeToDto('landing', $translateData);

        $this->assertInstanceOf(LandingPageTranslateDto::class, $dto);
        $this->assertSame('Login', $dto->loginButtonText);
        $this->assertSame('Policy', $dto->privacyPolicyText);
    }

    public function testDeserializeUnknownPageThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown page: unknown');

        $service = new PageTranslateDtoSerializer($this->serializer);

        $service->deserializeToDto('unknown', []);
    }
}
