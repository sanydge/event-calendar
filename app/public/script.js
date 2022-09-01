const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

let date = new Date();

let dateParam = new URL(window.location.href).searchParams.get("date");

if (dateParam && dateParam.length) {
    date = new Date(dateParam);
}

// Validare corectitudine format data
if (new Date(date).toString() === 'Invalid Date') {
    window.location = '/'
}

const bookdateElement = document.getElementById('bookdate')
    if(bookdateElement){
     bookdateElement.value = date.toISOString().split('T')[0];
    }


const monthDays = document.querySelector('.days')

const renderCalendar = () => {
    const calendarYear = date.getFullYear();
    const calendarMonth = date.getMonth();

    const firstDay = new Date(calendarYear, calendarMonth, 1)

    // Daca indexul zilei este 0 -> seteaza-l ca 7 (duminica)
    const firstDayIndex = firstDay.getDay() === 0 ? 7 : firstDay.getDay();

    const lastDay = new Date(calendarYear, calendarMonth + 1, 0)

    const previousMonthLastDay = new Date(calendarYear, calendarMonth, 0).getDate();

    let days = "";

    for (let b = previousMonthLastDay - firstDayIndex + 1; b < previousMonthLastDay; b++) {
        days += `<div class="previous-date">${b}</div>`
    }

    const month = `${date.getMonth() + 1}`.padStart(2, "0");

    for (let a = 1; a <= lastDay.getDate(); a++) {
        const isToday = a === new Date().getDate() && date.getMonth() === new Date().getMonth();
        const day = `${a}`.padStart(2, "0");
        days += `<div class="${isToday ? 'today' : ''}" data-date="${calendarYear}-${month}-${day}">${a}</div>`;
    }

    for (let c = 1; c <= 7 - lastDay.getDay(); c++) {
        days += `<div class="next-date">${c}</div>`;
    }

    monthDays.innerHTML = days;

    let daysList = document.querySelectorAll(".days [data-date]");

    daysList.forEach(function (elem) {
        elem.addEventListener("click", function (e) {
            console.log(elem.dataset.date);
            document.querySelector(".schedule p").innerHTML = new Date(elem.dataset.date).toDateString();
            window.location = "/?date=" + elem.dataset.date;
        })
    });

    document.querySelector(".date h1").innerHTML = months[calendarMonth];
    document.querySelector(".date p").innerHTML = `${calendarYear}`;
    document.querySelector(".schedule p").innerHTML = date.toDateString();
}


document.querySelector(".prev").addEventListener("click", () => {
    date.setMonth(date.getMonth() - 1);
    renderCalendar();
});

document.querySelector(".next").addEventListener("click", () => {
    date.setMonth(date.getMonth() + 1);
    renderCalendar();
});

renderCalendar();