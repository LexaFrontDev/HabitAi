class HttpClient {
    constructor(baseURL = '') {
        this.baseURL = baseURL;
        this.accessToken = localStorage.getItem('access_token');
        this.refreshToken = localStorage.getItem('refresh_token');
    }

    setTokens({ access_token, refresh_token }) {
        this.accessToken = access_token;
        this.refreshToken = refresh_token;
        localStorage.setItem('access_token', access_token);
        localStorage.setItem('refresh_token', refresh_token);
    }

    getHeaders() {
        return {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${this.accessToken}`,
            'X-Refresh-Token': this.refreshToken
        };
    }

    async refreshTokens() {
        const res = await fetch(`${this.baseURL}/auth/refresh`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ refresh_token: this.refreshToken })
        });

        if (!res.ok) throw new Error('Failed to refresh token');
        const data = await res.json();
        this.setTokens(data);
    }

    async request(method, url, body = null, customHeaders = {}) {
        let options = {
            method,
            headers: { ...this.getHeaders(), ...customHeaders }
        };

        if (body) options.body = JSON.stringify(body);

        let res = await fetch(this.baseURL + url, options);

        if (res.status === 401) {
            try {
                await this.refreshTokens();
                options.headers = { ...this.getHeaders(), ...customHeaders };
                res = await fetch(this.baseURL + url, options);
            } catch (e) {
                console.error("Не удалось обновить токен:", e);
                throw e;
            }
        }

        return res;
    }

    get(url, headers = {}) {
        return this.request('GET', url, null, headers);
    }

    post(url, body, headers = {}) {
        return this.request('POST', url, body, headers);
    }

    put(url, body, headers = {}) {
        return this.request('PUT', url, body, headers);
    }

    delete(url, body = null, headers = {}) {
        return this.request('DELETE', url, body, headers);
    }
}

export const http = new HttpClient('/api');
