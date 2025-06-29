<form action="{{ route('stat_vente') }}" method="POST">
    @csrf
    <div>
        <label for="date_debut">Date de début :</label>
        <input type="date" name="date_debut" id="date_debut" required>
    </div>

    <div>
        <label for="date_fin">Date de fin :</label>
        <input type="date" name="date_fin" id="date_fin" required>
    </div>

    <div>
        <button type="submit">Voir les statistiques</button>
    </div>
</form>
