<?php
$data = json_decode(file_get_contents('php://input'), true);
$file = 'scores.json';

// On récupère les scores existants
$scores = json_decode(file_get_contents($file), true);

// On vérifie si le pseudo existe déjà
$found = false;
foreach ($scores as &$entry) {
    if ($entry['pseudo'] === $data['pseudo']) {
        if ($data['score'] > $entry['score']) {
            $entry['score'] = $data['score']; // met à jour uniquement si meilleur score
        }
        $found = true;
        break;
    }
}
if (!$found) {
    $scores[] = $data; // ajoute un nouveau joueur
}

// On sauvegarde
file_put_contents($file, json_encode($scores));
echo json_encode(['status' => 'ok']);
?>
