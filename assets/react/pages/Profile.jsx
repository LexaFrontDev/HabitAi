import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Sidebar from '../Chunk/Sidebar';

const Profile = () => {
    const [showModal, setShowModal] = useState(false);
    const [data, setData] = useState(null);

    const fetchData = async () => {
        const response = await fetch('/api/example');
        const result = await response.json();
        setData(result);
    };

    return (
        <div>
            <Sidebar />
            <h1>Профиль</h1>
            <button onClick={() => setShowModal(true)}>Открыть модалку</button>
            <button onClick={fetchData}>Загрузить данные</button>

            {showModal && (
                <div className="modal">
                    <p>Это модалка</p>
                    <button onClick={() => setShowModal(false)}>Закрыть</button>
                </div>
            )}

            {data && <pre>{JSON.stringify(data, null, 2)}</pre>}

            <Link to="/">На главную</Link>
        </div>
    );
};

export default Profile;
