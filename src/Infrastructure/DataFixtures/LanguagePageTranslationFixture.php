<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Entity\Language\Language;
use App\Domain\Entity\Language\LanguagePageTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LanguagePageTranslationFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $languages = [
            'ru' => $manager->getRepository(Language::class)->findOneBy(['prefix' => 'ru']),
            'en' => $manager->getRepository(Language::class)->findOneBy(['prefix' => 'en']),
            'kz' => $manager->getRepository(Language::class)->findOneBy(['prefix' => 'kz']),
            'de' => $manager->getRepository(Language::class)->findOneBy(['prefix' => 'de']),
            'kg' => $manager->getRepository(Language::class)->findOneBy(['prefix' => 'kg']),
            'uz' => $manager->getRepository(Language::class)->findOneBy(['prefix' => 'uz']),
            'ko' => $manager->getRepository(Language::class)->findOneBy(['prefix' => 'ko']),
            'ja' => $manager->getRepository(Language::class)->findOneBy(['prefix' => 'ja']),
        ];

        $translationsLanding = [
            'loginButtonText' => [
                'ru'=>'Войти','en'=>'Login','kz'=>'Кіру',
                'de'=>'Anmelden','kg'=>'Кирүү','uz'=>'Kirish',
                'ko'=>'로그인','ja'=>'ログイン'
            ],
            'registerButtonText' => [
                'ru'=>'Регистрация','en'=>'Register','kz'=>'Тіркелу',
                'de'=>'Registrieren','kg'=>'Катталуу','uz'=>'Roʻyxatdan oʻtish',
                'ko'=>'회원가입','ja'=>'登録する'
            ],
            'logoHeadText' => [
                'ru'=>'Развивайся на 1% каждый день','en'=>'Grow 1% every day','kz'=>'Күн сайын 1% дамы',
                'de'=>'Wachse jeden Tag um 1 %','kg'=>'Ар күнү 1 % өс','uz'=>'Har kuni 1 % o‘s',
                'ko'=>'매일 1% 성장하세요','ja'=>'毎日1％成長しよう'
            ],
            'logoDescText' => [
                'ru'=>'Таймер Pomodoro, список привычек и матрица Эйзенхауэра — всё в одном месте',
                'en'=>'Pomodoro timer, habit list, and Eisenhower matrix—all in one place',
                'kz'=>'Pomodoro таймері, әдеттер тізімі және Айзенхауэр матрицасы — барлығы бір жерде',
                'de'=>'Pomodoro-Timer, Gewohnheitsliste und Eisenhower-Matrix – alles in einem',
                'kg'=>'Помодоро таймери, адаттар тизмеси жана Эйзенхауэр матрицасы — баары бир жерде',
                'uz'=>'Pomodoro taymeri, odatlar ro‘yxati va Ayzenhower matritsasi — hammasi bir joyda',
                'ko'=>'포모도로 타이머, 습관 목록, 아이젠하워 매트릭스 — 하나의 앱에서',
                'ja'=>'ポモドーロタイマー、習慣リスト、アイゼンハワーマトリックスを一つに'
            ],
            'beginButtonText' => [
                'ru'=>'Начать сейчас','en'=>'Start now','kz'=>'Қазір бастаңыз',
                'de'=>'Jetzt starten','kg'=>'Азыр баштаңыз','uz'=>'Hozir boshlang',
                'ko'=>'지금 시작하기','ja'=>'今すぐ始める'
            ],
            'feedbackText' => [
                'ru'=>'Отзывы наших пользователей','en'=>'User feedback','kz'=>'Пайдаланушылар пікірлері',
                'de'=>'Nutzer-Feedback','kg'=>'Колдонуучулардын пикирлери','uz'=>'Foydalanuvchilar fikri',
                'ko'=>'사용자 피드백','ja'=>'ユーザーフィードバック'
            ],
            'privacyPolicyText' => [
                'ru'=>'Политика конфиденциальности','en'=>'Privacy Policy','kz'=>'Құпиялық саясаты',
                'de'=>'Datenschutzrichtlinie','kg'=>'Купуялуулук саясаты','uz'=>'Maxfiylik siyosati',
                'ko'=>'개인정보 처리방침','ja'=>'プライバシーポリシー'
            ],
            'featuresText' => [
                'ru'=>'Наши фичи','en'=>'Our features','kz'=>'Біздің функциялар',
                'de'=>'Unsere Funktionen','kg'=>'Биздин функциялар','uz'=>'Bizning funksiyalar',
                'ko'=>'기능 소개','ja'=>'機能紹介'
            ],
            'plansTitle' => [
                'ru'=>'Тарифы','en'=>'Plans','kz'=>'Жоспарлар',
                'de'=>'Pläne','kg'=>'Тарифтер','uz'=>'Rejalar',
                'ko'=>'요금제','ja'=>'プラン'
            ],
            'plansSubTitle' => [
                'ru'=>'Выбери подходящий план для своей продуктивности','en'=>'Choose the right plan for your productivity','kz'=>'Өнімділігіңізге сай жоспарды таңдаңыз',
                'de'=>'Wähle den passenden Plan für deine Produktivität','kg'=>'Өндүрүмдүүлүгүңө ылайык план тандаңыз','uz'=>'Samaradorligingiz uchun mos rejani tanlang',
                'ko'=>'생산성에 맞는 요금제를 선택하세요','ja'=>'生産性に合ったプランを選びましょう'
            ],
            'selectPlanText' => [
                'ru'=>'Выбрать','en'=>'Choose','kz'=>'Таңдау',
                'de'=>'Auswählen','kg'=>'Тандоо','uz'=>'Tanlash','ko'=>'선택','ja'=>'選択'
            ],
        ];


        $translationsPremium = [
            'loginButtonText' => $translationsLanding['loginButtonText'],
            'registerButtonText' => $translationsLanding['registerButtonText'],
            'goToTasksButtonText' => [
                'ru'=>'Перейти к задачам','en'=>'Go to Tasks','kz'=>'Тапсырмаларға өту',
                'de'=>'Zu Aufgaben','kg'=>'Тапшырмаларга өтүңүз','uz'=>'Vazifalarga oʻting','ko'=>'작업으로 이동','ja'=>'タスクへ'
            ],
            'HeadText' => [
                'ru'=>'Увеличьте вашу продуктивность с TaskFlow Premium','en'=>'Boost your productivity with TaskFlow Premium',
                'kz'=>'TaskFlow Premium арқылы өнімділігіңізді арттырыңыз',
                'de'=>'Steigere deine Produktivität mit TaskFlow Premium','kg'=>'TaskFlow Premium сиздин өндүрүмдүүлүгүңүздү жогорулатат',
                'uz'=>'TaskFlow Premium bilan samaradorlikni oshiring','ko'=>'TaskFlow Premium으로 생산성을 높이세요','ja'=>'TaskFlow Premiumで生産性を高めましょう'
            ],
            'DescText' => [
                'ru'=>'Откройте все премиум-функции и наслаждайтесь организованной жизнью.','en'=>'Unlock all premium features and enjoy an organized life.',
                'kz'=>'Барлық премиум функцияларды ашып, жүйелі өмірдің рақатын көріңіз.',
                'de'=>'Schalte alle Premium-Funktionen frei und genieße ein organisiertes Leben.',
                'kg'=>'Бардык премиум функцияларды ачып, уюшкан жашоонун ырахатын көрүңүз.',
                'uz'=>'Barcha premium funksiyalarni ochib, tartibli hayotdan bahramand bo‘ling.',
                'ko'=>'모든 프리미엄 기능을 잠금 해제하고 정돈된 생활을 즐기세요.','ja'=>'すべてのプレミアム機能を開放し、整理された生活を楽しみましょう。'
            ],
            'plansTitle' => $translationsLanding['plansTitle'],
            'plansSubTitle' => [
                'ru'=>'Честный выбор без скрытых условий','en'=>'An honest choice with no hidden terms',
                'kz'=>'Жасырын шарттар жоқ, ашық әрі әділ жоспарлар','de'=>'Eine ehrliche Wahl ohne versteckte Bedingungen',
                'kg'=>'Жашырын талаптар жок, ачык жана адилеттүү тандоо','uz'=>'Yashirin shartlarsiz rost tanlov','ko'=>'숨겨진 조건 없이 정직한 선택','ja'=>'隠れた条件なしの誠実な選択'
            ],
            'faqTitle' => [
                'ru'=>'Вопросы и ответы','en'=>'FAQ','kz'=>'Жиі қойылатын сұрақтар',
                'de'=>'FAQ','kg'=>'Көп берилүүчү суроолор','uz'=>'Ko‘p so‘raladigan savollar','ko'=>'자주 묻는 질문','ja'=>'よくある質問'
            ],
            'benefitsTitle' => [
                'ru'=>'Что даёт премиум?','en'=>'What does premium offer?','kz'=>'Премиум қандай артықшылықтар береді?',
                'de'=>'Was bietet Premium?','kg'=>'Премиум эмнени берет?','uz'=>'Premium qanday afzalliklar beradi?','ko'=>'프리미엄이 제공하는 이점은 무엇인가요?','ja'=>'プレミアムのメリットは何ですか？'
            ],
            'readyQuestion' => [
                'ru' => 'Готовы достичь большего с Premium?',
                'en' => 'Ready to achieve more with Premium?',
                'kz' => 'Premium арқылы үлкен жетістікке жетуге дайынсыз ба?',
                'de' => 'Bereit, mit Premium mehr zu erreichen?',
                'kg' => 'Premium менен чоң жетишкендиктерге жетүүгө даярсызбы?',
                'uz' => 'Premium bilan katta yutuqlarga erishishga tayyormisiz?',
                'ko' => '프리미엄으로 더 큰 성과를 얻을 준비 되셨나요?',
                'ja' => 'プレミアムでより大きな成果を達成する準備はできていますか？',
            ],


            'readyButton' => [
                'ru' => 'Вперёд',
                'en' => 'Let’s Go',
                'kz' => 'Алға',
                'de' => 'Los geht’s',
                'kg' => 'Алга',
                'uz' => 'Oldinga',
                'ko' => '시작하기',
                'ja' => '進む',
            ],
            'selectPlanText' => [
                'ru'=>'Выбрать','en'=>'Choose','kz'=>'Таңдау',
                'de'=>'Auswählen','kg'=>'Тандоо','uz'=>'Tanlash','ko'=>'선택','ja'=>'選択'
            ],
        ];


        $translationsLogin = [
            'headText' => [
                'ru'=>'С возвращением 👋','en'=>'Welcome back 👋','kz'=>'Қайта қош келдіңіз 👋',
                'de'=>'Willkommen zurück 👋','kg'=>'Кайра кош келдиңиз 👋','uz'=>'Xush kelibsiz 👋',
                'ko'=>'다시 오신 걸 환영합니다 👋','ja'=>'おかえりなさい 👋'
            ],
            'headPText' => [
                'ru'=>'Войдите, чтобы продолжить','en'=>'Login to continue','kz'=>'Жалғастыру үшін кіріңіз',
                'de'=>'Melden Sie sich an, um fortzufahren','kg'=>'Улантуу үчүн кириңиз','uz'=>'Davom etish uchun kiring',
                'ko'=>'계속하려면 로그인하세요','ja'=>'続けるにはログインしてください'
            ],
            'emailLabelText' => [
                'ru'=>'Почта','en'=>'Email','kz'=>'Электрондық пошта',
                'de'=>'E‑Mail','kg'=>'Электрондук почта','uz'=>'Elektron pochta','ko'=>'이메일','ja'=>'メール'
            ],
            'passwordLabelText' => [
                'ru'=>'Пароль','en'=>'Password','kz'=>'Құпия сөз','de'=>'Passwort','kg'=>'Сырсөз','uz'=>'Parol','ко'=>'비밀번호','ja'=>'パスワード'
            ],
            'loginButton' => [
                'ru'=>'Войти','en'=>'Login','kz'=>'Кіру',
                'de'=>'Anmelden','kg'=>'Кирүү','uz'=>'Kirish','ko'=>'로그인','ja'=>'ログイン'
            ],
            'loginWithGoogleButton' => [
                'ru'=>'Войти через Google','en'=>'Login with Google','kz'=>'Google арқылы кіру',
                'de'=>'Mit Google anmelden','kg'=>'Google аркылуу кирүү','uz'=>'Google orqali kirish','ko'=>'Google로 로그인','ja'=>'Googleでログイン'
            ],
            'notHaveAccount' => [
                'ru'=>'У вас нет аккаунта? Зарегистрируйтесь','en'=>'Don’t have an account? Register','kz'=>'Аккаунтыңыз жоқ па? Тіркеліңіз',
                'de'=>'Noch keinen Account? Registrieren','kg'=>'Каттоо жокпу? Каттал','uz'=>'Hisobsizmisiz? Roʻyxatdan oʻting','ko'=>'계정이 없으신가요? 회원가입','ja'=>'アカウントをお持ちでないですか？登録'
            ],
            'invalidCredentials' => [
                'ru'=>'Неверный логин или пароль','en'=>'Invalid login or password','kz'=>'Логин немесе құпия сөз қате енгізілді',
                'de'=>'Ungültiger Login oder Passwort','kg'=>'Туура эмес логин же сырсөз','uz'=>'Notoʻgʻri login yoki parol','ko'=>'잘못된 로그인 또는 비밀번호','ja'=>'ログインまたはパスワードが無効です'
            ],


        ];

        $this->createTranslation('login', $translationsLogin, $languages, $manager);
        $this->createTranslation('landing', $translationsLanding, $languages, $manager);
        $this->createTranslation('premium', $translationsPremium, $languages, $manager);

        $manager->flush();
    }

    private function createTranslation(
        string $pageName,
        array $translations,
        array $languages,
        ObjectManager $manager
    ): void {
        foreach ($languages as $prefix => $language) {
            if (!$language) continue;

            $pageTranslate = [];
            foreach ($translations as $key => $value) {
                $pageTranslate[$key] = $value[$prefix] ?? '';
            }

            $translation = new LanguagePageTranslation();
            $translation->setPageName($pageName);
            $translation->setPageTranslate($pageTranslate);
            $translation->setLanguage($language);

            $manager->persist($translation);
        }
    }

    public function getDependencies(): array
    {
        return [LanguageFixture::class];
    }
}

