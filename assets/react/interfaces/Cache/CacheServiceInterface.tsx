export interface CacheServiceInterface {
    getAll<T extends { cacheId: number }>(key: string): Promise<T[]>;
    getByCacheId<T extends { cacheId: number }>(key: string, cacheId: number): Promise<T | null>;
    create<T extends object>(key: string, data: T): Promise<T & { cacheId: number }>;
    update<T extends { cacheId: number }>(key: string, cacheId: number, data: Partial<T>): Promise<T>;
    deleteItem(key: string, id: number): Promise<boolean>;
    clear(key: string): Promise<boolean>;
    merge<T extends { cacheId: number }>(key: string, data: T): Promise<void>;
}
