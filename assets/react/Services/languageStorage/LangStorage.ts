import { ILangStorage } from '../../interfaces/languageStorage/ILangStorage';

export class LangStorage implements ILangStorage {
    getPreferredLang(): string | null {
        return localStorage.getItem('preferred_lang');
    }

    setPreferredLang(lang: string) {
        localStorage.setItem('preferred_lang', lang);
    }

    getPreferredCountry(): string | null {
        return localStorage.getItem('preferred_country');
    }

    setPreferredCountry(country: string) {
        localStorage.setItem('preferred_country', country);
    }

    async detectCountry(): Promise<string> {
        try {
            const res = await fetch('https://api.country.is/');
            const geo = await res.json();
            return geo.country?.toUpperCase() || 'US';
        } catch {
            return 'US';
        }
    }
}
