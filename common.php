<?php

require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'services'.DIRECTORY_SEPARATOR.'dba.php';

/**
 * @return int
 */
function getEventId() {

	$current = date('Y-m-d');

	$dba = new dba();
	$dba->dba();
	$pdo = $dba->_db;

	$stmt = $pdo->prepare('SELECT
	  	evnt_id as id
		FROM bdnet_events
		WHERE evnt_firstday <= :date AND :date <= evnt_firstday
	');

	$stmt->execute(array(':date' => $current));

	$evtId = $stmt->fetch();

	return $evtId['id'];
}

function getTeams($eventId) {

	$dba = new dba();
	$dba->dba();
	$pdo = $dba->_db;

	$stmt = $pdo->prepare('SELECT
		team_id as id,
	  	team_name as name,
	  	team_stamp as abbr
		FROM bdnet_teams
		WHERE team_eventId = :eventId
		ORDER BY abbr ASC
	');

	$stmt->execute(array(
		':eventId' => $eventId,
	));

	return $stmt->fetchAll();
}

/**
 * @param array $teams from getTeams
 * @param string $teamAbbr
 */
function findTeam(array $teams, $teamAbbr) {
	foreach ($teams as $team) {
		if ($team['abbr'] === $teamAbbr) {
			return $team;
		}
	}

	return false;
}

function getPlayers($teamId) {

	$dba = new dba();
	$dba->dba();
	$pdo = $dba->_db;

	$stmt = $pdo->prepare('SELECT
		regi_memberId as id,
	  	regi_longName as name
		FROM bdnet_registration
		WHERE regi_teamId = :teamId
		ORDER BY name ASC
	');

	$stmt->execute(array(':teamId' => $teamId));

	return $stmt->fetchAll();
}

function getMatchs($teamId) {
	$dba = new dba();
	$dba->dba();
	$pdo = $dba->_db;

	$stmt = $pdo->prepare('SELECT
		m.mtch_id as id,
	  	m.mtch_num as num,
	  	m.mtch_begin as begin,
	  	m.mtch_end as end,
	  	m.mtch_score as score,
	  	m.mtch_court as court,
	  	r.regi_memberId as player_id,
	  	r.regi_longName as player_name,
	  	t.tie_schedule as schedule,
	  	d.draw_stamp as draw,
	  	p2m.p2m_result as won

	  	FROM bdnet_matchs m
	  	INNER JOIN bdnet_p2m p2m ON p2m.p2m_matchId = m.mtch_id
		INNER JOIN bdnet_i2p i2p ON i2p.i2p_pairId = p2m.p2m_pairId
		INNER JOIN bdnet_registration r ON r.regi_id = i2p.i2p_regiId
		INNER JOIN bdnet_ties t ON t.tie_id = m.mtch_tieId
		INNER JOIN bdnet_rounds rnd ON rnd.rund_id = t.tie_roundId
		INNER JOIN bdnet_draws d ON d.draw_id = rnd.rund_drawId
		WHERE r.regi_teamId = :teamId
		ORDER BY num DESC
	');

	$stmt->execute(array(':teamId' => $teamId));

	$rawMatchs = $stmt->fetchAll();

	$matchs = array();

	foreach ($rawMatchs as $m) {
		$k = 'm-'.$m['num'];
		if (!array_key_exists($k, $matchs)) {
			$matchs[$k] = array(
				'num' => $m['num'],
				'draw' => $m['draw'],
				'court' => $m['court'],
				'schedule' => formatDate($m['schedule']),
				'begin' => formatDate($m['begin']),
				'end' => formatDate($m['end']),
				'score' => $m['score'],
				'players' => array(),
			);
		}

		$matchs[$k]['players'][] = array(
			'id' => $m['player_id'],
			'name' => $m['player_name'],
			'won' => $m['won'],
		);
	}

	return $matchs;
}

function getLastMatch() {

	$dba = new dba();
	$dba->dba();
	$pdo = $dba->_db;

	$stmt = $pdo->prepare('SELECT
	  	mtch_num AS num,
		mtch_court as court
		FROM bdnet_matchs
		ORDER BY mtch_begin DESC
		LIMIT 1
	');

	$stmt->execute();

	return $stmt->fetch();
}



// ------ TOOLS ------ \\
function formatDate($date) {

	if (!$date || $date < date('Y-m-d')) {
		return false;
	}

	$d = DateTime::createFromFormat('Y-m-d H:i:s', $date);

	return array(
		'time' => $d->format('H:i'),
		'date' => $d->format('d/m'),
	);
}