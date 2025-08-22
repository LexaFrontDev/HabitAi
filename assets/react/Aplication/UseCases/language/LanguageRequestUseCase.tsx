import { LanguageReqInterface } from "../../../Domain/request/Language/LanguageReqInterface";
// @ts-ignore
import i18n from "../../../Infrastructure/Library/i18n";

export class LanguageRequestUseCase {
    private static CACHE_KEY_PREFIX = 'loadedTranslations_';

    constructor(private readonly langApi: LanguageReqInterface) {}

    private static getCache(prefix: string): Record<string, Record<string, string>> | null {
        const cacheJson = localStorage.getItem(LanguageRequestUseCase.CACHE_KEY_PREFIX + prefix);
        return cacheJson ? JSON.parse(cacheJson) : null;
    }

    private static setCache(prefix: string, translations: Record<string, Record<string, string>>) {
        localStorage.setItem(LanguageRequestUseCase.CACHE_KEY_PREFIX + prefix, JSON.stringify(translations));
    }

    async getTranslations(prefix: string): Promise<any> {
        const cachedTranslations = LanguageRequestUseCase.getCache(prefix);

        if (cachedTranslations) {
            i18n.addResourceBundle(prefix, 'translation', cachedTranslations, true, true); // 'translation' — стандартный глобальный namespace
            await i18n.changeLanguage(prefix);
            return;
        }



        const json = await this.langApi.getTranslations(prefix);
        const translationsRoot = json.translate || {};
        i18n.addResourceBundle(prefix, 'translation', translationsRoot as Record<string, string>, true, true);
        LanguageRequestUseCase.setCache(prefix, translationsRoot);
        await i18n.changeLanguage(prefix);
    }
}
