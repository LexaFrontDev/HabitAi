import React, { useEffect, useState } from 'react';

interface LanguageSelectProps {
    langCode: string;
    setLangCode: (code: string) => void;
    loadPageTranslation: (lang: string) => Promise<void>;
    currentPage: string;
    languages?: Record<string, string>;
    className?: string;
}

type Language = {
    prefix: string;
    lang_label: string;
};

const defaultLanguages: Record<string, string> = {
    ru: 'Русский',
    en: 'English',
    kz: 'Қазақша',
};

const LanguageSelect: React.FC<LanguageSelectProps> = ({
                                                           langCode,
                                                           setLangCode,
                                                           loadPageTranslation,
                                                           currentPage,
                                                           languages = defaultLanguages,
                                                           className = 'form-select form-select-sm me-3',
                                                       }) => {
    const [localLanguages, setLocalLanguages] = useState<Record<string, string>>(languages);

    const handleChange = async (e: React.ChangeEvent<HTMLSelectElement>) => {
        const newLang = e.target.value;
        setLangCode(newLang);
        localStorage.setItem('preferred_lang', newLang);
        await loadPageTranslation(newLang);
    };

    useEffect(() => {
        async function fetchLanguages() {
            try {
                const res = await fetch('/api/get/prefixs');
                const data = await res.json();

                if (data.result && Array.isArray(data.result)) {
                    const langsMap: Record<string, string> = {};

                    data.result.forEach(({ prefix, lang_label }: Language) => {
                        langsMap[prefix] = lang_label;
                    });

                    setLocalLanguages(langsMap);
                }
            } catch (error) {
                console.error('Ошибка при загрузке языков', error);
            }
        }

        fetchLanguages();
    }, []);

    return (
        <select className={className} value={langCode} onChange={handleChange}>
            {Object.entries(localLanguages).map(([code, label]) => (
                <option key={code} value={code}>
                    {label}
                </option>
            ))}
        </select>
    );
};

export default LanguageSelect;
