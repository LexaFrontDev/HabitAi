import { CacheServiceInterface } from "../../interfaces/Cache/CacheServiceInterface";
import { openDB, IDBPDatabase } from "idb";
import { CACHE_CONFIG } from "../../config/CACHE";

export class IndexedDBCacheService implements CacheServiceInterface {
    private dbPromise: Promise<IDBPDatabase>;

    constructor() {
        this.dbPromise = openDB(CACHE_CONFIG.DB_NAME, CACHE_CONFIG.DB_VERSION, {
            upgrade(db) {
                if (!db.objectStoreNames.contains(CACHE_CONFIG.STORE_NAME)) {
                    db.createObjectStore(CACHE_CONFIG.STORE_NAME);
                }
            },
        });
    }

    private async getNextCacheId(key: string): Promise<number> {
        const db = await this.dbPromise;
        const cacheIdKey = `${key}_cache_id`;
        let currentCacheId = (await db.get(CACHE_CONFIG.STORE_NAME, cacheIdKey)) || 0;
        currentCacheId += 1;
        await db.put(CACHE_CONFIG.STORE_NAME, currentCacheId, cacheIdKey);
        return currentCacheId;
    }

    async getAll<T extends { cacheId: number }>(key: string): Promise<T[]> {
        const db = await this.dbPromise;
        const list = (await db.get(CACHE_CONFIG.STORE_NAME, key)) || [];

        console.log(`[CACHE] getAll for key="${key}":`, list);

        return list.map((item: any) => ({ ...item }));
    }


    async getByCacheId<T extends { cacheId: number }>(key: string, cacheId: number): Promise<T | null> {
        const list = await this.getAll<T>(key);
        return list.find(item => item.cacheId === cacheId) || null;
    }

    async create<T extends object>(key: string, data: T): Promise<T & { cacheId: number }> {
        const list = await this.getAll<any>(key);
        const cacheId = await this.getNextCacheId(key);
        const item = { ...data, cacheId };
        list.push(item);
        const db = await this.dbPromise;
        await db.put(CACHE_CONFIG.STORE_NAME, list, key);
        console.log(`[CACHE] create for key="${key}"`, item);
        return item;
    }


    async update<T extends { cacheId: number }>(key: string, cacheId: number, data: Partial<T>): Promise<T> {
        const list = await this.getAll<any>(key);
        const idx = list.findIndex(item => item.cacheId === cacheId);
        if (idx === -1) throw new Error(`Не найден элемент с cacheId=${cacheId}`);
        list[idx] = { ...list[idx], ...data };
        const db = await this.dbPromise;
        await db.put(CACHE_CONFIG.STORE_NAME, list, key);
        return list[idx];
    }

    async deleteItem(key: string, cacheId: number): Promise<boolean> {
        let list = await this.getAll<any>(key);
        list = list.filter(item => item.cacheId !== cacheId);
        const db = await this.dbPromise;
        await db.put(CACHE_CONFIG.STORE_NAME, list, key);
        return true;
    }

    async clear(key: string): Promise<boolean> {
        const db = await this.dbPromise;
        await db.delete(CACHE_CONFIG.STORE_NAME, key);
        return true;
    }

    async merge<T extends { cacheId: number }>(key: string, data: T): Promise<void> {
        const list = await this.getAll<any>(key);
        const idx = list.findIndex(item => item.cacheId === data.cacheId);
        if (idx >= 0) {
            list[idx] = { ...list[idx], ...data };
        } else {
            list.push(data);
        }
        const db = await this.dbPromise;
        await db.put(CACHE_CONFIG.STORE_NAME, list, key);
    }
}
