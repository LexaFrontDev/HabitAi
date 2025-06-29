import React, { useState, useEffect, useRef } from 'react';
import Sidebar from '../../Chunk/Sidebar';
import Pomodoro from "../Pomodor/Pomodoro_page";
import HabitModal from "../../Chunk/Habits/HabitsModal";
import DataChunk from "../../Chunk/DataSelectChunk/DataChunk";


const TasksPage = () => {
    const [showTasksInput, setTasksInput] = useState(false);
    const [title, setTitle] = useState('');


    const handleChange = (e) => {
        setTitle(e.target.value);
    };


    return (
        <div id="tasks-page">
            <Sidebar/>
            <div className="app-nav">
                <div className="nav-left-side">
                    <h3 className="header">Задачи</h3>
                </div>
                <div className="nav-center-side">
                    <select name="" id=""></select>
                </div>
            </div>









            <div id="content">
               <div onClick={() => setTasksInput(true)} className="block-add-tasks"></div>

                {showTasksInput && (
                    <div className="input-tasks">
                        <input className="input-tasks-title" type="text" placeholder="Хотите что то сделать?"  value={title}  onChange={handleChange} />
                        <button>Добавить</button>
                    </div>
                )}






            </div>


        </div>


    );


}

export default TasksPage;