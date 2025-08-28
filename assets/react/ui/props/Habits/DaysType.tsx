import {DateType} from "./DateType";

export type Day = keyof Omit<DateType, 'count' | 'day'>;