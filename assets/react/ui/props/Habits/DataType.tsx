import {DateType} from "./DateType";
import {DatesType} from "./DatesType";

export type DataType = {
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
    checkAuto: boolean;
    checkClose: boolean;
    autoCount: number;
};