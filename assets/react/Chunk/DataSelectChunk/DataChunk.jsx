import React, { useState, useEffect, useRef } from 'react';
import { format, addMonths, subMonths, startOfMonth, endOfMonth, startOfWeek, endOfWeek, addDays } from 'date-fns';

const DataChunk = ({ onClose, onSave }) => {
    const weekDays = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
    const [activeTab, setActiveTab] = useState(1);
    const [currentMonth, setCurrentMonth] = useState(new Date());
    const [selectedDate, setSelectedDate] = useState(null);
    const [selectedTime, setSelectedTime] = useState(null);
    const [repeat, setRepeat] = useState('ежедневно');
    const [showTimeList, setShowTimeList] = useState(false);

    const [startDate, setStartDate] = useState('');
    const [startTime, setStartTime] = useState('08:00');
    const [endDate, setEndDate] = useState('');
    const [endTime, setEndTime] = useState('08:00');
    const [durationRepeat, setDurationRepeat] = useState('ежедневно');

    const timeListRef = useRef(null);
    const timeInputRef = useRef(null);

    const handleDateClick = (day) => {
        setSelectedDate(day);
    };

    const handleSave = () => {
        const data = {
            date: selectedDate ? format(selectedDate, 'yyyy-MM-dd') : null,
            time: selectedTime,
            repeat,
            duration: {
                startDate,
                startTime,
                endDate,
                endTime,
                repeat: durationRepeat
            }
        };
        onSave(data);
        onClose();
    };

    const renderHeader = () => (
        <div className="calendar-header">
            <button onClick={() => setCurrentMonth(subMonths(currentMonth, 1))}>{'<'}</button>
            <span>{format(currentMonth, 'MMMM yyyy')}</span>
            <button onClick={() => setCurrentMonth(addMonths(currentMonth, 1))}>{'>'}</button>
        </div>
    );

    const renderDays = () => (
        <div className="calendar-days">
            {weekDays.map((day, i) => (
                <div key={i}>{day}</div>
            ))}
        </div>
    );

    const renderCells = () => {
        const monthStart = startOfMonth(currentMonth);
        const monthEnd = endOfMonth(monthStart);
        const startDate = startOfWeek(monthStart, { weekStartsOn: 1 });
        const endDate = endOfWeek(monthEnd, { weekStartsOn: 1 });

        const rows = [];
        let days = [];
        let day = startDate;
        let daysCounter = 0;

        while (day <= endDate || rows.length < 6) {
            for (let i = 0; i < 7; i++) {
                const cloneDay = day;
                const isCurrentMonth = format(cloneDay, 'MM') === format(currentMonth, 'MM');

                days.push(
                    <div
                        key={cloneDay.toString()}
                        className={`cell 
                        ${!isCurrentMonth ? 'other-month' : ''}
                        ${selectedDate && format(cloneDay, 'yyyy-MM-dd') === format(selectedDate, 'yyyy-MM-dd') ? 'selected' : ''}
                    `}
                        onClick={() => handleDateClick(cloneDay)}
                    >
                        {format(cloneDay, 'd')}
                    </div>
                );

                day = addDays(day, 1);
                daysCounter++;
                if (daysCounter > 42) break;
            }

            rows.push(
                <div className="calendar-row" key={`row-${rows.length}`}>
                    {days}
                </div>
            );
            days = [];
        }

        return <div className="calendar-body">{rows}</div>;
    };

    useEffect(() => {
        const handleClickOutside = (e) => {
            if (showTimeList &&
                !timeListRef.current?.contains(e.target) &&
                !timeInputRef.current?.contains(e.target)) {
                setShowTimeList(false);
            }
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, [showTimeList]);

    const handleCloseTimeSelect = () => {
        setSelectedTime(null);
        setShowTimeList(false);
    };

    return (
        <div className="date-chunk-modal">
            <div className="tabs">
                <span onClick={() => setActiveTab(1)} className={activeTab === 1 ? 'active' : ''}>Дата</span>
                <span onClick={() => setActiveTab(2)} className={activeTab === 2 ? 'active' : ''}>Длительность</span>
            </div>

            {activeTab === 1 && (
                <div>
                    {renderHeader()}
                    {renderDays()}
                    {renderCells()}
                    <div className="time-select">
                        {selectedTime === null ? (
                            <p onClick={() => setShowTimeList(true)}>Выберите время</p>
                        ) : (
                            <div className="time-input-wrapper" ref={timeInputRef}>
                                <input
                                    className="input-times"
                                    type="time"
                                    value={selectedTime}
                                    onChange={e => setSelectedTime(e.target.value)}
                                    onClick={() => setShowTimeList(true)}
                                />
                                <button
                                    onClick={handleCloseTimeSelect}
                                    className="close-button"
                                >
                                    <img src="/Upload/Images/AppIcons/x-circle.svg" alt="Закрыть"/>
                                </button>
                            </div>
                        )}

                        {showTimeList && (
                            <div className="times-blocks" ref={timeListRef}>
                                {Array.from({length: 48}, (_, i) => {
                                    const hour = Math.floor(i / 2).toString().padStart(2, '0');
                                    const minutes = i % 2 === 0 ? '00' : '30';
                                    const timeStr = `${hour}:${minutes}`;
                                    return (
                                        <span
                                            key={timeStr}
                                            onClick={() => {
                                                setSelectedTime(timeStr);
                                                setShowTimeList(false);
                                            }}
                                        >
                                            {timeStr}
                                        </span>
                                    );
                                })}
                            </div>
                        )}
                    </div>
                    <div className="repeat-select">
                        <select value={repeat} onChange={(e) => setRepeat(e.target.value)}>
                            <option value="никогда">Никогда</option>
                            <option value="ежедневно">Ежедневно</option>
                            <option value="еженедельно">Еженедельно</option>
                            <option value="ежемесячно">Ежемесячно</option>
                            <option value="ежегодно">Ежегодно</option>
                        </select>
                    </div>
                </div>
            )}

            {activeTab === 2 && (
                <div>
                    <div className="start-section">
                        <span>Начать:</span>
                        <input
                            placeholder="мм-дд"
                            value={startDate}
                            onChange={(e) => setStartDate(e.target.value)}
                        />
                        <input
                            type="time"
                            value={startTime}
                            onChange={(e) => setStartTime(e.target.value)}
                        />
                    </div>
                    <div className="end-section">
                        <span>Закончить:</span>
                        <input
                            placeholder="мм-дд"
                            value={endDate}
                            onChange={(e) => setEndDate(e.target.value)}
                        />
                        <input
                            type="time"
                            value={endTime}
                            onChange={(e) => setEndTime(e.target.value)}
                        />
                    </div>
                    <div className="repeat-select">
                        <select value={durationRepeat} onChange={(e) => setDurationRepeat(e.target.value)}>
                            <option value="никогда">Никогда</option>
                            <option value="ежедневно">Ежедневно</option>
                            <option value="еженедельно">Еженедельно</option>
                            <option value="ежемесячно">Ежемесячно</option>
                            <option value="ежегодно">Ежегодно</option>
                        </select>
                    </div>
                </div>
            )}

            <div className="modal-actions">
                <button onClick={onClose} className="cancel-button">Отмена</button>
                <button onClick={handleSave} className="save-button">Сохранить</button>
            </div>
        </div>
    );
};

export default DataChunk;