(function() {
    const btn = document.getElementById('addHabitButton');
    if (!btn) return;
    const habitTemplates = [
        {
            title: "Ранний подъем",
            quote: "Кто рано встает, тому Бог дает!",
            icon: "⏰"
        },
        {
            title: "Утренняя пробежка",
            quote: "Бег - это жизнь!",
            icon: "🏃"
        },
        {
            title: "Чтение книги",
            quote: "Книги - корабли мысли",
            icon: "📚"
        },
        {
            title: "Медитация",
            quote: "Тишина - великий учитель",
            icon: "🧘"
        }
    ];

    let step = 1;
    const data = {
        titleHabit: '',
        iconBase64: '',
        quote: '',
        goalInDays: '30',
        datesType: 'daily',
        date: {
            mon: false,
            tue: false,
            wed: false,
            thu: false,
            fri: false,
            sat: false,
            sun: false
        },
        beginDate: Math.floor(Date.now() / 1000),
        notificationDate: '08:30',
        purposeType: 'count',
        purposeCount: 1,
        checkManually: false,
        checkAuto: false,
        checkClose: false,
        autoCount: 0 // New field for automatic count
    };

    const container = document.getElementById('habit-modal');
    let modal;

    const templates = {
        1: (data) => `
            <div class="modal-content">
                <div class="header">
                    <h4>Выберите привычку</h4>
                </div>
                
                <div class="habit-templates">
                    ${habitTemplates.map(habit => `
                        <div class="habit-template" data-title="${habit.title}" data-quote="${habit.quote}" data-icon="${habit.icon}">
                            <img src="${habit.icon}" alt="">
                            <h5>${habit.title}</h5>
                            <p>${habit.quote}</p>
                        </div>
                    `).join('')}
                </div>
                
                <div class="actions">
                    <button id="cancel">Отмена</button>
                </div>
            </div>`,
        2: (data) => `
            <div class="modal-content">
                <div class="header">
                    <h4>Название и мотивация</h4>
                </div>
                <input id="habit-title" placeholder="Название привычки" value="${data.titleHabit}" />
                 <div class="icon-block">
                    <div class="icon-preview">${data.iconBase64}</div>
                    <input id="habit-icon" type="file" accept="image/*" />
                </div>
                <input id="habit-quote" placeholder="Цитата для мотивации" value="${data.quote}" />
                
                <div class="actions">
                    <button id="prev">Назад</button>
                    <button id="next">Далее</button>
                </div>
            </div>`,
        3: (data) => `
            <div class="modal-content">
                <h2>Настройка расписания</h2>
                
                <div class="chose-data-block">
                    <span class="option daily ${data.datesType === 'daily' ? 'active' : ''}">Ежедневно</span>
                    <span class="option weekly ${data.datesType === 'weekly' ? 'active' : ''}">Еженедельно</span>
                    <span class="option repeat ${data.datesType === 'repeat' ? 'active' : ''}">Повтор</span>
                    <div class="date-type">
                        ${renderFrequencyOptions(data.datesType, data)}
                    </div>
                </div>
                
                <div class="settings-block">
                    <div class="setting-item">
                        <h3 class="setting-toggle">Цель:</h3>
                        <div class="setting-content inactive">
                            <div class="goal-options">
                                <label class="goal-option">
                                    <input type="radio" name="goal-type" value="complete" ${data.checkClose ? 'checked' : ''}>
                                    Достигните всего этого
                                </label>
                                <label class="goal-option">
                                    <input type="radio" name="goal-type" value="specific" ${!data.checkClose ? 'checked' : ''}>
                                    Достигните определенной суммы
                                </label>
                            </div>
                            
                            <div class="specific-goal ${!data.checkClose ? 'active' : ''}">
                                <div class="goal-input-row">
                                    <span>Ежедневно:</span>
                                    <input type="number" id="daily-goal" value="${data.purposeCount}" placeholder="Количество">
                                    <select id="goal-unit">
                                        <option value="count" ${data.purposeType === 'count' ? 'selected' : ''}>раз</option>
                                        <option value="km" ${data.purposeType === 'km' ? 'selected' : ''}>км</option>
                                        <option value="minutes" ${data.purposeType === 'minutes' ? 'selected' : ''}>минут</option>
                                        <option value="laps" ${data.purposeType === 'laps' ? 'selected' : ''}>кругов</option>
                                    </select>
                                </div>
                                
                                <div class="verification-method">
                                    <span>При проверке:</span>
                                    <select id="verification-type">
                                        <option value="auto" ${data.checkAuto ? 'selected' : ''}>Автоматический</option>
                                        <option value="manual" ${data.checkManually ? 'selected' : ''}>Вручную</option>
                                        <option value="complete" ${data.checkClose ? 'selected' : ''}>Завершить</option>
                                    </select>
                                </div>
                                
                                <div id="auto-count-container" style="${data.checkAuto ? '' : 'display: none;'} margin-top: 10px;">
                                    <span>Автоматическое количество:</span>
                                    <input type="number" id="auto-count" value="${data.autoCount}" placeholder="Введите количество" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="setting-item">
                        <h3 class="setting-toggle">Дата начала:</h3>
                        <div class="setting-content inactive">
                            <input type="date" id="start-date" value="${new Date(data.beginDate * 1000).toISOString().split('T')[0]}" />
                        </div>
                    </div>
                    
                    <div class="setting-item">
                        <h3 class="setting-toggle">Цель в днях:</h3>
                        <div class="setting-content inactive">
                            <input id="duration" type="number" value="${data.goalInDays}" placeholder="Кол-во дней" />
                        </div>
                    </div>
                    
                    <div class="setting-item">
                        <h3 class="setting-toggle">Напоминание:</h3>
                        <div class="setting-content inactive">
                            <input id="reminder" type="time" value="${data.notificationDate}" />
                        </div>
                    </div>
                </div>
                
                <div class="actions">
                    <button id="prev">Назад</button>
                    <button id="save">Сохранить</button>
                </div>
            </div>`
    };

    function renderFrequencyOptions(type, data) {
        if (type === 'daily') {
            const daysList = ['Пн','Вт','Ср','Чт','Пт','Сб','Вс'];
            const daysMap = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
            return `
                <div class="date-block">
                    <div>Выберите дни недели:</div>
                    <div class="day-grid">
                        ${daysList.map((d, i) =>
                `<div class="day-item ${data.date[daysMap[i]] ? 'active-day' : ''}" data-day="${daysMap[i]}">${d}</div>`
            ).join('')}
                    </div>
                </div>`;
        } else if (type === 'weekly') {
            return `
                <div class="date-block">
                    <div>Сколько дней в неделю?</div>
                    <select id="weekly-count">
                        ${[...Array(7).keys()].map(i =>
                `<option value="${i+1}" ${data.date.count === i+1 ? 'selected' : ''}>${i+1}</option>`
            ).join('')}
                    </select>
                </div>`;
        } else {
            return `
                <div class="date-block">
                    <div>Выберите дату месяца:</div>
                    <select id="repeat-day">
                        ${[...Array(31).keys()].map(i =>
                `<option value="${i+1}" ${data.date.day === i+1 ? 'selected' : ''}>${i+1}</option>`
            ).join('')}
                    </select>
                </div>`;
        }
    }

    const handlers = {
        1() {
            modal.querySelectorAll('.habit-template').forEach(template => {
                template.addEventListener('click', function() {
                    const title = this.getAttribute('data-title');
                    const quote = this.getAttribute('data-quote');
                    const icon = this.getAttribute('data-icon');

                    data.titleHabit = title;
                    data.quote = quote;
                    data.iconBase64 = icon;

                    modal.querySelectorAll('.habit-template').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    step = 2;
                    renderStep();
                });
            });
        },
        2() {
            modal.querySelector('#habit-icon')?.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        data.iconBase64 = event.target.result;
                        modal.querySelector('.icon-preview').innerHTML = `<img src="${event.target.result}" alt="Icon" style="width: 50px; height: 50px; object-fit: cover;"/>`;
                    };
                    reader.readAsDataURL(file);
                }
            });

            modal.querySelector('#habit-title')?.addEventListener('input', e => {
                data.titleHabit = e.target.value;
            });

            modal.querySelector('#habit-quote')?.addEventListener('input', e => {
                data.quote = e.target.value;
            });
        },
        3() {
            // Обработчики для переключения настроек
            modal.querySelectorAll('.setting-toggle').forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const content = this.nextElementSibling;
                    content.classList.toggle('inactive');
                    content.classList.toggle('active');
                });
            });

            // Обработчики для выбора типа цели
            modal.querySelectorAll('input[name="goal-type"]').forEach(radio => {
                radio.addEventListener('change', e => {
                    const goalType = e.target.value;
                    data.checkClose = (goalType === 'complete');
                    modal.querySelector('.specific-goal').classList.toggle('active', goalType === 'specific');

                    // Сбрасываем другие флаги при выборе "Достигните всего этого"
                    if (goalType === 'complete') {
                        data.checkAuto = false;
                        data.checkManually = false;
                    }
                });
            });

            // Обработчик для выбора метода проверки
            modal.querySelector('#verification-type')?.addEventListener('change', e => {
                const method = e.target.value;
                data.checkAuto = (method === 'auto');
                data.checkManually = (method === 'manual');
                data.checkClose = (method === 'complete');

                // Показываем/скрываем поле для автоматического количества
                const autoCountContainer = modal.querySelector('#auto-count-container');
                if (autoCountContainer) {
                    autoCountContainer.style.display = method === 'auto' ? 'block' : 'none';
                }
            });

            // Обработчик для поля автоматического количества
            modal.querySelector('#auto-count')?.addEventListener('input', e => {
                data.autoCount = parseInt(e.target.value) || 0;
            });

            // Обработчики для полей специфической цели
            modal.querySelector('#daily-goal')?.addEventListener('input', e => {
                data.purposeCount = parseInt(e.target.value) || 0;
            });

            modal.querySelector('#goal-unit')?.addEventListener('change', e => {
                data.purposeType = e.target.value;
            });

            modal.querySelectorAll('.chose-data-block .option')?.forEach(option => {
                option.addEventListener('click', e => {
                    modal.querySelectorAll('.option')?.forEach(o => o.classList.remove('active'));
                    e.currentTarget.classList.add('active');

                    const type = e.currentTarget.classList.contains('daily') ? 'daily'
                        : e.currentTarget.classList.contains('weekly') ? 'weekly' : 'repeat';

                    data.datesType = type;
                    modal.querySelector('.date-type').innerHTML = renderFrequencyOptions(type, data);
                    attachDayHandlers();
                });
            });

            attachDayHandlers();

            modal.querySelector('#start-date')?.addEventListener('change', e => {
                data.beginDate = Math.floor(new Date(e.target.value).getTime() / 1000);
            });

            modal.querySelector('#duration')?.addEventListener('input', e => {
                data.goalInDays = e.target.value;
            });

            modal.querySelector('#reminder')?.addEventListener('change', e => {
                data.notificationDate = e.target.value;
            });
        }
    };

    function attachDayHandlers() {
        modal.querySelectorAll('.day-item')?.forEach(el => {
            el.addEventListener('click', e => {
                e.currentTarget.classList.toggle('active-day');
                const day = e.currentTarget.getAttribute('data-day');
                data.date[day] = e.currentTarget.classList.contains('active-day');
            });
        });
    }

    function createModal() {
        if (modal) return;
        modal = document.createElement('div');
        modal.className = 'modal';
        container.appendChild(modal);
        renderStep();
    }

    function closeModal() {
        if (modal && container.contains(modal)) {
            container.removeChild(modal);
            modal = null;
        }
    }

    function renderStep() {
        if (!modal) return;
        modal.innerHTML = templates[step]?.(data) || '';
        attachHandlers();
        handlers[step]?.();
    }

    function attachHandlers() {
        modal.querySelector('#next')?.addEventListener('click', () => {
            if (validateStep(step)) {
                step++;
                renderStep();
            }
        });

        modal.querySelector('#prev')?.addEventListener('click', () => {
            step--;
            renderStep();
        });

        modal.querySelector('#cancel')?.addEventListener('click', closeModal);
        modal.querySelector('#save')?.addEventListener('click', saveData);
    }

    function validateStep(step) {
        if (step === 1) {
            return true;
        } else if (step === 2) {
            if (!data.titleHabit.trim()) {
                showError('Введите название привычки');
                return false;
            }
        } else if (step === 3) {
            if (data.datesType === 'daily' && !Object.values(data.date).some(v => v)) {
                showError('Выберите хотя бы один день');
                return false;
            }

            if (!data.checkClose && data.purposeCount <= 0) {
                showError('Введите корректное значение цели');
                return false;
            }

            if (data.checkAuto && data.autoCount <= 0) {
                showError('Введите корректное значение автоматического количества');
                return false;
            }

            if (!data.goalInDays || parseInt(data.goalInDays) <= 0) {
                showError('Укажите корректную длительность');
                return false;
            }
        }
        return true;
    }

    function saveData() {
        if (!validateStep(step)) return;

        const payload = {
            titleHabit: data.titleHabit,
            iconBase64: data.iconBase64,
            quote: data.quote,
            goalInDays: data.goalInDays,
            datesType: data.datesType,
            date: prepareDateField(),
            beginDate: data.beginDate,
            notificationDate: data.notificationDate,
            purposeType: data.purposeType,
            purposeCount: data.purposeCount,
            checkManually: data.checkManually,
            checkAuto: data.checkAuto,
            checkClose: data.checkClose,
            autoCount: data.autoCount // Добавляем autoCount в отправляемые данные
        };

        const loader = document.createElement('div');
        loader.className = 'loader';
        loader.innerHTML = `<img src="loader.gif" alt="Загрузка..."/>`;
        modal.appendChild(loader);

        console.log(payload);

        fetch('http://taskflow/api/habits/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        })
            .then(response => response.json())
            .then(result => {
                loader.remove();
                if (result.success) {
                    alert('Привычка сохранена!');
                    closeModal();
                } else {
                    showError(result.message || 'Ошибка сохранения данных!');
                }
            })
            .catch(error => {
                loader.remove();
                console.error(error);
                showError('Произошла ошибка! Попробуйте позже.');
            });
    }

    function prepareDateField() {
        if (data.datesType === 'daily') {
            return {
                mon: data.date.mon,
                tue: data.date.tue,
                wed: data.date.wed,
                thu: data.date.thu,
                fri: data.date.fri,
                sat: data.date.sat,
                sun: data.date.sun
            };
        } else if (data.datesType === 'weekly') {
            return {
                count: parseInt(modal.querySelector('#weekly-count').value) || 1
            };
        } else {
            return {
                day: parseInt(modal.querySelector('#repeat-day').value) || 1
            };
        }
    }

    function showError(message) {
        let errorDiv = modal.querySelector('.error-message');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.color = '#ff3d3d';
            errorDiv.style.margin = '10px';
            errorDiv.style.textAlign = 'center';
            errorDiv.style.fontWeight = 'bold';
            modal.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }

    btn.addEventListener('click', e => {
        e.preventDefault();
        step = 1;
        data.titleHabit = '';
        data.quote = '';
        data.iconBase64 = '';
        data.date = {
            mon: false,
            tue: false,
            wed: false,
            thu: false,
            fri: false,
            sat: false,
            sun: false
        };
        data.datesType = 'daily';
        data.beginDate = Math.floor(Date.now() / 1000);
        data.notificationDate = '08:30';
        data.purposeType = 'count';
        data.purposeCount = 1;
        data.checkManually = false;
        data.checkAuto = false;
        data.checkClose = false;
        data.goalInDays = '30';
        data.autoCount = 0; // Сбрасываем autoCount

        createModal();
    });
})();