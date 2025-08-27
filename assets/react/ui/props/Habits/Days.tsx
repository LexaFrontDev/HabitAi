import { t } from "i18next";

const weekShort = {
    mon: "week_short.mon",
    tue: "week_short.tue",
    wed: "week_short.wed",
    thu: "week_short.thu",
    fri: "week_short.fri",
    sat: "week_short.sat",
    sun: "week_short.sun",
} as const;

export type Days = keyof typeof weekShort;

export function translateDay(day: Days) {
    return t(weekShort[day]);
}


export function getAllTranslatedDays(): Record<Days, string> {
    return Object.fromEntries(
        (Object.keys(weekShort) as Days[]).map(day => [day, t(weekShort[day])])
    ) as Record<Days, string>;
}


export function getTranslatedDaysArray(): string[] {
    return (Object.keys(weekShort) as Days[]).map(day => t(weekShort[day]));
}
