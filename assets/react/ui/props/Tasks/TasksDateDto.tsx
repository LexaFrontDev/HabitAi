import {TaskDurationDto} from "./TaskDurationDto";

export interface TasksDateDto {
    date?: string;
    time?: string | undefined;
    repeat: string;
    duration: TaskDurationDto;
}