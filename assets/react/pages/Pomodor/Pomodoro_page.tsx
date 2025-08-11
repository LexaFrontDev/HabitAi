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

    const [focusMinutes, setFocusMinutes] = useState('25');
    const [TasksTitle, setTasksTitle] = useState('');
    const [breakMinutes, setBreakMinutes] = useState('5');
    const [isBreak, setIsBreak] = useState(false);
    const startTimeRef = useRef<string | null>(localStorage.getItem('pomodoroStartTime') || null);
    const intervalRef = useRef<number | undefined>(undefined);
    const [data, setDataTasks] = useState<any | null>(null);


    const radius = 196;
    const circumference = 2 * Math.PI * radius;
    const [langCode, setLangCode] = useState('en');
    const { t, i18n } = useTranslation('tasks');


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

    if (!i18n.hasResourceBundle(langCode, 'pomodoro')) return <Loading />;


    return (
        <div id="pomodor-page">
            <Sidebar/>
            <div style={{marginLeft: '50px'}}>
                <PanelGroup direction="horizontal">
                    <Panel maxSize={75} defaultSize={30} minSize={20}>
                        <div className="panel-content">
                            <div className="content-panel">
                                <div className="header-panel-center">
                                    <div className="header-text mt-xl-1">
                                        <h4 className="header-title">{t('tasks:tasksHeadText')}</h4>
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
                                        <div className="mt-3 btn-group">
                                            <button
                                                className="btn btn-primary"
                                                onClick={handleStartPause}
                                                disabled={timeLeft === 0 && !isRunning}
                                            >
                                                {isRunning ? 'Пауза' : timeLeft === 0 ? 'Начать заново' : 'Старт'}
                                            </button>
                                            {(isPaused || (isRunning && showCompleteButton)) && (
                                                <button
                                                    className="btn btn-danger"
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
                    <Panel maxSize={25} defaultSize={15} minSize={5}>
                        <div className="panel-content">
                            <div className="line-resize"></div>

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

                {showTasksModal && (
                    <div className="modal-tasks-list">
                        <div className="modal-contents">
                            <h3>Задачи которых можно выполнить</h3>
                            {data && data.map((item: any) => (
                                <div
                                    key={item.habit_id}
                                    onClick={() => {
                                        setTasksTitle(item.title);
                                        setTasksModal(false);
                                    }}
                                >
                                    {item.title}
                                </div>
                            ))}
                            <div className="close-button">
                                <button
                                    className="close-button"
                                    onClick={() => setTasksModal(false)}
                                >
                                    <img src="/Upload/Images/AppIcons/x-circle.svg" alt="Закрыть"/>
                                </button>
                            </div>
                        </div>
                    </div>
                )}
            </div>
            );
            };

            export default Pomodoro;