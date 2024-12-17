/**
 * First, we will load all of this project's JavaScript dependencies,
 * which includes Alpine.js, Livewire, and other libraries.
 */

import "./bootstrap";
// import Swal from 'sweetalert2';
// window.Swal = Swal;
import Calendar from "@toast-ui/calendar";
import "@toast-ui/calendar/dist/toastui-calendar.min.css";


document.addEventListener("DOMContentLoaded", () => {
  const calendarEl = document.getElementById("calendar");

  const calendar = new Calendar(calendarEl, {
    defaultView: "month", // Options: 'month', 'week', 'day'
    useDetailPopup: true, // Enables popups for event details
    useFormPopup: true,   // Enables popups for adding events
    month: {
      daynames: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"], // Customize day names
    },
    theme: {
      common: {
        today: { color: "#fff", backgroundColor: "#4caf50" }, // Highlight today's date
      },
    },
    template: {
      milestone(event) {
        return `<span class="milestone">${event.title}</span>`;
      },
    },
  });

  // Example events
  calendar.createEvents([
    {
      id: "1",
      calendarId: "0",
      title: "Meeting",
      category: "time",
      dueDateClass: "",
      start: "2024-12-01T10:30:00",
      end: "2024-12-01T12:30:00",
    },
    {
      id: "2",
      calendarId: "0",
      title: "Sports",
      category: "time",
      start: "2024-12-05T09:00:00",
      end: "2024-12-05T17:00:00",
    },
  ]);
});




