import {TasksDateDto} from "./TasksDateDto";

export interface Task {
    cacheId: number;
    id: number | string;
    title?: string;
    todo: boolean;
    description?: string;
    timeData: TasksDateDto;
}