import {PomodoroCreateType} from "../../../ui/props/Pomodoro/PomodoroCreateType";
import {PomodoroData} from "../../../ui/props/Habits/PomodoroData";

export interface PomodoroInterfaceRequest{
     createPomodoro(data: PomodoroCreateType): Promise<boolean>;
     getPomodoroInfo(): Promise<PomodoroData | false>;

}