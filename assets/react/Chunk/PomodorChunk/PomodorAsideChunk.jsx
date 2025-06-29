import React, { useEffect, useState } from 'react';
import Loading from "../LoadingChunk/Loading";



const Aside = () => {
    const [data, setData] = useState(null);
    const [activeTab, setActiveTab] = useState('pomodoro');

    useEffect(() => {
        fetch('/api/pomodor/summary')
            .then(res => res.json())
            .then(data => setData(data))
            .catch(err => console.error('Ошибка загрузки:', err));
    }, []);

    if (!data) return <Loading />


    const { todayPomos, todayFocusTime, totalPomodorCount, habitsList, tasksList, pomodorHistory } = data;

    return (
        <aside className="pomodoro-aside">
            <div className="stats">
                <div className="stat-box">
                    <h3>Помидоров сегодня</h3>
                    <span>{todayPomos}</span>
                </div>
                <div className="stat-box">
                    <h3>Общее время фокуса сегодня</h3>
                    <span>{todayFocusTime}</span>
                </div>
                <div className="stat-box">
                    <h3>Всего помидоров</h3>
                    <span>{totalPomodorCount}</span>
                </div>
            </div>
            <nav className="tabs">
                <a onClick={() => setActiveTab('habits')}>Привычки</a>
                <a onClick={() => setActiveTab('tasks')}>Задачи</a>
                <a onClick={() => setActiveTab('pomodoro')}>История</a>
            </nav>
            <div className="content">
                {activeTab === 'habits' && (
                    <section>
                        <h2>Привычки</h2>
                        {habitsList && habitsList.length > 0 ? (
                            <ul>
                                {habitsList.map((habit, i) => (
                                    <li key={i}>
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
                        <h2>Задачи</h2>
                        {tasksList && tasksList.length > 0 ? (
                            <ul>
                                {tasksList.map((task, i) => (
                                    <li key={i}>{task.name}</li>
                                ))}
                            </ul>
                        ) : (
                            <p>Нет задач</p>
                        )}
                    </section>
                )}

                {activeTab === 'pomodoro' && (
                    <section  id="pomodoro">
                        <h2>История Pomodoro</h2>
                        {pomodorHistory && pomodorHistory.length > 0 ? (
                            <div className="pomodoro-cards">
                                {pomodorHistory.map((record, i) => (
                                    <div key={i} className="pomodoro-card">
                                        <h3>{record.title || 'Фокус'}</h3>
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
