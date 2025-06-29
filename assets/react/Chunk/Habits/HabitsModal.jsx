import React, { useState, useRef, useEffect } from 'react';

const HabitModal = ({ onClose, onSave }) => {
    const [step, setStep] = useState(1);
    const [data, setData] = useState({
        titleHabit: '',
        iconBase64: '',
        quote: '',
        goalInDays: '30',
        datesType: 'daily',
        date: {
            mon: false,
            tue: false,
            wed: false,
            thu: false,
            fri: false,
            sat: false,
            sun: false
        },
        beginDate: Math.floor(Date.now() / 1000),
        notificationDate: '08:30',
        purposeType: 'count',
        purposeCount: 1,
        checkManually: false,
        checkAuto: false,
        checkClose: false,
        autoCount: 0
    });
    const [error, setError] = useState('');
    const [expandedSettings, setExpandedSettings] = useState({
        goal: false,
        startDate: false,
        duration: false,
        reminder: false
    });

    const habitTemplates = [
        {
            title: "Ранний подъем",
            quote: "Кто рано встает, тому Бог дает!",
            icon: "⏰"
        },
        {
            title: "Утренняя пробежка",
            quote: "Бег - это жизнь!",
            icon: "🏃"
        },
        {
            title: "Чтение книги",
            quote: "Книги - корабли мысли",
            icon: "📚"
        },
        {
            title: "Медитация",
            quote: "Тишина - великий учитель",
            icon: "🧘"
        }
    ];

    const toggleSetting = (setting) => {
        setExpandedSettings(prev => ({
            ...prev,
            [setting]: !prev[setting]
        }));
    };

    const renderFrequencyOptions = () => {
        if (data.datesType === 'daily') {
            const daysList = ['Пн','Вт','Ср','Чт','Пт','Сб','Вс'];
            const daysMap = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
            return (
                <div className="date-block">
                    <div>Выберите дни недели:</div>
                    <div className="day-grid">
                        {daysList.map((d, i) => (
                            <div
                                key={d}
                                className={`day-item ${data.date[daysMap[i]] ? 'active-day' : ''}`}
                                data-day={daysMap[i]}
                                onClick={() => toggleDay(daysMap[i])}
                            >
                                {d}
                            </div>
                        ))}
                    </div>
                </div>
            );
        } else if (data.datesType === 'weekly') {
            return (
                <div className="date-block">
                    <div>Сколько дней в неделю?</div>
                    <select
                        id="weekly-count"
                        value={data.datesType === 'weekly' ? (data.date.count || 1) : 1}
                        onChange={(e) => setData({
                            ...data,
                            date: {
                                ...data.date,
                                count: parseInt(e.target.value) || 1
                            }
                        })}
                    >
                        {[...Array(7).keys()].map(i => (
                            <option key={i + 1} value={i + 1}>{i + 1}</option>
                        ))}
                    </select>
                </div>
            );
        } else {
            return (
                <div className="date-block">
                    <div>Выберите дату месяца:</div>
                    <select
                        id="repeat-day"
                        value={data.date.day || 1}
                        onChange={(e) => setData({...data, date: {...data.date, day: parseInt(e.target.value)}})}
                    >
                        {[...Array(31).keys()].map(i => (
                            <option key={i+1} value={i+1}>{i+1}</option>
                        ))}
                    </select>
                </div>
            );
        }
    };

    const toggleDay = (day) => {
        setData({
            ...data,
            date: {
                ...data.date,
                [day]: !data.date[day]
            }
        });
    };

    const handleFileChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                setData({
                    ...data,
                    iconBase64: event.target.result
                });
            };
            reader.readAsDataURL(file);
        }
    };

    const validateStep = (currentStep) => {
        if (currentStep === 2) {
            if (!data.titleHabit.trim()) {
                setError('Введите название привычки');
                return false;
            }
        } else if (currentStep === 3) {
            if (data.datesType === 'daily' && !Object.values(data.date).some(v => v)) {
                setError('Выберите хотя бы один день');
                return false;
            }

            if (!data.checkClose && data.purposeCount <= 0) {
                setError('Введите корректное значение цели');
                return false;
            }

            if (data.checkAuto && data.autoCount <= 0) {
                setError('Введите корректное значение автоматического количества');
                return false;
            }

            if (!data.goalInDays || parseInt(data.goalInDays) <= 0) {
                setError('Укажите корректную длительность');
                return false;
            }
        }
        setError('');
        return true;
    };

    const saveData = () => {
        if (!validateStep(step)) return;

        const payload = {
            titleHabit: data.titleHabit,
            iconBase64: data.iconBase64,
            quote: data.quote,
            goalInDays: data.goalInDays,
            datesType: data.datesType,
            date: prepareDateField(),
            beginDate: data.beginDate,
            notificationDate: data.notificationDate,
            purposeType: data.purposeType,
            purposeCount: data.purposeCount,
            checkManually: data.checkManually,
            checkAuto: data.checkAuto,
            checkClose: data.checkClose,
            autoCount: data.autoCount
        };

        onSave(payload);
    };

    const prepareDateField = () => {
        if (data.datesType === 'daily') {
            return {
                mon: data.date.mon,
                tue: data.date.tue,
                wed: data.date.wed,
                thu: data.date.thu,
                fri: data.date.fri,
                sat: data.date.sat,
                sun: data.date.sun
            };
        } else if (data.datesType === 'weekly') {
            return {
                count: data.date.count || 1
            };
        } else {
            return {
                day: data.date.day || 1
            };
        }
    };

    const renderStep = () => {
        switch(step) {
            case 1:
                return (
                    <div className="modal-content">
                        <div className="header">
                            <h4>Выберите привычку</h4>
                        </div>

                        <div className="habit-templates">
                            {habitTemplates.map(habit => (
                                <div
                                    key={habit.title}
                                    className="habit-template"
                                    onClick={() => {
                                        setData({
                                            ...data,
                                            titleHabit: habit.title,
                                            quote: habit.quote,
                                            iconBase64: habit.icon
                                        });
                                        setStep(2);
                                    }}
                                >
                                    <img src={habit.icon} alt="" />
                                    <h5>{habit.title}</h5>
                                    <p>{habit.quote}</p>
                                </div>
                            ))}
                        </div>

                        <div className="actions">
                            <button id="cancel" onClick={onClose}>Отмена</button>
                        </div>
                    </div>
                );
            case 2:
                return (
                    <div className="modal-content">
                        <div className="header">
                            <h4>Название и мотивация</h4>
                        </div>
                        <input
                            id="habit-title"
                            placeholder="Название привычки"
                            value={data.titleHabit}
                            onChange={(e) => setData({...data, titleHabit: e.target.value})}
                        />
                        <div className="icon-block">
                            <div className="icon-preview">
                                {data.iconBase64.startsWith('data:image') ?
                                    <img src={data.iconBase64} alt="Icon" style={{width: '50px', height: '50px', objectFit: 'cover'}} /> :
                                    data.iconBase64
                                }
                            </div>
                            <input id="habit-icon" type="file" accept="image/*" onChange={handleFileChange} />
                        </div>
                        <input
                            id="habit-quote"
                            placeholder="Цитата для мотивации"
                            value={data.quote}
                            onChange={(e) => setData({...data, quote: e.target.value})}
                        />

                        <div className="actions">
                            <button id="prev" onClick={() => setStep(1)}>Назад</button>
                            <button id="next" onClick={() => validateStep(2) && setStep(3)}>Далее</button>
                        </div>
                    </div>
                );
            case 3:
                return (
                    <div className="modal-content">
                        <h2>Настройка расписания</h2>

                        <div className="chose-data-block">
                            <span
                                className={`option daily ${data.datesType === 'daily' ? 'active' : ''}`}
                                onClick={() => setData({...data, datesType: 'daily'})}
                            >
                                Ежедневно
                            </span>
                            <span
                                className={`option weekly ${data.datesType === 'weekly' ? 'active' : ''}`}
                                onClick={() => setData({...data, datesType: 'weekly'})}
                            >
                                Еженедельно
                            </span>
                            <span
                                className={`option repeat ${data.datesType === 'repeat' ? 'active' : ''}`}
                                onClick={() => setData({...data, datesType: 'repeat'})}
                            >
                                Повтор
                            </span>
                            <div className="date-type">
                                {renderFrequencyOptions()}
                            </div>
                        </div>

                        <div className="settings-block">
                            <div className="setting-item">
                                <h3
                                    className="setting-toggle"
                                    onClick={() => toggleSetting('goal')}
                                >
                                    Цель:
                                </h3>
                                <div className={`setting-content ${expandedSettings.goal ? 'active' : 'inactive'}`}>
                                    <div className="goal-options">
                                        <label className="goal-option">
                                            <input
                                                type="radio"
                                                name="goal-type"
                                                value="complete"
                                                checked={data.checkClose}
                                                onChange={() => setData({
                                                    ...data,
                                                    checkClose: true,
                                                    checkAuto: false,
                                                    checkManually: false
                                                })}
                                            />
                                            Достигните всего этого
                                        </label>
                                        <label className="goal-option">
                                            <input
                                                type="radio"
                                                name="goal-type"
                                                value="specific"
                                                checked={!data.checkClose}
                                                onChange={() => setData({...data, checkClose: false})}
                                            />
                                            Достигните определенной суммы
                                        </label>
                                    </div>

                                    <div className={`specific-goal ${!data.checkClose ? 'active' : ''}`}>
                                        <div className="goal-input-row">
                                            <span>Ежедневно:</span>
                                            <input
                                                type="number"
                                                id="daily-goal"
                                                value={data.purposeCount}
                                                placeholder="Количество"
                                                onChange={(e) => setData({
                                                    ...data,
                                                    purposeCount: parseInt(e.target.value) || 0
                                                })}
                                            />
                                            <select
                                                id="goal-unit"
                                                value={data.purposeType}
                                                onChange={(e) => setData({...data, purposeType: e.target.value})}
                                            >
                                                <option value="count">раз</option>
                                                <option value="km">км</option>
                                                <option value="minutes">минут</option>
                                                <option value="laps">кругов</option>
                                            </select>
                                        </div>

                                        <div className="verification-method">
                                            <span>При проверке:</span>
                                            <select
                                                id="verification-type"
                                                value={
                                                    data.checkAuto ? 'auto' :
                                                        data.checkManually ? 'manual' :
                                                            'complete'
                                                }
                                                onChange={(e) => {
                                                    const value = e.target.value;
                                                    setData({
                                                        ...data,
                                                        checkAuto: value === 'auto',
                                                        checkManually: value === 'manual',
                                                        checkClose: value === 'complete'
                                                    });
                                                }}
                                            >
                                                <option value="auto">Автоматический</option>
                                                <option value="manual">Вручную</option>
                                                <option value="complete">Завершить</option>
                                            </select>
                                        </div>

                                        <div
                                            id="auto-count-container"
                                            style={{
                                                display: data.checkAuto ? 'block' : 'none',
                                                marginTop: '10px'
                                            }}
                                        >
                                            <span>Автоматическое количество:</span>
                                            <input
                                                type="number"
                                                id="auto-count"
                                                value={data.autoCount}
                                                placeholder="Введите количество"
                                                onChange={(e) => setData({
                                                    ...data,
                                                    autoCount: parseInt(e.target.value) || 0
                                                })}
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="setting-item">
                                <h3
                                    className="setting-toggle"
                                    onClick={() => toggleSetting('startDate')}
                                >
                                    Дата начала:
                                </h3>
                                <div className={`setting-content ${expandedSettings.startDate ? 'active' : 'inactive'}`}>
                                    <input
                                        type="date"
                                        id="start-date"
                                        value={new Date(data.beginDate * 1000).toISOString().split('T')[0]}
                                        onChange={(e) => setData({
                                            ...data,
                                            beginDate: Math.floor(new Date(e.target.value).getTime() / 1000)
                                        })}
                                    />
                                </div>
                            </div>

                            <div className="setting-item">
                                <h3
                                    className="setting-toggle"
                                    onClick={() => toggleSetting('duration')}
                                >
                                    Цель в днях:
                                </h3>
                                <div className={`setting-content ${expandedSettings.duration ? 'active' : 'inactive'}`}>
                                    <input
                                        id="duration"
                                        type="number"
                                        value={data.goalInDays}
                                        placeholder="Кол-во дней"
                                        onChange={(e) => setData({...data, goalInDays: e.target.value})}
                                    />
                                </div>
                            </div>

                            <div className="setting-item">
                                <h3
                                    className="setting-toggle"
                                    onClick={() => toggleSetting('reminder')}
                                >
                                    Напоминание:
                                </h3>
                                <div className={`setting-content ${expandedSettings.reminder ? 'active' : 'inactive'}`}>
                                    <input
                                        id="reminder"
                                        type="time"
                                        value={data.notificationDate}
                                        onChange={(e) => setData({...data, notificationDate: e.target.value})}
                                    />
                                </div>
                            </div>
                        </div>

                        <div className="actions">
                            <button id="prev" onClick={() => setStep(2)}>Назад</button>
                            <button id="save" onClick={saveData}>Сохранить</button>
                        </div>
                    </div>
                );
            default:
                return null;
        }
    };

    return (
        <div className="modal">
            {error && (
                <div className="error-message" style={{
                    color: '#ff3d3d',
                    margin: '10px',
                    textAlign: 'center',
                    fontWeight: 'bold'
                }}>
                    {error}
                </div>
            )}
            {renderStep()}
        </div>
    );
};

export default HabitModal;