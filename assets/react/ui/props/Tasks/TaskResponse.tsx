import {Task} from "./Task";

export interface TaskResponse {
    cacheId: number;
    success: boolean;
    message: string;
    front: boolean;
    task?: number|string;
}

