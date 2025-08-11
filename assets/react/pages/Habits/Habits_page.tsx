import React, { useState, useEffect } from 'react';
import Sidebar from '../chunk/SideBar';
import Loading from "../chunk/LoadingChunk/Loading";
// @ts-ignore
import HabitModal from "../chunk/Habits/HabitsModal";
import {ImperativePanelGroupHandle, Panel, PanelGroup, PanelResizeHandle} from "react-resizable-panels";
import {useTranslation} from "react-i18next";
import {LanguageRequestUseCase} from "../../Aplication/UseCases/language/LanguageRequestUseCase";
import {LanguageApi} from "../../Infrastructure/request/Language/LanguageApi";
import {LangStorage} from "../../Infrastructure/languageStorage/LangStorage";
import {LangStorageUseCase} from "../../Aplication/UseCases/language/LangStorageUseCase";


const LangUseCase = new LanguageRequestUseCase('pomodoro', new LanguageApi());
const langStorage = new LangStorage();
const langUseCase = new LangStorageUseCase(langStorage);

const HabitsPage = () => {
    const [habits, setHabits] = useState<any[]>([]);
    const [showManualInput, setShowManualInput] = useState(false);
    const [manualInputValue, setManualInputValue] = useState('');
    const [currentHabitId, setCurrentHabitId] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [currentPeriod, setCurrentPeriod] = useState('');
    const [editingHabit, setEditingHabit] = useState<any | null>(null);
    const [status, setStatus] = useState<number | null>(null);
    const [langCode, setLangCode] = useState('en');
    const { t, i18n } = useTranslation('pomodoro');

    useEffect(() => {
        fetchHabits();
    }, []);

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

    const fetchHabits = () => {
        const hours = new Date().getHours();
        let period = 'other';
        if (hours >= 5 && hours < 12) period = 'morning';
        else if (hours >= 18 || hours < 5) period = 'night';

        setCurrentPeriod(period);

        fetch('/api/get/habits/today', { method: 'GET' })
            .then(async res => {
                setStatus(res.status);
                const data = await res.json();
                if (res.ok) {
                    setHabits(data?.data || []);
                } else {
                    console.error('Ошибка при получении привычек:', data);
                    setHabits([]);
                }
            })
            .catch(err => {
                setStatus(400);
                console.error('Ошибка сети:', err);
                setHabits([]);
            });
    };


    if (!habits || habits.length === 0 && status === 200) return <Loading />

    const handleProgressClick = async (habit: any) => {
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

    const handleSave = async (habitData: any) => {
        try {
            const url = editingHabit
                ? `/api/habits/update/${editingHabit.habit_id}`
                : '/api/Habits/save';

            const method = editingHabit ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(habitData),
            });

            console.log(habitData);

            const result = await response.json();

            if (result.success) {
                alert(editingHabit ? 'Привычка обновлена!' : 'Привычка сохранена!');
                setIsModalOpen(false);
                setEditingHabit(null);
                fetchHabits();
            } else {
                alert(result.message || 'Ошибка сохранения данных!');
            }
        } catch (error) {
            alert('Произошла ошибка! Попробуйте позже.');
        }
    };

    const handleEdit = (habit: any) => {
        console.log(habits);
        setEditingHabit(habit);
        setIsModalOpen(true);
    };

    const handleDelete = async (habitId: any) => {
        if (window.confirm('Вы уверены, что хотите удалить эту привычку?')) {
            try {
                const response = await fetch(`/api/habits/delete/${habitId}`, {
                    method: 'DELETE',
                });

                const result = await response.json();

                if (result.success) {
                    alert('Привычка удалена!');
                    fetchHabits();
                } else {
                    alert(result.message || 'Ошибка удаления привычки!');
                }
            } catch (error) {
                alert('Произошла ошибка! Попробуйте позже.');
            }
        }
    };

    const saveHabitProgress = async (habitId: any, countProgress: any) => {
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

    const toggleBlock = (blockName: string) => {
        const list = document.getElementById(blockName);
        const icon = document.getElementById(`icon-${blockName}`);

        if (!list || !icon) return;

        if (list.style.display === 'none') {
            list.style.display = 'block';
            icon.textContent = '▼';
        } else {
            list.style.display = 'none';
            icon.textContent = '▶';
        }
    };


    if (!i18n.hasResourceBundle(langCode, 'pomodoro')) return <Loading />;


    const renderHabitsBlock = (title: any, filteredHabits: any, blockName: any) => {
        if (!filteredHabits.length) return null;

        const shouldDisplay = blockName === currentPeriod ? 'block' : 'none';

        return (
            <div className="habits-group">
                <h3 className="toggle-header" onClick={() => toggleBlock(blockName)}>
                    <span className="toggle-icon" id={`icon-${blockName}`}>▶</span> {title}
                </h3>
                <ul id={blockName} className="habits-items" style={{display: shouldDisplay}}>
                    {filteredHabits.map((habit: any) => (
                        <li
                            key={habit.habit_id}
                            data-habit-id={habit.habit_id}
                            data-is-done={habit.is_done || false}
                        >
                            <div className="habit-text">
                                <strong>{habit.title}</strong><br/>
                                <small>{habit.notification_date}</small>
                                <small>{habit.type}</small>
                                <div className="habit-actions">
                                    <button onClick={() => handleEdit(habit)} className="edit-button">✏️</button>
                                    <button onClick={() => handleDelete(habit.habit_id)} className="delete-button">🗑️</button>
                                </div>
                            </div>
                            <div className="habit-progress" onClick={() => handleProgressClick(habit)}>
                                <svg className="progress-circle" width="40" height="40" viewBox="0 0 40 40">
                                    <circle className="progress-circle-background" cx="20" cy="20" r="18"
                                            strokeWidth="4" fill="none"/>
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
            <Sidebar/>


            <div style={{marginLeft: '50px'}}>
                <PanelGroup direction="horizontal">
                    <Panel  defaultSize={30} minSize={20}>
                        <div className="panel-content">
                            <div className="content-panel">
                                <div className="header-panel">
                                    <div className="header-text">
                                        <h4 className="header-title">{t('pomodoro:pomodoroHeadText')}</h4>
                                    </div>
                                    <div className="add-habit">
                                        <button onClick={() => {
                                            setEditingHabit(null);
                                            setIsModalOpen(true);
                                        }} id="addHabitButton" className="triger" title="Добавить привычку">
                                            <svg   style={{width: '30px', height: '25px'}} xmlns="http://www.w3.org/2000/svg"
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
                                                onClose={() => {
                                                    setIsModalOpen(false);
                                                    setEditingHabit(null);
                                                }}
                                                onSave={handleSave}
                                                edit={true}
                                                editData={editingHabit}
                                            />
                                        )}
                                    </div>
                                </div>
                                <div className="week-dates">
                                    {['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'].map(day => (
                                        <div
                                            key={day}
                                            style={{width: '100%', textAlign: 'center'}}
                                            className="triger"
                                        >
                                            {day}
                                        </div>
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
                    </Panel>
                    <PanelResizeHandle/>
                    <Panel  defaultSize={15} minSize={5}>
                        <div className="panel-content">
                            <div className="line-resize"></div>
                        </div>
                    </Panel>
                </PanelGroup>
            </div>



            {showManualInput && (
                <div id="manual-input-modal" style={{display: 'flex'}}>
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