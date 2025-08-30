import React, { useState, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import Loading from '../chunk/LoadingChunk/Loading';
import {LangStorage} from "../../Services/languageStorage/LangStorage";
import {LangStorageUseCase} from "../../Services/language/LangStorageUseCase";
import {LanguageRequestUseCase} from "../../Services/language/LanguageRequestUseCase";
import {LanguageApi} from "../../Services/language/LanguageApi";


const langStorage = new LangStorage();
const langUseCase = new LangStorageUseCase(langStorage);
const languageApi = new LanguageRequestUseCase(new LanguageApi());

const LoginPage = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
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

    const handleSubmit = async (e: any) => {
        e.preventDefault();
        setError('');
        setLoading(true);

        try {
            const response = await fetch('/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email, password }),
            });

            const contentType = response.headers.get('content-type');
            let result = null;

            if (contentType && contentType.includes('application/json')) {
                result = await response.json();
            } else {
                const text = await response.text();
                console.warn('Сервер вернул не JSON:', text);
            }

            setLoading(false);

            if (response.ok) {
                window.location.href = '/';
            } else {
                const errorMsg =
                    result?.message ||
                    (response.status === 401
                        ? t('login.invalidCredentials')
                        : t('login.unknownError'));
                setError(errorMsg);
            }
        } catch (err) {
            console.error('Ошибка при логине:', err);
            setLoading(false);
            setError(t('login.serverConnectionError'));
        }
    };



    if (!translationsLoaded) return <Loading />;

    return (
        <div className="login-container">
            <div className="login-image d-none d-lg-block" style={{ background: '#a4ac86 url(/StorageImages/Icons/focused.svg) no-repeat center center', backgroundSize: 'cover' }}></div>

            <div className="login-form-wrapper">
                <form onSubmit={handleSubmit} className="login-form">
                    <h2 className="mb-4 text-center fw-bold">{t('login.headText')}</h2>
                    <p className="text-muted text-center mb-4">{t('login.headPText')}</p>

                    <div className="mb-3">
                        <label className="form-label text-start d-block">{t('login.emailLabelText')}</label>
                        <input type="email" className="form-control" value={email} onChange={e => setEmail(e.target.value)} required />
                    </div>
                    <div className="mb-3">
                        <label className="form-label text-start d-block">{t('login.passwordLabelText')}</label>
                        <input type="password" className="form-control" value={password} onChange={e => setPassword(e.target.value)} required />
                    </div>

                    {error && <div className="error-message" style={{ display: 'block' }}>{error}</div>}

                    <button type="submit" className="btn btn-primary">{t('login.loginButton')}</button>
                    <button type="button" className="btn-google">
                        <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" style={{ width: '20px', height: '20px' }} /> {t('login.loginWithGoogleButton')}
                    </button>

                    {loading && <div className="loading-gift"><img src="/StorageImages/Animations/Load.gif" alt="Загрузка..." /></div>}

                    <a href="/Users/register">{t('login.notHaveAccount')}</a>
                </form>
            </div>
        </div>
    );
};

export default LoginPage;
