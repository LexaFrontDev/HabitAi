import React, { useState, useEffect, useRef } from 'react';
import Sidebar from '../chunk/SideBar';
import DataChunk from "../chunk/DataSelectChunk/DataChunk";
import Loading from "../chunk/LoadingChunk/Loading";
import { Task } from "../../ui/props/Tasks/Task";
import {ListTypeDTO} from "../../ui/Dto/ArrayDto/ListType/ListTypeDTO";
import {TasksService} from "../../Services/Tasks/TasksService";

import { Messages, ErrorAlert, SuccessAlert, IsDoneAlert } from '../chunk/MessageAlertChunk';
import {TaskUpdate} from "../../ui/props/Tasks/TaskUpdate";
import {SaveTasksDto} from "../../ui/props/Tasks/SaveTasksDto";
import {TasksDateDto} from "../../ui/props/Tasks/TasksDateDto";
import {Period} from "../../ui/props/Tasks/type/periodType";
import {ImperativePanelGroupHandle, Panel, PanelGroup, PanelResizeHandle} from "react-resizable-panels";
import {Button} from "../../ui/atoms/button/Button";
import TextArea from "../../ui/atoms/TextArea/TextArea";
import {LangStorage} from "../../Services/languageStorage/LangStorage";
import {LangStorageUseCase} from "../../Services/language/LangStorageUseCase";
import {useTranslation} from "react-i18next";
import {LanguageRequestUseCase} from "../../Services/language/LanguageRequestUseCase";


import {ListTasks} from "../../ui/props/Tasks/ListTasks/ListTasks";
import {LanguageApi} from "../../Services/language/LanguageApi";
import {formatTaskDateTime} from "../../Services/Tasks/taskDateFormatter";
import {CtnServices} from "../../Services/Ctn/CtnServices";
import {IndexedDBCacheService} from "../../Services/Cache/IndexedDBCacheService";

const ctnService = new CtnServices(new IndexedDBCacheService())
const tasksService = new TasksService(ctnService);
const LangUseCase = new LanguageRequestUseCase(new LanguageApi());
const langStorage = new LangStorage();
const langUseCase = new LangStorageUseCase(langStorage);


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
    const [activePeriod, setActivePeriod] = useState<'today' | 'tomorrow' | 'nextWeek' | 'nextMonth' | 'all'>('all');
    const [showPanel, setShowPanel] = useState<boolean>(true);
    const [showAddList, setAddList] = useState<boolean>(false);
    const groupRef = useRef<ImperativePanelGroupHandle>(null);
    const [centerSize, setCenterSize] = useState<number>(0);
    const [shouldIndent, setShouldIndent] = useState<boolean>(false);
    const [activeId, setActiveId] = useState<number | null>(1);
    const [langCode, setLangCode] = useState('en');
    const { t, i18n } = useTranslation('translation');
    const [translationsLoaded, setTranslationsLoaded] = useState<boolean>(false);
    const [ListTasks, SetListTasks] = useState<ListTasks[] | []>([])
    const [ListType, SetListType] = useState<string>('list');
    const [typeTitle, setTypeTitle] = useState("");


    useEffect(() => {
        const detectLang = async () => {
            try {
                const lang = await langUseCase.getLang();
                if (lang) {
                    setLangCode(lang);
                    await LangUseCase.getTranslations(lang);
                }
            } catch (err) {
                console.error(err);
            } finally {
                setTranslationsLoaded(true);
            }
        };
        detectLang();
    }, []);


    useEffect(() => {
        const fetchListTasks = async () => {
            try {
                let result = await tasksService.getListTasks();
                if (!result) {
                    SetListTasks([]);
                    return;
                }
                SetListTasks(result);
            } catch (error) {
                console.error("Ошибка при загрузке задач:", error);
                SetListTasks([]);
            }
        };

        fetchListTasks();
    }, []);



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
                    if(!tasks) setTasks([]);
                    console.log(tasks);
                    setTasks(tasks || []);
                } else {
                    const date = getActiveDate();
                    const tasks = await tasksService.getTasksFor(date);
                    setTasks(tasks || []);
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

    const handlePeriodChange = async (period: Period,  id: number) => {
        setActiveId(id)

        if (period === 'all') {
            await getTasks(period);
        }
        setActivePeriod(period);
    };

    const getTasks = async (period: 'today' | 'tomorrow' | 'nextWeek' | 'nextMonth' | 'all') => {
        try {
            setLoading(true);
            const allTasks = await tasksService.getTasksAll();
            setTasks(allTasks || []);
            setActivePeriod(period);
        } catch (err: any) {
            setError(err.message || t('tasks.mistakeGetAllTasks'));
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


            const createdTask: Task = {
                cacheId: result.cacheId,
                id: result.task,
                title,
                description,
                todo: false,
                timeData
            };
            setTasks(prev => [...prev, createdTask]);
            setTitle('');
            setDescription('');
            setSelectedDate('');
            Messages(t('tasks.createTasksSuccss'));
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

    const handleDelete = async (taskId: number | string, cacheId: number) => {
        const result = await tasksService.deleteTask(taskId, cacheId);

        if (result.success) {
            setTasks(tasks.filter(task => task.id !== taskId));
            setTitle('');
            setDescription('');
            setSelectedDate('');
            setTasksInput(false);
            Messages(t('tasks.deleteTasksSuccss'))
        } else if (result.front) {
            ErrorAlert(result.message);
        } else {
            console.error(result.message);
        }
    };

    const handleEdit = (task: Task) => {
        console.log(task)
        setEditingTask(task);
        setShowEditModal(true);
    };


    const saveTypes = () => {
        if(typeTitle === ""){
            ErrorAlert('Заполните корректно данные');
        }



    }


    const saveEditedTask = async () => {
        if (!editingTask || !editingTask.id) return;



        const taskDto: TaskUpdate = {
            cacheId: editingTask.cacheId,
            id: editingTask.id,
            title: editingTask.title!,
            description: editingTask.description || '',
            timeData: editingTask.timeData
        };

        const result = await tasksService.updateTask(taskDto);

        if (result.success && result.task) {
            const updatedTask: Task = {
                cacheId: editingTask.cacheId,
                id: result.task,
                title: editingTask.title!,
                description: editingTask.description || '',
                timeData: editingTask.timeData,
                todo: editingTask.todo ?? false
            };

            setTasks(prevTasks =>
                prevTasks.map(task =>
                    task.id === result.task ? updatedTask : task
                )
            );

            setShowEditModal(false);
        } else if (result.front) {
            ErrorAlert(result.message);
        } else {
            console.error(result.message);
        }
    };


    const handleWontDo = async (taskId: number | string,  cacheId: number,status: boolean) => {
        if (status) {
            Messages(t('tasks.confirmTasks'));
        } else {
            Messages(t('tasks.unConfirmTasks'));
        }
        const task = tasks.find(t => t.id === taskId);
        if (!task) return;
        const newStatus = !task.todo;
        setTasks(prevTasks =>
            prevTasks.map(task =>
                task.id === taskId ? { ...task, todo: status } : task
            )
        );

        setEditingTask(prev => {
            if (!prev) return null;

            return {
                ...prev,
                todo: newStatus,
                id: prev.id,
                title: prev.title,
                description: prev.description,
                timeData: prev.timeData
            };
        });
        const result = await tasksService.toggleWontDo(taskId, cacheId, status);
        if (!result.success && result.front) {
            ErrorAlert(result.message);
        } else if(!result.success) {
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
            cacheId: updatedTask.cacheId,
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
        const lines = formatTaskDateTime(task);
        return (
            <div>
                {lines.map((line, index) => (
                    <div key={index}>{line}</div>
                ))}
            </div>
        );
    };

    if (!translationsLoaded) return <Loading />;


    return (
        <div id="tasks-page">
            <Sidebar/>

            <PanelGroup autoSaveId="example" direction="horizontal" style={{ height: "100vh" }} ref={groupRef} onLayout={handleLayoutChange}>

                <Panel maxSize={25} defaultSize={20} minSize={0}>
                    <div className="panel-content sidebar-indent">
                        <div className="content-panel">
                            <div className="lists-buttons">
                                <div className="handlers">
                                    <Button key={1} variant="listButton"  isActive={activeId === 1}  onClick={() => handlePeriodChange('all', 1)} className="all handl">{t('buttons.AllButton')}</Button>
                                    <Button key={2} variant="listButton"  isActive={activeId === 2}  onClick={() => handlePeriodChange('today', 2)} className="day handl">{t('buttons.TodayButton')}</Button>
                                    <Button key={3} variant="listButton"  isActive={activeId === 3}  onClick={() => handlePeriodChange('tomorrow', 3)} className="day handl">{t('buttons.TomorowButton')}</Button>
                                    <Button key={4} variant="listButton"  isActive={activeId === 4}  onClick={() => handlePeriodChange('nextWeek', 4)} className="day handl">{t('buttons.WeekButton')}</Button>
                                    <Button key={5} variant="listButton"  isActive={activeId === 5}  onClick={() => handlePeriodChange('nextMonth', 5)} className="day handl">{t('buttons.MonthButton')}</Button>
                                </div>
                            </div>

                            <div className="list-tasks">
                                <button className="triger-pause mt-5" onClick={() => setAddList(true)}>Добавить список</button>
                                {ListTasks.map((task) => (
                                    <button>task.label</button>
                                ))}
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
                                    <h4 className="header-title">{t('tasks.tasksHeadText')}</h4>
                                </div>
                            </div>

                            {showDataModal && (
                                <DataChunk onClose={() => setDataModal(false)} onSave={handleSave}/>
                            )}


                            <div className="add-tasks-input triger">
                                {!showTasksInput && (
                                    <div className="add-head" onClick={() => setTasksInput(true)}>
                                        {t('tasks.questionWantDoTasks')}
                                    </div>
                                )}
                                {showTasksInput && (
                                    <div className="block-add-tasks">
                                        {!showTasksInput ? (
                                            <div onClick={() => setTasksInput(true)} className="button-add-tasks">
                                                <p>{t('tasks.questionWantDoTasks')}</p>
                                            </div>
                                        ) : (
                                            <div className="input-tasks">
                                                <input
                                                    className="input-tasks-title"
                                                    type="text"
                                                    placeholder={t('tasks.wantToDo')}
                                                    value={title}
                                                    onChange={handleChange}
                                                    onKeyDown={(e) => {
                                                        if (e.key === 'Enter') {
                                                            e.preventDefault();
                                                            saveTasks();
                                                        }
                                                    }}
                                                />
                                                <div className="actions">
                                                <span className="triger" onClick={() => setDataModal(true)}>
                                                    {selectedDate ? ` ${selectedDate}` : t('buttons.ChooseTime')}
                                                </span>
                                                    <button  className="triger" onClick={() => saveTasks()}>{t('buttons.addButton')}</button>
                                                </div>
                                            </div>
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
                                        <div key={task.id} className={`task-item triger-list ${task.todo ? 'wont-do' : ''}`}
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
                                                        checked={task.todo || false}
                                                        onChange={() => handleWontDo(task.id, task.cacheId, !task.todo)}
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
                                                    <span  className="dots">...</span>
                                                    <div className="dropdown-menu">
                                                        <button  onClick={() => handleDelete(task.id, task.cacheId)}>{t('buttons.deleteButton')}</button>
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
                <Panel maxSize={35} defaultSize={25} minSize={10}>
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
                                                checked={editingTask.todo || false}
                                                onChange={() => handleWontDo(editingTask.id, editingTask.cacheId, !editingTask.todo)}
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
                                         <span className="triger" onClick={() => setShowEditDateModal(true)}>
                                            {editingTask.timeData?.time ? `${editingTask.timeData.time}` : t('buttons.ChooseTime')}
                                         </span>
                                    </div>
                                </div>
                                <div className="block-description">
                                    <div className="edit-panel">
                                        <input
                                            className="tasks-text-input"
                                            type="text"
                                            value={editingTask.title || ''}
                                            placeholder={t('tasks.wantToDo')}
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
                                            placeholder={t('buttons.Description')}
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
                    <div className="modal-content"  style={{ maxHeight: "400px" }}>
                        <div className="header-container">
                            <h5 className="header-title">Создать список</h5>
                        </div>
                        <div className="content mt-5">
                            <div className="title-list-wrapper">
                                <input
                                    id="title_list"
                                    name="title_list"
                                    type="text"
                                    placeholder=" "
                                    className="list-header"
                                    value={title}
                                    onChange={(e) => setTypeTitle(e.target.value)}
                                />
                                <label htmlFor="title_list">Название списка</label>
                            </div>
                            <div className="select-type-list">
                                <span className="list_types_label">Тип</span>
                                <div className="buttons-list-types">
                                    <svg onClick={() => SetListType('list')} fill="currentColor">
                                        <use xlinkHref="/Upload/Images/AppIcons/view_kanban.svg"/>
                                    </svg>
                                    <svg onClick={() => SetListType('kanban')} fill="currentColor">
                                        <use xlinkHref="/Upload/Images/AppIcons/list_tasks.svg"/>
                                    </svg>
                                </div>
                            </div>



                            <button onClick={() => saveTypes()}>Создать</button>
                            <button onClick={() => setAddList(false)}>Отмена</button>
                        </div>
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