
import {PomodoroData} from "../../ui/props/Habits/PomodoroData";
import {PomodoroCreateType} from "../../ui/props/Pomodoro/PomodoroCreateType";
import {ErrorAlert} from "../../pages/chunk/MessageAlertChunk";
import {RequestServices} from "../Ctn/RequestServices";
import {DataType} from "../../ui/props/Habits/DataType";


export class PomodoroService {
    constructor(private readonly cacheService: RequestServices) {}

    async getPomdoroSummary(): Promise<PomodoroData[] | false | PomodoroData> {
        return await this.cacheService.get<PomodoroData>(`/api/pomodor/summary`, 'GET');
    }

    async createPomodro(data: PomodoroCreateType): Promise<boolean> {

        const response = this.cacheService.create('/api/pomodor/create', "POST", data);

        if (!response) {
            ErrorAlert('Не удалось сохранить историю на сервере, данные будут синхронизированы позже');
            return false;
        }
        return response;
    }



}