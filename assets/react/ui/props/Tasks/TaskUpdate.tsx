import {TasksDateDto} from "./TasksDateDto";

export interface TaskUpdate {
    id?: number | string;
    title: string;
    description?: string;
    timeData: TasksDateDto;
}
