import { CacheServiceInterface } from "../../interfaces/Cache/CacheServiceInterface";

export class CtnServices {
    constructor(
        private readonly cacheService: CacheServiceInterface,
    ) {}

    private async logError(message: string, payload?: any) {
        console.error("USECASE ERROR:", message, payload);
        try {
            await fetch('/error/create/log', {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ message, payload, date: new Date().toISOString() })
            });
        } catch (e) {
            console.warn("Не удалось отправить ошибку в лог-сервис:", e);
        }
    }

    async _request<R>(url: string, method: string, body?: any): Promise<R> {
        let payload = body;
        if (body && 'cacheId' in body) {
            const { cacheId, ...rest } = body;
            payload = rest;
        }

        const response = await fetch(url, {
            method,
            headers: { "Content-Type": "application/json" },
            body: payload ? JSON.stringify(payload) : undefined,
        });

        if (!response.ok) throw new Error(`Ошибка ${response.status}`);
        return await response.json() as Promise<R>;
    }



    async get<R extends { cacheId: number }>(
        key: string,
        fetchUrl: string,
        method: string = "GET",
        body?: any
    ): Promise<R[] | false> {
        try {
            const cached = await this.cacheService.getAll<R>(key);
            if (cached && cached.length) {
                console.log(`[CACHE HIT] key="${key}"`, cached);
                return cached;
            }

            const data = await this._request<R[]>(fetchUrl, method, method !== "GET" ? body : undefined);
            await this.cacheService.clear(key);
            for (const item of data) {
                await this.cacheService.create(key, item as any);
            }
            console.log(`[CACHE MISS] key="${key}" fetched`, data);
            return data;
        } catch (e) {
            await this.logError("Ошибка GET-запроса", { fetchUrl, error: e });
            const cached = await this.cacheService.getAll<R>(key);
            return cached.length ? cached : false;
        }
    }



    async create<B extends { id?: number }, R extends { cacheId: number }>(
        key: string,
        fetchUrl: string,
        method: string = "POST",
        body?: B
    ): Promise<R | false> {
        try {
            const data = await this._request<R>(fetchUrl, method, body);
            if (!data) return false;
            const dataToCache = body ? { ...body } : {};
            const cachedData = await this.cacheService.create<R>(key, dataToCache as any);
            return data;
        } catch (e) {
            await this.logError("Ошибка CREATE-запроса", { fetchUrl, body, error: e });
            if (!body) return false;

            const tempData = { ...body, _pending: true } as any;
            return await this.cacheService.create(key, tempData);
        }
    }



    async update<B extends object, R extends { cacheId: number }>(
        key: string,
        fetchUrl: string,
        cacheId: number,
        method: string = "PUT",
        body?: B
    ): Promise<R | false> {
        try {
            const data = await this._request<R>(fetchUrl, method, body);
            await this.cacheService.update(key, cacheId, data);
            return data;
        } catch (e) {
            await this.logError("Ошибка UPDATE-запроса", { fetchUrl, cacheId, body, error: e });
            await this.cacheService.merge(key, {...body, cacheId, _pending: true} as unknown as R);
            return false;
        }
    }


    async delete<R extends { cacheId: number }>(
        key: string,
        fetchUrl: string,
        cacheId: number,
        method: string = "DELETE",
        body?: any
    ): Promise<boolean> {
        try {
            await this._request<R>(fetchUrl, method, body);
            await this.cacheService.deleteItem(key, cacheId);
            return true;
        } catch (e) {
            await this.logError("Ошибка DELETE-запроса", { fetchUrl, cacheId, body, error: e });
            await this.cacheService.merge(key, { ...body, cacheId, _deleted: true, _pending: true } as R);
            return false;
        }
    }


    async createAlsoCache<T extends object>(key: string, data: T): Promise<T & { cacheId: number }> {
        const cachedData = await this.cacheService.create(key, data as any);
        return cachedData;
    }


    async updateAlsoCache<R extends { cacheId: number }>(key: string, cacheId: number, data: Partial<R>): Promise<R | false> {
        try {
            await this.cacheService.merge(key, { ...data, cacheId } as R);
            return { ...data, cacheId } as R;
        } catch (e) {
            await this.logError("Ошибка updateAlsoCache", { key, cacheId, data, error: e });
            return false;
        }
    }

    async deleteAlsoCache<R extends { cacheId: number }>(key: string, cacheId: number): Promise<boolean> {
        try {
            await this.cacheService.deleteItem(key, cacheId);
            return true;
        } catch (e) {
            await this.logError("Ошибка deleteAlsoCache", { key, cacheId, error: e });
            return false;
        }
    }

    async getAlsoCache<R extends { cacheId: number }>(key: string): Promise<R[] | false> {
        const cached = await this.cacheService.getAll<R>(key);
        return cached.length ? cached : false;
    }


    async forceRefresh<R extends { cacheId: number }>(
        key: string,
        fetchUrl: string,
        method: string = "GET",
        body?: any
    ): Promise<R[] | false> {
        try {
            await this.cacheService.clear(key);
            const data = await this._request<R[]>(fetchUrl, method, method !== "GET" ? body : undefined);
            for (const item of data) {
                await this.cacheService.create(key, item as any);
            }
            return data;
        } catch (e) {
            await this.logError("Ошибка forceRefresh", { fetchUrl, key, error: e });
            const cached = await this.cacheService.getAll<R>(key);
            return cached.length ? cached : false;
        }
    }


    async updateFieldAlsoCache<R extends { cacheId: number }, K extends keyof R>(
        key: string,
        cacheId: number,
        field: K,
        value: R[K]
    ): Promise<R | false> {
        try {
            const cachedItems = await this.getAlsoCache<R>(key);
            if (!cachedItems) return false;

            const item = cachedItems.find(i => i.cacheId === cacheId);
            if (!item) return false;

            item[field] = value;
            await this.updateAlsoCache(key, cacheId, item);
            return item;
        } catch (e) {
            await this.logError("Ошибка updateFieldAlsoCache", { key, cacheId, field, value, error: e });
            return false;
        }
    }


}
