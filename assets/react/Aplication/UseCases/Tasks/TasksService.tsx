import {TasksReqInterface} from "../../../Domain/request/Tasks/TasksReqInterface";
import {TaskUpdate} from "../../../ui/props/Tasks/TaskUpdate";
import {Task} from "../../../ui/props/Tasks/Task";
import {TaskResponse} from "../../../ui/props/Tasks/TaskResponse";
import {SaveTasksDto} from "../../../ui/props/Tasks/SaveTasksDto";

export class TasksService {
    constructor(private readonly tasksApi: TasksReqInterface) {}

    async getTasksFor(date: string): Promise<Task[]> {
        return await this.tasksApi.getTasksByDate(date);
    }

    async getTasksAll(): Promise<Task[]> {
        return await this.tasksApi.getTasksAll();
    }

    async createTask(task: Partial<SaveTasksDto>): Promise<TaskResponse> {
        try {
            const response = await this.tasksApi.saveTask(task);
            return {
                success: true,
                message: response.data?.message ?? 'Задача успешно создана',
                front: response.data?.front ?? false,
                task: response.data,
            };
        } catch (error: any) {
            const errRes = error?.response?.data;
            return {
                success: false,
                message: errRes?.message ?? 'Ошибка при создании задачи',
                front: errRes?.front ?? false,
            };
        }
    }



    async updateTask(task: TaskUpdate): Promise<TaskResponse> {
        try {
            const response = await this.tasksApi.updateTask(task);
            return {
                success: true,
                message: response.data?.message ?? 'Задача успешно обновлена',
                front: response.data?.front ?? false,
                task: response.data,
            };
        } catch (error: any) {
            const errRes = error?.response?.data;
            return {
                success: false,
                message: errRes?.message ?? 'Ошибка при обновлении задачи',
                front: errRes?.front ?? false,
            };
        }
    }


    async deleteTask(taskId: number | string): Promise<TaskResponse> {
        try {
            await this.tasksApi.deleteTask(taskId);
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


    async toggleWontDo(id: number | string): Promise<TaskResponse> {
        try {
            const response = await this.tasksApi.toggleWontDo(id);
            return {
                success: true,
                message: response?.data?.message || '',
                front: false,
            };
        } catch (error: any) {
            return {
                success: false,
                message: error?.response?.data?.message || 'Ошибка при обновлении статуса задачи',
                front: error?.response?.data?.front ?? false,
            };
        }
    }

}
