import {DatesType} from "./DatesType";
import {DateType} from "./DateType";

export type EditDataType = {
    habitsId: number;
    titleHabit: string;
    quote: string;
    goalInDays: string;
    datesType: DatesType;
    date: DateType;
    beginDate: number;
    notificationDate: string;
    purposeType: 'count' | string;
    purposeCount: number;
    checkManually: boolean;
    checkAuto: boolean;
    checkClose: boolean;
    autoCount: number;
};