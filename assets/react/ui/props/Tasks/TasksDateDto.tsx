import {TaskDurationDto} from "./TaskDurationDto";

export interface TasksDateDto {
    date?: string;
    time?: string | null;
    repeat: string;
    duration: TaskDurationDto;
}