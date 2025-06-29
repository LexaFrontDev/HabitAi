import { Task } from "../../../ui/props/Tasks/Task";
import {TaskUpdate} from "../../../ui/props/Tasks/TaskUpdate";
import {TasksReqInterface} from "../../../Domain/request/Tasks/TasksReqInterface";

export class TasksApi implements TasksReqInterface{
    async getTasksByDate(date: string): Promise<Task[]> {
        const res = await fetch(`http://taskflow/api/tasks/get${date}`);
        const data = await res.json();
        if (!data.success) throw new Error(data.message || 'Ошибка получения задач');
        return data.data;
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
        const res = await fetch(`/api/tasks/${task.id}`, {
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

    async toggleWontDo(taskId: string | number, newStatus: boolean): Promise<any> {
        const res = await fetch(`/api/tasks/${taskId}/wontdo`, {
            method: 'PATCH',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ wontDo: newStatus }),
        });
        return await res.json();
    }
}


