

export interface LanguageReqInterface{
    getTranslations(prefix: string): Promise<any>;
}