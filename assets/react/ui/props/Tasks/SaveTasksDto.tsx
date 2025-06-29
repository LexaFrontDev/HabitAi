import {TasksDateDto} from "./TasksDateDto";

export interface SaveTasksDto {
    title: string;
    description?: string;
    timeData: TasksDateDto;
}