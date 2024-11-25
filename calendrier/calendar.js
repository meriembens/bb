const calendarGrid = document.getElementById("grid"); // Grid element in HTML
const monthNames = [
  "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
  "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
];

let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
let appointments = []; // Global array for fetched appointments

// Define colors for each doctor
const doctorColors = {
  "Dr Belhedid Ibtissem": "#FF7F7F",
  "Dr Bensalah Meriem": "#87CEEB",
  "Dr Guerroumi Lynda": "#9370DB",
  "Dr Bouchetara Ryane": "#FFCC00",
  "Dr Keciour Nesma": "#98FB98"
};

// Display the current month and year
document.getElementById("month").innerHTML = monthNames[currentMonth];
document.getElementById("year").innerHTML = currentYear;

// Fetch appointments and store in the global array
function fetchAppointments() {
  fetch('fetchAppointments.php') // Replace with your PHP endpoint
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json(); // Parse JSON response
    })
    .then(data => {
      console.log("Fetched Appointments:", data); // Log fetched data
      appointments = data; // Store data in the global array
      updateCalendar(); // Update the calendar after fetching appointments
    })
    .catch(error => {
      console.error("Error fetching appointments:", error);
    });
}

// Filter appointments based on the selected doctor
function filterAppointmentsByDoctor(doctorName) {
  if (doctorName === "tous") {
    return appointments; // If "Tous les Docteurs" is selected, show all appointments
  } else {
    return appointments.filter(appointment => appointment.doctor === doctorName);
  }
}

// Update the calendar with day cells and appointments
function updateCalendar() {
  const selectedDoctor = document.getElementById("filters").value; // Get the selected doctor
  const filteredAppointments = filterAppointmentsByDoctor(selectedDoctor);

  let firstDay = new Date(currentYear, currentMonth, 1).getDay();
  const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
  calendarGrid.innerHTML = "";

  // Creating empty grid cells before the first day of the month
  for (let i = 0; i <= firstDay; i++) {
    calendarGrid.innerHTML += `<div class="day empty"></div>`;
  }

  // Define the time slots to display (from 08:00 to 14:00)
  const timeSlots = ["08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00"];

  // Loop through the days of the month
  for (let day = 1; day <= daysInMonth; day++) {
    const dayGrid = document.createElement("div");
    dayGrid.classList.add("day");
    dayGrid.innerText = day;

    const currentDateString = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

    // Filter appointments for the current date
    const dayAppointments = filteredAppointments.filter(appointment =>
      appointment.start.startsWith(currentDateString)
    );

    // Create a container for appointments
    const appointmentContainer = document.createElement("div");
    appointmentContainer.classList.add("appointment-container");

    // Only display time slots between 08:00 and 14:00 that have appointments
    timeSlots.forEach(slot => {
      const slotAppointments = dayAppointments.filter(appointment => appointment.heure === slot);

      if (slotAppointments.length > 0) { // Only display slots with appointments
        const slotDiv = document.createElement("div");
        slotDiv.classList.add("time-slot");
        slotDiv.innerText = slot; // Display the time slot (e.g., 08:00)

        // Add appointments to the slot
        slotAppointments.forEach(appointment => {
          const appointmentDiv = document.createElement("div");
          appointmentDiv.classList.add("appointment");

          // Set background color based on the doctor
          const doctorName = appointment.doctor;
          const color = doctorColors[doctorName] || "#f0f8ff"; // Default color if doctor is not in the map
          appointmentDiv.style.backgroundColor = color;

          // Add the doctor's name, motif, and time in the appointment
          appointmentDiv.textContent = `${appointment.motif}`; // Display the motif and the time
          slotDiv.appendChild(appointmentDiv);
        });

        appointmentContainer.appendChild(slotDiv); // Add the populated slot to the container
      }
    });

    if (appointmentContainer.childElementCount > 0) { // Only append the day if there are appointments
      dayGrid.appendChild(appointmentContainer);
      calendarGrid.appendChild(dayGrid);
    }

    // Add event listener for the day cell to redirect
    const dayOfWeek = new Date(currentYear, currentMonth, day).getDay();
    if (dayOfWeek === 5 || dayOfWeek === 6) {
      dayGrid.classList.add("unclickable");
    } else {
      dayGrid.addEventListener("click", function () {
        window.location.href = `days.html?day=${day}&month=${monthNames[currentMonth]}&year=${currentYear}`;
      });
    }

    // Highlight current day
    const today = new Date();
    if (
      day === today.getDate() &&
      currentMonth === today.getMonth() &&
      currentYear === today.getFullYear()
    ) {
      dayGrid.classList.add("current-day");
    }

    dayGrid.setAttribute("data-day", day);
    calendarGrid.appendChild(dayGrid);
  }
}

// Handle month navigation
function changeMonth(i) {
  currentMonth += i;

  if (currentMonth < 0) {
    currentMonth = 11;
    currentYear--;
  }

  if (currentMonth > 11) {
    currentMonth = 0;
    currentYear++;
  }

  updateMonthYearDisplay();
  updateCalendar();
}

// Update month and year display
function updateMonthYearDisplay() {
  document.getElementById("month").innerHTML = monthNames[currentMonth];
  document.getElementById("year").innerHTML = currentYear;
}

// Add event listener for doctor filter change
document.getElementById("filters").addEventListener("change", updateCalendar);

// Call fetchAppointments on page load
fetchAppointments();
