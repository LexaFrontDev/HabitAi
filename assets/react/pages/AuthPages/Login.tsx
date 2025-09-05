import React, { useState, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import Loading from '../chunk/LoadingChunk/Loading';
import { LangStorage } from "../../Services/languageStorage/LangStorage";
import { LangStorageUseCase } from "../../Services/language/LangStorageUseCase";
import { LanguageRequestUseCase } from "../../Services/language/LanguageRequestUseCase";
import { LanguageApi } from "../../Services/language/LanguageApi";
import {CACHE_CONFIG} from "../../config/CACHE";

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

    const GOOGLE_CLIENT_ID = import.meta.env.VITE_GOOGLE_CLIENT_ID!;
    const GOOGLE_REDIRECT_URI = import.meta.env.VITE_GOOGLE_REDIRECT_URI!; // например http://localhost:8000/api/auth/google/callback

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
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ email, password }),
            });

            const result = response.headers.get('content-type')?.includes('application/json')
                ? await response.json()
                : null;

            setLoading(false);

            if (response.ok) {
                window.location.href = '/';
            } else {
                const errorMsg = result?.message || (response.status === 401 ? t('login.invalidCredentials') : t('login.unknownError'));
                setError(errorMsg);
            }
        } catch (err) {
            console.error('Ошибка при логине:', err);
            setLoading(false);
            setError(t('login.serverConnectionError'));
        }
    };

    const handleGoogleLogin = () => {
        const scope = 'openid profile';
        const responseType = 'code';
        const url = `https://accounts.google.com/o/oauth2/v2/auth?client_id=${GOOGLE_CLIENT_ID}&redirect_uri=${encodeURIComponent(GOOGLE_REDIRECT_URI)}&response_type=${responseType}&scope=${encodeURIComponent(scope)}&prompt=select_account`;
        window.location.href = url;
    };

    if (!translationsLoaded) return <Loading />;

    return (
        <div className="login-container">
            <div className="login-image d-none d-lg-block" style={{ background: '#a4ac86 url(/StorageImages/Icons/focused.svg) no-repeat center center', backgroundSize: 'cover' }}></div>

            <div className="login-form-wrapper">
                <form onSubmit={handleSubmit}>
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

                    <button type="button" className="btn-google mt-3" onClick={handleGoogleLogin}>
                        <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" style={{ width: '20px', height: '20px', marginRight: '8px' }} />
                        {t('login.loginWithGoogleButton')}
                    </button>

                    {loading && <div className="loading-gift"><img src="/StorageImages/Animations/Load.gif" alt="Загрузка..." /></div>}

                    <a href="/Users/register" className="d-block mt-3">{t('login.notHaveAccount')}</a>
                </form>
            </div>
        </div>
    );
};

export default LoginPage;
