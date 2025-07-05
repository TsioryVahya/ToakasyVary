let gammes = [];
let typeBouteilles = [];

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Récupérer les données depuis les éléments data
    const gammeData = document.getElementById('gamme-data');
    const typeBouteilleData = document.getElementById('type-bouteille-data');
    
    if (gammeData) {
        gammes = JSON.parse(gammeData.textContent);
    }
    if (typeBouteilleData) {
        typeBouteilles = JSON.parse(typeBouteilleData.textContent);
    }
    
    // Initialiser les événements
    initializeEvents();
});

/**
 * Initialise tous les événements
 */
function initializeEvents() {
    // Événements pour fermer le modal
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
    
    // Fermer le modal en cliquant à l'extérieur
    window.onclick = function(event) {
        const modal = document.getElementById('lotModal');
        if (event.target == modal) {
            closeModal();
        }
    }
}

/**
 * Calcule les dates automatiquement selon la gamme et la date de début
 */
function calculateDates() {
    const gammeSelect = document.getElementById('id_gamme');
    const dateDebut = document.getElementById('date_debut').value;
    
    const miseEnBouteilleElement = document.getElementById('calculated_mise_en_bouteille');
    const commercialisationElement = document.getElementById('calculated_commercialisation');
    
    if (!gammeSelect.value || !dateDebut) {
        if (miseEnBouteilleElement) {
            miseEnBouteilleElement.textContent = 'Sélectionnez une gamme et une date de début';
        }
        if (commercialisationElement) {
            commercialisationElement.textContent = 'Sélectionnez une gamme et une date de début';
        }
        return;
    }
    
    const selectedOption = gammeSelect.options[gammeSelect.selectedIndex];
    const fermentationJours = parseInt(selectedOption.getAttribute('data-fermentation'));
    const vieillissementJours = parseInt(selectedOption.getAttribute('data-vieillissement'));
    
    const dateDebutObj = new Date(dateDebut);
    const dateMiseEnBouteille = new Date(dateDebutObj);
    dateMiseEnBouteille.setDate(dateMiseEnBouteille.getDate() + fermentationJours);
    
    const dateCommercialisation = new Date(dateMiseEnBouteille);
    dateCommercialisation.setDate(dateCommercialisation.getDate() + vieillissementJours);
    
    if (miseEnBouteilleElement) {
        miseEnBouteilleElement.textContent = 
            dateMiseEnBouteille.toLocaleDateString('fr-FR') + ' (' + fermentationJours + ' jours après le début)';
    }
    if (commercialisationElement) {
        commercialisationElement.textContent = 
            dateCommercialisation.toLocaleDateString('fr-FR') + ' (' + vieillissementJours + ' jours après la mise en bouteille)';
    }
}

/**
 * Ouvre le modal de création
 */
function openModal() {
    const modal = document.getElementById('lotModal');
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('lotForm');
    const methodField = document.getElementById('methodField');
    const submitBtn = document.getElementById('submitBtn');
    
    if (modal) modal.style.display = 'block';
    if (modalTitle) modalTitle.textContent = 'Nouveau Lot de Production';
    if (form) form.action = window.routes.store;
    if (methodField) methodField.innerHTML = '';
    if (submitBtn) submitBtn.textContent = 'Créer le Lot';
    
    resetForm();
}

/**
 * Ouvre le modal d'édition
 */
function openEditModal(lotId) {
    const dataUrl = window.routes.data.replace(':id', lotId);
    
    fetch(dataUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            const modal = document.getElementById('lotModal');
            const modalTitle = document.getElementById('modalTitle');
            const form = document.getElementById('lotForm');
            const methodField = document.getElementById('methodField');
            const submitBtn = document.getElementById('submitBtn');
            
            if (modal) modal.style.display = 'block';
            if (modalTitle) modalTitle.textContent = `Modifier le Lot #${lotId}`;
            if (form) form.action = window.routes.update.replace(':id', lotId);
            if (methodField) methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            if (submitBtn) submitBtn.textContent = 'Mettre à jour';
            
            // Remplir le formulaire avec les données existantes
            fillForm(data);
            
            // Calculer les dates
            calculateDates();
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('Erreur lors du chargement des données', 'error');
        });
}

/**
 * Remplit le formulaire avec les données
 */
function fillForm(data) {
    const fields = ['id_gamme', 'id_bouteille', 'date_debut', 'nombre_bouteilles'];
    
    fields.forEach(field => {
        const element = document.getElementById(field);
        if (element && data[field] !== undefined) {
            element.value = data[field] || '';
        }
    });
}

/**
 * Ferme le modal
 */
function closeModal() {
    const modal = document.getElementById('lotModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

/**
 * Remet le formulaire à zéro
 */
function resetForm() {
    const form = document.getElementById('lotForm');
    if (form) form.reset();
    
    const miseEnBouteilleElement = document.getElementById('calculated_mise_en_bouteille');
    const commercialisationElement = document.getElementById('calculated_commercialisation');
    
    if (miseEnBouteilleElement) {
        miseEnBouteilleElement.textContent = 'Sélectionnez une gamme et une date de début';
    }
    if (commercialisationElement) {
        commercialisationElement.textContent = 'Sélectionnez une gamme et une date de début';
    }
}

/**
 * Confirme la suppression
 */
function confirmDelete(lotId) {
    return confirm('Êtes-vous sûr de vouloir supprimer ce lot ?');
}

/**
 * Affiche une alerte
 */
function showAlert(message, type = 'info') {
    // Créer l'élément d'alerte
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg`;
    
    // Définir les classes selon le type
    const typeClasses = {
        'success': 'bg-green-100 border-green-400 text-green-700',
        'error': 'bg-red-100 border-red-400 text-red-700',
        'info': 'bg-blue-100 border-blue-400 text-blue-700',
        'warning': 'bg-yellow-100 border-yellow-400 text-yellow-700'
    };
    
    alertDiv.className += ' ' + (typeClasses[type] || typeClasses.info);
    alertDiv.textContent = message;
    
    // Ajouter à la page
    document.body.appendChild(alertDiv);
    
    // Supprimer après 5 secondes
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
        }
    }, 5000);
}

// Exposer les fonctions globalement
window.calculateDates = calculateDates;
window.openModal = openModal;
window.openEditModal = openEditModal;
window.closeModal = closeModal;
window.confirmDelete = confirmDelete;