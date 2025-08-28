import {DataType} from "../../../ui/props/Habits/DataType";
import {EditDataType} from "../../../ui/props/Habits/EditHabitsDataType";
import {HabitsDatasWithStatistic} from "../../../ui/props/Habits/HabitsDatasWithStatistic";
import {HabitTemplate} from "../../../ui/props/Habits/HabitTemplate";

export interface HabitsApiInterface{
    getHabitsByDate(date: string): Promise<DataType[]>;
    getHabitsAll(limit: number, offset: number): Promise<DataType[] | false>;
    saveHabits(habits: Partial<DataType>): Promise<any>;
    updateHabits(habits: EditDataType): Promise<any>
    deleteHabits(taskId: number): Promise<any>
    saveHabitProgress(habitId: number, countProgress: number): Promise<any>
    getHabitsStatisticByHabitsId(habitId: number): Promise<any>
    getHabitsTemplatesAll(): Promise<HabitTemplate[] | false>
    getHabitsStatisticAll(): Promise<any | false>
}