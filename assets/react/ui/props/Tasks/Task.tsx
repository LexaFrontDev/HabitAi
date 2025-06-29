import {TasksDateDto} from "./TasksDateDto";

export interface Task {
    id: number;
    title?: string;
    description?: string;
    timeData: TasksDateDto;
    wontDo: boolean;
}
