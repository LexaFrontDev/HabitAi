export class RequestServices {
    private async logError(message: string, payload?: any) {
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

    private async _request<R>(url: string, method: string, body?: any): Promise<R> {
        const payload = body && 'cacheId' in body ? (({ cacheId, ...rest }) => rest)(body) : body;

        const response = await fetch(url, {
            method,
            headers: { "Content-Type": "application/json" },
            body: payload ? JSON.stringify(payload) : undefined,
        });

        if (!response.ok) throw new Error(`Ошибка ${response.status}`);
        return response.json() as Promise<R>;
    }

    async get<R>(fetchUrl: string, method: string = "GET", body?: any): Promise<R | false> {
        try {
            const data = await this._request<R>(fetchUrl, method, method !== "GET" ? body : undefined);
            return data;
        } catch (e) {
            await this.logError("Ошибка GET-запроса", { fetchUrl, error: e });
            return false;
        }
    }

    async getArray<R>(fetchUrl: string, method: string = "GET", body?: any, accessMassiveKey?: string): Promise<R[] | false> {
        try {
            let data = await this._request<R | R[]>(fetchUrl, method, method !== "GET" ? body : undefined);

            if (accessMassiveKey && typeof data === "object" && !Array.isArray(data)) {
                data = (data as any)[accessMassiveKey];
            }

            if (!Array.isArray(data)) {
                if (data == null) return [];
                return [data as R];
            }

            return data;
        } catch (e) {
            await this.logError("Ошибка GET-запроса", { fetchUrl, error: e });
            return false;
        }
    }

    async create<B>(fetchUrl: string, method: string = "POST", body?: B): Promise<any> {
        try {
            return this._request(fetchUrl, method, body);
        } catch (e) {
            await this.logError("Ошибка CREATE-запроса", { fetchUrl, body, error: e });
            return false;
        }
    }

    async update<B>(fetchUrl: string, method: string = "PUT", body?: B): Promise<any> {
        try {
            return this._request(fetchUrl, method, body);
        } catch (e) {
            await this.logError("Ошибка UPDATE-запроса", { fetchUrl, body, error: e });
            return false;
        }
    }

    async delete(fetchUrl: string, body?: any): Promise<any> {
        try {
            return this._request(fetchUrl, "DELETE", body);
        } catch (e) {
            await this.logError("Ошибка DELETE-запроса", { fetchUrl, body, error: e });
            return false;
        }
    }
}
