
# Install

## Alias

Ajouter la ligne `127.0.0.1 badnet.dev` dans `/etc/hosts`.

## Apache Virtual host

	<VirtualHost *:80>

		DocumentRoot		PATHTOBADNET/badnet
		ServerName			badnet.dev

		ErrorLog			"PATHTOLOGS/logs/badnet.dev-err.log"
		CustomLog			"PATHTOLOGS/logs/badnet.dev-access.log" combined

		<Directory "/Users/FVilpoix/Perso/badnet">
			Options Indexes FollowSymLinks
			AllowOverride All
			Require all granted
		</Directory>

	</VirtualHost>

Redémarrer apache

## Modification des droits sur les fichiers

	$ cd badnet
	$ chmod -R +rx .
	$ chmod -R +w archive cache Conf data export log pdf sessions Temp tmp

## Modifiaction de fichiers source

Dans le fichier `src/index.php`, ligne 8, remplacer `error_reporting(E_ALL);` par `error_reporting(E_ALL^E_STRICT);`.

## Let's play !

Rendez-vous sur [http://badnet.dev](http://badnet.dev).

## MySQL modifications

Si le tournoi ne s'enregistre pas (sans erreur apparante), et que les fichiers de logs (situés dans PATHTOBADNET/Temp/LOG) indiquent qu'un champ MySQL n'accepte pas de valeur par défaut, il faut alors rajouter le support de NULL aux champs suivants :

* bdnet_events
	* evnt_textconvoc
	* evnt_urlrank
	* evnt_dpt
	* evnt_teamweight
* bdnet_eventsextra
	* evxt_fedeid
	* evxt_email
	* evxt_promoimg

Puis pour l'import de sauvegarde :

* bdnet_draws
	* draw_defmin
	* draw_defmax
	* draw_defstate
	* draw_maxpair
* bdnet_ties
	* tie_convoc
	* tie_place
	* tie_name
	* tie_step
	* tie_round
* bdnet_matchs
	* mtch_disci
	* mtch_catage
	* mtch_unild
* bdnet_assocs
	* asso_name
	* asso_stamp
	* asso_pseudo
* bdnet_teams
	* team_name
	* team_captain
	* team_captainid
	* team_stamp
	* team_textconvoc
	* team_uniId
* bdnet_members
	* mber_secondname
	* mber_uniId
* bdnet_registration
	* regi_longName
	* regi_shortName
	* regi_arrcmt
	* regi_depcmt
	* regi_prise
	* regi_transportcmt
	* regi_function
	* regi_uniId
	* regi_convocplace
* bdnet_i2p
	* i2p_rankdefid
* bdnet_postit
	* psit_title
	* psit_texte
	* psit_page

Pour la compatibilité entre la 2.97 disponible en téléchargement et les exports du site :

	ALTER TABLE bdnet_accounts ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_draws  ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_rounds  ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_ties  ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_matchs  ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_assocs ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_teams ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_a2t ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_members ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_registration ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_ranks   ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_pairs   ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_i2p ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_t2r ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_p2m ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_items ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_commands ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_postits  ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;

	ALTER TABLE bdnet_database   ADD `_cre` datetime DEFAULT NULL, ADD `_updt` datetime DEFAULT NULL, ADD `_pbl` TEXT DEFAULT NULL, ADD `_uniId` TEXT DEFAULT NULL, ADD `_id` TEXT DEFAULT NULL;