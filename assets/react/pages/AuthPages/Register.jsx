import React, {useEffect, useState} from 'react';

const RegisterPage = () => {
    const [form, setForm] = useState({ name: '', email: '', password: '' });


    useEffect(() => {
        fetch('/api/auth/check')
            .then(res => {
                if (res.ok) {
                    window.location.href = '/';
                }
            });
    }, []);

    const handleChange = e => setForm({ ...form, [e.target.name]: e.target.value });

    const handleSubmit = async e => {
        e.preventDefault();
        try {
            const res = await fetch('/api/auth/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(form)
            });
            const result = await res.json();
            if (res.ok) window.location.href = '/';
            else alert(result.message || 'Ошибка регистрации');
        } catch {
            alert('Ошибка соединения с сервером');
        }
    };

    return (
        <div className="register-container">
            <div className="register-image d-none d-lg-block" style={{ background: '#a4ac86 url(/StorageImages/Icons/focused.svg) no-repeat center center', backgroundSize: 'cover' }}></div>
            <div className="register-form-wrapper">
                <form onSubmit={handleSubmit} className="register-form">
                    <h2 className="mb-4 text-center fw-bold">Добро пожаловать 👋</h2>
                    <p className="text-muted text-center mb-4">Создайте аккаунт, чтобы стать ближе к своей цели</p>
                    <div className="mb-3">
                        <label className="form-label text-start d-block">Имя</label>
                        <input type="text" className="form-control" name="name" value={form.name} onChange={handleChange} required />
                    </div>
                    <div className="mb-3">
                        <label className="form-label text-start d-block">Email</label>
                        <input type="email" className="form-control" name="email" value={form.email} onChange={handleChange} required />
                    </div>
                    <div className="mb-3">
                        <label className="form-label text-start d-block">Пароль</label>
                        <input type="password" className="form-control" name="password" value={form.password} onChange={handleChange} required />
                    </div>
                    <button type="submit" className="btn btn-primary">Зарегистрироваться</button>
                    <button type="button" className="btn-google">
                        <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" style={{ width: '20px', height: '20px' }} /> Войти через Google
                    </button>
                    <a href="/users/login">У вас уже есть аккаунт? войти</a>
                </form>
            </div>
        </div>
    );
};

export default RegisterPage;
