let date = new Date();
let url_string = window.location.href;
let url = new URL(url_string);
let dateParam = url.searchParams.get("date");

if (dateParam && dateParam.length) {
    date = new Date(dateParam);
}
document.getElementById('bookdate').value = date.toISOString().split('T')[0];

const renderCalendar = () => {

    const calendarYear = date.getFullYear();
    const calendarMonth = date.getMonth();

    const firstDay = new Date(calendarYear, calendarMonth, 1)
    console.log({firstDay})
    const firstDayIndex = firstDay.getDay()
    console.log('firstDayIndex', firstDayIndex)

    // return

    const lastDay = new Date(calendarYear, calendarMonth + 1, 0)
    console.log(lastDay)


    const lastDayIndex = lastDay.getDay();
    console.log(lastDayIndex)
    // return

    const year = `${date.getFullYear()}`;
    const month = `${date.getMonth() + 1}`.padStart(2, "0");
    const monthDays = document.querySelector('.days')
    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    // const nextDays = 7 - lastDayIndex;


    document.querySelector(".date h1").innerHTML = months[date.getMonth()];
    document.querySelector(".date p").innerHTML = date.getFullYear();
    document.querySelector(".schedule p").innerHTML = date.toDateString();


    let days = "";
    for (let b = 1; b < firstDayIndex; b++) {
        days += `<div class="previous-date">${b}</div>`
    }

    for (let a = 1; a <= lastDay.getDate(); a++) {
        const isToday = a === new Date().getDate() && date.getMonth() === new Date().getMonth();
        const day = `${a}`.padStart(2, "0");
        days += `<div class="${isToday ? 'today' : ''}" data-date="${year}-${month}-${day}">${a}</div>`;
    }

    for (let c = 1; c <= 7 - lastDayIndex; c++) {
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


    // console.log(daysList);


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