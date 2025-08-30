import {DatesType} from "./DatesType";
import {DateType} from "./DateType";

export type EditDataType = {
    cacheId: number;
    habitId: number;
    title: string;
    quote: string;
    goalInDays: string;
    datesType: DatesType;
    date: DateType;
    beginDate: number;
    notificationDate: string;
    purposeType: 'count' | string;
    purposeCount: number;
    checkManually: boolean;
    autoCount: number;
    checkAuto: boolean;
    checkClose: boolean;
};