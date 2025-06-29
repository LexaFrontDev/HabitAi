import React, { useState, useEffect, useRef } from 'react';
import Sidebar from '../../Chunk/Sidebar';
import PomodorAsideChunk from "../../Chunk/PomodorChunk/PomodorAsideChunk";

const Pomodoro = () => {
    const [userId, setUserId] = useState(null);
    const [totalTime, setTotalTime] = useState(() => parseInt(localStorage.getItem('pomodoroTotalTime')) || 0);
    const [timeLeft, setTimeLeft] = useState(() => parseInt(localStorage.getItem('pomodoroTimeLeft')) || 0);
    const [isRunning, setIsRunning] = useState(false);
    const [showModal, setShowModal] = useState(false);
    const [showTasksModal, setTasksModal] = useState(false);

    const [focusMinutes, setFocusMinutes] = useState('');
    const [TasksTitle, setTasksTitle] = useState('');
    const [breakMinutes, setBreakMinutes] = useState('');
    const [isBreak, setIsBreak] = useState(false);
    const startTimeRef = useRef(localStorage.getItem('pomodoroStartTime') || null);
    const intervalRef = useRef(null);
    const circumference = 2 * Math.PI * 16;
    const [data, setDataTasks] = useState(null);

    useEffect(() => {
        fetch('/api/web/user/id')
            .then(res => res.json())
            .then(data => setUserId(data?.userId || null));
    }, []);

    useEffect(() => {
        localStorage.setItem('pomodoroTotalTime', totalTime);
        localStorage.setItem('pomodoroTimeLeft', timeLeft);
    }, [totalTime, timeLeft]);

    useEffect(() => {
        fetch('/api/get/habits/today')
            .then(res => res.json())
            .then(data => setDataTasks({title: data?.data.title, habit_id: data?.data.habit_id || null}));
    }, []);

    const formatTime = (seconds) => {
        const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
        const secs = (seconds % 60).toString().padStart(2, '0');
        return `${mins}:${secs}`;
    };

    const updateProgress = () => {
        const offset = totalTime > 0 ? ((totalTime - timeLeft) / totalTime) * circumference : 0;
        return `${offset} ${circumference}`;
    };

    const handleStartPause = () => {
        if (!isRunning) {
            if (!startTimeRef.current) {
                startTimeRef.current = new Date();
                localStorage.setItem('pomodoroStartTime', startTimeRef.current);
            }
            intervalRef.current = setInterval(() => {
                setTimeLeft(prev => {
                    if (prev > 0) {
                        return prev - 1;
                    } else {
                        clearInterval(intervalRef.current);
                        handleEndCycle(new Date());
                        return 0;
                    }
                });
            }, 1000);
        } else {
            clearInterval(intervalRef.current);
        }
        setIsRunning(!isRunning);
    };

    const handleEndCycle = async (finishTime) => {
        if (!isBreak) {
            alert('Фокус завершён! Время отдыха.');
            if (userId) {
                await fetch("/api/pomodor/create", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        userId,
                        timeFocus: totalTime,
                        timeStart: Math.floor(new Date(startTimeRef.current).getTime() / 1000),
                        timeEnd: Math.floor(finishTime.getTime() / 1000),
                        createdDate: Math.floor(new Date().getTime() / 1000),
                    }),
                });
            }
            startTimeRef.current = new Date();
            setTotalTime(parseInt(breakMinutes) * 60);
            setTimeLeft(parseInt(breakMinutes) * 60);
            setIsBreak(true);
            setIsRunning(true);
            intervalRef.current = setInterval(() => {
                setTimeLeft(prev => {
                    if (prev > 0) {
                        return prev - 1;
                    } else {
                        clearInterval(intervalRef.current);
                        alert('Отдых завершён!');
                        setIsBreak(false);
                        setIsRunning(false);
                        return 0;
                    }
                });
            }, 1000);
        }
    };

    const handleSetTime = () => {
        const focus = parseInt(focusMinutes);
        const rest = parseInt(breakMinutes);
        if (!focus || !rest) return;
        setTotalTime(focus * 60);
        setTimeLeft(focus * 60);
        setShowModal(false);
        setIsBreak(false);
    };

    return (
        <div id="pomodor-page">
            <Sidebar />
            <PomodorAsideChunk />
            <div className="app-nav">
                <div className="nav-left-side">
                    <h3 className="header">Помодоро/фокус</h3>
                </div>
            </div>
            <div id="content">
                <section className="container mt-4 text-center pomodoro-section">
                    <div className="header">
                        <h4 onClick={() => setTasksModal(true)}>{TasksTitle || 'Фокус'}</h4>
                    </div>
                    <div className="pomodoro-wrapper" onClick={() => setShowModal(true)}>
                        <svg viewBox="0 0 36 36">
                            <path className="bg-circle" fill="none" stroke="#eee" strokeWidth="2" d="M18 2 a 16 16 0 1 1 0 32 a 16 16 0 1 1 0 -32" />
                            <path className="progress-circle" fill="none" stroke="#000" strokeWidth="2" strokeLinecap="round" transform="rotate(-90 18 18)" strokeDasharray={updateProgress()} d="M18 2 a 16 16 0 1 1 0 32 a 16 16 0 1 1 0 -32" />
                            <text id="timer-text" x="50%" y="50%" textAnchor="middle" dy=".3em">{formatTime(timeLeft)}</text>
                        </svg>
                    </div>
                    <div className="mt-3 btn-group">
                        <button className="btn btn-primary" onClick={handleStartPause}>{isRunning ? 'Пауза' : 'Старт'}</button>
                    </div>
                </section>
            </div>
            {showModal && (
                <div className="modal-overlay">
                    <div className="modal-content">
                        <h3>Настройки</h3>
                        <input type="number" min="1" placeholder="Время фокуса (мин)" className="time-input" value={focusMinutes} onChange={e => setFocusMinutes(e.target.value)} />
                        <input type="number" min="1" placeholder="Время отдыха (мин)" className="time-input" value={breakMinutes} onChange={e => setBreakMinutes(e.target.value)} />
                        <button className="btn btn-success" onClick={handleSetTime}>Сохранить</button>
                        <button className="btn btn-outline-danger mt-2" onClick={() => setShowModal(false)}>Закрыть</button>
                    </div>
                </div>
            )}
            {showTasksModal && (
                <div className="modal-tasks-list">
                    <div className="modal-contents">
                        <h3>Задачи которых можно выполнить</h3>

                        {data && data.map((item) => (
                            <div key={item.habit_id} onClick={() => {
                                setTasksTitle(item.title);
                                setTasksModal(false);
                            }}>
                                {item.title}
                            </div>
                        ))}


                        <div className="close-button">
                            <button className="close-button"
                                    onClick={() => setTasksModal(false)}><img src="/Upload/Images/AppIcons/x-circle.svg" alt="Закрыть"/>
                            </button>
                        </div>

                    </div>

                </div>
            )}
        </div>
    );
};

export default Pomodoro;
