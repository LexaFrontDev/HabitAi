import {Period} from "../Tasks/type/periodType";
import {FilterOption} from "../Tasks/type/FilterOption";
import React from "react";

export type SidePanelProps = {
    onClose: () => void;
    activePeriod: Period;
    onPeriodChange: (period: Period) => void;
    filters?: FilterOption[];
    activeFilter: string;
    onFilterChange: (filterValue: string) => void;
    onAddClick: () => void;
    plusButtonContent?: React.ReactNode;
    closeButtonSrcImg: string;
    availableWidth?: number;
    onWidthChange?: (width: number) => void;
    onResize?: (width: number) => void;
};