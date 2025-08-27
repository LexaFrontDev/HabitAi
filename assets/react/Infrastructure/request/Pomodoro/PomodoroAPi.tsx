import {PomodoroInterfaceRequest} from "../../../Domain/request/Pomodoro/PomodoroInterfaceRequest";
import {PomodoroCreateType} from "../../../ui/props/Pomodoro/PomodoroCreateType";
import {PomodoroData} from "../../../ui/props/Habits/PomodoroData";

export class PomodoroAPi implements PomodoroInterfaceRequest
{
    async createPomodoro(data: PomodoroCreateType): Promise<boolean> {
        try {
            const response = await fetch('/api/pomodor/create', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data),
            });
            console.log(response)
            return response.ok;
        } catch (error) {
            console.error('Ошибка при запросе:', error);
            return false;
        }
    }


    async getPomodoroInfo(): Promise<PomodoroData | false> {
        try {
            const response = await fetch(`/api/pomodor/summary`, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' },
            });

            if (!response.ok) {
                return false;
            }

            return await response.json();
        } catch (error) {
            console.error('Ошибка при получении помидорок:', error);
            return false;
        }
    }



}