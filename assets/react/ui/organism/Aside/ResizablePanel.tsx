import React, {useEffect, useRef, useState} from "react";
import { ResizablePanelProps } from "../../props/ResizablePanel/ResizablePanelProps";
import { createResizeHandler } from "../../../components/Resize/ResizeLogic";

export default function ResizablePanel({
                                           position,
                                           minWidth = 200,
                                           maxWidth = 800,
                                           children,
                                           onClose = false,
                                           widths: externalWidth,
                                           onResize,
                                           leftWidth = undefined,
                                           rightWidth = undefined,
                                           centerWidth = undefined
                                       }: ResizablePanelProps) {
    const panelRef = useRef<HTMLElement>(null);
    const [width, setWidth] = useState(300);
    const [visible, setVisible] = useState(true);

    if (!visible) return null;

    const startResize = createResizeHandler({
        position,
        minWidth,
        maxWidth,
        width,
        setWidth: (newWidth: number) => {
            setWidth(newWidth);
            if (onResize) onResize(newWidth);
        },
        leftWidth,
        rightWidth,
        centerWidth,
    });


    useEffect(() => {
        if (typeof externalWidth === 'number') {
            setWidth(externalWidth);
        }
    }, [externalWidth]);
    return (
        <aside
            ref={panelRef}
            className={`resizable-panel side-panel ${position}`}
            style={{ width: `${width}px` }}
        >
            <div className="resizable-panel-background" />

            {onClose && (
                <button
                    onClick={() => setVisible(false)}
                    className="resizable-panel-close-button"
                >
                    ✕
                </button>
            )}

            <div className="resizable-panel-content">{children}</div>

            {position === "center" ? (
                <>
                    <div onMouseDown={startResize} className="resize-handle left-resize left" />
                    <div onMouseDown={startResize} className="resize-handle right-resize right" />
                </>
            ) : (
                <div
                    onMouseDown={startResize}
                    className={`resize-handle ${position === "left" ? "right" : "left"}`}
                />
            )}
        </aside>
    );
}
