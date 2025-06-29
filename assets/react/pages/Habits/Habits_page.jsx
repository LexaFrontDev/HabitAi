import React, { useState, useEffect } from 'react';
import Sidebar from '../../Chunk/Sidebar';
import Loading from "../../Chunk/LoadingChunk/Loading";
import HabitModal from "../../Chunk/Habits/HabitsModal";


const HabitsPage = () => {
    const [habits, setHabits] = useState([]);
    const [showManualInput, setShowManualInput] = useState(false);
    const [manualInputValue, setManualInputValue] = useState('');
    const [currentHabitId, setCurrentHabitId] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);



    useEffect(() => {
        fetch('/api/get/habits/today', { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                console.log('Ответ от API', data);
                setHabits(data?.data || []);
            });
    }, []);


    if (!habits || habits.length === 0) return <Loading />


    const handleProgressClick = async (habit) => {
        if (habit.is_done) return;

        setCurrentHabitId(habit.habit_id);

        if (habit.check_auto) {
            await saveHabitProgress(habit.habit_id, habit.auto_count);
        } else if (habit.check_manually) {
            setShowManualInput(true);
        } else if (habit.check_close) {
            await saveHabitProgress(habit.habit_id, habit.count_purposes);
        }
    };


    const handleSave = async (habitData) => {
        console.log('Saving habit:', habitData);
        try {
            const response = await fetch('/api/habits/save', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(habitData),
            });
            const result = await response.json();
            if (result.success) {
                alert('Привычка сохранена!');
                setIsModalOpen(false);
            } else {
                alert(result.message || 'Ошибка сохранения данных!');
            }
        } catch (error) {
            console.error(error);
            alert('Произошла ошибка! Попробуйте позже.');
        }
    };

    const saveHabitProgress = async (habitId, countProgress) => {
        try {
            const response = await fetch('/api/habits/save/progress', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    habits_id: habitId,
                    count_end: countProgress
                })
            });

            if (response.ok) {
                setHabits(prevHabits =>
                    prevHabits.map(habit => {
                        if (habit.habit_id === habitId) {
                            const newCountEnd = (habit.count_end || 0) + countProgress;
                            const isDone = newCountEnd >= habit.count_purposes;
                            return {
                                ...habit,
                                count_end: newCountEnd,
                                is_done: isDone
                            };
                        }
                        return habit;
                    })
                );
            } else {
                console.error('Ошибка при сохранении прогресса привычки');
            }
        } catch (error) {
            console.error('Ошибка:', error);
        }
    };

    const handleManualConfirm = async () => {
        const count = parseInt(manualInputValue);
        if (count > 0) {
            await saveHabitProgress(currentHabitId, count);
            setShowManualInput(false);
            setManualInputValue('');
        }
    };

    const handleManualCancel = () => {
        setShowManualInput(false);
        setManualInputValue('');
    };

    const toggleBlock = (blockName) => {
        const list = document.getElementById(blockName);
        const icon = document.getElementById(`icon-${blockName}`);
        if (list.style.display === 'none') {
            list.style.display = 'block';
            icon.textContent = '▼';
        } else {
            list.style.display = 'none';
            icon.textContent = '▶';
        }
    };

    const renderHabitsBlock = (title, filteredHabits, blockName) => {
        if (!filteredHabits.length) return null;

        return (
            <div className="habits-group card">
                <h3 className="toggle-header" onClick={() => toggleBlock(blockName)}>
                    {title} <span className="toggle-icon" id={`icon-${blockName}`}>▶</span>
                </h3>
                <ul id={blockName} className="habits-items" style={{ display: 'none' }}>
                    {filteredHabits.map(habit => (
                        <li
                            key={habit.habit_id}
                            data-habit-id={habit.habit_id}
                            data-is-done={habit.is_done || false}
                        >
                            <img src={habit.icon_url || '/images/default-icon.png'} alt={habit.title} className="habit-icon" />
                            <div className="habit-text">
                                <strong>{habit.title}</strong><br />
                                <small>{habit.notification_date}</small>
                                <small>{habit.type}</small>
                                <small>{habit.count}</small>
                            </div>
                            <div className="habit-progress" onClick={() => handleProgressClick(habit)}>
                                <svg className="progress-circle" width="40" height="40" viewBox="0 0 40 40">
                                    <circle className="progress-circle-background" cx="20" cy="20" r="18" strokeWidth="4" fill="none" />
                                    <circle
                                        className={`progress-circle-fill ${habit.is_done ? 'completed' : ''}`}
                                        cx="20"
                                        cy="20"
                                        r="18"
                                        strokeWidth="4"
                                        fill="none"
                                        strokeDasharray={2 * Math.PI * 18}
                                        strokeDashoffset={2 * Math.PI * 18 * (1 - ((habit.count_end || 0) / habit.count_purposes))}
                                    />
                                </svg>
                                <span className="progress-text">{(habit.count_end || 0)}/{habit.count_purposes}</span>
                            </div>
                        </li>
                    ))}
                </ul>
            </div>
        );
    };



    const morningHabits = habits.filter(h => h.period === 'morning');
    const nightHabits = habits.filter(h => h.period === 'night');
    const otherHabits = habits.filter(h => h.period !== 'morning' && h.period !== 'night');

    return (
        <div id="habits-page">
            <Sidebar />
            <div className="app-nav">
                <div className="nav-left-side">
                    <h3 className="header">Привычки</h3>
                </div>
                <div className="nav-center-side">
                    <div className="add-habit">

                        <button onClick={() => setIsModalOpen(true)} id="addHabitButton" className="add-habit-button" title="Добавить привычку">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24"
                                 width="48"
                                 height="48"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round"
                                 className="plus-icon">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </button>
                        {isModalOpen && (
                            <HabitModal
                                onClose={() => setIsModalOpen(false)}
                                onSave={handleSave}
                            />
                        )}
                    </div>
                </div>
            </div>
            <div id="content">
                <div className="habits-container">
                    <div className="week-dates">
                        {['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'].map(day => (
                            <div key={day} className="day">{day}</div>
                        ))}
                    </div>
                    <div className="habits-list">
                        {renderHabitsBlock('Утро', morningHabits, 'morning')}
                        {renderHabitsBlock('Вечер', nightHabits, 'night')}
                        {renderHabitsBlock('Другое', otherHabits, 'other')}
                        {habits.length === 0 && <p className="no-habits">Нет привычек.</p>}
                    </div>
                </div>
            </div>

            {showManualInput && (
                <div id="manual-input-modal" style={{ display: 'flex' }}>
                    <div className="modal-content">
                        <h3>Введите количество выполненных</h3>
                        <input
                            type="number"
                            id="manual-count-input"
                            min="1"
                            value={manualInputValue}
                            onChange={(e) => setManualInputValue(e.target.value)}
                        />
                        <button id="confirm-manual-count" onClick={handleManualConfirm}>Подтвердить</button>
                        <button id="cancel-manual-count" onClick={handleManualCancel}>Отмена</button>
                    </div>
                </div>
            )}
        </div>
    );
};

export default HabitsPage;