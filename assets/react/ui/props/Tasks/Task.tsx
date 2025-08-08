import {TasksDateDto} from "./TasksDateDto";

export interface Task {
    id: number;
    title?: string;
    todo: boolean;
    description?: string;
    timeData: TasksDateDto;
}
