import React, { useEffect, useState } from 'react';
import { BrowserRouter, Routes, Route, useLocation, useNavigate } from 'react-router-dom';
import Loading from './pages/chunk/LoadingChunk/Loading';

import Profile from './pages/Profile';
import Pomodoro from "./pages/Pomodor/PomodoroPage";
import Habits_page from "./pages/Habits/HabitsPage";
import TasksPage from "./pages/Tasks/TasksPage";
//@ts-ignore
import MatricPage from "./pages/MatricPage/MatricPage";
import Premium from "./pages/main/pages/Premium";
import PremiumPage from "./pages/main/pages/Premium";
import Lending from "./pages/main/pages/Main";
import LoginPage from "./pages/AuthPages/Login";
import RegisterPage from "./pages/AuthPages/Register";
import {AuthCheck} from "./ui/props/Auth/AuthCheck";
import HabitCalendar from "./pages/Statistic/StatisticPage";

const RouterDom = () => {
    const [isAuthenticated, setIsAuthenticated] = useState<boolean|null>(null);
    const location = useLocation();
    const navigate = useNavigate();

    useEffect(() => {
        const allowedPages = [
            '/tasks',
            '/habits',
            '/matric',
            '/pomodoro',
            '/profile',
            '/profile/statistics'
        ];

        if (allowedPages.includes(location.pathname.toLowerCase())) {
            localStorage.setItem('last_page', location.pathname);
        }
    }, [location.pathname]);

    useEffect(() => {
        const checkAuth = async () => {
            try {
                const res = await fetch('/api/auth/check');
                const ok = res.ok;
                setIsAuthenticated(ok);

                if (ok && location.pathname === '/') {
                    const lastPage = localStorage.getItem('last_page') || '/Tasks';
                    navigate(lastPage, { replace: true });
                }
            } catch {
                setIsAuthenticated(false);
            }
        };
        checkAuth();
    }, []);

    if (isAuthenticated === null) return <Loading />;

    return (
        <Routes>
            <Route path="/" element={<Lending />} />

            <Route
                path="/profile"
                element={
                    <AuthCheck isAuthenticated={isAuthenticated}>
                        <Profile />
                    </AuthCheck>
                }
            />

            <Route
                path="/pomodoro"
                element={
                    <AuthCheck isAuthenticated={isAuthenticated}>
                        <Pomodoro />
                    </AuthCheck>
                }
            />

            <Route
                path="/habits"
                element={
                    <AuthCheck isAuthenticated={isAuthenticated}>
                        <Habits_page />
                    </AuthCheck>
                }
            />

            <Route path="/users/register" element={<RegisterPage />} />
            <Route path="/users/login" element={<LoginPage />} />

            <Route
                path="/tasks"
                element={
                    <AuthCheck isAuthenticated={isAuthenticated}>
                        <TasksPage />
                    </AuthCheck>
                }
            />

            <Route
                path="/matric"
                element={
                    <AuthCheck isAuthenticated={isAuthenticated}>
                        <MatricPage />
                    </AuthCheck>
                }
            />

            <Route
                path="/premium"
                element={
                        <PremiumPage isAuthenticated={isAuthenticated} />
                }
            />

            <Route
                path="/profile/statistics"
                element={
                    <AuthCheck isAuthenticated={isAuthenticated}>
                        <HabitCalendar />
                    </AuthCheck>
                }
            />
        </Routes>
    );
};

const WrappedRouterDom = () => (
    <BrowserRouter>
        <RouterDom />
    </BrowserRouter>
);

export default WrappedRouterDom;
