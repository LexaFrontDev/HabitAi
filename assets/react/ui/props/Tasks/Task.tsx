import {TasksDateDto} from "./TasksDateDto";

export interface Task {
    id: number | string;
    title?: string;
    todo: boolean;
    description?: string;
    timeData: TasksDateDto;
}
