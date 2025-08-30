import {DataType} from "../../ui/props/Habits/DataType";
import {Days} from "../../ui/props/Habits/Days";
import {HabitsResponse} from "../../ui/props/Habits/HabitsResponse";
import {EditDataType} from "../../ui/props/Habits/EditHabitsDataType";
import {HabitTemplate} from "../../ui/props/Habits/HabitTemplate";
import {CtnServices} from "../Ctn/CtnServices";


export class HabitsService {
    constructor(private readonly cacheService: CtnServices) {}

    async getHabitsFor(date: string): Promise<DataType[] | false> {
        return await this.cacheService.get<DataType>(`habits`, `/api/get/Habits/by/day?date=${date}`, 'GET');
    }

    async getHabitsStatistic(habitId: number): Promise<any>{
        return await this.cacheService.get<any>('habits_statistic', `/api/Habits/statistic/all/${habitId}`, 'GET');
    }

    async getHabitsForDay(day: Days): Promise<DataType[] | false> {
        const date = this.getDateForDayOfWeek(day);
        return await this.cacheService.get<DataType>(`habits`, `/api/get/Habits/by/day?date=${date}`, 'GET');
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


    async getHabitsAll(limit: number = 50, offset: number = 0): Promise<DataType[] | false> {
        return await this.cacheService.get<DataType>(`habits`, `/api/get/Habits/all?limit=${limit}&offset=${offset}`, 'GET');
    }

    async getHabitsTemplatesAll():  Promise<HabitTemplate[] | false>{
        return await this.cacheService.get<HabitTemplate>(`habits_templates`, `/api/Habits/templates/all`, 'GET');
    }

    async getHabitsStatisticAll():  Promise<any | false>{
        return await this.cacheService.get<any>(`habits_statistic`, `/api/Habits/statistic/all`, 'GET');
    }

    async createHabits(habits: Partial<DataType>): Promise<HabitsResponse> {
        try {
            const response = await this.cacheService.create<any, any>(`habits`, `/api/Habits/save`, 'POST', habits);
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


    async updateHabits(habits: EditDataType, cacheId: number): Promise<HabitsResponse> {
        try {
            const response = await this.cacheService.update<any, any>(`habits`, '/api/Habits/update', cacheId, 'PUT', habits);
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


    async deleteHabits(habitId: number, cacheId: number): Promise<HabitsResponse> {
        try {
            const response = await this.cacheService.delete(`habits`, `/api/Habits/delete/${habitId}`, cacheId, 'DELETE');
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

            const response = await this.cacheService.create<any, any>(`habits_progress`, '/api/Habits/save/progress', 'POST', {habits_id: habitId, count_end: countProgress});
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
             icon.src = '/Upload/Images/AppIcons/arrow-down.svg';
         } else {
             list.style.display = 'none';
             icon.src = '/Upload/Images/AppIcons/arrow-right.svg';
         }
    };





}