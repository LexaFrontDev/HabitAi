import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { NavLink, Link } from 'react-router-dom';
import { LangStorage } from '../../../Infrastructure/languageStorage/LangStorage';
import { LangStorageUseCase } from '../../../Aplication/UseCases/language/LangStorageUseCase';
import DynamicGridList from '../../../ui/organism/cards/DynamicGridList';
import TariffCard from '../../../ui/molecule/Cards/TariffCard';
import BenefitCard from '../../../ui/molecule/Cards/BenefitCard';
import FAQCard from '../../../ui/molecule/Cards/FAQCard';
import Copyright from '../../../ui/atoms/TextBlock/Copyright';
import { Button } from '../../../ui/atoms/button/Button';
import { TextBlock } from '../../../ui/atoms/TextBlock/TextBlock';
import {LanguageRequestUseCase} from "../../../Aplication/UseCases/language/LanguageRequestUseCase";
import {LanguageApi} from "../../../Infrastructure/request/Language/LanguageApi";
import Loading from "../../chunk/LoadingChunk/Loading";
import {PremiumPageProps} from "../../../ui/props/Premium/PremiumPageProps";
import {Plan} from "../../../ui/props/Premium/PlanType";
import {Benefit} from "../../../ui/props/Premium/BenefitType";
import {FAQ} from "../../../ui/props/Premium/FAQType";

const LangUseCase = new LanguageRequestUseCase(new LanguageApi());
const langStorage = new LangStorage();
const langUseCase = new LangStorageUseCase(langStorage);

const PremiumPage: React.FC<PremiumPageProps> = ({ isAuthenticated }) => {
    const [langCode, setLangCode] = useState<string>('en');
    const { t, i18n } = useTranslation('translation');
    const [translationsLoaded, setTranslationsLoaded] = useState<boolean>(false);

    useEffect(() => {
        const detectLang = async () => {
            try {
                const lang = await langUseCase.getLang();
                if (lang) {
                    setLangCode(lang);
                    await LangUseCase.getTranslations(lang);
                }
            } catch (err) {
                console.error(err);
            } finally {
                setTranslationsLoaded(true);
            }
        };
        detectLang();
    }, []);

    const plans: Plan[] = [
        {
            name: 'Free',
            desc: 'Базовые функции для старта',
            price: '0₸ / мес',
            features: ['20 задач в день', '5 привычек', 'Матрица Эйзенхауэра', 'Помодоро', 'Базовый Кастомный фон'],
            highlight: true,
        },
        {
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
        },
        {
            name: 'Pro Год',
            desc: 'Экономия и приоритет',
            price: '9 999₸ / год',
            features: ['Всё из Pro', 'Приоритетная поддержка'],
            highlight: true,
        },
    ];

    const benefits: Benefit[] = [
        {
            title: 'ИИ помогает достигать целей',
            desc: 'Интеллектуальный помощник подсказывает приоритеты и экономит время',
            icon: '/icons/ai.svg',
        },
        {
            title: '50 привычек и задач в день',
            desc: 'Достаточно, чтобы охватить все сферы жизни',
            icon: '/icons/checklist.svg',
        },
        {
            title: 'Кастомный календарь',
            desc: 'Гибкий инструмент под твоё расписание и стиль',
            icon: '/icons/calendar.svg',
        },
        {
            title: 'Система рейтингов',
            desc: 'Соревнуйся с собой и другими',
            icon: '/icons/rating.svg',
        },
        {
            title: 'Темы и настройка',
            desc: 'Темная/светлая тема, фоны, звуки и многое другое',
            icon: '/icons/theme.svg',
        },
        {
            title: 'Уведомления и напоминания',
            desc: 'Никогда не пропусти важную задачу',
            icon: '/icons/bell.svg',
        },
    ];

    const faqs: FAQ[] = [
        {
            question: 'Можно ли вернуть деньги после покупки?',
            answer:
                'Да, вы можете оформить возврат в течение 14 дней после оплаты. Просто отправьте нам запрос через раздел "Обратная связь" в приложении. Если вы оформили подписку через App Store или Google Play, отмену и возврат необходимо оформить через соответствующую платформу.',
        },
        {
            question: 'Что будет с моими данными, если я не продлю подписку?',
            answer:
                'Мы не удалим ваши привычки, задачи и статистику. Всё останется сохранено. Если количество привычек превышает лимит бесплатной версии, привычки с низким приоритетом будут автоматически перемещены в архив. Доступ к премиум-функциям будет временно ограничен до продления подписки.',
        },
        {
            question: 'Как отменить подписку?',
            answer:
                'Если вы оформили подписку через Google Play или App Store — откройте соответствующее приложение, перейдите в раздел "Подписки" и отмените TaskFlow PremiumUseCases. Если подписка оформлена напрямую, просто напишите нам через "Обратную связь".',
        },
        {
            question: 'Что делать, если остались вопросы?',
            answer:
                'Напишите нам в поддержку через приложение или на почту support@taskflow.app. Мы всегда готовы помочь!',
        },
    ];



    if (!translationsLoaded) return <Loading />;

    return (
        <div className="premium-page">
            {/* навигация */}
            <nav className="navbar-premium">
                <div className="navbar-container">
                    <Link className="navbar-logo" to="/">
                        <img src="/Upload/Images/AppIcons/TaskFlowLogo.svg" alt="TaskFlow" />
                    </Link>
                    <div className="navbar-links">
                        <Link className="navbar-link" to="/download">
                            Скачать
                        </Link>
                        <NavLink to="/premium" className={({ isActive }) => `navbar-link ${isActive ? 'active' : ''}`}>
                            Премиум
                        </NavLink>
                        {isAuthenticated ? (
                            <Link className="btn-tasks" to="/tasks">
                                {t('goToTasksButtonText') || 'Перейти к задачам'}
                            </Link>
                        ) : (
                            <>
                                <Link className="btn-login" to="/users/login">
                                    {t('loginButtonText') || 'Войти'}
                                </Link>
                                <Link className="btn-register" to="/users/register">
                                    {t('registerButtonText') || 'Регистрация'}
                                </Link>
                            </>
                        )}
                    </div>
                </div>
            </nav>

            {/* хедер */}
            <header className="premium-header text-center py-5">
                <TextBlock variant="h1" align="center" weight="bold" size="display-4" className="mb-3">
                    {t('HeadText') || 'Увеличьте вашу продуктивность с TaskFlow PremiumUseCases'}
                </TextBlock>
                <TextBlock variant="p" align="center" color="text-muted" size="lead" className="mb-4">
                    {t('DescText') || 'Разблокируйте все премиум-функции на всех платформах. Наслаждайтесь организованной жизнью в полной мере.'}
                </TextBlock>
                <Button as="link" to="/users/register" variant="subscribe">
                    Подписаться
                </Button>
            </header>

            {/* планы */}
            <section className="premium-plans py-5 cards-hidline">
                <div className="container">
                    <h2 className="text-center mb-3">{t('plansTitle') || 'Тарифы'}</h2>
                    <p className="text-center text-white-50 mb-4">
                        {t('plansSubTitle') || 'Простой и честный выбор без скрытых условий'}
                    </p>
                    <DynamicGridList items={plans} renderItem={(plan) => <TariffCard {...plan} />} layout="3-cols" />
                </div>
            </section>

            {/* преимущества */}
            <section className="premium-benefits-section">
                <h2 className="text-center mb-4">{t('benefitsTitle') || 'Что даёт премиум?'}</h2>
                <div className="benefits-wrapper p-4 bg-dark">
                    <DynamicGridList items={benefits} renderItem={(benefit) => <BenefitCard {...benefit} />} layout="3-cols" />
                </div>
            </section>

            {/* FAQ */}
            <section className="premium-faq py-5 bg-dark">
                <div className="container">
                    <h2 className="text-center mb-4">{t('faqTitle') || 'Вопросы и ответы'}</h2>
                    <div className="faq-list">
                        <DynamicGridList items={faqs} renderItem={(faq) => <FAQCard {...faq} />} layout="full-width" />
                    </div>
                </div>
            </section>

            {/* CTA */}
            <section className="premium-begin-section text-center py-5 bg-dark">
                <div className="container">
                    <h2 className="display-6 fw-semibold mb-4">
                        {t('readyQuestion') || 'Готовы ли вы достичь большего с PremiumUseCases?'}
                    </h2>
                    <Button as="link" to="/users/register" variant="subscribe">
                        {t('readyButton') || 'Вперёд'}
                    </Button>
                </div>
            </section>

            <footer className="premium-footer text-center py-3 border-top border-warning bg-dark text-white-50 small">
                <Copyright />
            </footer>
        </div>
    );
};

export default PremiumPage;