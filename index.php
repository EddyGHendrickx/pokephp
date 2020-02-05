<?php
$input = strtolower($_GET['inputField']);

function getNameAndId(string $inputValue) : array {
    $response = file_get_contents("https://pokeapi.co/api/v2/pokemon/" . $inputValue);
    $data = json_decode($response, true);
    $nameAndId = array();
    array_push($nameAndId, $data['species']['name'], $data['id']);
    return $nameAndId;
}

function pokeAPISprites(string $inputValue): string
{
    $response = file_get_contents("https://pokeapi.co/api/v2/pokemon/" . $inputValue);
    $data = json_decode($response, true);
    return $data['sprites']['front_default'];
}

function getEvolutionChain(string $inputValue): array
{
    $responseForChain = file_get_contents('https://pokeapi.co/api/v2/pokemon-species/' . $inputValue);
    $dataForChain = json_decode($responseForChain, true);
    $evolutionChainUrl = $dataForChain['evolution_chain']['url'];
    $responseChain = file_get_contents($evolutionChainUrl);
    $dataChain = json_decode($responseChain, true);
    $firstEvolution = $dataChain['chain']['species']['name'];
    $secondEvolution = $dataChain['chain']['evolves_to'][0]['species']['name'];
    $thirdEvolution = $dataChain['chain']['evolves_to'][0]['evolves_to'][0]['species']['name'];
    $evolutions = array();
    array_push($evolutions, $firstEvolution, $secondEvolution, $thirdEvolution);
    return $evolutions;
}


function getMoves(string $pokemonInput): array
{
    $response = file_get_contents("https://pokeapi.co/api/v2/pokemon/" . $pokemonInput);
    $data = json_decode($response, true);
    $moves = array();
    for ($i = 0; $i < 4; $i++) {
        array_push($moves, $data['moves'][$i]['move']['name']);
    }
    return $moves;
}


// Check for valid input
$isAPokemon = isset($pokeName);
if (!($isAPokemon)) {
    $sprite = 'https://lh3.googleusercontent.com/proxy/L7keklfvrENwjdvlA5o3Qu5oN-5xR8g-Gj9PeJa1Nol-7lskE05tC4bGKf6IdFOxWnEY9vOwep0PC07U2FeTnM4ix1bqhdleYlahoroqWKpLHw';
};

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Gotta Catch 'Em All!</title>
</head>
<body>

<form action="" method="get">
    <input type="text" placeholder="pokemonID or name" name="inputField">
</form>
<div id="container">
    <div id="pokeSprite">
        <img src="<?php echo pokeAPISprites($input) ?>" id="sprite" alt="">
        <div id="name"><?php echo getNameAndId($input)[0] ?> </div>
        <div id="id">ID: <?php echo getNameAndId($input)[1] ?></div>
    </div>
    <div id="pokeChain">
        <div id="firstPokemon">
            <img src="<?php echo pokeAPISprites(getEvolutionChain($input)[0]) ?>" alt="">
        </div>
        <div id="secondPokemon">
            <img src="<?php echo pokeAPISprites(getEvolutionChain($input)[1]) ?>" alt="">
        </div>
        <div id="thirdPokemon">
            <img src="<?php echo pokeAPISprites(getEvolutionChain($input)[2]) ?>" alt="">
        </div>
    </div>
    <div id="pokeMoves">
        <div id="move1"><?php echo getMoves($input)[0] ?></div>
        <div id="move2"><?php echo getMoves($input)[1] ?></div>
        <div id="move3"><?php echo getMoves($input)[2] ?></div>
        <div id="move4"><?php echo getMoves($input)[3] ?></div>
    </div>
</div>
</body>
</html>
