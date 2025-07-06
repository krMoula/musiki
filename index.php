<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$conn = new mysqli(
  $_ENV['DB_HOST'],
  $_ENV['DB_USER'],
  $_ENV['DB_PASS'],
  $_ENV['DB_NAME']
);
if ($conn->connect_error) {
  die("Erreur de connexion : " . $conn->connect_error);
}

// Récupération des derniers alts
$stmt = $conn->prepare("SELECT id, name, cover_url, token_symbol FROM musiki_alts ORDER BY release_date DESC LIMIT 3");
$stmt->execute();
$result = $stmt->get_result();
$alts = $result->fetch_all(MYSQLI_ASSOC);

// Infos depuis GeckoTerminal (remplace par une vraie API si possible)
$apiUrl = "https://api.geckoterminal.com/api/v2/networks/solana/tokens/HoVmecXUj1x2rv9tvDyTrRq2VDVsncniL3xzZiNzpump";
$response = @file_get_contents($apiUrl);
$pump = $response ? json_decode($response, true) : null;
$tokenInfo = $pump['data']['attributes'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>BeatChain - Musiki</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/assets/css/style.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #0e0e0e;
      color: #fff;
    }
    .top-bar {
      background: #1c1c1c;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
      border-bottom: 1px solid #333;
    }
    .top-bar img {
      width: 32px;
      height: 32px;
    }
    .token-info {
      font-size: 14px;
      text-align: center;
    }
    .social-icons a {
      margin-left: 15px;
    }
    .social-icons img {
      width: 22px;
      height: 22px;
    }
    header {
      text-align: center;
      padding: 30px 10px;
    }
    .alt-grid {
      display: flex;
      justify-content: center;
      gap: 20px;
      flex-wrap: wrap;
      padding: 20px;
    }
    .alt-card {
      background: #191919;
      border-radius: 10px;
      padding: 15px;
      width: 200px;
      text-align: center;
      transition: transform 0.2s;
    }
    .alt-card:hover {
      transform: translateY(-5px);
    }
    .alt-card img {
      width: 100%;
      border-radius: 8px;
    }
    footer {
      text-align: center;
      padding: 20px;
      background: #111;
      font-size: 13px;
    }
  </style>
</head>
<body>
  <div class="top-bar">
    <img src="/assets/icons/pumpfun.svg" alt="Pumpfun">
    <?php if ($tokenInfo): ?>
      <div class="token-info">
        <strong><?= htmlspecialchars($tokenInfo['symbol'] ?? 'Token') ?></strong><br>
        Prix: <?= htmlspecialchars($tokenInfo['price_usd'] ?? '-') ?> $<br>
        Marketcap: <?= htmlspecialchars($tokenInfo['market_cap_usd'] ?? '-') ?> $
      </div>
    <?php else: ?>
      <div class="token-info">Données indisponibles</div>
    <?php endif; ?>
    <div class="social-icons">
      <a href="https://pump.fun/HoVmecXUj1x2rv9tvDyTrRq2VDVsncniL3xzZiNzpump"><img src="/assets/icons/pumpfun.png" alt="Pumpfun"></a>
      <a href="https://t.me/musikitoken"><img src="/assets/icons/telegram.svg" alt="Telegram"></a>
      <a href="https://twitter.com/musikitoken"><img src="/assets/icons/twitter.svg" alt="Twitter"></a>
      <a href="https://soundcloud.com/musiki"><img src="/assets/icons/soundcloud.svg" alt="SoundCloud"></a>
    </div>
  </div>

  <header>
    <h1>🎵 BeatChain</h1>
    <p>La plateforme musicale tokenisée sur Solana</p>
  </header>

  <main>
    <section>
      <h2 style="text-align:center">🎧 Derniers sons publiés</h2>
      <div class="alt-grid">
        <?php foreach ($alts as $alt): ?>
          <div class="alt-card">
            <img src="<?= htmlspecialchars($alt['cover_url']) ?>" alt="Cover">
            <h3><?= htmlspecialchars($alt['name']) ?></h3>
            <p><?= htmlspecialchars($alt['token_symbol']) ?></p>
            <a href="alt.php?id=<?= $alt['id'] ?>">🔊 Écouter</a>

          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

  <footer>
    &copy; 2025 BeatChain / Musiki. Tous droits réservés.
  </footer>
</body>
</html>