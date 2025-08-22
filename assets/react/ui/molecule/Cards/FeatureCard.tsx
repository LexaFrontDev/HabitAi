import React from 'react';
import {FeatureCardProps} from "../../props/CardProps/Feature/FeatureCardProps";



const FeatureCard: React.FC<FeatureCardProps> = ({ icon, title, desc, url }) => (
    <a href={url} className="text-decoration-none text-reset">
        <div className="card text-center p-4 h-100">
            <img src={`/StorageImages/${icon}`} alt={title} height="64" className="mb-3" />
            <h5 className="fw-semibold">{title}</h5>
            <p className="text-muted small">{desc}</p>
        </div>
    </a>
);

export default FeatureCard;
