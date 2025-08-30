
import {PomodoroData} from "../../ui/props/Habits/PomodoroData";
import {PomodoroCreateType} from "../../ui/props/Pomodoro/PomodoroCreateType";
import {ErrorAlert} from "../../pages/chunk/MessageAlertChunk";
import {CtnServices} from "../Ctn/CtnServices";
import {DataType} from "../../ui/props/Habits/DataType";


export class PomodoroService {
    constructor(private readonly cacheService: CtnServices) {}

    async getPomdoroSummary(): Promise<PomodoroData[] | false> {
        return await this.cacheService.get<PomodoroData>(`pomodoro`, `/api/pomodor/summary`, 'GET');
    }

    async createPomodro(data: PomodoroCreateType): Promise<boolean> {
        const cachedSummary = await this.cacheService.getAlsoCache<PomodoroData>('pomodoro');
        if (!cachedSummary || !cachedSummary.length) {
            ErrorAlert('Кэш с pomodoro пустой');
            return false;
        }

        const summary = cachedSummary[0];
        summary.pomodorHistory.push(data as any);


        const updated = await this.cacheService.updateAlsoCache('pomodoro', summary.cacheId, summary);
        if (!updated) {
            ErrorAlert('Ошибка обновления кэша истории помодоро');
            return false;
        }



        const response = await fetch('/api/pomodor/create', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            await this.cacheService.updateAlsoCache('pomodoro', summary.cacheId, { ...summary, _pending: true });
            ErrorAlert('Не удалось сохранить историю на сервере, данные будут синхронизированы позже');
            return false;
        }

        return response.ok;
    }



}