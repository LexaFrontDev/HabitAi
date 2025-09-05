
interface ImportMetaEnv {
    readonly VITE_CACHE_DB_NAME: string;
    readonly VITE_CACHE_STORE_NAME: string;

    // Google OAuth
    readonly VITE_GOOGLE_CLIENT_ID: string;
    readonly VITE_GOOGLE_REDIRECT_URI: string;
    readonly VITE_PUSH_PUBLIC_KEY: string;
    readonly VITE_PUSH_PRIVATE_KEY: string;
}

interface ImportMeta {
    readonly env: ImportMetaEnv;
}
