import i18n from '../Infrastructure/Library/i18n';

export const loadPageTranslation = async (page, lang) => {
    if (i18n.hasResourceBundle(lang, page)) return;

    try {
        const res = await fetch(`/api/language?page=${page}&lang=${lang}`);
        if (!res.ok) throw new Error('Не удалось загрузить перевод');

        const json = await res.json();
        i18n.addResourceBundle(lang, page, json, true, true);
        await i18n.changeLanguage(lang);
    } catch (e) {
        console.error('Ошибка при загрузке перевода:', e);
    }
};
