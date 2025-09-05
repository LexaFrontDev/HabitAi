
import {TaskUpdate} from "../../ui/props/Tasks/TaskUpdate";
import {Task} from "../../ui/props/Tasks/Task";
import {TaskResponse} from "../../ui/props/Tasks/TaskResponse";
import {SaveTasksDto} from "../../ui/props/Tasks/SaveTasksDto";
import {ListTasks} from "../../ui/props/Tasks/ListTasks/ListTasks";
import {RequestServices} from "../Ctn/RequestServices";
import {DataType} from "../../ui/props/Habits/DataType";
import {CreateListReq} from "../../ui/props/Tasks/ListTasks/CreateListReq";

export class TasksService {
    constructor(private readonly cacheService: RequestServices) {}

    async getTasksFor(date: string): Promise<Task[] | false> {
        const allTasks = await this.getTasksAll();
        if (!allTasks) return false;

        return (allTasks as Task[]).filter(task => task.timeData.date?.startsWith(date));
    }

    async getTasksAll(): Promise<Task[] | false | Task> {
        return await this.cacheService.get<Task>(`/api/tasks/all/get`, 'GET');
    }

    async getListTasks(): Promise<ListTasks[] | false | ListTasks> {
        const response = await this.cacheService.get<ListTasks>(
            '/api/list/tasks/all',
            'GET',
            undefined,
            'result'
        );

        return response;
    }



    async createTask(task: Partial<SaveTasksDto>): Promise<TaskResponse> {
        try {
            const response = await this.cacheService.create( '/api/tasks/save', "POST", task)
            return {
                success: true,
                message: response.message ?? 'Задача успешно создана',
                front: response.front ?? false,
                task: response.data
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

    async createList(tasksList: CreateListReq): Promise<boolean>{
        try {
            const response = await this.cacheService.create('/api/list/tasks/save', "POST", tasksList)

            if(response.ok){
                return true
            }
            return false
        } catch (error: any) {
            const errRes = error?.response?.data;
            console.error(errRes)
            return false;
        }
    }




    async updateTask(task: TaskUpdate): Promise<TaskResponse> {
        try {
            const response = await this.cacheService.update<any>( `/api/tasks/update`,  'PUT', task);
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
            const response = await this.cacheService.delete(`/api/tasks/delete/${taskId}`,  'DELETE');
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


    async toggleWontDo(id: number | string,  status: boolean): Promise<TaskResponse> {
        try {
            const response = await this.cacheService.create(`/api/tasks/to/do`, "POST", { task_id: id })

            return {
                success: true,
                message: response?.message || '',
                front: status,
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
