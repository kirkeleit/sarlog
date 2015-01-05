<?php

class LoggLinje
{
	public $LoggID;
	public $DatoRegistrert;
	public $DatoMelding;
	public $Melding;
	
	function __construct($LoggID, $Melding = null, $DatoMelding = null)
	{
		$now = new DateTime();
		$this->LoggID = $LoggID;
		$this->Melding = $Melding ? $Melding : "Opprettet logg ".$this->LoggID;
		$this->DatoMelding = $DatoMelding ? $DatoMelding : $now;
		$this->DatoRegistrert = $now;
	}
}