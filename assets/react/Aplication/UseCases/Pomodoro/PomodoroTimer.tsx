import { useEffect, useRef, useState } from "react";
import {PomodoroService} from "./PomodoroService";
import {PomodoroCreateType} from "../../../ui/props/Pomodoro/PomodoroCreateType";
import {IsDoneAlert, Messages} from "../../../pages/chunk/MessageAlertChunk";


export const usePomodoroTimer = (PomodoroUseCase: PomodoroService) => {
    const [totalTime, setTotalTime] = useState(1500);
    const [timeLeft, setTimeLeft] = useState(1500);
    const [isRunning, setIsRunning] = useState(false);
    const [isPaused, setIsPaused] = useState(false);
    const [sessionActive, setSessionActive] = useState(false);
    const [isBreak, setIsBreak] = useState(false);
    const [completedTime, setCompletedTime] = useState(0);
    const [TasksTitle, setTasksTitle] = useState('');
    const startTimeRef = useRef<string | null>(null);
    // @ts-ignore
    const intervalRef = useRef<number>();
    const [showCompleteButton, setShowCompleteButton] = useState(false);
    const [focusMinutes, setFocusMinutes] = useState('25');
    const [breakMinutes, setBreakMinutes] = useState('5');
    const [showModal, setShowModal] = useState(false);
    const radius = 196;
    const circumference = 2 * Math.PI * radius;


    const handleStartPause = () => {
        if (!isRunning) {
            if (timeLeft <= 0) {
                reset();
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
            reset();
            return;
        }




        if (timeCompleted > 0 && startTimeRef.current) {
            const pomodoroData: PomodoroCreateType = {
                title: TasksTitle,
                timeFocus: timeCompleted,
                timeStart: Math.floor(new Date(startTimeRef.current).getTime() / 1000),
                timeEnd: Math.floor(Date.now() / 1000),
                created_date: Math.floor(Date.now() / 1000),
            };

            await PomodoroUseCase.createPomodro(pomodoroData);
        }
        reset();
    };


    const handleEndCycle = async (finishTime: any) => {
        const timeCompleted = totalTime;
        setCompletedTime(timeCompleted);

        if (!isBreak && timeCompleted < 60) {
            Messages("Помидор не должен быть меньше 1 минуты!")
            reset();
            return;
        }


        if (!isBreak && startTimeRef.current) {
            const pomodoroData: PomodoroCreateType = {
                title: TasksTitle,
                timeFocus: timeCompleted,
                timeStart: Math.floor(new Date(startTimeRef.current).getTime() / 1000),
                timeEnd: Math.floor(Date.now() / 1000),
                created_date: Math.floor(Date.now() / 1000),
            };
            console.log(pomodoroData);
            await PomodoroUseCase.createPomodro(pomodoroData);
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

    const reset = () => {
        clearInterval(intervalRef.current);
        setTimeLeft(totalTime);
        setIsRunning(false);
        setIsPaused(false);
        setSessionActive(false);
        setIsBreak(false);
    };


    const formatTime = (seconds: number) => {
        const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
        const secs = (seconds % 60).toString().padStart(2, '0');
        return `${mins}:${secs}`;
    };

    const calculateProgress = () => {
        if (totalTime <= 0) return circumference;
        return ((totalTime - timeLeft) / totalTime) * circumference;
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

    return {
        totalTime,
        timeLeft,
        handleSetTime,
        isRunning,
        isPaused,
        sessionActive,
        setShowCompleteButton,
        showCompleteButton,
        focusMinutes,
        setFocusMinutes,
        breakMinutes,
        isBreak,
        completedTime,
        setTasksTitle,
        TasksTitle,
        setBreakMinutes,
        setShowModal,
        showModal,
        handleStartPause,
        handleEndCycle,
        handleCompleteSession,
        calculateProgress,
        formatTime,
        radius,
        circumference,
        reset,
        setTotalTime,
        setTimeLeft,
    };
};
