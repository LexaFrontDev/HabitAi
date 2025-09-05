import {CACHE_CONFIG} from "../../config/CACHE";
import {CacheServiceInterface} from "../../interfaces/Cache/CacheServiceInterface";
import {IDBPDatabase, openDB} from "idb";

export class IndexedDBCacheService implements CacheServiceInterface {
    private dbPromise: Promise<IDBPDatabase>;
    private cache: Record<string, Record<number, any>> = {};
    private nextCacheId: Record<string, number> = {};

    constructor() {
        this.dbPromise = openDB(CACHE_CONFIG.DB_NAME, CACHE_CONFIG.DB_VERSION, {
            upgrade(db) {
                if (!db.objectStoreNames.contains(CACHE_CONFIG.STORE_NAME)) {
                    db.createObjectStore(CACHE_CONFIG.STORE_NAME);
                }
            },
        });
    }

    async getAll<T>(key: string): Promise<T[]> {
        const db = await this.dbPromise;
        const allKeys = await db.getAllKeys(CACHE_CONFIG.STORE_NAME);
        const keyPrefix = `${key}_`;
        const relevantKeys = allKeys.filter(k => typeof k === 'string' && k.startsWith(keyPrefix));
        const allItems = await Promise.all(relevantKeys.map(k => db.get(CACHE_CONFIG.STORE_NAME, k)));
        return allItems as T[];
    }

    private async getNextCacheId(key: string): Promise<number> {
        if (!this.nextCacheId[key]) {
            const db = await this.dbPromise;
            this.nextCacheId[key] = (await db.get(CACHE_CONFIG.STORE_NAME, `${key}_cache_id`)) || 0;
        }
        this.nextCacheId[key]++;
        const cacheId = this.nextCacheId[key];
        // Асинхронно сохраняем на диск, не ждём
        this.dbPromise.then(db => db.put(CACHE_CONFIG.STORE_NAME, cacheId, `${key}_cache_id`));
        return cacheId;
    }

    async getByCacheId<T extends { cacheId: number }>(key: string, cacheId: number): Promise<T | null> {
        this.cache[key] = this.cache[key] || {};
        if (this.cache[key][cacheId]) return this.cache[key][cacheId] as T;

        const db = await this.dbPromise;
        const item = await db.get(CACHE_CONFIG.STORE_NAME, `${key}_${cacheId}`);
        if (item) this.cache[key][cacheId] = item;
        return item || null;
    }

    async create<T extends object>(key: string, data: T): Promise<T & { cacheId: number }> {
        const cacheId = await this.getNextCacheId(key);
        const item = { ...data, cacheId };
        this.cache[key] = this.cache[key] || {};
        this.cache[key][cacheId] = item;


        this.dbPromise.then(db => db.put(CACHE_CONFIG.STORE_NAME, item, `${key}_${cacheId}`))
            .catch(err => console.error(err));

        return item;
    }

    async update<T extends { cacheId: number }>(key: string, cacheId: number, data: Partial<T>): Promise<T> {
        const existing = await this.getByCacheId<T>(key, cacheId);
        if (!existing) throw new Error(`Не найден элемент с cacheId=${cacheId}`);

        const updatedItem = { ...existing, ...data };
        this.cache[key][cacheId] = updatedItem;

        this.dbPromise.then(db => db.put(CACHE_CONFIG.STORE_NAME, updatedItem, `${key}_${cacheId}`))
            .catch(err => console.error(err));

        return updatedItem;
    }

    async deleteItem(key: string, cacheId: number): Promise<boolean> {
        delete this.cache[key]?.[cacheId];

        this.dbPromise.then(db => db.delete(CACHE_CONFIG.STORE_NAME, `${key}_${cacheId}`))
            .catch(err => console.error(err));

        return true;
    }

    async merge<T extends { cacheId: number }>(key: string, data: T): Promise<void> {
        const existing = await this.getByCacheId<T>(key, data.cacheId);
        const merged = existing ? { ...existing, ...data } : data;
        this.cache[key] = this.cache[key] || {};
        this.cache[key][data.cacheId] = merged;

        this.dbPromise.then(db => db.put(CACHE_CONFIG.STORE_NAME, merged, `${key}_${data.cacheId}`))
            .catch(err => console.error(err));
    }


    async clear(key: string): Promise<boolean> {
        this.cache[key] = {};
        const db = await this.dbPromise;
        const allKeys = await db.getAllKeys(CACHE_CONFIG.STORE_NAME);
        const keyPrefix = `${key}_`;
        await Promise.all(allKeys.filter(k => typeof k === 'string' && k.startsWith(keyPrefix))
            .map(k => db.delete(CACHE_CONFIG.STORE_NAME, k)));
        return true;
    }
}
