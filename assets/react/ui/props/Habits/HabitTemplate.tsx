import {DatesType} from "./DatesType";

export interface HabitTemplate {
    cacheId: number;
    title: string;
    quote: string;
    notification: string;
    datesType: DatesType;
}