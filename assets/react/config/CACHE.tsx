export const CACHE_CONFIG = {
    DB_NAME: import.meta.env.VITE_CACHE_DB_NAME,
    STORE_NAME: import.meta.env.VITE_CACHE_STORE_NAME ?? "cache",
    DB_VERSION: 1,
};
