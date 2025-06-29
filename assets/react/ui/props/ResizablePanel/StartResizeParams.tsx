import {Position} from "./Position";

export interface StartResizeParams {
    position: Position;
    minWidth: number;
    maxWidth: number;
    width: number;
    setWidth: (value: number) => void;
}