import {DatesType} from "./DatesType";

export interface HabitTemplate {
    title: string;
    quote: string;
    notification: string;
    datesType: DatesType;
}