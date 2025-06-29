import React from 'react';

interface LanguageSelectProps {
    langCode: string;
    setLangCode: (code: string) => void;
    loadPageTranslation: (page: string, lang: string) => Promise<void>;
    currentPage: string;
    languages?: Record<string, string>;
    className?: string;
}

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
    const handleChange = async (e: React.ChangeEvent<HTMLSelectElement>) => {
        const newLang = e.target.value;
        setLangCode(newLang);
        localStorage.setItem('preferred_lang', newLang);
        await loadPageTranslation(currentPage, newLang);
    };

    return (
        <select className={className} value={langCode} onChange={handleChange}>
            {Object.entries(languages).map(([code, label]: [string, string]) => (
                <option key={code} value={code}>
                    {label}
                </option>
            ))}
        </select>
    );
};

export default LanguageSelect;