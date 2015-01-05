<?php

class LoggObject
{
	public $ID;
	public $TypeID;
	public $DatoOpprettet;
	public $Tittel;
	public $Beskrivelse;
	public $Kallesignal;
	
	function __construct($ID = null, $TypeID = null, $DatoOpprettet = null, $Tittel = null, $Beskrivelse = null, $Kallesignal = null) 
	{
		$this->ID = $ID ? $ID : uniqid(rand());
		$this->TypeID = $TypeID;
		$this->DatoOpprettet = $DatoOpprettet ? $DatoOpprettet : new DateTime();
		$this->Tittel = $Tittel ? $Tittel : ""; 
		$this->Beskrivelse = $Beskrivelse ? $Beskrivelse : ""; 
		$this->Kallesignal = $Kallesignal ? $Kallesignal : ""; 
	}
	
	public function start()
	{
		// TODO Implement
	}
	
	public function avslutt()
	{
		// TODO Implement
	}
}
