import React, { useState, useEffect, useRef } from 'react';
import Sidebar from '../chunk/SideBar';
import Loading from "../chunk/LoadingChunk/Loading";
import TasksPage from "../Tasks/TasksPage";

const MatricPage = () => {
    const [header, setHeader] = useState('Матрица Эйзенхауэра');
    const [blockHeaders, setBlockHeaders] = useState([
        { type: 1, title: "Срочно и важно" },
        { type: 2, title: "Не срочно, но важно" },
        { type: 3, title: "Срочно, но не важно" },
        { type: 4, title: "Не срочно и не важно" }
    ]);
    const [message, setMessage] = useState("");
    const clickCount = useRef(0);
    const timer = useRef(null);

    const [editingBlockType, setEditingBlockType] = useState(null);



    const getClassName = (type) => {
        switch (type) {
            case 1: return "one";
            case 2: return "two";
            case 3: return "three";
            case 4: return "four";
            default: return "";
        }
    };


    const handleClick = (type) => {
        clickCount.current += 1;

        clearTimeout(timer.current);
        timer.current = setTimeout(() => {
            if (clickCount.current === 2) {
                setEditingBlockType(type);
            }
            clickCount.current = 0;
        }, 300);
    };

    const handleChange = () => {

    }

    return (
        <div id="matric">
            <Sidebar/>
            <div className="app-nav">
                <div className="nav-left-side">
                    <h3 className="header">{header}</h3>
                </div>
            </div>


            <div id="content">
                <div className="matric__block__container">
                    {blockHeaders.map((block, index) => (
                        <div key={block.type} className={`matric__block ${getClassName(block.type)}`}>
                            <div className={`matric__block__header ${getClassName(block.type)}`}>
                                {editingBlockType === block.type ? (
                                    <input type="text" className="change_header" value={block.title}
                                        onChange={(e) => {
                                            const newHeaders = [...blockHeaders];
                                            newHeaders[index].title = e.target.value;
                                            setBlockHeaders(newHeaders);
                                        }}
                                        onBlur={() => setEditingBlockType(null)}
                                        autoFocus
                                    />
                                ) : (
                                    <h4 onClick={() => handleClick(block.type)} className="header">{block.title}</h4>
                                )}

                            </div>

                            <div className="matric__block__tasks">

                            </div>

                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}

export default MatricPage;
