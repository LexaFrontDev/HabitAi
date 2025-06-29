import React, { useState, useEffect, useRef } from 'react';
import { format, addMonths, subMonths, startOfMonth, endOfMonth, startOfWeek, endOfWeek, addDays } from 'date-fns';

const DatePicker = ({
                        onClose,
                        onDateSelect,
                        initialPosition = { top: 100, left: 100 },
                        draggable = true,
                        staticPosition = false
                    }) => {
    const weekDays = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
    const [currentMonth, setCurrentMonth] = useState(new Date());
    const [selectedDate, setSelectedDate] = useState(null);
    const [position, setPosition] = useState(initialPosition);
    const [dragging, setDragging] = useState(false);
    const [dragStart, setDragStart] = useState({ x: 0, y: 0 });

    const modalRef = useRef(null);

    const handleDateClick = (day) => {
        setSelectedDate(day);
        onDateSelect({
            day: format(day, 'd'),
            month: format(day, 'MMMM'),
            year: format(day, 'yyyy'),
            fullDate: format(day, 'yyyy-MM-dd')
        });
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

    // Обновляем позицию при изменении initialPosition
    useEffect(() => {
        if (staticPosition) {
            setPosition(initialPosition);
        }
    }, [initialPosition, staticPosition]);

    return (
        <div
            className="modal-container"
            style={{
                position: 'absolute',
                top: `${position.top}px`,
                left: `${position.left}px`,
                cursor: dragging ? 'grabbing' : 'default'
            }}
            ref={modalRef}
            onMouseDown={handleMouseDown}
        >
            <div className="date-picker-modal">
                <div className={`modal-header ${draggable ? 'draggable' : ''}`}>
                    <h3>Выберите дату</h3>
                </div>

                <div className="date-picker-content">
                    {renderHeader()}
                    {renderDays()}
                    {renderCells()}
                </div>

                <div className="modal-actions">
                    <button onClick={onClose} className="cancel-button">Закрыть</button>
                </div>
            </div>
        </div>
    );
};

export default DatePicker;