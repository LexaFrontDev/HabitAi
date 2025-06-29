import {Task} from "./Task";

export interface TaskResponse {
    success: boolean;
    message: string;
    front: boolean;
    task?: Task;
}

