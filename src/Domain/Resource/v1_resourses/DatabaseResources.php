<?php

namespace App\Domain\Resource\v1_resourses;

use App\Aplication\Dto\ResourceDto\BenefitsDto;
use App\Aplication\Dto\ResourceDto\FaqDto;
use App\Aplication\Dto\ResourceDto\HabitsTemplatesDto;
use App\Aplication\Dto\ResourceDto\LanguageDto;
use App\Aplication\Dto\ResourceDto\PremiumPlansDto;

class DatabaseResources
{
    /**
     * @return LanguageDto[]
     */
    public function getLanguages(): array
    {
        return [
            new LanguageDto(name: 'Русский', prefix: 'ru'),
            new LanguageDto(name: 'English', prefix: 'en'),
            new LanguageDto(name: 'Қазақ тілі', prefix: 'kz'),
            new LanguageDto(name: 'Deutsch', prefix: 'de'),
            new LanguageDto(name: 'Кыргыз тили', prefix: 'kg'),
            new LanguageDto(name: 'O‘zbek tili', prefix: 'uz'),
            new LanguageDto(name: '한국어', prefix: 'ko'),
            new LanguageDto(name: '日本語', prefix: 'ja'),
        ];
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     */
    public function getTranslates(): array
    {
        return [
            'landing' => [
                'loginButtonText' => [
                    'ru' => 'Войти', 'en' => 'Login', 'kz' => 'Кіру',
                    'de' => 'Anmelden', 'kg' => 'Кирүү', 'uz' => 'Kirish',
                    'ko' => '로그인', 'ja' => 'ログイン',
                ],
                'registerButtonText' => [
                    'ru' => 'Регистрация', 'en' => 'Register', 'kz' => 'Тіркелу',
                    'de' => 'Registrieren', 'kg' => 'Катталуу', 'uz' => 'Roʻyxatdan oʻtish',
                    'ko' => '회원가입', 'ja' => '登録する',
                ],
                'logoHeadText' => [
                    'ru' => 'Развивайся на 1% каждый день', 'en' => 'Grow 1% every day', 'kz' => 'Күн сайын 1% дамы',
                    'de' => 'Wachse jeden Tag um 1 %', 'kg' => 'Ар күнү 1 % өс', 'uz' => 'Har kuni 1 % o‘s',
                    'ko' => '매일 1% 성장하세요', 'ja' => '毎日1％成長しよう',
                ],

                'boldText' => [
                    'ru' => 'Повышай свою продуктивность',
                    'en' => 'Boost your productivity',
                    'kz' => 'Өнімділігіңді арттыр',
                    'de' => 'Steigere deine Produktivität',
                    'kg' => 'Өндүрүмдүүлүгүңдү жогорулат',
                    'uz' => 'Mahsuldorligingni oshir',
                    'ko' => '생산성을 높이세요',
                    'ja' => '生産性を高めよう',
                ],


                'logoDescText' => [
                    'ru' => 'Таймер Pomodoro, список привычек и матрица Эйзенхауэра — всё в одном месте',
                    'en' => 'Pomodoro timer, habit list, and Eisenhower matrix—all in one place',
                    'kz' => 'Pomodoro таймері, әдеттер тізімі және Айзенхауэр матрицасы — барлығы бір жерде',
                    'de' => 'Pomodoro-Timer, Gewohnheitsliste und Eisenhower-Matrix – alles in einem',
                    'kg' => 'Помодоро таймери, адаттар тизмеси жана Эйзенхауэр матрицасы — баары бир жерде',
                    'uz' => 'Pomodoro taymeri, odatlar ro‘yxati va Ayzenhower matritsasi — hammasi bir joyda',
                    'ko' => '포모도로 타이머, 습관 목록, 아이젠하워 매트릭스 — 하나의 앱에서',
                    'ja' => 'ポモドーロタイマー、習慣リスト、アイゼンハワーマトリックスを一つに',
                ],
                'beginButtonText' => [
                    'ru' => 'Начать сейчас', 'en' => 'Start now', 'kz' => 'Қазір бастаңыз',
                    'de' => 'Jetzt starten', 'kg' => 'Азыр баштаңыз', 'uz' => 'Hozir boshlang',
                    'ko' => '지금 시작하기', 'ja' => '今すぐ始める',
                ],
                'feedbackText' => [
                    'ru' => 'Отзывы наших пользователей', 'en' => 'User feedback', 'kz' => 'Пайдаланушылар пікірлері',
                    'de' => 'Nutzer-Feedback', 'kg' => 'Колдонуучулардын пикирлери', 'uz' => 'Foydalanuvchilar fikri',
                    'ko' => '사용자 피드백', 'ja' => 'ユーザーフィードバック',
                ],
                'privacyPolicyText' => [
                    'ru' => 'Политика конфиденциальности', 'en' => 'Privacy Policy', 'kz' => 'Құпиялық саясаты',
                    'de' => 'Datenschutzrichtlinie', 'kg' => 'Купуялуулук саясаты', 'uz' => 'Maxfiylik siyosati',
                    'ko' => '개인정보 처리방침', 'ja' => 'プライバシーポリシー',
                ],
                'featuresText' => [
                    'ru' => 'Наши фичи', 'en' => 'Our features', 'kz' => 'Біздің функциялар',
                    'de' => 'Unsere Funktionen', 'kg' => 'Биздин функциялар', 'uz' => 'Bizning funksiyalar',
                    'ko' => '기능 소개', 'ja' => '機能紹介',
                ],
                'plansTitle' => [
                    'ru' => 'Тарифы', 'en' => 'Plans', 'kz' => 'Жоспарлар',
                    'de' => 'Pläne', 'kg' => 'Тарифтер', 'uz' => 'Rejalar',
                    'ko' => '요금제', 'ja' => 'プラン',
                ],
                'plansSubTitle' => [
                    'ru' => 'Выбери подходящий план для своей продуктивности', 'en' => 'Choose the right plan for your productivity', 'kz' => 'Өнімділігіңізге сай жоспарды таңдаңыз',
                    'de' => 'Wähle den passenden Plan für deine Produktivität', 'kg' => 'Өндүрүмдүүлүгүңө ылайык план тандаңыз', 'uz' => 'Samaradorligingiz uchun mos rejani tanlang',
                    'ko' => '생산성에 맞는 요금제를 선택하세요', 'ja' => '生産性に合ったプランを選びましょう',
                ],
                'selectPlanText' => [
                    'ru' => 'Выбрать', 'en' => 'Choose', 'kz' => 'Таңдау',
                    'de' => 'Auswählen', 'kg' => 'Тандоо', 'uz' => 'Tanlash', 'ko' => '선택', 'ja' => '選択',
                ],
            ],




            'premium' => [
                'goToTasksButtonText' => [
                    'ru' => 'Перейти к задачам', 'en' => 'Go to tasks', 'kz' => 'Тапсырмаларға өту',
                    'de' => 'Zu Aufgaben', 'kg' => 'Тапшырмаларга өтүңүз', 'uz' => 'Vazifalarga oʻting', 'ko' => '작업으로 이동', 'ja' => 'タスクへ',
                ],
                'HeadText' => [
                    'ru' => 'Увеличьте вашу продуктивность с TaskFlow PremiumUseCases', 'en' => 'Boost your productivity with TaskFlow PremiumUseCases',
                    'kz' => 'TaskFlow PremiumUseCases арқылы өнімділігіңізді арттырыңыз',
                    'de' => 'Steigere deine Produktivität mit TaskFlow PremiumUseCases', 'kg' => 'TaskFlow PremiumUseCases сиздин өндүрүмдүүлүгүңүздү жогорулатат',
                    'uz' => 'TaskFlow PremiumUseCases bilan samaradorlikni oshiring', 'ko' => 'TaskFlow Premium으로 생산성을 높이세요', 'ja' => 'TaskFlow Premiumで生産性を高めましょう',
                ],
                'DescText' => [
                    'ru' => 'Откройте все премиум-функции и наслаждайтесь организованной жизнью.', 'en' => 'Unlock all premium features and enjoy an organized life.',
                    'kz' => 'Барлық премиум функцияларды ашып, жүйелі өмірдің рақатын көріңіз.',
                    'de' => 'Schalte alle PremiumUseCases-Funktionen frei und genieße ein organisiertes Leben.',
                    'kg' => 'Бардык премиум функцияларды ачып, уюшкан жашоонун ырахатын көрүңүз.',
                    'uz' => 'Barcha premium funksiyalarni ochib, tartibli hayotdan bahramand bo‘ling.',
                    'ko' => '모든 프리미엄 기능을 잠금 해제하고 정돈된 생활을 즐기세요.', 'ja' => 'すべてのプレミアム 기능을開放し、整理された生活を楽しみましょう。',
                ],
                'faqTitle' => [
                    'ru' => 'Вопросы и ответы', 'en' => 'FAQ', 'kz' => 'Жиі қойылатын сұрақтар',
                    'de' => 'FAQ', 'kg' => 'Көп берилүүчү суроолор', 'uz' => 'Ko‘p so‘raladigan savollar', 'ko' => '자주 묻는 질문', 'ja' => 'よくある質問',
                ],
                'benefitsTitle' => [
                    'ru' => 'Что даёт премиум?', 'en' => 'What does premium offer?', 'kz' => 'Премиум қандай артықшылықтар береді?',
                    'de' => 'Was bietet PremiumUseCases?', 'kg' => 'Премиум эмнени берет?', 'uz' => 'PremiumUseCases qanday afzalliklar beradi?', 'ko' => '프리미엄이 제공하는 이점은 무엇인가요?', 'ja' => 'プレミアムのメリットは何ですか？',
                ],
                'readyQuestion' => [
                    'ru' => 'Готовы достичь большего с PremiumUseCases?',
                    'en' => 'Ready to achieve more with PremiumUseCases?',
                    'kz' => 'PremiumUseCases арқылы үлкен жетістікке жетуге дайынсыз ба?',
                    'de' => 'Bereit, mit PremiumUseCases mehr zu erreichen?',
                    'kg' => 'PremiumUseCases менен чоң жетишкендиктерге жетүүгө даярсызбы?',
                    'uz' => 'PremiumUseCases bilan katta yutuqlarga erishishga tayyormisiz?',
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
            ],



            'login' => [
                'headText' => [
                    'ru' => 'С возвращением 👋', 'en' => 'Welcome back 👋', 'kz' => 'Қайта қош келдіңіз 👋',
                    'de' => 'Willkommen zurück 👋', 'kg' => 'Кайра кош келдиңиз 👋', 'uz' => 'Xush kelibsiz 👋',
                    'ko' => '다시 오신 걸 환영합니다 👋', 'ja' => 'おかえりなさい 👋',
                ],
                'headPText' => [
                    'ru' => 'Войдите, чтобы продолжить', 'en' => 'Login to continue', 'kz' => 'Жалғастыру үшін кіріңіз',
                    'de' => 'Melden Sie sich an, um fortzufahren', 'kg' => 'Улантуу үчүн кириңиз', 'uz' => 'Davom etish uchun kiring',
                    'ko' => '계속하려면 로그인하세요', 'ja' => '続けるにはログインしてください',
                ],
                'emailLabelText' => [
                    'ru' => 'Почта', 'en' => 'Email', 'kz' => 'Электрондық пошта',
                    'de' => 'E‑Mail', 'kg' => 'Электрондук почта', 'uz' => 'Elektron pochta', 'ko' => '이메일', 'ja' => 'メール',
                ],
                'passwordLabelText' => [
                    'ru' => 'Пароль', 'en' => 'Password', 'kz' => 'Құпия сөз', 'de' => 'Passwort', 'kg' => 'Сырсөз', 'uz' => 'Parol', 'ко' => '비밀번호', 'ja' => 'パスワード',
                ],
                'loginWithGoogleButton' => [
                    'ru' => 'Войти через Google', 'en' => 'Login with Google', 'kz' => 'Google арқылы кіру',
                    'de' => 'Mit Google anmelden', 'kg' => 'Google аркылуу кирүү', 'uz' => 'Google orqali kirish', 'ko' => 'Google로 로그인', 'ja' => 'Googleでログイン',
                ],
                'notHaveAccount' => [
                    'ru' => 'У вас нет аккаунта? Зарегистрируйтесь', 'en' => 'Don’t have an account? Register', 'kz' => 'Аккаунтыңыз жоқ па? Тіркеліңіз',
                    'de' => 'Noch keinen Account? Registrieren', 'kg' => 'Каттоо жокпу? Каттал', 'uz' => 'Hisobsizmisiz? Roʻyxatdan oʻting', 'ko' => '계정이 없으신가요? 회원가입', 'ja' => 'アカウントをお持ちでないですか？登録',
                ],
                'invalidCredentials' => [
                    'ru' => 'Неверный логин или пароль', 'en' => 'Invalid login or password', 'kz' => 'Логин немесе құпия сөз қате енгізілді',
                    'de' => 'Ungültiger Login oder Passwort', 'kg' => 'Туура эмес логин же сырсөз', 'uz' => 'Notoʻgʻri login yoki parol', 'ko' => '잘못된 로그인 또는 비밀번호', 'ja' => 'ログインまたはパスワードが無効です',
                ],
                'loginButton' => [
                    'ru' => 'Войти',
                    'en' => 'Login',
                    'kz' => 'Кіру',
                    'de' => 'Anmelden',
                    'kg' => 'Кирүү',
                    'uz' => 'Kirish',
                    'ko' => '로그인',
                    'ja' => 'ログイン',
                ],

            ],

            'register' => [
                'registerHeadText' => [
                    'ru' => 'Добро пожаловать 👋',
                    'en' => 'Welcome 👋',
                    'kz' => 'Қош келдіңіз 👋',
                    'de' => 'Willkommen 👋',
                    'kg' => 'Кош келиңиз 👋',
                    'uz' => 'Xush kelibsiz 👋',
                    'ko' => '환영합니다 👋',
                    'ja' => 'ようこそ 👋',
                ],
                'headDescRegister' => [
                    'ru' => 'Создайте аккаунт, чтобы стать ближе к своей цели',
                    'en' => 'Create an account to get closer to your goal',
                    'kz' => 'Мақсатыңызға жақындау үшін тіркеліңіз',
                    'de' => 'Erstellen Sie ein Konto, um Ihrem Ziel näher zu kommen',
                    'kg' => 'Максатыңызга жакындоо үчүн катталыңыз',
                    'uz' => 'Maqsadingizga yaqinlashish uchun hisob yarating',
                    'ko' => '목표에 더 가까워지려면 계정을 만드세요',
                    'ja' => '目標に近づくためにアカウントを作成しましょう',
                ],
                'nameInputLabel' => [
                    'ru' => 'Ваше имя',
                    'en' => 'Your name',
                    'kz' => 'Атыңыз',
                    'de' => 'Ihr Name',
                    'kg' => 'Атыңыз',
                    'uz' => 'Ismingiz',
                    'ko' => '성함',
                    'ja' => 'お名前',
                ],
                'emailInputLabel' => [
                    'ru' => 'Ваша почта',
                    'en' => 'Your email',
                    'kz' => 'Электрондық поштаңыз',
                    'de' => 'Ihre E-Mail',
                    'kg' => 'Электрондук почтаңыз',
                    'uz' => 'Elektron pochtangiz',
                    'ko' => '이메일 주소',
                    'ja' => 'メールアドレス',
                ],
                'passwordInputLabel' => [
                    'ru' => 'Ваш пароль',
                    'en' => 'Your password',
                    'kz' => 'Құпия сөзіңіз',
                    'de' => 'Ihr Passwort',
                    'kg' => 'Сырсөзүңүз',
                    'uz' => 'Parolingiz',
                    'ko' => '비밀번호',
                    'ja' => 'パスワード',
                ],
                'registerButton' => [
                    'ru' => 'Зарегистрироваться',
                    'en' => 'Register',
                    'kz' => 'Тіркелу',
                    'de' => 'Registrieren',
                    'kg' => 'Катталуу',
                    'uz' => 'Roʻyxatdan oʻtish',
                    'ko' => '회원가입',
                    'ja' => '登録する',
                ],
                'haveAccount' => [
                    'ru' => 'У вас уже есть аккаунт? Войти',
                    'en' => 'Already have an account? Login',
                    'kz' => 'Есептік жазбаңыз бар ма? Кіру',
                    'de' => 'Sie haben bereits ein Konto? Anmelden',
                    'kg' => 'Каттооңуз барбы? Кирүү',
                    'uz' => 'Hisobingiz bormi? Kirish',
                    'ko' => '이미 계정이 있습니까? 로그인',
                    'ja' => '既にアカウントをお持ちですか？ ログイン',
                ],
            ],



            'buttons' => [
                'AllButton' => [
                    'ru' => 'Все', 'en' => 'All', 'kz' => 'Барлығы',
                    'de' => 'Alle', 'kg' => 'Баары', 'uz' => 'Barchasi',
                    'ko' => '모두', 'ja' => 'すべて',
                ],
                'TodayButton' => [
                    'ru' => 'Сегодня', 'en' => 'Today', 'kz' => 'Бүгін',
                    'de' => 'Heute', 'kg' => 'Бүгүн', 'uz' => 'Bugun',
                    'ko' => '오늘', 'ja' => '今日',
                ],
                'TomorowButton' => [
                    'ru' => 'Завтра', 'en' => 'Tomorrow', 'kz' => 'Ертең',
                    'de' => 'Morgen', 'kg' => 'Эртең', 'uz' => 'Ertaga',
                    'ko' => '내일', 'ja' => '明日',
                ],
                'WeekButton' => ['ru' => 'Неделя', 'en' => 'Week', 'kz' => 'Апта',
                    'de' => 'Woche', 'kg' => 'Жума', 'uz' => 'Hafta',
                    'ko' => '주', 'ja' => '週',
                ],
                'MonthButton' => ['ru' => 'Месяц', 'en' => 'Month', 'kz' => 'Ай',
                    'de' => 'Monat', 'kg' => 'Ай', 'uz' => 'Oy',
                    'ko' => '월', 'ja' => '月',
                ],
                'addButton' => [
                    'ru' => 'Добавить', 'en' => 'Add', 'kz' => 'Қосу',
                    'de' => 'Hinzufügen', 'kg' => 'Кошуу', 'uz' => 'Qo‘shish',
                    'ko' => '추가', 'ja' => '追加',
                ],
                'EveryDay' => [
                    'ru' => 'Ежедневно', 'en' => 'Daily', 'kz' => 'Күн сайын',
                    'de' => 'Täglich', 'kg' => 'Күн сайын', 'uz' => 'Har kuni',
                    'ko' => '매일', 'ja' => '毎日',
                ],
                'EveryWeek' => [
                    'ru' => 'Еженедельно', 'en' => 'Weekly', 'kz' => 'Апта сайын',
                    'de' => 'Wöchentlich', 'kg' => 'Апта сайын', 'uz' => 'Haftada bir',
                    'ko' => '매주', 'ja' => '毎週',
                ],
                'EveryMonth' => [
                    'ru' => 'Ежемесячно', 'en' => 'Monthly', 'kz' => 'Ай сайын',
                    'de' => 'Monatlich', 'kg' => 'Ай сайын', 'uz' => 'Oyda bir',
                    'ko' => '매월', 'ja' => '毎月',
                ],
                'EveryYear' => [
                    'ru' => 'Ежегодно', 'en' => 'Yearly', 'kz' => 'Жыл сайын',
                    'de' => 'Jährlich', 'kg' => 'Жыл сайын', 'uz' => 'Yilda bir',
                    'ko' => '매년', 'ja' => '毎年',
                ],
                'Never' => [
                    'ru' => 'Никогда', 'en' => 'Never', 'kz' => 'Ешқашан',
                    'de' => 'Niemals', 'kg' => 'Эч качан', 'uz' => 'Hech qachon',
                    'ko' => '절대', 'ja' => '決して',
                ],
                'Description' => [
                    'ru' => 'Описание', 'en' => 'Description', 'kz' => 'Сипаттама',
                    'de' => 'Beschreibung', 'kg' => 'Сүрөттөмө', 'uz' => 'Tavsif',
                    'ko' => '설명', 'ja' => '説明',
                ],
                'ChooseTime' => [
                    'ru' => 'Выберите время',
                    'en' => 'Choose time',
                    'kz' => 'Уақытты таңдаңыз',
                    'de' => 'Zeit auswählen',
                    'kg' => 'Убакытты тандаңыз',
                    'uz' => 'Vaqtni tanlang',
                    'ko' => '시간을 선택하세요',
                    'ja' => '時間を選択してください',
                ],
                'deleteButton' => [
                    'ru' => 'Удалить',
                    'en' => 'Delete',
                    'kz' => 'Жою',
                    'de' => 'Löschen',
                    'kg' => 'Өчүрүү',
                    'uz' => 'Oʻchirish',
                    'ko' => '삭제',
                    'ja' => '削除する',
                ],

            ],



            'month' => [
                'January' => [
                    'ru' => 'Январь', 'en' => 'January', 'kz' => 'Қаңтар',
                    'de' => 'Januar', 'kg' => 'Январь', 'uz' => 'Yanvar',
                    'ko' => '1월', 'ja' => '1月',
                ],
                'February' => [
                    'ru' => 'Февраль', 'en' => 'February', 'kz' => 'Ақпан',
                    'de' => 'Februar', 'kg' => 'Февраль', 'uz' => 'Fevral',
                    'ko' => '2월', 'ja' => '2月',
                ],
                'March' => [
                    'ru' => 'Март', 'en' => 'March', 'kz' => 'Наурыз',
                    'de' => 'März', 'kg' => 'Март', 'uz' => 'Mart',
                    'ko' => '3월', 'ja' => '3月',
                ],
                'April' => [
                    'ru' => 'Апрель', 'en' => 'April', 'kz' => 'Сәуір',
                    'de' => 'April', 'kg' => 'Апрель', 'uz' => 'Aprel',
                    'ko' => '4월', 'ja' => '4月',
                ],
                'May' => [
                    'ru' => 'Май', 'en' => 'May', 'kz' => 'Мамыр',
                    'de' => 'Mai', 'kg' => 'Май', 'uz' => 'May',
                    'ko' => '5월', 'ja' => '5月',
                ],
                'June' => [
                    'ru' => 'Июнь', 'en' => 'June', 'kz' => 'Маусым',
                    'de' => 'Juni', 'kg' => 'Июнь', 'uz' => 'Iyun',
                    'ko' => '6월', 'ja' => '6月',
                ],
                'July' => [
                    'ru' => 'Июль', 'en' => 'July', 'kz' => 'Шілде',
                    'de' => 'Juli', 'kg' => 'Июль', 'uz' => 'Iyul',
                    'ko' => '7월', 'ja' => '7月',
                ],
                'August' => [
                    'ru' => 'Август', 'en' => 'August', 'kz' => 'Тамыз',
                    'de' => 'August', 'kg' => 'Август', 'uz' => 'Avgust',
                    'ko' => '8월', 'ja' => '8月',
                ],
                'September' => [
                    'ru' => 'Сентябрь', 'en' => 'September', 'kz' => 'Қыркүйек',
                    'de' => 'September', 'kg' => 'Сентябрь', 'uz' => 'Sentyabr',
                    'ko' => '9월', 'ja' => '9月',
                ],
                'October' => [
                    'ru' => 'Октябрь', 'en' => 'October', 'kz' => 'Қазан',
                    'de' => 'Oktober', 'kg' => 'Октябрь', 'uz' => 'Oktyabr',
                    'ko' => '10월', 'ja' => '10月',
                ],
                'November' => [
                    'ru' => 'Ноябрь', 'en' => 'November', 'kz' => 'Қараша',
                    'de' => 'November', 'kg' => 'Ноябрь', 'uz' => 'Noyabr',
                    'ko' => '11월', 'ja' => '11月',
                ],
                'December' => [
                    'ru' => 'Декабрь', 'en' => 'December', 'kz' => 'Желтоқсан',
                    'de' => 'Dezember', 'kg' => 'Декабрь', 'uz' => 'Dekabr',
                    'ko' => '12월', 'ja' => '12月',
                ],
            ],



            'tasks' => [
                'tasksHeadText' => [
                    'ru' => 'Задачи', 'en' => 'tasks', 'kz' => 'Тапсырмалар',
                    'de' => 'Aufgaben', 'kg' => 'Тапшырмалар', 'uz' => 'Vazifalar',
                    'ko' => '작업', 'ja' => 'タスク',
                ],
                'wantToDo' => [
                    'ru' => 'Что запланируем сегодня?', 'en' => 'What shall we plan today?', 'kz' => 'Бүгін не жоспарлаймыз?',
                    'de' => 'Was wollen wir heute planen?', 'kg' => 'Бүгүн эмне пландайбыз?', 'uz' => 'Bugun nima rejalashtiramiz?',
                    'ko' => '오늘은 무엇을 계획할까요?', 'ja' => '今日は何を計画しましょうか？',
                ],
                'confirmTasks' => [
                    'ru' => 'Отлично! Задача выполнена, ты молодец',
                    'en' => 'Great! The task is completed, well done',
                    'kz' => 'Керемет! Тапсырма орындалды',
                    'de' => 'Super! Die Aufgabe ist erledigt, gut gemacht',
                    'kg' => 'Ооба! Тапшырма аткарылды',
                    'uz' => 'Ajoyib! Vazifa bajarildi',
                    'ko' => '잘했어요! 작업이 완료되었습니다',
                    'ja' => '素晴らしい！タスクが完了しました',
                ],

                'unConfirmTasks' => [
                    'ru' => 'Задача отменена. Ничего страшного, в следующий раз получится',
                    'en' => 'The task was cancelled. No worries, you’ll succeed next time',
                    'kz' => 'Тапсырма тоқтатылды. Қорықпаңыз, келесі жолы сәтті болады',
                    'de' => 'Die Aufgabe wurde abgebrochen. Kein Problem, beim nächsten Mal klappt es',
                    'kg' => 'Тапшырма токтотулду. Тынч болуңуз, кийинки жолу ийгиликтүү болот',
                    'uz' => 'Vazifa bekor qilindi. Tashvishlanmang, keyingi safar muvaffaqiyatli bo\'lasiz',
                    'ko' => '작업이 취소되었습니다. 걱정 마세요, 다음 번에는 성공할 거예요',
                    'ja' => 'タスクはキャンセルされました。心配しないでください。次回はうまくいきますよ',
                ],

                'createTasksSuccss' => [
                    'ru' => 'Задача успешно создана',
                    'en' => 'The task was created successfully',
                    'kz' => 'Тапсырма сәтті құрылды',
                    'de' => 'Die Aufgabe wurde erfolgreich erstellt',
                    'kg' => 'Тапшырма ийгиликтүү түзүлдү',
                    'uz' => 'Vazifa muvaffaqiyatli yaratildi',
                    'ko' => '작업이 성공적으로 생성되었습니다',
                    'ja' => 'タスクが正常に作成されました',
                ],

                'deleteTasksSuccss' => [
                    'ru' => 'Задача успешно удалена',
                    'en' => 'The task was deleted successfully',
                    'kz' => 'Тапсырма сәтті жойылды',
                    'de' => 'Die Aufgabe wurde erfolgreich gelöscht',
                    'kg' => 'Тапшырма ийгиликтүү өчүрүлдү',
                    'uz' => 'Vazifa muvaffaqiyatli o\'chirildi',
                    'ko' => '작업이 성공적으로 삭제되었습니다',
                    'ja' => 'タスクが正常に削除されました',
                ],

                'questionWantDoTasks' => [
                    'ru' => '+ Хотите добавить задачу',
                    'en' => '+ Want to add a task',
                    'kz' => '+ Тапсырма қосқыңыз келе ме',
                    'de' => '+ Möchten Sie eine Aufgabe hinzufügen',
                    'kg' => '+ Тапшырма кошкуңуз келеби',
                    'uz' => '+ Vazifa qo‘shmoqchimisiz',
                    'ko' => '+ 작업을 추가하시겠습니까',
                    'ja' => '+ タスクを追加しますか',
                ],
                'mistakeGetAllTasks' => [
                    'ru' => 'Ошибка получения всех задач',
                    'en' => 'Error fetching all tasks',
                    'kz' => 'Барлық тапсырмаларды алу кезінде қате пайда болды',
                    'de' => 'Fehler beim Abrufen aller Aufgaben',
                    'kg' => 'Бардык тапшырмаларды алуу учурунда ката кетти',
                    'uz' => 'Barcha vazifalarni olishda xato yuz berdi',
                    'ko' => '모든 작업을 가져오는 중 오류 발생',
                    'ja' => 'すべてのタスクを取得中にエラーが発生しました',
                ],
            ],
            'pomodoro' => [
                'pomodoroHeadText' => [
                    'ru' => 'Помодоро/фокус',
                    'en' => 'Pomodoro/Focus',
                    'kz' => 'Помодоро/фокус',
                    'de' => 'Pomodoro/Fokus',
                    'kg' => 'Помодоро/Фокус',
                    'uz' => 'Pomodoro/Diqqat',
                    'ko' => '포모도로/집중',
                    'ja' => 'ポモドーロ/集中',
                ],
            ],


            'habits' => [
                'habitsHeadText' => [
                    'ru' => 'Привычки',
                    'en' => 'habits',
                    'kz' => 'Әдеттер',
                    'de' => 'Gewohnheiten',
                    'kg' => 'Адатар',
                    'uz' => 'Odatlar',
                    'ko' => '습관',
                    'ja' => '習慣',
                ],

                'morning' => [
                    'ru' => 'Утро',
                    'en' => 'Morning',
                    'kz' => 'Таңғы',
                    'de' => 'Morgen',
                    'kg' => 'Таң',
                    'uz' => 'Ertalab',
                    'ko' => '아침',
                    'ja' => '朝',
                ],

                'night' => [
                    'ru' => 'Ночь',
                    'en' => 'Night',
                    'kz' => 'Түн',
                    'de' => 'Nacht',
                    'kg' => 'Кеч',
                    'uz' => 'Kecha',
                    'ko' => '밤',
                    'ja' => '夜',
                ],

                'midnight' => [
                    'ru' => 'Полдень',
                    'en' => 'Midnight',
                    'kz' => 'Түскі уақыт',
                    'de' => 'Mitternacht',
                    'kg' => 'Түштөн кийинки',
                    'uz' => 'O‘rta kechasi',
                    'ko' => '자정',
                    'ja' => '真夜中',
                ],
            ],



            'week_short' => [
                'mon' => [
                    'ru' => 'Пн',
                    'en' => 'Mo',
                    'kz' => 'Дс',
                    'de' => 'Mo',
                    'kg' => 'Дш',
                    'uz' => 'Du',
                    'ko' => '월',
                    'ja' => '月',
                ],
                'tue' => [
                    'ru' => 'Вт',
                    'en' => 'Tu',
                    'kz' => 'Сс',
                    'de' => 'Di',
                    'kg' => 'Шш',
                    'uz' => 'Se',
                    'ko' => '화',
                    'ja' => '火',
                ],
                'wed' => [
                    'ru' => 'Ср',
                    'en' => 'We',
                    'kz' => 'Ср',
                    'de' => 'Mi',
                    'kg' => 'Шр',
                    'uz' => 'Ch',
                    'ko' => '수',
                    'ja' => '水',
                ],
                'thu' => [
                    'ru' => 'Чт',
                    'en' => 'Th',
                    'kz' => 'Бс',
                    'de' => 'Do',
                    'kg' => 'Бш',
                    'uz' => 'Pa',
                    'ko' => '목',
                    'ja' => '木',
                ],
                'fri' => [
                    'ru' => 'Пт',
                    'en' => 'Fr',
                    'kz' => 'Жм',
                    'de' => 'Fr',
                    'kg' => 'Жм',
                    'uz' => 'Ju',
                    'ko' => '금',
                    'ja' => '金',
                ],
                'sat' => [
                    'ru' => 'Сб',
                    'en' => 'Sa',
                    'kz' => 'Сн',
                    'de' => 'Sa',
                    'kg' => 'Иш',
                    'uz' => 'Sh',
                    'ko' => '토',
                    'ja' => '土',
                ],
                'sun' => [
                    'ru' => 'Вс',
                    'en' => 'Su',
                    'kz' => 'Жс',
                    'de' => 'So',
                    'kg' => 'Жк',
                    'uz' => 'Ya',
                    'ko' => '일',
                    'ja' => '日',
                ],
            ],



            'statistic' => [
                'all_tracking_days' => [
                    'ru' => 'Общее количество дней',
                    'en' => 'Total days tracked',
                    'kz' => 'Жалпы күндер саны',
                    'de' => 'Gesamte Anzahl der Tage',
                    'kg' => 'Жалпы күндөрдүн саны',
                    'uz' => 'Umumiy kunlar soni',
                    'ko' => '총 일수',
                    'ja' => '合計日数',
                ],
                'all_tracking_count' => [
                    'ru' => 'Общее количество',
                    'en' => 'Total count',
                    'kz' => 'Жалпы саны',
                    'de' => 'Gesamtanzahl',
                    'kg' => 'Жалпы саны',
                    'uz' => 'Umumiy soni',
                    'ko' => '총 횟수',
                    'ja' => '合計数',
                ],
                'tracking_week' => [
                    'ru' => 'Количество за неделю',
                    'en' => 'Count for the week',
                    'kz' => 'Апта бойынша саны',
                    'de' => 'Anzahl für die Woche',
                    'kg' => 'Апта боюнча саны',
                    'uz' => 'Haftalik hisob',
                    'ko' => '이번 주 횟수',
                    'ja' => '今週の数',
                ],
                'tracking_today' => [
                    'ru' => 'Количество за сегодня',
                    'en' => 'Count for today',
                    'kz' => 'Бүгінгі саны',
                    'de' => 'Anzahl für heute',
                    'kg' => 'Бүгүнкү саны',
                    'uz' => 'Bugungi hisob',
                    'ko' => '오늘 횟수',
                    'ja' => '今日の数',
                ],
            ],


            'week' => [
                'mon' => [
                    'ru' => 'Понедельник',
                    'en' => 'Monday',
                    'kz' => 'Дүйсенбі',
                    'de' => 'Montag',
                    'kg' => 'Дүйшөмбү',
                    'uz' => 'Dushanba',
                    'ko' => '월요일',
                    'ja' => '月曜日',
                ],
                'tue' => [
                    'ru' => 'Вторник',
                    'en' => 'Tuesday',
                    'kz' => 'Сейсенбі',
                    'de' => 'Dienstag',
                    'kg' => 'Шейшемби',
                    'uz' => 'Seshanba',
                    'ko' => '화요일',
                    'ja' => '火曜日',
                ],
                'wed' => [
                    'ru' => 'Среда',
                    'en' => 'Wednesday',
                    'kz' => 'Сәрсенбі',
                    'de' => 'Mittwoch',
                    'kg' => 'Шаршемби',
                    'uz' => 'Chorshanba',
                    'ko' => '수요일',
                    'ja' => '水曜日',
                ],
                'thu' => [
                    'ru' => 'Четверг',
                    'en' => 'Thursday',
                    'kz' => 'Бейсенбі',
                    'de' => 'Donnerstag',
                    'kg' => 'Бейшемби',
                    'uz' => 'Payshanba',
                    'ko' => '목요일',
                    'ja' => '木曜日',
                ],
                'fri' => [
                    'ru' => 'Пятница',
                    'en' => 'Friday',
                    'kz' => 'Жұма',
                    'de' => 'Freitag',
                    'kg' => 'Жума',
                    'uz' => 'Juma',
                    'ko' => '금요일',
                    'ja' => '金曜日',
                ],
                'sat' => [
                    'ru' => 'Суббота',
                    'en' => 'Saturday',
                    'kz' => 'Сенбі',
                    'de' => 'Samstag',
                    'kg' => 'Ишемби',
                    'uz' => 'Shanba',
                    'ko' => '토요일',
                    'ja' => '土曜日',
                ],
                'sun' => [
                    'ru' => 'Воскресенье',
                    'en' => 'Sunday',
                    'kz' => 'Жексенбі',
                    'de' => 'Sonntag',
                    'kg' => 'Жекшемби',
                    'uz' => 'Yakshanba',
                    'ko' => '일요일',
                    'ja' => '日曜日',
                ],
            ],

        ];
    }

    /**
     * @return PremiumPlansDto[]
     */
    public function getPremiumPlans(): array
    {
        return [
            new PremiumPlansDto(
                name: 'Free',
                desc: 'Базовые функции для старта',
                price: '0₸ / мес',
                features: ['20 задач в день', '5 привычек', 'Матрица Эйзенхауэра', 'Помодоро', 'Базовый Кастомный фон'],
                highlight: true,
            ),
            new PremiumPlansDto(
                name: 'Pro',
                desc: 'Для продуктивной работы',
                price: '999₸ / мес',
                features: [
                    '50 задач в день',
                    '50 привычек',
                    'Кастомный фон',
                    'ИИ-помощник',
                    'канбан',
                    'Больше звуков для помодора',
                    'полный Кастомный фон и возможность добавлять свой фоны',
                ],
                highlight: true,
            ),
            new PremiumPlansDto(
                name: 'Pro Год',
                desc: 'Экономия и приоритет',
                price: '9 999₸ / год',
                features: ['Всё из Pro', 'Приоритетная поддержка'],
                highlight: true,
            ),
        ];
    }

    /**
     * @return HabitsTemplatesDto[]
     */
    public function getHabitsTemplates(): array
    {
        return [
            new HabitsTemplatesDto(
                title: 'Ранний подъем',
                quote: 'Кто рано встает, тому Бог дает!',
                notification: '06:30',
                datesType: 'daily',
            ),
            new HabitsTemplatesDto(
                title: 'Утренняя пробежка',
                quote: 'Бег - это жизнь!',
                notification: '10:30',
                datesType: 'daily',
            ),
            new HabitsTemplatesDto(
                title: 'Чтение книги',
                quote: 'Книги - корабли мысли',
                notification: '16:30',
                datesType: 'daily',
            ),
            new HabitsTemplatesDto(
                title: 'Медитация',
                quote: 'Тишина - великий учитель',
                notification: '14:30',
                datesType: 'daily',
            ),
        ];
    }

    /**
     * @return FaqDto[]
     */
    public function getFaq(): array
    {
        return [
            new FaqDto(
                question: 'Можно ли вернуть деньги после покупки?',
                answer: 'Да, вы можете оформить возврат в течение 14 дней после оплаты. Просто отправьте нам запрос через раздел "Обратная связь" в приложении. Если вы оформили подписку через App Store или Google Play, отмену и возврат необходимо оформить через соответствующую платформу.',
            ),
            new FaqDto(
                question: 'Что будет с моими данными, если я не продлю подписку?',
                answer: 'Мы не удалим ваши привычки, задачи и статистику. Всё останется сохранено. Если количество привычек превышает лимит бесплатной версии, привычки с низким приоритетом будут автоматически перемещены в архив. Доступ к премиум-функциям будет временно ограничен до продления подписки.',
            ),
            new FaqDto(
                question: 'Как отменить подписку?',
                answer: 'Если вы оформили подписку через Google Play или App Store — откройте соответствующее приложение, перейдите в раздел "Подписки" и отмените TaskFlow PremiumUseCases. Если подписка оформлена напрямую, просто напишите нам через "Обратная связь".',
            ),
            new FaqDto(
                question: 'Что делать, если остались вопросы?',
                answer: 'Напишите нам в поддержку через приложение или на почту support@taskflow.app. Мы всегда готовы помочь!',
            ),
        ];
    }

    /**
     * @return BenefitsDto[]
     */
    public function getBenefits(): array
    {
        return [
            new BenefitsDto(
                title: 'ИИ помогает достигать целей',
                desc: 'Интеллектуальный помощник подсказывает приоритеты и экономит время',
                icon: '/icons/ai.svg',
            ),
            new BenefitsDto(
                title: '50 привычек и задач в день',
                desc: 'Достаточно, чтобы охватить все сферы жизни',
                icon: '/icons/checklist.svg',
            ),
            new BenefitsDto(
                title: 'Кастомный календарь',
                desc: 'Гибкий инструмент под твоё расписание и стиль',
                icon: '/icons/calendar.svg',
            ),
            new BenefitsDto(
                title: 'Система рейтингов',
                desc: 'Соревнуйся с собой и другими',
                icon: '/icons/rating.svg',
            ),
            new BenefitsDto(
                title: 'Темы и настройка',
                desc: 'Темная/светлая тема, фоны, звуки и многое другое',
                icon: '/icons/theme.svg',
            ),
            new BenefitsDto(
                title: 'Уведомления и напоминания',
                desc: 'Никогда не пропусти важную задачу',
                icon: '/icons/bell.svg',
            ),
        ];
    }
}
