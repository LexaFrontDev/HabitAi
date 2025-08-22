import React, {useEffect, useState} from 'react';
import {LangStorage} from "../../Infrastructure/languageStorage/LangStorage";
import {LangStorageUseCase} from "../../Aplication/UseCases/language/LangStorageUseCase";
import {LanguageRequestUseCase} from "../../Aplication/UseCases/language/LanguageRequestUseCase";
import {LanguageApi} from "../../Infrastructure/request/Language/LanguageApi";
import {useTranslation} from "react-i18next";
import Loading from "../chunk/LoadingChunk/Loading";


const langStorage = new LangStorage();
const langUseCase = new LangStorageUseCase(langStorage);
const languageApi = new LanguageRequestUseCase(new LanguageApi());


const RegisterPage = () => {
    const [form, setForm] = useState({ name: '', email: '', password: '' });
    const [langCode, setLangCode] = useState('en');
    const [translationsLoaded, setTranslationsLoaded] = useState<boolean>(false);
    const { t, i18n } = useTranslation('translation');

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
        fetch('/api/auth/check')
            .then(res => {
                if (res.ok) {
                    window.location.href = '/';
                }
            });
    }, []);

    const handleChange = (e: { target: { name: any; value: any; }; })  => setForm({ ...form, [e.target.name]: e.target.value });

    const handleSubmit = async (e: { preventDefault: () => void; }) => {
        e.preventDefault();
        try {
            const res = await fetch('/api/auth/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(form)
            });
            const result = await res.json();
            if (res.ok) window.location.href = '/';
            else alert(result.message || 'Ошибка регистрации');
        } catch {
            alert('Ошибка соединения с сервером');
        }
    };

    if (!translationsLoaded) return <Loading />;


    return (
        <div className="register-container">
            <div className="register-image d-none d-lg-block" style={{ background: '#a4ac86 url(/StorageImages/Icons/focused.svg) no-repeat center center', backgroundSize: 'cover' }}></div>
            <div className="register-form-wrapper">
                <form onSubmit={handleSubmit} className="register-form">
                    <h2 className="mb-4 text-center fw-bold">{t('register.registerHeadText')}</h2>
                    <p className="text-muted text-center mb-4">{t('register.headDescRegister')}</p>
                    <div className="mb-3">
                        <label className="form-label text-start d-block">{t('register.nameInputLabel')}</label>
                        <input type="text" className="form-control" name="name" value={form.name} onChange={handleChange} required />
                    </div>
                    <div className="mb-3">
                        <label className="form-label text-start d-block">{t('register.emailInputLabel')}</label>
                        <input type="email" className="form-control" name="email" value={form.email} onChange={handleChange} required />
                    </div>
                    <div className="mb-3">
                        <label className="form-label text-start d-block">{t('register.passwordInputLabel')}</label>
                        <input type="password" className="form-control" name="password" value={form.password} onChange={handleChange} required />
                    </div>
                    <button type="submit" className="btn btn-primary">{t('register.registerButton')}</button>
                    <button type="button" className="btn-google">
                        <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" style={{ width: '20px', height: '20px' }} />{t('login.loginWithGoogleButton')}
                    </button>
                    <a href="/users/login">{t('register.haveAccount')}</a>
                </form>
            </div>
        </div>
    );
};

export default RegisterPage;
