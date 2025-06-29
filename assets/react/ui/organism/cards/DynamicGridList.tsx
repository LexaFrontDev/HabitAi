import React, { useEffect, useState } from 'react';
import Loading from '../../../pages/chunk/LoadingChunk/Loading';

type LayoutType = 'full-width' | '3-cols' | '4-cols' | 'list';

interface DynamicGridListProps<T> {
    url?: string;
    items?: T[];
    extract?: (data: any) => T[];
    renderItem: (item: T, index: number) => React.ReactNode;
    layout?: LayoutType;
    className?: string;
}

const layoutClassMap: Record<LayoutType, string> = {
    'full-width': 'col-12',
    '3-cols': 'col-sm-6 col-md-4',
    '4-cols': 'col-sm-6 col-md-4 col-lg-3',
    'list': 'col-12',
};

const DynamicGridList = <T,>({
                                 url,
                                 items,
                                 extract,
                                 renderItem,
                                 layout = '4-cols',
                                 className,
                             }: DynamicGridListProps<T>) => {
    const [data, setData] = useState<T[]>([]);
    const [loading, setLoading] = useState<boolean>(!!url); // если передали URL — будет загрузка

    useEffect(() => {
        if (!url) {
            setData(items || []);
            return;
        }

        fetch(url)
            .then((res) => res.json())
            .then((resData) => {
                const result = extract?.(resData) || [];
                setData(result);
                setLoading(false);
            })
            .catch((err) => {
                console.error('Ошибка загрузки:', err);
                setLoading(false);
            });
    }, [url, items]);

    if (loading) return <Loading />;
    if (!data || data.length === 0) return <div>Нет данных</div>;

    const colClass = layoutClassMap[layout];

    return (
        <div className={`row g-4 justify-content-center ${className || ''}`}>
            {data.map((item, index) => (
                <div key={index} className={colClass}>
                    {renderItem(item, index)}
                </div>
            ))}
        </div>
    );
};

export default DynamicGridList;
