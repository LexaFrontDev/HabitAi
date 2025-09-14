import React from 'react';
import ReactDOM from 'react-dom/client';
import './Services/Library/i18n';
import RouterDom from './Router';
import { I18nextProvider } from 'react-i18next';
import i18n from './Services/Library/i18n';
import 'react-toastify/dist/ReactToastify.css';


// @ts-ignore
const root = ReactDOM.createRoot(document.getElementById('react-root'));

root.render(
    <React.StrictMode>
        <I18nextProvider i18n={i18n}>
            <RouterDom />
        </I18nextProvider>
    </React.StrictMode>
);
