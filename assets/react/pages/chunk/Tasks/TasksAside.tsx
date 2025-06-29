import React, { useState, useRef, useEffect, MouseEvent } from 'react';
import {Period} from "../../../ui/props/Tasks/type/periodType";
import {FilterOption} from "../../../ui/props/Tasks/type/FilterOption";
import {SidePanelProps} from "../../../ui/props/SidePanel/SidePanelProps";






const SidePanel: React.FC<SidePanelProps> = ({
                                                 onClose,
                                                 activePeriod,
                                                 onPeriodChange,
                                                 filters = [],
                                                 activeFilter,
                                                 onFilterChange,
                                                 onAddClick,
                                                 plusButtonContent,
                                                 closeButtonSrcImg,
                                                 availableWidth = 1200,
                                                 onWidthChange,
                                                 onResize = () => {}
                                             }) => {
    const [panelWidth, setPanelWidth] = useState<number>(300);
    const [isResizing, setIsResizing] = useState<boolean>(false);
    const sidePanelRef = useRef<HTMLDivElement | null>(null);

    const startResizing = (e: MouseEvent<HTMLDivElement>) => {
        setIsResizing(true);
        e.preventDefault();
    };

    const stopResizing = () => {
        setIsResizing(false);
    };

    const resize = (e: MouseEvent | globalThis.MouseEvent) => {
        if (isResizing && sidePanelRef.current) {
            const left = sidePanelRef.current.getBoundingClientRect().left;
            const newWidth = e.clientX - left;

            const maxWidth = availableWidth - 200;
            const clampedWidth = Math.max(200, Math.min(newWidth, maxWidth));

            setPanelWidth(clampedWidth);
            onResize(clampedWidth);
            onWidthChange?.(clampedWidth);
        }
    };

    useEffect(() => {
        if (!isResizing) return;

        const handleMouseMove = (e: globalThis.MouseEvent) => resize(e);
        const handleMouseUp = () => stopResizing();

        window.addEventListener('mousemove', handleMouseMove);
        window.addEventListener('mouseup', handleMouseUp);

        return () => {
            window.removeEventListener('mousemove', handleMouseMove);
            window.removeEventListener('mouseup', handleMouseUp);
        };
    }, [isResizing]);

    useEffect(() => {
        onWidthChange?.(panelWidth);
    }, []);

    const periodLabels = ['Все', 'Сегодня', 'Завтра', 'След. неделя'];
    const periodValues = ['all', 'today', 'tomorrow', 'nextWeek'];

    return (
        <aside
            className="side-panel"
            ref={sidePanelRef}
            style={{ width: `${panelWidth}px` }}
        >
            <div className="side-panel-header">
                <h5>Фильтрация</h5>
                <button className="close-btn button-aside" onClick={onClose}>
                    <img src={closeButtonSrcImg} alt="x" />
                </button>
            </div>

            <div className="nav-center-side">
                <div className="period-selector">
                    {periodLabels.map((label, index) => {
                        const value = periodValues[index];
                        return (
                            <button
                                key={value}
                                className={activePeriod === value ? 'active' : ''}
                                onClick={() => onPeriodChange(value as Period)}
                            >
                                {label}
                            </button>
                        );
                    })}
                </div>
            </div>

            <div className="side-panel-filters-header mt-4">
                <h5>Список</h5>
                <button className="add-btn" onClick={onAddClick}>
                    {plusButtonContent || '+'}
                </button>
            </div>

            <div className="side-panel-filters mt-4 period-selector">
                {filters.map((filter, i) => (
                    <button
                        key={i}
                        className={`filter-item ${activeFilter === filter.value ? 'active' : ''}`}
                        onClick={() => onFilterChange(filter.value)}
                    >
                        {filter.label}
                    </button>
                ))}
            </div>

            <div
                className="side-panel-resize-handle"
                onMouseDown={startResizing}
                style={{
                    position: 'absolute',
                    right: 0,
                    top: 0,
                    bottom: 0,
                    width: '5px',
                    cursor: 'col-resize',
                    backgroundColor: 'transparent'
                }}
            />
        </aside>
    );
};

export default SidePanel;