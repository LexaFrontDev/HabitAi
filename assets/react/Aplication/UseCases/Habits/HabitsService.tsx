import {HabitsApiInterface} from "../../../Domain/request/Habits/HabitsApiInterface";
import {DataType} from "../../../ui/props/Habits/DataType";
import {Days} from "../../../ui/props/Habits/Days";
import {HabitsResponse} from "../../../ui/props/Habits/HabitsResponse";
import {EditDataType} from "../../../ui/props/Habits/EditHabitsDataType";
import {HabitsDatasWithStatistic} from "../../../ui/props/Habits/HabitsDatasWithStatistic";
import {HabitTemplate} from "../../../ui/props/Habits/HabitTemplate";


export class HabitsService {
    constructor(private readonly habitsApi: HabitsApiInterface) {}

    async getHabitsFor(date: string): Promise<DataType[]> {
        return await this.habitsApi.getHabitsByDate(date);
    }


    async getHabitsStatistic(habitId: number): Promise<any>{
        return await this.habitsApi.getHabitsStatisticByHabitsId(habitId);
    }

    async getHabitsForDay(day: Days): Promise<DataType[]> {
        const date = this.getDateForDayOfWeek(day);
        return await this.habitsApi.getHabitsByDate(date);
    }


    private getDateForDayOfWeek(day: Days): string {
        const today = new Date();
        const currentDay = today.getDay();
        const dayMap: Record<Days, number> = {
            mon: 1,
            tue: 2,
            wed: 3,
            thu: 4,
            fri: 5,
            sat: 6,
            sun: 0
        };

        const targetDay = dayMap[day];
        const diff = targetDay - currentDay;
        const targetDate = new Date(today);
        targetDate.setDate(today.getDate() + diff);
        return targetDate.toISOString().split('T')[0];
    }


    async getHabitsAll(): Promise<DataType[] | false> {
        return await this.habitsApi.getHabitsAll(50, 0)
    }

    async getHabitsTemplatesAll():  Promise<HabitTemplate[] | false>{
        return await this.habitsApi.getHabitsTemplatesAll()
    }

    async getHabitsStatisticAll():  Promise<any | false>{
        return await this.habitsApi.getHabitsStatisticAll()
    }

    async createHabits(habits: Partial<DataType>): Promise<HabitsResponse> {
        try {
            const response = await this.habitsApi.saveHabits(habits);
            return {
                success: true,
                message: response.data?.message ?? 'Привычка успешно создана',
                front: response.data?.front ?? false,
                habits: response.data,
            };
        } catch (error: any) {
            const errRes = error?.response?.data;
            return {
                success: false,
                message: errRes?.message ?? 'Ошибка при создании Привычки',
                front: errRes?.front ?? false,
            };
        }
    }


    async updateHabits(habits: EditDataType): Promise<HabitsResponse> {
        try {
            const response = await this.habitsApi.updateHabits(habits);
            return {
                success: true,
                message: response.data?.message ?? 'Привычка успешно обновлена',
                front: response.data?.front ?? false,
                habits: response.data,
            };
        } catch (error: any) {
            const errRes = error?.response?.data;
            return {
                success: false,
                message: errRes?.message ?? 'Ошибка при обновлении привычки',
                front: errRes?.front ?? false,
            };
        }
    }


    async deleteHabits(habitId: number): Promise<HabitsResponse> {
        try {
            await this.habitsApi.deleteHabits(habitId);
            return {
                success: true,
                message: 'Задача успешно удалена',
                front: false,
            };
        } catch (error: any) {
            return {
                success: false,
                message: error?.response?.data?.message || 'Ошибка при удалении задачи',
                front: error?.response?.data?.front ?? false,
            };
        }
    }

    async saveProgress(habitId: number, countProgress: number): Promise<HabitsResponse> {
        try {
            const response = await this.habitsApi.saveHabitProgress(habitId, countProgress);
            return {
                success: true,
                message: response?.data?.message || '',
                front: false,
            };
        } catch (error: any) {
            return {
                success: false,
                message: error?.response?.data?.message || 'Ошибка при обновлении статуса привычки',
                front: error?.response?.data?.front ?? false,
            };
        }
    }

     toggleBlock = (blockName: string) => {
         const list = document.getElementById(blockName);
         const icon = document.getElementById(`icon-${blockName}`) as HTMLImageElement | null;

         if (!list || !icon) return;

         if (list.style.display === 'none') {
             list.style.display = 'block';
             icon.src = '/Upload/Images/AppIcons/arrow-down.svg'; // раскрыто
         } else {
             list.style.display = 'none';
             icon.src = '/Upload/Images/AppIcons/arrow-right.svg'; // свернуто
         }
    };





}