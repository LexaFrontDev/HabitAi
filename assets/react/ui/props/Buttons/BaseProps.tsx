import React from "react";
import {ButtonVariants} from "./ButtonVariants";

export interface BaseProps {
    children: React.ReactNode;
    variant?: ButtonVariants;
    className?: string;
    isActive?: boolean;
    onToggle?: (active: boolean) => void;
}