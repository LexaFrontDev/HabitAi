import {TasksDateDto} from "./TasksDateDto";

export interface TaskUpdate {
    id?: number;
    title: string;
    description?: string;
    timeData: TasksDateDto;
}
