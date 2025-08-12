import React, { useState, useEffect, useRef } from 'react';
import Sidebar from '../chunk/SideBar';
import { Messages, ErrorAlert, SuccessAlert, IsDoneAlert } from '../chunk/MessageAlertChunk';
import {ImperativePanelGroupHandle, Panel, PanelGroup, PanelResizeHandle} from "react-resizable-panels";
import {Button} from "../../ui/atoms/button/Button";
import {LanguageRequestUseCase} from "../../Aplication/UseCases/language/LanguageRequestUseCase";
import {LanguageApi} from "../../Infrastructure/request/Language/LanguageApi";
import {LangStorage} from "../../Infrastructure/languageStorage/LangStorage";
import {LangStorageUseCase} from "../../Aplication/UseCases/language/LangStorageUseCase";
import {useTranslation} from "react-i18next";
import Loading from "../chunk/LoadingChunk/Loading";
import { PomodoroData } from "../../ui/props/Habits/PomodoroData";
import {formatTaskDateTime} from "../../Domain/Services/Tasks/taskDateFormatter";
import {Task} from "../../ui/props/Tasks/Task";

const LangUseCase = new LanguageRequestUseCase('pomodoro', new LanguageApi());
const langStorage = new LangStorage();
const langUseCase = new LangStorageUseCase(langStorage);


const Pomodoro = () => {
    const [userId, setUserId] = useState(null);
    const [totalTime, setTotalTime] = useState<number>(() => parseInt(localStorage.getItem('pomodoroTotalTime') || '1500', 10));
    const [timeLeft, setTimeLeft] = useState<number>(() => parseInt(localStorage.getItem('pomodoroTimeLeft') || '1500', 10));
    const [isRunning, setIsRunning] = useState(false);
    const [isPaused, setIsPaused] = useState(false);
    const [showModal, setShowModal] = useState(false);
    const [showTasksModal, setTasksModal] = useState(false);
    const [completedTime, setCompletedTime] = useState(0);
    const [showCompleteButton, setShowCompleteButton] = useState(false);
    const [sessionActive, setSessionActive] = useState(false);
    const [dataPomodoro, setDataPomodoro] = useState<PomodoroData | null>(null);
    const [focusMinutes, setFocusMinutes] = useState('25');
    const [TasksTitle, setTasksTitle] = useState('');
    const [breakMinutes, setBreakMinutes] = useState('5');
    const [isBreak, setIsBreak] = useState(false);
    const startTimeRef = useRef<string | null>(localStorage.getItem('pomodoroStartTime') || null);
    const intervalRef = useRef<number | undefined>(undefined);
    const [data, setDataTasks] = useState<any | null>(null);
    const [activeTab, setActiveTab] = useState('Pomodoro');


    const radius = 196;
    const circumference = 2 * Math.PI * radius;
    const [langCode, setLangCode] = useState('en');
    const { t, i18n } = useTranslation('pomodoro');


    useEffect(() => {
        fetch('/api/pomodor/summary')
            .then(res => res.json())
            .then(data => {
                console.log('Полученные данные:', data); // выведет данные в консоль
                setDataPomodoro(data);
            })
            .catch(err => console.error('Ошибка загрузки:', err));
    }, []);



    useEffect(() => {
        const detectLang = async () => {
            const lang = await langUseCase.getLang();
            if (lang) {
                setLangCode(lang);
                await LangUseCase.getTranslations(lang);
            }
        };

        detectLang();
    }, []);

    useEffect(() => {
        fetch('/api/web/user/id')
            .then(res => res.json())
            .then(data => setUserId(data?.userId || null));
    }, []);

    useEffect(() => {
        localStorage.setItem('pomodoroTotalTime', String(totalTime));
        localStorage.setItem('pomodoroTimeLeft', String(timeLeft));
    }, [totalTime, timeLeft]);

    useEffect(() => {
        fetch('/api/get/habits/today')
            .then(res => res.json())
            .then(data => setDataTasks({title: data?.data.title, habit_id: data?.data.habit_id || null}));
    }, []);

    const formatTime = (seconds: number) => {
        const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
        const secs = (seconds % 60).toString().padStart(2, '0');
        return `${mins}:${secs}`;
    };

    const calculateProgress = () => {
        if (totalTime <= 0) return circumference;
        return ((totalTime - timeLeft) / totalTime) * circumference;
    };

    const resetProgress = () => {
        setTimeLeft(totalTime);
        setIsRunning(false);
        setIsPaused(false);
        setShowCompleteButton(false);
        setSessionActive(false);
        clearInterval(intervalRef.current);
    };

    const handleStartPause = () => {
        if (!isRunning) {
            if (timeLeft <= 0) {
                resetProgress();
                return;
            }

            startTimeRef.current = new Date().toISOString();
            localStorage.setItem('pomodoroStartTime', startTimeRef.current);
            setSessionActive(true);

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
            setIsRunning(true);
            setIsPaused(false);
            setShowCompleteButton(true);
        } else {
            clearInterval(intervalRef.current);
            setIsRunning(false);
            setIsPaused(true);
        }
    };

    const handleCompleteSession = async () => {
        clearInterval(intervalRef.current);
        const timeCompleted = totalTime - timeLeft;
        setCompletedTime(timeCompleted);


        if (timeCompleted < 60) {
            Messages("Помидор не должен быть меньше 1 минуты!")
            resetProgress();
            return;
        }

        if (userId && timeCompleted > 0 && startTimeRef.current) {
            await fetch("/api/pomodor/create", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    userId,
                    timeFocus: timeCompleted,
                    timeStart: Math.floor(new Date(startTimeRef.current).getTime() / 1000),
                    timeEnd: Math.floor(Date.now() / 1000),
                    createdDate: Math.floor(Date.now() / 1000),
                }),
            });
        }

        resetProgress();
    };

    const handleEndCycle = async (finishTime: any) => {
        const timeCompleted = totalTime;
        setCompletedTime(timeCompleted);

        if (!isBreak && timeCompleted < 60) {
            Messages("Помидор не должен быть меньше 1 минуты!")
            resetProgress();
            return;
        }

        if (!isBreak && userId && startTimeRef.current) {
            await fetch("/api/pomodor/create", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    title: TasksTitle,
                    userId,
                    timeFocus: timeCompleted,
                    timeStart: Math.floor(new Date(startTimeRef.current).getTime() / 1000),
                    timeEnd: Math.floor(finishTime.getTime() / 1000),
                    createdDate: Math.floor(Date.now() / 1000),
                }),
            });
        }

        if (!isBreak) {
            IsDoneAlert("Фокус завершён! Время отдыха.");
            startTimeRef.current = new Date().toISOString();
            const breakTime = parseInt(breakMinutes) * 60 || 300;
            setTotalTime(breakTime);
            setTimeLeft(breakTime);
            setIsBreak(true);
            setIsRunning(true);
            setIsPaused(false);
            setShowCompleteButton(true);
            intervalRef.current = setInterval(() => {
                setTimeLeft(prev => {
                    if (prev > 0) {
                        return prev - 1;
                    } else {
                        clearInterval(intervalRef.current);
                        IsDoneAlert("Отдых завершён!");
                        setIsBreak(false);
                        setIsRunning(false);
                        setIsPaused(false);
                        setShowCompleteButton(false);
                        setSessionActive(false);
                        return 0;
                    }
                });
            }, 1000);
        }
    };

    const handleSetTime = () => {
        if (sessionActive) {
            Messages("Завершите текущую сессию перед изменением времени");
            return;
        }

        const focus = parseInt(focusMinutes) || 25;
        const rest = parseInt(breakMinutes) || 5;
        setTotalTime(focus * 60);
        setTimeLeft(focus * 60);
        setShowModal(false);
        setIsBreak(false);
    };

    const openSettings = () => {
        if (sessionActive) {
            Messages("Завершите текущую сессию перед изменением времени");
            return;
        }
        setShowModal(true);
    };



    const renderTaskDateTime = (task: Task) => {
        const lines = formatTaskDateTime(task);
        return (
            <div>
                {lines.map((line, index) => (
                    <div key={index}>{line}</div>
                ))}
            </div>
        );
    };


    if (!i18n.hasResourceBundle(langCode, 'pomodoro') || !dataPomodoro) return <Loading />;


    const { todayPomos, todayFocusTime, totalPomodorCount, habitsList, tasksList, pomodorHistory } = dataPomodoro;

    return (
        <div id="pomodor-page">
            <Sidebar/>
            <div style={{marginLeft: '50px'}}>
                <PanelGroup direction="horizontal">
                    <Panel  defaultSize={30} minSize={20}>
                        <div className="panel-content">
                            <div className="content-panel">
                                <div className="header-panel-center">
                                    <div className="header-text mt-xl-1">
                                        <h4 className="header-title">{t('pomodoro:pomodoroHeadText')}</h4>
                                    </div>
                                </div>
                                <div className="pomodoro-section">
                                    <div className="pomodoro-wrapper">
                                        <div className="header">
                                            <h4 onClick={() => setTasksModal(true)}>{TasksTitle || 'Фокус'}</h4>
                                        </div>
                                        <svg width="360" height="360" viewBox="0 0 400 400">
                                            <circle
                                                stroke="rgba(255, 255, 255, 0.1)"
                                                cx="200"
                                                cy="200"
                                                r={radius}
                                                strokeWidth="8"
                                                fill="none"
                                            />
                                            <circle
                                                stroke={isBreak ? "#4CAF50" : "#4772fa"}
                                                transform="rotate(-90 200 200)"
                                                cx="200"
                                                cy="200"
                                                r={radius}
                                                strokeDasharray={circumference}
                                                strokeWidth="8"
                                                strokeDashoffset={circumference - calculateProgress()}
                                                strokeLinecap="round"
                                                fill="none"
                                            />
                                            <text
                                                x="200"
                                                y="210"
                                                textAnchor="middle"
                                                dominantBaseline="middle"
                                                fontSize="48"
                                                fill="#fff"
                                                fontFamily="Arial"
                                                onClick={openSettings}
                                                style={{cursor: sessionActive ? 'default' : 'pointer'}}
                                            >
                                                {formatTime(timeLeft)}
                                            </text>
                                        </svg>
                                        <div className="triger-line">
                                            <button
                                                className="triger"
                                                onClick={handleStartPause}
                                                disabled={timeLeft === 0 && !isRunning}
                                            >
                                                {isRunning ? 'Пауза' : timeLeft === 0 ? 'Начать заново' : 'Старт'}
                                            </button>
                                            {(isPaused || (isRunning && showCompleteButton)) && (
                                                <button
                                                    className="triger-delete"
                                                    onClick={handleCompleteSession}
                                                >
                                                    Завершить
                                                </button>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Panel>
                    <PanelResizeHandle/>
                    <Panel  defaultSize={15} minSize={5}>
                        <div className="panel-content">
                            <div className="line-resize"></div>
                            <div className="content-panel">
                                <div className="header-panel-center">
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
                                </div>

                                    <nav className="tabs">
                                        <a onClick={() => setActiveTab('Habits')}>Привычки</a>
                                        <a onClick={() => setActiveTab('tasks')}>Задачи</a>
                                        <a onClick={() => setActiveTab('Pomodoro')}>История</a>
                                    </nav>

                                    {activeTab === 'Habits' && (
                                        <section>
                                            <h2>Привычки</h2>
                                            {habitsList && habitsList.length > 0 ? (
                                                <ul className="habits-ul">
                                                    {habitsList.map((habit, i) => (
                                                        <li className="habits-li" key={i}>
                                                            <span>Название привычки: {habit.title}</span>
                                                            <br/>
                                                            <span>Количество: {habit.goal_in_days}</span>
                                                            <br/>
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
                                                <ul className="tasks-list">
                                                    {tasksList.map((task, i) => {
                                                        return (
                                                            <li key={i} className="task-item tasks-list-element" onClick={() => setTasksTitle(task.title)}>
                                                                <span className="info-span">ℹ️ Нажмите чтобы добавить в помо</span>
                                                                <h3 className="task-title">Задача :  {task.title}</h3>
                                                                <p className="task-description">Описание : {task.description}</p>
                                                                {renderTaskDateTime(task)}
                                                            </li>
                                                        );
                                                    })}
                                                </ul>
                                            ) : (
                                                <p>Нет задач</p>
                                            )}
                                        </section>
                                    )}

                                    {activeTab === 'Pomodoro' && (
                                        <section id="pomodoro">
                                            <h2>История Помодора</h2>
                                            {pomodorHistory && pomodorHistory.length > 0 ? (
                                                <div className="pomodoro-cards">
                                                    {pomodorHistory.map((record, i) => {
                                                        console.log(pomodorHistory);
                                                        return (
                                                            <div key={i} className="pomodoro-card">
                                                                <div className="left">
                                                                    <span>{record.startTime}–{record.endTime}</span>
                                                                    <h3>{record.title || 'Фокус'}</h3>
                                                                </div>
                                                                <div className="period-label">{record.periodLabel}</div>
                                                            </div>
                                                        );
                                                    })}
                                                </div>
                                            ) : (
                                                <p>Пока нет истории Pomodoro</p>
                                            )}
                                        </section>
                            )}
                        </div>
            </div>
        </Panel>
                </PanelGroup>
            </div>

            {showModal && (
                <div className="modal-overlay">
                    <div className="modal-content">
                        <h3>Настройки таймера</h3>
                        <input
                            type="number"
                            min="1"
                            placeholder="Время фокуса (мин)"
                            className="time-input"
                            value={focusMinutes}
                            onChange={e => setFocusMinutes(e.target.value)}
                        />
                        <input
                            type="number"
                            min="1"
                            placeholder="Время отдыха (мин)"
                            className="time-input"
                            value={breakMinutes}
                            onChange={e => setBreakMinutes(e.target.value)}
                        />
                        <button className="btn btn-success" onClick={handleSetTime}>
                            Сохранить
                        </button>
                        <button className="btn btn-outline-danger mt-2" onClick={() => setShowModal(false)}>
                            Закрыть
                        </button>
                    </div>
                </div>
            )}
        </div>
    );
};

export default Pomodoro;