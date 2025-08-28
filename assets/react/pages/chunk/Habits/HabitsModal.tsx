import React, { useState, useRef, useEffect } from 'react';
import {DateType} from "../../../ui/props/Habits/DateType";
import {DatesType} from "../../../ui/props/Habits/DatesType";
import {DataType} from "../../../ui/props/Habits/DataType";
import {EditDataType} from "../../../ui/props/Habits/EditHabitsDataType";
import {Days, getAllTranslatedDays, getTranslatedDaysArray} from "../../../ui/props/Habits/Days";
import {HabitTemplate} from "../../../ui/props/Habits/HabitTemplate";
import {HabitModalProps} from "../../../ui/props/Habits/HabitModalProps";
import {Day} from "../../../ui/props/Habits/DaysType";
import {renderStep} from "../../../Infrastructure/HabitModal/RenderCreateHabitsModal";
import {SettingKey} from "../../../ui/props/Habits/RenderHabits/SettingKey";
import {renderStepEdit} from "../../../Infrastructure/HabitModal/renderStepEditModal";





const daysList = getAllTranslatedDays();

const HabitModal: React.FC<HabitModalProps> = ({habitTemplates, onClose, onEdit, onSave, edit = false , editData}) => {

    const [step, setStep] = useState(1);
    const [data, setData] = useState<DataType>({
        title: '',
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
            sun: false,
        },
        beginDate: Math.floor(Date.now() / 1000),
        notificationDate: '',
        purposeType: 'count',
        purposeCount: 1,
        checkManually: false,
        checkAuto: false,
        checkClose: false,
        autoCount: 0,
    });

    const [error, setError] = useState('');
    const [expandedSettings, setExpandedSettings] = useState({
        goal: false,
        startDate: false,
        duration: false,
        reminder: false
    });

    function toTimestamp(mysqlDateTime: string): number {
        const d = new Date(mysqlDateTime.replace(" ", "T"));
        return Math.floor(d.getTime() / 1000);
    }





    useEffect(() => {
        if (edit && editData) {
            console.log('То что содержить edit data')
            console.log(editData);
            const notificationRaw = editData.notification_date || '';
            const timeOnly = notificationRaw.length >= 5 ? notificationRaw.substring(0, 5) : '';



            setData({
                ...data,
                title: editData.title,
                quote: editData.quote,
                notificationDate: timeOnly,
                datesType:  editData.data_type,
                goalInDays: String(editData.goal_in_days ?? ''),
                beginDate: toTimestamp(editData.begin_date),
                autoCount: editData.auto_count,
                checkAuto: editData.check_auto,
                checkClose: editData.check_close,
                checkManually:  editData.check_manually,
                purposeType: editData.type,
                purposeCount: editData.count,
                date: editData.date
            });
            console.log(data);
            setStep(2);
        }
    }, [edit, editData]);



    const toggleSetting = (setting: SettingKey) => {
        setExpandedSettings(prev => ({
            ...prev,
            [setting]: !prev[setting]
        }));
    };

    const renderFrequencyOptions = () => {
        if (data.datesType === 'daily') {
            const daysList = getTranslatedDaysArray();
            const daysMap = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
            return (
                <div className="date-block">
                    <div>Выберите дни недели:</div>
                    <div className="day-grid">
                        {daysList.map((d, i) => {
                            const dayKey = daysMap[i] as Days;
                            return (
                                <div
                                    key={d}
                                    className={`day-item ${data.date[dayKey] ? 'active-day' : ''}`}
                                    data-day={dayKey}
                                    onClick={() => toggleDay(dayKey)}
                                >
                                    {d}
                                </div>
                            );
                        })}
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

    const toggleDay = (day: Day) => {
        setData({
            ...data,
            date: {
                ...data.date,
                [day]: !data.date[day]
            }
        });
    };




    const validateStep = (currentStep: number) => {
        if (currentStep === 2) {
            if (!data.title.trim()) {
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

        const payload: DataType = {
            title: data.title,
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

        if (onSave) {
            onSave(payload);
        }
    };

    const editHandler = () => {
        if (!validateStep(step) || !editData?.habit_id) return;

        const payload: EditDataType = {
            habitId: editData.habit_id,
            title: data.title,
            quote: data.quote,
            goalInDays: data.goalInDays,
            datesType: data.datesType,
            date: prepareDateField(),
            beginDate: data.beginDate,
            notificationDate: data.notificationDate,
            purposeType: data.purposeType,
            purposeCount: data.purposeCount,
            checkManually: Boolean(data.checkManually),
            checkAuto: Boolean(data.checkAuto),
            checkClose: Boolean(data.checkClose),
            autoCount: data.autoCount
        };

        if (onEdit) {
            onEdit(payload);
        }
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








    return (
        <div className="modal">
            {error && (
                <div
                    className="error-message"
                    style={{
                        color: '#ff3d3d',
                        margin: '10px',
                        textAlign: 'center',
                        fontWeight: 'bold',
                    }}
                >
                    {error}
                </div>
            )}

            {edit ? renderStepEdit({
                step,
                setStep,
                data,
                setData,
                onClose: () => onClose,
                validateStep,
                expandedSettings,
                toggleSetting,
                renderFrequencyOptions,
                editHandler,
            }) : renderStep({
                step,
                setStep,
                habitTemplates: habitTemplates,
                data,
                setData,
                onClose: () => onClose,
                validateStep,
                expandedSettings,
                toggleSetting,
                renderFrequencyOptions,
                saveData,
            })}
        </div>
    );
};

export default HabitModal;