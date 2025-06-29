import React from 'react';
import { useTranslation } from 'react-i18next';

interface TariffCardProps {
    name: string;
    desc: string;
    price: string;
    features: string[];
    highlight?: boolean;
    className?: string;
}

const TariffCard: React.FC<TariffCardProps> = ({
                                                   name,
                                                   desc,
                                                   price,
                                                   features,
                                                   highlight = false,
                                                   className = '',
                                               }) => {
    const { t } = useTranslation();

    return (
        <div className={`card h-100 ${highlight ? 'tariff-highlight' : 'tariff-default'} ${className}`}>
            <div className="card-body d-flex flex-column">
                <h5 className="card-title fw-bold">{name}</h5>
                <p className="card-text">{desc}</p>
                <h4 className="my-3">{price}</h4>
                <ul className="list-unstyled flex-grow-1">
                    {features.map((f, i) => (
                        <li key={i}>✓ {f}</li>
                    ))}
                </ul>
                <button className={`btn mt-3 ${highlight ? 'btn-primary' : 'btn-outline-light'}`}>
                    {t('selectPlanText') || 'Выбрать'}
                </button>
            </div>
        </div>
    );
};

export default TariffCard;
