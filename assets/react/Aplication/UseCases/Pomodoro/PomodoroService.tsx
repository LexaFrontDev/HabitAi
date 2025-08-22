import {PomodoroInterfaceRequest} from "../../../Domain/request/Pomodoro/PomodoroInterfaceRequest";
import {PomodoroData} from "../../../ui/props/Habits/PomodoroData";
import {PomodoroCreateType} from "../../../ui/props/Pomodoro/PomodoroCreateType";
import {ErrorAlert} from "../../../pages/chunk/MessageAlertChunk";

export class PomodoroService {
    constructor(private readonly PomodoroApi: PomodoroInterfaceRequest) {}

    async getPomdoroSummary(): Promise<PomodoroData | false> {
        return await this.PomodoroApi.getPomodoroInfo();
    }

    async createPomodro(data: PomodoroCreateType): Promise<boolean>{
        let result = await this.PomodoroApi.createPomodoro(data);
        if(!result) ErrorAlert('при сохранение историй помодоро пройзошла ошибка')
        return result;
    }

}