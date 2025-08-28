import React, {JSX} from "react";
import {HabitTemplate} from "../HabitTemplate";
import {DataType} from "../DataType";
import {SettingKey} from "./SettingKey";

export interface RenderStepPropsEdit {
    step: number;
    setStep: React.Dispatch<React.SetStateAction<number>>;
    data: DataType;
    setData: React.Dispatch<React.SetStateAction<DataType>>;
    onClose: () => void;
    validateStep: (step: number) => boolean;
    expandedSettings: Record<string, boolean>;
    toggleSetting: (setting: SettingKey) => void;
    renderFrequencyOptions: () => JSX.Element;
    editHandler: () => void;
}