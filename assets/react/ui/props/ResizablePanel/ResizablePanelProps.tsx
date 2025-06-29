import React from "react";
import {Position} from "./Position";


export interface ResizablePanelProps {
    position: Position;
    minWidth?: number;
    maxWidth?: number;
    children: React.ReactNode;
    onClose?: boolean; // добавить это
}