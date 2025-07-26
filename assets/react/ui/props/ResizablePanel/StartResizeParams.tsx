import {Position} from "./Position";

export interface StartResizeParams {
    position: Position;
    minWidth: number;
    maxWidth: number;
    width: number;
    setWidth: (width: number) => void;
    leftWidth?: number;
    rightWidth?: number;
    centerWidth?: number;
}