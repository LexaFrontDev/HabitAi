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
import {ImperativePanelGroupHandle, Panel, PanelGroup, PanelResizeHandle} from "react-resizable-panels";
import {Button} from "../../ui/atoms/button/Button";
import TextArea from "../../ui/atoms/TextArea/TextArea";

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
    const [infoTasks, setInfoTasks] = useState<Task | null>(null);
    const [showEditModal, setShowEditModal] = useState<boolean>(false);
    const [showInfoTasks, setShowInfoTasks] = useState<boolean>(false);
    const [loading, setLoading] = useState<boolean>(false);
    const [error, setError] = useState<string | null>(null);
    const [showEditDateModal, setShowEditDateModal] = useState<boolean>(false);
    const [activePeriod, setActivePeriod] = useState<'today' | 'tomorrow' | 'nextWeek' | 'nextMonth' | 'all'>('all');
    const [showPanel, setShowPanel] = useState<boolean>(true);
    const [showAddList, setAddList] = useState<boolean>(false);
    const [listType, setListType] = useState<ListTypeDTO[]>([]);
    const groupRef = useRef<ImperativePanelGroupHandle>(null);
    const [centerSize, setCenterSize] = useState<number>(0);
    const [shouldIndent, setShouldIndent] = useState<boolean>(false);
    const [activeId, setActiveId] = useState<number | null>(1);

    const formatDate = (date: Date): string => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}${month}${day}`;
    };

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
            case 'nextMonth':
                const nextMonth = new Date(today);
                nextMonth.setDate(nextMonth.getDate() + 30);
                return formatDate(nextMonth);
            default:
                return formatDate(today);
        }
    };

    useEffect(() => {
        const fetchTasks = async () => {
            try {
                setLoading(true);
                if (activePeriod === 'all') {
                    const tasks = await tasksService.getTasksAll();
                    console.log(tasks);
                    setTasks(tasks);
                } else {
                    const date = getActiveDate();
                    const tasks = await tasksService.getTasksFor(date);
                    console.log(tasks);
                    setTasks(tasks);
                }
            } catch (err: any) {
                setError(err.message);
                setTasks([]);
            } finally {
                setLoading(false);
            }
        };

        fetchTasks();
    }, [activePeriod]);

    const handlePeriodChange = async (period: Period) => {
        if (period === 'all') {
            await getTasks(period);
        }
        setActivePeriod(period);
    };

    const getTasks = async (period: 'today' | 'tomorrow' | 'nextWeek' | 'nextMonth' | 'all') => {
        try {
            setLoading(true);
            const allTasks = await tasksService.getTasksAll();
            setTasks(allTasks);
            setActivePeriod(period);
        } catch (err: any) {
            setError(err.message || 'Ошибка получения всех задач');
            setTasks([]);
        } finally {
            setLoading(false);
        }
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
            timeData: editingTask.timeData
        };
        const result = await tasksService.updateTask(taskDto);

        if (result.success && result.task) {
            setTasks(tasks.map(task =>
                task.id === result.task!.id ? result.task! : task
            ));
            setShowEditModal(false);
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

    const handleEditDateSave = async (dateData: TasksDateDto) => {
        if (!editingTask) return;

        const updatedTask: Task = {
            ...editingTask,
            timeData: dateData
        };

        setEditingTask(updatedTask);
        setTasks(prev =>
            prev.map(task => task.id === updatedTask.id ? updatedTask : task)
        );

        setShowEditDateModal(false);


        await tasksService.updateTask({
            id: updatedTask.id,
            title: updatedTask.title!,
            description: updatedTask.description || '',
            timeData: updatedTask.timeData
        });
    };



    const handleLayoutChange = (sizes: number[]) => {
        setCenterSize(sizes[1]);
    };

    useEffect(() => {
        if (centerSize === 75) {
            setShouldIndent(true);
        } else {
            setShouldIndent(false);
        }
    }, [centerSize]);

    const closeLeftPanel = () => {
        groupRef.current?.setLayout([0, 75, 25]);
        setShowPanel(false)
    };

    const openLeftPanel = () => {
        groupRef.current?.setLayout([20, 55, 25]);
        setShowPanel(true)
    };

    const renderTaskDateTime = (task: Task) => {
        const { duration, time, date } = task.timeData ?? {};

        if (duration) {
            const {
                startDate,
                endDate,
                startTime,
                endTime
            } = duration;

            const hasFullDateRange = startDate && endDate;
            const hasFullTimeRange = startTime && endTime;

            if (hasFullDateRange || hasFullTimeRange) {
                return (
                    <div>
                        {hasFullDateRange && (
                            <div>{startDate} - {endDate}</div>
                        )}
                        {hasFullTimeRange && (
                            <div>{startTime} - {endTime}</div>
                        )}
                    </div>
                );
            }
        }

        if (date) {
            return (
                <div>
                    <div>{date}</div>
                    {time && <div>{time}</div>}
                </div>
            );
        }

        if (time) {
            return <div>{time}</div>;
        }

        return <div>Время не указано</div>;
    };


    return (
        <div id="tasks-page">
            <Sidebar/>

            <PanelGroup autoSaveId="example" direction="horizontal" style={{ height: "100vh" }} ref={groupRef} onLayout={handleLayoutChange}>

                <Panel maxSize={25} defaultSize={20} minSize={0}>
                    <div className="panel-content sidebar-indent">
                        <div className="content-panel">
                            <div className="lists-buttons">
                                <div className="handlers">
                                    <Button key={1} variant="listButton"  isActive={activeId === 1} onToggle={() => setActiveId(1)} onClick={() => handlePeriodChange('all')} className="all handl">Все</Button>
                                    <Button key={2} variant="listButton"  isActive={activeId === 2} onToggle={() => setActiveId(2)} onClick={() => handlePeriodChange('today')} className="day handl">Сегодня</Button>
                                    <Button key={3} variant="listButton"  isActive={activeId === 3} onToggle={() => setActiveId(3)} onClick={() => handlePeriodChange('tomorrow')} className="day handl">Завтра</Button>
                                    <Button key={4} variant="listButton"  isActive={activeId === 4} onToggle={() => setActiveId(4)} onClick={() => handlePeriodChange('nextWeek')} className="day handl">Неделя</Button>
                                    <Button key={5} variant="listButton"  isActive={activeId === 5} onToggle={() => setActiveId(5)} onClick={() => handlePeriodChange('nextMonth')} className="day handl">Месяц</Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </Panel>

                <PanelResizeHandle/>
                <Panel maxSize={75} defaultSize={50} minSize={20}>
                    <div className={`panel-content ${(!showPanel || shouldIndent) ? 'sidebar-indent' : ''}`}>
                        <div className="line-resize"></div>

                        <div className="content-panel">
                            <div className="header-panel-center">
                                <div className="close-block">
                                    {showPanel ? (
                                        <Button variant={"imgButton"} onClick={closeLeftPanel}>
                                            <img src="/Upload/Images/AppIcons/chevron-left.svg" alt="Закрыть"/>
                                        </Button>
                                    ) : (
                                        <Button variant={"imgButton"} onClick={openLeftPanel}>
                                            <img src="/Upload/Images/AppIcons/chevron-right.svg" alt="Открыть"/>
                                        </Button>
                                    )}
                                </div>

                                <div className="header-text mt-xl-1">
                                    <h4 className="header-title">Задачи</h4>
                                </div>
                            </div>


                            <div className="add-tasks-input">
                                {!showTasksInput && (
                                    <div className="add-head" onClick={() => setTasksInput(true)}>
                                        + Хотите добавить задачу
                                    </div>
                                )}
                                {showTasksInput && (
                                    <div className="block-add-tasks">
                                        {!showTasksInput ? (
                                            <div onClick={() => setTasksInput(true)} className="button-add-tasks">
                                                <p>+ Хотите добавить задачу</p>
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
                                    </div>

                                )}
                            </div>
                            <div className="tasks-list">
                                {loading ? (
                                    <Loading/>
                                ) : (
                                    tasks.map(task => (
                                        <div key={task.id} className={`task-item ${task.wontDo ? 'wont-do' : ''}`}
                                             onClick={(e) => {
                                                 e.stopPropagation();
                                                 handleEdit(task);
                                             }}>
                                            <div className="task-header">
                                                <div className="checkbox-wrapper-15">
                                                    <input
                                                        className="inp-cbx"
                                                        id={`cbx-${task.id}`}
                                                        type="checkbox"
                                                        style={{display: "none"}}
                                                        checked={task.wontDo || false}
                                                        onChange={() => handleWontDo(task.id)}
                                                    />
                                                    <label className="cbx" htmlFor={`cbx-${task.id}`}>
                                                      <span>
                                                        <svg width="12px" height="9px" viewBox="0 0 12 9">
                                                          <polyline points="1 5 4 8 11 1"></polyline>
                                                        </svg>
                                                      </span>
                                                    </label>
                                                </div>
                                                <div className="task-content">
                                                    <h3 className="task-title">{task.title}</h3>
                                                    <p className="task-description">{task.description}</p>
                                                </div>
                                            </div>

                                            <div className="task-actions">
                                                {renderTaskDateTime(task)}
                                                <div className="dots-menu">
                                                    <span className="dots">...</span>
                                                    <div className="dropdown-menu">
                                                        <button onClick={() => handleDelete(task.id)}>Удалить
                                                        </button>
                                                        <button onClick={() => handleWontDo(task.id)}>
                                                            {task.wontDo ? 'Вернуть' : 'Не буду делать'}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    )))}
                            </div>
                        </div>
                    </div>
                </Panel>
                <PanelResizeHandle/>
                <Panel defaultSize={25} minSize={10}>
                    <div className="panel-content">
                        <div className="line-resize"></div>
                        {editingTask && (
                            <div className="content-panel">
                                <div className="header-panel-center">
                                    <div className="close-block">
                                        <div className="checkbox-wrapper-15">
                                            <input
                                                className="inp-cbx"
                                                id={`cbx-${editingTask.id}`}
                                                type="checkbox"
                                                style={{display: "none"}}
                                                checked={editingTask.wontDo || false}
                                                onChange={() => handleWontDo(editingTask.id)}
                                            />
                                            <label className="cbx" htmlFor={`cbx-${editingTask.id}`}>
                                                      <span>
                                                        <svg width="12px" height="9px" viewBox="0 0 12 9">
                                                          <polyline points="1 5 4 8 11 1"></polyline>
                                                        </svg>
                                                      </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div className="header-text mt-xl-1">
                                         <span onClick={() => setShowEditDateModal(true)}>
                                            {editingTask.timeData?.time ? `Дата: ${editingTask.timeData.time}` : 'Добавить дату'}
                                        </span>
                                    </div>
                                </div>
                                <div className="block-description">
                                    <div className="edit-panel">
                                        <input
                                            className="tasks-text-input"
                                            type="text"
                                            value={editingTask.title || ''}
                                            placeholder="Название"
                                            onChange={(e) => {
                                                const updatedTask = {...editingTask, title: e.target.value};
                                                setEditingTask(updatedTask);
                                                setTasks(prev =>
                                                    prev.map(task => task.id === updatedTask.id ? updatedTask : task)
                                                );
                                            }}
                                            onBlur={saveEditedTask}
                                        />

                                        <TextArea
                                            variant="default"
                                            className="tasks-text-input"
                                            value={editingTask.description || ''}
                                            placeholder="Описание"
                                            onChange={(e) => {
                                                const updatedTask = { ...editingTask, description: e.target.value };
                                                setEditingTask(updatedTask);
                                                setTasks(prev =>
                                                    prev.map(task => task.id === updatedTask.id ? updatedTask : task)
                                                );
                                            }}
                                            onBlur={saveEditedTask}
                                        />

                                    </div>
                                </div>
                            </div>
                        )}
                    </div>
                </Panel>
            </PanelGroup>

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

            {showEditDateModal && editingTask && (
                <DataChunk
                    onClose={() => setShowEditDateModal(false)}
                    onSave={handleEditDateSave}
                    initialDate={editingTask.timeData?.time ?? undefined}
                />
            )}
        </div>
    );
};

export default TasksPage;