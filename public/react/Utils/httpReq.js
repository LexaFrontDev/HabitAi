export async function httpRequest(url, { method = 'GET', body = null, headers = {} } = {}) {
    const accessToken = localStorage.getItem('accessToken');

    const config = {
        method,
        headers: {
            'Content-Type': 'application/json',
            ...headers,
            ...(accessToken ? { 'access-token': accessToken } : {}),
        },
    };

    if (body) {
        config.body = JSON.stringify(body);
    }

    try {
        const response = await fetch(url, config);
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            const error = new Error(errorData.message || 'Ошибка запроса');
            error.status = response.status;
            error.data = errorData;
            throw error;
        }
        const contentType = response.headers.get('Content-Type') || '';
        if (contentType.includes('application/json')) {
            return await response.json();
        }

        return await response.text();
    } catch (err) {
        throw err;
    }
}