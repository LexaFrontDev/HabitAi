import {Task} from "../../../ui/props/Tasks/Task";
import {TaskUpdate} from "../../../ui/props/Tasks/TaskUpdate";
import {TasksReqInterface} from "../../../Domain/request/Tasks/TasksReqInterface";
import {ListTasks} from "../../../ui/props/Tasks/ListTasks/ListTasks";

export class TasksApi implements TasksReqInterface{
    async getTasksByDate(date: string): Promise<Task[]> {
        const res = await fetch(`/api/tasks/get${date}`);
        const data = await res.json();
        if (!data.success) throw new Error(data.message || 'Ошибка получения задач');
        return data.data;
    }

    async getTasksAll(): Promise<Task[]> {
        const res = await fetch(`/api/tasks/all/get`);

        if (!res.ok) {
            const error = await res.json().catch(() => ({}));
            throw new Error(error.message || 'Ошибка получения задач');
        }

        return await res.json();
    }



    async getListTasks(): Promise<ListTasks[] | false> {
        const res = await fetch(`/api/list/tasks/all`);

        if (!res.ok) {
            const error = await res.json().catch(() => ({}));
            throw new Error(error.message || 'Ошибка получения задач');
        }

        return await res.json();
    }



    async saveTask(task: Partial<Task>): Promise<any> {
        const res = await fetch('/api/tasks/save', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(task),
        });
        return await res.json();
    }

    async updateTask(task: TaskUpdate): Promise<any> {
        const res = await fetch(`/api/tasks/update`, {
            method: 'PUT',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(task),
        });
        return await res.json();
    }

    async deleteTask(taskId: string | number): Promise<any> {
        const res = await fetch(`/api/tasks/delete/${taskId}`, { method: 'DELETE' });
        return await res.json();
    }

    async toggleWontDo(taskId:  number): Promise<any> {
        const res = await fetch(`/api/tasks/to/do`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ task_id: taskId }),
        });
        return await res.json();
    }
}


