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
        <div className="max-w-md mx-auto p-6 bg-gray-50 rounded-xl shadow-lg mt-10">
            <h1 className="text-3xl font-bold underline text-red-500 mb-4 text-center">
                Календарь привычек
            </h1>

            <Calendar
                onClickDay={setSelectedDate}
                value={selectedDate || new Date()}
                className="rounded-lg border border-gray-300 shadow-inner p-2"
                tileClassName={({ date, view }) => {
                    if (view === 'month') {
                        const dateStr = date.toISOString().split('T')[0];
                        if (trackedDays.includes(dateStr)) {
                            return 'tracked-day bg-blue-200 text-blue-800 font-bold rounded-full';
                        }
                    }
                    return '';
                }}
            />

            {selectedDate && (
                <p className="mt-4 text-center text-lg font-semibold text-gray-700">
                    Выбрана дата: {selectedDate.toDateString()}
                </p>
            )}
        </div>
    );
};

export default HabitCalendar;
