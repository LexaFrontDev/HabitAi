import React, { useEffect, useState } from 'react';
import Sidebar from "../PomodorChunk/PomodorAsideChunk";

const Loading = () => {

    return (
        <div className="loading">
            <img src="/Upload/Images/Load/loader.svg" alt="Загрузка.."></img>
            <h1>Подождите загрузку</h1>
        </div>
    );

};
export default Loading;
