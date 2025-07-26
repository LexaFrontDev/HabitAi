import React from "react";
import {Position} from "./Position";


export interface ResizablePanelProps {
    position: Position;
    minWidth?: number;
    maxWidth?: number;
    children: React.ReactNode;
    onClose?: boolean;
    widths?: number;
    onResize?: (widths: number) => void;
    leftWidth?: number;
    rightWidth?: number;
    centerWidth?: number;
}
