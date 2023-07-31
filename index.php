<?php
$pokemon_id = 1; //Valeur initiale

//Fonction permettant de récupérer les données du pokémon selon l'ID
function get_pokemon_data($pokemon_id)
{
    $url = "https://api-pokemon-fr.vercel.app/api/v1/pokemon/{$pokemon_id}"; //URL de l'API
    $response = file_get_contents($url); //Récupère le JSON

    if ($response !== false) {
        $data = json_decode($response, true); //Interprète le JSON
        return $data;
    } else {
        echo "Erreur lors de la récupération des données."; //Erreur
        return null;
    }
}

//Récupère le premier et dernier ID
$datas = file_get_contents("https://api-pokemon-fr.vercel.app/api/v1/pokemon");
$decode = json_decode($datas, true);
$max = 0;
$min = 1;
foreach($decode as $data){
    if(isset($data['pokedexId']) && $data['pokedexId'] > $max){
        $max = $data['pokedexId'];
    }
}




//Si bouton cliqué dessus
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['next_poke'])) {
        if (isset($_POST['pokemon_id'])) {
            $pokemon_id = intval($_POST['pokemon_id']) + 1; //On passe au pokémon suivant
            if($pokemon_id > $max){
                $pokemon_id = $min;
            }
        } else {
            $pokemon_id = 1;
        }
    }else if(isset($_POST['prev_poke'])){
        if (isset($_POST['pokemon_id'])) {
            $pokemon_id = intval($_POST['pokemon_id']) - 1; //On passe au pokémon précédent
            if($pokemon_id < $min){
                $pokemon_id = $max;
            }
        } else {
            $pokemon_id = 1;
        }
    }else if(isset($_POST['target'])){
        $found = false;
        $dataspokes = file_get_contents("https://api-pokemon-fr.vercel.app/api/v1/pokemon");
        $pokedecode = json_decode($dataspokes);
        foreach($pokedecode as $pokemon){
            if($pokemon->name->fr == $_POST["pokemonName"] ||
            $pokemon->name->en == $_POST["pokemonName"]){
                $pokemon_id = $pokemon->pokedexId;
                $found = true;
            }
        }
        if(!$found){
            echo "No Pokémon found!";
        }
    }
} else {
    $pokemon_id = 1;
}

$pokemon_data = get_pokemon_data($pokemon_id); //Appelle la fonction pour récupérer les données du pokémon
?>

<?php if ($pokemon_data): ?>

<!-- HTML affichant les données -->
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Pokémon Website API</title>
</head>

<body>
    <h1>
        <?php echo ucfirst($pokemon_data['name']['en']) ?>
    </h1>

    <div class="container">
        <p>N°
            <?php echo $pokemon_id; ?>
        </p>
        <p>French name:
            <?php echo ucfirst($pokemon_data['name']['fr']); ?>
        </p>
        <p>English name:
            <?php echo ucfirst($pokemon_data['name']['en']); ?>
        </p>
        <p>Japanese name:
            <?php echo ucfirst($pokemon_data['name']['jp']); ?>
        </p>
        <p>Height:
            <?php echo $pokemon_data['height']; ?>
        </p>
        <p>Weight:
            <?php echo $pokemon_data['weight']; ?>
        </p>
        <p>Types:</p>
        <ul>
            <?php foreach ($pokemon_data['types'] as $typeEntry): ?>
                <li>
                <div class="types">
                <?php echo $typeEntry['name']. ' <img class="typepic" src="'.$typeEntry['image'].'" alt="pictype">'; ?>
                </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <p> Form: </p> <img id="pokepic" alt="form" src="<?php echo $pokemon_data['sprites']['regular']; ?>">
    <?php else: ?>
        <p>No Pokémon data found!.</p>
    <?php endif; ?>


    <!-- Utilisation du formulaire pour soumettre le bouton -->
    <form method="post" action="">
        <input type="hidden" name="pokemon_id" id="pokemon_id" value="<?php echo $pokemon_id; ?>">
        <input type="submit" name="prev_poke" value="Previous Pokémon">
        <input type="submit" name="next_poke" value="Next Pokémon">
    </form>

    <button name="target_poke" id="target_poke"> Pokémon wanted </button>
    <div id="pokemonNameInput">
        <form method="post" action="">
            <label for="pokemonName">Enter Pokémon Name:</label>
            <input type="text" id="pokemonName" name="pokemonName">
            <input type="submit" name="target" value="Rechercher">
        </form>

            
    </div>

</div>

</body>

<footer>

© Luc Leveque 2023

</footer>

<script src="script.js"></script>

</html>