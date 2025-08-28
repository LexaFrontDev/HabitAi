import React, { useState, useEffect } from 'react';
import Sidebar from '../chunk/SideBar';
import Loading from "../chunk/LoadingChunk/Loading";
import HabitModal from "../chunk/Habits/HabitsModal";
import {ImperativePanelGroupHandle, Panel, PanelGroup, PanelResizeHandle} from "react-resizable-panels";
import {useTranslation} from "react-i18next";
import {LanguageRequestUseCase} from "../../Aplication/UseCases/language/LanguageRequestUseCase";
import {LanguageApi} from "../../Infrastructure/request/Language/LanguageApi";
import {LangStorage} from "../../Infrastructure/languageStorage/LangStorage";
import {LangStorageUseCase} from "../../Aplication/UseCases/language/LangStorageUseCase";
import {HabitsService} from "../../Aplication/UseCases/Habits/HabitsService";
import {HabitsApi} from "../../Infrastructure/request/habits/HabitsApi";
import {useHabitsLogic} from "../../Aplication/UseCases/Habits/HabitsPageLogic";
import Calendar from "react-calendar";

const habitsService = new HabitsService(new HabitsApi());
const LangUseCase = new LanguageRequestUseCase(new LanguageApi());
const langStorage = new LangStorage();
const langUseCase = new LangStorageUseCase(langStorage);

const HabitsPage = () => {
    const [status, setStatus] = useState<number | null>(null);
    const [langCode, setLangCode] = useState('en');
    const { t, i18n } = useTranslation('translation');
    const [translationsLoaded, setTranslationsLoaded] = useState<boolean>(false);
    const  {
        fetchHabits,
        habits,
        setHabits,
        currentPeriod,
        setCurrentPeriod,
        saveHabitProgress,
        handleSave,
        editingHabit,
        setEditingHabit,
        setIsModalOpen,
        isModalOpen,
        handleDelete,
        showManualInput,
        setShowManualInput,
        currentHabitId,
        setCurrentHabitId,
        handleManualConfirm,
        handleManualCancel,
        manualInputValue,
        setManualInputValue,
        handleProgressClick,
        handleSideStatistic,
        HabitsStatistic,
        habitsSide,
        setHabitSide,
        IsLoading,
        setIsLoading,
        HabitsCurrentStatistics,
        SetHabitsCurrentStatistics,
        fetchHabitsStatistic,
        fetchHabitsTemplates,
        Templates,
        setTemplates,
        handleUpdate
    } = useHabitsLogic(habitsService);
    const [selectedDate, setSelectedDate] = useState<Date | null>(null);

    useEffect(() => {
        fetchHabits();
        fetchHabitsStatistic();
        fetchHabitsTemplates();
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




    if (!habits || habits.length === 0 && status === 200) return <Loading />



    const handleEdit = (habit: any) => {
        setEditingHabit(habit);
        setIsModalOpen(true);
    };


    if (!translationsLoaded || IsLoading) return <Loading />;


    const renderHabitsBlock = (title: any, filteredHabits: any, blockName: any) => {
        if (!filteredHabits.length) return null;

        const shouldDisplay = blockName === currentPeriod ? 'block' : 'none';

        return (
            <div className="habits-group">
                <div className="header-toggle">
                    <div className="toggle-wrapper" onClick={() => habitsService.toggleBlock(blockName)}>
                        <img
                            id={`icon-${blockName}`}
                            src="/Upload/Images/AppIcons/arrow-right.svg"
                            alt="toggle arrow"
                            style={{width: 16, height: 16, cursor: 'pointer'}}
                        />
                        <span className="toggle-text">{title}</span>
                    </div>
                </div>

                <ul id={blockName} className="tasks-list mt-1" style={{display: shouldDisplay}}>
                    {filteredHabits.map((habit: any) => (
                        <li
                            className="task-item triger-list"
                            key={habit.habit_id}
                            data-habit-id={habit.habit_id}
                            data-is-done={habit.is_done || false}
                            onClick={() => handleSideStatistic(habit)}
                        >
                            <div className="habit-text">
                                <strong>{habit.title}</strong><br/>
                                <small>{habit.notification_date}</small>
                                <small>{habit.type}</small>
                            </div>

                            <div className="habit-progress">
                                {habit.is_done ? (
                                    <object
                                        data="/AnimationDone.svg"
                                        type="image/svg+xml"
                                        width="40"
                                        height="40"
                                        aria-label="done animation"
                                        onClick={(e) => {
                                            e.stopPropagation();
                                            handleProgressClick(habit);
                                        }}
                                    />
                                ) : (
                                    <svg
                                        className="progress-circle"
                                        style={{width: "40px", height: "40px"}}
                                        viewBox="0 0 120 120"
                                        xmlns="http://www.w3.org/2000/svg"
                                        onClick={(e) => {
                                            e.stopPropagation();
                                            handleProgressClick(habit);
                                        }}
                                    >
                                        {(() => {
                                            const r = 23;
                                            const circumference = 2 * Math.PI * r;
                                            const progress = (habit.count_end || 0) / habit.count_purposes;

                                            return (
                                                <>

                                                    <circle
                                                        cx="60"
                                                        cy="60"
                                                        r={r}
                                                        fill="#c0c0c0"
                                                        stroke="none"
                                                    />


                                                    <circle
                                                        cx="60"
                                                        cy="60"
                                                        r={r}
                                                        fill="none"
                                                        stroke="#1E90FF"
                                                        strokeWidth="6"
                                                        strokeDasharray={circumference}
                                                        strokeDashoffset={circumference * (1 - progress)}
                                                        strokeLinecap="round"
                                                        transform="rotate(-90 60 60)"
                                                    />
                                                </>
                                            );
                                        })()}
                                    </svg>
                                )}
                            </div>
                        </li>

                    ))}
                </ul>
            </div>
        );
    };

    const morningHabits = habits.filter(h => h.period === 'morning');
    const nightHabits = habits.filter(h => h.period === 'night');
    const otherHabits = habits.filter(h => h.period === 'other');


    return (
        <div id="habits-page">
            <Sidebar/>


            <div style={{marginLeft: '50px'}}>
                <PanelGroup direction="horizontal">
                    <Panel defaultSize={30} minSize={20}>
                        <div className="panel-content">
                            <div className="content-panel">
                                <div className="header-panel">
                                    <div className="header-text">
                                        <h4 className="header-title">{t('habits.habitsHeadText')}</h4>
                                    </div>
                                    <div className="add-habit">
                                        <button onClick={() => {
                                            setEditingHabit(null);
                                            setIsModalOpen(true);
                                        }} id="addHabitButton" className="triger" title="Добавить привычку">
                                            <svg style={{width: '30px', height: '25px'}}
                                                 xmlns="http://www.w3.org/2000/svg"
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
                                                habitTemplates={Templates}
                                                onClose={() => {
                                                    setIsModalOpen(false);
                                                    setEditingHabit(null);
                                                }}
                                                onSave={handleSave}
                                                onEdit={handleUpdate}
                                                edit={!!editingHabit}
                                                editData={editingHabit}
                                            />
                                        )}
                                    </div>
                                </div>
                                <div className="week-dates">
                                    {[t('week_short.mon'), t('week_short.tue'), t('week_short.wed'), t('week_short.thu'), t('week_short.fri'), t('week_short.sat'), t('week_short.sun')].map(day => (
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
                                    {renderHabitsBlock(t('habits.morning'), morningHabits, 'morning')}
                                    {renderHabitsBlock(t('habits.night'), nightHabits, 'night')}
                                    {renderHabitsBlock(t('habits.midnight'), otherHabits, 'other')}
                                    {habits.length === 0 && <p className="no-habits">Нет привычек.</p>}
                                </div>


                            </div>
                        </div>
                    </Panel>
                    <PanelResizeHandle/>
                    <Panel  defaultSize={15} minSize={5}>
                        <div className="panel-content">
                            <div className="line-resize"></div>
                                {habitsSide && HabitsCurrentStatistics && (
                                    <div className="content-panel">
                                        <div className="header-panel">
                                            <div className="header-text">
                                                <h4 className="header-title">{habitsSide.title}</h4>
                                            </div>
                                            <div className="habit-actions">
                                                <button onClick={() => handleEdit(habitsSide)} className="triger-pause">
                                                    <img className="icon-img" src="/Upload/Images/AppIcons/edit.svg" alt=""/>
                                                </button>
                                                <button onClick={() => handleDelete(habitsSide.habit_id)} className="triger-delete">
                                                    <img className="icon-img" src="/Upload/Images/AppIcons/delete.svg" alt=""/>
                                                </button>
                                            </div>
                                        </div>

                                        <div className="stats mt-5">
                                            <div className="stat-box">
                                                <h3>Общее количество дней</h3>
                                                <span>{HabitsCurrentStatistics.all_tracking_days}</span>
                                            </div>
                                            <div className="stat-box">
                                                <h3>Общее количество</h3>
                                                <span>{HabitsCurrentStatistics.all_tracking_count}</span>
                                            </div>
                                            <div className="stat-box">
                                                <h3>количество за неделю</h3>
                                                <span>{HabitsCurrentStatistics.tracking_week}</span>
                                            </div>
                                            <div className="stat-box">
                                                <h3>количество за Сегодня</h3>
                                                <span>{HabitsCurrentStatistics.tracking_today}</span>
                                            </div>
                                        </div>


                                        <Calendar
                                            formatMonthYear={(locale, date) => {
                                                const monthNames = [
                                                    t('month.January'), t('month.February'), t('month.March'),
                                                    t('month.April'), t('month.May'), t('month.June'),
                                                    t('month.July'), t('month.August'), t('month.September'),
                                                    t('month.October'), t('month.November'), t('month.December')
                                                ];
                                                return `${monthNames[date.getMonth()]} ${date.getFullYear()}`;
                                            }}
                                            formatShortWeekday={(locale, date) => {
                                                const weekdays = [
                                                    t('week_short.mon'), t('week_short.tue'), t('week_short.wed'),
                                                    t('week_short.thu'), t('week_short.fri'), t('week_short.sat'), t('week_short.sun')
                                                ];
                                                return weekdays[date.getDay() === 0 ? 6 : date.getDay() - 1];
                                            }}
                                            onClickDay={setSelectedDate}
                                            value={selectedDate || new Date()}
                                            tileContent={({ date, view }) => {
                                                if (view === 'month' && HabitsCurrentStatistics) {
                                                    const dateStr = date.toISOString().split('T')[0];
                                                    const statForDate = HabitsCurrentStatistics.habitsList.find(
                                                        (h: { recordedAt: string; count: number; countEnd: number }) => h.recordedAt.split('T')[0] === dateStr
                                                    );

                                                    if (statForDate) {
                                                        const percent = Math.round((statForDate.countEnd / statForDate.count) * 100);
                                                        const color = percent === 100 ? '#2ecc71' : percent >= 50 ? '#f1c40f' : '#f39c12';

                                                        return (
                                                            <div style={{ position: 'relative', width: '100%', height: '70%' }}>
                                                                <svg
                                                                    viewBox="0 0 36 36"
                                                                    style={{
                                                                        width: '100%',
                                                                        height: '100%',
                                                                    }}
                                                                >
                                                                    <path
                                                                        d="M18 2.0845
                                   a 15.9155 15.9155 0 0 1 0 31.831
                                   a 15.9155 15.9155 0 0 1 0 -31.831"
                                                                        fill="none"
                                                                        stroke="#ddd"
                                                                        strokeWidth="3"
                                                                    />
                                                                    <path
                                                                        d="M18 2.0845
                                   a 15.9155 15.9155 0 0 1 0 31.831
                                   a 15.9155 15.9155 0 0 1 0 -31.831"
                                                                        fill="none"
                                                                        stroke={color}
                                                                        strokeWidth="3"
                                                                        strokeDasharray={`${percent}, 100`}
                                                                    />
                                                                </svg>
                                                            </div>
                                                        );
                                                    }
                                                }
                                                return null;
                                            }}
                                        />

                                    </div>
                                )}
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