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
    const dateKey = `${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;

    // Vérifie si c'est un jour férié
    let isFerie = false;
    let ferieNom = '';
    let ferieCouleur = '';
    if (joursFeries[year] && joursFeries[year][dateKey]) {
        isFerie = true;
        ferieNom = joursFeries[year][dateKey].nom;
        ferieCouleur = joursFeries[year][dateKey].couleur;
    }

    // Récupère les événements pour ce jour
    const dayEvents = vieillissementEvents
        .filter(e => e.date === dateStr)
        .sort((a, b) => EVENT_PRIORITY[a.type] - EVENT_PRIORITY[b.type]);

    // Contenu de base
    let content = `<div class="font-semibold text-white mb-1">${day}</div>`;

    // Affichage jour férié
    if (isFerie) {
        content += `<div class="mt-2 text-xs font-bold ${ferieCouleur} px-2 py-1 rounded">${ferieNom}</div>`;
        cell.className += ` ${ferieCouleur}`;
    }

    // Affichage des événements de production
    if (dayEvents.length > 0) {
        content += '<div class="mt-1 space-y-1">';
        dayEvents.forEach(event => {
            let displayText = event.nom.split(' - ')[0];
            let iconSymbol = '';

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

    // Détail au clic
    cell.addEventListener('click', () => {
        if (isFerie) {
            alert(`Jour férié : ${ferieNom}`);
        } else if (dayEvents.length > 0) {
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

// Jours fériés de Madagascar
const joursFeries = {
    2025: {
        '01-01': { nom: 'Nouvel An', couleur: 'bg-red-500' },
        '03-29': { nom: 'Insurrection de 1947', couleur: 'bg-purple-500' },
        '04-20': { nom: 'Pâques', couleur: 'bg-green-500' },
        '04-21': { nom: 'Lundi de Pâques', couleur: 'bg-green-400' },
        '05-01': { nom: 'Fête du Travail', couleur: 'bg-yellow-500' },
        '05-29': { nom: 'Ascension', couleur: 'bg-blue-500' },
        '06-08': { nom: 'Pentecôte', couleur: 'bg-indigo-500' },
        '06-09': { nom: 'Lundi de Pentecôte', couleur: 'bg-indigo-500' },
        '06-26': { nom: 'Fête de l\'Indépendance', couleur: 'bg-orange-500' },
        '08-15': { nom: 'Assomption', couleur: 'bg-pink-500' },
        '11-01': { nom: 'Toussaint', couleur: 'bg-teal-500' },
        '12-25': { nom: 'Noël', couleur: 'bg-red-600' },
        '10-04': { nom: 'Test Événement', couleur: 'bg-blue-500' } 
    }
};

// Fonction pour calculer la période de notification (3 mois avant pendant 1 semaine)
function isNotificationPeriod(eventDate, currentDate) {
    const threeMonthsBefore = new Date(eventDate);
    threeMonthsBefore.setMonth(threeMonthsBefore.getMonth() - 3);
    const notificationStart = new Date(threeMonthsBefore);
    const notificationEnd = new Date(threeMonthsBefore);
    notificationEnd.setDate(notificationEnd.getDate() + 7); // +7 jours pour une semaine
    return currentDate >= notificationStart && currentDate <= notificationEnd;
}

function getCurrentNotifications() {
    const today = new Date();
    const notifications = [];

    // Parcourir toutes les années disponibles
    Object.keys(joursFeries).forEach(year => {
        Object.keys(joursFeries[year]).forEach(dateKey => {
            const [month, day] = dateKey.split('-').map(Number);
            const eventDate = new Date(year, month - 1, day);

            if (isNotificationPeriod(eventDate, today)) {
                notifications.push({
                    nom: joursFeries[year][dateKey].nom,
                    date: eventDate,
                    couleur: joursFeries[year][dateKey].couleur
                });
            }
        });
    });

    // Ajout des notifications pour les événements de vieillissement (ex: 3 mois avant la fin)
    vieillissementEvents.forEach(event => {
        if (event.date) {
            const eventDate = new Date(event.date);
            if (isNotificationPeriod(eventDate, today)) {
                notifications.push({
                    nom: event.nom,
                    date: eventDate,
                    couleur: event.couleur || 'bg-blue-400'
                });
            }
        }
    });

    return notifications;
}


function displayNotifications() {
    const notifications = getCurrentNotifications();

    let notificationContainer = document.getElementById('notification-container');
    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        notificationContainer.className = 'fixed top-4 left-1/2 transform -translate-x-1/2 z-50 space-y-2 w-96';
        document.body.appendChild(notificationContainer);
    }

    notificationContainer.innerHTML = '';

    if (notifications.length > 0) {
        notifications.forEach((notification, index) => {
            // Calcul du nombre de jours restants
            const today = new Date();
            const diffTime = notification.date.getTime() - today.getTime();
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            let timingMsg = "";

            if (diffDays > 7) {
                timingMsg = `Dans ${diffDays} jours`;
            } else if (diffDays > 1) {
                timingMsg = `Dans ${diffDays} jours (cette semaine)`;
            } else if (diffDays === 1) {
                timingMsg = "Demain";
            } else if (diffDays === 0) {
                timingMsg = "Aujourd'hui";
            } else if (diffDays < 0) {
                timingMsg = `Il y a ${Math.abs(diffDays)} jours`;
            }

            const notificationDiv = document.createElement('div');
            notificationDiv.className = `bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg shadow-lg w-full mb-3`;
            notificationDiv.innerHTML = `
                <div class="flex items-center">
                    <div class="w-4 h-4 ${notification.couleur} rounded-full mr-4"></div>
                    <div class="flex-1">
                        <p class="font-bold text-base">🔔 ${notification.nom}</p>
                        <p class="text-sm">Le ${notification.date.toLocaleDateString('fr-FR')}</p>
                        <p class="text-xs italic">${timingMsg}</p>
                    </div>
                    <button onclick="temporaryHideNotification(${index})" class="ml-4 text-yellow-500 hover:text-yellow-700 text-2xl font-bold">
                        ×
                    </button>
                </div>
            `;
            notificationDiv.id = `notification-${index}`;
            notificationContainer.appendChild(notificationDiv);
        });
    }
}
function temporaryHideNotification(index) {
    const notif = document.getElementById(`notification-${index}`);
    if (notif) {
        notif.remove();
    }
}


// Initialisation
document.addEventListener('DOMContentLoaded', async () => {
    await loadData();
    generateCalendar(currentMonth, currentYear);
    displayNotifications();

    // Rafraîchissement automatique toutes les heures
    setInterval(async () => {
        await loadData();
        generateCalendar(currentMonth, currentYear);
    }, 3600000); // 1 heure

    setInterval(displayNotifications, 1800000); // Rafraîchit les notifications toutes les 30 min
});

// Exposer les fonctions au scope global
window.previousMonth = previousMonth;
window.nextMonth = nextMonth;
window.loadData = loadData;
window.generateCalendar = generateCalendar;
