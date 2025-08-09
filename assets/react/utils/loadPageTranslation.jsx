import i18n from '../Infrastructure/Library/i18n';
import {LanguageRequestUseCase} from "../Aplication/UseCases/language/LanguageRequestUseCase";
import {LanguageApi} from "../Infrastructure/request/Language/LanguageApi";
const languageApi = new LanguageRequestUseCase('landing', new LanguageApi());

export const loadPageTranslation = async (page, lang) => {
    if (i18n.hasResourceBundle(lang, page)) return;

    try {
        await languageApi.getTranslations(lang);
    } catch (e) {
        console.error('Ошибка при загрузке перевода:', e);
    }
};
