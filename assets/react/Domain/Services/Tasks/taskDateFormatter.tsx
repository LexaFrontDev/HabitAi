import {Task} from "../../../ui/props/Tasks/Task";

export function formatTaskDateTime(task: Task): string[] {
    const { duration, time, date } = task.timeData ?? {};
    const result: string[] = [];



    if (duration && (duration.startDate || duration.endDate || duration.startTime || duration.endTime)) {

        const { startDate, endDate, startTime, endTime } = duration;
        if (startDate && endDate) result.push(`${startDate} - ${endDate}`);
        if (startTime && endTime) result.push(`${startTime} - ${endTime}`);
        return result;
    }



    if (date) {
        const dt = new Date(date);
        const day = dt.getDate().toString().padStart(2, '0');
        const month = (dt.getMonth() + 1).toString().padStart(2, '0');
        const year = dt.getFullYear();
        const formattedDate = `${day}/${month}/${year}`;
        result.push(formattedDate);
        if (time) result.push(time);
        return result;
    }


    if (time) return [time];

    return ["Время не указано"];
}
