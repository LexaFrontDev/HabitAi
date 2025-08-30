
import {HabitsService} from "./HabitsService";
import {useState} from "react";
import {ErrorAlert, Messages} from "../../pages/chunk/MessageAlertChunk";
import {DataType} from "../../ui/props/Habits/DataType";
import {HabitsDatasWithStatistic} from "../../ui/props/Habits/HabitsDatasWithStatistic";
import {HabitTemplate} from "../../ui/props/Habits/HabitTemplate";
import {EditDataType} from "../../ui/props/Habits/EditHabitsDataType";

export const useHabitsLogic = (habitsService: HabitsService) => {
    const [currentPeriod, setCurrentPeriod] = useState('');
    const [habits, setHabits] = useState<any[]>([]);
    const [editingHabit, setEditingHabit] = useState<any | null>(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [showManualInput, setShowManualInput] = useState(false);
    const [manualInputValue, setManualInputValue] = useState('');
    const [currentHabitId, setCurrentHabitId] = useState(null);
    const [HabitsStatistic, setHabitsStatistic] = useState<any[] | null>(null)
    const [HabitsCurrentStatistics, SetHabitsCurrentStatistics] = useState<any | null>(null)
    const [habitsSide, setHabitSide] = useState<any | null>(null);
    const [IsLoading, setIsLoading] = useState(false);
    const [Templates, setTemplates] = useState<HabitTemplate[] | undefined>(undefined)

    const fetchHabits = async () => {
        setIsLoading(true);
        const hours = new Date().getHours();
        let period = 'other';
        if (hours >= 5 && hours < 12) {
            period = 'morning';
        } else if (hours >= 12 && hours < 18) {
            period = 'midday';
        } else {
            period = 'night';
        }


        setCurrentPeriod(period);

        let result = await habitsService.getHabitsAll();


        if(!result){
            setIsLoading(false);
            return setHabits([]);
        }
        setIsLoading(false);
        setHabits(result || []);
    };

    const fetchHabitsStatistic = async () => {
        setIsLoading(true);
        let statistic = await habitsService.getHabitsStatisticAll();
        if(!statistic){
            setIsLoading(false);
            return setHabitsStatistic([]);
        }


        console.log(statistic)
        setIsLoading(false);
        setHabitsStatistic(statistic || [])
    }


    const fetchHabitsTemplates = async () => {
        setIsLoading(true);
        let templates = await habitsService.getHabitsTemplatesAll();
        if(!templates){
            setIsLoading(false);
            return setTemplates([]);
        }

        setIsLoading(false);
        setTemplates(templates || [])
    }

    const saveHabitProgress = async (habitId: number, countProgress: number) => {
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
        let result = await habitsService.saveProgress(habitId, countProgress)
        if (result.success) {
            Messages('Умничка продолжай в этом же духе')
        } else {
            console.error('Ошибка при сохранении прогресса привычки');
        }
    };

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

    const handleSave = async (habitData: DataType) => {
        try {
            let result = await habitsService.createHabits(habitData);
            setIsModalOpen(false);
            setEditingHabit(null);
            Messages('Привычка сохранена!');



            if (result.success) {
                await fetchHabits();
            } else {
                Messages(result.message || 'Ошибка сохранения данных!');
            }
        } catch (error) {
            Messages('Произошла ошибка! Попробуйте позже.');
        }
    };

    const handleUpdate = async (habitData: EditDataType) => {
        try {
            let result = await habitsService.updateHabits(habitData, habitData.cacheId);
            setIsModalOpen(false);
            setEditingHabit(null);
            Messages('Привычка обновлена!');

            if (result.success) {
                await fetchHabits();
            } else {
                Messages(result.message || 'Ошибка сохранения данных!');
            }
        } catch (error) {
            Messages('Произошла ошибка! Попробуйте позже.');
        }
    };

    const handleDelete = async (habitId: number, cacheId: number) => {
        if (window.confirm('Вы уверены, что хотите удалить эту привычку?')) {
            let  result = await habitsService.deleteHabits(habitId, cacheId)

            if (result.success) {
                Messages('Привычка удалена!');
                await fetchHabits();
            } else {
                Messages(result.message || 'Ошибка удаления привычки!');
            }
        }
    };

    const handleSideStatistic = (habit: any) => {
        if (!habit) return SetHabitsCurrentStatistics([]);

        if (!HabitsStatistic) {
            return ;
        }


        // @ts-ignore
        // ВНИМАНИЕ МЕСТО ТРЕБУЕТЬ ЧТО БЫ БЫЛ АССОЦИАТИВНЫЙ МАССИВ
        const habitStats = HabitsStatistic[String(habit.habit_id)];

        setHabitSide(habit);
        SetHabitsCurrentStatistics(habitStats);
    };

    const handleManualConfirm = async () => {
        const count = parseInt(manualInputValue);
        if (count > 0 && currentHabitId) {
            await saveHabitProgress(currentHabitId, count);
            setShowManualInput(false);
            setManualInputValue('');
        }
    };

    const handleManualCancel = () => {
        setShowManualInput(false);
        setManualInputValue('');
    };

    return {
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
    }
}