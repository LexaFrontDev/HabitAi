
import {TaskUpdate} from "../../ui/props/Tasks/TaskUpdate";
import {Task} from "../../ui/props/Tasks/Task";
import {TaskResponse} from "../../ui/props/Tasks/TaskResponse";
import {SaveTasksDto} from "../../ui/props/Tasks/SaveTasksDto";
import {ListTasks} from "../../ui/props/Tasks/ListTasks/ListTasks";
import {CtnServices} from "../Ctn/CtnServices";
import {DataType} from "../../ui/props/Habits/DataType";

export class TasksService {
    constructor(private readonly cacheService: CtnServices) {}

    async getTasksFor(date: string): Promise<Task[] | false> {
        const allTasks = await this.getTasksAll();
        if (!allTasks) return false;

        return allTasks.filter(task => task.timeData.date && task.timeData.date.startsWith(date));
    }



    async getTasksAll(): Promise<Task[] | false> {
        return await this.cacheService.get<Task>('tasks', `/api/tasks/all/get`, 'GET');
    }

    async getListTasks(): Promise<ListTasks[] | false>{
        return await this.cacheService.get<ListTasks>('list_tasks', `/api/list/tasks/all`, 'GET');
    }

    async createTask(task: Partial<SaveTasksDto>): Promise<TaskResponse> {
        try {
            const serverResponse = await this.cacheService._request<any>('/api/tasks/save', 'POST', task);

            const cachedTask = await this.cacheService.createAlsoCache('tasks', {
                ...task,
                id: serverResponse.data
            });

            return {
                cacheId: cachedTask.cacheId,
                success: true,
                message: serverResponse.message ?? 'Задача успешно создана',
                front: serverResponse.front ?? false,
                task: serverResponse.data
            };
        } catch (error: any) {
            const errRes = error?.response?.data;
            return {
                cacheId: 0,
                success: false,
                message: errRes?.message ?? 'Ошибка при создании задачи',
                front: errRes?.front ?? false,
            };
        }
    }




    async updateTask(task: TaskUpdate): Promise<TaskResponse> {
        try {
            console.log(task);
            const response = await this.cacheService.update<any, any>(`tasks`, `/api/tasks/update`, task.cacheId, 'PUT', task);
            return {
                cacheId: response.cacheId,
                success: true,
                message: response.data?.message ?? 'Задача успешно обновлена',
                front: response.data?.front ?? false,
                task: response.data,
            };
        } catch (error: any) {
            const errRes = error?.response?.data;
            return {
                cacheId: 0,
                success: false,
                message: errRes?.message ?? 'Ошибка при обновлении задачи',
                front: errRes?.front ?? false,
            };
        }
    }


    async deleteTask(taskId: number | string, cacheId: number): Promise<TaskResponse> {
        try {
            const response = await this.cacheService.delete(`tasks`, `/api/tasks/delete/${taskId}`, cacheId, 'DELETE');
            return {
                cacheId: 0,
                success: true,
                message: 'Задача успешно удалена',
                front: false,
            };
        } catch (error: any) {
            return {
                cacheId: 0,
                success: false,
                message: error?.response?.data?.message || 'Ошибка при удалении задачи',
                front: error?.response?.data?.front ?? false,
            };
        }
    }


    async toggleWontDo(id: number | string, cachedId: number, status: boolean): Promise<TaskResponse> {
        try {
            const serverResponse = await this.cacheService._request<any>(`/api/tasks/to/do`, 'POST', { task_id: id });

            if (!serverResponse.ok) {
                throw new Error('Ошибка сервера');
            }

            await this.cacheService.updateFieldAlsoCache<Task, 'todo'>('tasks', cachedId, 'todo', status);
            return {
                cacheId: cachedId,
                success: true,
                message: serverResponse?.message || '',
                front: status,
            };
        } catch (error: any) {
            return {
                cacheId: 0,
                success: false,
                message: error?.response?.data?.message || 'Ошибка при обновлении статуса задачи',
                front: error?.response?.data?.front ?? false,
            };
        }
    }


}
