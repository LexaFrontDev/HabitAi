import React, {useEffect, useState} from 'react';
import {LangStorage} from "../../Infrastructure/languageStorage/LangStorage";
import {LangStorageUseCase} from "../../Aplication/UseCases/language/LangStorageUseCase";
import {LanguageRequestUseCase} from "../../Aplication/UseCases/language/LanguageRequestUseCase";
import {LanguageApi} from "../../Infrastructure/request/Language/LanguageApi";
import {useTranslation} from "react-i18next";
import Loading from "../chunk/LoadingChunk/Loading";


const currentPage = 'register';
const langStorage = new LangStorage();
const langUseCase = new LangStorageUseCase(langStorage);
const languageApi = new LanguageRequestUseCase(currentPage, new LanguageApi());


const RegisterPage = () => {
    const [form, setForm] = useState({ name: '', email: '', password: '' });
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
        fetch('/api/auth/check')
            .then(res => {
                if (res.ok) {
                    window.location.href = '/';
                }
            });
    }, []);

    const handleChange = e => setForm({ ...form, [e.target.name]: e.target.value });

    const handleSubmit = async e => {
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

    if (!i18n.hasResourceBundle(langCode, currentPage)) return <Loading />;


    return (
        <div className="register-container">
            <div className="register-image d-none d-lg-block" style={{ background: '#a4ac86 url(/StorageImages/Icons/focused.svg) no-repeat center center', backgroundSize: 'cover' }}></div>
            <div className="register-form-wrapper">
                <form onSubmit={handleSubmit} className="register-form">
                    <h2 className="mb-4 text-center fw-bold">{t('registerHeadText')}</h2>
                    <p className="text-muted text-center mb-4">{t('headDescRegister')}</p>
                    <div className="mb-3">
                        <label className="form-label text-start d-block">{t('nameInputLabel')}</label>
                        <input type="text" className="form-control" name="name" value={form.name} onChange={handleChange} required />
                    </div>
                    <div className="mb-3">
                        <label className="form-label text-start d-block">{t('emailInputLabel')}</label>
                        <input type="email" className="form-control" name="email" value={form.email} onChange={handleChange} required />
                    </div>
                    <div className="mb-3">
                        <label className="form-label text-start d-block">{t('passwordInputLabel')}</label>
                        <input type="password" className="form-control" name="password" value={form.password} onChange={handleChange} required />
                    </div>
                    <button type="submit" className="btn btn-primary">{t('registerButton')}</button>
                    <button type="button" className="btn-google">
                        <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" style={{ width: '20px', height: '20px' }} />{t('login:notHaveAccount')}
                    </button>
                    <a href="/users/login">{t('haveAccount')}</a>
                </form>
            </div>
        </div>
    );
};

export default RegisterPage;
