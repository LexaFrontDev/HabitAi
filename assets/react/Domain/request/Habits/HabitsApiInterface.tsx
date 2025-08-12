import {DataType} from "../../../ui/props/Habits/DataType";
import {EditDataType} from "../../../ui/props/Habits/EditHabitsDataType";

export interface HabitsApiInterface{
    getHabitsByDate(date: string): Promise<DataType[]>;
    getHabitsAll(limit: number, offset: number): Promise<DataType[] | false>;
    saveHabits(habits: Partial<DataType>): Promise<any>;
    updateHabits(habits: EditDataType): Promise<any>
    deleteHabits(taskId: number): Promise<any>
    saveHabitProgress(habitId: number, countProgress: number): Promise<any>
}