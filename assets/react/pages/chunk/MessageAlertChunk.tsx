import React, { useEffect } from 'react';
import ReactDOM from 'react-dom/client';
import {AlertWrapperProps} from '../../ui/props/Alert/AlertWrapperProps';


const playSound = (src?: string): void => {
    if (!src) return;
    const audio = new Audio(src);
    audio.play().catch(() => {});
};

const AlertWrapper: React.FC<AlertWrapperProps> = ({ className, title, desc, sound }) => {
    useEffect(() => {
        playSound(sound);
        const timer = setTimeout(() => {
            const div = document.getElementById('alert-root');
            div?.remove();
        }, 3000);

        return () => clearTimeout(timer);
    }, [sound]);

    return (
        <div className={className}>
            <div className="alert__content">
                <h1 className="alert__title">{title}</h1>
                {desc && <p className="alert__desc">{desc}</p>}
            </div>
            <button
                className="alert__close"
                onClick={() => {
                    const node = document.getElementById('alert-root');
                    node?.remove();
                }}
            >
                ×
            </button>
        </div>
    );
};

const showAlert = (component: React.ReactElement): void => {
    const div = document.createElement('div');
    div.id = 'alert-root';
    document.body.appendChild(div);
    const root = ReactDOM.createRoot(div);
    root.render(component);
};


export const Messages = (message: string, desc?: string): void => {
    showAlert(<AlertWrapper className="message__alert" title={message} desc={desc} />);
};

export const ErrorAlert = (message: string): void => {
    showAlert(<AlertWrapper className="error__message__alert" title={message} />);
};

export const SuccessAlert = (message: string): void => {
    showAlert(
        <AlertWrapper
            className="success__message__alert"
            title={message}
            sound="/sounds/success.mp3"
        />
    );
};

export const IsDoneAlert = (message: string): void => {
    showAlert(
        <AlertWrapper
            className="isdone__message__alert"
            title="Задача выполнена!"
            desc={message}
            sound="/sounds/done.mp3"
        />
    );
};