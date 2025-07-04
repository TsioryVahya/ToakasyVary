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

// Fonction pour obtenir les notifications actuelles
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
    
    return notifications;
}

function displayNotifications() {
    const notifications = getCurrentNotifications();
    
    let notificationContainer = document.getElementById('notification-container');
    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        notificationContainer.className = 'fixed top-4 left-1/2 transform -translate-x-1/2 z-50 space-y-2 w-96'; // Élargi et centré
        document.body.appendChild(notificationContainer);
    }
    
    notificationContainer.innerHTML = '';
    
    if (notifications.length > 0) {
        notifications.forEach((notification, index) => {
            const notificationDiv = document.createElement('div');
            notificationDiv.className = `bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-6 rounded-lg shadow-lg w-full mb-3`; // Plus large et plus de padding
            notificationDiv.innerHTML = `
                <div class="flex items-center">
                    <div class="w-4 h-4 ${notification.couleur} rounded-full mr-4"></div>
                    <div class="flex-1">
                        <p class="font-bold text-lg">🔔 Rappel événement</p>
                        <p class="text-base font-semibold">${notification.nom}</p>
                        <p class="text-sm">Le ${notification.date.toLocaleDateString('fr-FR')}</p>
                        <p class="text-sm italic">Dans environ 3 mois</p>
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

    for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'p-3 min-h-[80px] border border-gray-200 rounded';
        calendarGrid.appendChild(emptyCell);
    }

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

function temporaryHideNotification(index) {
    const notification = document.getElementById(`notification-${index}`);
    if (notification) {
        notification.style.display = 'none';
    }
}

function closeNotification(index) {
    const notification = document.getElementById(`notification-${index}`);
    if (notification) {
        notification.remove();
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

// Exposer les fonctions globalement
window.previousMonth = previousMonth;
window.nextMonth = nextMonth;
window.generateCalendar = generateCalendar;
window.closeNotification = closeNotification;
window.temporaryHideNotification = temporaryHideNotification;

document.addEventListener('DOMContentLoaded', function() {
    generateCalendar(currentMonth, currentYear);
    displayNotifications();
    
    // Vérifier et afficher les notifications toutes les 30 minutes
    setInterval(displayNotifications, 1800000); // 30 minutes = 1800000 ms
});