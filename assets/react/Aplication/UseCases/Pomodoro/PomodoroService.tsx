import {PomodoroInterfaceRequest} from "../../../Domain/request/Pomodoro/PomodoroInterfaceRequest";
import {PomodoroData} from "../../../ui/props/Habits/PomodoroData";
import {PomodoroCreateType} from "../../../ui/props/Pomodoro/PomodoroCreateType";
import {ErrorAlert} from "../../../pages/chunk/MessageAlertChunk";
import {PomodoroAPi} from "../../../Infrastructure/request/Pomodoro/PomodoroAPi";

export class PomodoroService {
    constructor(private readonly PomodoroAPi: PomodoroInterfaceRequest) {}

    async getPomdoroSummary(): Promise<PomodoroData | false> {
        return await this.PomodoroAPi.getPomodoroInfo();
    }

    async createPomodro(data: PomodoroCreateType): Promise<boolean>{
        let result = await this.PomodoroAPi.createPomodoro(data);
        if(!result) ErrorAlert('при сохранение историй помодоро пройзошла ошибка')
        return result;
    }

}