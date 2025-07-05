<body>
    <h1>Stats</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Cout unitaire</th>
                <th>Total de bouteille</th>
                <th>Revenue</th>
                <th>Depense</th>
                <th>Marge Beneficiaire</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats as $stat)
                <tr>
                    <td>{{ $stat->nom }}</td>
                    <td>{{ $stat->cout_matiere_unitaire }}</td>
                    <td>{{ $stat->total_bouteille }}</td>
                    <td>{{ $stat->total_montant }}</td>
                    <td>{{ $stat->total_depense }}</td>
                    <td>{{ $stat->total_marge }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
