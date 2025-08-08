import {Task} from "../../../ui/props/Tasks/Task";
import {TaskUpdate} from "../../../ui/props/Tasks/TaskUpdate";

export interface TasksReqInterface{
     getTasksByDate(date: string): Promise<Task[]>;
     getTasksAll(): Promise<Task[]>;
     saveTask(task: Partial<Task>): Promise<any>;
     updateTask(task: TaskUpdate): Promise<any>
     deleteTask(taskId: string | number): Promise<any>
     toggleWontDo(taskId: number): Promise<any>
}