export interface ILangStorage {
    getPreferredLang(): string | null;
    setPreferredLang(lang: string): void;
    getPreferredCountry(): string | null;
    setPreferredCountry(country: string): void;
    detectCountry(): Promise<string>;
}
