<?php
declare(strict_types = 1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
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
    if (isset($data['sprites']['front_default'])){
        return $data['sprites']['front_default'];
    } else {
        return "";
    }
}

function getEvolutionChain(string $inputValue): array
{
    $responseForChain = file_get_contents('https://pokeapi.co/api/v2/pokemon-species/' . $inputValue);
    $dataForChain = json_decode($responseForChain, true);
    $evolutionChainUrl = $dataForChain['evolution_chain']['url'];
    $responseChain = file_get_contents($evolutionChainUrl);
    $dataChain = json_decode($responseChain, true);
    $firstEvolution = $dataChain['chain']['species']['name'];

    if (isset($firstEvolution)){
        if (isset($dataChain['chain']['evolves_to'][0]['species']['name'])) {
            $secondEvolution = $dataChain['chain']['evolves_to'][0]['species']['name'];
            if (isset($dataChain['chain']['evolves_to'][0]['evolves_to'][0]['species']['name'])){
                $thirdEvolution = $dataChain['chain']['evolves_to'][0]['evolves_to'][0]['species']['name'];
                $evolutions = array();
                array_push($evolutions, $firstEvolution, $secondEvolution, $thirdEvolution);
                return $evolutions;
            } else {
                $thirdEvolution = "";
                $evolutions = array();
                array_push($evolutions, $firstEvolution, $secondEvolution, $thirdEvolution);
                return $evolutions;
            }
        } else {
            $secondEvolution = "";
            $thirdEvolution = "";
            $evolutions = array();
            array_push($evolutions, $firstEvolution, $secondEvolution, $thirdEvolution);
            return $evolutions;
        }
    }   array_push($evolutions, "","","");
        return $evolutions;
}

function hasMultipleEvolutions (string $inputValue) : array {
    $responseForChain = file_get_contents('https://pokeapi.co/api/v2/pokemon-species/' . $inputValue);
    $dataForChain = json_decode($responseForChain, true);
    $evolutionChainUrl = $dataForChain['evolution_chain']['url'];
    $responseChain = file_get_contents($evolutionChainUrl);
    $dataChain = json_decode($responseChain, true);
    $evolutions = [];
    foreach ($dataChain['chain']['evolves_to'] as $evol){
        array_push($evolutions, $evol['species']['name']);
    }
    $NUMBEROFMAXEVOLUTIONS = 8;
    for ($i = count($dataChain['chain']['evolves_to']); $i < $NUMBEROFMAXEVOLUTIONS; $i++){
        array_push($evolutions, "");
    }
    return $evolutions;
}

function getMoves(string $pokemonInput): array
{
    $response = file_get_contents("https://pokeapi.co/api/v2/pokemon/" . $pokemonInput);
    $data = json_decode($response, true);
    $moves = array();
    $NUMBEROFMOVESIWANT = 4;
    for ($i = 0; $i < count($data['moves']); $i++) {
        array_push($moves, $data['moves'][$i]['move']['name']);
    }
    for ($i = count($data['moves']); $i < $NUMBEROFMOVESIWANT; $i++){
        array_push($moves, "");
    }
    return $moves;
}

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
        <div id="moveTitle">MOVES</div>
        <div id="move1"><?php echo getMoves($input)[0] ?></div>
        <div id="move2"><?php echo getMoves($input)[1] ?></div>
        <div id="move3"><?php echo getMoves($input)[2] ?></div>
        <div id="move4"><?php echo getMoves($input)[3] ?></div>
    </div>
    <div id="eeveeLutions">
        <div id="eevee1"><img src="<?php  echo pokeAPISprites(hasMultipleEvolutions($input)[1])?>" ></div>
        <div id="eevee2"><img src="<?php  echo pokeAPISprites(hasMultipleEvolutions($input)[2])?>" ></div>
        <div id="eevee3"><img src="<?php  echo pokeAPISprites(hasMultipleEvolutions($input)[3])?>" ></div>
        <div id="eevee4"><img src="<?php  echo pokeAPISprites(hasMultipleEvolutions($input)[4])?>" ></div>
        <div id="eevee5"><img src="<?php  echo pokeAPISprites(hasMultipleEvolutions($input)[5])?>" ></div>
        <div id="eevee6"><img src="<?php  echo pokeAPISprites(hasMultipleEvolutions($input)[6])?>" ></div>
        <div id="eevee7"><img src="<?php  echo pokeAPISprites(hasMultipleEvolutions($input)[7])?>" ></div>
    </div>
</div>
</body>
</html>
