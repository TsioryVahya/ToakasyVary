let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

// Jours fériés de Madagascar
const joursFeries = {
    2025: {
        '01-01': { nom: 'Nouvel An', couleur: 'bg-red-500' },
        '03-29': { nom: 'Insurrection de 1947', couleur: 'bg-purple-500' },
        '04-20': { nom: 'Pâques', couleur: 'bg-green-500' },
        '04-21': { nom: 'Lundi de Pâques', couleur: 'bg-green-400' },
        '05-01': { nom: 'Fête du Travail', couleur: 'bg-yellow-500' },
        '05-29': { nom: 'Ascension', couleur: 'bg-blue-500' },
        '06-09': { nom: 'Lundi de Pentecôte', couleur: 'bg-indigo-500' },
        '06-26': { nom: 'Fête de l\'Indépendance', couleur: 'bg-orange-500' },
        '08-15': { nom: 'Assomption', couleur: 'bg-pink-500' },
        '11-01': { nom: 'Toussaint', couleur: 'bg-teal-500' },
        '12-25': { nom: 'Noël', couleur: 'bg-red-600' }
    }
};

function generateCalendar(month, year) {
    const monthNames = [
        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
    ];

    document.getElementById('monthYear').textContent = `${monthNames[month]} ${year}`;

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const calendarGrid = document.getElementById('calendar-grid');
    
    calendarGrid.innerHTML = '';

    // Cellules vides pour les jours précédents
    for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'p-3 min-h-[80px] border border-gray-200 rounded';
        calendarGrid.appendChild(emptyCell);
    }

    // Jours du mois
    for (let day = 1; day <= daysInMonth; day++) {
        const dayCell = document.createElement('div');
        dayCell.className = 'p-3 min-h-[80px] border border-gray-200 rounded cursor-pointer hover:bg-gray-50';
        
        const dateString = `${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const jourFerie = joursFeries[year] && joursFeries[year][dateString];

        if (jourFerie) {
            dayCell.className += ` ${jourFerie.couleur} text-white`;
            dayCell.innerHTML = `
                <div class="font-bold">${day}</div>
                <div class="text-xs mt-1">${jourFerie.nom}</div>
            `;
            dayCell.title = jourFerie.nom;
        } else {
            dayCell.innerHTML = `<div class="font-semibold">${day}</div>`;
        }

        // Mettre en évidence le jour actuel
        if (day === currentDate.getDate() && month === currentDate.getMonth() && year === currentDate.getFullYear()) {
            dayCell.className += ' ring-2 ring-blue-400';
        }

        calendarGrid.appendChild(dayCell);
    }
}

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

// Make functions global
window.previousMonth = previousMonth;
window.nextMonth = nextMonth;
window.generateCalendar = generateCalendar;

// Initialiser le calendrier quand la page est chargée
document.addEventListener('DOMContentLoaded', function() {
    generateCalendar(currentMonth, currentYear);
});