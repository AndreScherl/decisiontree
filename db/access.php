<?php 
	/**
	 * Definieren den Berechtigungen für die Entscheidungsbaum-Erweiterung
	 * 
	 * @package	mod/decisiontree
	 * @author	Andre Scherl
	 * @version	1.0 - 23.08.2012
	 */

	$capabilities = array(
		'mod/decisiontree:addinstance' => array(
			'captype'		=> 'write',
			'contextlevel'	=> CONTEXT_MODULE,
			'legacy'		=> array(
								// Standardrolle von Moodle
								'guest'					=> CAP_PREVENT,
								'student'				=> CAP_PREVENT,
								'teacher'				=> CAP_PREVENT,
								'editingteacher'		=> CAP_ALLOW,
								'coursecreator'			=> CAP_ALLOW,
								'admin'					=> CAP_ALLOW
								)
		)
	);

?>