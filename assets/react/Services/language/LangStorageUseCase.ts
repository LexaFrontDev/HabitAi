
import {LangStorage} from "../languageStorage/LangStorage";

export class LangStorageUseCase
{
    constructor(private readonly storage: LangStorage) {}

    async getLang(): Promise<string | false> {
        const lang = this.storage.getPreferredLang();
        if (lang !== null) return lang;
        const country = await this.storage.detectCountry();
        if (!country) return false;

        const resolvedLang = country === 'KZ'
            ? 'kz'
            : ['RU', 'BY'].includes(country)
                ? 'ru'
                : 'en';

        this.storage.setPreferredCountry(country);
        this.storage.setPreferredLang(resolvedLang);

        return resolvedLang;
    }



}