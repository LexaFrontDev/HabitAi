import React, { useState, useEffect, useRef } from 'react';
import Sidebar from '../chunk/SideBar';
import { Messages, ErrorAlert, SuccessAlert, IsDoneAlert } from '../chunk/MessageAlertChunk';
import {ImperativePanelGroupHandle, Panel, PanelGroup, PanelResizeHandle} from "react-resizable-panels";
import {LanguageRequestUseCase} from "../../Services/language/LanguageRequestUseCase";

import {LangStorage} from "../../Services/languageStorage/LangStorage";
import {LangStorageUseCase} from "../../Services/language/LangStorageUseCase";
import {useTranslation} from "react-i18next";
import Loading from "../chunk/LoadingChunk/Loading";
import { PomodoroData } from "../../ui/props/Habits/PomodoroData";

import {Task} from "../../ui/props/Tasks/Task";

import {PomodoroService} from "../../Services/Pomodoro/PomodoroService";
import {usePomodoroTimer} from "../../Services/Pomodoro/PomodoroTimer";
import {ToastContainer} from "react-toastify";
import {LanguageApi} from "../../Services/language/LanguageApi";
import {formatTaskDateTime} from "../../Services/Tasks/taskDateFormatter";
import {CtnServices} from "../../Services/Ctn/CtnServices";
import {IndexedDBCacheService} from "../../Services/Cache/IndexedDBCacheService";

const ctnService = new CtnServices(new IndexedDBCacheService())
const PomodoroUseCase = new PomodoroService(ctnService);
const LangUseCase = new LanguageRequestUseCase(new LanguageApi());
const langStorage = new LangStorage();
const langUseCase = new LangStorageUseCase(langStorage);


const Pomodoro = () => {
    const [dataPomodoro, setDataPomodoro] = useState<PomodoroData | null>(null);
    const [activeTab, setActiveTab] = useState('Pomodoro');
    const [langCode, setLangCode] = useState('en');
    const { t, i18n } = useTranslation('translation');
    const [translationsLoaded, setTranslationsLoaded] = useState<boolean>(false);
    const {
        totalTime, timeLeft, isRunning, handleStartPause, handleCompleteSession, setTasksTitle, TasksTitle, setTotalTime, setTimeLeft,  isPaused, sessionActive, isBreak,
        showCompleteButton, breakMinutes, focusMinutes, setBreakMinutes, setFocusMinutes, setShowModal, showModal, handleSetTime, calculateProgress, formatTime, radius, circumference,
    } = usePomodoroTimer(PomodoroUseCase);

    useEffect(() => {
        const fetchPomodoro = async () => {
            const result = await PomodoroUseCase.getPomdoroSummary();

            if (result) {
                setDataPomodoro(result[0]);
            }
        };
        fetchPomodoro();
    }, []);

    useEffect(() => {
        const detectLang = async () => {
            try {
                const lang = await langUseCase.getLang();
                if (lang) {
                    setLangCode(lang);
                    await LangUseCase.getTranslations(lang);
                }
            } catch (err) {
                console.error(err);
            } finally {
                setTranslationsLoaded(true);
            }
        };
        detectLang();
    }, []);

    useEffect(() => {
        localStorage.setItem('pomodoroTotalTime', String(totalTime));
        localStorage.setItem('pomodoroTimeLeft', String(timeLeft));
    }, [totalTime, timeLeft]);


    const openSettings = () => {
        if (sessionActive) {
            Messages("Завершите  сессию перед изменением времени");
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


    if (!translationsLoaded || !dataPomodoro) return <Loading />;


    const { todayPomos, todayFocusTime, totalPomodorCount, habitsList, tasksList, pomodorHistory } = dataPomodoro;

    return (
        <div id="pomodor-page">
            <Sidebar/>

            <ToastContainer position="top-right" autoClose={3000} />
            <div style={{marginLeft: '50px'}}>
                <PanelGroup direction="horizontal">
                    <Panel  defaultSize={30} minSize={20}>
                        <div className="panel-content">
                            <div className="content-panel">
                                <div className="header-panel-center">
                                    <div className="header-text mt-xl-1">
                                        <h4 className="header-title">{t('pomodoro.pomodoroHeadText')}</h4>
                                    </div>
                                </div>
                                <div className="pomodoro-section">
                                    <div className="pomodoro-wrapper">
                                        <div className="header">
                                            <h4>{TasksTitle || 'Фокус'}</h4>
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