import { Position } from "../../../ui/props/ResizablePanel/Position";
import {StartResizeParams} from "../../../ui/props/ResizablePanel/StartResizeParams";
import React from "react";

export type ResizeDirection = "left" | "right";

const getResizeMeta = (e: React.MouseEvent, position: Position): { direction: ResizeDirection } => {
    const target = e.currentTarget as HTMLElement;
    const isLeftHandle = target.classList.contains("left-resize");

    const direction: ResizeDirection =
        position === "left" || (position === "center" && isLeftHandle) ? "right" : "left";

    return { direction };
};



export const createResizeHandler = ({
                                        position,
                                        minWidth,
                                        maxWidth,
                                        width,
                                        setWidth,
                                    }: StartResizeParams) => {
    return (e: React.MouseEvent) => {
        e.preventDefault();

        const startX = e.clientX;
        const startWidth = width;
        const { direction } = getResizeMeta(e, position);

        const onMouseMove = (moveEvent: MouseEvent) => {
            let delta = moveEvent.clientX - startX;
            if (direction === "left") delta = -delta;

            const newWidth = Math.min(maxWidth, Math.max(minWidth, startWidth + delta));
            setWidth(newWidth);
        };

        const onMouseUp = () => {
            window.removeEventListener("mousemove", onMouseMove);
            window.removeEventListener("mouseup", onMouseUp);
        };

        window.addEventListener("mousemove", onMouseMove);
        window.addEventListener("mouseup", onMouseUp);
    };
};

