let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

const EVENT_PRIORITY = {
    'production_start': 1,
    'fermentation_start': 2,
    'fermentation_end': 3,
    'aging_start': 4,
    'aging_end': 5
};

let vieillissementEvents = [];

// Chargement des données
async function loadData() {
    try {
        const response = await fetch('/production/calendar/data');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        console.log("Données reçues:", data);
        vieillissementEvents = data;
        return data;
    } catch (error) {
        console.error("Erreur lors du chargement:", error);
        return [];
    }
}

// Génération du calendrier
function generateCalendar(month, year) {
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    document.getElementById('monthYear').textContent =
        new Date(year, month).toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });

    const calendarGrid = document.getElementById('calendar-grid');
    calendarGrid.innerHTML = '';

    // Cases vides pour le début du mois
    for (let i = 0; i < firstDay; i++) {
        calendarGrid.appendChild(createEmptyCell());
    }

    // Génération des jours du mois
    for (let day = 1; day <= daysInMonth; day++) {
        calendarGrid.appendChild(createDayCell(day, month, year));
    }
}

function createEmptyCell() {
    const cell = document.createElement('div');
    cell.className = 'p-3 min-h-[80px] border border-gray-700 rounded bg-[#2d2d2d]';
    return cell;
}

function createDayCell(day, month, year) {
    const cell = document.createElement('div');
    cell.className = 'p-3 min-h-[80px] border border-gray-700 rounded cursor-pointer hover:bg-gray-800 bg-[#2d2d2d] text-white relative';

    // Format YYYY-MM-DD pour correspondre à l'API
    const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;

    // Récupère les événements pour ce jour
    const dayEvents = vieillissementEvents
        .filter(e => e.date === dateStr)
        .sort((a, b) => EVENT_PRIORITY[a.type] - EVENT_PRIORITY[b.type]);

    // Contenu de base
    let content = `<div class="font-semibold text-white mb-1">${day}</div>`;

    if (dayEvents.length > 0) {
        content += '<div class="mt-1 space-y-1">';

        dayEvents.forEach(event => {
            let displayText = event.nom.split(' - ')[0];
            let iconSymbol = '';

            // Définir les icônes selon le type d'événement
            if (event.is_start_event) {
                iconSymbol = '▶';
            } else if (event.is_end_event) {
                iconSymbol = '⏹';
            }

            content += `
                <div class="flex items-center text-xs text-gray-200 mb-1">
                    <span class="w-2 h-2 rounded-full ${event.couleur} mr-1 flex-shrink-0"></span>
                    <span class="truncate flex-1">${displayText}</span>
                    <span class="ml-1 text-yellow-400">${iconSymbol}</span>
                </div>
            `;
        });

        content += '</div>';
    }

    cell.innerHTML = content;

    // Ajouter un événement de clic pour plus de détails
    cell.addEventListener('click', () => {
        if (dayEvents.length > 0) {
            showEventDetails(dayEvents, dateStr);
        }
    });

    return cell;
}

function showEventDetails(events, date) {
    const eventsList = events.map(event =>
        `<li class="mb-2 p-2 bg-gray-800 rounded">
            <div class="flex items-center">
                <span class="w-3 h-3 rounded-full ${event.couleur} mr-2"></span>
                <div>
                    <div class="font-semibold">${event.nom}</div>
                    <div class="text-sm text-gray-400">${event.gamme}</div>
                </div>
            </div>
        </li>`
    ).join('');

    const formattedDate = new Date(date).toLocaleDateString('fr-FR', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    alert(`Événements du ${formattedDate}:\n\n${events.map(e => `• ${e.nom} (${e.gamme})`).join('\n')}`);
}

// Navigation entre les mois
function previousMonth() {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    generateCalendar(currentMonth, currentYear);
}

function nextMonth() {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    generateCalendar(currentMonth, currentYear);
}

// Initialisation
document.addEventListener('DOMContentLoaded', async () => {
    await loadData();
    generateCalendar(currentMonth, currentYear);

    // Rafraîchissement automatique toutes les heures
    setInterval(async () => {
        await loadData();
        generateCalendar(currentMonth, currentYear);
    }, 3600000); // 1 heure
});

// Exposer les fonctions au scope global
window.previousMonth = previousMonth;
window.nextMonth = nextMonth;
window.loadData = loadData;
window.generateCalendar = generateCalendar;
