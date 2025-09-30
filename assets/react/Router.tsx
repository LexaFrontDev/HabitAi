import React, { useEffect, useState } from 'react';
import { BrowserRouter, Routes, Route, useLocation, useNavigate } from 'react-router-dom';
import Loading from './pages/chunk/LoadingChunk/Loading';

import Profile from './pages/Profile';
import Pomodoro from "./pages/Pomodor/PomodoroPage";
import Habits_page from "./pages/Habits/HabitsPage";
import TasksPage from "./pages/Tasks/TasksPage";
//@ts-ignore
import MatricPage from "./pages/MatricPage/MatricPage";
import PremiumPage from "./pages/main/pages/Premium";
import Lending from "./pages/main/pages/Main";
import LoginPage from "./pages/AuthPages/Login";
import RegisterPage from "./pages/AuthPages/Register";
import { AuthCheck } from "./ui/props/Auth/AuthCheck";
import HabitCalendar from "./pages/Statistic/StatisticPage";

const RouterDom = () => {
    const [isAuthenticated, setIsAuthenticated] = useState<boolean | null>(null);
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
                const data = await res.json();
                const ok = res.ok;
                setIsAuthenticated(ok);

                if(ok){
                    subscribePush();
                }

                if (ok && location.pathname === '/') {
                    const lastPage = localStorage.getItem('last_page') || '/tasks';
                    navigate(lastPage, { replace: true });
                }

                const protectedPages = [
                    '/tasks',
                    '/habits',
                    '/matric',
                    '/pomodoro',
                    '/profile',
                    '/profile/statistics',
                    '/premium'
                ];
                if (!ok && protectedPages.includes(location.pathname.toLowerCase())) {
                    navigate('/users/login', { replace: true });
                }

            } catch {
                setIsAuthenticated(false);
                if (!['/users/login', '/users/register'].includes(location.pathname.toLowerCase())) {
                    navigate('/users/login', { replace: true });
                }
            }
        };
        checkAuth();
    }, [location.pathname, navigate]);





    const getPlatform = () => {
        const ua = navigator.userAgent;

        let os = 'Unknown OS';
        if (/Windows NT/.test(ua)) os = 'Windows';
        else if (/Macintosh/.test(ua)) os = 'Mac';
        else if (/Android/.test(ua)) os = 'Android';
        else if (/iPhone|iPad|iPod/.test(ua)) os = 'iOS';
        else if (/Linux/.test(ua)) os = 'Linux';

        let browser = 'Unknown Browser';
        if (/Chrome/.test(ua) && /Mobile/.test(ua)) browser = 'Chrome Mobile';
        else if (/Chrome/.test(ua)) browser = 'Chrome';
        else if (/Firefox/.test(ua)) browser = 'Firefox';
        else if (/Safari/.test(ua) && !/Chrome/.test(ua)) browser = 'Safari';
        else if (/Edg/.test(ua)) browser = 'Edge';
        else if (/OPR/.test(ua)) browser = 'Opera';
        return `${browser}_${os}`;
    };



    const subscribePush = async () => {
        try {
            if (!('serviceWorker' in navigator)) return;
            if (Notification.permission === 'denied') {
                console.warn('Пуши заблокированы пользователем. Нужно сбросить разрешения в браузере.');
                return;
            }

            if (Notification.permission !== 'granted') {
                const permission = await Notification.requestPermission();
                if (permission !== 'granted') {
                    console.warn('Пользователь не разрешил пуши.');
                    return;
                }
            }
            const registration = await navigator.serviceWorker.register('/serviceWorker/ServiceWorker.js');

            const subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array('BGFRrWJKC5fnlb_eLwOIqa4bWlbBqRUIFvlDYQ1GX56Hl5Kv4KNopAIqZ2Tq6ohG4hIdLJoim4313yKUyNevXgo')
            });

            const pushData = {
                platform: getPlatform(),
                endpoint: subscription.endpoint,
                keys: subscription.toJSON().keys
            };



            await fetch('/api/save/subscription/web', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('jwt')}`
                },
                body: JSON.stringify(pushData)
            });

        } catch (err) {
            console.error('Не удалось подписать на пуши:', err);
        }
    };



    if (isAuthenticated === null) return <Loading />;

    return (
        <Routes>
            <Route path="/" element={<Lending />} />
            <Route path="/profile" element={<AuthCheck isAuthenticated={isAuthenticated}><Profile /></AuthCheck>} />
            <Route path="/pomodoro" element={<AuthCheck isAuthenticated={isAuthenticated}><Pomodoro /></AuthCheck>} />
            <Route path="/habits" element={<AuthCheck isAuthenticated={isAuthenticated}><Habits_page /></AuthCheck>} />
            <Route path="/tasks" element={<AuthCheck isAuthenticated={isAuthenticated}><TasksPage /></AuthCheck>} />
            <Route path="/profile/statistics" element={<AuthCheck isAuthenticated={isAuthenticated}><HabitCalendar /></AuthCheck>} />
            <Route path="/matric" element={<AuthCheck isAuthenticated={isAuthenticated}><MatricPage /></AuthCheck>} />
            <Route path="/premium" element={<AuthCheck isAuthenticated={isAuthenticated}><PremiumPage isAuthenticated={isAuthenticated} /></AuthCheck>} />
            <Route path="/users/register" element={<RegisterPage />} />
            <Route path="/users/login" element={<LoginPage />} />
        </Routes>
    );
};

const WrappedRouterDom = () => (
    <BrowserRouter>
        <RouterDom />
    </BrowserRouter>
);

export default WrappedRouterDom;


function urlBase64ToUint8Array(base64String: string) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = atob(base64);
    return new Uint8Array([...rawData].map(c => c.charCodeAt(0)));
}
