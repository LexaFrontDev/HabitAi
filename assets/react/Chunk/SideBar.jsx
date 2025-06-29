import React, { useEffect, useState } from 'react';

const Sidebar = () => {
    const [items, setItems] = useState([]);
    const [openGroupItems, setOpenGroupItems] = useState([]);

    useEffect(() => {
        fetch('/api/sidebar')
            .then(res => res.json())
            .then(data => {
                const combined = [];
                if (data.group['group-settings']) {
                    combined.push({ type: 'group', data: data.group });
                }
                if (Array.isArray(data.item)) {
                    combined.push(...data.item.map(i => ({ type: 'item', data: i })));
                }
                setItems(combined);
            })
            .catch(err => console.error('Ошибка загрузки меню:', err));
    }, []);

    const handleGroupClick = (groupData) => {
        if (openGroupItems === groupData['group-item']) {
            setOpenGroupItems([]);
        } else {
            setOpenGroupItems(groupData['group-item'] || []);
        }
    };

    return (
        <>
            <div id="sidebar">
                {items.map((item, index) => {
                    if (item.type === 'group') {
                        const groupData = item.data;
                        return (
                            <div key={index} className="sidebar-group">
                                <div className="sidebar-group-header" onClick={() => handleGroupClick(groupData)}>
                                    <img src={`/${groupData['group-settings'][0].icon}`} alt={groupData['group-settings'][0].label} />
                                </div>
                            </div>
                        );
                    }

                    if (item.type === 'item') {
                        const data = item.data;
                        return (
                            <a key={data.url} href={data.url} className="sidebar-item" data-label={data.label}>
                                <img src={`/${data.icon}`} alt={data.label} />
                            </a>
                        );
                    }

                    return null;
                })}
            </div>

            <div id="sidebar-group-external">
                {openGroupItems.map(subItem => (
                    <a key={subItem.url} href={subItem.url} className="sidebar-group-external-item">
                        <img src={`/${subItem.icon}`} alt={subItem.label} />
                        <span>{subItem.label}</span>
                    </a>
                ))}
            </div>
        </>
    );
};

export default Sidebar;