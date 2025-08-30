import { Position } from "../../ui/props/ResizablePanel/Position";
import { StartResizeParams } from "../../ui/props/ResizablePanel/StartResizeParams";
import React from "react";

export type ResizeDirection = "left" | "right";

const getResizeMeta = (
    e: React.MouseEvent,
    position: Position
): { direction: ResizeDirection } => {
    const target = e.currentTarget as HTMLElement;
    const isLeftHandle = target.classList.contains("left-resize");

    const direction: ResizeDirection =
        position === "left" || (position === "center" && isLeftHandle)
            ? "right"
            : "left";

    return { direction };
};

export const createResizeHandler = ({
                                        position,
                                        minWidth,
                                        maxWidth,
                                        width,
                                        setWidth,
                                        leftWidth,
                                        rightWidth,
                                        centerWidth,
                                    }: StartResizeParams) => {
    return (e: React.MouseEvent) => {
        e.preventDefault();
        const startX = e.clientX;
        const startWidth = width;
        const target = e.currentTarget as HTMLElement;
        const isLeftHandle = target.classList.contains("left-resize");
        const isRightHandle = target.classList.contains("right-resize");
        const panel = document.querySelector(`.side-panel.${position}`) as HTMLElement;
        const startLeft = panel ? parseFloat(getComputedStyle(panel).left) : 0;
        const GAP = 2;

        const onMouseMove = (moveEvent: MouseEvent) => {
            let delta = moveEvent.clientX - startX;
            let newWidth = startWidth;

            if (!panel) return;

            const safeLeft = leftWidth ?? 0;
            const safeRight = rightWidth ?? 0;
            const safeCenter = centerWidth ?? 0;

            if (position === "center") {
                const availableWidth = window.innerWidth - safeLeft - safeRight - GAP * 2;

                if (isLeftHandle) {
                    const proposedWidth = startWidth - delta;
                    newWidth = Math.min(
                        Math.min(maxWidth, availableWidth),
                        Math.max(minWidth, proposedWidth)
                    );

                    if (proposedWidth === newWidth) {
                        panel.style.left = `${startLeft + delta}px`;
                    }

                } else if (isRightHandle) {
                    const proposedWidth = startWidth + delta;
                    newWidth = Math.min(
                        Math.min(maxWidth, availableWidth),
                        Math.max(minWidth, proposedWidth)
                    );

                    if (proposedWidth === newWidth) {
                        panel.style.left = `${startLeft}px`;
                    }
                }

                panel.style.width = `${newWidth}px`;
                panel.style.transform = "none";
                setWidth(newWidth);
                return;
            }

            if (position === "left") {
                const maxAllowed = window.innerWidth - safeCenter - safeRight - GAP * 2;
                const proposedWidth = startWidth + delta;

                newWidth = Math.min(
                    maxWidth,
                    Math.max(minWidth, Math.min(proposedWidth, maxAllowed))
                );

                panel.style.width = `${newWidth}px`;
                setWidth(newWidth);
                return;
            }

            if (position === "right") {
                const maxAllowed = window.innerWidth - safeCenter - safeLeft - GAP * 2;
                const proposedWidth = startWidth - delta;

                newWidth = Math.min(
                    maxWidth,
                    Math.max(minWidth, Math.min(proposedWidth, maxAllowed))
                );

                panel.style.width = `${newWidth}px`;
                setWidth(newWidth);
                return;
            }
        };



        const onMouseUp = () => {
            window.removeEventListener("mousemove", onMouseMove);
            window.removeEventListener("mouseup", onMouseUp);
        };

        window.addEventListener("mousemove", onMouseMove);
        window.addEventListener("mouseup", onMouseUp);
    };
};

