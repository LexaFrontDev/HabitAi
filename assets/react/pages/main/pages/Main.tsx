import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import Loading from '../../chunk/LoadingChunk/Loading';
import { LangStorageUseCase } from "../../../Services/language/LangStorageUseCase";
import { LangStorage } from "../../../Services/languageStorage/LangStorage";
import { Button } from "../../../ui/atoms/button/Button";
import { AppLink } from "../../../ui/atoms/link/AppLink";
import { ImageWrapper } from "../../../ui/atoms/Image/ImageWrapper";
import { TextBlock } from "../../../ui/atoms/TextBlock/TextBlock";
import DynamicGridList from "../../../ui/organism/cards/DynamicGridList";
import FeatureCard from "../../../ui/molecule/Cards/FeatureCard";
import ReviewCard from "../../../ui/molecule/Cards/ReviewCard";
import Copyright from "../../../ui/atoms/TextBlock/Copyright";
import Navbar from "../../../ui/organism/navbar/Navbar";
import LanguageSelect from "../../../ui/atoms/select/LanguageSelect";
import { LanguageRequestUseCase } from "../../../Services/language/LanguageRequestUseCase";
import {ReviewCardProps} from "../../../ui/props/CardProps/Review/ReviewCardProps";
import {FeatureCardProps} from "../../../ui/props/CardProps/Feature/FeatureCardProps";
import {LanguageApi} from "../../../Services/language/LanguageApi";



interface LandingData {
    features: FeatureCardProps[];
    reviews: ReviewCardProps[];
}

const currentPage = 'landing';
const langStorage = new LangStorage();
const langUseCase = new LangStorageUseCase(langStorage);
const languageApi = new LanguageRequestUseCase(new LanguageApi());

const Lending: React.FC = () => {
    const [data, setData] = useState<LandingData | null>(null);
    const [langCode, setLangCode] = useState<string>('en');
    const { t } = useTranslation();
    const [translationsLoaded, setTranslationsLoaded] = useState<boolean>(false);

    useEffect(() => {
        const detectLang = async () => {
            try {
                const lang = await langUseCase.getLang();
                if (lang) {
                    setLangCode(lang);
                    await languageApi.getTranslations(lang);
                }
            } catch (err) {
                console.error(err);
            } finally {
                setTranslationsLoaded(true);
            }
        };
        detectLang();
    }, []);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const res = await fetch('/api/landing/data');
                if (!res.ok) throw new Error(`Failed to fetch data: ${res.status}`);
                const json: LandingData = await res.json();
                setData(json);
            } catch (err) {
                console.error(err);
            }
        };
        fetchData();
    }, []);

    const changeLanguage = async (lang: string) => {
        try {
            await languageApi.getTranslations(lang);
            setLangCode(lang);
        } catch (err) {
            console.error(err);
        }
    };

    useEffect(() => {
        if (!data) return;

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('appear');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        const timer = setTimeout(() => {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach(el => observer.observe(el));
        }, 100);

        return () => {
            clearTimeout(timer);
            observer.disconnect();
        };
    }, [data]);

    if (!data || !translationsLoaded) return <Loading />;

    return (
        <div id="lending">
            <Navbar
                right={
                    <>
                        <LanguageSelect
                            langCode={langCode}
                            setLangCode={setLangCode}
                            loadPageTranslation={changeLanguage}
                            currentPage={currentPage}
                        />
                        <AppLink to="/download" variant="nav">Скачать</AppLink>
                        <AppLink to="/premium" variant="nav">Премиум</AppLink>
                        <Button as="link" to="/users/login" variant="login">{t('landing.loginButtonText')}</Button>
                        <Button as="link" to="/users/register" variant="register">{t('landing.registerButtonText')}</Button>
                    </>
                }
            />

            <section className="hero position-relative overflow-hidden py-5">
                <div className="container">
                    <div className="row d-flex flex-column text-center align-items-center">
                        <div className="col-12 col-lg-8 mb-4">
                            <TextBlock  variant="h1" font="klein" align="center" weight="bold" size="text-[3.5rem]" className="mb-3 ">
                                {t('landing.logoHeadText') || 'TaskFlow — порядок в делах'}
                            </TextBlock>

                            <TextBlock variant="h1" font="klein" align="center" weight="bold" size="text-[5em]" className="mb-3 bg-boost text-red-500">
                                {t('landing.boldText') || 'TaskFlow — порядок в делах'}
                            </TextBlock>

                            <TextBlock variant="p" font="poppins"  align="center" color="text-muted" size="lead" className="mb-4">
                                {t('landing.logoDescText') || 'Легкий и мощный инструмент для задач и привычек'}
                            </TextBlock>
                            <Button as="link" to="/users/register" variant="big">
                                Начать
                            </Button>
                        </div>
                    </div>
                </div>
            </section>

            <section className="features container py-5">
                <TextBlock variant="h3" align="center" weight="bold" size="display-4" className="mb-3">
                    {t('landing.featuresText')}
                </TextBlock>
                <DynamicGridList
                    items={data.features}
                    renderItem={(feature) => <FeatureCard key={feature.id} {...feature} />}
                    layout="4-cols"
                />
            </section>

            <section className="testimonials container py-5">
                <TextBlock variant="h3" align="center" weight="bold" size="display-4" className="mb-3">
                    {t('landing.feedbackText')}
                </TextBlock>
                <DynamicGridList
                    items={data.reviews}
                    renderItem={(review) => <ReviewCard key={review.id} {...review} />}
                    layout="3-cols"
                />
            </section>

            <footer className="text-center py-3">
                <Copyright />
            </footer>
        </div>
    );
};

export default Lending;
