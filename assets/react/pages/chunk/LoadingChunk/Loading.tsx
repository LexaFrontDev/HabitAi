import React from 'react';

const Loading: React.FC = () => {
    return (
        <div className="loading">
            <div>
                <img src="/StorageImages/Animations/Load.gif" alt="Загрузка..." />
            </div>
            <h1>Подождите загрузку</h1>
        </div>
    );
};

export default Loading;
