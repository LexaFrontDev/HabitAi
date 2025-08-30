interface ImportMetaEnv {
    readonly VITE_CACHE_DB_NAME: string;
    readonly VITE_CACHE_STORE_NAME: string;
}

interface ImportMeta {
    readonly env: ImportMetaEnv;
}
