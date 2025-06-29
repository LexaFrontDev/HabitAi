import React, { useEffect, useState } from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Home from './pages/main/Home';
import Profile from './pages/Profile';
import Pomodoro from "./pages/Pomodor/Pomodoro_page";
import Habits_page from "./pages/Habits/Habits_page";
import Lending from "./pages/main/Main";
import LoginPage from "./pages/AuthPages/Login";
import RegisterPage from "./pages/AuthPages/Register";
import TasksPage from "./pages/Tasks/TasksPage";

const RouterDom = () => {
    const [isAuthenticated, setIsAuthenticated] = useState(null);

    useEffect(() => {
        const checkAuth = async () => {
            try {
                const res = await fetch('/api/auth/check');
                setIsAuthenticated(res.ok);
            } catch {
                setIsAuthenticated(false);
            }
        };
        checkAuth();
    }, []);

    if (isAuthenticated === null) return null;

    return (
        <BrowserRouter>
            <Routes>
                <Route path="/" element={isAuthenticated ? <Home /> : <Lending />} />
                <Route path="/profile" element={<Profile />} />
                <Route path="/pomodoro" element={<Pomodoro />} />
                <Route path="/Habits" element={<Habits_page />} />
                <Route path="/users/register" element={<RegisterPage />} />
                <Route path="/users/login" element={<LoginPage />} />
                <Route path="/tasks" element={<TasksPage />} />

            </Routes>
        </BrowserRouter>
    );
};

export default RouterDom;
