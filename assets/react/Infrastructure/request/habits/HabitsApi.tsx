import {HabitsApiInterface} from "../../../Domain/request/Habits/HabitsApiInterface";
import {DataType} from "../../../ui/props/Habits/DataType";
import {EditDataType} from "../../../ui/props/Habits/EditHabitsDataType";
import {HabitsDatasWithStatistic} from "../../../ui/props/Habits/HabitsDatasWithStatistic";
import {HabitTemplate} from "../../../ui/props/Habits/HabitTemplate";

export class HabitsApi implements HabitsApiInterface
{
    async getHabitsByDate(date: string): Promise<DataType[]> {
        const response = await fetch(`/api/get/Habits/by/day?date=${date}`, {
            method: 'GET',
            headers: {'Content-Type': 'application/json'},
        });

        const data = await response.json();
        return data.data;
    }


    async  getHabitsStatisticByHabitsId(habitId: number): Promise<any>{
        const response = await fetch(`/api/Habits/statistic/all/${habitId}`, {
            method: 'GET',
            headers: {'Content-Type': 'application/json'},
        });

        return await response.json();
    }

    async getHabitsAll(limit: number, offset: number): Promise<DataType[] | false> {
        const response = await fetch(`/api/get/Habits/all?limit=${limit}&offset=${offset}`, {
            method: 'GET',
            headers: {'Content-Type': 'application/json'},
        });

        const data = await response.json();


        if (!response.ok){
            return false;
        }
        return data.data;
    }


    async getHabitsTemplatesAll(): Promise<HabitTemplate[] | false>{
        const response = await fetch(`/api/Habits/templates/all`, {
            method: 'GET',
            headers: {'Content-Type': 'application/json'},
        });

        const data = await response.json();


        if (!response.ok){
            return false;
        }
        return data.result;
    }

    async getHabitsStatisticAll(): Promise<any | false>{
        const response = await fetch(`/api/Habits/statistic/all`, {
            method: 'GET',
            headers: {'Content-Type': 'application/json'},
        });

        const data = await response.json();


        if (!response.ok){
            return false;
        }
        return data.result;
    }

    async saveHabits(habits: Partial<DataType>): Promise<any> {
        const response = await fetch('/api/Habits/save', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(habits),
        });
        return  await response.json();
    }
    async updateHabits(habits: EditDataType): Promise<any> {
        const response = await fetch('/api/Habits/update', {
            method: 'PUT',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(habits),
        });
        return await response.json();
    }

    async deleteHabits(habitId: number): Promise<any> {
        const response = await fetch(`/api/Habits/delete/${habitId}`, {
            method: 'DELETE',
        });

        return await response.json();
    }
    async saveHabitProgress(habitId: number, countProgress: number): Promise<any> {
        const response =  await fetch('/api/Habits/save/progress', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                habits_id: habitId,
                count_end: countProgress
            })
        });
        return await response.json();
    }
}