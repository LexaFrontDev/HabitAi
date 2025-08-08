(function() {
    let userId = null;

    let totalTime = parseInt(localStorage.getItem('pomodoroTotalTime')) || 0;
    let timeLeft = parseInt(localStorage.getItem('pomodoroTimeLeft')) || 0;
    let isRunning = false;
    let startTime = localStorage.getItem('pomodoroStartTime') || null;

    const circumference = 2 * Math.PI * 16;

    const timerText = document.getElementById('timer-text');
    const startBtn = document.getElementById('start-btn');
    const pomodoroWrapper = document.getElementById('Pomodoro-wrapper');
    const progressPath = document.getElementById('progress-path');
    const overlay = document.getElementById('modal-overlay');
    const minutesInputEl = document.getElementById('modal-minutes-input');
    const saveBtn = document.getElementById('modal-save-btn');
    const closeBtn = document.getElementById('modal-close-btn');

    let intervalId;

    async function fetchUserId() {
        const resp = await fetch('/api/web/user/id');
        const data = await resp.json();
        return userId = data?.userId ?? null;
    }


    function saveToLocal() {
        localStorage.setItem('pomodoroTotalTime', totalTime);
        localStorage.setItem('pomodoroTimeLeft', timeLeft);
    }

    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
        const secs = (seconds % 60).toString().padStart(2, '0');
        return `${mins}:${secs}`;
    }

    async function endFocus(finishTime) {
        alert("Время вышло! 🕰️");

        if (!userId) {
            userId = await fetchUserId();
        }

        if (!userId) {
            console.error("Не удалось получить userId!");
            return;
        }

        console.log("Используем userId:", userId);

        await fetch("/api/pomodor/create", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({
                userId,
                timeFocus: totalTime,
                timeStart: Math.floor(new Date(startTime).getTime() / 1000),
                timeEnd: Math.floor(finishTime.getTime() / 1000),
                createdDate: Math.floor(new Date().getTime() / 1000),
            }),
        }).catch(error => console.error(error));
    }



    function updateDisplay() {
        timerText.textContent = formatTime(timeLeft);
        const offset = totalTime > 0 ? ((totalTime - timeLeft) / totalTime) * circumference : 0;
        progressPath.setAttribute('stroke-dasharray', `${offset} ${circumference}`);
    }

    function handleStartPause() {
        if (!isRunning) {
            if (!startTime) {
                startTime = new Date();
                localStorage.setItem('pomodoroStartTime', startTime);
            }
            intervalId = setInterval(() => {
                if (timeLeft > 0) {
                    timeLeft -= 1;
                    saveToLocal();
                    updateDisplay();
                } else {
                    clearInterval(intervalId);
                    const finishTime = new Date();
                    localStorage.removeItem('pomodoroTotalTime');
                    localStorage.removeItem('pomodoroTimeLeft');
                    localStorage.removeItem('pomodoroStartTime');
                    endFocus(finishTime);
                }
            }, 1000);
        } else {
            clearInterval(intervalId);
        }
        isRunning = !isRunning;
        startBtn.textContent = isRunning ? 'Пауза' : 'Старт';
    }

    function handleSetTime() {
        const minutes = parseInt(minutesInputEl.value);
        if (!minutes) return;

        totalTime = minutes * 60;
        timeLeft = totalTime;

        saveToLocal();
        overlay.style.display = 'none';
        updateDisplay();
    }

    pomodoroWrapper.addEventListener('click', () => {
        overlay.style.display = 'block';
    });
    saveBtn.addEventListener('click', handleSetTime);
    closeBtn.addEventListener('click', () => {
        overlay.style.display = 'none';
    });
    startBtn.addEventListener('click', handleStartPause);

    (async function init() {
        await fetchUserId();
        updateDisplay();
    })();
})();
