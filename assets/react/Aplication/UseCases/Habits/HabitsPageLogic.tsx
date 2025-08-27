
import {HabitsService} from "./HabitsService";
import {useState} from "react";
import {ErrorAlert, Messages} from "../../../pages/chunk/MessageAlertChunk";
import {DataType} from "../../../ui/props/Habits/DataType";
import {HabitsDatasWithStatistic} from "../../../ui/props/Habits/HabitsDatasWithStatistic";

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

    const fetchHabits = async () => {
        setIsLoading(true);
        const hours = new Date().getHours();
        let period = 'other';
        if (hours >= 5 && hours < 12) period = 'morning';
        else if (hours >= 18 || hours < 5) period = 'night';

        setCurrentPeriod(period);

        let result = await habitsService.getHabitsAll();


        if(!result){
            setIsLoading(false);
            return setHabits([]);
        }
        setIsLoading(false);
        setHabitsStatistic(result.statistic || [])
        setHabits(result.data || []);
    };

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
            Messages(editingHabit ? 'Привычка обновлена!' : 'Привычка сохранена!');



            if (result.success) {
                await fetchHabits();
            } else {
                Messages(result.message || 'Ошибка сохранения данных!');
            }
        } catch (error) {
            Messages('Произошла ошибка! Попробуйте позже.');
        }
    };

    const handleDelete = async (habitId: number) => {
        if (window.confirm('Вы уверены, что хотите удалить эту привычку?')) {
            let  result = await habitsService.deleteHabits(habitId)

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
        console.log(HabitsStatistic);
        console.log(habitStats);
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
        SetHabitsCurrentStatistics
    }
}