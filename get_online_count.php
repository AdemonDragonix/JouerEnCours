<?php
session_start();
$sessionId = session_id();
$timestamp = time();
$timeout = 60; // utilisateurs actifs dans les 60 dernières secondes

$pdo = new PDO('mysql:host=localhost;dbname=ton_jeu;charset=utf8', 'user', 'password');

// Crée la table si elle n'existe pas
$pdo->exec("CREATE TABLE IF NOT EXISTS sessions (
  id VARCHAR(255) PRIMARY KEY,
  last_seen INT
)");

$pdo->prepare("REPLACE INTO sessions (id, last_seen) VALUES (?, ?)")
    ->execute([$sessionId, $timestamp]);

$minTime = $timestamp - $timeout;
$stmt = $pdo->prepare("SELECT COUNT(*) FROM sessions WHERE last_seen > ?");
$stmt->execute([$minTime]);

echo json_encode(['count' => (int) $stmt->fetchColumn()]);
