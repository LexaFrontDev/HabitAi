import React, { useState, useEffect, useRef, useMemo } from 'react';
import {
    format,
    addMonths,
    subMonths,
    startOfMonth,
    endOfMonth,
    startOfWeek,
    endOfWeek,
    addDays,
    isSameDay
} from 'date-fns';

const StaticDate = ({
                        id,
                        onDateSelect,
                        initialPosition = { top: 100, left: 100 },
                        draggable = true,
                        staticPosition = false,
                        tasks = [],
                        isDropdown = false,
                        groupByHabit = false,
                        customModalClass = ''
                    }) => {
    const weekDays = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
    const [currentMonth, setCurrentMonth] = useState(new Date());
    const [selectedDate, setSelectedDate] = useState(new Date());
    const [position, setPosition] = useState(initialPosition);
    const [dragging, setDragging] = useState(false);
    const [dragStart, setDragStart] = useState({ x: 0, y: 0 });
    const [isOpen, setIsOpen] = useState(!isDropdown);
    const [currentHabitIndex, setCurrentHabitIndex] = useState(0);

    const modalRef = useRef(null);

    const habits = useMemo(() => {
        const uniqueHabits = new Map();

        tasks.forEach(task => {
            if (task.habitId && !uniqueHabits.has(task.habitId)) {
                uniqueHabits.set(task.habitId, {
                    habitId: task.habitId,
                    title: task.title
                });
            }
        });

        return Array.from(uniqueHabits.values());
    }, [tasks]);

    const currentHabit = habits[currentHabitIndex] || {};

    useEffect(() => {
        if (isDropdown) {
            setIsOpen(false);
        }
    }, [isDropdown]);

    const switchHabit = (direction) => {
        setCurrentHabitIndex(prev => {
            if (habits.length <= 1) return 0;
            return (prev + direction + habits.length) % habits.length;
        });
    };

    const handleDateClick = (day) => {
        setSelectedDate(day);
        if (onDateSelect) {
            onDateSelect({
                id,
                day: format(day, 'd'),
                month: format(day, 'MMMM'),
                year: format(day, 'yyyy'),
                fullDate: format(day, 'yyyy-MM-dd')
            });
        }
        if (isDropdown) {
            setIsOpen(false);
        }
    };

    const toggleDropdown = () => {
        if (isDropdown) {
            setIsOpen(!isOpen);
        }
    };

    const getTasksForDate = (date) => {
        if (groupByHabit && currentHabit.habitId) {
            return tasks.filter(task =>
                task.recordedAt &&
                isSameDay(new Date(task.recordedAt), date) &&
                task.habitId === currentHabit.habitId
            );
        }

        return tasks.filter(task =>
            task.recordedAt &&
            isSameDay(new Date(task.recordedAt), date)
        );
    };

    const renderHeader = () => (
        <div className="calendar-header">
            {groupByHabit && habits.length > 0 ? (
                <>
                    <div className="habit-navigation">
                        <button
                            onClick={() => switchHabit(-1)}
                            disabled={habits.length <= 1}
                        >
                            {'<'}
                        </button>
                        <span className="habit-title">{currentHabit.title || 'No Habit'}</span>
                        <button
                            onClick={() => switchHabit(1)}
                            disabled={habits.length <= 1}
                        >
                            {'>'}
                        </button>
                    </div>
                    <div className="month-navigation">
                        <button onClick={() => setCurrentMonth(subMonths(currentMonth, 1))}>
                            {'<'}
                        </button>
                        <span>{format(currentMonth, 'MMMM yyyy')}</span>
                        <button onClick={() => setCurrentMonth(addMonths(currentMonth, 1))}>
                            {'>'}
                        </button>
                    </div>
                </>
            ) : (
                <>
                    <button onClick={() => setCurrentMonth(subMonths(currentMonth, 1))}>
                        {'<'}
                    </button>
                    <span>{format(currentMonth, 'MMMM yyyy')}</span>
                    <button onClick={() => setCurrentMonth(addMonths(currentMonth, 1))}>
                        {'>'}
                    </button>
                </>
            )}
        </div>
    );
    const renderDays = () => (
        <div className="calendar-days">
            {weekDays.map((day, i) => (
                <div key={i}>{day}</div>
            ))}
        </div>
    );

    const renderDateCircle = (day, dateTasks) => {
        const hasTasks = dateTasks.length > 0;
        const radius = 16;
        const circumference = 2 * Math.PI * radius;

        let totalCount = 0;
        let totalCompleted = 0;

        dateTasks.forEach(task => {
            totalCount += task.count || 0;
            totalCompleted += task.countEnd || 0;
        });

        const progress = totalCount > 0 ? totalCompleted / totalCount : 0;
        const strokeDashoffset = circumference * (1 - progress);

        return (
            <svg width="36" height="36" viewBox="0 0 36 36" className="date-circle">
                {/* Фоновый круг (серый) */}
                <circle
                    cx="18"
                    cy="18"
                    r={radius}
                    fill="none"
                    stroke="#e0e0e0"
                    strokeWidth="2"
                />

                {/* Прогресс (зеленый) - только если есть задачи */}
                {hasTasks && (
                    <circle
                        cx="18"
                        cy="18"
                        r={radius}
                        fill="none"
                        stroke="#4CAF50"
                        strokeWidth="2"
                        strokeDasharray={circumference}
                        strokeDashoffset={strokeDashoffset}
                        strokeLinecap="round"
                        transform="rotate(-90 18 18)"
                    />
                )}

                {/* Текст с числом дня */}
                <text
                    x="18"
                    y="18"
                    textAnchor="middle"
                    dominantBaseline="middle"
                    fontSize="12"
                    fontWeight="500"
                    fill={progress === 1 && hasTasks ? "#fff" : "#333"}
                >
                    {format(day, 'd')}
                </text>

                {/* Если прогресс 100%, добавляем зеленый фон */}
                {progress === 1 && hasTasks && (
                    <circle
                        cx="18"
                        cy="18"
                        r={radius}
                        fill="#4CAF50"
                    />
                )}
            </svg>
        );
    };

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
                const dateTasks = getTasksForDate(cloneDay);

                days.push(
                    <div
                        key={cloneDay.toString()}
                        className={`cell 
              ${!isCurrentMonth ? 'other-month' : ''}
              ${selectedDate && isSameDay(cloneDay, selectedDate) ? 'selected' : ''}
            `}
                        onClick={() => handleDateClick(cloneDay)}
                    >
                        {renderDateCircle(cloneDay, dateTasks)}
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

    const handleMouseDown = (e) => {
        if (draggable && e.target.classList.contains('modal-header')) {
            setDragging(true);
            setDragStart({
                x: e.clientX - position.left,
                y: e.clientY - position.top
            });
        }
    };

    const handleMouseMove = (e) => {
        if (dragging && draggable && !staticPosition) {
            setPosition({
                left: e.clientX - dragStart.x,
                top: e.clientY - dragStart.y
            });
        }
    };

    const handleMouseUp = () => {
        setDragging(false);
    };

    useEffect(() => {
        if (dragging) {
            document.addEventListener('mousemove', handleMouseMove);
            document.addEventListener('mouseup', handleMouseUp);
            return () => {
                document.removeEventListener('mousemove', handleMouseMove);
                document.removeEventListener('mouseup', handleMouseUp);
            };
        }
    }, [dragging, dragStart]);

    useEffect(() => {
        if (staticPosition) {
            setPosition(initialPosition);
        }
    }, [initialPosition, staticPosition]);

    return (
        <div className="static-date-container">
            {isDropdown && (
                <div className="dropdown-trigger" onClick={toggleDropdown}>
                    {format(selectedDate, 'dd.MM.yyyy')}
                </div>
            )}

            {(isOpen || !isDropdown) && (
                <div
                    className={`modal-container ${customModalClass}`}
                    style={{
                        position: 'absolute',
                        top: `${position.top}px`,
                        left: `${position.left}px`,
                        cursor: dragging ? 'grabbing' : 'default',
                        zIndex: 1000,
                        display: isDropdown && !isOpen ? 'none' : 'block'
                    }}
                    ref={modalRef}
                    onMouseDown={handleMouseDown}
                >
                    <div className="date-picker-modal">
                        <div className="date-picker-content">
                            {renderHeader()}
                            {renderDays()}
                            {renderCells()}
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};

export default StaticDate;