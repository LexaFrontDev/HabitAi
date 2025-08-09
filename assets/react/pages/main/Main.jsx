import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import Loading from '../chunk/LoadingChunk/Loading';
import { loadPageTranslation } from '../../utils/loadPageTranslation';
import { LangStorageUseCase } from "../../Aplication/UseCases/language/LangStorageUseCase";
import {LangStorage} from "../../Infrastructure/languageStorage/LangStorage";
import { Link } from 'react-router-dom';
import {Button} from "../../ui/atoms/button/Button";
import {AppLink} from "../../ui/atoms/link/AppLink";
import {ImageWrapper} from "../../ui/atoms/Image/ImageWrapper";
import {TextBlock} from "../../ui/atoms/TextBlock/TextBlock";
import FeatureList from "../../ui/organism/cards/FeatureList";
import DynamicGridList from "../../ui/organism/cards/DynamicGridList";
import FeatureCard from "../../ui/molecule/Cards/FeatureCard";
import ReviewCard from "../../ui/molecule/Cards/ReviewCard";
import Copyright from "../../ui/atoms/TextBlock/Copyright";
import Navbar from "../../ui/organism/navbar/Navbar";
import LanguageSelect from "../../ui/atoms/select/LanguageSelect";
import {LanguageRequestUseCase} from "../../Aplication/UseCases/language/LanguageRequestUseCase";
import {LanguageApi} from "../../Infrastructure/request/Language/LanguageApi";
import {changeLanguage} from "i18next";



const currentPage = 'landing';
const langStorage = new LangStorage();
const langUseCase = new LangStorageUseCase(langStorage);
const languageApi = new LanguageRequestUseCase(currentPage, new LanguageApi());


const Lending = () => {
    const [data, setData] = useState(null);
    const [langCode, setLangCode] = useState('en');
    const { t, i18n } = useTranslation(currentPage);

    useEffect(() => {
        const detectLang = async () => {
            const lang = await langUseCase.getLang();
            if (lang) {
                setLangCode(lang);
                await languageApi.getTranslations(lang);
            }
        };

        detectLang();
    }, []);

    useEffect(() => {
        fetch('/api/landing/data')
            .then(res => res.json())
            .then(setData);
    }, []);

    const changeLanguage = async (lang) => {
        await languageApi.getTranslations(lang);
    }

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

    if (!data || !i18n.hasResourceBundle(langCode, currentPage)) return <Loading />;

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
                        <Button as="link" to="/users/login" variant="login">{t('loginButtonText')}</Button>
                        <Button as="link" to="/users/register" variant="register">{t('registerButtonText')}</Button>
                    </>
                }
            />


             <section className="hero position-relative overflow-hidden py-5">
                <div className="container">
                    <div className="row d-flex flex-column text-center align-items-center">
                        <div className="col-12 col-lg-8 mb-4">
                            <TextBlock variant="h1" align="center" weight="bold" size="display-4" className="mb-3">
                                {t('logoHeadText') || 'TaskFlow — порядок в делах'}
                            </TextBlock>

                            <TextBlock variant="p" align="center" color="text-muted" size="lead" className="mb-4">
                                {t('logoDescText') || 'Легкий и мощный инструмент для задач и привычек'}
                            </TextBlock>
                            <Button as="link" href="/users/register" variant="big">
                                Начать
                            </Button>
                        </div>
                    </div>
                    <ImageWrapper
                        src="/StorageImages/Icons/FonLogos.png"
                        size="big"
                        position="center"
                    />
                </div>
            </section>


            <section className="features container py-5">
                <TextBlock variant="h3" align="center" weight="bold" size="display-4" className="mb-3">
                    {t('featuresText')}
                </TextBlock>
                <DynamicGridList
                    items={data.features}
                    renderItem={(feature) => <FeatureCard {...feature} />}
                    layout="4-cols"
                />
            </section>


            <section className="testimonials container py-5">
                <TextBlock variant="h3" align="center" weight="bold" size="display-4" className="mb-3">
                    {t('feedbackText')}
                </TextBlock>
                <DynamicGridList
                    items={data.reviews}
                    renderItem={(review) => <ReviewCard {...review} />}
                    layout="3-cols"
                />
            </section>


            <footer className="text-center py-3">
                <Copyright/>
            </footer>
        </div>
    );
};

export default Lending;
