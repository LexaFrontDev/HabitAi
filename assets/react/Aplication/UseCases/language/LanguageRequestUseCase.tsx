import { LanguageReqInterface } from "../../../Domain/request/Language/LanguageReqInterface";
// @ts-ignore
import i18n from "../../../Infrastructure/Library/i18n";

export class LanguageRequestUseCase {
    private static CACHE_KEY_PREFIX = 'loadedTranslations_'; // ключи с языком

    constructor(private readonly page: string, private readonly langApi: LanguageReqInterface) {}

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
            for (const [namespace, translations] of Object.entries(cachedTranslations)) {
                i18n.addResourceBundle(prefix, namespace, translations, true, true);
            }
            await i18n.changeLanguage(prefix);
            return;
        }

        const json = await this.langApi.getTranslations(prefix);
        const translationsRoot = json.translate;

        for (const [namespace, translations] of Object.entries(translationsRoot)) {
            i18n.addResourceBundle(prefix, namespace, translations as Record<string, string>, true, true);
        }

        LanguageRequestUseCase.setCache(prefix, translationsRoot);

        await i18n.changeLanguage(prefix);
    }
}
