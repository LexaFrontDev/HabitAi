import React, { useEffect, useState } from 'react';
import Loading from '../../Chunk/LoadingChunk/Loading';

const Lending = () => {
    const [data, setData] = useState(null);

    useEffect(() => {
        fetch('/api/landing/data')
            .then(res => res.json())
            .then(data => setData(data));
    }, []);

    useEffect(() => {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('appear');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        const elements = document.querySelectorAll('.fade-in');
        elements.forEach(el => observer.observe(el));

        return () => observer.disconnect();
    }, [data]);

    if (!data) return <Loading />;

    return (
        <div id="lending">
            <nav className="navbar shadow-sm border-bottom">
                <div className="container d-flex justify-content-between align-items-center py-2">
                    <span className="navbar-brand text-primary fw-bold">TaskFlow</span>
                    <div>
                        <a href="/users/login" className="btn btn-outline-secondary me-2">Войти</a>
                        <a href="/users/register" className="btn btn-secondary">Регистрация</a>
                    </div>
                </div>
            </nav>

            <section className="hero d-flex align-items-center position-relative overflow-hidden">
                <div className="container">
                    <div className="row align-items-center">
                        <div className="col-lg-6 d-none d-lg-block">
                            <div className="hero-image rounded shadow-lg overflow-hidden">
                                <img src="/StorageImages/Icons/FonLogos.png" alt="TaskFlow Illustration" className="img-fluid" />
                            </div>
                        </div>
                        <div className="col-lg-6 text-center text-lg-start slide-in-right appear z-2 mt-4 mt-lg-0">
                            <h1 className="display-4 fw-bold mb-3">Развивайся на 1% каждый день</h1>
                            <p className="lead text-muted mb-4">Таймер помодор, Список Привычек, и Матрица Эйзенхауэра — всё в одном месте</p>
                            <a href="/users/register" className="btn btn-lg btn-primary">Начать сейчас</a>
                        </div>
                    </div>
                </div>
            </section>

            <section className="features container py-5">
                <div className="row g-4 justify-content-center">
                    {data.features?.map((feature, index) => (
                        <div key={index} className="col-sm-6 col-md-4 col-lg-3 fade-in">
                            <a href={feature.url} className="text-decoration-none text-reset">
                                <div className="card text-center p-4 h-100">
                                    <img src={`/StorageImages/${feature.icon}`} alt={feature.title} height="64" className="mb-3" />
                                    <h5 className="fw-semibold">{feature.title}</h5>
                                    <p className="text-muted small">{feature.desc}</p>
                                </div>
                            </a>
                        </div>
                    ))}
                </div>
            </section>

            <section className="testimonials container py-5">
                <h3 className="text-center mb-4">Отзывы наших пользователей</h3>
                <div className="row g-4 justify-content-center">
                    {data.reviews?.map((review, index) => (
                        <div key={index} className="col-md-4 fade-in">
                            <div className="card p-3">
                                <blockquote className="blockquote mb-0">
                                    <p className="mb-2">"{review.comment}"</p>
                                    <div className="blockquote-footer text-muted">{review.name}</div>
                                </blockquote>
                            </div>
                        </div>
                    ))}
                </div>
            </section>

            <footer className="text-center py-4 border-top fade-in">
                <small className="text-muted">
                    © {new Date().getFullYear()} TaskFlow — <a href="/privacy" className="text-decoration-none text-primary">Политика конфиденциальности</a>
                </small>
            </footer>
        </div>
    );
};

export default Lending;
