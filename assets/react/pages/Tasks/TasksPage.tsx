import React, { useState, useEffect, useRef } from 'react';
import Sidebar from '../chunk/SideBar';
import DataChunk from "../chunk/DataSelectChunk/DataChunk";
import Loading from "../chunk/LoadingChunk/Loading";
import SidePanel from "../chunk/Tasks/TasksAside";
import { Task } from "../../ui/props/Tasks/Task";
import { TimeData } from "../../ui/props/Tasks/TimeData";
import {ListTypeDTO} from "../../Aplication/Dto/ArrayDto/ListType/ListTypeDTO";
import {TasksService} from "../../Aplication/UseCases/Tasks/TasksService";
import {TasksApi} from "../../Infrastructure/request/tasks/TasksApi";
import { Messages, ErrorAlert, SuccessAlert, IsDoneAlert } from '../chunk/MessageAlertChunk';
import {TaskDelete} from "../../ui/props/Tasks/TaskDelete";
import {TaskUpdate} from "../../ui/props/Tasks/TaskUpdate";
import {SaveTasksDto} from "../../ui/props/Tasks/SaveTasksDto";
import {TasksDateDto} from "../../ui/props/Tasks/TasksDateDto";
import {Period} from "../../ui/props/Tasks/type/periodType";
import ResizablePanel from "../../ui/organism/Aside/ResizablePanel";


const tasksService = new TasksService(new TasksApi());



const TasksPage: React.FC = () => {
    const [showTasksInput, setTasksInput] = useState<boolean>(false);
    const [title, setTitle] = useState<string>('');
    const [description, setDescription] = useState<string>('');
    const [showDataModal, setDataModal] = useState<boolean>(false);
    const [selectedDate, setSelectedDate] = useState<string>('');
    const [timeData, setTimeData] = useState<TasksDateDto>({
        time: '',
        repeat: '',
        duration: {
            startDate: '',
            startTime: '',
            endDate: '',
            endTime: ''
        }
    });
    const [tasks, setTasks] = useState<Task[]>([]);
    const [editingTask, setEditingTask] = useState<Task | null>(null);
    const [showEditModal, setShowEditModal] = useState<boolean>(false);
    const [loading, setLoading] = useState<boolean>(false);
    const [error, setError] = useState<string | null>(null);
    const [showEditDateModal, setShowEditDateModal] = useState<boolean>(false);
    const [activePeriod, setActivePeriod] = useState<'today' | 'tomorrow' | 'nextWeek'>('today');
    const [showPanel, setShowPanel] = useState<boolean>(true);
    const [showAddList, setAddList] = useState<boolean>(false);
    const [listType, setListType] = useState<ListTypeDTO[]>([]);
    const [containerWidth, setContainerWidth] = useState<number>(800);
    const [containerLeft, setContainerLeft] = useState<number>(260);
    const containerRef = useRef<HTMLDivElement>(null);
    const isResizing = useRef<'left' | 'right' | null>(null);
    const startX = useRef<number>(0);
    const startWidth = useRef<number>(0);
    const startLeft = useRef<number>(0);
    const [asideWidth, setAsideWidth] = useState<number>(300);

    const formatDate = (date: Date): string => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}${month}${day}`;
    };

    useEffect(() => {
        setContainerLeft(asideWidth + 20);
    }, [asideWidth]);

    const getActiveDate = (): string => {
        const today = new Date();
        switch(activePeriod) {
            case 'tomorrow':
                const tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);
                return formatDate(tomorrow);
            case 'nextWeek':
                const nextWeek = new Date(today);
                nextWeek.setDate(nextWeek.getDate() + 7);
                return formatDate(nextWeek);
            default:
                return formatDate(today);
        }
    };

    useEffect(() => {
        const fetchTasks = async () => {
            try {
                setLoading(true);
                const date = getActiveDate();
                const tasks = await tasksService.getTasksFor(date);
                setTasks(tasks);
            } catch (err: any) {
                setError(err.message);
                setTasks([]);
            } finally {
                setLoading(false);
            }
        };

        fetchTasks();
    }, [activePeriod]);

    const handlePeriodChange = (period: Period) => {
        if (period === 'all') return;
        setActivePeriod(period);
    };

    const startResize = (e: React.MouseEvent<HTMLDivElement>, side: 'left' | 'right') => {
        isResizing.current = side;
        startX.current = e.clientX;
        startWidth.current = containerWidth;
        startLeft.current = containerLeft;
        document.addEventListener('mousemove', handleResize);
        document.addEventListener('mouseup', stopResize);
    };

    const handleResize = (e: MouseEvent) => {
        if (!isResizing.current) return;

        const dx = e.clientX - startX.current;

        if (isResizing.current === 'right') {
            let newWidth = startWidth.current + dx;
            newWidth = Math.max(600, Math.min(1200, newWidth));
            setContainerWidth(newWidth);
        }

        if (isResizing.current === 'left') {
            let newWidth = startWidth.current - dx;
            newWidth = Math.max(600, Math.min(1200, newWidth));
            setContainerWidth(newWidth);
        }
    };

    const stopResize = () => {
        isResizing.current = null;
        document.removeEventListener('mousemove', handleResize);
        document.removeEventListener('mouseup', stopResize);
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setTitle(e.target.value);
    };

    const saveTasks = async () => {
        const newTask: SaveTasksDto = {
            title,
            description,
            timeData
        };

        setTasksInput(false);

        const result = await tasksService.createTask(newTask);

        if (result.success && result.task) {
            setTasks([...tasks, result.task]);
            setTitle('');
            setDescription('');
            setSelectedDate('');
            Messages('Задача успешно созданно');
        } else if (result.front) {
            ErrorAlert(result.message);
        } else {
            console.error(result.message);
        }
    };


    const handleSave = (dateData: TasksDateDto) => {
        const dateString = dateData.time ? dateData.time.toString() : '';
        setSelectedDate(dateString);
        setDataModal(false);
        setTimeData(dateData);
    };

    const handleDelete = async (taskId: number) => {
        const result = await tasksService.deleteTask(taskId);

        if (result.success) {
            setTasks(tasks.filter(task => task.id !== taskId));
            setTitle('');
            setDescription('');
            setSelectedDate('');
            setTasksInput(false);
            Messages('Задача успешно удаленно')
        } else if (result.front) {
            ErrorAlert(result.message);
        } else {
            console.error(result.message);
        }
    };


    const handleEdit = (task: Task) => {
        setEditingTask(task);
        setShowEditModal(true);
    };

    const saveEditedTask = async () => {
        if (!editingTask) return;

        const taskDto: TaskUpdate = {
            id: editingTask.id,
            title: editingTask.title!,
            description: editingTask.description || '',
            timeData
        };


        const result = await tasksService.updateTask(taskDto);

        if (result.success && result.task) {
            setTasks(tasks.map(task =>
                task.id === result.task!.id ? result.task! : task
            ));
            setShowEditModal(false);
            Messages('Задача обновлена');
        } else if (result.front) {
            ErrorAlert(result.message);
        } else {
            console.error(result.message);
        }
    };

    const handleWontDo = async (taskId: number) => {
        const task = tasks.find(t => t.id === taskId);
        if (!task) return;
        const newStatus = !task.wontDo;
        setTasks(prevTasks =>
            prevTasks.map(task =>
                task.id === taskId ? { ...task, wontDo: newStatus } : task
            )
        );
        const result = await tasksService.toggleWontDo(taskId, newStatus);
        if (result.success) {
            Messages(result.message);
        } else if (result.front) {
            ErrorAlert(result.message);
        } else {
            console.error(result.message);
        }
    };


    const handleEditDateSave = (dateData: TasksDateDto) => {
        if (!editingTask) return;
        const dateString = dateData.time ? dateData.time.toString() : '';
        setEditingTask({
            ...editingTask,
            timeData: {
                ...editingTask.timeData,
                time: dateString,
            },
        });
        setShowEditDateModal(false);
    };


    const renderTaskDateTime = (task: Task) => {
        const { duration, time, date } = task.timeData ?? {};

        if (duration?.startDate) {
            return <div className="task-time">{time}</div>;
        } else if (date) {
            return (
                <div className="task-time">
                    <div>{time}</div>
                </div>
            );
        } else if (duration) {
            return (
                <div className="task-time">
                    <div>{duration.startDate} - {duration.endDate}</div>
                    <div>{duration.startTime} - {duration.endTime}</div>
                </div>
            );
        }

        return null;
    };

    return (
        <div id="tasks-page">
            <Sidebar/>
            <div className="aside-tasks-container">

                {showPanel && (
                    <ResizablePanel position="left" minWidth={300}>
                        <div>Контент внутри панели</div>
                    </ResizablePanel>


                    // <SidePanel
                    //     onClose={() => setShowPanel(false)}
                    //     activePeriod={activePeriod}
                    //     onPeriodChange={handlePeriodChange}
                    //     filters={[
                    //         {label: 'Задачи на неделю', value: 'weekTasks'},
                    //         {label: 'Привычки', value: 'habits'},
                    //         {label: 'Все задачи', value: 'allTasks'}
                    //     ]}
                    //     activeFilter={'weekTasks'}
                    //     onFilterChange={() => console.log('Добавить')}
                    //     onAddClick={() => setAddList(true)}
                    //     closeButtonSrcImg={"/Upload/Images/AppIcons/chevron-left.svg"}
                    //     plusButtonContent={<img src="/Upload/Images/AppIcons/plus-circle.svg" alt=""/>}
                    //     onResize={(w: number) => setAsideWidth(w)}
                    //     availableWidth={asideWidth}
                    // />
                )}
            </div>
            <div className="app-nav">
                <button className="open-aside button-aside" onClick={() => setShowPanel(true)}>
                    <img src="/Upload/Images/AppIcons/chevron-right.svg" alt=""/>
                </button>
                <div className="nav-left-side">
                    <h3 className="header">Задачи</h3>
                </div>
            </div>

            <div id="content">
                <div className="resizable-container" ref={containerRef} style={{width: `${containerWidth}px`}}>
                    <div className="resize-handle left" onMouseDown={(e) => startResize(e, 'left')}/>
                    <div className="block-add-tasks" style={{height: showTasksInput ? '110px' : '50px'}}>
                        {showTasksInput === false ? (
                            <div onClick={() => setTasksInput(true)} className="button-add-tasks">
                                <p>Добавить задачу</p>
                            </div>
                        ) : (
                            <div className="input-tasks">
                                <input
                                    className="input-tasks-title"
                                    type="text"
                                    placeholder="Хотите что то сделать?"
                                    value={title}
                                    onChange={handleChange}
                                />
                                <div className="actions">
                                    <span onClick={() => setDataModal(true)}>
                                        {selectedDate ? ` ${selectedDate}` : 'Добавить дату'}
                                    </span>
                                    <button onClick={() => saveTasks()}>Добавить</button>
                                </div>
                            </div>
                        )}

                        {showDataModal && (
                            <DataChunk onClose={() => setDataModal(false)} onSave={handleSave}/>
                        )}

                        {loading && <Loading/>}
                        {error && <div className="error">Ошибка: {error}</div>}

                        <div className="tasks-list">
                            {tasks.map(task => (
                                <div key={task.id} className={`task-item ${task.wontDo ? 'wont-do' : ''}`}>
                                    <div className="task-header">
                                        <input
                                            type="checkbox"
                                            checked={task.wontDo || false}
                                            onChange={() => handleWontDo(task.id)}
                                            className="wont-do-checkbox"
                                        />
                                        <div className="task-content">
                                            <h3 className="task-title">{task.title}</h3>
                                            <p className="task-description">{task.description}</p>
                                        </div>
                                    </div>
                                    {renderTaskDateTime(task)}
                                    <div className="task-actions">
                                        <div className="dots-menu">
                                            <span className="dots">...</span>
                                            <div className="dropdown-menu">
                                                <button onClick={() => handleEdit(task)}>Редактировать</button>
                                                <button onClick={() => handleDelete(task.id)}>Удалить</button>
                                                <button onClick={() => handleWontDo(task.id)}>
                                                    {task.wontDo ? 'Вернуть' : 'Не буду делать'}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                    <div className="resize-handle right" onMouseDown={(e) => startResize(e, 'right')}/>
                </div>

                {showAddList && (
                    <div className="modal">
                        <div className="modal-content">
                            <div className="header-container mb-sm-5">
                                <h5 className="header-title">Добавить список</h5>
                                <button className="header-button" onClick={() => setAddList(false)}>
                                    <img src="/Upload/Images/AppIcons/x-circle.svg" alt=""/>
                                </button>
                            </div>
                            <input type="text" placeholder="Название списка" className="list-header"/>
                            <select className="type-list" name="Тип списка" id="type-list">
                                {listType.map((type) => (
                                    <option key={type.type_id} value={type.type_id}>
                                        {type.label}
                                    </option>
                                ))}
                            </select>
                        </div>
                    </div>
                )}

                {showEditModal && editingTask && (
                    <div className="edit-modal">
                        <div className="modal-content">
                            <h3>Редактирование задачи</h3>
                            <input
                                type="text"
                                value={editingTask.title || ''}
                                onChange={(e) => setEditingTask({...editingTask, title: e.target.value})}
                            />
                            <textarea
                                value={editingTask.description || ''}
                                onChange={(e) => setEditingTask({...editingTask, description: e.target.value})}
                            />
                            <div className="date-section">
                                <span onClick={() => setShowEditDateModal(true)}>
                                    {editingTask.timeData.time ? `Дата: ${editingTask.timeData.time}` : 'Добавить дату'}
                                </span>
                            </div>
                            <div className="modal-actions">
                                <button onClick={() => setShowEditModal(false)}>Отмена</button>
                                <button onClick={saveEditedTask}>Сохранить</button>
                            </div>
                        </div>
                    </div>
                )}



                {showEditDateModal && editingTask && (
                    <DataChunk
                        onClose={() => setShowEditDateModal(false)}
                        onSave={handleEditDateSave}
                        initialDate={editingTask.timeData.time ?? undefined}
                    />
                )}
            </div>

            <ResizablePanel position="right" minWidth={300}>
                <div>Контент sdasdasdas панели</div>
            </ResizablePanel>
        </div>
    );
};

export default TasksPage;