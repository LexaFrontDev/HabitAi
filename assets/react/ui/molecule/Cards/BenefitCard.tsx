import React from 'react';

interface BenefitCardProps {
    icon?: string;
    title: string;
    desc: string;
    className?: string;
}

const BenefitCard: React.FC<BenefitCardProps> = ({
                                                     icon,
                                                     title,
                                                     desc,
                                                     className = '',
                                                 }) => {
    return (
        <div className={`benefit-card h-100 text-center px-3 py-4 ${className}`}>
            {icon && (
                <img src={icon} alt={title} className="benefit-icon mb-3" height={64} />
            )}
            <h5 className="benefit-title fw-semibold mb-2">{title}</h5>
            <p className="benefit-desc text-muted small">{desc}</p>
        </div>
    );
};

export default BenefitCard;
