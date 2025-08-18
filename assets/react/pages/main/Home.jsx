import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import Loading from "../chunk/LoadingChunk/Loading";
import Sidebar from "../chunk/SideBar";
import StaticDate from "../chunk/DataSelectChunk/StateDataPicker";

const Pomodoro = () => {
    const [stats, setStats] = useState(null);
    const [habitsCount, setHabitsCount] = useState(null);

    useEffect(() => {
        fetch('/api/count/period')
            .then(res => res.json())
            .then(data => setStats(data.stats || {}));
    }, []);


    useEffect(() => {
        fetch('/api/get/count/habits/today')
            .then(res => res.json())
            .then(data => setHabitsCount(data.data || {}));
            console.log(habitsCount?.progress_habits)
    }, []);

    if (!stats && !habitsCount) return <Loading />

    return (
        <div id="home">
            <Sidebar />

            <div className="app-nav">
                <div className="nav-left-side">
                    <h3 className="header">Главная страница <span className="info-icon">💭</span></h3>
                </div>
            </div>

            <div id="content">
                <div className="container-home">
                    <div className="stats-grid">
                        <div className="stat-card">
                            <h3>Место в рейтинге</h3>
                            <span>{stats?.position ?? '–'}</span>
                        </div>
                        <div className="stat-card">
                            <h3>Помо за неделю</h3>
                            <span>Время {stats?.periodLabel ?? '–'}</span><br/>
                            <span>Количество {stats?.count ?? '–'}</span>
                        </div>
                        <div className="stat-card">
                            <h3>Привычки за сегодня</h3>
                            <span>Количество {habitsCount?.count_habits ?? '–'}</span><br/>
                            <span>Количество выполненных {habitsCount?.count_done_habits ?? '–'}</span>
                        </div>
                    </div>


                    <div className="statistic">
                        <StaticDate
                            initialPosition={{ top: -25, left: 1006 }}
                            groupByHabit={true}
                            tasks={habitsCount?.progress_habits}
                        />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Pomodoro;
