import React, { useEffect, useState, useRef } from 'react';
import Loading from "../LoadingChunk/Loading";

const Aside = () => {
    const [data, setData] = useState(null);
    const [activeTab, setActiveTab] = useState('pomodoro');
    const [width, setWidth] = useState(() => {
        const savedWidth = localStorage.getItem('asideWidth');
        return savedWidth ? parseInt(savedWidth) : 300;
    });
    const [isResizing, setIsResizing] = useState(false);
    const asideRef = useRef(null);

    // Рассчитываем коэффициент масштабирования (от 0.7 до 1)
    const scaleFactor = Math.min(Math.max(width / 400, 0.7), 1);

    useEffect(() => {
        fetch('/api/pomodor/summary')
            .then(res => res.json())
            .then(data => setData(data))
            .catch(err => console.error('Ошибка загрузки:', err));
    }, []);

    useEffect(() => {
        const handleMouseMove = (e) => {
            if (!isResizing) return;
            const newWidth = asideRef.current.getBoundingClientRect().right - e.clientX;
            setWidth(Math.min(Math.max(newWidth, 200), 650));
        };

        const handleMouseUp = () => {
            setIsResizing(false);
            localStorage.setItem('asideWidth', width.toString());
        };

        if (isResizing) {
            document.addEventListener('mousemove', handleMouseMove);
            document.addEventListener('mouseup', handleMouseUp);
        }

        return () => {
            document.removeEventListener('mousemove', handleMouseMove);
            document.removeEventListener('mouseup', handleMouseUp);
        };
    }, [isResizing, width]);

    if (!data) return <Loading />;

    const { todayPomos, todayFocusTime, totalPomodorCount, habitsList, tasksList, pomodorHistory } = data;

    return (
        <aside
            className="pomodoro-aside"
            ref={asideRef}
            style={{
                width: `${width}px`,
                transition: isResizing ? 'none' : 'width 0.2s ease',
                position: 'fixed',
                right: 0,
                top: 0,
                bottom: 0,
                fontSize: `${scaleFactor * 100}%` // Масштабируем базовый размер шрифта
            }}
        >
            <div
                className="resize-handle"
                onMouseDown={() => setIsResizing(true)}
                style={{
                    width: '10px',
                    height: '100%',
                    cursor: 'col-resize',
                    backgroundColor: isResizing ? '#4a76a8' : 'transparent',
                    position: 'absolute',
                    left: 0,
                    top: 0,
                    zIndex: 10,
                    ':hover': {
                        backgroundColor: '#4a76a8'
                    }
                }}
            />

            <div className="stats" style={{ fontSize: `${scaleFactor * 1}em` }}>
                <div className="stat-box">
                    <h3 style={{ fontSize: `${scaleFactor * 1.2}em` }}>Помидоров сегодня</h3>
                    <span style={{ fontSize: `${scaleFactor * 1.5}em` }}>{todayPomos}</span>
                </div>
                <div className="stat-box">
                    <h3 style={{ fontSize: `${scaleFactor * 1.2}em` }}>Общее время фокуса сегодня</h3>
                    <span style={{ fontSize: `${scaleFactor * 1.5}em` }}>{todayFocusTime}</span>
                </div>
                <div className="stat-box">
                    <h3 style={{ fontSize: `${scaleFactor * 1.2}em` }}>Всего помидоров</h3>
                    <span style={{ fontSize: `${scaleFactor * 1.5}em` }}>{totalPomodorCount}</span>
                </div>
            </div>

            <nav className="tabs" style={{ fontSize: `${scaleFactor * 1.1}em` }}>
                <a onClick={() => setActiveTab('habits')}>Привычки</a>
                <a onClick={() => setActiveTab('tasks')}>Задачи</a>
                <a onClick={() => setActiveTab('pomodoro')}>История</a>
            </nav>

            <div className="content" style={{ fontSize: `${scaleFactor * 1}em` }}>
                {activeTab === 'habits' && (
                    <section>
                        <h2 style={{ fontSize: `${scaleFactor * 1.3}em` }}>Привычки</h2>
                        {habitsList && habitsList.length > 0 ? (
                            <ul className="habits-ul">
                                {habitsList.map((habit, i) => (
                                    <li className="habits-li" key={i} style={{ fontSize: `${scaleFactor * 1}em` }}>
                                        <span>Название привычки: {habit.title}</span>
                                        <br />
                                        <span>Количество: {habit.goal_in_days}</span>
                                        <br />
                                        <span>Время напоминание: {habit.notification_date}</span>
                                    </li>
                                ))}
                            </ul>
                        ) : (
                            <p>Нет привычек</p>
                        )}
                    </section>
                )}

                {activeTab === 'tasks' && (
                    <section>
                        <h2 style={{ fontSize: `${scaleFactor * 1.3}em` }}>Задачи</h2>
                        {tasksList && tasksList.length > 0 ? (
                            <ul>
                                {tasksList.map((task, i) => (
                                    <li key={i} style={{ fontSize: `${scaleFactor * 1}em` }}>{task.name}</li>
                                ))}
                            </ul>
                        ) : (
                            <p>Нет задач</p>
                        )}
                    </section>
                )}

                {activeTab === 'pomodoro' && (
                    <section id="pomodoro">
                        <h2 style={{ fontSize: `${scaleFactor * 1.3}em` }}>История Pomodoro</h2>
                        {pomodorHistory && pomodorHistory.length > 0 ? (
                            <div className="pomodoro-cards">
                                {pomodorHistory.map((record, i) => (
                                    <div key={i} className="pomodoro-card" style={{ fontSize: `${scaleFactor * 1}em` }}>
                                        <h3 style={{ fontSize: `${scaleFactor * 1.1}em` }}>{record.title || 'Фокус'}</h3>
                                        <span>{record.startTime}–{record.endTime}</span>
                                        <div className="period-label">{record.periodLabel}</div>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <p>Пока нет истории Pomodoro</p>
                        )}
                    </section>
                )}
            </div>
        </aside>
    );
};

export default Aside;