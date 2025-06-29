import React, { useState, useEffect } from 'react';
import Modal from 'react-modal';

Modal.setAppElement('#root');

const List = ({
                  apiUrl,
                  autoStart = true,
                  trigger,
                  displayFields,
                  editApiUrl,
                  deleteApiUrl,
                  allowEdit = false,
                  allowDelete = false
              }) => {
    const [data, setData] = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const [sortConfig, setSortConfig] = useState(null);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [editingItem, setEditingItem] = useState(null);
    const [formData, setFormData] = useState({});

    const fetchData = async () => {
        setLoading(true);
        setError(null);
        try {
            const response = await fetch(apiUrl);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const result = await response.json();
            if (result && result.result) {
                setData(result.result);
            } else {
                throw new Error("Invalid data format: expected { result: [...] }");
            }
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    const handleDelete = async (id) => {
        if (!deleteApiUrl || !allowDelete) return;

        if (!window.confirm('Are you sure you want to delete this item?')) return;

        try {
            setLoading(true);
            const response = await fetch(`${deleteApiUrl}/${id}`, {
                method: 'DELETE'
            });

            if (!response.ok) {
                throw new Error(`Delete failed with status: ${response.status}`);
            }

            // Refresh data after successful deletion
            await fetchData();
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    const handleEdit = (item) => {
        if (!allowEdit) return;

        setEditingItem(item);
        setFormData(item);
        setIsModalOpen(true);
    };

    const handleSave = async () => {
        if (!editApiUrl || !allowEdit || !editingItem) return;

        try {
            setLoading(true);
            const response = await fetch(`${editApiUrl}/${editingItem.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData),
            });

            if (!response.ok) {
                throw new Error(`Update failed with status: ${response.status}`);
            }

            // Refresh data after successful update
            await fetchData();
            setIsModalOpen(false);
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
    };

    useEffect(() => {
        if (autoStart) {
            fetchData();
        }
    }, [autoStart, apiUrl]);

    useEffect(() => {
        if (trigger) {
            fetchData();
        }
    }, [trigger]);

    const handleSort = (key) => {
        let direction = 'asc';
        if (sortConfig && sortConfig.key === key && sortConfig.direction === 'asc') {
            direction = 'desc';
        }
        setSortConfig({ key, direction });
    };

    const sortedData = React.useMemo(() => {
        if (!sortConfig) return data;

        return [...data].sort((a, b) => {
            if (a[sortConfig.key] < b[sortConfig.key]) {
                return sortConfig.direction === 'asc' ? -1 : 1;
            }
            if (a[sortConfig.key] > b[sortConfig.key]) {
                return sortConfig.direction === 'asc' ? 1 : -1;
            }
            return 0;
        });
    }, [data, sortConfig]);

    const getDisplayFields = (item) => {
        if (!displayFields) return Object.keys(item);

        return displayFields.split(' ').map(field => {
            const [name, direction] = field.split(':');
            return name;
        });
    };

    if (loading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;
    if (data.length === 0) return <div>No data available</div>;

    return (
        <div className="list-container">
            <table>
                <thead>
                <tr>
                    {getDisplayFields(data[0]).map((field) => (
                        <th key={field} onClick={() => handleSort(field)}>
                            {field}
                            {sortConfig && sortConfig.key === field && (
                                <span>{sortConfig.direction === 'asc' ? ' ↑' : ' ↓'}</span>
                            )}
                        </th>
                    ))}
                    {(allowEdit || allowDelete) && <th>Actions</th>}
                </tr>
                </thead>
                <tbody>
                {sortedData.map((item, index) => (
                    <tr key={index}>
                        {getDisplayFields(item).map((field) => (
                            <td key={field}>{item[field]}</td>
                        ))}
                        {(allowEdit || allowDelete) && (
                            <td>
                                {allowEdit && (
                                    <button onClick={() => handleEdit(item)}>Edit</button>
                                )}
                                {allowDelete && (
                                    <button onClick={() => handleDelete(item.id)}>Delete</button>
                                )}
                            </td>
                        )}
                    </tr>
                ))}
                </tbody>
            </table>

            {/* Edit Modal */}
            <Modal
                isOpen={isModalOpen}
                onRequestClose={() => setIsModalOpen(false)}
                contentLabel="Edit Item"
            >
                <h2>Edit Item</h2>
                {editingItem && (
                    <form>
                        {Object.keys(editingItem).map(key => (
                            <div key={key}>
                                <label>
                                    {key}:
                                    <input
                                        type="text"
                                        name={key}
                                        value={formData[key] || ''}
                                        onChange={handleInputChange}
                                        disabled={key === 'id'} // ID обычно нельзя редактировать
                                    />
                                </label>
                            </div>
                        ))}
                        <button type="button" onClick={handleSave}>Save</button>
                        <button type="button" onClick={() => setIsModalOpen(false)}>Cancel</button>
                    </form>
                )}
            </Modal>
        </div>
    );
};

export default List;