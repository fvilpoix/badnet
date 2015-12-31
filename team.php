<?php

// ------ GET CURRENT EVENT ID ------ \\

require_once __DIR__.DIRECTORY_SEPARATOR.'common.php';

$evtId = getEventId();

if (!$evtId) {
  exit('Pas de tournoi en cours');
}

$evtId = getEventId();

$lastMatch = getLastMatch();

// ------ FETCH ALL AVAILABLES TEAMS ------ \\

$teams = getTeams($evtId);

// ------ PARSE URL PARAMETERS TO KNOW WHAT WE SHOW ------ \\
$team = null;
if (array_key_exists('team', $_GET)) {
  $teamAbbr = $_GET['team'];
  $team = findTeam($teams, $teamAbbr);

  $matchs = getMatchs($team['id']);
  usort($matchs, function($match1, $match2) {
    return $match1['num'] > $match2['num'] ? 1 : -1;
  });

  $matchs_done = array_filter($matchs, function($match) {
    return (bool) $match['score'];
  });

  $matchs_inprogress = array_filter($matchs, function($match) {
    return (bool) $match['begin'] && !$match['end'];
  });
  usort($matchs_inprogress, function($match1, $match2) {
    return $match1['begin']['time'] < $match2['begin']['time'] ? 1 : -1; // DESC
  });

  $matchs_scheduled = array_filter($matchs, function($match) {
    return !$match['begin'];
  });
}

?><!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="refresh" content="30">
  <title>Matchs par club</title>
  <style type="text/css">
  body {
    font-size: 2em;
    font-family: arial;
  }

  h2, h3, h4 {
    margin: 0;
    text-align: center;
  }

  #last-match {
    position: absolute;
    float: left;
    background: #c33;
    padding: 0.1em;
  }

  #team-inprogress, #team-scheduled {
    width: 49%;
    float: left;
  }
  #team-inprogress {
    margin-right: 1%;
  }
  #team-inprogress ul, #team-scheduled ul {
    padding: 0;
    margin-top: 0;
  }
  #team-inprogress li, #team-scheduled li {
    list-style: none;
    border: #000 solid 1px;
    margin: 0.5em 0;
    padding-left: 0.5em;
  }
  .match-draw {
    float: right;
  }
  #team-done {
    border: #000 solid 1px;
    width: 100%;
  }
  #team-done th, #team-done td {
    border: #000 solid 1px;
  }

  .team a {
    text-decoration: none;
  }
  </style>
</head>
<body>
  <div id="content">
  <div id="last-match">
    Dernier: n<sup>o</sup> <?php echo $lastMatch['num'] ?> t<sup>o</sup><?php echo $lastMatch['court'] ?>
  </div>
  
  <?php if ($team): ?>
  <div id="team">
  <h2><?php echo $team['name'] ?></h2>

  <div id="team-inprogress">
    <h3>En cours</h3>
    <ul>
    <?php foreach ($matchs_inprogress as $match): ?>
      <li>
        <span class="match-draw"><?php echo $match['draw'] ?></span>
        n<sup>o</sup><?php echo $match['num'] ?> - t<sup>o</sup><?php echo $match['court'] ?> - <?php echo $match['begin']['time'] ?><br>
        <?php foreach($match['players'] as $pl): ?>
          <?php echo $pl['name'] ?><br>
        <?php endforeach ?>
      </li>
    <?php endforeach ?>
    </ul>
  </div>

  <div id="team-scheduled">
    <h3>A venir</h3>
    <ul>
    <?php foreach ($matchs_scheduled as $match): ?>
      <li>
        <span class="match-draw"><?php echo $match['draw'] ?></span>
        n<sup>o</sup><?php echo $match['num'] ?> - <?php echo $match['schedule']['time'] ?><br>
        <?php foreach($match['players'] as $pl): ?>
          <?php echo $pl['name'] ?><br>
        <?php endforeach ?>
      </li>
    <?php endforeach ?>
    </ul>
  </div>

  <table id="team-done">
  <caption>
    <h3>Matchs terminés</h3>
  </caption>
  <thead>
    <th>N<sup>o</sup></th>
    <th>Joueurs</th>
    <th>Date</th>
    <th>Score</th>
  </thead>
  <tbody>
    <?php foreach($matchs_done as $match): ?>
    <tr>
      <td>
        <?php echo $match['num'] ?><br>
        <?php echo $match['draw'] ?>
      </td>
      <td>
        <?php foreach($match['players'] as $pl): ?>
          <?php echo $pl['name'] ?> <img src="../src/img/icon/<?php echo $pl['won'] ?>.gif"><br>
        <?php endforeach ?>
      </td>
      <td>
        <?php echo $match['begin']['date'] ?><br>
        <?php echo $match['begin']['time'] ?>
        - <?php echo $match['end']['time'] ?>
      </td>
      <td><?php echo $match['score'] ?></td>
    </tr>
    <?php endforeach ?>
  </tbody>
  </table>
  </div>
  <hr>
  <?php endif ?>

  <div id="teams">
    <h2>Equipes</h2>
    <ul class="team">
      <?php foreach ($teams as $t): ?>
        <li>
          <a href="./team.php?team=<?php echo urlencode($t['abbr']) ?>" title="<?php echo $t['name']?>">
            <?php echo $t['abbr']?>
            - <?php echo $t['name']?>
          </a>
        </li>
      <?php endforeach ?>
    </ul>
  </div>


  </div>
</body>
</html>