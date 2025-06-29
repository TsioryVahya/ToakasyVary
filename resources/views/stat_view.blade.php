<body>
    <h1>Stats</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Montant</th>
                <th>Quantite</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats as $stat)
                <tr>
                    <td>{{ $stat->nom }}</td>
                    <td>{{ $stat->total_montant }}</td>
                    <td>{{ $stat->total_quantite }}</td>


                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
