import {LanguageReqInterface} from "../../interfaces/Language/LanguageReqInterface";

export class LanguageApi implements LanguageReqInterface
{
    async getTranslations(prefix: string): Promise<any> {
        const res = await fetch(`/api/language?versions=v1_translate_site&lang=${prefix}`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
        });

        if (!res.ok) {
            const error = await res.json().catch(() => ({}));
            throw new Error(error.message || 'Ошибка получения Языка');
        }

        return res.json();
    }

}