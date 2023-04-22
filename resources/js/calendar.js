import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import frLocale from '@fullcalendar/core/locales/fr'; // Idioma francés y configuración


document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById("calendar");

    let calendar = new Calendar(calendarEl, {
        lazyFetching: true,
        locale: frLocale,
        plugins: [ dayGridPlugin, timeGridPlugin, listPlugin ],
        initialView: 'dayGridMonth',
        events: '/events',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        editable: true,
        selectable: true,
        draggable: true,
        //dayMaxEvents: 3,
        // otros parámetros
    });

    calendar.render();
});
