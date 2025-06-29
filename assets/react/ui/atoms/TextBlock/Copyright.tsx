import React from 'react';
import { useTranslation } from 'react-i18next';

const Copyright: React.FC = () => {
    const { t } = useTranslation();

    return (
        <small className="text-muted">
            © {new Date().getFullYear()} TaskFlow —{' '}
            <a href="/privacy" className="text-decoration-none text-primary">
                {t('privacyPolicyText')}
            </a>
        </small>
    );
};

export default Copyright;
