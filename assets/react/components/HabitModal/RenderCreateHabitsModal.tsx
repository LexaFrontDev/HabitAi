import {HabitTemplate} from "../../ui/props/Habits/HabitTemplate";
import {DataType} from "../../ui/props/Habits/DataType";
import {RenderStepProps} from "../../ui/props/Habits/RenderHabits/RenderStepProps";


export const renderStep = ({
                               step,
                               setStep,
                               habitTemplates,
                               data,
                               setData,
                               onClose,
                               validateStep,
                               expandedSettings,
                               toggleSetting,
                               renderFrequencyOptions,
                               saveData
                           }: RenderStepProps) => {
    switch(step) {
        case 1:
            return (
                <div className="modal-content">
                    <h4>Выберите привычку</h4>
                    <div className="habit-templates">
                        {habitTemplates && habitTemplates.map(habit => (
                            <div
                                key={habit.title}
                                className="habit-template"
                                onClick={() => {
                                    setData({
                                        ...data,
                                        title: habit.title,
                                        quote: habit.quote,
                                        notificationDate: habit.notification,
                                        datesType: habit.datesType,
                                    });
                                    setStep(2);
                                }}
                            >
                                <div className="left-side">
                                    <h5>{habit.title}</h5>
                                    <p>{habit.notification}</p>
                                </div>
                                <div className="right-side">
                                    <p>{habit.quote}</p>
                                </div>
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
                        value={data.title}
                        onChange={(e) => setData({...data, title: e.target.value})}
                    />
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
                                onClick={() => toggleSetting('startDate')}>
                                Дата начала:
                            </h3>
                            <div className={`setting-content ${expandedSettings.startDate ? 'active' : 'inactive'}`}>
                                <div className="input-group date" id="datepicker-start" data-provide="datepicker">
                                    <input
                                        type="text"
                                        className="form-control"
                                        value={data.beginDate ? new Date(data.beginDate * 1000).toLocaleDateString('ru-RU') : ''}
                                        onChange={(e) => {
                                            const newTimestamp = Math.floor(new Date(e.target.value).getTime() / 1000);
                                            setData({
                                                ...data,
                                                beginDate: newTimestamp,
                                            });
                                        }}
                                    />
                                    <span className="input-group-text">
                                        <i className="bi bi-calendar"></i>
                                    </span>
                                </div>
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