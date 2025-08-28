import React, { useState } from 'react';
import Calendar from 'react-calendar';
import 'react-calendar/dist/Calendar.css';

const trackedDays = [
    '2025-08-20',
    '2025-08-22',
    '2025-08-25',
];

const HabitCalendar: React.FC = () => {
    const [selectedDate, setSelectedDate] = useState<Date | null>(null);

    return (
        <div>
            <h2>Календарь привычек</h2>
            <Calendar
                onClickDay={setSelectedDate}
                value={selectedDate || new Date()}
                tileClassName={({ date, view }) => {
                    if (view === 'month') {
                        const dateStr = date.toISOString().split('T')[0];
                        if (trackedDays.includes(dateStr)) {
                            return 'tracked-day';
                        }
                    }
                    return '';
                }}
            />
            {selectedDate && (
                <p>Выбрана дата: {selectedDate.toDateString()}</p>
            )}
        </div>
    );
};

export default HabitCalendar;
