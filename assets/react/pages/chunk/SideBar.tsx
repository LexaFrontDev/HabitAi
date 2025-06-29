import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import {SidebarItemData} from '../../ui/props/sidebar/SidebarItemData';
import {GroupSettings} from '../../ui/props/sidebar/GroupSettings';
import {GroupItem} from '../../ui/props/sidebar/GroupItem';
import {SidebarGroupData} from '../../ui/props/sidebar/SidebarGroupData';

type SidebarData =
    | { type: 'group'; data: SidebarGroupData }
    | { type: 'item'; data: SidebarItemData };

const Sidebar: React.FC = () => {
    const [items, setItems] = useState<SidebarData[]>([]);
    const [openGroupItems, setOpenGroupItems] = useState<GroupItem[]>([]);

    useEffect(() => {
        fetch('/api/sidebar')
            .then(res => res.json())
            .then((data: { group: SidebarGroupData; item: SidebarItemData[] }) => {
                const combined: SidebarData[] = [];
                if (data.group['group-settings']) {
                    combined.push({ type: 'group', data: data.group } as const);
                }
                if (Array.isArray(data.item)) {
                    combined.push(
                        ...data.item.map(i => ({ type: 'item', data: i } as const))
                    );
                }
                setItems(combined);
            })
            .catch(err => console.error('Ошибка загрузки меню:', err));
    }, []);

    const handleGroupClick = (groupData: SidebarGroupData) => {
        if (
            JSON.stringify(openGroupItems) === JSON.stringify(groupData['group-item'])
        ) {
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
                                <div
                                    className="sidebar-group-header"
                                    onClick={() => handleGroupClick(groupData)}
                                >
                                    <img
                                        src={`/${groupData['group-settings'][0].icon}`}
                                        alt={groupData['group-settings'][0].label}
                                    />
                                </div>
                            </div>
                        );
                    }

                    if (item.type === 'item') {
                        const data = item.data;
                        return (
                            <Link
                                key={data.url}
                                to={data.url}
                                className="sidebar-item"
                                data-label={data.label}
                            >
                                <img src={`/${data.icon}`} alt={data.label} />
                            </Link>
                        );
                    }

                    return null;
                })}
            </div>

            <div
                id="sidebar-group-external"
                className={openGroupItems.length === 0 ? 'hidden' : ''}
            >
                {openGroupItems.map(subItem => (
                    <Link
                        key={subItem.url}
                        to={subItem.url}
                        className="sidebar-group-external-item"
                    >
                        <img src={`/${subItem.icon}`} alt={subItem.label} />
                        <span>{subItem.label}</span>
                    </Link>
                ))}
            </div>
        </>
    );
};

export default Sidebar;