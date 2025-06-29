import React, { useEffect, useState } from 'react';
import FeatureCard from "../../molecule/Cards/FeatureCard";
import Loading from '../../../pages/chunk/LoadingChunk/Loading';




interface Feature {
    icon: string;
    title: string;
    desc: string;
    url: string;
}

const FeatureList = () => {
    const [features, setFeatures] = useState<Feature[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetch('/api/landing/data')
            .then(res => res.json())
            .then(data => {
                setFeatures(data.features || []);
                setLoading(false);
            })
            .catch(err => {
                console.error('Ошибка при загрузке:', err);
                setLoading(false);
            });
    }, []);

    if (loading) return <Loading />;

    return (
        <div className="row g-4 justify-content-center">
            {features.map((feature, index) => (
                <div key={index} className="col-sm-6 col-md-4 col-lg-3">
                    <FeatureCard {...feature} />
                </div>
            ))}
        </div>
    );
};

export default FeatureList;
