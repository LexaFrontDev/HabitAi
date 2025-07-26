import React, { useEffect, useState } from 'react';
import { BrowserRouter, Routes, Route, useLocation, useNavigate } from 'react-router-dom';
import Loading from './pages/chunk/LoadingChunk/Loading';

import Profile from './pages/Profile';
import Pomodoro from "./pages/Pomodor/Pomodoro_page";
import Habits_page from "./pages/Habits/Habits_page";
import Lending from "./pages/main/Main";
import LoginPage from "./pages/AuthPages/Login";
import RegisterPage from "./pages/AuthPages/Register";
import TasksPage from "./pages/Tasks/TasksPage";
import MatricPage from "./pages/MatricPage/MatricPage";
import Premium from "./pages/main/pages/Premium";
import PremiumPage from "./pages/main/pages/Premium";
import Static from "./pages/Static/StaticPage";

const RouterDom = () => {
    const [isAuthenticated, setIsAuthenticated] = useState(null);
    const location = useLocation();
    const navigate = useNavigate();

    useEffect(() => {
        const excluded = ['/', '/users/login', '/users/register'];
        if (!excluded.includes(location.pathname)) {
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
                    const lastPage = localStorage.getItem('last_page') || '/tasks';
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
            <Route path="/profile" element={<Profile />} />
            <Route path="/pomodoro" element={<Pomodoro />} />
            <Route path="/habits" element={<Habits_page />} />
            <Route path="/users/register" element={<RegisterPage />} />
            <Route path="/users/login" element={<LoginPage />} />
            <Route path="/tasks" element={<TasksPage />} />
            <Route path="/matric" element={<MatricPage />} />
            <Route path="/profile/statistics" element={<Static />} />
            <Route path="/premium" element={<PremiumPage isAuthenticated={isAuthenticated} />} />
        </Routes>
    );
};

const WrappedRouterDom = () => (
    <BrowserRouter>
        <RouterDom />
    </BrowserRouter>
);

export default WrappedRouterDom;
