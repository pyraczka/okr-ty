<?php
// Połączenie z bazą danych
$host = '127.0.0.1';
$user = 's267';
$password = '5enob5oadCos';
$dbname = 'dbs267';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

// Obsługa podstron
$page = $_GET['page'] ?? 'home';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Okręty Wojenne</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: grid;
            grid-template-areas:
                "header header"
                "menu content"
                "footer footer";
            grid-template-columns: 200px 1fr;
            grid-template-rows: auto 1fr auto;
            height: 100vh;
        }
        header, footer {
            grid-area: header;
            background-color: #003366;
            color: white;
            padding: 1em;
            text-align: center;
        }
        nav {
            grid-area: menu;
            background-color: #f0f0f0;
            padding: 1em;
            border-right: 1px solid #ccc;
        }
        nav a {
            display: block;
            margin-bottom: 10px;
            color: #003366;
            font-weight: bold;
            text-decoration: none;
        }
        main {
            grid-area: content;
            padding: 2em;
        }
        footer {
            grid-area: footer;
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 1em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1em;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 0.5em;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>

<header>
    <h1>Okręty Wojenne</h1>
</header>

<nav>
    <a href="index.php?page=home">Strona główna</a>
    <a href="index.php?page=zestawienie">Lista okrętów</a>
    <a href="index.php?page=pierwszy_rok">Zadanie 1</a>
    <a href="index.php?page=typy_kraje">Zadanie 2</a>
</nav>

<main>
    <?php
    if ($page === 'home') {
        echo "<h2>Strona główna</h2>
        <p>Witamy na stronie poświęconej okrętom wojennym. Użyj menu po lewej stronie, aby wyświetlić dane z bazy.</p>";
    }

    if ($page === 'zestawienie') {
        echo "<h2>Zestawienie okrętów wojennych</h2>";
        $sql = "SELECT o.id_okretu, o.nazwa, o.typ, k.klasa, k.kraj, o.rok_zwodowania
                FROM okrety o
                JOIN klasy_okretow k ON o.typ = k.typ";
        $result = $conn->query($sql);
        echo "<table>
            <tr><th>ID</th><th>Nazwa</th><th>Typ</th><th>Klasa</th><th>Kraj</th><th>Rok zwodowania</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id_okretu']}</td>
                <td>{$row['nazwa']}</td>
                <td>{$row['typ']}</td>
                <td>{$row['klasa']}</td>
                <td>{$row['kraj']}</td>
                <td>{$row['rok_zwodowania']}</td>
            </tr>";
        }
        echo "</table>";
    }

    if ($page === 'pierwszy_rok') {
        echo "<h2>Typy okrętów i pierwszy rok zwodowania (po 1920)</h2>";
        $sql = "SELECT typ, MIN(rok_zwodowania) AS pierwszy_rok
                FROM okrety
                WHERE rok_zwodowania > 1920
                GROUP BY typ";
        $result = $conn->query($sql);
        echo "<table>
            <tr><th>Typ</th><th>Pierwszy rok</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['typ']}</td>
                <td>{$row['pierwszy_rok']}</td>
            </tr>";
        }
        echo "</table>";
    }

    if ($page === 'typy_kraje') {
        echo "<h2>Typy okrętów, kraje i liczba jednostek</h2>";
        $sql = "SELECT k.typ, k.kraj, COUNT(o.id_okretu) AS liczba_okretow
                FROM klasy_okretow k
                JOIN okrety o ON k.typ = o.typ
                GROUP BY k.typ, k.kraj
                ORDER BY liczba_okretow DESC";
        $result = $conn->query($sql);
        echo "<table>
            <tr><th>Typ</th><th>Kraj</th><th>Liczba okrętów</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['typ']}</td>
                <td>{$row['kraj']}</td>
                <td>{$row['liczba_okretow']}</td>
            </tr>";
        }
        echo "</table>";
    }
    ?>
</main>

<footer>
    &copy; 2025 Okręty Wojenne. Wszelkie prawa zastrzeżone.
</footer>

</body>
</html>

<?php
$conn->close();
?>
