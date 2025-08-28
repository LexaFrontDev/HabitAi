import {HabitTemplate} from "../HabitTemplate";
import {DataType} from "../DataType";
import React, {JSX} from "react";
import {SettingKey} from "./SettingKey";

export interface RenderStepProps {
    step: number;
    setStep: React.Dispatch<React.SetStateAction<number>>;
    habitTemplates?: HabitTemplate[];
    data: DataType;
    setData: React.Dispatch<React.SetStateAction<DataType>>;
    onClose: () => void;
    validateStep: (step: number) => boolean;
    expandedSettings: Record<string, boolean>;
    toggleSetting: (setting: SettingKey) => void;
    renderFrequencyOptions: () => JSX.Element;
    saveData: () => void;
}