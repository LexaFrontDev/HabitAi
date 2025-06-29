import { useEffect } from 'react';
import { loadPageTranslation } from '../../utils/loadPageTranslation';

const LangInit = ({ children }) => {
    useEffect(() => {
        const initLang = async () => {
            let lang = localStorage.getItem('preferred_lang');

            if (!lang) {
                try {
                    const geo = await fetch('https://api.country.is/').then(res => res.json());
                    const country = geo?.country?.toUpperCase();
                    lang = country === 'KZ' ? 'kz' : ['RU', 'BY'].includes(country) ? 'ru' : 'en';
                } catch {
                    lang = 'en';
                }

                localStorage.setItem('preferred_lang', lang);
            }

            await loadPageTranslation('landing', lang);
        };

        initLang();
    }, []);

    return <>{children}</>;
};

export default LangInit;
