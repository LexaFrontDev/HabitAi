import React, { useState, useEffect } from 'react';

const LoginPage = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');

    useEffect(() => {
        fetch('/api/auth/check')
            .then(res => {
                if (res.ok) {
                    window.location.href = '/';
                }
            });
    }, []);

    const handleSubmit = async e => {
        e.preventDefault();
        setError('');
        setLoading(true);

        try {
            const response = await fetch('/api/auth/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });

            const resultText = await response.text();
            let result;
            try {
                result = JSON.parse(resultText);
            } catch {
                result = { message: resultText };
            }

            setLoading(false);

            if (response.ok) {
                window.location.href = '/';
            } else {
                setError(result.message || 'Произошла ошибка входа');
            }
        } catch (err) {
            setLoading(false);
            setError('Ошибка соединения с сервером');
        }
    };

    return (
        <div className="login-container">
            <div className="login-image d-none d-lg-block" style={{ background: '#a4ac86 url(/StorageImages/Icons/focused.svg) no-repeat center center', backgroundSize: 'cover' }}></div>

            <div className="login-form-wrapper">
                <form onSubmit={handleSubmit} className="login-form">
                    <h2 className="mb-4 text-center fw-bold">С возвращением 👋</h2>
                    <p className="text-muted text-center mb-4">Войдите, чтобы продолжить</p>

                    <div className="mb-3">
                        <label className="form-label text-start d-block">Email</label>
                        <input type="email" className="form-control" value={email} onChange={e => setEmail(e.target.value)} required />
                    </div>
                    <div className="mb-3">
                        <label className="form-label text-start d-block">Пароль</label>
                        <input type="password" className="form-control" value={password} onChange={e => setPassword(e.target.value)} required />
                    </div>

                    {error && <div className="error-message" style={{ display: 'block' }}>{error}</div>}

                    <button type="submit" className="btn btn-primary">Войти</button>
                    <button type="button" className="btn-google">
                        <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" style={{ width: '20px', height: '20px' }} /> Войти через Google
                    </button>

                    {loading && <div className="loading-gift"><img src="/StorageImages/Animations/Load.gif" alt="Загрузка..." /></div>}

                    <a href="/users/register">У вас нету аккаунта? Зарегистрироваться</a>
                </form>
            </div>
        </div>
    );
};

export default LoginPage;
