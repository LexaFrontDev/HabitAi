import React, { useState } from "react";
import {
    Panel,
    PanelGroup,
    PanelResizeHandle,
} from "react-resizable-panels";

const Static: React.FC = () => {
    const [isLeftVisible, setIsLeftVisible] = useState(true);

    return (
        <PanelGroup autoSaveId="example" direction="horizontal" style={{ height: "100vh" }}>
            <Panel defaultSize={25} minSize={10}>
                <div className="panel-content">
                    <h1>Левая</h1>
                </div>
            </Panel>
            <PanelResizeHandle />
            <Panel defaultSize={50} minSize={20}>
                <div className="panel-content">
                    <h1>Центр</h1>
                </div>
            </Panel>
            <PanelResizeHandle />
            <Panel defaultSize={25} minSize={10}>
                <div className="panel-content">
                    <h1>Правая</h1>
                </div>
            </Panel>
        </PanelGroup>


    );
};

export default Static;
